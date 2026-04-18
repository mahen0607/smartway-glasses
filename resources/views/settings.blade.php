{{-- resources/views/settings.blade.php --}}
@extends('layouts.app')
@section('title','Settings')

@push('styles')
<style>
  .page-heading {
    display: flex; align-items: center; gap: 10px;
    font-size: 22px; font-weight: 800; color: var(--gray-800);
    margin-bottom: 24px;
  }
  .settings-grid {
    display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px;
  }
  .setting-item {
    background: white; border: 1px solid var(--gray-200); border-radius: 16px;
    padding: 36px 20px 28px;
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    gap: 16px; cursor: pointer; transition: all .2s ease; min-height: 150px;
  }
  .setting-item:hover {
    border-color: var(--green-400);
    box-shadow: 0 6px 24px rgba(34,197,94,.15);
    transform: translateY(-3px);
  }
  .setting-icon svg {
    width: 42px; height: 42px; stroke: var(--gray-800); fill: none;
    stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round;
  }

  .heading-icon {
    width: 24px;
    height: 24px;
    fill: #1f2937; /* sama kayak icon lain */
  }
  .setting-label { font-size: 14px; font-weight: 700; color: var(--gray-800); text-align: center; }

  /* MODAL */
  .modal-backdrop {
    display: none; position: fixed; inset: 0; z-index: 999;
    background: rgba(0,0,0,.35); backdrop-filter: blur(3px);
    align-items: center; justify-content: center;
  }
  .modal-backdrop.open { display: flex; }
  .modal-box {
    background: white; border-radius: 20px; padding: 28px 28px 26px;
    width: 100%; max-width: 420px; margin: 20px; position: relative;
    box-shadow: 0 24px 64px rgba(0,0,0,.15);
    animation: modalIn .22s ease both;
  }
  @keyframes modalIn {
    from { opacity:0; transform:scale(.93) translateY(16px); }
    to   { opacity:1; transform:scale(1) translateY(0); }
  }
  .modal-close {
    position: absolute; top: 16px; right: 18px; background: none; border: none;
    cursor: pointer; font-size: 20px; color: var(--gray-400); transition: color .15s;
  }
  .modal-close:hover { color: var(--gray-800); }
  .modal-title { font-size: 18px; font-weight: 800; color: var(--gray-800); margin-bottom: 20px; text-align: center; }
  .section-label { font-size: 13px; font-weight: 600; color: var(--gray-700); margin-bottom: 10px; margin-top: 18px; }
  .section-label:first-of-type { margin-top: 0; }

  .value-grid { display: grid; gap: 12px; }
  .value-grid.cols-2 { grid-template-columns: 1fr 1fr; }
  .value-grid.cols-1 { grid-template-columns: 1fr; }
  .value-box {
    border: 1.5px solid var(--gray-300); border-radius: 12px;
    padding: 16px 14px; text-align: center; background: white;
    cursor: pointer; transition: border-color .15s, box-shadow .15s;
  }
  .value-box:hover { border-color: var(--green-400); }
  .value-box.selected { border-color: var(--green-500); box-shadow: 0 0 0 2px rgba(34,197,94,.2); }
  .value-box-label { font-size: 10px; font-weight: 700; color: var(--gray-400); text-transform: uppercase; letter-spacing: .6px; margin-bottom: 8px; }
  .value-box-big { font-size: 26px; font-weight: 800; color: var(--gray-800); line-height: 1; }
  .num-input {
    border: none; outline: none; background: transparent;
    font-size: 26px; font-weight: 800; color: var(--gray-800);
    text-align: center; width: 100%; font-family: inherit;
  }
  input[type=number].num-input::-webkit-inner-spin-button { -webkit-appearance: none; }

  .action-btn {
    width: 100%; padding: 13px; border: none; border-radius: 10px;
    font-size: 14px; font-weight: 800; font-family: inherit;
    letter-spacing: 1.5px; cursor: pointer; transition: all .18s; margin-top: 6px;
  }
  .action-btn.green { background: var(--green-500); color: white; }
  .action-btn.green:hover { background: var(--green-600); }
  .action-btn.gray  { background: var(--gray-200); color: var(--gray-600); }
  .action-btn.gray:hover { background: var(--gray-300); }
  .action-btn:disabled { opacity: .6; cursor: not-allowed; transform: none !important; }

  .tes-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
  .tes-btn {
    padding: 13px; border: none; border-radius: 10px;
    background: var(--green-500); color: white;
    font-size: 13px; font-weight: 800; font-family: inherit;
    letter-spacing: 1px; cursor: pointer; transition: all .18s;
  }
  .tes-btn:hover { background: var(--green-600); transform: translateY(-1px); }

  .select-box { border: 1.5px solid var(--gray-300); border-radius: 12px; padding: 14px; text-align: center; background: white; }
  .select-box select {
    border: none; outline: none; background: transparent;
    font-size: 22px; font-weight: 800; color: var(--gray-800);
    text-align: center; font-family: inherit; cursor: pointer; width: 100%;
  }

  /* Form akun */
  .field-label { font-size: 11px; font-weight: 700; color: var(--gray-500); text-transform: uppercase; letter-spacing: .4px; margin-bottom: 5px; display: block; }
  .field-input {
    width: 100%; padding: 12px 14px; margin-bottom: 13px;
    border: 1.5px solid var(--gray-200); border-radius: 10px;
    font-size: 14px; font-family: inherit; color: var(--gray-800);
    background: var(--gray-50); outline: none; transition: all .18s;
  }
  .field-input:focus { border-color: var(--green-500); background: white; box-shadow: 0 0 0 3px rgba(34,197,94,.12); }
  .field-input.error { border-color: #ef4444; }

  .alert-box { border-radius: 10px; padding: 10px 14px; font-size: 12.5px; font-weight: 600; margin-bottom: 14px; display: none; }
  .alert-box.red   { background: #fee2e2; border: 1px solid #fca5a5; color: #b91c1c; }
  .alert-box.green { background: #dcfce7; border: 1px solid #86efac; color: #15803d; }

  .lupa-link { display: block; text-align: right; font-size: 12px; color: var(--green-600); font-weight: 600; text-decoration: none; margin-bottom: 16px; margin-top: -6px; }
  .lupa-link:hover { text-decoration: underline; }

  .divider { border: none; border-top: 1px solid var(--gray-100); margin: 4px 0 16px; position: relative; text-align: center; }
  .divider span { background: white; padding: 0 10px; font-size: 11px; color: var(--gray-400); position: relative; top: -8px; }

  .toast { display: none; position: fixed; bottom: 24px; right: 24px; z-index: 9999; background: var(--green-500); color: white; padding: 12px 20px; border-radius: 12px; font-size: 13px; font-weight: 700; box-shadow: 0 4px 20px rgba(34,197,94,.4); }
</style>
@endpush

@section('content')

<div class="page-heading">
  <svg viewBox="0 0 24 24" class="heading-icon">
    <path d="M19.14 12.94a7.43 7.43 0 0 0 .05-.94 7.43 7.43 0 0 0-.05-.94l2.03-1.58a.5.5 0 0 0 .12-.64l-1.92-3.32a.5.5 0 0 0-.6-.22l-2.39.96a7.28 7.28 0 0 0-1.63-.94l-.36-2.54a.5.5 0 0 0-.5-.42h-3.84a.5.5 0 0 0-.5.42l-.36 2.54a7.28 7.28 0 0 0-1.63.94l-2.39-.96a.5.5 0 0 0-.6.22L2.71 8.84a.5.5 0 0 0 .12.64l2.03 1.58a7.43 7.43 0 0 0-.05.94c0 .32.02.63.05.94l-2.03 1.58a.5.5 0 0 0-.12.64l1.92 3.32c.14.24.43.34.68.22l2.39-.96c.5.39 1.04.71 1.63.94l.36 2.54c.05.25.26.42.5.42h3.84c.24 0 .45-.17.5-.42l.36-2.54c.59-.23 1.13-.55 1.63-.94l2.39.96c.25.12.54.02.68-.22l1.92-3.32a.5.5 0 0 0-.12-.64l-2.03-1.58zM12 15.5A3.5 3.5 0 1 1 12 8a3.5 3.5 0 0 1 0 7.5z"/>
  </svg>
  Settings
</div>

<div class="card" style="padding: 24px;">
  <div class="settings-grid">
    <div class="setting-item" onclick="openModal('sensor-jarak')">
      <div class="setting-icon">
        <svg viewBox="0 0 24 24" fill="currentColor">
          <rect x="10" y="10" width="4" height="4" rx="1"/>
          <path d="M6 12c0-2 2-4 4-4"/>
          <path d="M18 12c0-2-2-4-4-4"/>
          <path d="M4 12c0-3.5 3-6 6-6"/>
          <path d="M20 12c0-3.5-3-6-6-6"/>
        </svg>
      </div>
      <div class="setting-label">Sensor Jarak</div>
    </div>
    <div class="setting-item" onclick="openModal('getaran')">
      <div class="setting-icon">
        <svg viewBox="0 0 24 24" fill="currentColor">
          <rect x="6" y="10" width="12" height="4" rx="2"/>
          <path d="M3 9c1 1 1 5 0 6"/>
          <path d="M21 9c-1 1-1 5 0 6"/>
        </svg>
      </div>
      <div class="setting-label">Getaran (Feedback)</div>
    </div>
    <div class="setting-item" onclick="openModal('gps')">
      <div class="setting-icon"><svg viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/><circle cx="12" cy="9" r="2.5"/></svg></div>
      <div class="setting-label">GPS</div>
    </div>
    <div class="setting-item" onclick="openModal('baterai')">
      <div class="setting-icon"><svg viewBox="0 0 24 24"><rect x="2" y="7" width="18" height="10" rx="2"/><path d="M22 11v2"/><rect x="4" y="9" width="10" height="6" rx="1" style="fill:var(--green-500);stroke:none"/></svg></div>
      <div class="setting-label">Baterai</div>
    </div>
    <div class="setting-item" onclick="openModal('akun')">
      <div class="setting-icon"><svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg></div>
      <div class="setting-label">Akun</div>
    </div>
  </div>
</div>

{{-- Modal Sensor Jarak --}}
<div class="modal-backdrop" id="modal-sensor-jarak" onclick="closeOnBackdrop(event,'modal-sensor-jarak')">
  <div class="modal-box">
    <button class="modal-close" onclick="closeModal('modal-sensor-jarak')">✕</button>
    <div class="modal-title">Sensor Jarak</div>
    <div class="section-label">Sensitivitas Jarak</div>
    <div class="value-grid cols-2">
      <div class="value-box"><div class="value-box-label">Waspada (cm)</div><input class="num-input" type="number" value="50" min="10" max="300"></div>
      <div class="value-box"><div class="value-box-label">Bahaya (cm)</div><input class="num-input" type="number" value="20" min="5" max="100"></div>
    </div>
    <div class="section-label">Status Sensor</div>
    <button class="action-btn green" onclick="toggleBtn(this)">AKTIF</button>
  </div>
</div>

{{-- Modal Getaran --}}
<div class="modal-backdrop" id="modal-getaran" onclick="closeOnBackdrop(event,'modal-getaran')">
  <div class="modal-box" style="max-width:460px;">
    <button class="modal-close" onclick="closeModal('modal-getaran')">✕</button>
    <div class="modal-title">Getaran (Feedback)</div>
    <div class="section-label">Kekuatan Getaran</div>
    <div class="value-grid cols-2">
      <div class="value-box selected" onclick="selectBox(this)"><div class="value-box-label">KIRI</div><div class="value-box-big">SEDANG</div></div>
      <div class="value-box" onclick="selectBox(this)"><div class="value-box-label">KANAN</div><div class="value-box-big">KUAT</div></div>
    </div>
    <div class="section-label">Tes Getar</div>
    <div class="tes-grid">
      <button class="tes-btn" onclick="showToast('📳 Tes getar KIRI dikirim!')">KIRI</button>
      <button class="tes-btn" onclick="showToast('📳 Tes getar KANAN dikirim!')">KANAN</button>
    </div>
    <div class="section-label">Pola Getaran</div>
    <div class="value-grid cols-2">
      <div class="value-box selected" onclick="selectBox(this)"><div class="value-box-label">Waspada</div><div class="value-box-big">LAMBAT</div></div>
      <div class="value-box" onclick="selectBox(this)"><div class="value-box-label">Bahaya</div><div class="value-box-big">CEPAT</div></div>
    </div>
  </div>
</div>

{{-- Modal GPS --}}
<div class="modal-backdrop" id="modal-gps" onclick="closeOnBackdrop(event,'modal-gps')">
  <div class="modal-box">
    <button class="modal-close" onclick="closeModal('modal-gps')">✕</button>
    <div class="modal-title">GPS</div>
    <div class="section-label">Interval Update Lokasi</div>
    <div class="select-box"><select><option>1 detik</option><option selected>5 detik</option><option>10 detik</option><option>30 detik</option></select></div>
    <div class="section-label">Status Sensor</div>
    <button class="action-btn green" onclick="toggleBtn(this)">AKTIF</button>
  </div>
</div>

{{-- Modal Baterai --}}
<div class="modal-backdrop" id="modal-baterai" onclick="closeOnBackdrop(event,'modal-baterai')">
  <div class="modal-box">
    <button class="modal-close" onclick="closeModal('modal-baterai')">✕</button>
    <div class="modal-title">Baterai</div>
    <div class="section-label">Persentase Baterai</div>
    <div class="value-box" style="cursor:default;"><div class="value-box-big" id="baterai-display">50 %</div></div>
    <div class="section-label">Mode Hemat Daya</div>
    <button class="action-btn green" onclick="toggleBtn(this)">AKTIF</button>
  </div>
</div>

{{-- ══════════════════════════════════
     MODAL AKUN — tersambung ke backend
══════════════════════════════════ --}}
<div class="modal-backdrop" id="modal-akun" onclick="closeOnBackdrop(event,'modal-akun')">
  <div class="modal-box" style="max-width:480px;">
    <button class="modal-close" onclick="closeModal('modal-akun')">✕</button>
    <div class="modal-title">Akun</div>

    <div class="alert-box red"   id="akun-error"></div>
    <div class="alert-box green" id="akun-success"></div>

    <label class="field-label">NAMA LENGKAP</label>
    <input type="text" id="akun-name" class="field-input" value="{{ auth()->user()->name ?? '' }}">

    <label class="field-label">EMAIL</label>
    <input type="email" id="akun-email" class="field-input" value="{{ auth()->user()->email ?? '' }}">

    <hr class="divider"><span></span>

    <label class="field-label">PASSWORD LAMA</label>
    <input type="password" id="akun-current" class="field-input" placeholder="Masukkan password lama">

    <label class="field-label">PASSWORD BARU</label>
    <input type="password" id="akun-password" class="field-input" placeholder="Min. 8 karakter">

    <label class="field-label">KONFIRMASI PASSWORD</label>
    <input type="password" id="akun-confirm" class="field-input" placeholder="Ulangi password baru">

    {{-- Link ke halaman lupa password --}}
    <a href="{{ route('password.request') }}" class="lupa-link">Lupa password? Klik di sini →</a>

    <button class="action-btn green" id="btn-simpan" onclick="simpanAkun()">SIMPAN</button>
  </div>
</div>

<div class="toast" id="toast"></div>

@endsection

@push('scripts')
<script>
  function openModal(name) {
    document.getElementById('modal-'+name).classList.add('open');
    document.body.style.overflow = 'hidden';
  }
  function closeModal(id) {
    document.getElementById(id).classList.remove('open');
    document.body.style.overflow = '';
  }
  function closeOnBackdrop(e, id) {
    if (e.target === document.getElementById(id)) closeModal(id);
  }
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
      document.querySelectorAll('.modal-backdrop.open').forEach(m => {
        m.classList.remove('open');
        document.body.style.overflow = '';
      });
    }
  });
  function toggleBtn(btn) {
    if (btn.classList.contains('green')) {
      btn.classList.replace('green','gray'); btn.textContent = 'NONAKTIF';
    } else {
      btn.classList.replace('gray','green'); btn.textContent = 'AKTIF';
    }
  }
  function selectBox(el) {
    el.closest('.value-grid').querySelectorAll('.value-box').forEach(b => b.classList.remove('selected'));
    el.classList.add('selected');
  }
  function showToast(msg) {
    const t = document.getElementById('toast');
    t.textContent = msg; t.style.display = 'block';
    setTimeout(() => { t.style.display = 'none'; }, 2500);
  }

  // ── SIMPAN AKUN via AJAX ──
  function simpanAkun() {
    const btn    = document.getElementById('btn-simpan');
    const errEl  = document.getElementById('akun-error');
    const okEl   = document.getElementById('akun-success');
    const name   = document.getElementById('akun-name').value.trim();
    const email  = document.getElementById('akun-email').value.trim();
    const curr   = document.getElementById('akun-current').value;
    const pass   = document.getElementById('akun-password').value;
    const conf   = document.getElementById('akun-confirm').value;

    // Reset alert
    errEl.style.display = 'none';
    okEl.style.display  = 'none';

    // Validasi sisi klien
    if (!name || !email) {
      errEl.textContent = 'Nama dan email wajib diisi.';
      errEl.style.display = 'block'; return;
    }
    if (pass && pass.length < 8) {
      errEl.textContent = 'Password baru minimal 8 karakter.';
      errEl.style.display = 'block'; return;
    }
    if (pass && pass !== conf) {
      errEl.textContent = 'Konfirmasi password tidak cocok.';
      errEl.style.display = 'block'; return;
    }
    if (pass && !curr) {
      errEl.textContent = 'Masukkan password lama untuk ganti password.';
      errEl.style.display = 'block'; return;
    }

    // Kirim ke Laravel
    btn.disabled = true;
    btn.textContent = 'Menyimpan...';

    const body = { name, email, _token: '{{ csrf_token() }}' };
    if (pass) {
      body.current_password   = curr;
      body.password           = pass;
      body.password_confirmation = conf;
    }

    fetch('{{ route("settings.akun") }}', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
      body: JSON.stringify(body)
    })
    .then(r => r.json())
    .then(d => {
      btn.disabled = false;
      btn.textContent = 'SIMPAN';

      if (d.success) {
        okEl.textContent = '✅ ' + d.message;
        okEl.style.display = 'block';

        // Update nama di sidebar & topbar
        document.querySelectorAll('.user-name, .topbar-profile').forEach(el => {
          if (el.classList.contains('user-name')) el.textContent = d.name;
        });

        // Kosongkan field password
        document.getElementById('akun-current').value  = '';
        document.getElementById('akun-password').value = '';
        document.getElementById('akun-confirm').value  = '';

        showToast('✅ Akun berhasil diperbarui!');

        setTimeout(() => { okEl.style.display = 'none'; }, 3000);
      } else {
        // Tampilkan error dari Laravel
        const msgs = d.errors
          ? Object.values(d.errors).flat().join(' ')
          : (d.message || 'Terjadi kesalahan.');
        errEl.textContent = '❌ ' + msgs;
        errEl.style.display = 'block';
      }
    })
    .catch(() => {
      btn.disabled = false;
      btn.textContent = 'SIMPAN';
      errEl.textContent = '❌ Gagal terhubung ke server.';
      errEl.style.display = 'block';
    });
  }

  // Ambil persentase baterai
  fetch('{{ route("api.device.status") }}', {
    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
  })
  .then(r => r.json())
  .then(d => {
    if (d.battery_pct) document.getElementById('baterai-display').textContent = d.battery_pct + ' %';
  }).catch(()=>{});
</script>
@endpush
