{{-- resources/views/camera.blade.php --}}
@extends('layouts.app')
@section('title','Camera')

@push('styles')
<style>
  .page-heading {
    display: flex; align-items: center; gap: 10px;
    font-size: 22px; font-weight: 800; color: var(--gray-800);
    margin-bottom: 22px;
  }

  .page-heading svg {
    color: var(--gray-800);
  }

  /* ── VIEWPORT UTAMA ── */
  .camera-card { padding: 20px; }

  .camera-viewport {
    width: 100%;
    background: #000;
    border-radius: 12px;
    aspect-ratio: 16/9;
    position: relative;
    overflow: hidden;
    border: 2px solid #2a2a2a;
  }

  /* live feed (jika ada stream) */
  .camera-viewport video,
  .camera-viewport img.live-feed {
    width: 100%; height: 100%;
    object-fit: cover;
    display: block;
  }

  /* ── OVERLAY UI ── */
  .cam-overlay {
    position: absolute; inset: 0;
    display: flex; flex-direction: column;
    justify-content: space-between;
    padding: 18px 20px;
    pointer-events: none;
  }

  /* TOP BAR */
  .cam-top {
    display: flex; align-items: center; justify-content: space-between;
  }

  .rec-badge {
    display: flex; align-items: center; gap: 7px;
    font-size: 14px; font-weight: 700; color: white;
    letter-spacing: 1px;
  }
  .rec-dot {
    width: 10px; height: 10px;
    background: #ef4444; border-radius: 50%;
    animation: blink 1.1s infinite;
    box-shadow: 0 0 6px rgba(239,68,68,.8);
  }
  @keyframes blink { 0%,100%{opacity:1} 50%{opacity:.15} }

  .cam-battery {
    display: flex; align-items: center; gap: 4px;
  }
  .battery-body {
    width: 28px; height: 14px;
    border: 1.5px solid rgba(255,255,255,.7); border-radius: 3px;
    position: relative; display: flex; align-items: center; padding: 2px;
  }
  .battery-body::after {
    content: ''; position: absolute; right: -5px; top: 50%; transform: translateY(-50%);
    width: 3px; height: 6px; background: rgba(255,255,255,.7); border-radius: 0 2px 2px 0;
  }
  .battery-fill {
    height: 100%; border-radius: 1px; background: #4ade80;
    width: 70%;
    transition: width .5s;
  }

  /* MIDDLE – crosshair */
  .cam-middle {
    position: absolute; inset: 0;
    display: flex; align-items: center; justify-content: center;
  }

  .crosshair-outer {
    width: 160px; height: 120px;
    position: relative;
  }

  /* empat sudut crosshair */
  .crosshair-outer::before,
  .crosshair-outer::after,
  .crosshair-inner::before,
  .crosshair-inner::after {
    content: ''; position: absolute;
    width: 24px; height: 24px;
    border-color: rgba(255,255,255,.55);
    border-style: solid;
  }
  .crosshair-outer::before { top:0; left:0;    border-width: 2px 0 0 2px; }
  .crosshair-outer::after  { top:0; right:0;   border-width: 2px 2px 0 0; }
  .crosshair-inner::before { bottom:0; left:0; border-width: 0 0 2px 2px; }
  .crosshair-inner::after  { bottom:0; right:0;border-width: 0 2px 2px 0; }

  .crosshair-inner {
    position: absolute; inset: 0;
  }

  .crosshair-plus {
    position: absolute; top: 50%; left: 50%;
    transform: translate(-50%, -50%);
    color: rgba(255,255,255,.45);
    font-size: 22px; font-weight: 300; line-height: 1;
  }

  /* BOTTOM BAR */
  .cam-bottom {
    display: flex; align-items: center; justify-content: space-between;
  }

  .cam-timecode {
    display: flex; align-items: center; gap: 8px;
  }
  .timecode-bar {
    width: 3px; height: 16px; background: white; border-radius: 2px; opacity: .8;
  }
  .timecode-text {
    font-size: 14px; font-weight: 700; color: white;
    font-variant-numeric: tabular-nums;
    letter-spacing: 1px;
  }

  .cam-specs {
    font-size: 13px; font-weight: 700; color: rgba(255,255,255,.75);
    letter-spacing: .5px;
  }
  .cam-specs span { margin: 0 5px; opacity: .5; }

  /* scanning line animasi */
  .scan-line {
    position: absolute; left: 0; right: 0;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(74,222,128,.4), transparent);
    animation: scan 4s linear infinite;
    pointer-events: none;
  }
  @keyframes scan {
    0%   { top: 5%; opacity: 0; }
    5%   { opacity: 1; }
    95%  { opacity: 1; }
    100% { top: 95%; opacity: 0; }
  }

  /* ── STATUS PANEL ── */
  .status-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
    margin-top: 18px;
  }

  .status-tile {
    background: var(--gray-50);
    border: 1px solid var(--gray-200);
    border-radius: 12px; padding: 14px 16px;
    text-align: center;
  }
  .status-tile-label { font-size: 11px; color: var(--gray-400); font-weight: 600; text-transform: uppercase; letter-spacing: .4px; margin-bottom: 6px; }
  .status-tile-value { font-size: 16px; font-weight: 800; color: var(--gray-800); }
  .status-tile-value.green { color: var(--green-600); }
  .status-tile-value.red   { color: #ef4444; }
  .status-tile-value.amber { color: #d97706; }

  /* ── REKAMAN TERAKHIR ── */
  .recordings-card { margin-top: 18px; }
  .section-title   { font-size: 15px; font-weight: 700; color: var(--gray-800); margin-bottom: 14px; }

  .rec-table { width: 100%; border-collapse: collapse; }
  .rec-table th {
    text-align: left; font-size: 11px; font-weight: 700;
    color: var(--gray-400); text-transform: uppercase; letter-spacing: .4px;
    padding: 0 0 10px; border-bottom: 1px solid var(--gray-100);
  }
  .rec-table td {
    padding: 10px 0; font-size: 13px;
    border-bottom: 1px solid var(--gray-100); color: var(--gray-700);
  }
  .rec-table tr:last-child td { border-bottom: none; }
  .rec-table td:first-child { font-weight: 600; color: var(--green-600); }

  .badge-rec {
    display: inline-block; padding: 3px 10px;
    border-radius: 20px; font-size: 11px; font-weight: 700;
  }
  .badge-rec.selesai { background: var(--green-100); color: var(--green-600); }
  .badge-rec.error   { background: #fee2e2; color: #b91c1c; }
  .badge-rec.rekam   { background: #fef9c3; color: #a16207; }
</style>
@endpush

@section('content')

<div class="page-heading">
  <svg width="24" height="24" viewBox="0 0 24 24"
       fill="none" stroke="currentColor" stroke-width="2"
       stroke-linecap="round" stroke-linejoin="round">
    <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
    <circle cx="12" cy="13" r="4"/>
  </svg>
  Camera
</div>

<div class="card camera-card">

  {{-- ── VIEWPORT ── --}}
  <div class="camera-viewport">

    {{-- Scan line animasi --}}
    <div class="scan-line"></div>

    {{-- Overlay UI --}}
    <div class="cam-overlay">

      {{-- TOP: REC + Battery --}}
      <div class="cam-top">
        <div class="rec-badge">
          <span class="rec-dot"></span> REC
        </div>
        <div class="cam-battery">
          <div class="battery-body">
            <div class="battery-fill" id="battery-fill"></div>
          </div>
        </div>
      </div>

      {{-- BOTTOM: timecode + specs --}}
      <div class="cam-bottom">
        <div class="cam-timecode">
          <div class="timecode-bar"></div>
          <div class="timecode-text" id="cam-timecode">00:00:00:00</div>
        </div>
        <div class="cam-specs">
          30 FPS <span>|</span> 4K
        </div>
      </div>

    </div>

    {{-- CROSSHAIR tengah --}}
    <div class="cam-middle">
      <div class="crosshair-outer">
        <div class="crosshair-inner"></div>
        <div class="crosshair-plus">+</div>
      </div>
    </div>

  </div>

  {{-- ── STATUS TILES ── --}}
  <div class="status-row">
    <div class="status-tile">
      <div class="status-tile-label">Status</div>
      <div class="status-tile-value red" id="cam-status">Error</div>
    </div>
    <div class="status-tile">
      <div class="status-tile-label">Resolusi</div>
      <div class="status-tile-value">3840×2160</div>
    </div>
    <div class="status-tile">
      <div class="status-tile-label">Frame Rate</div>
      <div class="status-tile-value">30 FPS</div>
    </div>
    <div class="status-tile">
      <div class="status-tile-label">Baterai</div>
      <div class="status-tile-value green" id="cam-battery-pct">78%</div>
    </div>
  </div>

</div>

{{-- ── REKAMAN TERAKHIR ── --}}
<div class="card recordings-card">
  <div class="section-title">Rekaman Terakhir</div>
  <table class="rec-table">
    <thead>
      <tr>
        <th>Waktu Mulai</th>
        <th>Durasi</th>
        <th>Resolusi</th>
        <th>FPS</th>
        <th>Ukuran</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      @forelse($recordings ?? [] as $r)
        <tr>
          <td>{{ \Carbon\Carbon::parse($r->created_at)->format('H:i:s') }}</td>
          <td>{{ $r->durasi ?? '-' }}</td>
          <td>{{ $r->resolusi ?? '4K' }}</td>
          <td>{{ $r->fps ?? 30 }}</td>
          <td>{{ $r->ukuran ?? '-' }}</td>
          <td><span class="badge-rec {{ strtolower($r->status) }}">{{ $r->status }}</span></td>
        </tr>
      @empty
        {{-- Data dummy --}}
        @foreach([
          ['14:32:00','00:05:12','3840×2160',30,'1.2 GB','selesai'],
          ['14:20:44','00:10:03','3840×2160',30,'2.4 GB','selesai'],
          ['14:08:15','00:03:50','3840×2160',30,'912 MB','selesai'],
          ['13:55:30','00:00:00','3840×2160',30,'—','error'],
          ['13:42:10','00:08:22','3840×2160',30,'2.0 GB','selesai'],
        ] as $r)
        <tr>
          <td>{{ $r[0] }}</td>
          <td>{{ $r[1] }}</td>
          <td>{{ $r[2] }}</td>
          <td>{{ $r[3] }}</td>
          <td>{{ $r[4] }}</td>
          <td><span class="badge-rec {{ $r[5] }}">{{ ucfirst($r[5]) }}</span></td>
        </tr>
        @endforeach
      @endforelse
    </tbody>
  </table>
</div>

@endsection

@push('scripts')
<script>
// ── TIMECODE (HH:MM:SS:FF) ──
let totalFrames = 0;
const FPS = 30;

setInterval(() => {
  totalFrames++;
  const ff = totalFrames % FPS;
  const totalSec = Math.floor(totalFrames / FPS);
  const ss = totalSec % 60;
  const mm = Math.floor(totalSec / 60) % 60;
  const hh = Math.floor(totalSec / 3600);

  const pad = n => String(n).padStart(2, '0');
  document.getElementById('cam-timecode').textContent =
    `${pad(hh)}:${pad(mm)}:${pad(ss)}:${pad(ff)}`;
}, 1000 / FPS);

// ── BATTERY POLLING ──
function pollCamera() {
  fetch('{{ route("api.device.status") }}', {
    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
  })
  .then(r => r.json())
  .then(d => {
    if (d.battery_pct !== undefined) {
      document.getElementById('cam-battery-pct').textContent = d.battery_pct + '%';
      document.getElementById('battery-fill').style.width   = d.battery_pct + '%';
      // Warna battery: merah jika < 20%
      document.getElementById('battery-fill').style.background =
        d.battery_pct < 20 ? '#ef4444' : d.battery_pct < 50 ? '#f59e0b' : '#4ade80';
    }
    if (d.camera) {
      const el = document.getElementById('cam-status');
      el.textContent = d.camera;
      el.className   = 'status-tile-value ' + (d.camera === 'Active' ? 'green' : 'red');
    }
  })
  .catch(() => {});
}
pollCamera();
setInterval(pollCamera, 5000);
</script>
@endpush
