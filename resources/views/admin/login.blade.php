@extends('layouts.public')

@section('title', 'Login Admin | ' . ($profile->school_name ?? config('app.name', 'Sekolah')))

@section('page-style')
<style>
    .login-wrap {
        min-height: calc(100vh - 100px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 48px 20px;
        position: relative;
        overflow: hidden;
        background-color: #f6f4ef;
        background-image:
            radial-gradient(at 15% 15%, rgba(26, 58, 92, 0.08) 0px, transparent 50%),
            radial-gradient(at 85% 85%, rgba(197, 155, 39, 0.10) 0px, transparent 50%),
            radial-gradient(#d8d3c8 0.75px, transparent 0.75px);
        background-size: 100% 100%, 100% 100%, 24px 24px;
    }

    .login-card {
        width: 100%;
        max-width: 440px;
        background: #ffffff;
        border: 1px solid rgba(227, 224, 216, 0.9);
        border-radius: 20px;
        padding: 0;
        overflow: hidden;
        box-shadow:
            0 20px 50px rgba(26, 58, 92, 0.10),
            0 4px 14px rgba(26, 58, 92, 0.05);
        animation: cardFadeIn 0.45s cubic-bezier(0.16, 1, 0.3, 1);
    }

    @keyframes cardFadeIn {
        from {
            opacity: 0;
            transform: translateY(14px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .login-top-accent {
        height: 5px;
        background: linear-gradient(90deg, #1a3a5c 0%, #2c5f8a 65%, #c59b27 100%);
    }

    .login-body {
        padding: 34px 32px 30px;
    }

    .school-header {
        text-align: center;
        margin-bottom: 26px;
    }

    .logo-ring {
        width: 80px;
        height: 80px;
        margin: 0 auto 14px;
        padding: 4px;
        border-radius: 50%;
        background: linear-gradient(135deg, #1a3a5c, #c59b27);
        box-shadow: 0 8px 20px rgba(26, 58, 92, 0.16);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .logo-ring .logo-img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
        background: #ffffff;
    }

    .logo-fallback {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: #ffffff;
        color: #1a3a5c;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .portal-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 999px;
        background: rgba(26, 58, 92, 0.07);
        color: #1a3a5c;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        margin-bottom: 8px;
    }

    .school-header h1 {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1e293b;
        line-height: 1.35;
        margin: 0;
    }

    .school-subtitle {
        font-size: 0.85rem;
        color: #64748b;
        margin-top: 4px;
    }

    .alert-box {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 12px 14px;
        border-radius: 12px;
        font-size: 0.84rem;
        line-height: 1.45;
        margin-bottom: 18px;
    }

    .alert-success {
        background: #ecfdf5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }

    .alert-error {
        background: #fef2f2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    .alert-info {
        background: #f8fafc;
        color: #334155;
        border: 1px solid #cbd5e1;
        margin-top: 14px;
        display: none;
    }

    .form-group {
        margin-bottom: 18px;
    }

    .form-group label {
        display: block;
        font-size: 0.83rem;
        color: #334155;
        margin-bottom: 7px;
        font-weight: 600;
    }

    .input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .input-wrapper input {
        width: 100%;
        padding: 12px 42px;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        font-size: 0.95rem;
        background: #f8fafc;
        color: #1e293b;
        transition: all 0.2s ease;
    }

    .input-wrapper input::placeholder {
        color: #94a3b8;
    }

    .input-wrapper input:hover {
        border-color: #cbd5e1;
        background: #ffffff;
    }

    .input-wrapper input:focus {
        outline: none;
        background: #ffffff;
        border-color: #2c5f8a;
        box-shadow: 0 0 0 4px rgba(44, 95, 138, 0.12);
    }

    .input-icon-left {
        position: absolute;
        left: 14px;
        color: #94a3b8;
        display: flex;
        align-items: center;
        pointer-events: none;
        transition: color 0.2s ease;
    }

    .input-wrapper input:focus ~ .input-icon-left {
        color: #1a3a5c;
    }

    .toggle-password-btn {
        position: absolute;
        right: 12px;
        background: none;
        border: none;
        padding: 4px;
        color: #94a3b8;
        cursor: pointer;
        display: flex;
        align-items: center;
        border-radius: 6px;
        transition: color 0.15s, background 0.15s;
    }

    .toggle-password-btn:hover {
        color: #1a3a5c;
        background: rgba(26, 58, 92, 0.06);
    }

    .form-group.has-error .input-wrapper input {
        border-color: #dc2626;
        background: #fef2f2;
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.10);
    }

    .form-group.has-error .input-icon-left {
        color: #dc2626;
    }

    .error-msg {
        display: flex;
        align-items: center;
        gap: 5px;
        color: #dc2626;
        font-size: 0.78rem;
        font-weight: 500;
        margin-top: 6px;
    }

    .form-options {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin: 14px 0 22px;
        font-size: 0.84rem;
    }

    .remember-label {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #475569;
        cursor: pointer;
        user-select: none;
    }

    .remember-label input[type="checkbox"] {
        width: 16px;
        height: 16px;
        accent-color: #1a3a5c;
        border-radius: 4px;
        cursor: pointer;
    }

    .forgot-link {
        color: #2c5f8a;
        font-weight: 600;
        text-decoration: none;
        transition: color 0.15s;
    }

    .forgot-link:hover {
        color: #1a3a5c;
        text-decoration: underline;
    }

    .btn-primary-login {
        width: 100%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 13px 18px;
        border: none;
        border-radius: 12px;
        font-size: 0.96rem;
        font-weight: 600;
        letter-spacing: 0.01em;
        background: linear-gradient(135deg, #1a3a5c 0%, #265380 100%);
        color: #ffffff;
        cursor: pointer;
        box-shadow: 0 6px 16px rgba(26, 58, 92, 0.22);
        transition: all 0.2s ease;
    }

    .btn-primary-login:hover {
        background: linear-gradient(135deg, #142e49 0%, #1f456b 100%);
        transform: translateY(-1px);
        box-shadow: 0 8px 20px rgba(26, 58, 92, 0.28);
    }

    .btn-primary-login:active {
        transform: translateY(0);
    }

    .divider {
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 20px 0;
        color: #94a3b8;
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .divider::before,
    .divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #e2e8f0;
    }

    .btn-secondary-login {
        width: 100%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 11px 16px;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        font-size: 0.88rem;
        font-weight: 600;
        background: #ffffff;
        color: #475569;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .btn-secondary-login:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        color: #1e293b;
    }

    .login-footer {
        margin-top: 22px;
        padding-top: 16px;
        border-top: 1px solid #f1f5f9;
        text-align: center;
        font-size: 0.82rem;
        color: #64748b;
    }

    .login-footer a {
        color: #1a3a5c;
        font-weight: 600;
        text-decoration: none;
    }

    .login-footer a:hover {
        text-decoration: underline;
    }

    @media (max-width: 480px) {
        .login-body {
            padding: 28px 22px 24px;
        }
    }
</style>
@endsection

@section('content')
<div class="login-wrap">
    <div class="login-card">
        <div class="login-top-accent"></div>

        <div class="login-body">
            <div class="school-header">
                <div class="logo-ring">
                    @if ($profile?->logo_path)
                        <img src="{{ asset('storage/' . $profile->logo_path) }}" alt="{{ $profile->school_name }}" class="logo-img">
                    @else
                        <div class="logo-fallback">
                            <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                                <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                            </svg>
                        </div>
                    @endif
                </div>

                <span class="portal-badge">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    </svg>
                    Portal Administrasi
                </span>

                <h1>{{ $profile->school_name ?? config('app.name', 'Sekolah') }}</h1>
                <p class="school-subtitle">Silakan masuk menggunakan akun admin atau guru Anda</p>
            </div>

            @if (session('status'))
                <div class="alert-box alert-success">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0; margin-top:1px;">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
                    </svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="alert-box alert-error">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0; margin-top:1px;">
                        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('filament.admin.auth.login.post') }}" id="adminLoginForm">
                @csrf

                <div class="form-group @error('email') has-error @enderror">
                    <label for="email">Email / Username</label>
                    <div class="input-wrapper">
                        <input
                            type="text"
                            id="email"
                            name="email"
                            placeholder="nama@sekolah.sch.id"
                            value="{{ old('email') }}"
                            autocomplete="username"
                            autofocus
                            required
                        >
                        <span class="input-icon-left">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="4" width="20" height="16" rx="3"/>
                                <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                            </svg>
                        </span>
                    </div>
                    @error('email')
                        <span class="error-msg">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group @error('password') has-error @enderror">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Masukkan kata sandi"
                            autocomplete="current-password"
                            required
                        >
                        <span class="input-icon-left">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                        </span>
                        <button type="button" class="toggle-password-btn" id="togglePasswordBtn" aria-label="Tampilkan atau sembunyikan password">
                            <svg id="eyeIcon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <span class="error-msg">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-options">
                    <label class="remember-label">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span>Ingat saya</span>
                    </label>
                    <a href="#forgot-password" class="forgot-link" id="forgotPasswordTrigger">Lupa Password?</a>
                </div>

                <button type="submit" class="btn-primary-login" id="submitLoginBtn">
                    <span>Masuk ke Panel Admin</span>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                        <polyline points="10 17 15 12 10 7"/>
                        <line x1="15" y1="12" x2="3" y2="12"/>
                    </svg>
                </button>

                <div id="forgot-password" class="alert-box alert-info">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#1a3a5c" stroke-width="2" style="flex-shrink:0; margin-top:1px;">
                        <circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/>
                    </svg>
                    <div>
                        <strong>Bantuan Reset Password:</strong><br>
                        Silakan hubungi Operator / Administrator Sekolah untuk melakukan pengaturan ulang kata sandi akun Anda.
                    </div>
                </div>

                <div class="divider">Atau</div>

                <a href="{{ url('/admin') }}" class="btn-secondary-login">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="19" y1="12" x2="5" y2="12"/>
                        <polyline points="12 19 5 12 12 5"/>
                    </svg>
                    <span>Kembali ke Dashboard</span>
                </a>

                <div class="login-footer">
                    Belum punya akses? <a href="{{ route('public.home') }}">Hubungi admin sekolah</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleBtn = document.getElementById('togglePasswordBtn');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        if (toggleBtn && passwordInput) {
            toggleBtn.addEventListener('click', function () {
                const isPassword = passwordInput.getAttribute('type') === 'password';
                passwordInput.setAttribute('type', isPassword ? 'text' : 'password');

                eyeIcon.innerHTML = isPassword
                    ? '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>'
                    : '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
            });
        }

        const forgotTrigger = document.getElementById('forgotPasswordTrigger');
        const forgotBox = document.getElementById('forgot-password');

        if (forgotTrigger && forgotBox) {
            forgotTrigger.addEventListener('click', function (e) {
                e.preventDefault();
                const isHidden = forgotBox.style.display === '' || forgotBox.style.display === 'none';
                forgotBox.style.display = isHidden ? 'flex' : 'none';
            });
        }

        const loginForm = document.getElementById('adminLoginForm');
        const submitBtn = document.getElementById('submitLoginBtn');

        if (loginForm && submitBtn) {
            loginForm.addEventListener('submit', function () {
                submitBtn.disabled = true;
                submitBtn.style.opacity = '0.8';
                submitBtn.querySelector('span').textContent = 'Memverifikasi...';
            });
        }
    });
</script>
@endsection