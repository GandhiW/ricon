// =======================
// notifications.js
// =======================

document.addEventListener('DOMContentLoaded', () => {
    const bell = document.getElementById('notificationBell');
    const dropdown = document.getElementById('notifList');

    if (!bell || !dropdown) return;

    // Toggle dropdown
    bell.addEventListener('click', (e) => {
        e.preventDefault();
        dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
    });

    // Load pertama kali
    if (localStorage.getItem('token')) loadNotifications();

    // Auto-refresh setiap 30 detik
    setInterval(() => {
        if (localStorage.getItem('token')) loadNotifications();
    }, 30000);
});

// =======================
// Auth header
// =======================
function authHeaders() {
    return {
        'Authorization': 'Bearer ' + localStorage.getItem('token'),
        'Accept': 'application/json'
    };
}

// =======================
// Load notif list
// =======================
function loadNotifications() {
    fetch('/api/notifications', { headers: authHeaders() })
        .then(res => res.json())
        .then(renderNotifications)
        .catch(() => {
            const list = document.getElementById('notifList');
            if (list) list.innerHTML = `<div class="p-3 text-danger text-center">Failed load notifications</div>`;
        });
}

// =======================
// Render notif list + badge
// =======================
function renderNotifications(data) {
    const list = document.getElementById('notifList');
    const badge = document.getElementById('notifBadge');
    if (!list || !badge) return;

    list.innerHTML = '';

    const unreadCount = data.filter(n => !n.is_read).length;
    badge.textContent = unreadCount;
    badge.classList.toggle('d-none', unreadCount === 0);

    if (data.length === 0) {
        list.innerHTML = `<div class="p-3 text-muted text-center">No notifications</div>`;
        return;
    }

    data.forEach(n => {
        // Kirim title ke onclick supaya bisa filter "telah masuk"
        list.insertAdjacentHTML('beforeend', `
            <div class="p-3 border-bottom notif-item ${n.is_read ? 'read' : 'unread'}"
                 style="cursor:pointer"
                 onclick="openNotification(${n.id}, '${n.title}')">
                <div class="fw-semibold ${n.is_read ? '' : 'text-primary'}">${n.title}</div>
                <small class="text-muted">${new Date(n.created_at).toLocaleString()}</small>
            </div>
        `);
    });
}

// =======================
// Open notif detail + mark read
// =======================
function openNotification(id, title) {
    if (!title.includes('telah masuk')) {
        return;
    }

    fetch(`/api/notifications/${id}`, { headers: authHeaders() })
        .then(res => res.json())
        .then(data => {
            showNotificationDetail(data);
            return markAsRead(id);
        })
        .then(() => loadNotifications());
}

function markAsRead(id) {
    return fetch(`/api/notifications/${id}/read`, { method:'PATCH', headers: authHeaders() });
}

// =======================
// Show modal + generate receipt image
// =======================
function showNotificationDetail(n) {
    if (!n.item) return;

    const modalBody = document.getElementById('notifModalBody');

    modalBody.innerHTML = `
        <div id="receiptContainer" style="padding:20px; background:#fff; border-radius:10px; box-shadow:0 5px 15px rgba(0,0,0,0.2); font-family:Segoe UI;">
            <h5>${n.title}</h5>
            <small>${new Date(n.created_at).toLocaleString()}</small>
            <hr>
            <p><b>Item:</b> ${n.item.name}</p>
            ${n.item.detail ? `<p>Detail: ${n.item.detail}</p>` : ''}
            <p>Added at: ${new Date(n.item.added_at).toLocaleString()}</p>
        </div>
        <button id="downloadReceipt" class="btn btn-sm btn-primary mt-3">
            <i class="bi bi-download"></i> Download Receipt
        </button>
    `;

    // Tampilkan modal
    new bootstrap.Modal(document.getElementById('notifModal')).show();

    // Tombol download -> generate image & auto download
    const downloadBtn = document.getElementById('downloadReceipt');
    downloadBtn.onclick = () => {
        const container = document.getElementById('receiptContainer');
        html2canvas(container).then(canvas => {
            const link = document.createElement('a');
            link.href = canvas.toDataURL('image/png');
            link.download = `item-receipt-${n.id}.png`;
            link.click();
        });
    };
}

// =======================
// Global export
// =======================
window.openNotification = openNotification;
