@extends('admin.layout')

@section('title', 'Profil & Tanda Tangan Digital Petugas')

@section('content')
<div class="max-w-3xl mx-auto space-y-6 pb-12">

    <!-- Header Profil Card -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm relative overflow-hidden">
        <div class="absolute -right-8 -top-8 w-32 h-32 bg-emerald-50 rounded-full blur-2xl pointer-events-none"></div>
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 relative z-10">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl {{ $user->isSuperAdmin() ? 'bg-gradient-to-tr from-emerald-600 to-teal-500' : ($user->isBendahara() ? 'bg-gradient-to-tr from-amber-500 to-orange-500' : 'bg-gradient-to-tr from-blue-600 to-indigo-500') }} text-white font-black flex items-center justify-center text-2xl shadow-md shrink-0">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <div class="flex flex-wrap items-center gap-2">
                        <h1 class="text-lg font-bold text-slate-900">{{ $user->name }}</h1>
                        <span class="text-[11px] font-bold px-2.5 py-0.5 rounded-full {{ $user->isSuperAdmin() ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : ($user->isBendahara() ? 'bg-amber-100 text-amber-800 border border-amber-200' : 'bg-blue-100 text-blue-800 border border-blue-200') }}">
                            {{ $user->isSuperAdmin() ? 'Superadmin' : ($user->isBendahara() ? 'Bendahara Pesantren' : 'Administrator') }}
                        </span>
                        @if($user->jabatan)
                            <span class="text-[11px] font-semibold px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 border border-slate-200">
                                {{ $user->jabatan }}
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-slate-500 mt-1 font-mono flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        {{ $user->email }}
                    </p>
                </div>
            </div>

            <div class="text-left sm:text-right">
                <span class="text-[11px] text-slate-400 block">Status TTD Digital</span>
                @if($user->signature_image && file_exists(public_path($user->signature_image)))
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 mt-0.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        Sudah Ada TTD
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200 mt-0.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                        Belum Ada TTD
                    </span>
                @endif
            </div>
        </div>
    </div>

    <!-- Banner Info Kwitansi -->
    <div class="p-4 rounded-xl bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-200/80 flex items-start gap-3 shadow-xs">
        <div class="w-8 h-8 rounded-lg bg-emerald-600 text-white flex items-center justify-center shrink-0 mt-0.5 shadow-xs">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
        </div>
        <div class="text-xs text-slate-700 leading-relaxed">
            <strong class="font-bold text-emerald-950 block text-sm mb-0.5">Otomatisasi TTD & Nama Petugas Kasir / Kwitansi</strong>
            Nama lengkap, jabatan, dan tanda tangan digital di bawah ini akan langsung tampil di lembar <strong>Bukti Setoran & Bukti Penarikan Tabungan (Kwitansi)</strong> saat Anda bertugas mencatat pembayaran santri.
        </div>
    </div>

    <!-- Form Utama -->
    <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data" class="space-y-6" id="profileForm">
        @csrf
        @method('PUT')

        <!-- Hidden input untuk data base64 canvas jika user menggambar TTD -->
        <input type="hidden" name="signature_canvas" id="signature_canvas" value="">

        <!-- CARD 1: BIODATA & JABATAN PETUGAS -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
            <h2 class="text-sm font-bold text-slate-800 flex items-center gap-2 border-b border-slate-100 pb-3">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Biodata & Identitas Petugas
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Nama Lengkap & Gelar <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required 
                        placeholder="Contoh: Ust. H. Ahmad Fauzi, S.Pd.I"
                        class="w-full px-3.5 py-2.5 text-xs font-medium border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
                    <span class="text-[10px] text-slate-400 mt-1 block">Nama ini tercetak di bawah tanda tangan petugas pada kwitansi.</span>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Jabatan / Posisi Petugas
                    </label>
                    <input type="text" name="jabatan" value="{{ old('jabatan', $user->jabatan ?: ($user->isBendahara() ? 'Bendahara Pesantren' : '')) }}" 
                        placeholder="Contoh: Bendahara Pesantren / Kasir Keuangan"
                        class="w-full px-3.5 py-2.5 text-xs font-medium border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
                    <span class="text-[10px] text-slate-400 mt-1 block">Jabatan ini tercantum di bawah nama Anda pada bukti transaksi.</span>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Alamat Email Akun</label>
                    <input type="email" value="{{ $user->email }}" disabled 
                        class="w-full px-3.5 py-2.5 text-xs border border-slate-200 bg-slate-50 text-slate-500 rounded-xl outline-none font-mono cursor-not-allowed">
                    <span class="text-[10px] text-slate-400 mt-1 block">Email digunakan sebagai username login akun Anda.</span>
                </div>
            </div>
        </div>

        <!-- CARD 2: TANDA TANGAN DIGITAL (TTD) -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-5" x-data="{ ttdTab: 'canvas' }">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h2 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    Tanda Tangan Digital (TTD) Petugas
                </h2>
                <span class="text-[11px] text-slate-500">Khusus Akun: <strong>{{ $user->name }}</strong></span>
            </div>

            <!-- PREVIEW TTD SAAT INI (JIKA ADA) -->
            @if($user->signature_image && file_exists(public_path($user->signature_image)))
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <!-- Checkerboard Pattern Background untuk Transparansi -->
                        <div class="w-40 h-20 rounded-lg border border-slate-300 bg-[linear-gradient(45deg,#f1f5f9_25%,transparent_25%),linear-gradient(-45deg,#f1f5f9_25%,transparent_25%),linear-gradient(45deg,transparent_75%,#f1f5f9_75%),linear-gradient(-45deg,transparent_75%,#f1f5f9_75%)] bg-[size:16px_16px] bg-[position:0_0,0_8px,8px_-8px,-8px_0] p-2 flex items-center justify-center shrink-0">
                            <img src="{{ $user->signature_image }}" alt="TTD Saya" class="max-h-full max-w-full object-contain">
                        </div>
                        <div>
                            <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded">
                                ✓ TTD Aktif Digunakan
                            </span>
                            <p class="text-xs text-slate-600 mt-1">Tanda tangan ini saat ini tercetak di setiap kwitansi pembayaran yang Anda simpan.</p>
                        </div>
                    </div>
                    <label class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-rose-50 border border-rose-200 text-rose-700 hover:bg-rose-100 text-xs font-semibold cursor-pointer transition shrink-0">
                        <input type="checkbox" name="remove_signature" value="1" class="rounded text-rose-600 focus:ring-rose-500">
                        <span>Hapus / Reset TTD</span>
                    </label>
                </div>
            @endif

            <!-- TAB PILIHAN INPUT TTD: GORESKAN DI LAYAR ATAU UPLOAD FILE -->
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <label class="block text-xs font-bold text-slate-800">
                        {{ $user->signature_image ? 'Ganti Tanda Tangan Baru' : 'Tetapkan Tanda Tangan Digital Baru' }}
                    </label>
                    <div class="flex items-center gap-1 p-1 bg-slate-100 rounded-lg text-xs font-semibold">
                        <button type="button" @click="ttdTab = 'canvas'; initSignaturePad();" :class="ttdTab === 'canvas' ? 'bg-white text-emerald-700 shadow-xs' : 'text-slate-600 hover:text-slate-900'" class="px-3 py-1.5 rounded-md transition">
                            ✍️ Goreskan di Layar
                        </button>
                        <button type="button" @click="ttdTab = 'upload'" :class="ttdTab === 'upload' ? 'bg-white text-emerald-700 shadow-xs' : 'text-slate-600 hover:text-slate-900'" class="px-3 py-1.5 rounded-md transition">
                            📁 Unggah File Gambar
                        </button>
                    </div>
                </div>

                <!-- OPSI A: CANVAS PAD (GORESKAN DI LAYAR / TOUCH / MOUSE) -->
                <div x-show="ttdTab === 'canvas'" class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-3">
                    <div class="flex items-center justify-between text-xs text-slate-600">
                        <span>Gunakan mouse, touchpad, atau jari/stylus di layar sentuh untuk tanda tangan:</span>
                        <span id="canvasAppliedStatus" class="hidden text-xs font-bold text-emerald-700 flex items-center gap-1">
                            ✓ TTD siap disimpan!
                        </span>
                    </div>

                    <div class="relative w-full max-w-lg mx-auto bg-white rounded-xl border-2 border-dashed border-slate-300 p-1">
                        <canvas id="sigCanvas" width="480" height="160" class="w-full h-40 cursor-crosshair block touch-none rounded-lg bg-transparent"></canvas>
                        <div class="absolute bottom-6 left-6 right-6 border-b border-dashed border-slate-300 pointer-events-none text-center">
                            <span class="text-[10px] text-slate-400 bg-white px-2 -bottom-2 relative">Goreskan tanda tangan di atas garis ini</span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between gap-2 max-w-lg mx-auto">
                        <button type="button" onclick="clearSignatureCanvas()" class="px-3.5 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-xl text-xs font-semibold transition flex items-center gap-1.5 cursor-pointer">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            <span>Hapus / Ulangi</span>
                        </button>
                        <button type="button" onclick="applySignatureCanvas()" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-xs transition flex items-center gap-1.5 cursor-pointer">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Terapkan Goresan TTD</span>
                        </button>
                    </div>
                </div>

                <!-- OPSI B: UPLOAD FILE GAMBAR -->
                <div x-show="ttdTab === 'upload'" class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-3" style="display: none;">
                    <div class="flex flex-col sm:flex-row items-center gap-4">
                        <div id="uploadPreviewBox" class="w-36 h-24 rounded-lg border border-slate-300 bg-[linear-gradient(45deg,#f1f5f9_25%,transparent_25%),linear-gradient(-45deg,#f1f5f9_25%,transparent_25%),linear-gradient(45deg,transparent_75%,#f1f5f9_75%),linear-gradient(-45deg,transparent_75%,#f1f5f9_75%)] bg-[size:16px_16px] bg-[position:0_0,0_8px,8px_-8px,-8px_0] p-2 flex items-center justify-center shrink-0">
                            <img id="filePreviewImg" src="" alt="Preview TTD" class="max-h-full max-w-full object-contain hidden">
                            <span id="filePreviewPlaceholder" class="text-[10px] text-slate-400 text-center">Preview file TTD</span>
                        </div>
                        <div class="flex-1 space-y-1.5">
                            <label class="block text-xs font-semibold text-slate-700">Pilih Berkas Gambar Tanda Tangan (PNG / JPG / WEBP)</label>
                            <input type="file" name="signature_file" id="signature_file" accept="image/png, image/jpeg, image/webp" 
                                onchange="previewSignatureFile(this)"
                                class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                            <p class="text-[11px] text-slate-500">
                                Disarankan menggunakan file format <strong>PNG transparan</strong> agar tanda tangan menyatu sempurna dengan cap stempel pesantren.
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- CARD 3: KEAMANAN & GANTI PASSWORD -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
            <h2 class="text-sm font-bold text-slate-800 flex items-center gap-2 border-b border-slate-100 pb-3">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                Ganti Kata Sandi (Kosongkan bila tidak ingin diubah)
            </h2>

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Kata Sandi Saat Ini</label>
                    <input type="password" name="current_password" placeholder="Masukkan kata sandi lama Anda jika ingin mengganti sandi" 
                        class="w-full px-3.5 py-2.5 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Kata Sandi Baru</label>
                        <input type="password" name="password" minlength="6" placeholder="Minimal 6 karakter" 
                            class="w-full px-3.5 py-2.5 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Ulangi Kata Sandi Baru</label>
                        <input type="password" name="password_confirmation" minlength="6" placeholder="Konfirmasi sandi baru" 
                            class="w-full px-3.5 py-2.5 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
                    </div>
                </div>
            </div>
        </div>

        <!-- Tombol Aksi Simpan -->
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('admin.pembayaran.index') }}" class="px-5 py-2.5 text-xs font-semibold text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 rounded-xl transition shadow-xs">
                Kembali
            </a>
            <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-md hover:shadow-lg transition flex items-center gap-2 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>Simpan Profil & Tanda Tangan</span>
            </button>
        </div>

    </form>
