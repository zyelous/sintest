@extends('layouts.guest')

@section('content')
<style>
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    body {
        overflow-x: hidden;
        background-color: #0f172a;
    }

    .login-container {
        display: flex;
        width: 100vw;
        min-height: 100vh;
        background: #ffffff;
    }

    /* Left Hero Panel (Full Height Split Screen) */
    .login-hero {
        position: relative;
        flex: 0 0 52%;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 60px 64px;
        background-image: url('{{ asset('images/loginbg.png') }}');
        background-size: cover;
        background-position: center;
        overflow: hidden;
    }

    .hero-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(15, 30, 54, 0.88) 0%, rgba(27, 58, 92, 0.82) 50%, rgba(10, 20, 38, 0.94) 100%);
        backdrop-filter: blur(3px);
        z-index: 1;
    }

    /* Decorative Orbs */
    .hero-orb-1 {
        position: absolute;
        top: -10%;
        left: -10%;
        width: 400px;
        height: 400px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(56, 189, 248, 0.25) 0%, rgba(0, 0, 0, 0) 70%);
        z-index: 2;
        pointer-events: none;
    }

    .hero-orb-2 {
        position: absolute;
        bottom: -15%;
        right: -10%;
        width: 500px;
        height: 500px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(14, 165, 233, 0.2) 0%, rgba(0, 0, 0, 0) 70%);
        z-index: 2;
        pointer-events: none;
    }

    .hero-content {
        position: relative;
        z-index: 3;
        color: #ffffff;
        display: flex;
        flex-direction: column;
        height: 100%;
        justify-content: space-between;
    }

    .hero-brand {
        display: inline-flex;
        align-items: center;
        gap: 16px;
        padding: 12px 24px 12px 16px;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.18);
        border-radius: 50px;
        width: fit-content;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
    }

    .brand-logo {
        height: 44px;
        width: auto;
        object-fit: contain;
        filter: drop-shadow(0 2px 6px rgba(0, 0, 0, 0.3));
    }

    .brand-text-group {
        display: flex;
        flex-direction: column;
    }

    .brand-name {
        font-size: 22px;
        font-weight: 800;
        letter-spacing: 2px;
        color: #ffffff;
        line-height: 1;
    }

    .brand-sub {
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 1px;
        color: rgba(255, 255, 255, 0.8);
        text-transform: uppercase;
        margin-top: 3px;
    }

    .hero-main {
        margin: auto 0;
        padding: 40px 0;
        max-width: 560px;
    }

    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 16px;
        background: rgba(56, 189, 248, 0.15);
        border: 1px solid rgba(56, 189, 248, 0.3);
        border-radius: 30px;
        font-size: 13px;
        font-weight: 700;
        color: #38bdf8;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        margin-bottom: 24px;
    }

    .hero-badge-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background-color: #38bdf8;
        box-shadow: 0 0 10px #38bdf8;
    }

    .hero-title {
        font-size: 42px;
        font-weight: 800;
        line-height: 1.25;
        margin-bottom: 20px;
        color: #ffffff;
        letter-spacing: -0.5px;
        text-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    }

    .hero-title span {
        background: linear-gradient(135deg, #38bdf8 0%, #818cf8 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .hero-description {
        font-size: 16px;
        line-height: 1.7;
        color: rgba(226, 232, 240, 0.9);
        margin-bottom: 36px;
        font-weight: 400;
    }

    /* Features Grid */
    .features-list {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .feature-item {
        display: flex;
        align-items: center;
        gap: 14px;
        font-size: 15px;
        font-weight: 600;
        color: rgba(255, 255, 255, 0.95);
    }

    .feature-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #38bdf8;
        flex-shrink: 0;
    }

    .hero-copyright {
        font-size: 13px;
        color: rgba(255, 255, 255, 0.5);
        font-weight: 500;
    }

    /* Right Form Panel (Full Height) */
    .login-panel {
        flex: 0 0 48%;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 60px 80px;
        background: #ffffff;
        position: relative;
    }

    .login-box {
        width: 100%;
        max-width: 500px;
        animation: fadeIn 0.4s ease-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(12px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .mobile-brand {
        display: none;
    }

    .login-header {
        margin-bottom: 36px;
    }

    .login-header h2 {
        font-size: 36px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 10px;
        letter-spacing: -0.5px;
    }

    .login-header p {
        font-size: 16px;
        color: #64748b;
        line-height: 1.6;
    }

    form {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .label {
        font-size: 13.5px;
        font-weight: 700;
        color: #334155;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 10px;
        display: block;
    }

    .control {
        position: relative;
    }

    .control-icon {
        position: absolute;
        top: 50%;
        left: 20px;
        transform: translateY(-50%);
        width: 22px;
        height: 22px;
        color: #94a3b8;
        pointer-events: none;
        transition: color 0.2s ease;
    }

    .input {
        width: 100%;
        height: 58px;
        padding: 0 54px 0 56px;
        border: 1.5px solid #cbd5e1;
        border-radius: 16px;
        font-size: 16px;
        font-family: inherit;
        outline: none;
        background: #f8fafc;
        color: #0f172a;
        transition: all 0.2s ease;
    }

    .input::placeholder {
        color: #94a3b8;
    }

    .input:focus {
        border-color: #1b3a5c;
        background: #ffffff;
        box-shadow: 0 0 0 4px rgba(27, 58, 92, 0.12);
    }

    .input:focus + .control-icon {
        color: #1b3a5c;
    }

    /* Error States */
    .input.error {
        border-color: #ef4444;
        background: #fffbfa;
    }

    .input.error:focus {
        box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.12);
    }

    .input.error + .control-icon {
        color: #ef4444;
    }

    .error-text {
        margin-top: 8px;
        font-size: 13.5px;
        color: #ef4444;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* Eye Toggle */
    .eye {
        position: absolute;
        top: 50%;
        right: 18px;
        transform: translateY(-50%);
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border: 0;
        background: transparent;
        color: #94a3b8;
        cursor: pointer;
        border-radius: 50%;
        transition: all 0.2s ease;
    }

    .eye:hover {
        color: #475569;
        background: #e2e8f0;
    }

    .eye svg {
        width: 22px;
        height: 22px;
    }

    .hidden {
        display: none;
    }

    /* Form Options Row */
    .form-options {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 2px;
    }

    .remember {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #475569;
        font-size: 15px;
        font-weight: 500;
        cursor: pointer;
        user-select: none;
    }

    .remember input {
        width: 20px;
        height: 20px;
        border-radius: 6px;
        border: 1.5px solid #cbd5e1;
        accent-color: #1b3a5c;
        cursor: pointer;
    }

    .forgot-link {
        font-size: 14.5px;
        font-weight: 700;
        color: #1b3a5c;
        text-decoration: none;
        background: none;
        border: none;
        cursor: pointer;
        transition: color 0.2s ease;
    }

    .forgot-link:hover {
        color: #2563eb;
        text-decoration: underline;
    }

    /* Submit Button */
    .submit {
        display: flex;
        width: 100%;
        height: 58px;
        align-items: center;
        justify-content: center;
        gap: 12px;
        border: 0;
        border-radius: 16px;
        background: linear-gradient(135deg, #1b3a5c 0%, #0f2440 100%);
        color: #ffffff;
        font-family: inherit;
        font-size: 17px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 12px 24px -6px rgba(27, 58, 92, 0.35);
        margin-top: 8px;
    }

    .submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 18px 30px -6px rgba(27, 58, 92, 0.45);
        opacity: 0.96;
    }

    .submit:active {
        transform: translateY(0);
        box-shadow: 0 6px 12px -3px rgba(27, 58, 92, 0.3);
    }

    .submit svg {
        width: 22px;
        height: 22px;
        transition: transform 0.25s ease;
    }

    .submit:hover svg {
        transform: translateX(4px);
    }

    /* Alerts */
    .alert-box {
        padding: 16px 18px;
        border-radius: 14px;
        font-size: 14.5px;
        line-height: 1.5;
        margin-bottom: 28px;
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }

    .alert-box.success {
        background-color: #f0fdf4;
        border: 1px solid #bbf7d0;
        color: #166534;
    }

    .alert-box.error {
        background-color: #fef2f2;
        border: 1px solid #fecaca;
        color: #991b1b;
    }

    .alert-box.info {
        background-color: #eff6ff;
        border: 1px solid #bfdbfe;
        color: #1e40af;
    }

    /* Modal Styles */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.7);
        backdrop-filter: blur(8px);
        z-index: 999;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 24px;
        opacity: 0;
        visibility: hidden;
        transition: all 0.25s ease;
    }

    .modal-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    .modal-card {
        background: #ffffff;
        width: 100%;
        max-width: 500px;
        border-radius: 28px;
        padding: 36px;
        box-shadow: 0 30px 60px -15px rgba(0, 0, 0, 0.4);
        transform: translateY(20px);
        transition: all 0.25s ease;
    }

    .modal-overlay.active .modal-card {
        transform: translateY(0);
    }

    .modal-header {
        margin-bottom: 24px;
    }

    .modal-header h3 {
        font-size: 24px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 8px;
    }

    .modal-header p {
        font-size: 15px;
        color: #64748b;
        line-height: 1.6;
    }

    .modal-actions {
        display: flex;
        gap: 14px;
        margin-top: 28px;
    }

    .btn-secondary {
        flex: 1;
        height: 52px;
        border: 1.5px solid #cbd5e1;
        background: #ffffff;
        color: #475569;
        border-radius: 14px;
        font-weight: 700;
        font-size: 15px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-secondary:hover {
        background: #f8fafc;
        color: #0f172a;
    }

    .btn-primary {
        flex: 1;
        height: 52px;
        border: 0;
        background: #1b3a5c;
        color: #ffffff;
        border-radius: 14px;
        font-weight: 700;
        font-size: 15px;
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: 0 10px 20px -5px rgba(27, 58, 92, 0.3);
    }

    .btn-primary:hover {
        background: #0f2440;
    }

    /* Responsiveness */
    @media (max-width: 1100px) {
        .login-hero {
            flex: 0 0 45%;
            padding: 48px 40px;
        }
        .login-panel {
            flex: 0 0 55%;
            padding: 48px 48px;
        }
        .hero-title {
            font-size: 34px;
        }
    }

    @media (max-width: 868px) {
        .login-container {
            flex-direction: column;
        }
        .login-hero {
            display: none;
        }
        .login-panel {
            flex: 1 0 100%;
            min-height: 100vh;
            padding: 40px 24px;
        }
        .mobile-brand {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 28px;
            padding: 12px 18px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            width: fit-content;
        }
        .mobile-brand .brand-logo {
            height: 40px;
            width: auto;
        }
        .mobile-brand .brand-name {
            font-size: 20px;
            font-weight: 800;
            color: #1b3a5c;
            letter-spacing: 1.5px;
        }
        .login-header h2 {
            font-size: 28px;
        }
    }
</style>

<div class="login-container">
    <!-- Panel Kiri: Hero & Branding (Full Screen Height) -->
    <section class="login-hero">
        <div class="hero-overlay"></div>
        <div class="hero-orb-1"></div>
        <div class="hero-orb-2"></div>
        
        <div class="hero-content">
            <div class="hero-brand">
                <img src="{{ asset('images/logo_lampung.png') }}" alt="Logo Lampung" class="brand-logo">
                <div class="brand-text-group">
                    <span class="brand-name">SINTARA</span>
                    <span class="brand-sub">Bappeda Prov. Lampung</span>
                </div>
            </div>
            
            <div class="hero-main">
                
                <h1 class="hero-title">Sistem Informasi Monitoring & <span>Tracking Arsip</span></h1>
                

            <p class="hero-copyright">&copy; {{ date('Y') }} Bappeda Provinsi Lampung. All rights reserved.</p>
        </div>
    </section>

    <!-- Panel Kanan: Form Login Full Height -->
    <main class="login-panel">
        <div class="login-box">
            <!-- Branding untuk Tampilan Mobile -->
            <div class="mobile-brand">
                <img src="{{ asset('images/logo_lampung.png') }}" alt="Logo Lampung" class="brand-logo">
                <span class="brand-name">SINTARA</span>
            </div>
            
            <div class="login-header">
                <h2>Selamat Datang</h2>
                <p>Silakan masuk dengan akun Anda untuk mengakses sistem manajemen arsip.</p>
            </div>

            @if(session('reset_success'))
                <div class="alert-box success">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ session('reset_success') }}</span>
                </div>
            @endif

            @if(session('reset_error'))
                <div class="alert-box error">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ session('reset_error') }}</span>
                </div>
            @endif

            @if(session('reset_info'))
                <div class="alert-box info">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ session('reset_info') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('login.submit') }}">
                @csrf

                <!-- Field Username -->
                <div class="form-group">
                    <label class="label" for="username">Username</label>
                    <div class="control">
                        <input class="input {{ $errors->has('username') ? 'error' : '' }}" type="text" id="username" name="username" value="{{ old('username') }}" placeholder="Masukkan username Anda" autocomplete="username" required autofocus>
                        <svg class="control-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    @error('username') 
                        <p class="error-text">
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            {{ $message }}
                        </p> 
                    @enderror
                </div>

                <!-- Field Password -->
                <div class="form-group">
                    <label class="label" for="password">Kata Sandi</label>
                    <div class="control">
                        <input class="input {{ $errors->has('password') ? 'error' : '' }}" type="password" id="password" name="password" placeholder="••••••••" autocomplete="current-password" required>
                        <svg class="control-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <button class="eye" type="button" id="togglePassword" aria-label="Tampilkan kata sandi">
                            <svg id="eyeOpen" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7s-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg id="eyeClosed" class="hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18M10.6 10.6a2 2 0 002.8 2.8M9.9 5.1A10.6 10.6 0 0112 5c4.5 0 8.3 2.9 9.5 7a10.4 10.4 0 01-2.1 3.8M6.6 6.6A10.2 10.2 0 002.5 12c1.2 4.1 5 7 9.5 7 1.1 0 2.2-.2 3.1-.5"/>
                            </svg>
                        </button>
                    </div>
                    @error('password') 
                        <p class="error-text">
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            {{ $message }}
                        </p> 
                    @enderror
                </div>

                <!-- Remember Me Checkbox & Forgot Password Link -->
                <div class="form-options">
                    <label class="remember">
                        <input type="checkbox" name="remember">
                        <span>Ingat Saya</span>
                    </label>
                    <button type="button" class="forgot-link" id="openForgotModal">
                        Lupa Kata Sandi?
                    </button>
                </div>

                <!-- Tombol Submit -->
                <button class="submit" type="submit">
                    Masuk Ke Sistem
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l4-4-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3v1"/>
                    </svg>
                </button>
            </form>
        </div>
    </main>
