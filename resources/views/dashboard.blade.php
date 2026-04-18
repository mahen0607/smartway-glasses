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
  .sensor-panel-title svg {
    width: 18px; height: 18px;
    fill: var(--gray-800); stroke: none;
  }
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
  .toggle input:checked + .toggle-slider.red-on    { background: var(--red-500); }
  .toggle input:checked + .toggle-slider::before   { transform: translateX(24px); }

  /* status bar */
  .grid-status {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr 260px;
    gap: 14px;
    margin-bottom: 16px;
    align-items: stretch;
  }
  .status-card {
    display: flex; align-items: center; gap: 12px;
    padding: 14px 16px;
  }
  .status-icon-wrap {
    width: 40px; height: 40px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
  }
  .status-icon-wrap svg {
    width: 22px; height: 22px;
    stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round;
  }
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

  /* gps label in status bar */
  .gps-label-card {
    display: flex; align-items: center; gap: 8px;
    padding: 14px 16px;
    font-size: 14px; font-weight: 700; color: var(--gray-800);
  }
  .gps-label-card svg {
    width: 20px; height: 20px;
    stroke: var(--gray-700); fill: none;
    stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round;
  }

  /* bottom grid */
  .grid-bottom {
    display: grid;
    grid-template-columns: 1fr 260px;
    gap: 18px;
  }

  /* camera */
  .camera-title {
    display: flex; align-items: center; gap: 8px;
    font-size: 14px; font-weight: 700; color: var(--gray-800);
    margin-bottom: 12px;
  }
  .camera-title svg {
    width: 18px; height: 18px;
    stroke: var(--gray-700); fill: none;
    stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round;
  }
  .camera-viewport {
    background: #111; border-radius: 10px;
    aspect-ratio: 16/9; position: relative; overflow: hidden;
  }
  .cam-overlay {
    position: absolute; inset: 0;
    display: flex; flex-direction: column; justify-content: space-between;
    padding: 10px 14px;
  }
  .cam-top, .cam-bottom { display: flex; align-items: center; justify-content: space-between; }
  .rec-badge { display: flex; align-items: center; gap: 5px; font-size: 11px; font-weight: 700; color: white; letter-spacing: 1px; }
  .rec-dot { width: 8px; height: 8px; background: var(--red-500); border-radius: 50%; animation: blink 1.2s infinite; }
  @keyframes blink { 0%,100%{opacity:1}50%{opacity:.2} }
  .cam-crosshair {
    width: 48px; height: 48px;
    border: 1.5px solid rgba(255,255,255,.3); border-radius: 6px;
    position: absolute; top:50%; left:50%; transform: translate(-50%,-50%);
  }
  .cam-plus { position: absolute; top:50%; left:50%; transform: translate(-50%,-50%); color: rgba(255,255,255,.35); font-size: 18px; }
  .cam-time { font-size: 11px; color: rgba(255,255,255,.7); font-variant-numeric: tabular-nums; }
  .cam-fps  { font-size: 10px; color: rgba(255,255,255,.45); }
  .cam-battery-icon svg { width: 16px; height: 16px; stroke: rgba(255,255,255,.6); fill: none; stroke-width: 1.8; stroke-linecap: round; }

  /* gps map */
  .gps-map-wrap {
    border-radius: 12px; overflow: hidden;
    aspect-ratio: 1/.9; position: relative;
    background: var(--gray-100);
  }
  .gps-map-wrap iframe { width:100%; height:100%; border:none; display:block; }
  .gps-placeholder {
    width:100%; height:100%;
    background: linear-gradient(135deg,#dbeafe,#bbf7d0,#d1fae5);
    display:flex; flex-direction:column; align-items:center; justify-content:center; gap:6px;
  }
  .gps-placeholder svg { width:32px; height:32px; stroke:#16a34a; fill:none; stroke-width:1.8; stroke-linecap:round; stroke-linejoin:round; }
  .gps-placeholder-loc { font-size:12px; font-weight:700; color:var(--gray-600); }
  .gps-placeholder-sub { font-size:10px; color:var(--gray-400); }

  /* live pulse */
  .live-pill {
    display:inline-flex; align-items:center; gap:5px;
    background:var(--green-500); color:white;
    padding:3px 9px; border-radius:20px; font-size:11px; font-weight:700;
  }
  .live-dot { width:6px;height:6px;background:white;border-radius:50%;animation:pulse 1.4s infinite; }
  @keyframes pulse{0%,100%{opacity:1;transform:scale(1)}50%{opacity:.4;transform:scale(.7)}}
</style>
@endpush

@section('content')

{{-- Greeting --}}
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

{{-- TOP: Glasses hero + Sensor toggles --}}
<div class="grid-top">

  {{-- Glasses image --}}
  <div class="glasses-card">
    <img class="glasses-hero"
      src="{{ asset('images/glasses.png') }}"
      alt="Smart-Way Glasses"
      onerror="this.src='https://via.placeholder.com/280x160?text=Smart-Way+Glasses'">
  </div>

  {{-- Sensor toggles --}}
  <div class="card sensor-panel">
    <div class="sensor-panel-title">
      {{-- Bar chart icon --}}
      <svg viewBox="0 0 24 24">
        <rect x="2"  y="14" width="4" height="8" rx="1"/>
        <rect x="9"  y="9"  width="4" height="13" rx="1"/>
        <rect x="16" y="4"  width="4" height="18" rx="1"/>
      </svg>
      Sensor
    </div>

    @php
      $sensors = [
        ['label'=>'GPS',      'on'=>true,  'color'=>'green-on'],
        ['label'=>'Jarak',    'on'=>true,  'color'=>'green-on'],
        ['label'=>'Kamera',   'on'=>true,  'color'=>'green-on'],
        ['label'=>'Sensor A', 'on'=>true,  'color'=>'green-on'],
        ['label'=>'Sensor B', 'on'=>true,  'color'=>'green-on'],
      ];
    @endphp

    @foreach($sensors as $s)
    <div class="sensor-row">
      <span class="sensor-row-label">{{ $s['label'] }}</span>
      <label class="toggle">
        <input type="checkbox" {{ $s['on'] ? 'checked' : '' }}
          onchange="updateSensor('{{ Str::slug($s['label']) }}', this.checked)">
        <span class="toggle-slider {{ $s['color'] }}"></span>
      </label>
    </div>
    @endforeach
  </div>
</div>

{{-- STATUS ROW --}}
<div class="grid-status">

  {{-- Battery --}}
  <div class="card status-card">
    <div class="status-icon-wrap blue">
    <svg viewBox="0 0 24 24" width="22" height="22"
         fill="none" stroke="#3b82f6" stroke-width="1.8"
         stroke-linecap="round" stroke-linejoin="round">
        <rect x="1" y="6" width="18" height="12" rx="2" ry="2"/>
        <line x1="23" y1="13" x2="23" y2="11"/>
        <line x1="4" y1="9" x2="4" y2="15"/>
        <line x1="7" y1="9" x2="7" y2="15"/>
        <line x1="10" y1="9" x2="10" y2="15"/>
    </svg>
</div>
    <div>
      <div class="status-label">Battery</div>
      <div class="status-sub">{{ $device->battery_hours ?? 4 }}hour</div>
    </div>
    <div class="status-val green">{{ $device->battery_pct ?? 50 }}%</div>
  </div>

  {{-- Wifi --}}
  <div class="card status-card">
    <div class="status-icon-wrap green">
      <svg viewBox="0 0 24 24">
        <path d="M5 12.55a11 11 0 0 1 14.08 0"/>
        <path d="M1.42 9a16 16 0 0 1 21.16 0"/>
        <path d="M8.53 16.11a6 6 0 0 1 6.95 0"/>
        <circle cx="12" cy="20" r="1" fill="currentColor" stroke="none"/>
      </svg>
    </div>
    <div>
      <div class="status-label">Wifi</div>
    </div>
    <div class="status-val green" id="wifi-status">{{ $device->wifi ?? 'Connected' }}</div>
  </div>

  {{-- Camera --}}
  <div class="card status-card">
    <div class="status-icon-wrap red">
      <svg viewBox="0 0 24 24">
        <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
        <circle cx="12" cy="13" r="4"/>
      </svg>
    </div>
    <div>
      <div class="status-label">Camera</div>
    </div>
    <div class="status-val red" id="cam-status">{{ $device->camera ?? 'Error' }}</div>
  </div>

  {{-- GPS label --}}
  <div class="card gps-label-card">
    <svg viewBox="0 0 24 24">
      <path d="M12 2C8.686 2 6 4.686 6 8c0 5.25 6 13 6 13s6-7.75 6-13c0-3.314-2.686-6-6-6z"/>
      <circle cx="12" cy="8" r="2.5" fill="currentColor" stroke="none"/>
    </svg>
    GPS Location
    <span class="live-pill" style="margin-left:auto"><span class="live-dot"></span>Live</span>
  </div>

</div>

{{-- BOTTOM: Camera feed + GPS map --}}
<div class="grid-bottom">

  {{-- Camera feed --}}
  <div class="card">
    <div class="camera-title">
      <svg viewBox="0 0 24 24">
        <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
        <circle cx="12" cy="13" r="4"/>
      </svg>
      Camera
    </div>
    <div class="camera-viewport">
      <div class="cam-overlay">
        <div class="cam-top">
          <div class="rec-badge"><span class="rec-dot"></span> REC</div>
          <div class="cam-battery-icon">
            <svg viewBox="0 0 24 24">
              <rect x="2" y="7" width="16" height="10" rx="2"/>
              <path d="M22 11v2"/>
            </svg>
          </div>
        </div>
        <div class="cam-crosshair"></div>
        <div class="cam-plus">+</div>
        <div class="cam-bottom">
          <div class="cam-time" id="cam-timer">00:00:00</div>
          <div class="cam-fps">30 FPS | 4K</div>
        </div>
      </div>
    </div>
  </div>

  {{-- GPS map --}}
  <div class="card" style="padding:18px 18px 14px;">
    <div class="gps-map-wrap">
      @if(config('services.google_maps.key'))
        <iframe
          src="https://www.google.com/maps?q={{ $gps->latitude ?? -7.9525 }},{{ $gps->longitude ?? 112.6144 }}&z=15&output=embed"
          loading="lazy" allowfullscreen>
        </iframe>
      @else
        <div class="gps-placeholder">
          <svg viewBox="0 0 24 24">
            <path d="M12 2C8.686 2 6 4.686 6 8c0 5.25 6 13 6 13s6-7.75 6-13c0-3.314-2.686-6-6-6z"/>
            <circle cx="12" cy="8" r="2.5" fill="#16a34a" stroke="none"/>
          </svg>
          <div class="gps-placeholder-loc">Universitas Brawijaya</div>
          <div class="gps-placeholder-sub">-7.9525 , 112.6144</div>
          <div style="font-size:10px;color:#94a3b8;margin-top:2px;">Tambahkan GOOGLE_MAPS_KEY di .env</div>
        </div>
      @endif
    </div>
    <div style="display:flex;gap:8px;margin-top:10px;">
      <div style="flex:1;background:var(--gray-50);border-radius:8px;padding:7px 10px;">
        <div style="font-size:10px;color:var(--gray-400);font-weight:600;text-transform:uppercase;">Lat</div>
        <div style="font-size:12px;font-weight:700;color:var(--gray-700);">{{ $gps->latitude ?? '-7.9525' }}°</div>
      </div>
      <div style="flex:1;background:var(--gray-50);border-radius:8px;padding:7px 10px;">
        <div style="font-size:10px;color:var(--gray-400);font-weight:600;text-transform:uppercase;">Lng</div>
        <div style="font-size:12px;font-weight:700;color:var(--gray-700);">{{ $gps->longitude ?? '112.6144' }}°</div>
      </div>
      <div style="flex:1;background:var(--gray-50);border-radius:8px;padding:7px 10px;">
        <div style="font-size:10px;color:var(--gray-400);font-weight:600;text-transform:uppercase;">Akurasi</div>
        <div style="font-size:12px;font-weight:700;color:var(--gray-700);">±{{ $gps->akurasi ?? 3 }}m</div>
      </div>
    </div>
  </div>

</div>
@endsection

@push('scripts')
<script>
// Camera timer
let sec = 0;
setInterval(() => {
  sec++;
  const h = String(Math.floor(sec/3600)).padStart(2,'0');
  const m = String(Math.floor((sec%3600)/60)).padStart(2,'0');
  const s = String(sec%60).padStart(2,'0');
  document.getElementById('cam-timer').textContent = `${h}:${m}:${s}`;
}, 1000);

// Toggle sensor via AJAX
function updateSensor(name, val) {
  fetch('{{ route("api.sensor.toggle") }}', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': '{{ csrf_token() }}'
    },
    body: JSON.stringify({ sensor: name, value: val })
  }).catch(console.warn);
}

// Realtime polling setiap 5 detik
function pollStatus() {
  fetch('{{ route("api.device.status") }}', {
    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
  })
  .then(r => r.json())
  .then(d => {
    if (d.wifi)   document.getElementById('wifi-status').textContent = d.wifi;
    if (d.camera) document.getElementById('cam-status').textContent  = d.camera;
  })
  .catch(console.warn);
}
setInterval(pollStatus, 5000);
</script>
@endpush