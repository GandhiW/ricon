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

    // Load pertama
    loadNotifications();

    // Auto-refresh tiap 30 detik
    setInterval(() => loadNotifications(), 30000);
});

// =======================
// Load notif list
// =======================
function loadNotifications() {
    fetch('/notifications')
        .then(res => res.json())
        .then(renderNotifications)
        .catch(() => {
            const list = document.getElementById('notifList');
            if (list)
                list.innerHTML = `<div class="p-3 text-danger text-center">Failed load notifications</div>`;
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
        // notif yang bisa dibuka detail = "barang telah masuk"
        const canOpen = n.title.includes('telah masuk');

        const markIcon = n.is_read ? '' : `<span class="mark-read ms-2" style="cursor:pointer;">✔</span>`;

        list.insertAdjacentHTML('beforeend', `
            <div class="p-3 border-bottom notif-item d-flex justify-content-between align-items-center
                ${n.is_read ? 'read' : 'unread'}" data-id="${n.id}">

                <div class="notif-content"
                     style="cursor:${canOpen ? 'pointer' : 'default'};"
                     ${canOpen ? `onclick="openNotification(${n.id})"` : ''}>

                    <div class="fw-semibold ${!n.is_read ? 'text-primary' : ''}">
                        ${n.title}
                    </div>
                    <small class="text-muted d-block">
                        ${new Date(n.created_at).toLocaleString()}
                    </small>
                </div>

                ${markIcon}
            </div>
        `);
    });

    // Mark-as-read
    document.querySelectorAll('.mark-read').forEach(el => {
        el.addEventListener('click', (e) => {
            e.stopPropagation();

            const notifElem = e.target.closest('.notif-item');
            const id = notifElem.dataset.id;

            if (!id) return;

            markAsRead(id).then(() => {
                notifElem.classList.remove('unread');
                notifElem.classList.add('read');
                notifElem.querySelector('.fw-semibold').classList.remove('text-primary');
                e.target.remove();
            });
        });
    });
}

// =======================
// Open notif detail
// =======================
function openNotification(id) {
    fetch(`/notifications/${id}`)
        .then(res => res.json())
        .then(data => showNotificationDetail(data))
        .then(() => markAsRead(id));
}

function markAsRead(id) {
    return fetch(`/notifications/${id}/read`, {
        method: 'PATCH',
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
        },
        credentials: "same-origin"
    });
}

// =======================
// Show modal + receipt
// =======================
function showNotificationDetail(n) {
    const modalBody = document.getElementById('notifModalBody');
    modalBody.innerHTML = '';

    // jika notification tidak punya detail item
    if (!n.item || !n.item.name) {
        modalBody.innerHTML = `<p class="text-muted">No detailed information for this notification.</p>`;
    } else {
        modalBody.innerHTML = `
            <div id="receiptContainer"
                style="padding:20px; background:#fff; border-radius:10px; box-shadow:0 5px 15px rgba(0,0,0,0.2); font-family:Segoe UI;">

                <h5>${n.title}</h5>
                <small>${new Date(n.created_at).toLocaleString()}</small>
                <hr>

                <p><b>Item:</b> ${n.item.name}</p>
                ${n.item.detail ? `<p><b>Detail:</b> ${n.item.detail}</p>` : ''}
                <p><b>Added at:</b> ${new Date(n.item.added_at).toLocaleString()}</p>
            </div>

            <button id="downloadReceipt" class="btn btn-sm btn-primary mt-3">
                <i class="bi bi-download"></i> Download Receipt
            </button>
        `;

        // download receipt
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

    new bootstrap.Modal(document.getElementById('notifModal')).show();
}

// Global export
window.openNotification = openNotification;
