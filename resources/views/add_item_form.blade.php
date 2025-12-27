@extends('layouts.app')

@section('title', 'Sewa Loker')

@push('styles')
<style>
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
document.addEventListener('DOMContentLoaded', function () {
    const lockers = document.querySelectorAll('.locker.available');
    const lockerInput = document.getElementById('locker_id');

    lockers.forEach(locker => {
        locker.addEventListener('click', function () {
            lockers.forEach(l => l.classList.remove('selected'));
            this.classList.add('selected');
            lockerInput.value = this.dataset.id;
        });
    });
});
</script>
@endpush

@section('content')
<div class="container">

    {{-- HEADER --}}
    <div class="hero d-flex justify-content-betweenb-0 align-items-center m">
        <h1 class="fw-bold">Tambah Barang</h1>
    </div>

    <form method="POST" action="{{ route('book.update') }}">
    @csrf
        {{-- INFORMASI ITEM --}}
        <div class="card p-4 mb-4">
            <h5 class="mb-3 fw-semibold">Informasi Barang</h5>

            <div class="mb-3">
                <label class="form-label">Nama Barang</label>
                <input type="text" name="item_name" class="form-control" placeholder="Contoh: Nasi ayam">
            </div>

            <div>
                <label class="form-label">Detail Barang</label>
                <input type="text"  name="item_detail" class="form-control" placeholder="Contoh: Ayam gembus pak gepuk 2 porsi">
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
