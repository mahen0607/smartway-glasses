{{-- resources/views/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-icon', '📊')
@section('page-title', 'Sensor Data')

@push('styles')
<style>
  .grid-top { display: grid; grid-template-columns: 1fr 280px; gap: 20px; margin-bottom: 20px; }
  .grid-status { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 20px; }
  .grid-bottom { display: grid; grid-template-columns: 1fr 280px; gap: 20px; }

  /* Activity */
  .activity-header { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 16px; }
  .activity-label { font-size: 14px; font-weight: 600; color: var(--gray-700); margin-bottom: 4px; }
  .activity-meta { display: flex; align-items: center; gap: 16px; }
  .activity-stat { font-size: 12px; color: var(--gray-400); }
  .activity-stat strong { font-size: 15px; font-weight: 700; color: var(--gray-800); margin-left: 4px; }
  .big-rotasi { text-align: right; }
  .big-rotasi-label { font-size: 11px; color: var(--gray-400); font-weight: 500; }
  .big-rotasi-val { font-size: 40px; font-weight: 800; color: var(--gray-800); line-height: 1; }

  /* Stat cards */
  .stat-card { display: flex; align-items: center; gap: 14px; }
  .stat-icon { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0; }
  .stat-icon.green { background: var(--green-100); }
  .stat-icon.lime { background: #ecfccb; }
  .stat-info { flex: 1; }
  .stat-label { font-size: 13px; font-weight: 600; color: var(--gray-700); }
  .stat-sub { font-size: 11px; color: var(--green-600); font-weight: 500; }
  .stat-value { font-size: 26px; font-weight: 800; color: var(--green-600); margin-left: auto; }
  .stat-value.lime { color: #65a30d; }

  /* Status */
  .status-row { display: flex; align-items: center; gap: 10px; padding: 14px 18px; }
  .status-icon-wrap { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0; }
  .status-icon-wrap.green { background: #dcfce7; }
  .status-icon-wrap.red { background: #fee2e2; }
  .status-label { font-size: 14px; font-weight: 600; color: var(--gray-800); }
  .status-val { font-size: 13px; font-weight: 700; margin-left: auto; }
  .status-val.connected { color: var(--green-600); }
  .status-val.error { color: var(--red-500); }

  /* Camera */
  .camera-viewport {
    background: #111; border-radius: 10px; aspect-ratio: 16/9;
    position: relative; display: flex; align-items: center; justify-content: center; overflow: hidden;
  }
  .camera-overlay { position: absolute; inset: 0; display: flex; flex-direction: column; justify-content: space-between; padding: 10px 14px; }
  .camera-top-row, .camera-bottom-row { display: flex; align-items: center; justify-content: space-between; }
  .rec-badge { display: flex; align-items: center; gap: 5px; font-size: 11px; font-weight: 700; color: white; letter-spacing: 1px; }
  .rec-dot { width: 8px; height: 8px; background: var(--red-500); border-radius: 50%; animation: blink 1.2s infinite; }
  @keyframes blink { 0%, 100% { opacity: 1; } 50% { opacity: 0.2; } }
  .camera-crosshair { width: 50px; height: 50px; border: 1.5px solid rgba(255,255,255,0.35); border-radius: 6px; position: absolute; top: 50%; left: 50%; transform: translate(-50%,-50%); }
  .cam-time { font-size: 11px; color: rgba(255,255,255,0.7); font-variant-numeric: tabular-nums; }
  .cam-fps { font-size: 10px; color: rgba(255,255,255,0.5); }

  /* GPS */
  .gps-map-wrap { border-radius: 10px; overflow: hidden; aspect-ratio: 4/3; background: var(--gray-100); position: relative; }
  .gps-coords { margin-top: 10px; display: flex; gap: 12px; }
  .gps-coord-item { flex: 1; background: var(--gray-50); border-radius: 8px; padding: 8px 10px; }
  .gps-coord-label { font-size: 10px; color: var(--gray-400); font-weight: 600; text-transform: uppercase; }
  .gps-coord-val { font-size: 12px; font-weight: 700; color: var(--gray-700); margin-top: 2px; }
</style>
@endpush

@section('content')
<div class="grid-top">

  {{-- Activity Chart --}}
  <div class="card">
    <div class="activity-header">
      <div>
        <div class="activity-label">Aktivitas Terkini</div>
        <div class="activity-meta">
          <span class="activity-stat">Deteksi Halangan</span>
          <span class="activity-stat">Terdekat <strong id="terdekat-val">{{ $sensorData->terdekat ?? '0.7' }}</strong> m</span>
        </div>
      </div>
      <div class="big-rotasi">
        <div class="big-rotasi-label">Rotasi</div>
        <div class="big-rotasi-val" id="rotasi-val">{{ $sensorData->rotasi ?? 580 }}</div>
      </div>
    </div>
    <div style="position: relative; width: 100%; height: 160px;">
      <canvas id="activityChart"></canvas>
    </div>
    <div style="display: flex; justify-content: space-between; margin-top: 6px;">
      @foreach(['13 Apr','14 Apr','15 Apr','15 Apr'] as $label)
        <span style="font-size: 10px; color: var(--gray-400);">{{ $label }}</span>
      @endforeach
    </div>
  </div>

  {{-- Right Stat Cards --}}
  <div style="display: flex; flex-direction: column; gap: 14px;">
    <div class="card">
      <div class="stat-card">
        <div class="stat-icon green">🛡️</div>
        <div class="stat-info">
          <div class="stat-label">Total Deteksi Halangan</div>
          <div class="stat-sub">Sensor Aktif</div>
        </div>
        <div class="stat-value" id="total-deteksi">{{ $sensorData->total_deteksi ?? 460 }}</div>
      </div>
    </div>
    <div class="card">
      <div class="stat-card">
        <div class="stat-icon lime">🕐</div>
        <div class="stat-info">
          <div class="stat-label">Durasi Pemakaian</div>
        </div>
        <div class="stat-value lime" id="durasi-val">{{ $sensorData->durasi ?? '12h24m' }}</div>
      </div>
    </div>
    <div class="card" style="padding: 14px 18px;">
      <div style="font-size: 12px; color: var(--gray-400); margin-bottom: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.4px;">Sensor Realtime</div>
      <div style="display: flex; flex-direction: column; gap: 8px;">
        <div style="display: flex; justify-content: space-between; font-size: 13px;">
          <span style="color: var(--gray-500);">Jarak Min</span>
          <span style="font-weight: 700; color: var(--green-600);" id="jarak-min">{{ $sensorData->jarak_min ?? '0.3' }} m</span>
        </div>
        <div style="display: flex; justify-content: space-between; font-size: 13px;">
          <span style="color: var(--gray-500);">Jarak Maks</span>
          <span style="font-weight: 700; color: var(--gray-700);" id="jarak-maks">{{ $sensorData->jarak_maks ?? '4.2' }} m</span>
        </div>
        <div style="display: flex; justify-content: space-between; font-size: 13px;">
          <span style="color: var(--gray-500);">Suhu Sensor</span>
          <span style="font-weight: 700; color: var(--gray-700);">{{ $sensorData->suhu ?? 38 }}°C</span>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- STATUS ROW --}}
<div class="grid-status">
  <div class="card" style="padding: 0;">
    <div class="status-row">
      <div class="status-icon-wrap green">📶</div>
      <div><div class="status-label">Wifi</div></div>
      <div class="status-val connected">{{ $deviceStatus->wifi ?? 'Connected' }}</div>
    </div>
  </div>
  <div class="card" style="padding: 0;">
    <div class="status-row">
      <div class="status-icon-wrap red">📷</div>
      <div><div class="status-label">Camera</div></div>
      <div class="status-val error">{{ $deviceStatus->camera ?? 'Error' }}</div>
    </div>
  </div>
</div>

{{-- BOTTOM ROW --}}
<div class="grid-bottom">

  {{-- Camera --}}
  <div class="card">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px;">
      <div style="font-size: 13px; font-weight: 600; color: var(--gray-500); text-transform: uppercase; letter-spacing: 0.5px;">📷 Camera</div>
      <div style="font-size: 11px; color: var(--red-500); font-weight: 600; background: #fee2e2; padding: 3px 10px; border-radius: 20px;">⚠ Error</div>
    </div>
    <div class="camera-viewport">
      <div class="camera-overlay">
        <div class="camera-top-row">
          <div class="rec-badge"><span class="rec-dot"></span> REC</div>
          <div style="font-size:11px;color:white;opacity:0.7;">🔋 78%</div>
        </div>
        <div class="camera-crosshair"></div>
        <div class="camera-bottom-row">
          <div class="cam-time" id="cam-timer">00:00:00</div>
          <div class="cam-fps">30 FPS | 4K</div>
        </div>
      </div>
    </div>
    <div style="text-align:center;margin-top:10px;font-size:12px;color:var(--gray-400);font-weight:500;">Waktu rekaman aktif</div>
  </div>

  {{-- GPS --}}
  <div class="card">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px;">
      <div style="font-size: 13px; font-weight: 600; color: var(--gray-500); text-transform: uppercase; letter-spacing: 0.5px;">📍 GPS Location</div>
      <div style="font-size:11px;color:var(--green-600);font-weight:600;background:var(--green-100);padding:3px 10px;border-radius:20px;">Aktif</div>
    </div>
    <div class="gps-map-wrap">
      @if(config('services.google_maps.key'))
        <iframe
          src="https://www.google.com/maps?q={{ $gpsData->latitude ?? -7.9525 }},{{ $gpsData->longitude ?? 112.6144 }}&z=15&output=embed"
          width="100%" height="100%" style="border:none;" loading="lazy">
        </iframe>
      @else
        <div style="width:100%;height:100%;background:linear-gradient(135deg,#dbeafe 0%,#bbf7d0 50%,#d1fae5 100%);display:flex;flex-direction:column;align-items:center;justify-content:center;gap:8px;">
          <div style="font-size:32px;">📍</div>
          <div style="font-size:12px;font-weight:600;color:var(--gray-600);">Universitas Brawijaya</div>
          <div style="font-size:11px;color:var(--gray-400);">Malang, Jawa Timur</div>
        </div>
      @endif
    </div>
    <div class="gps-coords">
      <div class="gps-coord-item">
        <div class="gps-coord-label">Latitude</div>
        <div class="gps-coord-val">{{ $gpsData->latitude ?? '-7.9525' }}°</div>
      </div>
      <div class="gps-coord-item">
        <div class="gps-coord-label">Longitude</div>
        <div class="gps-coord-val">{{ $gpsData->longitude ?? '112.6144' }}°</div>
      </div>
      <div class="gps-coord-item">
        <div class="gps-coord-label">Akurasi</div>
        <div class="gps-coord-val">±{{ $gpsData->akurasi ?? 3 }} m</div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>
<script>
const ctx = document.getElementById('activityChart').getContext('2d');
// Data dari Laravel via JSON (bisa diganti dengan API call)
const chartData = @json($chartData ?? []);
const labels = chartData.labels ?? Array.from({length: 24}, () => '');
const dataHalangan = chartData.halangan ?? Array.from({length: 24}, () => Math.floor(50 + Math.random() * 100));
const dataRotasi = chartData.rotasi ?? Array.from({length: 24}, () => Math.floor(200 + Math.random() * 450));

new Chart(ctx, {
  type: 'line',
  data: {
    labels,
    datasets: [
      {
        label: 'Deteksi Halangan',
        data: dataHalangan,
        borderColor: '#4ade80',
        backgroundColor: 'rgba(74,222,128,0.12)',
        borderWidth: 2, tension: 0.45, fill: true, pointRadius: 0,
      },
      {
        label: 'Rotasi',
        data: dataRotasi.map(v => Math.round(v / 5)),
        borderColor: '#22c55e',
        backgroundColor: 'rgba(34,197,94,0.06)',
        borderWidth: 2, tension: 0.45, fill: true, pointRadius: 0,
      }
    ]
  },
  options: {
    responsive: true, maintainAspectRatio: false,
    plugins: { legend: { display: false }, tooltip: { enabled: false } },
    scales: {
      x: { display: false },
      y: {
        display: true,
        grid: { color: 'rgba(0,0,0,0.04)' },
        ticks: { font: { size: 10 }, color: '#94a3b8', maxTicksLimit: 4 },
        border: { display: false }
      }
    },
    animation: false
  }
});

// Camera timer
let secs = 0;
setInterval(() => {
  secs++;
  const h = String(Math.floor(secs / 3600)).padStart(2, '0');
  const m = String(Math.floor((secs % 3600) / 60)).padStart(2, '0');
  const s = String(secs % 60).padStart(2, '0');
  document.getElementById('cam-timer').textContent = h + ':' + m + ':' + s;
}, 1000);

// Polling API realtime setiap 5 detik
function fetchRealtimeData() {
  fetch('{{ route("api.sensor.realtime") }}', {
    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
  })
  .then(r => r.json())
  .then(data => {
    if (data.terdekat) document.getElementById('terdekat-val').textContent = data.terdekat;
    if (data.rotasi) document.getElementById('rotasi-val').textContent = data.rotasi;
    if (data.total_deteksi) document.getElementById('total-deteksi').textContent = data.total_deteksi;
    if (data.jarak_min) document.getElementById('jarak-min').textContent = data.jarak_min + ' m';
    if (data.jarak_maks) document.getElementById('jarak-maks').textContent = data.jarak_maks + ' m';
    if (data.durasi) document.getElementById('durasi-val').textContent = data.durasi;
  })
  .catch(err => console.warn('Realtime polling error:', err));
}
setInterval(fetchRealtimeData, 5000);
</script>
@endpush