</div>

<!-- Modal Minta Reset Password -->
<div class="modal-overlay" id="forgotModal">
    <div class="modal-card">
        <div class="modal-header">
            <h3>Minta Reset Kata Sandi</h3>
            <p>Khusus Operator. Masukkan username Anda untuk mengajukan permohonan reset kata sandi ke Administrator.</p>
        </div>
        <form method="POST" action="{{ route('password.request.store') }}">
            @csrf
            <div class="form-group">
                <label class="label" for="reset_username">Username</label>
                <div class="control">
                    <input class="input" type="text" id="reset_username" name="username" placeholder="username" required>
                    <svg class="control-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
            </div>
            <div class="form-group" style="margin-top: 14px;">
                <label class="label" for="reset_alasan">Alasan / Catatan (Opsional)</label>
                <div class="control">
                    <input class="input" type="text" id="reset_alasan" name="alasan" placeholder="Contoh: Lupa password akun bidang">
                    <svg class="control-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn-secondary" id="closeForgotModal">Batal</button>
                <button type="submit" class="btn-primary">Kirim Ke Admin</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const eyeOpen = document.getElementById('eyeOpen');
        const eyeClosed = document.getElementById('eyeClosed');

        if (togglePassword && passwordInput) {
            togglePassword.addEventListener('click', function () {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                
                if (type === 'password') {
                    eyeOpen.classList.remove('hidden');
                    eyeClosed.classList.add('hidden');
                } else {
                    eyeOpen.classList.add('hidden');
                    eyeClosed.classList.remove('hidden');
                }
            });
        }

        // Modal Lupa Password
        const openForgotModal = document.getElementById('openForgotModal');
        const closeForgotModal = document.getElementById('closeForgotModal');
        const forgotModal = document.getElementById('forgotModal');

        if (openForgotModal && forgotModal) {
            openForgotModal.addEventListener('click', function() {
                forgotModal.classList.add('active');
            });
        }

        if (closeForgotModal && forgotModal) {
            closeForgotModal.addEventListener('click', function() {
                forgotModal.classList.remove('active');
            });
        }

        if (forgotModal) {
            forgotModal.addEventListener('click', function(e) {
                if (e.target === forgotModal) {
                    forgotModal.classList.remove('active');
                }
            });
        }
    });
</script>
@endsection