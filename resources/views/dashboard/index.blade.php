@extends('layouts.app')

@section('title', 'Dashboard')

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
            <h1 class="fw-bold text-white">Hi, Kadek Artika!</h1>
            <p class="fs-5 fst-italic text-white">book your locker now!</p>
        </div>

        <a href="{{ route('booking.index') }}" class="btn btn-primary btn-rounded px-4">
            Book a Locker
        </a>

    </div>

    {{-- MAIN CONTENT --}}
    <div class="row g-4 mt-3">

        {{-- ACTIVE BOOKINGS --}}
        <div class="col-lg-8">
            <div class="card p-4">
                <h5 class="fw-bold mb-3">Active Bookings:</h5>
                @if ($booking)
                    <div class="row align-items-center">
                        <div class="col-md-4 text-center">
                            <p class="mb-1 fw-semibold">locker number:</p>
                            <div class="locker-number">1</div>
                        </div>

                        <div class="col-md-8">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <a href="{{ route('booking.edit', $booking->id) }}"
                                        class="btn btn-dark w-100 btn-rounded">
                                        Add new item to locker
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    <button class="btn btn-dark w-100 btn-rounded">
                                        show QR Code
                                    </button>
                                </div>
                                <div class="col-md-6">
                                    <a href="{{ route('booking.showAssignUserForm', $booking) }}" class="btn btn-dark w-100 btn-rounded">
                                        assign person to pick up
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    <button class="btn btn-success w-100 btn-rounded">
                                        release locker
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-warning mb-0">
                        You have no active booking.
                    </div>
                @endif
            </div>
        </div>

        {{-- LOCKERS AVAILABLE --}}
        <div class="col-lg-4">
            <div class="card p-4 text-center">
                <h5 class="fw-bold">Lockers Available:</h5>
                <div class="locker-number mt-3">3</div>
            </div>
        </div>

        {{-- BOOK A LOCKER CARD --}}
        <div class="col-lg-6">
            <div class="card p-4 text-center">
                <h4 class="fw-bold mb-3">Book a Locker</h4>
                <img src="https://cdn-icons-png.flaticon.com/512/3050/3050525.png" alt="Locker" style="max-width:200px"
                    class="mx-auto">
            </div>
        </div>

        {{-- EMPTY / FUTURE CARD --}}
        <div class="col-lg-6">
            <div class="card p-4" style="min-height:240px;">
                {{-- future content --}}
            </div>
        </div>

    </div>

@endsection
