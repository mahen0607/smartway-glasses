{{-- resources/views/sensor.blade.php --}}
@extends('layouts.app')
@section('title','Sensor Data')

@push('styles')
<style>
  /* PAGE HEADING */
  .page-heading {
    display: flex; align-items: center; gap: 10px;
    font-size: 22px; font-weight: 800; color: var(--gray-800);
    margin-bottom: 22px;
  }
  .page-heading svg {
    width: 24px; height: 24px;
    fill: var(--gray-800);
  }

  /* TOP GRID */
  .grid-top {
    display: grid;
    grid-template-columns: 1fr 270px;
    gap: 18px;
    margin-bottom: 18px;
  }

  /* Activity card */
  .activity-header {
    display: flex; align-items: flex-start; justify-content: space-between;
    margin-bottom: 14px;
  }
  .activity-label { font-size: 15px; font-weight: 700; color: var(--gray-800); margin-bottom: 6px; }
  .activity-meta  { display: flex; align-items: center; gap: 20px; }
  .a-tag  { font-size: 12px; color: var(--gray-400); font-weight: 500; }
  .a-val  { font-size: 14px; font-weight: 700; color: var(--gray-700); margin-left: 4px; }
  .rotasi-block { text-align: right; }
  .rotasi-lbl { font-size: 11px; color: var(--gray-400); font-weight: 500; }
  .rotasi-num { font-size: 42px; font-weight: 800; color: var(--gray-800); line-height: 1; }
  .chart-dates { display: flex; justify-content: space-between; margin-top: 6px; }
  .chart-dates span { font-size: 10px; color: var(--gray-400); }

  /* Stat cards kanan */
  .stat-stack { display: flex; flex-direction: column; gap: 12px; }
  .stat-card  { display: flex; align-items: center; gap: 12px; padding: 14px 16px; }

  .stat-icon {
    width: 44px; height: 44px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
  }
  .stat-icon.green { background: var(--green-500); }
  .stat-icon.lime  { background: var(--green-500); }
  .stat-icon.gray  { background: #f1f5f9; }
  .stat-icon.teal  { background: #dcfce7; }

  .stat-info { flex: 1; min-width: 0; }
  .stat-name { font-size: 13.5px; font-weight: 700; color: var(--gray-800); }
  .stat-sub  { font-size: 11px; color: var(--green-600); font-weight: 500; }
  .stat-sub.muted { color: var(--gray-400); }
  .stat-value {
    font-size: 22px; font-weight: 800;
    color: var(--green-500); white-space: nowrap; margin-left: auto;
  }

  /* MID GRID */
  .grid-mid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 18px;
    margin-bottom: 18px;
  }
  .section-title { font-size: 15px; font-weight: 700; color: var(--gray-800); margin-bottom: 12px; }

  .info-table { width: 100%; border-collapse: collapse; }
  .info-table td {
    padding: 10px 0; font-size: 13.5px;
    border-bottom: 1px solid var(--gray-100);
  }
  .info-table tr:last-child td { border-bottom: none; }
  .info-table td:first-child { color: var(--gray-500); font-weight: 500; }
  .info-table td:last-child  { text-align: right; font-weight: 700; color: var(--gray-800); }

  .badge { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; }
  .badge.waspada  { background: #fef9c3; color: #a16207; }
  .badge.aman     { background: var(--green-100); color: var(--green-600); }
  .badge.bahaya   { background: #fee2e2; color: #b91c1c; }
  .badge.bergerak { background: #dbeafe; color: #1d4ed8; }
  .badge.diam     { background: var(--gray-100); color: var(--gray-500); }

  /* Log */
  .log-list { list-style: none; display: flex; flex-direction: column; gap: 8px; }
  .log-item {
    display: flex; align-items: center; gap: 10px;
    padding: 9px 12px; background: var(--gray-50); border-radius: 8px; font-size: 13px;
  }
  .log-time  { font-size: 12px; font-weight: 700; color: var(--green-600); flex-shrink: 0; width: 38px; }
  .log-arrow { color: var(--gray-400); }
  .log-desc  { color: var(--gray-700); font-weight: 500; }

  .live-dot {
    display: inline-block; width: 7px; height: 7px; border-radius: 50%;
    background: var(--green-500); margin-right: 5px;
    animation: blink 1.4s infinite;
  }
  @keyframes blink { 0%,100%{opacity:1} 50%{opacity:.3} }
</style>
@endpush

@section('content')

{{-- Heading --}}
<div class="page-heading">
  <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
    <rect x="2"  y="14" width="4" height="8" rx="1"/>
    <rect x="9"  y="9"  width="4" height="13" rx="1"/>
    <rect x="16" y="4"  width="4" height="18" rx="1"/>
  </svg>
  Sensor Data
</div>

{{-- TOP --}}
<div class="grid-top">

  {{-- Aktivitas Terkini --}}
  <div class="card">
    <div class="activity-header">
      <div>
        <div class="activity-label">Aktivitas Terkini</div>
        <div class="activity-meta">
          <span class="a-tag">Deteksi Halangan</span>
          <span class="a-tag">Terdekat
            <span class="a-val" id="val-terdekat">{{ number_format($sensor->jarak_terdekat ?? 0.7, 1) }}</span> m
          </span>
        </div>
      </div>
      <div class="rotasi-block">
        <div class="rotasi-lbl">Rotasi</div>
        <div class="rotasi-num" id="val-rotasi">{{ $sensor->rotasi ?? 580 }}</div>
      </div>
    </div>
    <div style="position:relative;width:100%;height:155px;">
      <canvas id="actChart"></canvas>
    </div>
    <div class="chart-dates">
      <span>13 Apr</span><span>14 Apr</span><span>15 Apr</span><span>15 Apr</span>
    </div>
  </div>

  {{-- Stat Cards --}}
  <div class="stat-stack">

    {{-- Total Deteksi Halangan --}}
    <div class="card stat-card">
      <div class="stat-icon green">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M12 2L4 6v5c0 5.25 3.5 9.74 8 11 4.5-1.26 8-5.75 8-11V6L12 2z"/>
          <polyline points="9 12 11 14 15 10"/>
        </svg>
      </div>
      <div class="stat-info">
        <div class="stat-name">Total Deteksi Halangan</div>
        <div class="stat-sub">Sensor Aktif</div>
      </div>
      <div class="stat-value" id="val-total">{{ $sensor->total_deteksi ?? 460 }}</div>
    </div>

    {{-- Durasi Pemakaian --}}
    <div class="card stat-card">
      <div class="stat-icon lime">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="12" r="10"/>
          <polyline points="12 6 12 12 16 14"/>
        </svg>
      </div>
      <div class="stat-info">
        <div class="stat-name">Durasi Pemakaian</div>
      </div>
      <div class="stat-value" id="val-durasi">{{ $durasi ?? '12h24m' }}</div>
    </div>

    {{-- Baterai --}}
    <div class="card stat-card">
      <div class="stat-icon gray">
        <svg width="26" height="26" viewBox="0 0 26 26" xmlns="http://www.w3.org/2000/svg">
          <rect x="1" y="7" width="19" height="12" rx="2" ry="2" fill="none" stroke="#9ca3af" stroke-width="2"/>
          <rect x="20" y="10" width="3" height="6" rx="1" fill="#9ca3af" stroke="none"/>
          <rect x="3" y="9" width="8" height="8" rx="1" fill="#9ca3af" stroke="none"/>
        </svg>
      </div>
      <div class="stat-info">
        <div class="stat-name">Baterai</div>
        <div class="stat-sub muted">{{ $device->battery_hours ?? 4 }} Hour</div>
      </div>
      <div class="stat-value" id="val-battery">{{ $device->battery_pct ?? 50 }}%</div>
    </div>

    {{-- Wifi --}}
    <div class="card stat-card">
      <div class="stat-icon teal">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M5 12.55a11 11 0 0 1 14.08 0"/>
          <path d="M1.42 9a16 16 0 0 1 21.16 0"/>
          <path d="M8.53 16.11a6 6 0 0 1 6.95 0"/>
          <circle cx="12" cy="20" r="1.2" fill="#16a34a" stroke="none"/>
        </svg>
      </div>
      <div class="stat-info">
        <div class="stat-name">Wifi</div>
        <div class="stat-sub" id="val-wifi">{{ $device->wifi ?? 'Connected' }}</div>
      </div>
    </div>

  </div>
</div>

{{-- MID: Sensor Jarak + GPS --}}
<div class="grid-mid">
  <div class="card">
    <div class="section-title">Sensor jarak</div>
    <table class="info-table">
      <tr>
        <td>Jarak</td>
        <td id="val-jarak">{{ isset($sensor->jarak_terdekat) ? round($sensor->jarak_terdekat * 100).' cm' : '45 cm' }}</td>
      </tr>
      <tr>
        <td>Status</td>
        <td>
          @php $st = $sensor->status ?? 'Waspada'; @endphp
          <span class="badge {{ strtolower($st) }}" id="val-status">{{ $st }}</span>
        </td>
      </tr>
      <tr>
        <td>Arah</td>
        <td id="val-arah">{{ $sensor->arah ?? 'Depan' }}</td>
      </tr>
    </table>
  </div>

  <div class="card">
    <div class="section-title">GPS</div>
    <table class="info-table">
      <tr>
        <td>Lat</td>
        <td id="val-lat">{{ $gps->latitude ?? '-7.9684108°' }}</td>
      </tr>
      <tr>
        <td>Long</td>
        <td id="val-lng">{{ $gps->longitude ?? '112.5926341°' }}</td>
      </tr>
      <tr>
        <td>Status</td>
        <td>
          <span class="live-dot"></span>
          <span class="badge diam" id="val-gpsstatus">{{ $gps->status ?? 'diam' }}</span>
        </td>
      </tr>
    </table>
  </div>
</div>

{{-- Log Perjalanan --}}
<div class="card">
  <div class="section-title">Log Perjalanan</div>
  <ul class="log-list" id="log-list">
    @forelse($logs ?? [] as $log)
      <li class="log-item">
        <span class="log-time">{{ \Carbon\Carbon::parse($log->created_at)->format('H:i') }}</span>
        <span class="log-arrow">→</span>
        <span class="log-desc">{{ $log->deskripsi }}</span>
      </li>
    @empty
      <li class="log-item"><span class="log-time">14:32</span><span class="log-arrow">→</span><span class="log-desc">Objek dekat (depan)</span></li>
    @endforelse
  </ul>
</div>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>
<script>
const ctx = document.getElementById('actChart').getContext('2d');
const raw = @json($chartData ?? []);
const halangan = raw.halangan ?? Array.from({length:24}, () => Math.floor(60 + Math.random() * 100));
const rotasi   = raw.rotasi   ?? Array.from({length:24}, () => Math.floor(300 + Math.random() * 350));
const labels   = raw.labels   ?? Array.from({length:24}, (_, i) => i);

new Chart(ctx, {
  type: 'line',
  data: {
    labels,
    datasets: [
      {
        label: 'Deteksi Halangan',
        data: halangan,
        borderColor: '#4ade80',
        backgroundColor: 'rgba(74,222,128,.13)',
        borderWidth: 2, tension: .45, fill: true, pointRadius: 0
      },
      {
        label: 'Rotasi',
        data: rotasi.map(v => Math.round(v / 4)),
        borderColor: '#22c55e',
        backgroundColor: 'rgba(34,197,94,.07)',
        borderWidth: 2, tension: .45, fill: true,
        pointRadius: 2, pointBackgroundColor: '#22c55e'
      }
    ]
  },
  options: {
    responsive: true, maintainAspectRatio: false,
    plugins: { legend: { display: false } },
    scales: {
      x: { display: false },
      y: {
        display: true,
        grid: { color: 'rgba(0,0,0,.04)' },
        ticks: { font: { size: 10 }, color: '#94a3b8', maxTicksLimit: 4 },
        border: { display: false }
      }
    }
  }
});

function poll() {
  console.log("[%s] Polling data ke server...", new Date().toLocaleTimeString());
  
  fetch('{{ route("api.sensor.realtime") }}', {
    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
  })
  .then(r => {
    if(!r.ok) {
        throw new Error("HTTP Error! Status: " + r.status);
    }
    return r.json();
  })
  .then(d => {
    // Debugging: Lihat data mentah di Console
    console.log("Data Diterima:", d);

    if (Object.keys(d).length === 0) {
        console.warn("Peringatan: Data diterima tapi kosong (Object Kosong). Pastikan ESP32 sudah mengirim data ke database.");
        return;
    }

    if (d.jarak_terdekat !== undefined) {
      document.getElementById('val-jarak').textContent    = Math.round(d.jarak_terdekat * 100) + ' cm';
      document.getElementById('val-terdekat').textContent = d.jarak_terdekat.toFixed(1);
    }
    if (d.rotasi)        document.getElementById('val-rotasi').textContent    = d.rotasi;
    if (d.total_deteksi) document.getElementById('val-total').textContent     = d.total_deteksi;
    if (d.durasi)        document.getElementById('val-durasi').textContent    = d.durasi;
    if (d.battery_pct)   document.getElementById('val-battery').textContent   = d.battery_pct + '%';
    if (d.wifi)          document.getElementById('val-wifi').textContent      = d.wifi;
    if (d.lat)           document.getElementById('val-lat').textContent       = d.lat;
    if (d.lng)           document.getElementById('val-lng').textContent       = d.lng;
    if (d.gps_status)    document.getElementById('val-gpsstatus').textContent = d.gps_status;
    if (d.arah)          document.getElementById('val-arah').textContent      = d.arah;
    
    if (d.jarak_status) {
      const el = document.getElementById('val-status');
      el.textContent = d.jarak_status;
      el.className = 'badge ' + d.jarak_status.toLowerCase();
    }

    if (d.log_baru) {
      console.log("Log Baru Terdeteksi:", d.log_baru);
      const list = document.getElementById('log-list');
      const now  = new Date().toTimeString().slice(0, 5);
      const li   = document.createElement('li');
      li.className = 'log-item';
      li.innerHTML = `<span class="log-time">${now}</span><span class="log-arrow">→</span><span class="log-desc">${d.log_baru}</span>`;
      list.prepend(li);
      while (list.children.length > 10) list.removeChild(list.lastChild);
    }
  })
  .catch(err => {
    console.error("KONEKSI GAGAL: Tidak bisa mengambil data dari server Laravel. Detail:", err.message);
    console.info("Tips: Cek apakah server Laravel menyala atau URL API '" + '{{ route("api.sensor.realtime") }}' + "' sudah benar.");
  });
}

// Jalankan polling setiap 4 detik
setInterval(poll, 4000);
// Jalankan sekali saat halaman pertama dibuka
poll();
</script>
@endpush