@extends('layouts.app')

@section('title', 'Sewa Loker')

@push('styles')
    <style>
        .btn.btn-blue {
            background-color: #22396C;
            color: #ffffff;
            border: none;
            border-radius: 18px;
            padding: 14px 32px;
            font-size: 20px;
            font-weight: 600;
            width: 100%;
            transition: all 0.25s ease;
        }

        /* HOVER */
        .btn.btn-blue:hover:not(:disabled) {
            filter: brightness(1.05);
            transform: translateY(-1px);
        }

        /* DISABLED */
        .btn.btn-blue:disabled {
            background-color: #22396C !important;
            color: #ffffff !important;
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* SAAT DIKLIK (ACTIVE) */
        .btn.btn-blue:active,
        .btn.btn-blue.active,
        .btn.btn-blue:focus,
        .btn.btn-blue:focus-visible {
            background-color: #22396C !important;
            color: #ffffff !important;
            box-shadow: none !important;
            outline: none !important;
            transform: none;
        }

        .card .form-control::placeholder {
            color: rgba(0, 0, 0, 0.4);
        }

        .locker-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, 90px);
            gap: 16px;
            width: 100%;
        }

        .locker {
            width: 90px;
            height: 90px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            font-weight: 700;
            cursor: pointer;
            user-select: none;
        }

        .locker.available {
            background: #4CAF50;
            color: #0b2d5c;
        }

        .locker.not-available {
            background: #D9534F;
            color: #0b2d5c;
            cursor: not-allowed;
            opacity: 0.75;
        }

        .locker.selected {
            outline: 4px solid #0b2d5c;
        }

        .locker-legend {
            display: flex;
            gap: 16px;
            margin-top: 16px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 14px;
        }

        .legend-box {
            width: 16px;
            height: 16px;
            border-radius: 4px;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const lockers = document.querySelectorAll('.locker.available');
            const lockerInput = document.getElementById('locker_id');

            lockers.forEach(locker => {
                locker.addEventListener('click', function() {
                    lockers.forEach(l => l.classList.remove('selected'));
                    this.classList.add('selected');
                    lockerInput.value = this.dataset.id;
                });
            });
        });
    </script>

    {{-- validasi form required --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const lockerInput = document.getElementById('locker_id');
            const itemName = document.querySelector('input[name="item_name"]');
            const itemDetail = document.querySelector('input[name="item_detail"]');
            const submitBtn = document.getElementById('submitBtn');

            function validateForm() {
                if (
                    lockerInput.value &&
                    itemName.value.trim() !== '' &&
                    itemDetail.value.trim() !== ''
                ) {
                    submitBtn.disabled = false;
                } else {
                    submitBtn.disabled = true;
                }
            }

            // cek saat input diketik
            itemName.addEventListener('input', validateForm);
            itemDetail.addEventListener('input', validateForm);

            // cek saat locker dipilih
            document.querySelectorAll('.locker.available').forEach(locker => {
                locker.addEventListener('click', validateForm);
            });
        });
    </script>

@endpush

@section('content')
    <div class="container">

        <div class="hero d-flex justify-content-between align-items-center mb-2">
            <div>
                <h1 class="fw-bold text-white">Sewa Loker</h1>
            </div>
        </div>


        <form method="POST" action="{{ route('booking.store') }}">
            @csrf

            {{-- PILIH LOKER --}}

            <div class="card p-4 mb-4">
                <h5 class="mb-3 fw-semibold">Pilih Loker</h5>

                <div class="locker-wrapper">
                    <div class="locker-grid">

                        @foreach ($lockers as $locker)
                            <div class="locker {{ $locker->status === 'available' ? 'available' : 'not-available' }}"
                                data-id="{{ $locker->id }}" data-status="{{ $locker->status }}">
                                {{ $locker->id }}
                            </div>
                        @endforeach
                    </div>

                    <div class="locker-legend">
                        <div class="legend-item">
                            <span class="legend-box" style="background:#4CAF50"></span>
                            <span>Tersedia</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-box" style="background:#D9534F"></span>
                            <span>Tidak Tersedia</span>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="locker_id" id="locker_id" required>
            </div>



            {{-- INFORMASI ITEM --}}
            <div class="card p-4 mb-4">
                <h5 class="mb-3 fw-semibold">Informasi Barang</h5>

                <div class="mb-3">
                    <label class="form-label">Nama Barang</label>
                    <input type="text" name="item_name" class="form-control" placeholder="Contoh: Nasi ayam" required>
                </div>

                <div>
                    <label class="form-label">Detail Barang</label>
                    <input type="text" name="item_detail" class="form-control"
                        placeholder="Contoh: Ayam gembus pak gepuk 2 porsi" required>
                </div>
            </div>

            {{-- CATATAN --}}
            <div class="card p-4 mb-4">
                <h5 class="mb-3 fw-semibold">Catatan</h5>
                <ul class="mb-0">
                    <li>Jika loker tidak terisi dalam waktu 2 jam, maka sewa loker akan otomatis selesai.</li>
                    <li>Loker hanya dapat diakses menggunakan QR Code atau Face Recognition oleh pengguna yang terdaftar.
                    </li>
                </ul>
            </div>

            {{-- SUBMIT --}}
            <div class="text-center">
                <button
                    type="submit"
                    id="submitBtn"
                    class="btn btn-blue px-5 py-2 fw-semibold"
                    disabled
                >
                    Konfirmasi & Sewa
                </button>
            </div>

        </form>

    </div>
@endsection
