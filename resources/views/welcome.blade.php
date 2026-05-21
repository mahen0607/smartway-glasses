<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <style>
                /* ... Kode CSS Bawaan Tailwind 4 ... */
                /*! tailwindcss v4.0.7 | MIT License | https://tailwindcss.com */
                /* (Diringkas untuk kenyamanan, tetap gunakan CSS asli Anda di sini) */
            </style>
        @endif

        <style>
            /* Animasi Tambahan untuk Kamera */
            @keyframes scan { 
                0% { top: 5%; opacity: 0; } 
                5% { opacity: 1; } 
                95% { opacity: 1; } 
                100% { top: 95%; opacity: 0; } 
            }
            .scan-line { 
                position: absolute; left: 0; right: 0; height: 1px; z-index: 10;
                background: linear-gradient(90deg, transparent, rgba(74, 222, 128, 0.5), transparent);
                animation: scan 4s linear infinite; pointer-events: none;
            }
            @keyframes blink { 0%,100%{opacity:1} 50%{opacity:0.2} }
            .rec-dot { animation: blink 1.1s infinite; }
        </style>
    </head>
    <body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col font-sans">
        
        <header class="w-full lg:max-w-4xl max-w-[335px] text-sm mb-6 not-has-[nav]:hidden">
            @if (Route::has('login'))
                <nav class="flex items-center justify-end gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] rounded-sm text-sm">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] text-[#1b1b18] border border-transparent hover:border-[#19140035] rounded-sm text-sm">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] rounded-sm text-sm">Register</a>
                        @endif
                    @endauth
                </nav>
            @endif
        </header>

        <div class="flex items-center justify-center w-full transition-opacity opacity-100 duration-750 lg:grow">
            <main class="flex max-w-[335px] w-full flex-col-reverse lg:max-w-4xl lg:flex-row shadow-2xl rounded-lg overflow-hidden">
                
                <!-- SISI KIRI: INFO LARAVEL -->
                <div class="text-[13px] leading-[20px] flex-1 p-6 pb-12 lg:p-20 bg-white dark:bg-[#161615] dark:text-[#EDEDEC] border-r dark:border-[#3E3E3A]">
                    <h1 class="mb-1 font-medium text-lg">Smart Camera System</h1>
                    <p class="mb-6 text-[#706f6c] dark:text-[#A1A09A]">ESP32-CAM telah terintegrasi dengan ekosistem Laravel. Anda dapat memantau perangkat secara langsung dari sini.</p>
                    
                    <ul class="flex flex-col mb-8 gap-4">
                        <li class="flex items-center gap-3">
                            <span class="w-2 h-2 rounded-full bg-green-500"></span>
                            <span>Status: <b id="t-status" class="text-red-500">Offline</b></span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                            <span>Stream: <span id="t-url" class="font-mono text-[11px] opacity-60">Memuat...</span></span>
                        </li>
                    </ul>

                    <div class="flex gap-3">
                        <a href="{{ route('login') }}" class="px-6 py-2 bg-[#f53003] text-white rounded-sm font-medium hover:bg-[#d42a02] transition-colors">
                            Buka Dashboard
                        </a>
                    </div>
                </div>

                <!-- SISI KANAN: VIEWPORT KAMERA -->
                <div class="bg-black relative aspect-[335/376] lg:aspect-auto w-full lg:w-[438px] shrink-0 overflow-hidden flex items-center justify-center">
                    
                    <!-- Scan Line -->
                    <div class="scan-line"></div>

                    <!-- Gambar Stream -->
                    <div id="stream-wrap" class="absolute inset-0 hidden">
                        <img id="live-stream" src="" class="w-full h-full object-cover" alt="ESP32 Stream">
                    </div>

                    <!-- State Saat Offline -->
                    <div id="offline-state" class="flex flex-col items-center justify-center gap-4 z-20">
                        <div class="w-16 h-16 rounded-full bg-white/5 flex items-center justify-center border border-white/10">
                            <span class="text-3xl opacity-20 text-white">📷</span>
                        </div>
                        <div class="text-center">
                            <p class="text-sm font-bold text-white/50">CAMERA OFFLINE</p>
                            <p class="text-[11px] text-white/30">Pastikan ESP32 terhubung ke Wi-Fi</p>
                        </div>
                        <button onclick="retryConnect()" class="mt-2 px-4 py-1.5 bg-white/10 hover:bg-white/20 text-white text-[11px] rounded-full border border-white/20 transition-all pointer-events-auto">
                            Hubungkan Ulang
                        </button>
                    </div>

                    <!-- Overlay UI Kamera -->
                    <div class="absolute inset-0 p-6 flex flex-col justify-between pointer-events-none z-30">
                        <div class="flex justify-between items-start">
                            <div class="flex items-center gap-2 bg-black/40 backdrop-blur-md px-3 py-1 rounded-sm text-white text-[10px] font-bold tracking-widest border border-white/10">
                                <span class="w-2 h-2 bg-red-600 rounded-full rec-dot"></span> LIVE
                            </div>
                            <div id="stream-badge" class="bg-red-600/80 px-2 py-0.5 rounded-sm text-[9px] font-black text-white uppercase">OFFLINE</div>
                        </div>

                        <div class="flex justify-between items-end">
                            <div class="bg-black/40 backdrop-blur-md px-3 py-1 rounded-sm border border-white/10">
                                <div class="text-white font-mono text-xs tracking-tighter" id="tc">00:00:00:00</div>
                            </div>
                            <div class="text-[9px] text-white/40 font-bold tracking-tighter text-right">
                                CAM_ID: ESP32_v1<br>
                                30 FPS | 1080p
                            </div>
                        </div>
                    </div>

                    <!-- Sudut Fokus (Crosshair) -->
                    <div class="absolute inset-0 pointer-events-none opacity-20">
                        <div class="absolute top-10 left-10 w-8 h-8 border-t-2 border-l-2 border-white"></div>
                        <div class="absolute top-10 right-10 w-8 h-8 border-t-2 border-r-2 border-white"></div>
                        <div class="absolute bottom-10 left-10 w-8 h-8 border-b-2 border-l-2 border-white"></div>
                        <div class="absolute bottom-10 right-10 w-8 h-8 border-b-2 border-r-2 border-white"></div>
                    </div>
                </div>
            </main>
        </div>

        <footer class="mt-6 text-center text-[#706f6c] text-[11px]">
            &copy; {{ date('Y') }} {{ config('app.name') }} - Smartway Camera Integration
        </footer>

        <!-- SCRIPTS: Logika Persis Seperti camera.blade.php -->
        <script>
            const camStreamURL = @json(config('esp32.cam_url', ''));
            let kameraAktif = true;
            let frames = 0;

            // 1. Timecode Generator (Jam Berjalan)
            setInterval(() => {
                frames++;
                const ts = Math.floor(frames / 30);
                const ff = frames % 30;
                const ss = ts % 60, mm = Math.floor(ts/60)%60, hh = Math.floor(ts/3600);
                const p = n => String(n).padStart(2,'0');
                const tcElement = document.getElementById('tc');
                if(tcElement) tcElement.textContent = `${p(hh)}:${p(mm)}:${p(ss)}:${p(ff)}`;
            }, 1000/30);

            // 2. Fungsi Memuat Gambar Stream
            function muatStream(url) {
                if (!url || !kameraAktif) return;
                
                const img = document.getElementById('live-stream');
                const wrap = document.getElementById('stream-wrap');
                const offline = document.getElementById('offline-state');
                const badge = document.getElementById('stream-badge');
                const statusText = document.getElementById('t-status');
                const urlText = document.getElementById('t-url');

                urlText.textContent = url;

                // Percobaan memuat stream
                img.src = url + (url.includes('?') ? '&' : '?') + 't=' + Date.now();

                img.onload = function() {
                    wrap.style.display = 'block';
                    offline.style.display = 'none';
                    badge.textContent = 'ONLINE';
                    badge.style.backgroundColor = 'rgba(34, 197, 94, 0.8)'; // Green
                    statusText.textContent = 'Online';
                    statusText.className = 'text-green-500 font-bold';
                };

                img.onerror = function() {
                    wrap.style.display = 'none';
                    offline.style.display = 'flex';
                    badge.textContent = 'OFFLINE';
                    badge.style.backgroundColor = 'rgba(239, 68, 68, 0.8)'; // Red
                    statusText.textContent = 'Offline';
                    statusText.className = 'text-red-500 font-bold';
                };
            }

            function retryConnect() {
                muatStream(camStreamURL);
            }

            // Jalankan otomatis saat halaman dimuat
            document.addEventListener('DOMContentLoaded', () => {
                if (camStreamURL) {
                    muatStream(camStreamURL);
                } else {
                    document.getElementById('t-url').textContent = 'URL tidak dikonfigurasi (.env)';
                }
            });
        </script>
    </body>
</html>