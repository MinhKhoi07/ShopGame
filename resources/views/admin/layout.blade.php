<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - ShopGame')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .admin-container {
            display: flex;
            min-height: 100vh;
            background: var(--steam-darker);
        }

        .admin-sidebar {
            width: 250px;
            background: var(--steam-dark);
            border-right: 1px solid var(--steam-border);
            padding: 20px 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }

        .admin-main {
            flex: 1;
            margin-left: 250px;
        }

        .admin-header {
            background: var(--steam-dark);
            border-bottom: 1px solid var(--steam-border);
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .admin-header h1 {
            color: white;
            margin: 0;
            font-size: 24px;
        }

        .admin-content {
            padding: 30px;
        }

        .sidebar-brand {
            padding: 20px;
            border-bottom: 1px solid var(--steam-border);
            margin-bottom: 20px;
        }

        .sidebar-brand a {
            color: white;
            text-decoration: none;
            font-size: 20px;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-menu {
            list-style: none;
        }

        .sidebar-menu li {
            margin: 0;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            color: var(--steam-text);
            text-decoration: none;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: rgba(103, 193, 245, 0.1);
            color: var(--steam-blue);
            border-left-color: var(--steam-blue);
        }

        .sidebar-menu i {
            width: 20px;
            text-align: center;
        }

        .admin-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: var(--steam-dark);
            border-radius: 8px;
            padding: 20px;
            border-left: 4px solid var(--steam-blue);
        }

        /* Helper borders for review stats */
        .border-green { border-left-color: #5ba32b !important; }
        .border-yellow { border-left-color: #beee11 !important; }
        .border-red { border-left-color: #ff6b6b !important; }

        .stat-card h3 {
            color: var(--steam-text);
            font-size: 14px;
            margin: 0 0 10px 0;
            text-transform: uppercase;
        }

        .stat-card .number {
            color: white;
            font-size: 28px;
            font-weight: bold;
        }

        .table-container {
            background: var(--steam-dark);
            border-radius: 8px;
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table thead {
            background: var(--steam-darker);
        }

        table th {
            padding: 15px;
            color: var(--steam-text);
            text-align: left;
            font-weight: 600;
            border-bottom: 1px solid var(--steam-border);
        }

        table td {
            padding: 15px;
            color: var(--steam-text);
            border-bottom: 1px solid var(--steam-border);
        }

        table tbody tr:hover {
            background: rgba(103, 193, 245, 0.05);
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 12px;
        }

        .badge {
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: bold;
        }

        .badge-success {
            background: rgba(91, 163, 43, 0.2);
            color: #5ba32b;
        }

        .badge-warning {
            background: rgba(190, 238, 17, 0.2);
            color: #beee11;
        }

        .badge-danger {
            background: rgba(220, 53, 69, 0.2);
            color: #ff6b6b;
        }

        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: rgba(91, 163, 43, 0.2);
            color: #5ba32b;
            border-left: 4px solid #5ba32b;
        }

        .alert-danger {
            background: rgba(220, 53, 69, 0.2);
            color: #ff6b6b;
            border-left: 4px solid #ff6b6b;
        }

        .alert p {
            margin: 5px 0;
        }

        @media (max-width: 768px) {
            .admin-sidebar {
                width: 200px;
            }

            .admin-main {
                margin-left: 200px;
            }

            .admin-stats {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body class="bg-dark">
    <div class="admin-container">
        <!-- Sidebar -->
        <div class="admin-sidebar">
            <div class="sidebar-brand">
                <a href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-gamepad"></i>
                    <span>ShopGame</span>
                </a>
            </div>

            <ul class="sidebar-menu">
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-chart-line"></i>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.statistics') }}" class="{{ request()->routeIs('admin.statistics') ? 'active' : '' }}">
                        <i class="fas fa-chart-bar"></i>
                        Thống Kê
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.games') }}" class="{{ request()->routeIs('admin.games*') ? 'active' : '' }}">
                        <i class="fas fa-gamepad"></i>
                        Games
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.categories') }}" class="{{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
                        <i class="fas fa-list"></i>
                        Categories
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.banners') }}" class="{{ request()->routeIs('admin.banners*') ? 'active' : '' }}">
                        <i class="fas fa-images"></i>
                        Banners
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.sales') }}" class="{{ request()->routeIs('admin.sales*') ? 'active' : '' }}">
                        <i class="fas fa-tags"></i>
                        Sales
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.reviews') }}" class="{{ request()->routeIs('admin.reviews*') ? 'active' : '' }}">
                        <i class="fas fa-star"></i>
                        Reviews
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.keys') }}" class="{{ request()->routeIs('admin.keys*') ? 'active' : '' }}">
                        <i class="fas fa-key"></i>
                        Game Keys
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.orders') }}" class="{{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
                        <i class="fas fa-shopping-cart"></i>
                        Orders
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.users') }}" class="{{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        Users
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.chats') }}" class="{{ request()->routeIs('admin.chats*') ? 'active' : '' }}">
                        <i class="fas fa-comments"></i>
                        Chat Support
                    </a>
                </li>
                <li>
                    <a href="{{ route('home') }}">
                        <i class="fas fa-home"></i>
                        Trang chủ
                    </a>
                </li>
                <li>
                    <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                        @csrf
                        <button type="submit" style="width: 100%; background: none; border: none; padding: 12px 20px; color: var(--steam-text); text-align: left; cursor: pointer; display: flex; align-items: center; gap: 12px;">
                            <i class="fas fa-sign-out-alt"></i>
                            Đăng xuất
                        </button>
                    </form>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="admin-main">
            <div class="admin-header">
                <h1>@yield('page-title', 'Admin')</h1>
                <div>
                    <span style="color: var(--steam-text);">{{ auth()->user()->name }}</span>
                </div>
            </div>

            <div class="admin-content">
                @if(session('success'))
                    <div class="alert alert-success" style="margin-bottom: 20px;">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger" style="margin-bottom: 20px;">
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>
