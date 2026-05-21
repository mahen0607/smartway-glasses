{{-- resources/views/dashboard.blade.php --}}
@extends('layouts.app')
@section('title','Dashboard')

@push('styles')
<style>
  /* greeting */
  .greeting { margin-bottom: 20px; }
  .greeting-title { font-size: 24px; font-weight: 800; color: var(--gray-800); margin-bottom: 2px; }
  .greeting-sub   { font-size: 13px; color: var(--gray-400); font-weight: 400; }

  /* top grid: glasses card + sensor panel */
  .grid-top {
    display: grid;
    grid-template-columns: 1fr 260px;
    gap: 18px;
    margin-bottom: 16px;
  }

  /* glasses hero card */
  .glasses-card {
    display: flex; align-items: center; justify-content: center;
    min-height: 190px;
    background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
    border-radius: 16px;
    border: 1px solid rgba(0,0,0,.06);
    box-shadow: 0 2px 10px rgba(0,0,0,.04);
    position: relative; overflow: hidden;
  }
  .glasses-card::before {
    content: '';
    position: absolute; bottom: -20px; left: 50%; transform: translateX(-50%);
    width: 260px; height: 40px;
    background: radial-gradient(ellipse, rgba(0,0,0,.12) 0%, transparent 70%);
    border-radius: 50%;
  }
  .glasses-hero {
    width: 280px; max-width: 80%;
    filter: drop-shadow(0 12px 28px rgba(0,0,0,.2));
    animation: float 3.5s ease-in-out infinite;
  }
  @keyframes float {
    0%,100% { transform: translateY(0) rotate(-1deg); }
    50%      { transform: translateY(-10px) rotate(1deg); }
  }

  /* sensor toggle panel */
  .sensor-panel-title {
    display: flex; align-items: center; gap: 8px;
    font-size: 14px; font-weight: 700; color: var(--gray-800);
    margin-bottom: 14px;
  }
  .sensor-panel-title svg { width: 18px; height: 18px; fill: var(--gray-800); stroke: none; }
  .sensor-row {
    display: flex; align-items: center; justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid var(--gray-100);
  }
  .sensor-row:last-child { border-bottom: none; }
  .sensor-row-label { font-size: 13.5px; font-weight: 500; color: var(--gray-700); }

  /* toggle switch */
  .toggle { position: relative; display: inline-block; width: 52px; height: 28px; }
  .toggle input { opacity: 0; width: 0; height: 0; }
  .toggle-slider {
    position: absolute; inset: 0;
    border-radius: 28px;
    background: var(--gray-200);
    cursor: pointer; transition: background .25s;
  }
  .toggle-slider::before {
    content: ''; position: absolute;
    width: 22px; height: 22px; border-radius: 50%;
    background: white; top: 3px; left: 3px;
    box-shadow: 0 1px 4px rgba(0,0,0,.25);
    transition: transform .25s;
  }
  .toggle input:checked + .toggle-slider.green-on  { background: var(--green-500); }
  .toggle input:checked + .toggle-slider::before   { transform: translateX(24px); }

  /* status bar */
  .grid-status {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr 260px;
    gap: 14px;
    margin-bottom: 16px;
    align-items: stretch;
  }
  .status-card { display: flex; align-items: center; gap: 12px; padding: 14px 16px; }
  .status-icon-wrap {
    width: 40px; height: 40px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
  }
  .status-icon-wrap svg { width: 22px; height: 22px; stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round; }
  .status-icon-wrap.green { background: #dcfce7; }
  .status-icon-wrap.green svg { stroke: var(--green-600); fill: none; }
  .status-icon-wrap.red   { background: #fee2e2; }
  .status-icon-wrap.red   svg { stroke: var(--red-500); fill: none; }
  .status-icon-wrap.blue  { background: #dbeafe; }
  .status-icon-wrap.blue  svg { stroke: #3b82f6; fill: none; }

  .status-label { font-size: 13px; font-weight: 600; color: var(--gray-700); }
  .status-sub   { font-size: 11px; color: var(--gray-400); }
  .status-val   { font-size: 18px; font-weight: 800; margin-left: auto; }
  .status-val.green  { color: var(--green-600); }
  .status-val.red    { color: var(--red-500); }

  /* Camera Viewport */
  .camera-viewport {
    background: #000; border-radius: 10px;
    aspect-ratio: 16/9; position: relative; overflow: hidden;
  }
  #stream-wrap { width: 100%; height: 100%; display: none; }
  #live-stream { width: 100%; height: 100%; object-fit: cover; }
  
  #offline-state {
    position: absolute; inset: 0;
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    background: #0a0a0a; gap: 10px; z-index: 5;
  }

  .cam-overlay {
    position: absolute; inset: 0;
    display: flex; flex-direction: column; justify-content: space-between;
    padding: 10px 14px; z-index: 10; pointer-events: none;
  }
  .cam-top, .cam-bottom { display: flex; align-items: center; justify-content: space-between; }
  .rec-badge { display: flex; align-items: center; gap: 5px; font-size: 11px; font-weight: 700; color: white; letter-spacing: 1px; }
  .rec-dot { width: 8px; height: 8px; background: var(--red-500); border-radius: 50%; animation: blink 1.2s infinite; }
  @keyframes blink { 0%,100%{opacity:1}50%{opacity:.2} }
  
  .stream-badge { padding: 2px 8px; border-radius: 4px; font-size: 9px; font-weight: 800; color: white; }
  .stream-badge.live { background: #22c55e; }
  .stream-badge.offline { background: #ef4444; }

  .cam-time { font-size: 11px; color: rgba(255,255,255,.8); font-family: monospace; }
  
  .scan-line {
    position: absolute; left: 0; right: 0; height: 1px; z-index: 8;
    background: linear-gradient(90deg, transparent, rgba(34, 197, 94, 0.4), transparent);
    animation: scan 4s linear infinite; pointer-events: none;
  }
  @keyframes scan { 0%{top:5%} 100%{top:95%} }

  /* bottom grid */
  .grid-bottom { display: grid; grid-template-columns: 1fr 260px; gap: 18px; }
  .gps-map-wrap { border-radius: 12px; overflow: hidden; aspect-ratio: 1/.9; background: var(--gray-100); }
  .gps-map-wrap iframe { width:100%; height:100%; border:none; display:block; }

  /* GPS Label Card Link */
  /* GPS Label Card */
.gps-label-card{
    display: flex;
    align-items: center;
    justify-content: space-between;

    padding: 18px 22px; /* padding lebih lega */
    border-radius: 24px;

    font-size: 14px;
    font-weight: 700;
}

/* Bagian kiri */
.gps-title{
    display: flex;
    align-items: center;
    gap: 12px;
}

/* Icon maps */
.gps-title i{
    font-size: 22px;
    color: #ef4444;
}

/* Text GPS */
.gps-title span{
    font-size: 15px;
    font-weight: 700;
    color: var(--gray-800);
}

/* Tombol Live */
.config-link{
    margin-left: auto;
    font-size: 10px;

    background: var(--gray-100);
    padding: 8px 16px;

    border-radius: 999px;
    text-decoration: none;

    color: var(--gray-700);
    transition: all 0.2s ease;
}

.config-link:hover{
    background: var(--gray-200);
    color: var(--gray-900);
}
</style>
@endpush

@section('content')

<div class="greeting">
  <div class="greeting-title">
    @php
      $hour = now()->hour;
      $greeting = $hour < 12 ? 'Good Morning' : ($hour < 17 ? 'Good Afternoon' : 'Good Evening');
    @endphp
    {{ $greeting }}, {{ auth()->user()->name ?? 'User' }}
  </div>
  <div class="greeting-sub">Welcome to Smart-Way Glasses dashboard</div>
</div>

<div class="grid-top">
  <div class="glasses-card">
    <img class="glasses-hero" src="{{ asset('images/glasses.png') }}" alt="Smart-Way Glasses" onerror="this.src='https://via.placeholder.com/280x160?text=Smart-Way+Glasses'">
  </div>

  <div class="card sensor-panel">
    <div class="sensor-panel-title">
      <svg viewBox="0 0 24 24"><rect x="2" y="14" width="4" height="8" rx="1"/><rect x="9" y="9" width="4" height="13" rx="1"/><rect x="16" y="4" width="4" height="18" rx="1"/></svg>
      Sensor Control
    </div>

    @php
      $sensors = [
        ['label'=>'GPS',      'on'=>false,  'var' => 'gps'],
        ['label'=>'Jarak',    'on'=>false,  'var' => 'distance'],
        ['label'=>'Kamera',   'on'=>false,  'var' => 'camera_power'],
        ['label'=>'Flash',    'on'=>false, 'var' => 'flash'],
      ];
    @endphp

    @foreach($sensors as $s)
    <div class="sensor-row">
      <span class="sensor-row-label">{{ $s['label'] }}</span>
      <label class="toggle">
        <input type="checkbox" {{ $s['on'] ? 'checked' : '' }} onchange="updateSensor('{{ $s['var'] }}', this.checked)">
        <span class="toggle-slider green-on"></span>
      </label>
    </div>
    @endforeach
  </div>
</div>

<div class="grid-status">
  <div class="card status-card">
    <div class="status-icon-wrap blue"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="6" width="18" height="12" rx="2"/><line x1="23" y1="13" x2="23" y2="11"/></svg></div>
    <div><div class="status-label">Battery</div></div>
    <div class="status-val green" id="t-battery">{{ $device->battery_pct ?? 85 }}%</div>
  </div>

  <div class="card status-card">
    <div class="status-icon-wrap green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12.55a11 11 0 0 1 14.08 0"/><path d="M1.42 9a16 16 0 0 1 21.16 0"/><circle cx="12" cy="20" r="1"/></svg></div>
    <div><div class="status-label">Wifi</div></div>
    <div class="status-val green" id="wifi-status">Connected</div>
  </div>

  <div class="card status-card">
    <div class="status-icon-wrap red" id="cam-icon-wrap"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg></div>
    <div><div class="status-label">Camera</div></div>
    <div class="status-val red" id="cam-status">Offline</div>
  </div>

  <!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<div class="card gps-label-card">
    <div class="gps-title">
        <i class="fa-solid fa-location-dot"></i>
        <span>GPS</span>
    </div>

    <a href="{{ route('control.center') }}" class="config-link">Live</a>
</div>
</div>

<div class="grid-bottom">
  <div class="card">
    <div class="camera-title" style="font-size: 14px; font-weight: 700; margin-bottom: 12px; color: var(--gray-800);">Live Feed</div>
    <div class="camera-viewport">
      <div class="scan-line"></div>
      <div id="stream-wrap"><img id="live-stream" src=""></div>
      <div id="offline-state">
        <span style="font-size:20px; opacity:0.3">📷</span>
        <span id="offline-msg" style="font-size:10px; color:rgba(255,255,255,0.4); font-weight:700;">CAMERA POWERED OFF</span>
      </div>
      <div class="cam-overlay">
        <div class="cam-top">
          <div class="rec-badge"><span class="rec-dot"></span> REC</div>
          <span class="stream-badge offline" id="stream-badge">OFFLINE</span>
        </div>
        <div class="cam-bottom">
          <div class="cam-time" id="cam-timer">00:00:00:00</div>
          <div class="cam-fps">30 FPS | HD</div>
        </div>
      </div>
    </div>
  </div>

  <div class="card" style="padding:10px 10px 0px;">
      <div class="gps-map-wrap">
          <iframe
              id="gps-frame"
              src="https://www.google.com/maps?q={{ $lat }},{{ $lng }}&z={{ $zoom }}&output=embed"
              allowfullscreen=""
              loading="lazy">
          </iframe>
      </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
// ===============================
// GPS REALTIME DASHBOARD
// ===============================

async function updateRealtimeGPS() {

    try {

        const response = await fetch('/api/gps/realtime');

        const data = await response.json();

        if (data.success) {

            const lat = parseFloat(data.latitude);
            const lng = parseFloat(data.longitude);

            // update iframe maps
            const mapFrame = document.getElementById('gps-frame');

            mapFrame.src =
                `https://www.google.com/maps?q=${lat},${lng}&z=18&output=embed`;

        }

    } catch (err) {

        console.log("GPS DASHBOARD ERROR:", err);

    }

}

// refresh tiap 3 detik
setInterval(updateRealtimeGPS, 3000);

const camStreamURL = @json(config('esp32.cam_url', ''));
const camBaseURL   = @json(config('esp32.cam_base', ''));
let kameraAktif = true; 

// Timer Frame Jam
let frames = 0;
setInterval(() => {
  frames++;
  const ts = Math.floor(frames / 30);
  const ff = frames % 30;
  const ss = ts % 60, mm = Math.floor(ts/60)%60, hh = Math.floor(ts/3600);
  const p = n => String(n).padStart(2,'0');
  document.getElementById('cam-timer').textContent = `${p(hh)}:${p(mm)}:${p(ss)}:${p(ff)}`;
}, 1000/30);

function muatStream(url) {
  if (!url || !kameraAktif) return;
  const img = document.getElementById('live-stream');
  const wrap = document.getElementById('stream-wrap');
  const offline = document.getElementById('offline-state');
  const badge = document.getElementById('stream-badge');
  const statusVal = document.getElementById('cam-status');

  img.src = url + (url.includes('?') ? '&' : '?') + 't=' + Date.now();

  img.onload = function() {
    wrap.style.display = 'block';
    offline.style.display = 'none';
    badge.textContent = 'LIVE';
    badge.className = 'stream-badge live';
    statusVal.textContent = 'Online';
    statusVal.className = 'status-val green';
  };

  img.onerror = function() {
    if(kameraAktif) {
        statusVal.textContent = 'Error';
        badge.textContent = 'ERR';
    }
  };
}

function matikanStream() {
  const img = document.getElementById('live-stream');
  const wrap = document.getElementById('stream-wrap');
  const offline = document.getElementById('offline-state');
  const badge = document.getElementById('stream-badge');
  const statusVal = document.getElementById('cam-status');
  const msg = document.getElementById('offline-msg');

  img.src = ""; 
  wrap.style.display = 'none';
  offline.style.display = 'flex';
  msg.textContent = "CAMERA POWERED OFF";
  badge.textContent = 'OFFLINE';
  badge.className = 'stream-badge offline';
  statusVal.textContent = 'Offline';
  statusVal.className = 'status-val red';
}

function updateSensor(name, val) {
  if (name === 'camera_power') {
    kameraAktif = val;
    if (val) {
        muatStream(camStreamURL);
    } else {
        matikanStream();
    }
  }

  // Kirim Status ke Backend Laravel
  fetch('{{ route("api.sensor.toggle") }}', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
    body: JSON.stringify({ sensor: name, value: val })
  });

  // Kirim instruksi langsung ke ESP32 (Jika ada)
  if (camBaseURL) {
    let espVar = name === 'camera_power' ? 'power' : name;
    fetch(`${camBaseURL}/control?var=${espVar}&val=${val ? 1 : 0}`, { mode: 'no-cors' });
  }
}

document.addEventListener('DOMContentLoaded', () => {
  if (camStreamURL) muatStream(camStreamURL);
});
</script>
@endpush