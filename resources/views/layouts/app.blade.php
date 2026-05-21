{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Smart-Way Glasses – @yield('title', 'Dashboard')</title>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  @stack('styles')
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --green-50 : #f0fdf4;
      --green-100: #dcfce7;
      --green-400: #4ade80;
      --green-500: #22c55e;
      --green-600: #16a34a;
      --red-500  : #ef4444;
      --red-600  : #dc2626;
      --gray-50  : #f8fafc;
      --gray-100 : #f1f5f9;
      --gray-200 : #e2e8f0;
      --gray-300 : #cbd5e1;
      --gray-400 : #94a3b8;
      --gray-500 : #64748b;
      --gray-700 : #334155;
      --gray-800 : #1e293b;
      --white    : #ffffff;
      --sw       : 210px;
    }

    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background: linear-gradient(160deg, #e8fdf0 0%, #f0fdf4 50%, #dcfce7 100%);
      min-height: 100vh;
      color: var(--gray-800);
    }

    .app { display: flex; min-height: 100vh; }

/* Container untuk tombol aksi di kanan navbar */
.header-actions {
    display: flex;
    align-items: center;
    gap: 16px;
}

/* Tombol Notifikasi */
.nav-icon-btn {
    position: relative;
    width: 38px;
    height: 38px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    background: #f8fafc;
    color: #64748b;
    transition: all 0.3s ease;
    cursor: pointer;
    border: 1px solid #e2e8f0;
}

.nav-icon-btn:hover {
    background: #f0fdf4;
    color: #22c55e;
    border-color: #bbf7d0;
    transform: translateY(-2px);
}

/* Titik Notifikasi Merah */
.badge-dot {
    position: absolute;
    top: 9px;
    right: 9px;
    width: 8px;
    height: 8px;
    background: #ef4444;
    border: 2px solid white;
    border-radius: 50%;
    z-index: 2;
}

/* Animasi Berdenyut (Pulse) */
.badge-pulse {
    position: absolute;
    top: 9px;
    right: 9px;
    width: 8px;
    height: 8px;
    background: #ef4444;
    border-radius: 50%;
    animation: pulse-red 2s infinite;
}

@keyframes pulse-red {
    0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
    70% { transform: scale(1.5); box-shadow: 0 0 0 10px rgba(239, 68, 68, 0); }
    100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
}

/* Dropdown User Profile */
.user-dropdown-btn {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 6px 14px 6px 6px;
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 40px;
    transition: all 0.3s ease;
    cursor: pointer;
}

