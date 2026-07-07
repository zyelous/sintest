@extends('layouts.guest')

@section('content')
<style>
    * { box-sizing: border-box }
    .login-page { display:flex; width:100%; min-height:100dvh; overflow:hidden; background:#fff; color:#1e293b }
    .login-hero { position:relative; flex:0 0 60%; min-height:100dvh; overflow:hidden }
    .login-hero img { position:absolute; inset:0; width:100%; height:100%; object-fit:cover }
    .login-hero::after { content:""; position:absolute; inset:0; background:linear-gradient(to top,rgba(1,38,69,.88),rgba(9,50,78,.42),rgba(25,65,91,.3)),rgba(1,38,69,.48) }
    .brand { position:relative; z-index:1; padding:clamp(42px,5vw,76px) clamp(42px,5.5vw,88px); color:#fff }
    .brand p { margin:0; font-size:clamp(24px,1.8vw,32px); font-weight:600 }
    .brand h1 { max-width:760px; margin:26px 0 0; font-size:clamp(30px,2.4vw,44px); line-height:1.18; text-transform:uppercase }
    .login-panel { display:flex; flex:0 0 40%; min-height:100dvh; align-items:center; justify-content:center; padding:40px clamp(32px,4vw,72px); overflow-y:auto }
    .login-box { width:100%; max-width:420px; margin:auto }
    .mobile-brand { display:none }
    .login-box h2 { margin:0; font-size:30px }
    .intro { margin:9px 0 0; color:#4b5563; line-height:1.55 }
    form { display:grid; gap:22px; margin-top:38px }
    .label { display:block; margin-bottom:8px; color:#475569; font-size:12px; font-weight:700; text-transform:uppercase }
    .control { position:relative }
    .control > svg { position:absolute; top:50%; left:15px; width:20px; color:#64748b; transform:translateY(-50%); pointer-events:none }
    .input { width:100%; height:49px; padding:0 46px 0 48px; border:1px solid #cbd5e1; border-radius:4px; outline:0; font:inherit }
    .input::placeholder { color:#94a3b8 }
    .input:focus { border-color:#0b4775; box-shadow:0 0 0 3px rgba(11,71,117,.12) }
    .input.error { border-color:#ef4444 }
    .error-text { margin:6px 0 0; color:#ef4444; font-size:12px }
    .eye { position:absolute; top:0; right:0; display:grid; width:48px; height:49px; place-items:center; border:0; background:transparent; color:#64748b; cursor:pointer }
    .eye svg { width:20px }
    .hidden { display:none }
    .remember { display:flex; width:max-content; align-items:center; gap:10px; color:#4b5563; font-size:14px; cursor:pointer }
    .remember input { width:20px; height:20px; margin:0; accent-color:#073b63 }
    .submit { display:flex; width:100%; height:56px; align-items:center; justify-content:center; gap:9px; border:0; border-radius:4px; background:#073b63; color:#fff; font-family:inherit; font-size:16px; font-weight:600; cursor:pointer; box-shadow:0 8px 18px rgba(7,59,99,.2) }
    .submit:hover { background:#052d4b }
    .submit svg { width:20px }

    @media (max-width:1023px) {
        .login-hero { display:none }
        .login-panel { flex-basis:100%; padding:32px 24px }
        .mobile-brand { display:block; margin-bottom:38px; color:#073b63; font-size:20px; font-weight:700 }
    }
</style>

<div class="login-page">
    <section class="login-hero">
<<<<<<< Updated upstream
        <img src="{{ asset('images/login_bg.png') }}" alt="Latar SINTARA">
=======
        <img src="{{ asset('images/loginbg.png') }}" alt="Latar SINTARA">
>>>>>>> Stashed changes
        <div class="brand">
            <p>SINTARA</p>
            <h1>Sistem Informasi Monitoring<br>dan Tracking Arsip</h1>
        </div>
    </section>

    <main class="login-panel">
        <div class="login-box">
            <div class="mobile-brand">SINTARA</div>
            <h2>Selamat Datang</h2>
            <p class="intro">Silakan masuk dengan akun resmi Anda untuk melanjutkan ke dashboard sistem.</p>

            <form method="POST" action="{{ route('login.submit') }}">
                @csrf

                <div>
                    <label class="label" for="username">Username</label>
                    <div class="control">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <input class="input {{ $errors->has('username') ? 'error' : '' }}" type="text" id="username" name="username" value="{{ old('username') }}" placeholder="username" autocomplete="username" required autofocus>
                    </div>
                    @error('username') <p class="error-text">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="label" for="password">Kata Sandi</label>
                    <div class="control">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <input class="input {{ $errors->has('password') ? 'error' : '' }}" type="password" id="password" name="password" placeholder="••••••••" autocomplete="current-password" required>
                        <button class="eye" type="button" id="togglePassword" aria-label="Tampilkan kata sandi">
                            <svg id="eyeOpen" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7s-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg id="eyeClosed" class="hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18M10.6 10.6a2 2 0 002.8 2.8M9.9 5.1A10.6 10.6 0 0112 5c4.5 0 8.3 2.9 9.5 7a10.4 10.4 0 01-2.1 3.8M6.6 6.6A10.2 10.2 0 002.5 12c1.2 4.1 5 7 9.5 7 1.1 0 2.2-.2 3.1-.5"/>
                            </svg>
                        </button>
                    </div>
                    @error('password') <p class="error-text">{{ $message }}</p> @enderror
                </div>

                <label class="remember">
                    <input type="checkbox" name="remember">
                    <span>Ingat Saya</span>
                </label>

                <button class="submit" type="submit">
                    Masuk Ke Sistem
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l4-4-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>