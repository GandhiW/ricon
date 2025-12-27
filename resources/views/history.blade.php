@extends('layouts.app')

@section('title', 'History')

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>

    <script>
        document.addEventListener('click', function(e) {
            if (!e.target.classList.contains('detail-btn')) return;

            const btn = e.target;

            const data = {
                title: btn.dataset.title,
                created_at: btn.dataset.created,
                locker: btn.dataset.locker,
                item: {
                    id: btn.dataset.itemId,
                    name: btn.dataset.itemName,
                    detail: btn.dataset.itemDetail,
                    added_at: btn.dataset.itemAdded,
                }
            };

            showNotificationDetail(data);
        });

        function showNotificationDetail(n) {
            const modalBody = document.getElementById('notifModalBody');

            modalBody.innerHTML = `
        <div id="receiptContainer"
            style="padding:20px;background:#fff;border-radius:10px;
                   box-shadow:0 5px 15px rgba(0,0,0,.2);
                   font-family:Segoe UI;">
            <h5>${n.title}</h5>
            <small>${new Date(n.created_at).toLocaleString()}</small>
            <hr>
            <p><b>Locker:</b> ${n.locker}</p>
            <p><b>Item:</b> ${n.item.name}</p>
            ${n.item.detail ? `<p>Detail: ${n.item.detail}</p>` : ''}
            <p>Added at: ${new Date(n.item.added_at).toLocaleString()}</p>
        </div>

        <button class="btn btn-sm btn-primary mt-3" id="downloadReceipt">
            Download Receipt
        </button>
    `;

            document.getElementById('downloadReceipt').onclick = () => {
                html2canvas(document.getElementById('receiptContainer')).then(canvas => {
                    const link = document.createElement('a');
                    link.href = canvas.toDataURL('image/png');
                    link.download = `locker-item-${n.item.id}.png`;
                    link.click();
                });
            };

            new bootstrap.Modal(document.getElementById('notifModal')).show();
        }
    </script>
@endpush

@push('styles')
    <style>
        .table-wrapper {
            background: #ffffff;
            border-radius: 14px;
            padding: 16px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
            margin-top: 20px;
        }

        .table {
            margin-bottom: 0;
            border-collapse: separate;
            border-spacing: 0;
        }

        .table thead th {
            background: #f8f9fa;
            color: #212529;
            font-weight: 600;
            border-bottom: 2px solid #dee2e6;
        }

        .table tbody td {
            vertical-align: middle;
            background: #ffffff;
        }

        .table tbody tr:hover td {
            background: #f5f7fa;
        }

        /* Rounded corners */
        .table thead tr th:first-child {
            border-top-left-radius: 12px;
        }

        .table thead tr th:last-child {
            border-top-right-radius: 12px;
        }

        .table tbody tr:last-child td:first-child {
            border-bottom-left-radius: 12px;
        }

        .table tbody tr:last-child td:last-child {
            border-bottom-right-radius: 12px;
        }

        .badge {
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 0.8rem;
        }

        .btn-outline-dark {
            border-radius: 8px;
        }

        /* FORCE TEXT COLOR FOR HISTORY TABLE */
        .table-wrapper,
        .table-wrapper table,
        .table-wrapper th,
        .table-wrapper td {
            color: #212529 !important;
            /* dark text */
        }

        /* Header text */
        .table-wrapper thead th {
            color: #212529 !important;
        }

        /* Detail button outline fix */
        .table-wrapper .btn-outline-dark {
            border-color: #6c757d;
        }
    </style>
@endpush

@section('content')
    <div class="hero d-flex justify-content-between align-items-center">
        <div>
            <h1 class="fw-bold text-white">Riwayat</h1>
        </div>
    </div>

    @if ($bookings->isEmpty())
        <div class="alert alert-warning">
            Tidak ada riwayat pemesanan loker
        </div>
    @else
        <div class="table-wrapper">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Booked At</th>
                        <th>Locker</th>
                        <th>Status</th>
                        <th>Nama Barang</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bookings as $booking)
                        @php
                            $items = $booking->items;
                            $rowspan = max($items->count(), 1);
                        @endphp

                        @forelse ($items as $index => $item)
                            <tr>
                                @if ($index === 0)
                                    <td rowspan="{{ $rowspan }}">{{ $loop->parent->iteration }}</td>
                                    <td rowspan="{{ $rowspan }}">
                                        {{ $booking->created_at->format('d M Y H:i') }}
                                    </td>
                                    <td rowspan="{{ $rowspan }}">
                                        Locker {{ $booking->locker_id }}
                                    </td>
                                    <td rowspan="{{ $rowspan }}">
                                        <span class="badge bg-{{ $booking->status === 'done' ? 'success' : 'danger' }}">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </td>
                                @endif

                                <td>{{ $item->item_name }}</td>

                                <td>
                                    <button class="btn btn-sm btn-outline-dark detail-btn" data-title="Locker Receipt"
                                        data-created="{{ $booking->created_at }}"
                                        data-locker="Locker {{ $booking->locker_id }}" data-item-id="{{ $item->id }}"
                                        data-item-name="{{ $item->item_name }}" data-item-detail="{{ $item->detail }}"
                                        data-item-added="{{ $item->created_at }}">
                                        Detail
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $booking->created_at->format('d M Y H:i') }}</td>
                                <td>Locker {{ $booking->locker_id }}</td>
                                <td>
                                    <span class="badge bg-secondary">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                        @endforelse
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- MODAL --}}
        <div class="modal fade" id="notifModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Item Detail</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" id="notifModalBody"></div>
                </div>
            </div>
        </div>
    @endif
@endsection
