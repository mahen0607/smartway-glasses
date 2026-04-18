<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login – Smart-Way Glasses</title>
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

        /* ── Labelled container (mimics the "Login" label + white box in screenshot) ── */
        .login-container {
            width: 100%;
        }

        .login-label {
            font-size: 13px;
            font-weight: 500;
            color: #555;
            padding: 14px 20px 0;
        }

        /* ── White outer box ── */
        .login-box {
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
            margin-bottom: 14px;
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
            border-radius: 30px;          /* pill shape like screenshot */
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

        /* ── Row: Register | Lupa Password ── */
        .form-links {
            display: flex;
            justify-content: space-between;
            margin: 6px 2px 20px;
        }

        .form-links a {
            font-size: 0.74rem;
            color: #666;
            text-decoration: none;
            transition: color .2s;
        }

        .form-links a:hover { color: #111; }

        /* ── Submit button ── */
        .btn-masuk {
            width: 100%;
            padding: 13px;
            background: #fff;
            border: 2px solid #111;
            border-radius: 30px;          /* pill */
            font-family: 'DM Sans', sans-serif;
            font-size: 0.95rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            color: #111;
            cursor: pointer;
            transition: background .2s, color .2s, transform .15s, box-shadow .2s;
        }

        .btn-masuk:hover {
            background: #111;
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(0,0,0,0.12);
        }

        .btn-masuk:active {
            transform: translateY(0);
            box-shadow: none;
        }

        /* ── Alert success ── */
        .alert-status {
            background: #f0fdf4;
            border: 1px solid #86efac;
            color: #166534;
            font-size: 0.76rem;
            padding: 9px 14px;
            border-radius: 6px;
            margin-bottom: 14px;
        }

        /* ── Responsive ── */
        @media (max-width: 600px) {
            .glasses-side { display: none; }
            .form-card { max-width: 100%; }
            .login-box { padding: 30px 16px; }
        }
    </style>
</head>
<body>
<div class="page">
    <div class="login-container">
        <div class="login-box">
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
                    <div class="brand-sub">Silahkan Login untuk dapat mengakses dashboard</div>

                    <div class="form-card">
                        <div class="form-heading">Login</div>

                        @if (session('status'))
                            <div class="alert-status">{{ session('status') }}</div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            {{-- Email / Username --}}
                            <div class="input-group">
                                {{-- Person icon --}}
                                <svg class="icon" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <input
                                    type="text"
                                    name="email"
                                    placeholder="Username / email"
                                    value="{{ old('email') }}"
                                    autocomplete="email"
                                    autofocus
                                    class="{{ $errors->has('email') ? 'is-error' : '' }}">
                                @error('email')
                                    <div class="error-text">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Password --}}
                            <div class="input-group">
                                {{-- Shield / lock icon --}}
                                <svg class="icon" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                                <input
                                    type="password"
                                    name="password"
                                    placeholder="Password"
                                    autocomplete="current-password"
                                    class="{{ $errors->has('password') ? 'is-error' : '' }}">
                                @error('password')
                                    <div class="error-text">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Links --}}
                            <div class="form-links">
                                <a href="{{ route('register') }}">Register</a>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}">Lupa Password?</a>
                                @endif
                            </div>

                            <button type="submit" class="btn-masuk">Masuk</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
</body>
</html>