{{-- resources/views/camera.blade.php --}}
@extends('layouts.app')
@section('title','Camera')

@push('styles')
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
  .page-heading {
    display: flex; align-items: center; gap: 10px;
    font-size: 22px; font-weight: 800; color: var(--gray-800);
    margin-bottom: 22px;
  }

  .camera-card { padding: 20px; }

  /* ── VIEWPORT ── */
  .camera-viewport {
    width: 100%; background: #000;
    border-radius: 12px; position: relative; overflow: hidden;
    border: 2px solid #1a1a1a;
    min-height: 420px;
  }

  #stream-wrap { width: 100%; height: 100%; min-height: 420px; position: relative; }
  #live-stream { width: 100%; height: 100%; min-height: 420px; object-fit: cover; display: block; }

  #offline-state {
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    min-height: 420px; gap: 12px;
    background: #0a0a0a;
  }
  .offline-icon { font-size: 48px; opacity: .35; }
  .offline-text { font-size: 15px; color: rgba(255,255,255,.4); font-weight: 600; }

  /* ── OVERLAY ── */
  .cam-overlay {
    position: absolute; inset: 0;
    display: flex; flex-direction: column; justify-content: space-between;
    padding: 14px 18px; pointer-events: none; z-index: 10;
  }

  .cam-top { display: flex; align-items: center; justify-content: space-between; }
  .rec-badge { display: flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 700; color: white; letter-spacing: 1px; }
  .rec-dot { width: 10px; height: 10px; background: #ef4444; border-radius: 50%; box-shadow: 0 0 6px rgba(239,68,68,.8); animation: blink 1.1s infinite; }
  @keyframes blink { 0%,100%{opacity:1} 50%{opacity:.1} }

  .cam-top-right { display: flex; align-items: center; gap: 8px; }

  .stream-badge { padding: 4px 11px; border-radius: 20px; font-size: 11px; font-weight: 700; }
  .stream-badge.live    { background: rgba(34,197,94,.85); color: white; }
  .stream-badge.offline { background: rgba(239,68,68,.85);  color: white; }

  .battery-body {
    width: 26px; height: 13px; border: 1.5px solid rgba(255,255,255,.6); border-radius: 3px;
    position: relative; display: flex; align-items: center; padding: 2px;
  }
  .battery-body::after {
    content:''; position:absolute; right:-4px; top:50%; transform:translateY(-50%);
    width:3px; height:6px; background:rgba(255,255,255,.6); border-radius:0 2px 2px 0;
  }
  .battery-fill { height:100%; border-radius:1px; background:#4ade80; transition: width .5s; }

  .cam-crosshair {
    position: absolute; top:50%; left:50%; transform:translate(-50%,-50%);
    width: 150px; height: 110px; pointer-events: none; z-index: 10;
  }
  .ch { position:absolute; width:22px; height:22px; border-color:rgba(255,255,255,.4); border-style:solid; }
  .ch.tl { top:0; left:0; border-width:2px 0 0 2px; }
  .ch.tr { top:0; right:0; border-width:2px 2px 0 0; }
  .ch.bl { bottom:0; left:0; border-width:0 0 2px 2px; }
  .ch.br { bottom:0; right:0; border-width:0 2px 2px 0; }
  .ch-plus { position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);color:rgba(255,255,255,.35);font-size:20px; }

  .cam-bottom { display:flex; align-items:center; justify-content:space-between; }
  .tc-text { font-size:13px;font-weight:700;color:white;font-variant-numeric:tabular-nums;letter-spacing:1px; }
  .cam-specs { font-size:12px;font-weight:700;color:rgba(255,255,255,.65); }

  .scan-line {
    position:absolute;left:0;right:0;height:1px;z-index:5;
    background:linear-gradient(90deg,transparent,rgba(74,222,128,.3),transparent);
    animation:scan 4s linear infinite; pointer-events:none;
  }
  @keyframes scan { 0%{top:5%;opacity:0}5%{opacity:1}95%{opacity:1}100%{top:95%;opacity:0} }

  /* ── STATUS TILES ── */
  .status-row { display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-top:16px; }
  .stile { background:var(--gray-50);border:1px solid var(--gray-200);border-radius:12px;padding:12px 14px;text-align:center; }
  .stile-label { font-size:10px;color:var(--gray-400);font-weight:600;text-transform:uppercase;margin-bottom:5px; }
  .stile-value { font-size:15px;font-weight:800;color:var(--gray-800); }
  .stile-value.green { color:var(--green-600); }
  .stile-value.red   { color:#ef4444; }

  /* ── KONTROL ── */
  .ctrl-row { display:flex;align-items:center;justify-content:space-between;margin-top:14px;flex-wrap:wrap;gap:10px; }
  .ctrl-group { display:flex;align-items:center;gap:8px; }
  .ctrl-label { font-size:12px;font-weight:600;color:var(--gray-500); }
  .ctrl-btn {
    padding:7px 14px; border:1.5px solid var(--gray-200); border-radius:8px;
    font-size:12px;font-weight:700;background:white;
    cursor:pointer;transition:all .18s;color:var(--gray-700);
  }
  .ctrl-btn:hover  { background:var(--gray-100); }
  .ctrl-btn.active { background:var(--green-500); border-color:var(--green-500); color:white; }
  .ctrl-btn.flash-on  { background:#f59e0b; border-color:#d97706; color:white; }
  .ctrl-btn.cam-on  { background:#22c55e; border-color:#16a34a; color:white; }
  .ctrl-btn.cam-off { background:#ef4444; border-color:#dc2626; color:white; }
</style>
@endpush

@section('content')

<div class="page-heading">
    <i class="fa-solid fa-camera"></i>
    Camera Feed
</div>

<div class="card camera-card">
  {{-- ── VIEWPORT ── --}}
  <div class="camera-viewport" id="viewport">
    <div class="scan-line"></div>
    <div class="cam-crosshair">
      <div class="ch tl"></div><div class="ch tr"></div>
      <div class="ch bl"></div><div class="ch br"></div>
      <div class="ch-plus">+</div>
    </div>

    <div id="stream-wrap" style="display:none;">
      <img id="live-stream" src="" alt="Live Stream">
    </div>

    <div id="offline-state">
      <div class="offline-icon">📷</div>
      <div class="offline-text">Camera Powered Off</div>
    </div>

    <div class="cam-overlay">
      <div class="cam-top">
        <div class="rec-badge"><span class="rec-dot"></span> REC</div>
        <div class="cam-top-right">
          <span class="stream-badge offline" id="stream-badge">OFFLINE</span>
          <div class="battery-body">
            <div class="battery-fill" id="bat-fill" style="width:{{ $device->battery_pct ?? 0 }}%"></div>
          </div>
        </div>
      </div>
      <div class="cam-bottom">
        <div class="tc-text" id="tc">00:00:00:00</div>
        <div class="cam-specs">30 FPS <span>|</span> 4K</div>
      </div>
    </div>
  </div>

  {{-- ── STATUS TILES ── --}}
  <div class="status-row">
    <div class="stile">
      <div class="stile-label">Status</div>
      <div class="stile-value red" id="t-status">Offline</div>
    </div>
    <div class="stile">
      <div class="stile-label">Resolusi</div>
      <div class="stile-value">VGA</div>
    </div>
    <div class="stile">
      <div class="stile-label">Frame Rate</div>
      <div class="stile-value">30 FPS</div>
    </div>
    <div class="stile">
      <div class="stile-label">Baterai</div>
      <div class="stile-value green" id="t-battery">{{ $device->battery_pct ?? 50 }}%</div>
    </div>
  </div>

  {{-- ── KONTROL ── --}}
  <div class="ctrl-row">
    <div class="ctrl-group">
      <span class="ctrl-label">Kualitas:</span>
      <button class="ctrl-btn" id="btn-tinggi" onclick="setKualitas(this,6)">Tinggi</button>
      <button class="ctrl-btn active" id="btn-sedang" onclick="setKualitas(this,12)">Sedang</button>
      <button class="ctrl-btn" id="btn-hemat"  onclick="setKualitas(this,20)">Hemat</button>
    </div>

    <div class="ctrl-group">
      <!-- FLASH -->
      <button class="ctrl-btn flash-off" id="btn-flash" onclick="toggleFlash()">
          <i class="fa-solid fa-bolt"></i> Flash OFF
      </button>

      <!-- SNAPSHOT -->
      <button class="ctrl-btn" onclick="snapshot()">
          <i class="fa-solid fa-camera-retro"></i> Snapshot
      </button>

      <!-- CAMERA (Mati secara default) -->
      <button class="ctrl-btn cam-off" id="btn-power" onclick="toggleKamera()">
          <i class="fa-solid fa-video-slash"></i> Kamera OFF
      </button>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
let camBaseURL   = @json(config('esp32.cam_base', ''));
let camStreamURL = @json(config('esp32.cam_url',  ''));
let flashState   = false;
let kameraAktif  = false; // Default OFF
let retryTimer   = null;

// ── Timecode Timer ──
let frames = 0;
setInterval(() => {
  frames++;
  const ff = frames % 30;
  const ts = Math.floor(frames / 30);
  const ss = ts % 60, mm = Math.floor(ts/60)%60, hh = Math.floor(ts/3600);
  const p = n => String(n).padStart(2,'0');
  document.getElementById('tc').textContent = `${p(hh)}:${p(mm)}:${p(ss)}:${p(ff)}`;
}, 1000/30);

// ── Core Stream Functions ──
function muatStream(url) {
  if (!url || !kameraAktif) return;

  const img = document.getElementById('live-stream');
  const wrap = document.getElementById('stream-wrap');
  const offline = document.getElementById('offline-state');
  
  img.src = url + (url.includes('?') ? '&' : '?') + 't=' + Date.now();

  img.onload = () => {
    wrap.style.display = 'block';
    offline.style.display = 'none';
    setUIStatus(true);
  };

  img.onerror = () => {
    wrap.style.display = 'none';
    offline.style.display = 'flex';
    setUIStatus(false);
    
    if (kameraAktif) {
        if (retryTimer) clearTimeout(retryTimer);
        retryTimer = setTimeout(() => muatStream(camStreamURL), 5000);
    }
  };
}

function setUIStatus(isOnline) {
  const badge = document.getElementById('stream-badge');
  const statusTxt = document.getElementById('t-status');
  
  if(isOnline) {
    badge.textContent = 'LIVE';
    badge.className = 'stream-badge live';
    statusTxt.textContent = 'Online';
    statusTxt.className = 'stile-value green';
  } else {
    badge.textContent = 'OFFLINE';
    badge.className = 'stream-badge offline';
    statusTxt.textContent = 'Offline';
    statusTxt.className = 'stile-value red';
  }
}

function toggleKamera() {
  kameraAktif = !kameraAktif;
  const btn = document.getElementById('btn-power');
  
  if (kameraAktif) {
    // UI saat ON
    btn.innerHTML = '<i class="fa-solid fa-video"></i> Kamera ON';
    btn.className = 'ctrl-btn cam-on';
    muatStream(camStreamURL);
  } else {
    // UI saat OFF
    btn.innerHTML = '<i class="fa-solid fa-video-slash"></i> Kamera OFF';
    btn.className = 'ctrl-btn cam-off';
    document.getElementById('live-stream').src = '';
    document.getElementById('stream-wrap').style.display = 'none';
    document.getElementById('offline-state').style.display = 'flex';
    setUIStatus(false);
    if (retryTimer) clearTimeout(retryTimer);
  }
}

// ── ESP32 Controls ──
function setCtrl(variable, value) {
  if (!camBaseURL) return;
  fetch(`${camBaseURL}/control?var=${variable}&val=${value}`, { mode: 'no-cors' });
}

function setKualitas(btn, q) {
  document.querySelectorAll('.ctrl-group .ctrl-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
  setCtrl('quality', q);
}

function toggleFlash() {
  flashState = !flashState;
  const btn = document.getElementById('btn-flash');
  btn.innerHTML = flashState ? '<i class="fa-solid fa-bolt"></i> Flash ON' : '<i class="fa-solid fa-bolt"></i> Flash OFF';
  btn.className = flashState ? 'ctrl-btn flash-on' : 'ctrl-btn flash-off';
  setCtrl('flash', flashState ? 1 : 0);
}

function snapshot() {
  if (!camBaseURL) return;
  const link = document.createElement('a');
  link.href = `${camBaseURL}/capture?t=` + Date.now();
  link.download = `snapshot_${Date.now()}.jpg`;
  link.click();
}

// ── Sync Battery ──
function pollBattery() {
  fetch('{{ route("api.device.status") }}')
    .then(r => r.json())
    .then(d => {
      if (d.battery_pct !== undefined) {
        document.getElementById('t-battery').textContent = d.battery_pct + '%';
        document.getElementById('bat-fill').style.width = d.battery_pct + '%';
      }
    }).catch(() => {});
}
setInterval(pollBattery, 10000);

// Init
document.addEventListener('DOMContentLoaded', () => {
  // Status awal UI diset ke Offline
  setUIStatus(false);
  // Tidak memanggil muatStream di sini agar kamera tetap mati saat pertama buka.
});
</script>
@endpush