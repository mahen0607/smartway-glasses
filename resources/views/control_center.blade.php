@extends('layouts.app')
@section('title', 'Control Center')

@section('content')
<div class="greeting" style="margin-bottom: 20px;">
    <div class="greeting-title">Control Center</div>
    <div class="greeting-sub">Manipulasi Lokasi Maps Kacamata</div>
</div>

<div style="max-width: 600px;">
    @if(session('success'))
        <div style="background: #dcfce7; color: #166534; padding: 15px; border-radius: 10px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    <div class="card" style="padding: 25px; border: 1px solid var(--gray-100);">
        <form action="{{ route('control.update') }}" method="POST">
            @csrf
            <div style="margin-bottom: 15px;">
                <label style="display:block; font-size:13px; font-weight:600; color:var(--gray-600); margin-bottom:8px;">Latitude (Garis Lintang)</label>
                <input type="text" name="map_lat" value="{{ $configs['map_lat'] ?? '-7.9525' }}" 
                    style="width:100%; padding:12px; border:1px solid #ddd; border-radius:8px;" placeholder="Contoh: -6.1754">
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display:block; font-size:13px; font-weight:600; color:var(--gray-600); margin-bottom:8px;">Longitude (Garis Bujur)</label>
                <input type="text" name="map_lng" value="{{ $configs['map_lng'] ?? '112.6144' }}" 
                    style="width:100%; padding:12px; border:1px solid #ddd; border-radius:8px;" placeholder="Contoh: 106.8272">
            </div>

            <div style="margin-bottom: 25px;">
                <label style="display:block; font-size:13px; font-weight:600; color:var(--gray-600); margin-bottom:8px;">Zoom Level (1 - 20)</label>
                <input type="number" name="map_zoom" value="{{ $configs['map_zoom'] ?? '15' }}" 
                    style="width:100%; padding:12px; border:1px solid #ddd; border-radius:8px;">
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="submit" style="background: var(--gray-800); color:white; border:none; padding:12px 25px; border-radius:8px; font-weight:700; cursor:pointer;">
                    Simpan Perubahan Lokasi
                </button>
                <a href="{{ route('dashboard') }}" style="text-decoration:none; color:var(--gray-500); padding:12px; font-size:13px;">Kembali ke Dashboard</a>
            </div>
        </form>
    </div>
    
    <div style="margin-top: 20px; font-size: 12px; color: var(--gray-400);">
        *Tips: Buka Google Maps, klik kanan pada lokasi manapun, lalu copy angkanya.
    </div>
</div>
@endsection