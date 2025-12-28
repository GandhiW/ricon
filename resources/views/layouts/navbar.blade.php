<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg bg-white">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center fw-bold" href="{{ route('dashboard') }}">
            <img src="{{ asset('images/logo.png') }}" alt="myBox Logo" height="36" class="me-2">
            myBox
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto ms-4">
                <li class="nav-item">
                    <a class="nav-link fw-semibold {{ request()->routeIs('dashboard') ? 'active text-primary' : 'text-secondary' }}"
                        href="{{ route('dashboard') }}">
                        Dashboard
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link fw-semibold {{ request()->routeIs('history.*') ? 'active text-primary' : 'text-secondary' }}"
                        href="{{ route('history.index') }}">
                        History
                    </a>
                </li>
            </ul>


            <div class="d-flex align-items-center gap-3 position-relative">
                <!-- NOTIFICATION -->
                <a href="#" class="position-relative text-secondary" id="notificationBell">
                    <i class="bi bi-bell fs-4"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger d-none"
                        id="notifBadge">0</span>
                </a>

                <!-- DROPDOWN NOTIF -->
                <div id="notifList" class="card position-absolute"
                    style="display:none; top:50px; right:0; width:300px; max-height:400px; overflow-y:auto; z-index:1050;">
                    <div class="p-3 text-center text-muted">Loading...</div>
                </div>

                <!-- PROFILE -->
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle text-white d-flex justify-content-center align-items-center"
                        style="width:38px;height:38px color: #4a8ebb;">👤</div>
                    <span class="fw-semibold text-secondary">{{ Auth::check() ? Auth::user()->name : 'Guest' }}</span>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- MODAL DETAIL NOTIF -->
<div class="modal fade" id="notifModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content position-relative">
            <div class="modal-header">
                <h5 class="modal-title">Notification</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="notifModalBody" style="position: relative; min-height:200px;">
            </div>
        </div>
    </div>
</div>


<style>
    /* Paksa tinggi navbar */
    .navbar {
        min-height: 64px;
    }

    /* Fix khusus Dashboard & History */
    .navbar-nav {
        height: 64px;
    }

    .navbar-nav .nav-item {
        display: flex;
        align-items: center;
        height: 100%;
    }

    .navbar-nav .nav-link {
        display: flex !important;
        align-items: center !important;
        height: 100%;
        padding-top: 0 !important;
        padding-bottom: 0 !important;
        line-height: 1 !important;
    }

    .nav-link.active {
        color: #4a8ebb !important;
        font-weight: 600;
    }
</style>
