@extends('layouts.guest')
@section('title', 'Login')

@section('content')
<div class="auth-wrapper">
    <div class="auth-card">
        <!-- Logo -->
        <div style="text-align: center; margin-bottom: 1.5rem;">
            <div style="font-size: 3rem; margin-bottom: 0.5rem;">🎓</div>
            <h1 class="auth-title">Selamat Datang!</h1>
            <p class="auth-subtitle">Masuk ke Smart School Task Manager</p>
        </div>

        <!-- Validation Errors -->
        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <!-- Session Status -->
        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div class="form-group">
                <label class="form-label">
                    <i class='bx bx-envelope'></i> Email
                </label>
                <input type="email" name="email" class="form-control" placeholder="nama@email.com" value="{{ old('email') }}" required autofocus>
            </div>

            <!-- Password -->
            <div class="form-group">
                <label class="form-label">
                    <i class='bx bx-lock-alt'></i> Password
                </label>
                <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
            </div>

            <!-- Remember Me -->
            <div class="form-group" style="display: flex; align-items: center; gap: 0.5rem;">
                <input type="checkbox" name="remember" id="remember" style="width: 20px; height: 20px; cursor: pointer;">
                <label for="remember" style="font-weight: 600; cursor: pointer;">Ingat saya</label>
            </div>

            <!-- Submit -->
            <button type="submit" class="btn btn-yellow btn-lg" style="width: 100%; margin-top: 0.5rem;">
                <i class='bx bx-log-in'></i> Masuk
            </button>
        </form>

        <div class="auth-footer">
            Belum punya akun?
            <a href="{{ route('register') }}">Daftar sekarang</a>
        </div>
    </div>
</div>
@endsection
