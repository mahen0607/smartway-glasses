{{-- resources/views/gps.blade.php --}}

@extends('layouts.app')
@section('title','GPS Location')

@push('styles')

<link rel="stylesheet"
href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

<style>

.page-heading{
    display:flex;
    align-items:center;
    gap:10px;
    font-size:24px;
    font-weight:800;
    color:#1f2937;
    margin-bottom:20px;
}

.map-card{
    padding:18px;
}

.map-wrapper{
    position:relative;
    border-radius:16px;
    overflow:hidden;
    border:1px solid #e5e7eb;
}

#leaflet-map{
    width:100%;
    height:500px;
}

.live-badge{
    position:absolute;
    top:14px;
    left:60px;
    z-index:999;
    background:white;
    padding:8px 16px;
    border-radius:30px;
    font-size:13px;
    font-weight:700;
    color:#374151;
    box-shadow:0 2px 10px rgba(0,0,0,.15);
    display:flex;
    align-items:center;
    gap:8px;
}

.dot{
    width:10px;
    height:10px;
    border-radius:50%;
    background:#22c55e;
    animation:blink 1s infinite;
}

@keyframes blink{
    0%{opacity:1}
    50%{opacity:.3}
    100%{opacity:1}
}

.info-grid{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:16px;
    margin-top:20px;
}

.info-box{
    background:#f9fafb;
    border:1px solid #e5e7eb;
    border-radius:14px;
    padding:20px;
    text-align:center;
}

.label{
    font-size:12px;
    font-weight:700;
    color:#9ca3af;
    margin-bottom:8px;
    text-transform:uppercase;
}

.value{
    font-size:20px;
    font-weight:800;
    color:#111827;
}

.history-card{
    margin-top:22px;
    padding:20px;
    overflow-x:auto;
}

.history-title{
    font-size:20px;
    font-weight:700;
    color:#1f2937;
    margin-bottom:18px;
}

/* =========================
   TABLE STYLE
========================= */

.hist-table{
    width:100%;
    border-collapse:collapse;
    min-width:700px;
}

.hist-table thead{
    background:#f3f4f6;
}

.hist-table th{
    padding:16px 20px;
    text-align:left;
    font-size:13px;
    font-weight:700;
    color:#6b7280;
    border-bottom:2px solid #e5e7eb;
}

.hist-table td{
    padding:16px 20px;
    font-size:14px;
    color:#374151;
    border-bottom:1px solid #f1f5f9;
}

.hist-table tbody tr:hover{
    background:#f9fafb;
    transition:0.2s;
}

.status-badge{
    background:#dcfce7;
    color:#16a34a;
    padding:6px 12px;
    border-radius:20px;
    font-size:12px;
    font-weight:700;
}

@media(max-width:900px){

    .info-grid{
        grid-template-columns:repeat(2,1fr);
    }

    .value{
        font-size:22px;
    }

}

@media(max-width:600px){

    .info-grid{
        grid-template-columns:1fr;
    }

    #leaflet-map{
        height:350px;
    }

}

</style>

@endpush

@section('content')

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<div class="page-heading">
    <i class="fa-solid fa-location-dot"></i>
    GPS Realtime Tracking
</div>

<div class="card map-card">

    <div class="map-wrapper">

        <div class="live-badge">
            <span class="dot"></span>
            LIVE GPS ACTIVE
        </div>

        <div id="leaflet-map"></div>

    </div>

    <div class="info-grid">

        <div class="info-box">
            <div class="label">Latitude</div>
            <div class="value" id="latitude">
                {{ $gps->latitude }}
            </div>
        </div>

        <div class="info-box">
            <div class="label">Longitude</div>
            <div class="value" id="longitude">
                {{ $gps->longitude }}
            </div>
        </div>

        <div class="info-box">
            <div class="label">Akurasi</div>
            <div class="value" id="akurasi">
                {{ $gps->akurasi }}m
            </div>
        </div>

        <div class="info-box">
            <div class="label">Status</div>
            <div class="value" id="status" style="color:#16a34a;">
                {{ $gps->status }}
            </div>
        </div>

    </div>

</div>

<div class="card history-card">

    <div class="history-title">
        Riwayat GPS
    </div>

    <table class="hist-table">

        <thead>
            <tr>
                <th>Waktu</th>
                <th>Latitude</th>
                <th>Longitude</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>

        @forelse($histories as $h)

            <tr>

                <td>
                    {{ \Carbon\Carbon::parse($h->created_at)->format('d M Y H:i:s') }}
                </td>

                <td>
                    {{ number_format($h->latitude, 6) }}
                </td>

                <td>
                    {{ number_format($h->longitude, 6) }}
                </td>

                <td>
                    <span class="status-badge">
                        {{ $h->status }}
                    </span>
                </td>

            </tr>

        @empty

            <tr>
                <td colspan="4" style="text-align:center;padding:30px;">
                    Tidak ada data GPS
                </td>
            </tr>

        @endforelse

        </tbody>

    </table>

</div>

@endsection

@push('scripts')

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>

let lat = {{ $gps->latitude }};
let lng = {{ $gps->longitude }};

// =============================
// INIT MAP
// =============================

const map = L.map('leaflet-map').setView([lat, lng], 18);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution:'© OpenStreetMap'
}).addTo(map);

// =============================
// MARKER
// =============================

const marker = L.marker([lat, lng]).addTo(map);

marker.bindPopup("SmartWay GPS Tracking").openPopup();

// =============================
// REALTIME FETCH
// =============================

async function loadRealtimeGPS(){

    try{

        const response = await fetch('/api/gps/realtime');

        const data = await response.json();

        if(data.success){

            const newLat = parseFloat(data.latitude);
            const newLng = parseFloat(data.longitude);

            // update marker
            marker.setLatLng([newLat, newLng]);

            // auto move map
            map.panTo([newLat, newLng]);

            // update info box
            document.getElementById('latitude').innerHTML =
                newLat.toFixed(6);

            document.getElementById('longitude').innerHTML =
                newLng.toFixed(6);

            document.getElementById('akurasi').innerHTML =
                data.akurasi + 'm';

            document.getElementById('status').innerHTML =
                data.status;

        }

    }catch(err){

        console.log("GPS ERROR:", err);

    }

}

// refresh realtime tiap 2 detik
setInterval(loadRealtimeGPS, 2000);

</script>

@endpush