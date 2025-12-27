<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Dashboard')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- CSRF TOKEN --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons (🔔) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    {{-- AdminLTE --}}
    <link rel="stylesheet" href="{{ asset('dashboard-assets/css/adminlte.css') }}">

    {{-- Overlay Scrollbars --}}
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css"
          crossorigin="anonymous" />

    {{-- Custom Style --}}
    <style>
        body {
            background: linear-gradient(90deg, #5bb6c6, #6bb7e8);
            min-height: 100vh;
            font-family: 'Segoe UI', sans-serif;
        }

        .navbar {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .card {
            border-radius: 20px;
            border: none;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .notif-item:hover {
            background: #f5f7fa;
        }

        .notif-item.unread {
            background-color: #eef3ff;
            border-left: 4px solid #0d6efd;
        }

        .notif-item.read {
            background-color: #fff;
            opacity: 0.8;
        }
    </style>

    @stack('styles')

    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>

</head>

<body>

@if(!Route::is('kiosk.scan'))
    @include('layouts.navbar')
@endif

    <div class="container pb-5 mt-4">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @vite(['resources/js/app.js'])

    @stack('scripts')

</body>
</html>
