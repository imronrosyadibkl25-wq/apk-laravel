@extends('layouts.guest')
@section('title', 'Register')

@section('content')
<div class="auth-wrapper">
    <div class="auth-card">
        <!-- Logo -->
        <div style="text-align: center; margin-bottom: 1.5rem;">
            <div style="font-size: 3rem; margin-bottom: 0.5rem;">📚</div>
            <h1 class="auth-title">Daftar Akun</h1>
            <p class="auth-subtitle">Buat akun baru untuk mulai kelola tugas</p>
        </div>

        <!-- Validation Errors -->
        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div class="form-group">
                <label class="form-label">
                    <i class='bx bx-user'></i> Nama Lengkap
                </label>
                <input type="text" name="name" class="form-control" placeholder="Masukkan nama lengkap" value="{{ old('name') }}" required autofocus>
            </div>

            <!-- Email -->
            <div class="form-group">
                <label class="form-label">
                    <i class='bx bx-envelope'></i> Email
                </label>
                <input type="email" name="email" class="form-control" placeholder="nama@email.com" value="{{ old('email') }}" required>
            </div>

            <!-- Password -->
            <div class="form-group">
                <label class="form-label">
                    <i class='bx bx-lock-alt'></i> Password
                </label>
                <input type="password" name="password" class="form-control" placeholder="Minimal 8 karakter" required>
            </div>

            <!-- Confirm Password -->
            <div class="form-group">
                <label class="form-label">
                    <i class='bx bx-lock'></i> Konfirmasi Password
                </label>
                <input type="password" name="password_confirmation" class="form-control" placeholder="Ketik ulang password" required>
            </div>

            <!-- Submit -->
            <button type="submit" class="btn btn-pink btn-lg" style="width: 100%; margin-top: 0.5rem;">
                <i class='bx bx-user-plus'></i> Daftar Sekarang
            </button>
        </form>

        <div class="auth-footer">
            Sudah punya akun?
            <a href="{{ route('login') }}">Masuk di sini</a>
        </div>
    </div>
</div>
@endsection
