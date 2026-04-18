<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password – Smart-Way Glasses</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500&display=swap');

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html, body {
            height: 100%;
            font-family: 'DM Sans', sans-serif;
            background: #f5f5f5;
        }

        /* ── Outer page wrapper ── */
        .page {
            min-height: 100vh;
            display: flex;
            align-items: flex-start;
            padding: 0;
        }

        /* ── Labelled container ── */
        .forgot-container {
            width: 100%;
        }

        .forgot-label {
            font-size: 13px;
            font-weight: 500;
            color: #555;
            padding: 14px 20px 0;
        }

        /* ── White outer box ── */
        .forgot-box {
            background: #fff;
            margin: 8px 16px 16px;
            border-radius: 8px;
            min-height: calc(100vh - 60px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        /* ── Inner layout: glasses + form ── */
        .inner {
            display: flex;
            align-items: center;
            gap: 0;
            width: 720px;
            max-width: 100%;
        }

        /* ── Glasses side ── */
        .glasses-side {
            flex: 0 0 320px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding-right: 10px;
        }

        .glasses-img {
            width: 260px;
            max-width: 100%;
            filter: drop-shadow(4px 18px 20px rgba(0,0,0,0.13));
            animation: floatGlasses 4s ease-in-out infinite;
        }

        @keyframes floatGlasses {
            0%,100% { transform: translateY(0) rotate(-2deg); }
            50%      { transform: translateY(-10px) rotate(0deg); }
        }

        /* Shadow under glasses */
        .glasses-shadow {
            width: 200px;
            height: 18px;
            background: radial-gradient(ellipse, rgba(0,0,0,0.12) 0%, transparent 70%);
            border-radius: 50%;
            margin-top: -8px;
        }

        /* ── Form side ── */
        .form-side {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .brand-title {
            font-size: 1.6rem;
            font-weight: 700;
            color: #111;
            text-align: center;
            margin-bottom: 4px;
        }

        .brand-sub {
            font-size: 0.78rem;
            color: #888;
            text-align: center;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        /* ── Form card ── */
        .form-card {
            width: 100%;
            max-width: 340px;
            border: 1.5px solid #ccc;
            border-radius: 6px;
            padding: 28px 28px 32px;
        }

        .form-heading {
            text-align: center;
            font-size: 0.88rem;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: #111;
            margin-bottom: 22px;
        }

        /* ── Input group ── */
        .input-group {
            position: relative;
            margin-bottom: 20px;
        }

        .input-group .icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            width: 17px;
            height: 17px;
            color: #aaa;
            pointer-events: none;
        }

        .input-group input {
            width: 100%;
            padding: 12px 14px 12px 42px;
            border: 1.5px solid #ddd;
            border-radius: 30px;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.83rem;
            color: #333;
            background: #fff;
            outline: none;
            transition: border-color .2s;
        }

        .input-group input:focus {
            border-color: #555;
        }

        .input-group input::placeholder {
            color: #bbb;
        }

        .input-group input.is-error {
            border-color: #e74c3c;
        }

        .error-text {
            font-size: 0.7rem;
            color: #e74c3c;
            margin-top: 5px;
            padding-left: 14px;
        }

        /* ── Alert status ── */
        .alert-status {
            background: #f0fdf4;
            border: 1px solid #86efac;
            color: #166534;
            font-size: 0.76rem;
            padding: 9px 14px;
            border-radius: 6px;
            margin-bottom: 14px;
        }

        /* ── Submit button ── */
        .btn-kirim {
            width: 100%;
            padding: 13px;
            background: #fff;
            border: 2px solid #111;
            border-radius: 30px;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.95rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            color: #111;
            cursor: pointer;
            transition: background .2s, color .2s, transform .15s, box-shadow .2s;
        }

        .btn-kirim:hover {
            background: #111;
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(0,0,0,0.12);
        }

        .btn-kirim:active {
            transform: translateY(0);
            box-shadow: none;
        }

        /* ── Back to login link ── */
        .back-link {
            text-align: center;
            margin-top: 16px;
            font-size: 0.74rem;
        }

        .back-link a {
            color: #666;
            text-decoration: none;
            transition: color .2s;
        }

        .back-link a:hover { color: #111; }

        /* ── Responsive ── */
        @media (max-width: 600px) {
            .glasses-side { display: none; }
            .form-card { max-width: 100%; }
            .forgot-box { padding: 30px 16px; }
        }
    </style>
</head>
<body>
<div class="page">
    <div class="forgot-container">
        <div class="forgot-box">
            <div class="inner">

                {{-- Kacamata --}}
                <div class="glasses-side">
                    <img
                        src="{{ asset('images/glasses.png') }}"
                        alt="Smart-Way Glasses"
                        class="glasses-img"
                        onerror="this.style.display='none'">
                    <div class="glasses-shadow"></div>
                </div>

                {{-- Form --}}
                <div class="form-side">
                    <div class="brand-title">Smart-Way Glasses</div>
                    <div class="brand-sub">Silahkan masukkan email yang terdaftar untuk<br>mendapatkan password anda</div>

                    <div class="form-card">
                        <div class="form-heading">Lupa Password</div>

                        @if (session('status'))
                            <div class="alert-status">{{ session('status') }}</div>
                        @endif

                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf

                            {{-- Email --}}
                            <div class="input-group">
                                <svg class="icon" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <input
                                    type="email"
                                    name="email"
                                    placeholder="Email"
                                    value="{{ old('email') }}"
                                    autocomplete="email"
                                    autofocus
                                    class="{{ $errors->has('email') ? 'is-error' : '' }}">
                                @error('email')
                                    <div class="error-text">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn-kirim">Kirim</button>
                        </form>

                        <div class="back-link">
                            <a href="{{ route('login') }}">← Kembali ke halaman Login</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
</body>
</html>