@extends('layouts.app')

@section('title', 'Sewa Loker')

@section('content')

@push('styles')
    <style>
        .card .form-control::placeholder {
            color: rgba(0, 0, 0, 0.4);
        }

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
    </style>
@endpush

<div class="container">

    {{-- HEADER --}}
    <div class="hero d-flex justify-content-between align-items-center mb-2">
        <div>
            <h1 class="fw-bold text-white">Tambah Barang</h1>
        </div>
    </div>
    <form method="POST" action="{{ route('booking.update', $booking->id) }}">
    @csrf
    @method('PUT')
        {{-- INFORMASI ITEM --}}
        <div class="card p-4 mb-4">
            <h5 class="mb-3 fw-semibold">Informasi Barang</h5>

            <div class="mb-3">
                <label class="form-label">Nama Barang</label>
                <input type="text" name="item_name" class="form-control" placeholder="Contoh: Nasi ayam" required>
            </div>

            <div>
                <label class="form-label">Detail Barang</label>
                <input type="text"  name="item_detail" class="form-control" placeholder="Contoh: Ayam gembus pak gepuk 2 porsi" required>
            </div>
        </div>
        {{-- SUBMIT --}}
        <div class="text-center">
            <button type="submit" class="btn btn-blue px-5 py-2 fw-semibold">
                Tambah Barang
            </button>
        </div>

    </form>

</div>
@endsection
