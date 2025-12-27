@extends('layouts.app')

@section('title', 'Assign User')

@section('content')
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
            <button type="submit" class="btn btn-primary px-5 py-2 fw-semibold">
                Simpan
            </button>
        </div>

    </form>

</div>
@endsection
