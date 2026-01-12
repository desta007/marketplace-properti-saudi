<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - SaudiProp Admin</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            color: #1e293b;
            min-height: 100vh;
        }

        .admin-layout {
            display: flex;
            min-height: 100vh;
        }

        /* Enhanced Sidebar */
        .sidebar {
            width: 280px;
            min-width: 280px;
            background: linear-gradient(180deg, #0f172a 0%, #1e293b 50%, #334155 100%);
            color: white;
            position: fixed;
            height: 100vh;
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 25px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
            z-index: 100;
            left: 0;
        }

        .sidebar.collapsed {
            width: 80px;
            min-width: 80px;
        }

        .sidebar.collapsed .sidebar-logo span,
        .sidebar.collapsed .nav-section-title,
        .sidebar.collapsed .sidebar-nav a span:not(.icon),
        .sidebar.collapsed .user-details,
        .sidebar.collapsed .sidebar-footer .btn span {
            display: none;
        }

        .sidebar.collapsed .sidebar-logo {
            justify-content: center;
        }

        .sidebar.collapsed .sidebar-nav a {
            justify-content: center;
            padding: 0.875rem;
        }

        .sidebar.collapsed .sidebar-nav a .icon {
            margin: 0;
        }

        .sidebar.collapsed .user-info {
            justify-content: center;
        }

        .sidebar.collapsed .sidebar-footer .btn {
            padding: 0.75rem;
            justify-content: center;
        }

        .sidebar-header {
            padding: 1.5rem;
            background: rgba(0, 0, 0, 0.2);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .sidebar-logo {
            font-size: 1.5rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .sidebar-logo .icon {
            width: 42px;
            height: 42px;
            min-width: 42px;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
        }

        .sidebar-logo span {
            color: #10b981;
        }

        .sidebar-toggle {
            background: rgba(255, 255, 255, 0.1);
            border: none;
            color: white;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .sidebar-toggle:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .sidebar.collapsed .sidebar-toggle {
            position: absolute;
            right: -16px;
            top: 24px;
            background: #1e293b;
            box-shadow: 2px 0 8px rgba(0, 0, 0, 0.2);
        }

        .sidebar-nav {
            padding: 1rem;
            flex: 1;
            overflow-y: auto;
        }

        .nav-section {
            margin-bottom: 1.5rem;
        }

        .nav-section-title {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: rgba(255, 255, 255, 0.4);
            padding: 0 1rem;
            margin-bottom: 0.5rem;
        }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 0.875rem;
            padding: 0.875rem 1rem;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            border-radius: 10px;
            margin-bottom: 0.25rem;
            transition: all 0.2s ease;
            font-weight: 500;
            white-space: nowrap;
        }

        .sidebar-nav a:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .sidebar-nav a.active {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .sidebar-nav a .icon {
            font-size: 1.25rem;
            width: 24px;
            min-width: 24px;
            text-align: center;
        }

        .sidebar-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            background: linear-gradient(180deg, rgba(15, 23, 42, 0.95) 0%, rgba(15, 23, 42, 1) 100%);
            flex-shrink: 0;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .user-info-wrapper {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            min-width: 40px;
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1rem;
        }

        .user-details h4 {
            font-size: 0.875rem;
            font-weight: 600;
        }

        .user-details p {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.5);
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 280px;
            padding: 2rem;
            min-height: 100vh;
            max-width: calc(100vw - 280px);
            transition: all 0.3s ease;
        }

        .main-content.expanded {
            margin-left: 80px;
            max-width: calc(100vw - 80px);
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .page-header h1 {
            font-size: 1.875rem;
            font-weight: 800;
            color: #0f172a;
        }

        .page-header p {
            color: #64748b;
            margin-top: 0.25rem;
        }

        /* Cards */
        .card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05), 0 10px 40px rgba(0, 0, 0, 0.03);
            margin-bottom: 1.5rem;
            border: 1px solid rgba(0, 0, 0, 0.04);
            overflow: hidden;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.25rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .card-title {
            font-size: 1.125rem;
            font-weight: 700;
            color: #0f172a;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05), 0 10px 40px rgba(0, 0, 0, 0.03);
            border: 1px solid rgba(0, 0, 0, 0.04);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.1) 0%, transparent 70%);
        }

        .stat-card .icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .stat-card .icon.green {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        }

        .stat-card .icon.blue {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        }

        .stat-card .icon.amber {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        }

        .stat-card .icon.purple {
            background: linear-gradient(135deg, #ede9fe 0%, #ddd6fe 100%);
        }

        .stat-card h3 {
            font-size: 0.875rem;
            color: #64748b;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .stat-card .value {
            font-size: 2.25rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1;
        }

        .stat-card .change {
            font-size: 0.75rem;
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .stat-card .change.up {
            color: #10b981;
        }

        .stat-card .change.down {
            color: #ef4444;
        }

        /* Table */
        .table-container {
            overflow-x: auto;
            margin: -1.5rem;
            margin-top: 0;
            padding: 0 1.5rem 1.5rem;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            min-width: 700px;
        }

        .table th,
        .table td {
            padding: 1rem 1rem;
            text-align: left;
        }

        .table th {
            background: #f8fafc;
            font-weight: 600;
            font-size: 0.75rem;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 2px solid #e2e8f0;
            white-space: nowrap;
        }

        .table td {
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
        }

        .table tr:hover td {
            background: #f8fafc;
        }

        .table tr:last-child td {
            border-bottom: none;
        }

        /* Property Title Link */
        .property-title-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: inherit;
            transition: all 0.2s;
        }

        .property-title-link:hover {
            color: #10b981;
        }

        .property-thumb {
            width: 50px;
            height: 40px;
            border-radius: 8px;
            object-fit: cover;
            background: #f1f5f9;
            flex-shrink: 0;
        }

        .property-info h4 {
            font-weight: 600;
            font-size: 0.875rem;
            margin-bottom: 0.125rem;
        }

        .property-info p {
            font-size: 0.75rem;
            color: #64748b;
        }

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.375rem 0.875rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-pending {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            color: #92400e;
        }

        .badge-active,
        .badge-verified {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #065f46;
        }

        .badge-rejected {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #991b1b;
        }

        .badge-suspended {
            background: linear-gradient(135deg, #e5e7eb 0%, #d1d5db 100%);
            color: #374151;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.625rem 1.25rem;
            border-radius: 10px;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            border: none;
            text-decoration: none;
            white-space: nowrap;
        }

        .btn-primary {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.35);
        }

        .btn-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.25);
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(239, 68, 68, 0.35);
        }

        .btn-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.25);
        }

        .btn-secondary {
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
        }

        .btn-secondary:hover {
            background: #e2e8f0;
        }

        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.75rem;
            border-radius: 8px;
        }

        .btn-icon {
            width: 36px;
            height: 36px;
            padding: 0;
            border-radius: 8px;
        }

        /* Form Controls */
        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #334155;
            font-size: 0.875rem;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.875rem;
            transition: all 0.2s;
            background: white;
        }

        .form-control:focus {
            outline: none;
            border-color: #10b981;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
        }

        /* Alerts */
        .alert {
            padding: 1rem 1.25rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 500;
        }

        .alert-success {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #065f46;
            border: 1px solid #6ee7b7;
        }

        .alert-error {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        /* Pagination */
        .pagination {
            display: flex;
            gap: 0.375rem;
            list-style: none;
            margin-top: 1.5rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .pagination a,
        .pagination span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            padding: 0 0.75rem;
            border-radius: 8px;
            text-decoration: none;
            color: #64748b;
            background: white;
            border: 1px solid #e2e8f0;
            font-weight: 500;
            font-size: 0.875rem;
            transition: all 0.2s;
        }

        .pagination a:hover {
            background: #f1f5f9;
            color: #0f172a;
        }

        .pagination .active span {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border-color: transparent;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);
        }

        /* Info Grid */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1rem;
        }

        .info-item {
            background: #f8fafc;
            padding: 1rem;
            border-radius: 10px;
        }

        .info-item label {
            font-size: 0.75rem;
            color: #64748b;
            display: block;
            margin-bottom: 0.25rem;
        }

        .info-item strong {
            font-size: 1rem;
            color: #0f172a;
        }

        /* Mobile Toggle */
        .mobile-toggle {
            display: none;
            position: fixed;
            bottom: 1.5rem;
            right: 1.5rem;
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border: none;
            border-radius: 16px;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            box-shadow: 0 4px 20px rgba(16, 185, 129, 0.4);
            z-index: 101;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .sidebar.collapsed {
                width: 280px;
                transform: translateX(-100%);
            }

            .main-content,
            .main-content.expanded {
                margin-left: 0;
                max-width: 100%;
            }

            .mobile-toggle {
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .sidebar-toggle {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }
        }

        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
    @stack('styles')
</head>

<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo">
                    <div class="icon">🏠</div>
                    <span>Saudi</span>Prop
                </div>
                <button class="sidebar-toggle" onclick="toggleSidebar()" title="Toggle Sidebar">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M11 19l-7-7 7-7M18 19l-7-7 7-7" />
                    </svg>
                </button>
            </div>

            <nav class="sidebar-nav">
                <div class="nav-section">
                    <div class="nav-section-title">Main Menu</div>
                    <a href="{{ route('admin.dashboard') }}"
                        class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <span class="icon">📊</span> <span>Dashboard</span>
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">Moderation</div>
                    <a href="{{ route('admin.properties.index') }}"
                        class="{{ request()->routeIs('admin.properties.*') ? 'active' : '' }}">
                        <span class="icon">🏢</span> <span>Properties</span>
                    </a>
                    <a href="{{ route('admin.agents.index') }}"
                        class="{{ request()->routeIs('admin.agents.*') ? 'active' : '' }}">
                        <span class="icon">👥</span> <span>Agents</span>
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">Quick Links</div>
                    <a href="{{ route('home') }}" target="_blank">
                        <span class="icon">🌐</span> <span>View Website</span>
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">SaaS Management</div>
                    <a href="{{ route('admin.subscriptions.index') }}"
                        class="{{ request()->routeIs('admin.subscriptions.*') ? 'active' : '' }}">
                        <span class="icon">🔄</span> <span>Subscriptions</span>
                    </a>
                    <a href="{{ route('admin.transactions.index') }}"
                        class="{{ request()->routeIs('admin.transactions.*') ? 'active' : '' }}">
                        <span class="icon">💰</span> <span>Transactions</span>
                    </a>
                    <a href="{{ route('admin.subscription-plans.index') }}"
                        class="{{ request()->routeIs('admin.subscription-plans.*') ? 'active' : '' }}">
                        <span class="icon">💳</span> <span>Plans</span>
                    </a>
                    <a href="{{ route('admin.boost-packages.index') }}"
                        class="{{ request()->routeIs('admin.boost-packages.*') ? 'active' : '' }}">
                        <span class="icon">🚀</span> <span>Boost Packages</span>
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">Configuration</div>
                    <a href="{{ route('admin.settings.index') }}"
                        class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                        <span class="icon">⚙️</span> <span>Settings</span>
                    </a>
                    <a href="{{ route('admin.features.index') }}"
                        class="{{ request()->routeIs('admin.features.*') ? 'active' : '' }}">
                        <span class="icon">✨</span> <span>Features</span>
                    </a>
                </div>
            </nav>

            <div class="sidebar-footer">
                <div class="user-info-wrapper">
                    <div class="user-info">
                        <div class="user-avatar">{{ substr(auth()->user()->name, 0, 1) }}</div>
                        <div class="user-details">
                            <h4>{{ auth()->user()->name }}</h4>
                            <p>Administrator</p>
                        </div>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                        @csrf
                        <button type="submit"
                            style="background: rgba(239, 68, 68, 0.15); color: #f87171; border: none; cursor: pointer; width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; transition: all 0.2s;"
                            title="Logout" onmouseover="this.style.background='rgba(239, 68, 68, 0.3)'"
                            onmouseout="this.style.background='rgba(239, 68, 68, 0.15)'">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                <polyline points="16 17 21 12 16 7"></polyline>
                                <line x1="21" y1="12" x2="9" y2="12"></line>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content" id="main-content">
            @if(session('success'))
                <div class="alert alert-success">✅ {{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-error">❌ {{ session('error') }}</div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Mobile Toggle -->
    <button class="mobile-toggle" onclick="toggleMobileSidebar()">☰</button>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');

            // Save preference
            localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
        }

        function toggleMobileSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('open');
        }

        // Restore sidebar state
        document.addEventListener('DOMContentLoaded', function () {
            const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            if (isCollapsed && window.innerWidth > 1024) {
                document.getElementById('sidebar').classList.add('collapsed');
                document.getElementById('main-content').classList.add('expanded');
            }

            // Close mobile sidebar when clicking outside
            document.addEventListener('click', function (e) {
                const sidebar = document.getElementById('sidebar');
                const mobileToggle = document.querySelector('.mobile-toggle');
                if (window.innerWidth <= 1024 &&
                    sidebar.classList.contains('open') &&
                    !sidebar.contains(e.target) &&
                    !mobileToggle.contains(e.target)) {
                    sidebar.classList.remove('open');
                }
            });
        });
    </script>
    @stack('scripts')
</body>

</html>