.user-dropdown-btn:hover {
    background: #f8fafc;
    border-color: #22c55e;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}

/* Lingkaran Inisial Nama */
.avatar-circle {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: linear-gradient(135deg, #22c55e, #16a34a);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    font-weight: 800;
    box-shadow: 0 2px 6px rgba(34, 197, 94, 0.3);
}

.user-info-text {
    display: flex;
    flex-direction: column;
    line-height: 1.2;
}

.user-name {
    font-size: 13px;
    font-weight: 700;
    color: #1e293b;
}

.user-role {
    font-size: 10px;
    color: #94a3b8;
    font-weight: 500;
}

    /* ── SIDEBAR ── */
    .sidebar {
      width: var(--sw);
      background: var(--white);
      border-right: 1px solid var(--gray-200);
      display: flex; flex-direction: column;
      position: fixed; inset: 0 auto 0 0;
      z-index: 200;
      box-shadow: 2px 0 16px rgba(0,0,0,0.05);
    }

    .sidebar-logo {
      display: flex; align-items: center; gap: 9px;
      padding: 14px 18px;
      border-bottom: 1px solid var(--gray-100);
    }

    /* Gambar kacamata sidebar */
    .logo-img {
      width: 48px;
      height: auto;
      object-fit: contain;
      filter: drop-shadow(0 1px 4px rgba(0,0,0,0.18));
    }

    .logo-text { font-size: 13px; font-weight: 800; color: var(--gray-800); line-height: 1.2; }
    .logo-text em { color: var(--green-600); font-style: normal; }

    .sidebar-nav { flex: 1; padding: 14px 12px; }

    .nav-item {
      display: flex; align-items: center; gap: 10px;
      padding: 10px 13px; border-radius: 10px;
      font-size: 13.5px; font-weight: 500; color: var(--gray-500);
      text-decoration: none; transition: all .18s; margin-bottom: 3px;
    }
    .nav-item:hover { background: var(--gray-50); color: var(--gray-800); }
    .nav-item.active { background: var(--green-500); color: #fff; font-weight: 700; }

    .nav-icon {
      width: 20px; height: 20px;
      display: flex; align-items: center; justify-content: center;
      flex-shrink: 0;
    }
    .nav-icon svg {
      width: 18px; height: 18px;
      stroke: currentColor; fill: none;
      stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round;
    }
    .nav-icon svg.icon-sensor { fill: currentColor; stroke: none; }

    .sidebar-footer { padding: 14px 16px; border-top: 1px solid var(--gray-100); }
    .user-box {
      display: flex; align-items: center; gap: 10px;
      background: var(--gray-50); border-radius: 10px; padding: 10px;
      margin-bottom: 10px;
    }
    .user-avatar {
      width: 36px; height: 36px; border-radius: 50%;
      background: var(--gray-200);
      display: flex; align-items: center; justify-content: center;
    }
    .user-avatar svg { width: 20px; height: 20px; stroke: var(--gray-500); fill: none; stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round; }
    .user-name  { font-size: 13px; font-weight: 700; color: var(--gray-800); }
    .user-email { font-size: 11px; color: var(--gray-400); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 130px; }

    .btn-logout-trigger {
      width: 100%; display: flex; align-items: center; justify-content: center; gap: 8px;
      padding: 11px; background: var(--red-500); color: white;
      border: none; border-radius: 10px; font-size: 13.5px; font-weight: 700;
      font-family: inherit; cursor: pointer; transition: background .18s;
    }
    .btn-logout-trigger:hover { background: var(--red-600); }
    .btn-logout-trigger svg { width: 16px; height: 16px; stroke: white; fill: none; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }

    /* ── TOPBAR ── */
    .main { margin-left: var(--sw); flex: 1; display: flex; flex-direction: column; }
    .topbar {
      display: flex; align-items: center; justify-content: space-between;
      padding: 10px 26px;
      background: rgba(255,255,255,.9); backdrop-filter: blur(10px);
      border-bottom: 1px solid var(--gray-100);
      position: sticky; top: 0; z-index: 100;
    }
    .topbar-brand {
      display: flex; align-items: center; gap: 10px;
      font-size: 17px; font-weight: 800; color: var(--gray-800);
    }

    /* Gambar kacamata topbar — lebih kecil */
    .topbar-glasses-img {
      width: 36px;
      height: auto;
      object-fit: contain;
      filter: drop-shadow(0 1px 3px rgba(0,0,0,0.12));
    }

    .topbar-right { display: flex; align-items: center; gap: 10px; }
    .icon-btn {
      width: 36px; height: 36px; border: 1px solid var(--gray-200); border-radius: 10px;
      background: white; cursor: pointer;
      display: flex; align-items: center; justify-content: center;
      transition: background .15s; position: relative;
    }
    .icon-btn svg { width: 18px; height: 18px; stroke: var(--gray-500); fill: none; stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round; }
    .icon-btn:hover { background: var(--gray-50); }
    .notif-dot {
      width: 7px; height: 7px; background: var(--red-500); border-radius: 50%;
      position: absolute; top: 6px; right: 6px;
    }
    .topbar-profile {
      display: flex; align-items: center; gap: 7px;
      padding: 6px 12px; border: 1px solid var(--gray-200); border-radius: 10px;
      background: white; font-size: 13px; font-weight: 600; cursor: pointer;
    }
    .topbar-profile svg { width: 16px; height: 16px; stroke: var(--gray-500); fill: none; stroke-width: 2; }

    /* ── PAGE ── */
    .page { padding: 22px 26px; }

    /* ── CARD ── */
    .card {
      background: white; border-radius: 16px; padding: 18px 20px;
      border: 1px solid rgba(0,0,0,.06); box-shadow: 0 2px 10px rgba(0,0,0,.04);
    }

    /* ══ MODAL LOGOUT ══ */
    .modal-backdrop {
      display: none;
      position: fixed; inset: 0; z-index: 999;
      background: rgba(0,0,0,0.35); backdrop-filter: blur(3px);
      align-items: center; justify-content: center;
    }
    .modal-backdrop.open { display: flex; }
    .modal-box {
      background: white; border-radius: 24px; padding: 48px 40px 40px;
      width: 100%; max-width: 520px; margin: 20px; position: relative;
      box-shadow: 0 24px 64px rgba(0,0,0,0.18);
      animation: modalIn .22s ease both; text-align: center;
    }
    @keyframes modalIn {
      from { opacity:0; transform: scale(.93) translateY(16px); }
      to   { opacity:1; transform: scale(1) translateY(0); }
    }
    .modal-close {
      position: absolute; top: 18px; right: 20px;
      background: none; border: none; cursor: pointer;
      font-size: 22px; color: var(--gray-400); line-height: 1; transition: color .15s;
    }
    .modal-close:hover { color: var(--gray-800); }
    .modal-text { font-size: 22px; font-weight: 800; color: var(--gray-800); line-height: 1.4; margin-bottom: 36px; }
    .modal-actions { display: flex; gap: 16px; justify-content: center; }
    .btn-batal {
      flex: 1; padding: 16px 24px; background: var(--red-500); color: white;
      border: none; border-radius: 50px; font-size: 15px; font-weight: 800;
      font-family: inherit; letter-spacing: 1px; cursor: pointer; transition: all .18s;
    }
    .btn-batal:hover { background: var(--red-600); transform: translateY(-1px); }
    .btn-konfirmasi {
      flex: 1; padding: 16px 24px; background: var(--green-500); color: white;
      border: none; border-radius: 50px; font-size: 15px; font-weight: 800;
      font-family: inherit; letter-spacing: 1px; cursor: pointer; transition: all .18s;
    }
    .btn-konfirmasi:hover { background: var(--green-600); transform: translateY(-1px); }
    #logout-form { display: none; }
  </style>
</head>
<body>
<div class="app">

  {{-- ── SIDEBAR ── --}}
  <aside class="sidebar">

    {{-- Logo: glasses.png + teks --}}
    <div class="sidebar-logo">
      <img
        src="{{ asset('images/mata.png') }}"
        alt="Smart-Way Glasses"
        class="logo-img"
        onerror="this.style.display='none'">
      <div class="logo-text">Smart-Way<br><em>Glasses</em></div>
    </div>

    <nav class="sidebar-nav">

      <a href="{{ route('dashboard') }}"
         class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <span class="nav-icon">
          <svg viewBox="0 0 24 24">
            <path d="M3 12L12 3l9 9"/>
            <path d="M9 21V12h6v9"/>
            <path d="M3 12v9h18V12"/>
          </svg>
        </span>
        Dashboard
      </a>

      <a href="{{ route('sensor') }}"
         class="nav-item {{ request()->routeIs('sensor') ? 'active' : '' }}">
        <span class="nav-icon">
          <svg viewBox="0 0 24 24" class="icon-sensor">
            <rect x="2"  y="14" width="4" height="8" rx="1"/>
            <rect x="9"  y="9"  width="4" height="13" rx="1"/>
            <rect x="16" y="4"  width="4" height="18" rx="1"/>
          </svg>
        </span>
        Sensor data
      </a>

      <a href="{{ route('gps') }}"
         class="nav-item {{ request()->routeIs('gps') ? 'active' : '' }}">
        <span class="nav-icon">
          <svg viewBox="0 0 24 24">
            <path d="M12 2C8.686 2 6 4.686 6 8c0 5.25 6 13 6 13s6-7.75 6-13c0-3.314-2.686-6-6-6z"/>
            <circle cx="12" cy="8" r="2.5" fill="currentColor" stroke="none"/>
          </svg>
        </span>
        GPS Location
      </a>

      <a href="{{ route('camera') }}"
         class="nav-item {{ request()->routeIs('camera') ? 'active' : '' }}">
        <span class="nav-icon">
          <svg viewBox="0 0 24 24">
            <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
            <circle cx="12" cy="13" r="4"/>
          </svg>
        </span>
        Camera
      </a>

      <a href="{{ route('settings') }}"
         class="nav-item {{ request()->routeIs('settings') ? 'active' : '' }}">
        <span class="nav-icon">
          <svg viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="3"/>
            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>
          </svg>
        </span>
        Settings
      </a>

    </nav>

    <div class="sidebar-footer">
      <div class="user-box">
        <div class="user-avatar">
          <svg viewBox="0 0 24 24">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
            <circle cx="12" cy="7" r="4"/>
          </svg>
        </div>
        <div>
          <div class="user-name">{{ auth()->user()->name ?? 'User' }}</div>
          <div class="user-email">{{ auth()->user()->email ?? 'user@gmail.com' }}</div>
        </div>
      </div>

      <button type="button" class="btn-logout-trigger" onclick="openLogoutModal()">
        <svg viewBox="0 0 24 24">
          <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
          <polyline points="16 17 21 12 16 7"/>
          <line x1="21" y1="12" x2="9" y2="12"/>
        </svg>
        Keluar
      </button>
    </div>
  </aside>

  {{-- ── MAIN ── --}}
  <div class="main">
    <header class="topbar">
      <div class="topbar-brand">
        {{-- glasses.png di topbar --}}
        <img
          src="{{ asset('images/mata.png') }}"
          alt="Smart-Way Glasses"
          class="topbar-glasses-img"
          onerror="this.style.display='none'">
        Smart-Way Glasses
      </div>
      <div class="topbar-right">
        <div class="icon-btn">
          <svg viewBox="0 0 24 24">
            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
            <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
          </svg>
          <span class="notif-dot"></span>
        </div>
        <div class="icon-btn">
          <svg viewBox="0 0 24 24">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
            <circle cx="12" cy="7" r="4"/>
          </svg>
        </div>
        <div class="topbar-profile">
          {{ auth()->user()->name ?? 'User' }}
          <svg viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
        </div>
      </div>
    </header>

    <main class="page">
      @yield('content')
    </main>
  </div>

</div>

{{-- MODAL LOGOUT --}}
<div class="modal-backdrop" id="logout-modal" onclick="closeOnBackdrop(event)">
  <div class="modal-box" id="modal-box">
    <button class="modal-close" onclick="closeLogoutModal()" aria-label="Tutup">✕</button>
    <div class="modal-text">
      Apakah anda yakin ingin keluar (Logout)<br>dari dashboard?
    </div>
    <div class="modal-actions">
      <button class="btn-batal" onclick="closeLogoutModal()">BATAL</button>
      <button class="btn-konfirmasi" onclick="doLogout()">KONFIRMASI</button>
    </div>
  </div>
</div>

<form id="logout-form" method="POST" action="{{ route('logout') }}">
  @csrf
</form>

<script>
  function openLogoutModal() {
    document.getElementById('logout-modal').classList.add('open');
    document.body.style.overflow = 'hidden';
  }
  function closeLogoutModal() {
    document.getElementById('logout-modal').classList.remove('open');
    document.body.style.overflow = '';
  }
  function closeOnBackdrop(e) {
    if (e.target === document.getElementById('logout-modal')) closeLogoutModal();
  }
  document.addEventListener('keydown', e => { if (e.key === 'Escape') closeLogoutModal(); });
  function doLogout() { document.getElementById('logout-form').submit(); }
</script>

@stack('scripts')
</body>
</html>