<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Smart School Task Manager') }} - @yield('title', 'Dashboard')</title>
    <meta name="description" content="Smart School Task Manager - Kelola tugas sekolahmu dengan lebih pintar!">
    <link rel="stylesheet" href="{{ asset('css/neobrutalism.css') }}">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <!-- NAVBAR -->
    <nav class="navbar">
        <a href="{{ route('dashboard') }}" class="navbar-brand">
            <i class='bx bxs-graduation'></i>
            Smart School Task Manager
        </a>
        <div class="navbar-links">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class='bx bxs-dashboard'></i> Dashboard
            </a>
            <a href="{{ route('tasks.index') }}" class="nav-link {{ request()->routeIs('tasks.*') ? 'active' : '' }}">
                <i class='bx bxs-book-content'></i> Tugas
            </a>
            <a href="{{ route('tasks.create') }}" class="nav-link {{ request()->routeIs('tasks.create') ? 'active' : '' }}">
                <i class='bx bx-plus-circle'></i> Tambah
            </a>
            <span class="nav-user">
                <i class='bx bxs-user'></i> {{ Auth::user()->name }}
            </span>
            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                @csrf
                <button type="submit" class="nav-link" style="font-family:var(--font);">
                    <i class='bx bx-log-out'></i> Logout
                </button>
            </form>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <main class="main-content">
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer style="text-align:center; padding:2rem; font-weight:600; border-top:var(--border-thick); background:var(--yellow);">
        <p>📚 Smart School Task Manager &copy; {{ date('Y') }} — Belajar Lebih Pintar!</p>
    </footer>

    @yield('scripts')
</body>
</html>