</div>

<script>
    let sigCanvas, sigCtx, isDrawing = false, hasDrawn = false;

    function initSignaturePad() {
        setTimeout(() => {
            sigCanvas = document.getElementById('sigCanvas');
            if (!sigCanvas) return;
            sigCtx = sigCanvas.getContext('2d');
            sigCtx.strokeStyle = '#0f2b5c'; // Classic pen ink
            sigCtx.lineWidth = 3;
            sigCtx.lineCap = 'round';
            sigCtx.lineJoin = 'round';

            function getPos(e) {
                const rect = sigCanvas.getBoundingClientRect();
                const scaleX = sigCanvas.width / rect.width;
                const scaleY = sigCanvas.height / rect.height;
                const clientX = e.clientX || (e.touches && e.touches[0] ? e.touches[0].clientX : 0);
                const clientY = e.clientY || (e.touches && e.touches[0] ? e.touches[0].clientY : 0);
                return {
                    x: (clientX - rect.left) * scaleX,
                    y: (clientY - rect.top) * scaleY
                };
            }

            function startDraw(e) {
                if (e.target !== sigCanvas) return;
                e.preventDefault();
                isDrawing = true;
                hasDrawn = true;
                const pos = getPos(e);
                sigCtx.beginPath();
                sigCtx.moveTo(pos.x, pos.y);
            }

            function draw(e) {
                if (!isDrawing) return;
                e.preventDefault();
                const pos = getPos(e);
                sigCtx.lineTo(pos.x, pos.y);
                sigCtx.stroke();
            }

            function stopDraw(e) {
                if (isDrawing) {
                    isDrawing = false;
                    sigCtx.closePath();
                }
            }

            sigCanvas.addEventListener('mousedown', startDraw);
            sigCanvas.addEventListener('mousemove', draw);
            window.addEventListener('mouseup', stopDraw);

            sigCanvas.addEventListener('touchstart', startDraw, { passive: false });
            sigCanvas.addEventListener('touchmove', draw, { passive: false });
            window.addEventListener('touchend', stopDraw);
        }, 100);
    }

    function clearSignatureCanvas() {
        if (!sigCanvas || !sigCtx) return;
        sigCtx.clearRect(0, 0, sigCanvas.width, sigCanvas.height);
        hasDrawn = false;
        document.getElementById('signature_canvas').value = '';
        const canvasStatus = document.getElementById('canvasAppliedStatus');
        if (canvasStatus) canvasStatus.classList.add('hidden');
    }

    function applySignatureCanvas() {
        if (!sigCanvas || !hasDrawn) {
            alert('Silakan goreskan tanda tangan Anda pada kotak canvas terlebih dahulu.');
            return;
        }

        const dataUrl = sigCanvas.toDataURL('image/png');
        document.getElementById('signature_canvas').value = dataUrl;

        // Reset file upload
        const fileInput = document.getElementById('signature_file');
        if (fileInput) fileInput.value = '';

        const canvasStatus = document.getElementById('canvasAppliedStatus');
        if (canvasStatus) canvasStatus.classList.remove('hidden');

        alert('Tanda tangan digital berhasil diterapkan! Klik tombol "Simpan Profil & Tanda Tangan" di bagian bawah untuk menyimpan permanen.');
    }

    function previewSignatureFile(input) {
        const previewImg = document.getElementById('filePreviewImg');
        const placeholder = document.getElementById('filePreviewPlaceholder');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewImg.classList.remove('hidden');
                placeholder.classList.add('hidden');
            };
            reader.readAsDataURL(input.files[0]);

            // Kosongkan canvas jika upload file
            document.getElementById('signature_canvas').value = '';
            const canvasStatus = document.getElementById('canvasAppliedStatus');
            if (canvasStatus) canvasStatus.classList.add('hidden');
        } else {
            previewImg.classList.add('hidden');
            placeholder.classList.remove('hidden');
        }
    }

    // Init canvas saat halaman dimuat jika tab canvas aktif
    document.addEventListener('DOMContentLoaded', () => {
        initSignaturePad();
    });
</script>
@endsection
