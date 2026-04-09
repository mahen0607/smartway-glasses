{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>SmartWay Glasses - @yield('title', 'Dashboard')</title>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  @stack('styles')
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    :root {
      --green-50: #f0fdf4; --green-100: #dcfce7; --green-400: #4ade80;
      --green-500: #22c55e; --green-600: #16a34a; --green-700: #15803d;
      --red-400: #f87171; --red-500: #ef4444; --red-600: #dc2626;
      --gray-50: #f8fafc; --gray-100: #f1f5f9; --gray-200: #e2e8f0;
      --gray-300: #cbd5e1; --gray-400: #94a3b8; --gray-500: #64748b;
      --gray-700: #334155; --gray-800: #1e293b; --gray-900: #0f172a;
      --white: #ffffff; --sidebar-w: 220px;
    }
    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background: linear-gradient(160deg, #e8fdf0 0%, #f0fdf4 40%, #dcfce7 100%);
      min-height: 100vh;
      color: var(--gray-800);
    }
    .app-wrapper { display: flex; min-height: 100vh; }

    /* SIDEBAR */
    .sidebar {
      width: var(--sidebar-w);
      background: var(--white);
      border-right: 1px solid var(--gray-200);
      display: flex; flex-direction: column;
      position: fixed; top: 0; left: 0; bottom: 0;
      z-index: 100;
      box-shadow: 2px 0 12px rgba(0,0,0,0.04);
    }
    .sidebar-logo {
      display: flex; align-items: center; gap: 10px;
      padding: 20px 20px 16px;
      border-bottom: 1px solid var(--gray-100);
    }
    .logo-icon {
      width: 34px; height: 22px;
      background: var(--gray-800); border-radius: 11px;
      position: relative; display: flex; align-items: center; justify-content: center;
    }
    .logo-icon::before, .logo-icon::after {
      content: ''; width: 10px; height: 10px;
      border: 2.5px solid var(--white); border-radius: 50%; position: absolute;
    }
    .logo-icon::before { left: 4px; }
    .logo-icon::after { right: 4px; }
    .logo-text { font-size: 13px; font-weight: 700; color: var(--gray-800); line-height: 1.2; }
    .logo-text span { color: var(--green-600); }
    .sidebar-nav { flex: 1; padding: 12px; }
    .nav-item {
      display: flex; align-items: center; gap: 10px;
      padding: 10px 12px; border-radius: 10px; cursor: pointer;
      font-size: 13.5px; font-weight: 500; color: var(--gray-500);
      transition: all 0.18s ease; margin-bottom: 2px; text-decoration: none;
    }
    .nav-item:hover { background: var(--gray-50); color: var(--gray-800); }
    .nav-item.active { background: var(--green-500); color: white; font-weight: 600; }
    .nav-icon { font-size: 15px; width: 18px; text-align: center; }
    .sidebar-user { padding: 14px 16px; border-top: 1px solid var(--gray-100); }
    .user-card {
      display: flex; align-items: center; gap: 10px;
      padding: 10px; background: var(--gray-50); border-radius: 10px; margin-bottom: 10px;
    }
    .user-avatar {
      width: 36px; height: 36px; background: var(--gray-200);
      border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 15px;
    }
    .user-info { flex: 1; min-width: 0; }
    .user-name { font-size: 13px; font-weight: 600; color: var(--gray-800); }
    .user-email { font-size: 11px; color: var(--gray-400); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .btn-logout {
      width: 100%; display: flex; align-items: center; justify-content: center; gap: 8px;
      padding: 10px; background: var(--red-500); color: white; border: none;
      border-radius: 10px; font-size: 13.5px; font-weight: 600; cursor: pointer;
      transition: background 0.18s; font-family: inherit;
    }
    .btn-logout:hover { background: var(--red-600); }

    /* MAIN */
    .main-content { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; }
    .topbar {
      display: flex; align-items: center; justify-content: space-between;
      padding: 16px 28px;
      background: rgba(255,255,255,0.85); backdrop-filter: blur(8px);
      border-bottom: 1px solid var(--gray-100);
      position: sticky; top: 0; z-index: 50;
    }
    .topbar-title { display: flex; align-items: center; gap: 10px; font-size: 20px; font-weight: 700; }
    .topbar-right { display: flex; align-items: center; gap: 12px; }
    .notif-btn {
      width: 36px; height: 36px; border: 1px solid var(--gray-200); border-radius: 10px;
      background: white; cursor: pointer; display: flex; align-items: center; justify-content: center;
      font-size: 16px; position: relative; transition: all 0.15s;
    }
    .notif-btn:hover { background: var(--gray-50); }
    .notif-dot {
      width: 7px; height: 7px; background: var(--red-500); border-radius: 50%;
      position: absolute; top: 7px; right: 7px;
    }
    .live-badge {
      display: inline-flex; align-items: center; gap: 6px;
      background: var(--green-500); color: white;
      padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 700;
    }
    .live-pulse {
      width: 6px; height: 6px; background: white; border-radius: 50%;
      animation: pulse 1.4s infinite;
    }
    @keyframes pulse { 0%, 100% { opacity: 1; transform: scale(1); } 50% { opacity: 0.5; transform: scale(0.7); } }
    .page { padding: 24px 28px; }
    .card {
      background: white; border-radius: 16px; padding: 20px;
      border: 1px solid rgba(0,0,0,0.06); box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    .card-title { font-size: 13px; font-weight: 600; color: var(--gray-500); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 14px; }
  </style>
</head>
<body>
<div class="app-wrapper">

  {{-- SIDEBAR --}}
  <aside class="sidebar">
    <div class="sidebar-logo">
      <div class="logo-icon"></div>
      <div class="logo-text">Monitoring<br><span>Kacamata Tunanetra</span></div>
    </div>
    <nav class="sidebar-nav">
      <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <span class="nav-icon">🏠</span> Dashboard
      </a>
      <a href="{{ route('sensor') }}" class="nav-item {{ request()->routeIs('sensor') ? 'active' : '' }}">
        <span class="nav-icon">📊</span> Sensor Data
      </a>
      <a href="{{ route('gps') }}" class="nav-item {{ request()->routeIs('gps') ? 'active' : '' }}">
        <span class="nav-icon">📍</span> GPS Location
      </a>
      <a href="{{ route('camera') }}" class="nav-item {{ request()->routeIs('camera') ? 'active' : '' }}">
        <span class="nav-icon">📷</span> Camera
      </a>
      <a href="{{ route('settings') }}" class="nav-item {{ request()->routeIs('settings') ? 'active' : '' }}">
        <span class="nav-icon">⚙️</span> Settings
      </a>
    </nav>
    <div class="sidebar-user">
      <div class="user-card">
        <div class="user-avatar">👤</div>
        <div class="user-info">
          <div class="user-name">{{ auth()->user()->name ?? 'User' }}</div>
          <div class="user-email">{{ auth()->user()->email ?? 'user@gmail.com' }}</div>
        </div>
      </div>
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn-logout">← Keluar</button>
      </form>
    </div>
  </aside>

  {{-- MAIN --}}
  <div class="main-content">
    <header class="topbar">
      <div class="topbar-title">
        <span>@yield('page-icon', '📊')</span>
        @yield('page-title', 'Dashboard')
      </div>
      <div class="topbar-right">
        <div class="live-badge"><span class="live-pulse"></span> LIVE</div>
        <button class="notif-btn">🔔<span class="notif-dot"></span></button>
        <div style="display:flex;align-items:center;gap:8px;padding:6px 12px;border:1px solid var(--gray-200);border-radius:10px;background:white;font-size:13px;font-weight:500;">
          👤 {{ auth()->user()->name ?? 'User' }} ▾
        </div>
      </div>
    </header>

    <main class="page">
      @yield('content')
    </main>
  </div>
</div>
@stack('scripts')
</body>
</html>
