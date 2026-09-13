<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi Pembayaran &amp; Tagihan Santri — {{ $student->nama_lengkap }}</title>
    <link rel="icon" href="/logo.png" type="image/png">
    
    <!-- Google Fonts: Plus Jakarta Sans & JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'monospace'],
                    },
                    colors: {
                        pondok: {
                            50: '#edfbf2',
                            100: '#d4f5de',
                            600: '#1e7d42',
                            700: '#1a6b38',
                            800: '#145a2e',
                            900: '#0d3b1e',
                        }
                    }
                }
            }
        }
    </script>
    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="min-h-screen bg-slate-50 flex flex-col justify-between font-sans antialiased text-slate-800"
      x-data="{
          modalUpload: false,
          modalPreview: false,
          modalUploadSurat: false,
          suratTargetBill: {
              id: null,
              judul: '',
              jenis_surat: '',
              instruksi: ''
          },
          previewImgUrl: '',
          previewTitle: '',
          selectedBank: 'Bank Syariah Indonesia (BSI) - 714 556 7890 (a.n. Pondok Pesantren Hidayatullah Tuksongo)',
          uploadNominal: {{ $unpaidBills->sum('sisa_tagihan') ?: 0 }},
          selectedBills: {{ json_encode($unpaidBills->pluck('id')->values()->toArray()) }},
          billsData: {{ json_encode($unpaidBills->map(fn($b) => ['id' => $b->id, 'sisa' => (float)$b->sisa_tagihan, 'judul' => $b->judul_tagihan, 'pos' => $b->pos_biaya])->values()) }},
          filePreview: '',

          toggleSelectAll(checked) {
              if (checked) {
                  this.selectedBills = this.billsData.map(b => b.id);
              } else {
                  this.selectedBills = [];
              }
              this.recalcNominal();
          },

          toggleBill(id) {
              const idx = this.selectedBills.indexOf(id);
              if (idx > -1) {
                  this.selectedBills.splice(idx, 1);
              } else {
                  this.selectedBills.push(id);
              }
              this.recalcNominal();
          },

          isBillChecked(id) {
              return this.selectedBills.includes(id);
          },

          recalcNominal() {
              let total = 0;
              this.billsData.forEach(b => {
                  if (this.selectedBills.includes(b.id)) {
                      total += parseFloat(b.sisa) || 0;
                  }
              });
              this.uploadNominal = total;
          },

          handleFileChange(e) {
              const file = e.target.files[0];
              if (file) {
                  this.filePreview = URL.createObjectURL(file);
              } else {
                  this.filePreview = '';
              }
          },

          openUploadForBill(id, sisa) {
              this.selectedBills = [id];
              this.uploadNominal = sisa;
              this.modalUpload = true;
          },

          openUploadSurat(id, judul, jenisSurat, instruksi) {
              this.suratTargetBill.id = id;
              this.suratTargetBill.judul = judul;
              this.suratTargetBill.jenis_surat = jenisSurat;
              this.suratTargetBill.instruksi = instruksi;
              this.modalUploadSurat = true;
          },

          formatRp(num) {
              return 'Rp ' + new Intl.NumberFormat('id-ID').format(num || 0);
          }
      }">

    <!-- Top Navigation Bar -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-40 shadow-xs px-4 sm:px-8 py-3.5">
        <div class="max-w-6xl mx-auto flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('santri.dashboard') }}">
                    <img src="/logo.png" alt="Logo" class="w-10 h-10 object-contain">
                </a>
                <div>
                    <h2 class="text-xs sm:text-sm font-bold text-slate-900 leading-tight">Pondok Pesantren Hidayatullah</h2>
                    <span class="text-[11px] text-emerald-700 font-semibold tracking-wide">Portal Keuangan &amp; Tagihan Santri</span>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('santri.dashboard') }}" class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200 hover:bg-slate-50 text-xs font-semibold text-slate-600 transition">
                    &larr; Dasbor Utama
                </a>
                <div class="hidden md:flex flex-col text-right">
                    <span class="text-xs font-bold text-slate-900">{{ $student->nama_lengkap }}</span>
                    <span class="text-[11px] font-mono text-slate-400">Kelas {{ $student->kelas }} (NIS: {{ $student->nis }})</span>
                </div>
                <form action="{{ route('santri.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-300 hover:bg-rose-50 hover:text-rose-600 hover:border-rose-300 text-xs font-semibold text-slate-700 transition cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 max-w-6xl w-full mx-auto p-4 sm:p-8 space-y-6">

        <!-- Flash Notifications -->
        @if(session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50/90 p-4 text-xs sm:text-sm text-emerald-900 flex items-start gap-3 shadow-xs">
                <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div>
                    <p class="font-bold">Alhamdulillah, Berhasil!</p>
                    <p>{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="rounded-2xl border border-rose-200 bg-rose-50/90 p-4 text-xs sm:text-sm text-rose-900 flex items-start gap-3 shadow-xs">
                <svg class="w-5 h-5 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div>
                    <p class="font-bold">Perhatian!</p>
                    <p>{{ session('error') }}</p>
                </div>
            </div>
        @endif

        @if(isset($errors) && $errors->any())
            <div class="rounded-2xl border border-rose-200 bg-rose-50/90 p-4 text-xs sm:text-sm text-rose-900 shadow-xs">
                <p class="font-bold mb-1">Gagal Mengunggah Bukti Pembayaran:</p>
                <ul class="list-disc list-inside space-y-0.5 text-xs text-rose-700">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Nav Breadcrumb -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2 text-xs">
                <a href="{{ route('santri.dashboard') }}" class="text-emerald-700 font-semibold hover:underline">Dasbor Santri</a>
                <span class="text-slate-300">/</span>
                <span class="text-slate-500 font-medium">Informasi Pembayaran &amp; Tagihan</span>
            </div>
            <a href="{{ route('santri.dashboard') }}" class="sm:hidden text-xs text-emerald-700 font-semibold hover:underline">
                &larr; Dasbor
            </a>
        </div>

        <!-- Banner Header -->
        <div class="rounded-3xl bg-gradient-to-r from-emerald-900 via-emerald-800 to-teal-800 p-6 sm:p-8 text-white shadow-lg relative overflow-hidden">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="space-y-2">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-xs font-semibold text-emerald-200">
                        <span>💳 Status Pembayaran Santri</span>
                        <span>•</span>
                        <span>Kelas {{ $student->kelas }} ({{ $student->jenjang }})</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-bold tracking-tight">Tagihan &amp; Riwayat Pembayaran</h1>
                    <p class="text-xs sm:text-sm text-emerald-100 max-w-2xl leading-relaxed">
                        Pantau rincian biaya pesantren (Syahriyah/SPP, Uang Makan, SOT, Tabungan, Ziarah, Wisuda, dll.), potongan keringanan SKTM, serta kirim bukti transfer secara mandiri ke kasir pesantren.
                    </p>
                    <div class="mt-3 inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-emerald-950/50 border border-emerald-500/30 backdrop-blur-sm shadow-inner">
                        <span class="text-xs text-emerald-200">Saldo Tabungan Santri:</span>
                        <span class="text-sm sm:text-base font-bold font-mono text-white">Rp {{ number_format($student->saldo_tabungan, 0, ',', '.') }}</span>
                    </div>
                </div>
                <div class="shrink-0 flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                    @if($totalTunggakan <= 0)
                        <div class="px-5 py-3 rounded-2xl bg-emerald-500/20 border border-emerald-300/40 text-emerald-200 text-center backdrop-blur-sm">
                            <span class="text-2xl block">🎉</span>
                            <span class="text-xs font-bold uppercase tracking-wider block mt-1">Alhamdulillah, Bebas Tunggakan!</span>
                        </div>
                    @else
                        <div class="px-5 py-3 rounded-2xl bg-amber-500/20 border border-amber-300/40 text-amber-200 text-center backdrop-blur-sm">
                            <span class="text-xs font-bold uppercase tracking-wider block">Ada Tagihan Aktif</span>
                            <span class="text-sm sm:text-base font-black font-mono block mt-0.5">Rp {{ number_format($totalTunggakan, 0, ',', '.') }}</span>
                        </div>
                    @endif

                    @if($unpaidBills->count() > 0)
                        <button type="button" @click="modalUpload = true" class="px-5 py-3 rounded-2xl bg-white text-emerald-900 hover:bg-emerald-50 font-black text-xs sm:text-sm shadow-md transition flex items-center justify-center gap-2 cursor-pointer">
                            <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                            <span>Unggah Bukti Transfer</span>
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Banner Permintaan Berkas Dokumen Keringanan / Pemutihan dari Admin -->
        @if(isset($pendingSuratBills) && $pendingSuratBills->count() > 0)
            <div class="space-y-3">
                @foreach($pendingSuratBills as $psb)
                    @if($psb->status_dispensasi === 'diminta_surat')
                        <div class="rounded-3xl bg-amber-500/10 border-2 border-amber-400 p-5 sm:p-6 shadow-md flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div class="space-y-1.5">
                                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-500 text-white text-[11px] font-black uppercase tracking-wider shadow-xs">
                                    <span>📩 Permintaan Berkas Surat Keringanan</span>
                                </div>
                                <h3 class="text-base sm:text-lg font-bold text-amber-950">
                                    Pihak Pesantren meminta Anda mengunggah: <span class="text-amber-800 underline decoration-amber-400">{{ $psb->jenis_surat_diminta }}</span>
                                </h3>
                                <p class="text-xs sm:text-sm text-amber-900 leading-relaxed">
                                    Untuk penyesuaian / pemutihan tagihan <strong>{{ $psb->judul_tagihan }}</strong> (Sisa: <span class="font-mono font-bold text-rose-700">Rp {{ number_format($psb->sisa_tagihan, 0, ',', '.') }}</span>).
                                    @if($psb->instruksi_surat)
                                        <br><span class="italic text-slate-700 bg-white/80 px-2.5 py-1 rounded-lg border border-amber-200 mt-1 inline-block">Catatan Admin: "{{ $psb->instruksi_surat }}"</span>
                                    @endif
                                </p>
                            </div>
                            <div class="shrink-0">
                                <button type="button" 
                                        @click="openUploadSurat({{ $psb->id }}, '{{ addslashes($psb->judul_tagihan) }}', '{{ addslashes($psb->jenis_surat_diminta) }}', '{{ addslashes($psb->instruksi_surat) }}')"
                                        class="w-full sm:w-auto px-5 py-3 rounded-2xl bg-amber-500 hover:bg-amber-600 text-white font-black text-xs sm:text-sm shadow-md transition flex items-center justify-center gap-2 cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                    <span>Unggah Berkas Sekarang</span>
                                </button>
                            </div>
                        </div>
                    @elseif($psb->status_dispensasi === 'surat_diunggah')
                        <div class="rounded-3xl bg-blue-50 border border-blue-200 p-4 sm:p-5 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <span class="text-2xl">⏳</span>
                                <div>
                                    <h4 class="text-xs sm:text-sm font-bold text-blue-950">Berkas {{ $psb->jenis_surat_diminta }} Telah Terkirim</h4>
                                    <p class="text-xs text-blue-800">
                                        Berkas untuk tagihan <strong>{{ $psb->judul_tagihan }}</strong> telah diunggah pada {{ $psb->surat_uploaded_at ? date('d M Y H:i', strtotime($psb->surat_uploaded_at)) : 'hari ini' }} dan sedang dalam peninjauan oleh bendahara pesantren.
                                    </p>
                                </div>
                            </div>
                            @if($psb->file_surat_dispensasi)
                                <a href="{{ asset($psb->file_surat_dispensasi) }}" target="_blank" class="px-3 py-1.5 rounded-xl bg-white border border-blue-200 text-blue-700 hover:bg-blue-50 font-bold text-xs transition text-center shrink-0">
                                    Lihat File Terunggah ↗
                                </a>
                            @endif
                        </div>
                    @elseif($psb->status_dispensasi === 'ditolak')
                        <div class="rounded-3xl bg-rose-50 border-2 border-rose-300 p-4 sm:p-5 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-3">
                            <div class="space-y-1">
                                <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-rose-200 text-rose-800 text-[10px] font-bold">
                                    <span>❌ Berkas Perlu Diperbaiki</span>
                                </div>
                                <h4 class="text-xs sm:text-sm font-bold text-rose-950">
                                    Berkas {{ $psb->jenis_surat_diminta }} untuk {{ $psb->judul_tagihan }} Ditolak
                                </h4>
                                @if($psb->alasan_penolakan_surat)
                                    <p class="text-xs text-rose-800 bg-white/80 p-2 rounded-xl border border-rose-200">
                                        <strong>Alasan Penolakan:</strong> {{ $psb->alasan_penolakan_surat }}
                                    </p>
                                @endif
                            </div>
                            <div class="shrink-0">
                                <button type="button" 
                                        @click="openUploadSurat({{ $psb->id }}, '{{ addslashes($psb->judul_tagihan) }}', '{{ addslashes($psb->jenis_surat_diminta) }}', '{{ addslashes($psb->instruksi_surat) }}')"
                                        class="w-full sm:w-auto px-4 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs shadow-sm transition flex items-center justify-center gap-1.5 cursor-pointer">
                                    <span>Unggah Ulang Berkas</span>
                                    <span>&rarr;</span>
                                </button>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif

        <!-- Financial Overview Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 {{ ($totalDitangguhkan ?? 0) > 0 ? 'lg:grid-cols-4' : 'lg:grid-cols-3' }} gap-4">
            <div class="bg-white rounded-3xl border border-slate-200 p-5 sm:p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block">Total Semua Tagihan</span>
                        <h3 class="text-xl sm:text-2xl font-black text-slate-800 font-mono mt-1">
                            Rp {{ number_format($totalTagihan, 0, ',', '.') }}
                        </h3>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-700 flex items-center justify-center font-bold text-xl">
                        🧾
                    </div>
                </div>
                <p class="text-[11px] text-slate-400 mt-2">{{ $bills->count() }} pos tagihan tercatat</p>
            </div>

            <div class="bg-white rounded-3xl border border-emerald-200 bg-emerald-50/20 p-5 sm:p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-semibold text-emerald-700 uppercase tracking-wider block">Total Sudah Terbayar</span>
                        <h3 class="text-xl sm:text-2xl font-black text-emerald-700 font-mono mt-1">
                            Rp {{ number_format($totalTerbayar, 0, ',', '.') }}
                        </h3>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-xl">
                        ✅
                    </div>
                </div>
                <p class="text-[11px] text-emerald-700/80 mt-2">Diterima oleh bendahara pesantren</p>
            </div>

            <div class="bg-white rounded-3xl border {{ $totalTunggakan > 0 ? 'border-rose-300 bg-rose-50/30' : 'border-slate-200' }} p-5 sm:p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-semibold {{ $totalTunggakan > 0 ? 'text-rose-600' : 'text-slate-400' }} uppercase tracking-wider block">Sisa Tunggakan Aktif</span>
                        <h3 class="text-xl sm:text-2xl font-black {{ $totalTunggakan > 0 ? 'text-rose-600 font-mono' : 'text-slate-800 font-mono' }} mt-1">
                            Rp {{ number_format($totalTunggakan, 0, ',', '.') }}
                        </h3>
                    </div>
                    <div class="w-12 h-12 rounded-2xl {{ $totalTunggakan > 0 ? 'bg-rose-100 text-rose-600' : 'bg-slate-100 text-slate-500' }} flex items-center justify-center font-bold text-xl">
                        ⏳
                    </div>
                </div>
                <p class="text-[11px] {{ $totalTunggakan > 0 ? 'text-rose-600/80 font-medium' : 'text-slate-400' }} mt-2">
                    {{ ($unpaidActiveBills ?? $unpaidBills)->count() }} pos tagihan aktif belum lunas
                </p>
            </div>

            @if(($totalDitangguhkan ?? 0) > 0)
                <div class="bg-white rounded-3xl border border-indigo-200 bg-indigo-50/30 p-5 sm:p-6 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-[11px] font-semibold text-indigo-700 uppercase tracking-wider block">Ditangguhkan ke Wisuda</span>
                            <h3 class="text-xl sm:text-2xl font-black text-indigo-700 font-mono mt-1">
                                Rp {{ number_format($totalDitangguhkan, 0, ',', '.') }}
                            </h3>
                        </div>
                        <div class="w-12 h-12 rounded-2xl bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-xl">
                            🎓
                        </div>
                    </div>
                    <p class="text-[11px] text-indigo-700/80 mt-2 font-medium">
                        {{ ($wisudaBills ?? collect())->count() }} pos disimpan sampai wisuda
                    </p>
                </div>
            @endif
        </div>

        <!-- Info Keringanan / Beasiswa Santri (Jika Ada) -->
        @if($discounts->count() > 0)
            <div class="bg-purple-50/70 border border-purple-200 rounded-3xl p-5 shadow-xs">
                <div class="flex items-center gap-2 mb-2">
                    <span class="w-6 h-6 rounded-lg bg-purple-200 text-purple-800 flex items-center justify-center text-xs font-bold">🏷️</span>
                    <h3 class="text-xs sm:text-sm font-bold text-purple-950">Status Keringanan &amp; Subsidi Santri Aktif</h3>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-3">
                    @foreach($discounts as $disc)
                        <div class="bg-white rounded-2xl p-3.5 border border-purple-100 text-xs flex items-start justify-between gap-3 shadow-2xs">
                            <div>
                                <span class="font-bold text-purple-900 block text-xs">{{ $disc->jenis_potongan }}</span>
                                @if($disc->no_surat_miskin)
                                    <span class="text-[11px] font-mono text-slate-500 block mt-0.5">No. SKTM: {{ $disc->no_surat_miskin }}</span>
                                @endif
                                <span class="text-[10px] text-slate-400 mt-1 block">Pos Sasaran: <strong>{{ $disc->pos_biaya }}</strong></span>
                            </div>
                            <div class="text-right">
                                <span class="px-2.5 py-1 rounded-full bg-purple-100 text-purple-800 font-extrabold text-xs font-mono">
                                    @if($disc->tipe_nilai === 'persen')
                                        Potongan {{ rtrim(rtrim(number_format($disc->nilai, 2, ',', '.'), '0'), ',') }}%
                                    @else
                                        - Rp {{ number_format($disc->nilai, 0, ',', '.') }}
                                    @endif
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- TABEL 1: TAGIHAN AKTIF / BELUM LUNAS -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                <div>
                    <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-rose-500 inline-block"></span>
                        <span>Daftar Tagihan Belum Lunas</span>
                        <span class="px-2 py-0.5 rounded-full bg-rose-50 border border-rose-200 text-rose-700 text-xs font-bold font-mono">
                            {{ $unpaidBills->count() }} Tagihan
                        </span>
                    </h3>
                    <p class="text-xs text-slate-400 mt-0.5">
                        Anda dapat membayar per pos (misal hanya Syahriyah atau hanya Makan) atau langsung melunasi beberapa pos sekaligus.
                    </p>
                </div>
                @if($unpaidBills->count() > 0)
                    <button type="button" @click="modalUpload = true" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-xs transition cursor-pointer self-start sm:self-auto">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        <span>Bayar &amp; Upload Bukti Transfer</span>
                    </button>
                @endif
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-600 uppercase font-semibold text-[10px] tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="px-4 py-3 text-center w-12">No</th>
                            <th class="px-4 py-3">Pos &amp; Rincian Tagihan</th>
                            <th class="px-4 py-3 text-center">Periode / Bulan</th>
                            <th class="px-4 py-3 text-center">Jatuh Tempo</th>
                            <th class="px-4 py-3 text-right">Biaya Asli</th>
                            <th class="px-4 py-3 text-right">Potongan</th>
                            <th class="px-4 py-3 text-right">Harus Bayar</th>
                            <th class="px-4 py-3 text-right">Sudah Dibayar</th>
                            <th class="px-4 py-3 text-right">Sisa Tunggakan</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-center">Aksi Bayar</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($unpaidBills as $idx => $b)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="px-4 py-3.5 text-center text-slate-400 font-medium">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3.5">
                                    <span class="inline-block px-2 py-0.5 rounded bg-blue-50 text-blue-700 font-extrabold text-[10px] border border-blue-200 mb-0.5">
                                        {{ $b->pos_biaya }}
                                    </span>
                                    <div class="font-bold text-slate-900">{{ $b->judul_tagihan }}</div>
                                    @if($b->catatan)
                                        <div class="text-[10px] text-slate-400 italic">{{ $b->catatan }}</div>
                                    @endif
                                </td>
                                <td class="px-4 py-3.5 text-center font-medium text-slate-800">
                                    {{ $b->bulan ? $b->bulan . ' ' . $b->tahun : ($b->tahun ?: '—') }}
                                </td>
                                <td class="px-4 py-3.5 text-center text-[11px] text-slate-500">
                                    {{ $b->tanggal_jatuh_tempo ? date('d M Y', strtotime($b->tanggal_jatuh_tempo)) : '—' }}
                                </td>
                                <td class="px-4 py-3.5 text-right font-mono text-slate-500">
                                    Rp {{ number_format($b->nominal_asli, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3.5 text-right font-mono text-purple-700">
                                    @if($b->nominal_potongan > 0)
                                        - Rp {{ number_format($b->nominal_potongan, 0, ',', '.') }}
                                    @else
                                        <span class="text-slate-300">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3.5 text-right font-mono font-bold text-slate-900">
                                    Rp {{ number_format($b->nominal_tagihan, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3.5 text-right font-mono font-bold text-emerald-700">
                                    Rp {{ number_format($b->nominal_bayar, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3.5 text-right font-mono font-black text-rose-600 bg-rose-50/40">
                                    Rp {{ number_format($b->sisa_tagihan, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3.5 text-center">
                                    @if($b->penangguhan_wisuda)
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-100 text-indigo-800 border border-indigo-200 inline-flex items-center gap-1">
                                            <span>🎓</span>
                                            <span>Ditangguhkan (Wisuda)</span>
                                        </span>
                                        @if($b->catatan_penangguhan)
                                            <div class="text-[10px] text-indigo-600 italic mt-0.5">{{ $b->catatan_penangguhan }}</div>
                                        @endif
                                    @elseif($b->status === 'Cicilan')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                            Cicilan
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800 border border-rose-200">
                                            Belum Bayar
                                        </span>
                                    @endif

                                    @if($b->status_dispensasi === 'diminta_surat')
                                        <div class="mt-1">
                                            <span class="px-2 py-0.5 rounded bg-amber-50 text-amber-800 border border-amber-200 text-[10px] font-semibold block">
                                                📩 Diminta: {{ $b->jenis_surat_diminta }}
                                            </span>
                                        </div>
                                    @elseif($b->status_dispensasi === 'surat_diunggah')
                                        <div class="mt-1">
                                            <span class="px-2 py-0.5 rounded bg-blue-50 text-blue-700 border border-blue-200 text-[10px] font-semibold block">
                                                ⏳ Berkas Sedang Ditinjau
                                            </span>
                                        </div>
                                    @elseif($b->status_dispensasi === 'ditolak')
                                        <div class="mt-1">
                                            <span class="px-2 py-0.5 rounded bg-rose-50 text-rose-700 border border-rose-200 text-[10px] font-semibold block">
                                                ❌ Berkas Ditolak
                                            </span>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-4 py-3.5 text-center space-y-1">
                                    @if($b->status_dispensasi === 'diminta_surat' || $b->status_dispensasi === 'ditolak')
                                        <button type="button" 
                                                @click="openUploadSurat({{ $b->id }}, '{{ addslashes($b->judul_tagihan) }}', '{{ addslashes($b->jenis_surat_diminta) }}', '{{ addslashes($b->instruksi_surat) }}')"
                                                class="w-full inline-flex items-center justify-center gap-1 px-2.5 py-1 rounded-lg bg-amber-500 hover:bg-amber-600 text-white font-bold text-[11px] shadow-2xs transition cursor-pointer">
                                            <span>📄 Upload {{ $b->jenis_surat_diminta }}</span>
                                        </button>
                                    @endif

                                    <button type="button" @click="openUploadForBill({{ $b->id }}, {{ $b->sisa_tagihan }})" class="w-full inline-flex items-center justify-center gap-1 px-2.5 py-1 rounded-lg {{ $b->penangguhan_wisuda ? 'bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-300' : 'bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-200' }} font-bold text-[11px] transition shadow-2xs cursor-pointer">
                                        <span>{{ $b->penangguhan_wisuda ? 'Bayar Lebih Awal' : 'Bayar Pos Ini' }}</span>
                                        <span>&rarr;</span>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center py-8 text-slate-400 text-xs">
                                    <span class="text-3xl block mb-1">🎉</span>
                                    Tidak ada tagihan yang belum lunas. Semua kewajiban pembayaran telah diselesaikan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if($unpaidBills->count() > 0)
                        <tfoot class="bg-slate-50 font-bold text-xs">
                            <tr>
                                <td colspan="8" class="px-4 py-3 text-right uppercase tracking-wider text-slate-600">
                                    Total Sisa Tunggakan Aktif Belum Dibayar:
                                </td>
                                <td class="px-4 py-3 text-right font-mono font-black text-rose-700 text-sm">
                                    Rp {{ number_format($totalTunggakan, 0, ',', '.') }}
                                    @if(($totalDitangguhkan ?? 0) > 0)
                                        <div class="text-[10px] text-indigo-700 font-semibold mt-0.5">
                                            (+ Rp {{ number_format($totalDitangguhkan, 0, ',', '.') }} ditangguhkan ke wisuda)
                                        </div>
                                    @endif
                                </td>
                                <td colspan="2"></td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>

        <!-- TABEL 2: RIWAYAT PEMBAYARAN RESMI & STATUS VERIFIKASI TRANSAKSI -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                <div>
                    <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 inline-block"></span>
                        <span>Riwayat Pembayaran &amp; Bukti Setoran</span>
                        <span class="px-2 py-0.5 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold font-mono">
                            {{ $payments->count() }} Transaksi
                        </span>
                    </h3>
                    <p class="text-xs text-slate-400 mt-0.5">Daftar transaksi pembayaran santri beserta status verifikasi oleh bendahara dan cetak kwitansi sah</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-600 uppercase font-semibold text-[10px] tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="px-4 py-3 text-center w-12">No</th>
                            <th class="px-4 py-3">No. Kwitansi</th>
                            <th class="px-4 py-3 text-center">Tanggal</th>
                            <th class="px-4 py-3">Rincian Pos yang Dibayar</th>
                            <th class="px-4 py-3 text-center">Metode</th>
                            <th class="px-4 py-3 text-right">Jumlah Dibayar</th>
                            <th class="px-4 py-3 text-center">Status Verifikasi</th>
                            <th class="px-4 py-3 text-center">Kwitansi / Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($payments as $idx => $p)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="px-4 py-3.5 text-center text-slate-400 font-medium">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3.5 font-mono font-bold text-slate-900">
                                    {{ $p->no_transaksi }}
                                </td>
                                <td class="px-4 py-3.5 text-center text-slate-600 font-medium">
                                    {{ optional($p->tanggal_bayar)->format('d M Y') ?: $p->created_at->format('d M Y') }}
                                </td>
                                <td class="px-4 py-3.5">
                                    @if($p->items && $p->items->count() > 0)
                                        <div class="flex flex-wrap gap-1.5">
                                            @foreach($p->items as $it)
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-slate-100 border border-slate-200 text-[11px] font-medium text-slate-800">
                                                    <strong class="text-emerald-700">{{ $it->pos_biaya }}:</strong>
                                                    <span>Rp {{ number_format($it->nominal, 0, ',', '.') }}</span>
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="font-semibold text-slate-800">{{ $p->jenis_pembayaran }}</span>
                                        @if($p->catatan)
                                            <span class="text-[10px] text-slate-400 block italic">{{ $p->catatan }}</span>
                                        @endif
                                    @endif
                                </td>
                                <td class="px-4 py-3.5 text-center">
                                    <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 font-semibold text-[10px]">
                                        {{ $p->metode_pembayaran }}
                                    </span>
                                </td>
                                <td class="px-4 py-3.5 text-right font-mono font-black text-emerald-800 text-sm">
                                    {{ $p->formatted_nominal }}
                                </td>
                                <td class="px-4 py-3.5 text-center">
                                    @if($p->status === 'Menunggu Konfirmasi')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-900 border border-amber-300">
                                            ⏱ Menunggu Verifikasi
                                        </span>
                                        @if($p->bukti_bayar)
                                            <button type="button" @click="previewImgUrl = '{{ asset($p->bukti_bayar) }}'; previewTitle = 'Bukti Transfer {{ $p->no_transaksi }}'; modalPreview = true" class="block text-[10px] text-blue-600 font-bold hover:underline mt-0.5 mx-auto">
                                                🔍 Lihat Bukti
                                            </button>
                                        @endif
                                    @elseif($p->status === 'Ditolak')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800 border border-rose-300">
                                            ✕ Ditolak
                                        </span>
                                        @if($p->catatan)
                                            <span class="text-[10px] text-rose-600 block mt-0.5 max-w-xs mx-auto">{{ $p->catatan }}</span>
                                        @endif
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                            ✓ Sah &amp; Lunas
                                        </span>
                                        <span class="text-[10px] text-slate-400 block mt-0.5">{{ $p->penerima_nama ?: 'Bendahara' }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3.5 text-center">
                                    @if($p->status === 'Lunas')
                                        <a href="{{ route('santri.pembayaran.kwitansi', $p->id) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs shadow-xs transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                            <span>Cetak Kwitansi</span>
                                        </a>
                                    @else
                                        <span class="text-[11px] text-slate-400 italic">Kwitansi terbit setelah lunas</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-8 text-slate-400 text-xs">
                                    <span class="text-3xl block mb-1">📄</span>
                                    Belum ada catatan riwayat pembayaran untuk akun santri ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Section: Informasi Rekening Resmi & Prosedur Pembayaran -->
        <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm space-y-4 text-xs">
            <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                <span>🏦 Rekening Resmi Pembayaran Pesantren Hidayatullah Tuksongo</span>
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-4 rounded-2xl border border-emerald-200 bg-emerald-50/50 space-y-2">
                    <span class="px-2 py-0.5 rounded bg-emerald-600 text-white font-bold text-[10px] uppercase">Bank Syariah Indonesia (BSI)</span>
                    <div class="text-lg font-mono font-bold text-slate-900">714 556 7890</div>
                    <p class="text-slate-600 text-[11px]">a.n. <strong>Pondok Pesantren Hidayatullah Tuksongo</strong></p>
                    <p class="text-slate-400 text-[10px]">Kode Bank: 451</p>
                </div>

                <div class="p-4 rounded-2xl border border-blue-200 bg-blue-50/50 space-y-2">
                    <span class="px-2 py-0.5 rounded bg-blue-600 text-white font-bold text-[10px] uppercase">Bank BRI</span>
                    <div class="text-lg font-mono font-bold text-slate-900">0153 0100 2345 531</div>
                    <p class="text-slate-600 text-[11px]">a.n. <strong>Ponpes Hidayatullah Temanggung</strong></p>
                    <p class="text-slate-400 text-[10px]">Kode Bank: 002</p>
                </div>
            </div>
            <div class="p-3.5 bg-amber-50 border border-amber-200 rounded-2xl text-amber-900 text-[11px] leading-relaxed flex items-start gap-2.5">
                <span class="text-base">💡</span>
                <div>
                    <strong>Petunjuk Pembayaran:</strong> Setelah transfer berhasil, Anda dapat mengunggah foto struk/slip transfer langsung melalui tombol <strong>"Unggah Bukti Transfer"</strong> di halaman ini, atau konfirmasi via WhatsApp resmi Bendahara Pesantren di <a href="https://wa.me/6285290429617" target="_blank" class="font-bold text-emerald-800 underline">0852-9042-9617</a> agar dapat langsung diverifikasi dan dibukukan ke dalam sistem.
                </div>
            </div>
        </div>

    </main>

    <!-- ========================================================================= -->
    <!-- MODAL FORM UNGGAH BUKTI PEMBAYARAN MANDIRI OLEH SANTRI                     -->
    <!-- ========================================================================= -->
    <div x-show="modalUpload" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/70 backdrop-blur-xs">
        <div @click.away="modalUpload = false" class="w-full max-w-lg rounded-3xl bg-white p-6 shadow-2xl border border-slate-100 space-y-4 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Unggah Bukti Transfer Mandiri</h3>
                    <p class="text-xs text-slate-500">Pilih pos tagihan yang dibayar &amp; kirimkan foto struk transfer</p>
                </div>
                <button type="button" @click="modalUpload = false" class="text-slate-400 hover:text-slate-600 text-xl font-bold">&times;</button>
            </div>

            <form action="{{ route('santri.pembayaran.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs">
                @csrf

                <!-- Step 1: Pilih Pos Tagihan yang Dibayar -->
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <label class="font-bold text-slate-800">1. Pilih Pos Tagihan yang Dibayar:</label>
                        <div class="flex items-center gap-2">
                            <button type="button" @click="toggleSelectAll(true)" class="text-[11px] text-emerald-700 font-semibold hover:underline">Pilih Semua</button>
                            <span class="text-slate-300">•</span>
                            <button type="button" @click="toggleSelectAll(false)" class="text-[11px] text-slate-500 font-semibold hover:underline">Hapus Semua</button>
                        </div>
                    </div>

                    <div class="space-y-1.5 max-h-48 overflow-y-auto border border-slate-200 rounded-2xl p-2.5 bg-slate-50/50">
                        @forelse($unpaidBills as $b)
                            <label class="flex items-center justify-between p-2 rounded-xl bg-white border border-slate-200/80 hover:border-emerald-300 transition cursor-pointer">
                                <div class="flex items-center gap-2.5">
                                    <input type="checkbox" name="bills[]" value="{{ $b->id }}" :checked="isBillChecked({{ $b->id }})" @change="toggleBill({{ $b->id }})" class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500">
                                    <div>
                                        <span class="font-bold text-slate-900 block text-xs">{{ $b->judul_tagihan }}</span>
                                        <span class="text-[10px] text-slate-400 font-mono">Pos: {{ $b->pos_biaya }} &bull; {{ $b->bulan ? $b->bulan.' '.$b->tahun : '' }}</span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="font-mono font-bold text-rose-600 text-xs">Rp {{ number_format($b->sisa_tagihan, 0, ',', '.') }}</span>
                                    <span class="text-[10px] text-slate-400 block">Sisa</span>
                                </div>
                            </label>
                        @empty
                            <p class="text-slate-400 text-center py-2 text-xs">Tidak ada tagihan yang belum lunas.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Step 2: Nominal Transfer -->
                <div>
                    <label class="block font-bold text-slate-800 mb-1">2. Nominal yang Ditransfer (Rp):</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 font-bold text-xs pointer-events-none">Rp</span>
                        <input type="number" name="nominal" x-model="uploadNominal" min="1000" required class="w-full pl-9 pr-3 py-2.5 rounded-xl border border-slate-300 font-mono font-bold text-sm text-slate-900 focus:border-emerald-500">
                    </div>
                    <p class="text-[10px] text-slate-400 mt-1">Otomatis terisi sesuai total pos yang dicentang di atas, namun dapat disesuaikan jika nominal transfer berbeda.</p>
                </div>

                <!-- Step 3: Pilih Rekening Bank Tujuan -->
                <div>
                    <label class="block font-bold text-slate-800 mb-1">3. Rekening Bank Tujuan Pesantren:</label>
                    <select name="bank_tujuan" x-model="selectedBank" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-medium text-slate-800 focus:border-emerald-500">
                        <option value="Bank Syariah Indonesia (BSI) - 714 556 7890 (a.n. Pondok Pesantren Hidayatullah Tuksongo)">
                            Bank Syariah Indonesia (BSI) - 714 556 7890 (a.n. PP Hidayatullah Tuksongo)
                        </option>
                        <option value="Bank BRI - 0153 0100 2345 531 (a.n. Ponpes Hidayatullah Temanggung)">
                            Bank BRI - 0153 0100 2345 531 (a.n. Ponpes Hidayatullah Temanggung)
                        </option>
                    </select>
                </div>

                <!-- Step 4: Unggah Foto Struk Transfer -->
                <div class="p-3 bg-slate-50 rounded-2xl border border-slate-200 space-y-2">
                    <label class="block font-bold text-slate-800">4. Unggah Foto Bukti Transfer / Struk ATM / M-Banking: <span class="text-rose-500">*</span></label>
                    <input type="file" name="bukti_file" accept="image/*" required @change="handleFileChange" class="w-full text-xs text-slate-600 file:mr-2 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-600 file:text-white hover:file:bg-emerald-700 cursor-pointer">
                    <p class="text-[10px] text-slate-400">Pastikan gambar jelas memperlihatkan tanggal, bank, nominal, dan nama rekening (JPG/PNG maks 5MB).</p>

                    <!-- Preview Thumbnail -->
                    <template x-if="filePreview">
                        <div class="mt-2 p-2 bg-white rounded-xl border border-slate-200">
                            <span class="text-[10px] font-bold text-slate-500 block mb-1">Pratinjau Foto Bukti:</span>
                            <img :src="filePreview" alt="Pratinjau Bukti" class="max-h-40 rounded-lg mx-auto object-contain">
                        </div>
                    </template>
                </div>

                <!-- Step 5: Catatan / Keterangan Pengirim -->
                <div>
                    <label class="block font-bold text-slate-800 mb-1">5. Catatan / Nama Rekening Pengirim (Opsional):</label>
                    <input type="text" name="catatan" placeholder="Misal: Transfer via BSI Mobile a.n. Ayah (Ahmad Fauzi)" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs text-slate-800 focus:border-emerald-500">
                </div>

                <!-- Modal Actions -->
                <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                    <button type="button" @click="modalUpload = false" class="px-4 py-2 rounded-xl border border-slate-300 text-slate-700 font-semibold hover:bg-slate-50 transition">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold shadow-md transition flex items-center gap-1.5 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Kirim Bukti Pembayaran</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL PREVIEW FOTO BUKTI PEMBAYARAN -->
    <div x-show="modalPreview" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/70 backdrop-blur-xs">
        <div @click.away="modalPreview = false" class="w-full max-w-lg rounded-3xl bg-white p-6 shadow-2xl border border-slate-100">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                <h4 class="text-sm font-bold text-slate-900" x-text="previewTitle"></h4>
                <button @click="modalPreview = false" class="text-slate-400 hover:text-slate-600 text-xl font-bold">&times;</button>
            </div>
            <div class="max-h-[70vh] overflow-y-auto flex items-center justify-center bg-slate-100 rounded-xl p-2">
                <img :src="previewImgUrl" alt="Bukti Transfer" class="max-h-[65vh] object-contain rounded-lg">
            </div>
        </div>
    </div>

    <!-- MODAL UPLOAD SURAT DISPENSASI / KERINGANAN SANTRI -->
    <div x-show="modalUploadSurat" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/70 backdrop-blur-xs">
        <div @click.away="modalUploadSurat = false" class="w-full max-w-lg rounded-3xl bg-white p-6 shadow-2xl border border-slate-100 text-left">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                <div>
                    <h4 class="text-base font-bold text-slate-900 flex items-center gap-2">
                        <span>📄 Unggah Dokumen Keringanan / Pemutihan</span>
                    </h4>
                    <p class="text-xs text-slate-500 mt-0.5">Sesuai permintaan admin untuk verifikasi pemutihan / penyesuaian tagihan.</p>
                </div>
                <button @click="modalUploadSurat = false" class="text-slate-400 hover:text-slate-600 text-xl font-bold">&times;</button>
            </div>

            <!-- Target Bill Information -->
            <div class="mb-4 p-3.5 bg-amber-50 rounded-2xl border border-amber-200 text-xs text-amber-900 space-y-1.5">
                <div class="font-bold text-slate-800 text-sm" x-text="suratTargetBill.judul"></div>
                <div class="flex items-center gap-1.5">
                    <span class="font-semibold text-slate-600">Dokumen Diminta:</span> 
                    <span class="font-extrabold text-amber-900 px-2 py-0.5 bg-amber-200/80 rounded-md font-mono" x-text="suratTargetBill.jenis_surat"></span>
                </div>
                <div x-show="suratTargetBill.instruksi">
                    <span class="font-semibold text-slate-600">Catatan Admin:</span> 
                    <span class="italic text-slate-700 bg-white/70 px-2 py-0.5 rounded border border-amber-100 inline-block mt-0.5" x-text="suratTargetBill.instruksi"></span>
                </div>
            </div>

            <form :action="'/santri/pembayaran/tagihan/' + suratTargetBill.id + '/upload-surat'" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs">
                @csrf
                <div class="p-3 bg-slate-50 rounded-2xl border border-slate-200 space-y-2">
                    <label class="block font-bold text-slate-800">
                        Pilih File Scan / Foto Dokumen (PDF atau Foto JPG/PNG, maks 5MB): <span class="text-rose-500">*</span>
                    </label>
                    <input type="file" name="file_surat" accept=".pdf,image/*" required class="w-full text-xs text-slate-600 file:mr-2 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-amber-600 file:text-white hover:file:bg-amber-700 cursor-pointer">
                    <p class="text-[10px] text-slate-400">Pastikan tulisan surat, stempel, dan tanda tangan resmi terbaca dengan jelas.</p>
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                    <button type="button" @click="modalUploadSurat = false" class="px-4 py-2 rounded-xl border border-slate-300 text-slate-700 font-semibold hover:bg-slate-50 transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-bold shadow-md transition flex items-center gap-1.5 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        <span>Kirim &amp; Unggah Dokumen</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="py-4 text-center text-xs text-slate-400 border-t border-slate-200 bg-white">
        &copy; {{ date('Y') }} Pondok Pesantren Hidayatullah Tuksongo Pringsurat.
    </footer>

</body>
</html>
