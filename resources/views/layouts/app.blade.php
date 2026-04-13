<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title') | KursusMusic</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap & Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background-color: #f5f7fb;
            overflow-x: hidden;
        }

        /* Sidebar Sticky Styling */
        .sidebar-wrapper {
            width: 260px;
            height: 100vh;
            position: sticky;
            top: 0;
            padding: 1.5rem 1rem;
            flex-shrink: 0; /* Mencegah sidebar menyusut */
        }

        .sidebar-card {
            height: 100%;
            background: white;
            border-radius: 1.25rem;
            display: flex;
            flex-direction: column;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        /* Nav Link Styling */
        .nav-link {
            transition: all 0.25s ease;
            font-weight: 500;
            color: #4b5563;
            display: flex;
            align-items: center;
            border: 1px solid transparent;
        }

        .nav-link:hover {
            background-color: #f3f4f6;
            color: #6366f1;
        }

        /* Active State */
        .nav-link.active {
            background-color: #6366f1 !important;
            color: white !important;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.35);
        }

        .nav-link i {
            font-size: 1.1rem;
        }

        /* Content Area */
        .main-content {
            flex-grow: 1;
            min-width: 0; /* Penting untuk responsivitas */
        }

        .btn-logout {
            border: none;
            background: transparent;
            width: 100%;
            text-align: left;
            padding: 0.5rem 1rem;
            transition: 0.2s;
        }
        
        .btn-logout:hover {
            background: #fee2e2;
            color: #dc2626 !important;
        }
    </style>
</head>

<body>

<div class="d-flex align-items-start">

    @auth
        @php
            $role = auth()->user()->role;
            $menus = [
                'admin' => [
                    ['name' => 'Dashboard', 'icon' => 'bi-house', 'route' => 'admin.dashboard', 'pattern' => '*dashboard'],
                    ['name' => 'Users', 'icon' => 'bi-person', 'route' => 'admin.users.index', 'pattern' => 'admin/users*'],
                    ['name' => 'Mentors', 'icon' => 'bi-mortarboard', 'route' => 'admin.mentors.index', 'pattern' => 'admin/mentors*'],
                    ['name' => 'Courses', 'icon' => 'bi-book', 'route' => 'admin.courses.index', 'pattern' => 'admin/courses*'],
                ],
                'kasir' => [
                    ['name' => 'Dashboard', 'icon' => 'bi-house', 'route' => 'kasir.dashboard', 'pattern' => '*dashboard'],
                    ['name' => 'Data Transaksi', 'icon' => 'bi-table', 'route' => 'kasir.transactions.index', 'pattern' => 'kasir/transactions*'],
                    ['name' => 'Data Pelanggan', 'icon' => 'bi-people', 'route' => 'kasir.pelanggan', 'pattern' => 'kasir/pelanggan*'],
                ],
                'owner' => [
                    ['name' => 'Dashboard', 'icon' => 'bi-house', 'route' => 'owner.dashboard', 'pattern' => 'owner/dashboard'],
                    ['name' => 'Laporan Keuangan', 'icon' => 'bi-file-earmark-bar-graph', 'route' => 'owner.reports.index', 'pattern' => 'owner/reports*'],
                    ['name' => 'Data Kursus', 'icon' => 'bi-book', 'route' => 'owner.courses.index', 'pattern' => 'owner/courses*'],
                    ['name' => 'Paket Kursus', 'icon' => 'bi-box-seam', 'route' => 'owner.packages.index', 'pattern' => 'owner/packages*'],
                    ['name' => 'Log Aktivitas', 'icon' => 'bi-clock-history', 'route' => 'owner.logs.index', 'pattern' => 'owner/logs*'],
                ],
            ];
        @endphp

        {{-- SIDEBAR --}}
        <div class="sidebar-wrapper d-none d-lg-block">
            <div class="sidebar-card p-3">
                <div class="px-3 mb-4 mt-2">
                    <h5 class="fw-bold text-primary mb-0 text-capitalize">
                         {{ $role }} Panel
                    </h5>
                    <small class="text-muted">Manajemen Sistem</small>
                </div>

                <hr class="mt-0 mb-3 mx-3 opacity-10">

                <ul class="nav flex-column gap-2 flex-grow-1">
                    @foreach($menus[$role] ?? [] as $menu)
                        @php
                            // Mengecek apakah route aktif berdasarkan pattern
                            $isActive = request()->is($menu['pattern']);
                        @endphp
                        <li>
                            <a href="{{ $menu['route'] === '#' ? '#' : route($menu['route']) }}"
                               class="nav-link rounded-3 px-3 py-2 {{ $isActive ? 'active' : '' }}">
                                <i class="bi {{ $menu['icon'] }} me-3"></i>
                                <span>{{ $menu['name'] }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>

                {{-- LOGOUT --}}
                <div class="mt-auto border-top pt-3">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="nav-link btn-logout text-danger rounded-3">
                            <i class="bi bi-box-arrow-right me-3"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endauth

    {{-- CONTENT --}}
    <main class="main-content p-4">
        {{-- Tombol Mobile Nav (Opsional) --}}
        <div class="d-lg-none mb-4 bg-white p-3 rounded-4 shadow-sm d-flex justify-content-between align-items-center">
            <h5 class="fw-bold text-primary mb-0">Panel</h5>
            <button class="btn btn-primary btn-sm"><i class="bi bi-list"></i></button>
        </div>

        @yield('content')
    </main>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>