{{-- resources/views/gps.blade.php --}}
@extends('layouts.app')
@section('title','GPS Location')

@push('styles')
<style>
  .page-heading {
    display: flex; align-items: center; gap: 10px;
    font-size: 22px; font-weight: 800; color: var(--gray-800);
    margin-bottom: 22px;
  }

  .page-heading svg {
    width: 22px; height: 22px;
    color: var(--gray-800);
  }

  /* Map utama */
  .map-card { padding: 16px; }

  .map-frame {
    width: 100%;
    border-radius: 14px;
    overflow: hidden;
    border: 1px solid var(--gray-200);
    background: var(--gray-100);
    position: relative;
  }

  .map-frame iframe {
    width: 100%; height: 420px;
    border: none; display: block;
  }

  /* Placeholder jika tidak ada API key */
  .map-placeholder {
    width: 100%; height: 420px;
    background: linear-gradient(135deg, #e0f2fe 0%, #bbf7d0 50%, #d1fae5 100%);
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    gap: 10px; position: relative; overflow: hidden;
  }
  .map-placeholder-roads {
    position: absolute; inset: 0; opacity: .18;
    background-image:
      repeating-linear-gradient(0deg,   transparent, transparent 40px, #334155 40px, #334155 42px),
      repeating-linear-gradient(90deg,  transparent, transparent 60px, #334155 60px, #334155 62px),
      repeating-linear-gradient(45deg,  transparent, transparent 80px, #475569 80px, #475569 81px);
  }
  .map-pin-wrap { position: relative; z-index: 2; text-align: center; }
  .map-pin-icon { font-size: 52px; filter: drop-shadow(0 4px 8px rgba(0,0,0,.25)); }
  .map-pin-label {
    font-size: 18px; font-weight: 800; color: var(--red-500);
    text-shadow: 0 1px 3px rgba(255,255,255,.8);
    margin-top: 4px;
  }
  .map-pin-sub { font-size: 12px; color: var(--gray-500); margin-top: 2px; }

  /* Live badge di atas peta */
  .map-live-badge {
    position: absolute; top: 14px; left: 14px; z-index: 10;
    display: flex; align-items: center; gap: 6px;
    background: white; border-radius: 20px; padding: 5px 12px;
    font-size: 12px; font-weight: 700; color: var(--gray-700);
    box-shadow: 0 2px 8px rgba(0,0,0,.12);
  }
  .live-dot {
    width: 8px; height: 8px; border-radius: 50%; background: var(--green-500);
    animation: blink 1.3s infinite;
  }
  @keyframes blink { 0%,100%{opacity:1} 50%{opacity:.25} }

  /* Info grid bawah peta */
  .info-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
    margin-top: 18px;
  }

  .info-tile {
    background: var(--gray-50);
    border: 1px solid var(--gray-200);
    border-radius: 12px;
    padding: 14px 16px;
    text-align: center;
  }
  .info-tile-label { font-size: 11px; color: var(--gray-400); font-weight: 600; text-transform: uppercase; letter-spacing: .4px; margin-bottom: 6px; }
  .info-tile-value { font-size: 16px; font-weight: 800; color: var(--gray-800); }
  .info-tile-value.green { color: var(--green-600); }
  .info-tile-value.red   { color: var(--red-500); }

  /* History tabel */
  .history-card { margin-top: 18px; }
  .section-title { font-size: 15px; font-weight: 700; color: var(--gray-800); margin-bottom: 14px; }

  .hist-table { width: 100%; border-collapse: collapse; }
  .hist-table th {
    text-align: left; font-size: 11px; font-weight: 700;
    color: var(--gray-400); text-transform: uppercase; letter-spacing: .4px;
    padding: 0 0 10px; border-bottom: 1px solid var(--gray-100);
  }
  .hist-table td {
    padding: 10px 0; font-size: 13px;
    border-bottom: 1px solid var(--gray-100); color: var(--gray-700);
  }
  .hist-table tr:last-child td { border-bottom: none; }
  .hist-table td:first-child { font-weight: 600; color: var(--green-600); }

  .badge-mov {
    display: inline-block; padding: 3px 10px; border-radius: 20px;
    font-size: 11px; font-weight: 700;
  }
  .badge-mov.bergerak { background: #dbeafe; color: #1d4ed8; }
  .badge-mov.diam     { background: var(--gray-100); color: var(--gray-500); }
</style>
@endpush

@section('content')

<div class="page-heading">
  <svg width="22" height="22" viewBox="0 0 24 24"
       fill="none" stroke="currentColor" stroke-width="2"
       stroke-linecap="round" stroke-linejoin="round">
    <path d="M12 2C8.686 2 6 4.686 6 8c0 5.25 6 13 6 13s6-7.75 6-13c0-3.314-2.686-6-6-6z"/>
    <circle cx="12" cy="8" r="2.5" fill="currentColor" stroke="none"/>
  </svg>
  GPS Location
</div>

{{-- Peta utama --}}
<div class="card map-card">

  <div class="map-frame" style="position:relative;">

    {{-- Live badge --}}
    <div class="map-live-badge">
      <span class="live-dot"></span> GPS Live
    </div>

    @if(config('services.google_maps.key'))
      {{-- Google Maps embed jika ada API key --}}
      <iframe
        src="https://www.google.com/maps?q={{ $gps->latitude ?? -7.9525 }},{{ $gps->longitude ?? 112.6144 }}&z=16&output=embed"
        allowfullscreen loading="lazy">
      </iframe>
    @else
      {{-- Placeholder saat tidak ada API key — pakai OpenStreetMap (gratis) --}}
      <iframe
        src="https://www.openstreetmap.org/export/embed.html?bbox=112.6044%2C-7.9625%2C112.6244%2C-7.9425&layer=mapnik&marker=-7.9525%2C112.6144"
        style="width:100%;height:420px;border:none;"
        loading="lazy">
      </iframe>
    @endif

  </div>

  {{-- Info tiles --}}
  <div class="info-grid">
    <div class="info-tile">
      <div class="info-tile-label">Latitude</div>
      <div class="info-tile-value" id="val-lat">{{ $gps->latitude ?? '-7.9525' }}°</div>
    </div>
    <div class="info-tile">
      <div class="info-tile-label">Longitude</div>
      <div class="info-tile-value" id="val-lng">{{ $gps->longitude ?? '112.6144' }}°</div>
    </div>
    <div class="info-tile">
      <div class="info-tile-label">Akurasi</div>
      <div class="info-tile-value green" id="val-akurasi">±{{ $gps->akurasi ?? 3 }} m</div>
    </div>
    <div class="info-tile">
      <div class="info-tile-label">Status</div>
      <div class="info-tile-value green" id="val-status">
        {{ $gps->status ?? 'Bergerak' }}
      </div>
    </div>
  </div>

</div>

{{-- Riwayat Lokasi --}}
<div class="card history-card">
  <div class="section-title">Riwayat Lokasi</div>
  <table class="hist-table">
    <thead>
      <tr>
        <th>Waktu</th>
        <th>Latitude</th>
        <th>Longitude</th>
        <th>Akurasi</th>
        <th>Status</th>
        <th>Alamat</th>
      </tr>
    </thead>
    <tbody id="history-body">
      @forelse($histories ?? [] as $h)
        <tr>
          <td>{{ \Carbon\Carbon::parse($h->created_at)->format('H:i:s') }}</td>
          <td>{{ $h->latitude }}°</td>
          <td>{{ $h->longitude }}°</td>
          <td>±{{ $h->akurasi }} m</td>
          <td>
            <span class="badge-mov {{ strtolower($h->status ?? 'bergerak') }}">
              {{ $h->status ?? 'Bergerak' }}
            </span>
          </td>
          <td>{{ $h->alamat ?? '-' }}</td>
        </tr>
      @empty
        {{-- Data dummy jika tabel masih kosong --}}
        @foreach([
          ['14:32:10', '-7.9525', '112.6144', 3, 'bergerak', 'Universitas Brawijaya'],
          ['14:30:05', '-7.9521', '112.6140', 4, 'bergerak', 'Jl. Veteran, Malang'],
          ['14:28:00', '-7.9518', '112.6138', 3, 'diam',     'Gerbang UB Malang'],
          ['14:25:44', '-7.9515', '112.6135', 5, 'bergerak', 'Jl. Kertosari'],
          ['14:23:30', '-7.9510', '112.6130', 3, 'bergerak', 'Jl. MT Haryono'],
        ] as $row)
        <tr>
          <td>{{ $row[0] }}</td>
          <td>{{ $row[1] }}°</td>
          <td>{{ $row[2] }}°</td>
          <td>±{{ $row[3] }} m</td>
          <td><span class="badge-mov {{ $row[4] }}">{{ ucfirst($row[4]) }}</span></td>
          <td>{{ $row[5] }}</td>
        </tr>
        @endforeach
      @endforelse
    </tbody>
  </table>
</div>

@endsection

@push('scripts')
<script>
// Polling GPS realtime setiap 5 detik
function pollGps() {
  fetch('{{ route("api.gps.realtime") }}', {
    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
  })
  .then(r => r.json())
  .then(d => {
    if (d.latitude)  document.getElementById('val-lat').textContent    = d.latitude + '°';
    if (d.longitude) document.getElementById('val-lng').textContent    = d.longitude + '°';
    if (d.akurasi)   document.getElementById('val-akurasi').textContent = '±' + d.akurasi + ' m';
    if (d.status)    document.getElementById('val-status').textContent  = d.status;
  })
  .catch(() => {});
}
setInterval(pollGps, 5000);
</script>
@endpush
