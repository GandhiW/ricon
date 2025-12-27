@extends('layouts.app')

@section('title', 'History')

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script> --}}
    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.bootstrap5.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const buttons = document.querySelectorAll('.lihat-detail-btn');
            const modal = document.getElementById('historiModal');

            buttons.forEach(btn => {
                btn.addEventListener('click', function() {
                    document.getElementById('modal-date').innerText = this.dataset.date;
                    document.getElementById('modal-address').innerText = this.dataset.address;
                    document.getElementById('modal-driver').innerText = this.dataset.driver;
                    document.getElementById('modal-summary').innerHTML = this.dataset.summary;
                    modal.style.display = 'block';
                });
            });

            document.querySelector('.close-btn').addEventListener('click', () => {
                modal.style.display = 'none';
            });
        });
    </script>
@endpush

@section('content')
    <div class="hero d-flex justify-content-between align-items-center">
        <div>
            <h1 class="fw-bold">Riwayat</h1>
        </div>
    </div>


    <div class="card-body">

        {{-- ===== STATIC ORDER ITEM ===== --}}
        <div class="mb-3 p-3 rounded" style="background-color:#f9fdf9; border:1px solid #d4ecd4;">
            <div class="row mb-2">
                <div class="col-6">
                    <span class="badge bg-success">Disetujui</span>
                </div>
                <div class="col-6 text-end">
                    <span class="fw-semibold text-success">
                        Rp25.000
                    </span>
                </div>
            </div>

            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="text-muted">
                        Senin, 16 Des 2024
                    </div>
                    <div class="text-success fw-semibold">
                        2kg Plastik, 1kg Kertas
                    </div>
                </div>
                <div class="col-md-4 text-md-end">
                    <button class="btn btn-outline-success lihat-detail-btn w-100" data-date="Senin, 16 Des 2024"
                        data-address="Jl. Merdeka No.10, Jakarta, DKI Jakarta, 12345" data-driver="Budi Santoso"
                        data-summary="
                                        <table class='table table-bordered text-sm'>
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Jenis Sampah</th>
                                                    <th>Berat</th>
                                                    <th>Harga/Kg</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <td>Plastik</td>
                                                    <td>2 kg</td>
                                                    <td>Rp5.000</td>
                                                    <td>Rp10.000</td>
                                                </tr>
                                                <tr>
                                                    <td>2</td>
                                                    <td>Kertas</td>
                                                    <td>1 kg</td>
                                                    <td>Rp15.000</td>
                                                    <td>Rp15.000</td>
                                                </tr>
                                                <tr>
                                                    <td colspan='4'><b>Total</b></td>
                                                    <td><b>Rp25.000</b></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        ">
                        Lihat Detail
                    </button>
                </div>
            </div>
        </div>
        {{-- ===== END STATIC ORDER ===== --}}

        {{-- MODAL --}}
        <div id="historiModal" class="modal-overlay" style="display:none;">
            <div class="modal-content">
                <div class="d-flex justify-content-between mb-2">
                    <div>
                        <strong>Tanggal:</strong> <span id="modal-date"></span><br>
                        <strong>Alamat:</strong> <span id="modal-address"></span><br>
                        <strong>Driver:</strong> <span id="modal-driver"></span>
                    </div>
                    <button class="close-btn btn btn-sm btn-light">&times;</button>
                </div>
                <h6>Ringkasan Penukaran</h6>
                <div id="modal-summary"></div>
            </div>
        </div>
        {{-- END MODAL --}}
    </div>
    @endsection
