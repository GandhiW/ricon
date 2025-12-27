@extends('layouts.app')

@section('title', 'Sewa Loker')

@section('content')
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
            <button type="submit" class="btn btn-primary px-5 py-2 fw-semibold">
                Tambah Barang
            </button>
        </div>

    </form>

</div>
@endsection
