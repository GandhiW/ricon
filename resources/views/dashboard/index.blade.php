@extends('layouts.app')

@section('title', 'Dashboard')

@push('styles')
    <style>
    /* ===================== */
    /* TYPOGRAPHY */
    /* ===================== */
    .hero h1 {
        font-size: 40px;
        font-weight: 800;
    }

    .hero p {
        font-size: 18px;
        opacity: 0.9;
    }

    .card h5 {
        color: #1f2f5c;
        font-weight: 700;
    }

    .locker-number {
        font-size: 48px;
        font-weight: 800;
        color: #1f2f5c;
    }

    /* ===================== */
    /* BUTTONS */
    /* ===================== */
    .btn-rounded {
        border-radius: 10px;
        padding: 12px 20px;
        font-weight: 600;
        font-size: 15px;
    }

    /* navy button (Add item, manage, assign) */
    .btn-navy {
        background: #1f2f5c;
        color: #fff;
        border: none;
    }

    .btn-navy:hover {
        background: #253b73;
        color: #fff;
    }

    /* gradient primary button */
    .btn-gradient {
        background: linear-gradient(90deg, #1f3c88 0%, #2f8f9d 100%);
        color: #fff;
        border: none;
    }

    .btn-gradient:hover {
        background: linear-gradient(90deg, #1f3c88 0%, #2f8f9d 100%);
        color: #fff !important;
        filter: brightness(1.1);
    }

    /* release locker */
    .btn-release {
        background: #43b36b;
        color: #fff;
        border: none;
    }

    .btn-release:hover {
        background: #3aa160;
        color: #fff;
    }

    /* Menjaga konsistensi tombol saat ditekan */
    .btn:active {
        opacity: 1 !important;
        transform: scale(0.98) !important;
    }
    </style>
@endpush

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" id="autoDismissAlert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- HERO --}}
    <div class="hero d-flex justify-content-between align-items-center">
        <div>
            {{-- Menggunakan nama dinamis dari kode lama --}}
            <h1 class="fw-bold text-white">Hi, {{ Auth::check() ? Auth::user()->name : 'Kadek Artika' }}</h1>
            <p class="fs-5 fst-italic text-white">pesan loker mu sekarang juga!</p>
        </div>

        @if ($booking)
            <button class="btn btn-secondary btn-rounded px-4 btn-gradient" disabled style="opacity: 0.6;">
                Pesan Loker
            </button>
        @else
            <a href="{{ route('booking.index') }}" class="btn btn-gradient btn-rounded px-4">
                Pesan Loker
            </a>
        @endif
    </div>

    {{-- MAIN CONTENT --}}
    <div class="row g-4 mt-3">

        {{-- ACTIVE BOOKINGS --}}
        <div class="col-lg-8">
            <div class="card p-4">
                <h5 class="fw-bold mb-3">Pesanan Aktif:</h5>
                @if ($booking)
                    <div class="row align-items-center">
                        <div class="col-md-4 text-center">
                            <p class="mb-1 fw-semibold">nomor loker:</p>
                            {{-- Mengambil nomor loker dinamis --}}
                            <div class="locker-number">{{ $booking->locker->locker_number ?? '1' }}</div>
                        </div>

                        <div class="col-md-8">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <a href="{{ route('booking.edit', $booking->id) }}"
                                        class="btn btn-navy w-100 btn-rounded">
                                        Tambah Barang Baru
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    {{-- Menggunakan route show untuk Manage Locker sesuai kode baru --}}
                                    <a href="{{ route('booking.show', $booking->id) }}"
                                        class="btn btn-navy w-100 btn-rounded">
                                        Kelola Loker (QR)
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    <a href="{{ route('booking.showAssignUserForm', $booking) }}"
                                       class="btn btn-navy w-100 btn-rounded">
                                        Tugaskan Pengambil
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    {{-- Menggunakan form POST agar fungsi releaseLocker di controller terpanggil --}}
                                    <form action="{{ route('booking.release', $booking) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-release w-100 btn-rounded"
                                                onclick="return confirm('Apakah Anda yakin ingin melepaskan loker?')">
                                            Lepaskan Loker
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-warning mb-0">
                        Anda tidak memiliki pemesanan aktif.
                    </div>
                @endif
            </div>
        </div>

        {{-- LOCKERS AVAILABLE --}}
        <div class="col-lg-4">
            <div class="card p-4 text-center">
                <h5 class="fw-bold">Loker Tersedia:</h5>
                {{-- Variabel dinamis dari kode lama --}}
                <div class="locker-number mt-3">{{ $availableLockerCount ?? '0' }}</div>
            </div>
        </div>

        {{-- INFO CARDS (GABUNGAN) --}}
        {{-- <div class="col-lg-6">
            <div class="card p-4 text-center">
                <h4 class="fw-bold mb-3">Pesan Loker</h4>
                <img src="https://cdn-icons-png.flaticon.com/512/3050/3050525.png" alt="Locker" style="max-width:200px"
                    class="mx-auto">
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card p-4 d-flex align-items-center justify-content-center" style="min-height:240px;">
                 <p class="text-muted italic">Fitur lainnya akan segera hadir.</p>
            </div>
        </div> --}}

    </div>
@endsection
