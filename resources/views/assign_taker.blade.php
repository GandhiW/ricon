@extends('layouts.app')

@section('title', 'Assign User')

@section('content')

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
    </style>
@endpush

<div class="container">

    {{-- HEADER --}}
    <div class="hero d-flex justify-content-between align-items-center mb-2">
        <div>
            <h1 class="fw-bold text-white">Pilih User Yang Mengambil Pesanan</h1>
        </div>
    </div>

    <form method="POST" action="{{ route('booking.assignUser', $booking->id) }}">
        @csrf
        @method('PUT')

        {{-- CARD --}}
        <div class="card p-4 mb-4">
            <h5 class="mb-3 fw-semibold">Pilih User</h5>

            <div class="mb-3">
                <label class="form-label">User</label>
                <select name="user_id" class="form-select" required>
                    <option value="" disabled selected>-- Pilih User --</option>

                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>

                @error('user_id')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- SUBMIT --}}
        <div class="text-center">
            <button type="submit" class="btn btn-blue px-5 py-2 fw-semibold">
                Simpan
            </button>
        </div>

    </form>

</div>
@endsection
