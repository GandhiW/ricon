import cv2
import numpy as np
import json
import os
import mysql.connector
import qrcode
import uuid
from flask import Flask, request, jsonify
from flask_cors import CORS
from insightface.app import FaceAnalysis
from scipy.spatial.distance import cosine

app = Flask(__name__)
CORS(app)

# --- 1. CONFIGURATION ---
db_config = {
    'host': '127.0.0.1',
    'user': 'root',
    'password': '',
    'database': 'ricon'
}

# --- 2. INITIALIZE MODELS ---
app_model = FaceAnalysis(name='buffalo_l', providers=['CPUExecutionProvider'])
app_model.prepare(ctx_id=0, det_size=(640, 640))
qr_detector = cv2.QRCodeDetector()

def get_db_connection():
    return mysql.connector.connect(**db_config)

QR_STORAGE_PATH = os.path.join(os.getcwd(), "public", "qrcodes")
if not os.path.exists(QR_STORAGE_PATH):
    os.makedirs(QR_STORAGE_PATH)

# --- 3. SILENCE & UTILITY ROUTES ---

@app.route('/')
def index():
    """Health check route to prevent 404 when accessing root"""
    return jsonify({"status": "API Active", "model": "buffalo_l"}), 200

@app.route('/favicon.ico')
def favicon():
    """Silence browser favicon requests"""
    return '', 204

# --- 4. FUNCTIONAL ROUTES ---

@app.route('/generate', methods=['GET'])
def generate_qr():
    random_key = str(uuid.uuid4())[:8]
    filename = f"qr_{random_key}.png"
    filepath = os.path.join(QR_STORAGE_PATH, filename)

    img = qrcode.make(random_key)
    img.save(filepath)

    return jsonify({
        "status": "success",
        "key": random_key,
        "qr_path": f"qrcodes/{filename}"
    })

@app.route('/recognize', methods=['POST'])
def recognize():
    if 'images' not in request.files:
        return jsonify({"error": "No images uploaded"}), 400

    file = request.files['images']
    img = cv2.imdecode(np.frombuffer(file.read(), np.uint8), cv2.IMREAD_COLOR)

    conn = get_db_connection()
    cursor = conn.cursor(dictionary=True)

    try:
        # STEP A: QR DETECTION
        qr_data, points, _ = qr_detector.detectAndDecode(img)
        if qr_data:
            cursor.execute("SELECT id, locker_id, opened_by_sender FROM locker_items WHERE `key` = %s", (qr_data,))
            item = cursor.fetchone()

            if item:
                if item['opened_by_sender'] == 1:
                    return jsonify([{"type": "qr_error", "result": "Loker ini sudah pernah dibuka oleh pengirim"}])

                cursor.execute("UPDATE locker_items SET opened_by_sender = 1 WHERE id = %s", (item['id'],))
                conn.commit()
                return jsonify([{"type": "qr_success", "result": "QR Verified", "locker_id": item['locker_id']}])

            return jsonify([{"type": "qr_error", "result": "QR Key Tidak Valid"}])

        # STEP B: FACE RECOGNITION FALLBACK
        best_name, best_id, min_dist = "STRANGER", None, 0.45
        faces = app_model.get(img)

        if faces:
            target_emb = faces[0].normed_embedding
            cursor.execute("SELECT id, name, face_embedding FROM users WHERE face_embedding IS NOT NULL")
            for record in cursor.fetchall():
                db_emb = np.array(json.loads(record['face_embedding']))
                dist = cosine(target_emb, db_emb)
                if dist < min_dist:
                    min_dist, best_name, best_id = dist, record['name'], record['id']

        return jsonify([{"type": "face", "result": best_name, "user_id": best_id}])

    finally:
        cursor.close()
        conn.close()

if __name__ == '__main__':
    # Using 0.0.0.0 allows access from your S25 Ultra if it's on the same Wi-Fi
    app.run(host='0.0.0.0', port=5000, debug=False)
