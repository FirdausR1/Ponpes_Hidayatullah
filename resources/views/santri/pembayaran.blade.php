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
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            200: '#bbf7d0',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
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
          tabTagihan: 'unpaid',
          modalUpload: false,
          modalPreview: false,
          modalUploadSurat: false,
          modalAjukanKeringanan: false,
          ajukanBillId: '{{ $unpaidBills->first()->id ?? '' }}',
          suratTargetBill: {
              id: null,
              judul: '',
              jenis_surat: '',
              instruksi: ''
          },
          previewImgUrl: '',
          previewTitle: '',
          selectedBank: 'Bank BRI - {{ \App\Models\Setting::get('rek_admin_bri_no', '010201022009537') }} (a.n. {{ \App\Models\Setting::get('rek_admin_bri_an', 'DIKY FACHRI HUSEIN') }})',
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
    <header class="bg-white border-b border-slate-200 sticky top-0 z-40 px-3 sm:px-6 lg:px-8 py-2.5 sm:py-3">
        <div class="max-w-6xl mx-auto flex items-center justify-between gap-2 sm:gap-4">
            <div class="flex items-center gap-2.5 sm:gap-3 shrink-0">
                <a href="{{ route('santri.dashboard') }}" class="shrink-0">
                    <img src="/logo.png" alt="Logo" class="w-8 h-8 sm:w-9 sm:h-9 object-contain">
                </a>
                <div>
                    <h2 class="text-xs sm:text-sm font-bold text-slate-900 leading-tight truncate max-w-[150px] sm:max-w-none">Pesantren Hidayatullah</h2>
                    <span class="text-[10px] sm:text-[11px] text-emerald-700 font-medium">Portal Santri</span>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="flex items-center gap-1.5 sm:gap-2 overflow-x-auto py-1">
                <a href="{{ route('santri.dashboard') }}" class="px-2.5 sm:px-3 py-1.5 rounded-lg bg-white border border-slate-200 text-slate-600 hover:bg-emerald-50 hover:text-emerald-800 hover:border-emerald-300 text-xs font-semibold whitespace-nowrap transition">
                    Dasbor
                </a>
                <a href="{{ route('santri.profil') }}" class="px-2.5 sm:px-3 py-1.5 rounded-lg bg-white border border-slate-200 text-slate-600 hover:bg-emerald-50 hover:text-emerald-800 hover:border-emerald-300 text-xs font-semibold whitespace-nowrap transition">
                    Biodata &amp; Berkas
                </a>
                <a href="{{ route('santri.pembayaran') }}" class="px-2.5 sm:px-3 py-1.5 rounded-lg bg-emerald-700 text-white text-xs font-semibold whitespace-nowrap shadow-xs">
                    Tagihan
                </a>
                <form action="{{ route('santri.logout') }}" method="POST" class="shrink-0">
                    @csrf
                    <button type="submit" class="px-2.5 sm:px-3 py-1.5 rounded-lg bg-white border border-slate-200 hover:bg-rose-50 hover:text-rose-600 hover:border-rose-200 text-xs font-semibold text-slate-600 whitespace-nowrap transition cursor-pointer">
                        Keluar
                    </button>
                </form>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 max-w-6xl w-full mx-auto p-4 sm:p-6 lg:p-8 space-y-5 sm:space-y-6">

        @if(session('success'))
            <div class="p-3.5 sm:p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl flex items-center gap-2.5 text-xs sm:text-sm font-medium">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="p-3.5 sm:p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl flex items-center gap-2.5 text-xs sm:text-sm font-medium">
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if(isset($errors) && $errors->any())
            <div class="p-3.5 sm:p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl text-xs space-y-1">
                @foreach($errors->all() as $err)
                    <p>• {{ $err }}</p>
                @endforeach
            </div>
        @endif

        <!-- Nav Breadcrumb -->
        <div class="flex items-center justify-between pb-1 border-b border-slate-200">
            <div class="flex items-center gap-2 text-xs">
                <a href="{{ route('santri.dashboard') }}" class="text-emerald-700 font-semibold hover:underline">&larr; Kembali ke Dasbor</a>
                <span class="text-slate-300">/</span>
                <span class="text-slate-500 font-medium">Tagihan &amp; Riwayat Pembayaran</span>
            </div>
            <span class="text-xs text-slate-500 hidden sm:inline">
                Santri: <strong class="text-slate-800">{{ $student->nama_lengkap }}</strong> (NIS: {{ $student->nis }})
            </span>
        </div>

        <!-- Banner Header -->
        <div class="rounded-2xl bg-gradient-to-r from-emerald-900 via-emerald-800 to-emerald-700 p-5 sm:p-7 text-white shadow-xs relative overflow-hidden">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-5">
                <div class="space-y-2">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/20 text-xs font-semibold text-emerald-200">
                        <span>Status Pembayaran Santri</span>
                        <span>•</span>
                        <span>Kelas {{ $student->kelas }} ({{ $student->jenjang }})</span>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-bold tracking-tight">Tagihan &amp; Riwayat Pembayaran</h1>
                    <p class="text-xs sm:text-sm text-emerald-100/90 max-w-xl leading-relaxed">
                        Pantau rincian biaya pesantren (Syahriyah, Uang Makan, SOT, Tabungan, Ziarah, dll.), permohonan keringanan, serta unggah bukti transfer mandiri.
                    </p>
                    <div class="pt-1">
                        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-black/20 border border-white/15 text-xs text-white">
                            <span>Saldo Tabungan Santri:</span>
                            <strong class="font-mono text-emerald-300">Rp {{ number_format($student->saldo_tabungan, 0, ',', '.') }}</strong>
                        </span>
                    </div>
                </div>

                <div class="shrink-0 flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5 w-full md:w-auto">
                    @if($unpaidBills->count() > 0)
                        <button type="button" @click="modalUpload = true" class="text-center px-4 py-2.5 rounded-xl bg-white text-emerald-800 hover:bg-emerald-50 font-bold text-xs shadow-xs transition cursor-pointer">
                            Unggah Bukti Transfer
                        </button>
                        <button type="button" @click="modalAjukanKeringanan = true" class="text-center px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-semibold text-xs border border-white/25 transition cursor-pointer">
                            + Ajukan Keringanan
                        </button>
                    @else
                        <div class="px-4 py-2 rounded-xl bg-white/10 border border-white/20 text-center text-xs font-semibold text-emerald-100">
                            Bebas Tunggakan
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Banner Permintaan Berkas Dokumen Keringanan / Pemutihan dari Admin -->
        @if(isset($pendingSuratBills) && $pendingSuratBills->count() > 0)
            <div class="space-y-3">
                @foreach($pendingSuratBills as $psb)
                    @if($psb->status_dispensasi === 'diminta_surat')
                        <div class="rounded-2xl bg-amber-50 border border-amber-200 p-4 sm:p-5 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-3">
                            <div class="space-y-1">
                                <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800 text-[10px] font-bold uppercase">
                                    <span>Permintaan Berkas Surat Keringanan</span>
                                </div>
                                <h3 class="text-xs sm:text-sm font-bold text-amber-950">
                                    Mohon unggah dokumen: <strong class="underline decoration-amber-400">{{ $psb->jenis_surat_diminta }}</strong>
                                </h3>
                                <p class="text-xs text-amber-900">
                                    Untuk penyesuaian tagihan <strong>{{ $psb->judul_tagihan }}</strong> (Sisa: <span class="font-mono font-bold text-rose-700">Rp {{ number_format($psb->sisa_tagihan, 0, ',', '.') }}</span>).
                                    @if($psb->instruksi_surat)
                                        <br><span class="italic text-slate-600 bg-white px-2 py-0.5 rounded border border-amber-200 mt-1 inline-block">Catatan Admin: "{{ $psb->instruksi_surat }}"</span>
                                    @endif
                                </p>
                            </div>
                            <div class="shrink-0">
                                <button type="button" 
                                        @click="openUploadSurat({{ $psb->id }}, '{{ addslashes($psb->judul_tagihan) }}', '{{ addslashes($psb->jenis_surat_diminta) }}', '{{ addslashes($psb->instruksi_surat) }}')"
                                        class="w-full sm:w-auto px-4 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-semibold text-xs shadow-xs transition cursor-pointer">
                                    Unggah Berkas Sekarang &rarr;
                                </button>
                            </div>
                        </div>
                    @elseif($psb->status_dispensasi === 'surat_diunggah')
                        <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-3">
                            <div>
                                <h4 class="text-xs sm:text-sm font-bold text-slate-800">Berkas {{ $psb->jenis_surat_diminta }} Telah Terkirim</h4>
                                <p class="text-xs text-slate-600">
                                    Berkas untuk <strong>{{ $psb->judul_tagihan }}</strong> sedang dalam proses peninjauan oleh bendahara pondok.
                                </p>
                            </div>
                            @if($psb->file_surat_dispensasi)
                                <a href="{{ asset($psb->file_surat_dispensasi) }}" target="_blank" class="px-3 py-1.5 rounded-lg bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 font-semibold text-xs transition text-center shrink-0">
                                    Lihat File Terunggah &rarr;
                                </a>
                            @endif
                        </div>
                    @elseif($psb->status_dispensasi === 'ditolak')
                        <div class="rounded-2xl bg-rose-50 border border-rose-200 p-4 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-3">
                            <div class="space-y-1">
                                <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-rose-100 text-rose-800 text-[10px] font-bold">
                                    <span>Berkas Perlu Diperbaiki</span>
                                </div>
                                <h4 class="text-xs sm:text-sm font-bold text-rose-950">
                                    Berkas {{ $psb->jenis_surat_diminta }} untuk {{ $psb->judul_tagihan }} Ditolak
                                </h4>
                                @if($psb->alasan_penolakan_surat)
                                    <p class="text-xs text-rose-800 bg-white p-2 rounded-lg border border-rose-200">
                                        <strong>Alasan Penolakan:</strong> {{ $psb->alasan_penolakan_surat }}
                                    </p>
                                @endif
                            </div>
                            <div class="shrink-0">
                                <button type="button" 
                                        @click="openUploadSurat({{ $psb->id }}, '{{ addslashes($psb->judul_tagihan) }}', '{{ addslashes($psb->jenis_surat_diminta) }}', '{{ addslashes($psb->instruksi_surat) }}')"
                                        class="w-full sm:w-auto px-4 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-semibold text-xs shadow-xs transition cursor-pointer">
                                    Unggah Ulang Berkas &rarr;
                                </button>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif

        <!-- Financial Overview Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 {{ ($totalDitangguhkan ?? 0) > 0 ? 'lg:grid-cols-4' : 'lg:grid-cols-3' }} gap-3 sm:gap-4">
            <div class="bg-white rounded-2xl border border-slate-200 p-4 sm:p-5 shadow-xs">
                <span class="text-[10px] sm:text-[11px] font-semibold text-slate-400 uppercase tracking-wider block">Total Semua Tagihan</span>
                <h3 class="text-lg sm:text-xl font-bold text-slate-900 font-mono mt-1">
                    Rp {{ number_format($totalTagihan, 0, ',', '.') }}
                </h3>
                <p class="text-[11px] text-slate-400 mt-1">{{ $bills->count() }} pos tagihan tercatat</p>
            </div>

            <div class="bg-white rounded-2xl border border-emerald-200 bg-emerald-50/20 p-4 sm:p-5 shadow-xs">
                <span class="text-[10px] sm:text-[11px] font-semibold text-emerald-700 uppercase tracking-wider block">Total Sudah Terbayar</span>
                <h3 class="text-lg sm:text-xl font-bold text-emerald-700 font-mono mt-1">
                    Rp {{ number_format($totalTerbayar, 0, ',', '.') }}
                </h3>
                <p class="text-[11px] text-emerald-700/80 mt-1">Diterima oleh bendahara pondok</p>
            </div>

            <div class="bg-white rounded-2xl border {{ $totalTunggakan > 0 ? 'border-rose-300 bg-rose-50/20' : 'border-slate-200' }} p-4 sm:p-5 shadow-xs">
                <span class="text-[10px] sm:text-[11px] font-semibold {{ $totalTunggakan > 0 ? 'text-rose-600' : 'text-slate-400' }} uppercase tracking-wider block">Sisa Tunggakan Aktif</span>
                <h3 class="text-lg sm:text-xl font-bold {{ $totalTunggakan > 0 ? 'text-rose-600' : 'text-slate-900' }} font-mono mt-1">
                    Rp {{ number_format($totalTunggakan, 0, ',', '.') }}
                </h3>
                <p class="text-[11px] {{ $totalTunggakan > 0 ? 'text-rose-600/80 font-medium' : 'text-slate-400' }} mt-1">
                    {{ ($unpaidActiveBills ?? $unpaidBills)->count() }} pos belum lunas
                </p>
            </div>

            @if(($totalDitangguhkan ?? 0) > 0)
                <div class="bg-white rounded-2xl border border-slate-200 p-4 sm:p-5 shadow-xs">
                    <span class="text-[10px] sm:text-[11px] font-semibold text-slate-600 uppercase tracking-wider block">Ditangguhkan ke Wisuda</span>
                    <h3 class="text-lg sm:text-xl font-bold text-slate-800 font-mono mt-1">
                        Rp {{ number_format($totalDitangguhkan, 0, ',', '.') }}
                    </h3>
                    <p class="text-[11px] text-slate-500 mt-1">
                        {{ ($wisudaBills ?? collect())->count() }} pos ditunda sampai wisuda
                    </p>
                </div>
            @endif
        </div>

        <!-- Info Keringanan / Beasiswa Santri (Jika Ada) -->
        @if($discounts->count() > 0)
            <div class="bg-white rounded-2xl border border-slate-200 p-4 sm:p-5 shadow-xs space-y-3">
                <div>
                    <h3 class="text-xs sm:text-sm font-bold text-slate-900">Keringanan &amp; Subsidi Santri Aktif</h3>
                    <p class="text-[11px] text-slate-400">Daftar pemotongan biaya resmi yang telah disetujui pihak pesantren.</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($discounts as $disc)
                        <div class="bg-slate-50 rounded-xl p-3 border border-slate-200 text-xs flex items-start justify-between gap-3">
                            <div>
                                <span class="font-bold text-slate-900 block">{{ $disc->jenis_potongan }}</span>
                                @if($disc->no_surat_miskin)
                                    <span class="text-[11px] font-mono text-slate-500 block mt-0.5">No. SKTM: {{ $disc->no_surat_miskin }}</span>
                                @endif
                                <span class="text-[10px] text-slate-400 mt-0.5 block">Pos Sasaran: <strong>{{ $disc->pos_biaya }}</strong></span>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-800 border border-emerald-200 font-bold text-xs font-mono">
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

        <!-- Tab Switch Bar: Tagihan Belum Lunas vs Tagihan Sudah Lunas -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="inline-flex items-center gap-1.5 p-1 bg-slate-200/80 rounded-xl self-start">
                <button type="button" 
                        @click="tabTagihan = 'unpaid'" 
                        :class="tabTagihan === 'unpaid' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900'"
                        class="px-3.5 py-1.5 rounded-lg font-semibold text-xs transition flex items-center gap-1.5 cursor-pointer">
                    <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                    <span>Tagihan Belum Lunas</span>
                    <span class="px-1.5 py-0.2 rounded text-[10px] font-bold" :class="tabTagihan === 'unpaid' ? 'bg-rose-100 text-rose-800' : 'bg-slate-300 text-slate-700'">
                        {{ $unpaidBills->count() }}
                    </span>
                </button>
                <button type="button" 
                        @click="tabTagihan = 'paid'" 
                        :class="tabTagihan === 'paid' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900'"
                        class="px-3.5 py-1.5 rounded-lg font-semibold text-xs transition flex items-center gap-1.5 cursor-pointer">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span>Riwayat Lunas</span>
                    <span class="px-1.5 py-0.2 rounded text-[10px] font-bold" :class="tabTagihan === 'paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-300 text-slate-700'">
                        {{ $paidBills->count() }}
                    </span>
                </button>
            </div>

            @if($unpaidBills->count() > 0)
                <button type="button" @click="modalAjukanKeringanan = true" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white border border-slate-300 hover:bg-emerald-50 hover:text-emerald-800 hover:border-emerald-300 text-slate-700 font-semibold text-xs transition cursor-pointer self-start sm:self-auto">
                    <span>Ajukan Keringanan / SKTM</span>
                    <span class="text-emerald-700">&rarr;</span>
                </button>
            @endif
        </div>

        <!-- TABEL 1A: TAGIHAN AKTIF / BELUM LUNAS -->
        <div x-show="tabTagihan === 'unpaid'" class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                <div>
                    <h3 class="text-xs sm:text-sm font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-rose-500 inline-block"></span>
                        <span>Daftar Tagihan Belum Lunas</span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-rose-50 border border-rose-200 text-rose-700 font-mono">
                            {{ $unpaidBills->count() }} Tagihan
                        </span>
                    </h3>
                    <p class="text-xs text-slate-400 mt-0.5">
                        Anda dapat membayar pos tertentu atau melunasi beberapa pos sekaligus.
                    </p>
                </div>
                @if($unpaidBills->count() > 0)
                    <button type="button" @click="modalUpload = true" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-semibold text-xs shadow-xs transition cursor-pointer self-start sm:self-auto">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        <span>Bayar &amp; Upload Bukti</span>
                    </button>
                @endif
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs min-w-[700px]">
                    <thead class="bg-slate-50 text-slate-600 uppercase font-semibold text-[10px] tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="px-3.5 py-3 text-center w-10">No</th>
                            <th class="px-3.5 py-3">Pos &amp; Rincian Tagihan</th>
                            <th class="px-3.5 py-3 text-center">Bulan</th>
                            <th class="px-3.5 py-3 text-right">Biaya Asli</th>
                            <th class="px-3.5 py-3 text-right">Potongan</th>
                            <th class="px-3.5 py-3 text-right">Harus Bayar</th>
                            <th class="px-3.5 py-3 text-right">Sudah Dibayar</th>
                            <th class="px-3.5 py-3 text-right">Sisa</th>
                            <th class="px-3.5 py-3 text-center">Status</th>
                            <th class="px-3.5 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($unpaidBills as $idx => $b)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="px-3.5 py-3 text-center text-slate-400 font-medium">{{ $loop->iteration }}</td>
                                <td class="px-3.5 py-3">
                                    <span class="inline-block px-1.5 py-0.2 rounded bg-slate-100 text-slate-700 font-semibold text-[10px] border border-slate-200 mb-0.5">
                                        {{ $b->pos_biaya }}
                                    </span>
                                    <div class="font-bold text-slate-900">{{ $b->judul_tagihan }}</div>
                                    @if($b->catatan)
                                        <div class="text-[10px] text-slate-400 italic">{{ $b->catatan }}</div>
                                    @endif
                                </td>
                                <td class="px-3.5 py-3 text-center font-medium text-slate-800">
                                    {{ $b->bulan ? $b->bulan . ' ' . $b->tahun : ($b->tahun ?: '—') }}
                                </td>
                                <td class="px-3.5 py-3 text-right font-mono text-slate-500">
                                    Rp {{ number_format($b->nominal_asli, 0, ',', '.') }}
                                </td>
                                <td class="px-3.5 py-3 text-right font-mono text-emerald-700">
                                    @if($b->nominal_potongan > 0)
                                        - Rp {{ number_format($b->nominal_potongan, 0, ',', '.') }}
                                    @else
                                        <span class="text-slate-300">—</span>
                                    @endif
                                </td>
                                <td class="px-3.5 py-3 text-right font-mono font-bold text-slate-900">
                                    Rp {{ number_format($b->nominal_tagihan, 0, ',', '.') }}
                                </td>
                                <td class="px-3.5 py-3 text-right font-mono font-bold text-emerald-700">
                                    Rp {{ number_format($b->nominal_bayar, 0, ',', '.') }}
                                </td>
                                <td class="px-3.5 py-3 text-right font-mono font-bold text-rose-600 bg-rose-50/40">
                                    Rp {{ number_format($b->sisa_tagihan, 0, ',', '.') }}
                                </td>
                                <td class="px-3.5 py-3 text-center">
                                    @if($b->penangguhan_wisuda)
                                        <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-700 border border-slate-200 block">
                                            Ditangguhkan (Wisuda)
                                        </span>
                                    @elseif($b->status === 'Cicilan')
                                        <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-amber-50 text-amber-800 border border-amber-200 block">
                                            Cicilan
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-rose-50 text-rose-800 border border-rose-200 block">
                                            Belum Bayar
                                        </span>
                                    @endif

                                    @if($b->status_dispensasi === 'diminta_surat')
                                        <span class="px-1.5 py-0.2 rounded bg-amber-50 text-amber-800 border border-amber-200 text-[9px] font-semibold block mt-1">
                                            Diminta: {{ $b->jenis_surat_diminta }}
                                        </span>
                                    @elseif($b->status_dispensasi === 'surat_diunggah')
                                        <span class="px-1.5 py-0.2 rounded bg-blue-50 text-blue-700 border border-blue-200 text-[9px] font-semibold block mt-1">
                                            Sedang Ditinjau
                                        </span>
                                    @elseif($b->status_dispensasi === 'ditolak')
                                        <span class="px-1.5 py-0.2 rounded bg-rose-50 text-rose-700 border border-rose-200 text-[9px] font-semibold block mt-1">
                                            Berkas Ditolak
                                        </span>
                                    @endif
                                </td>
                                <td class="px-3.5 py-3 text-center space-y-1">
                                    @if($b->status_dispensasi === 'diminta_surat' || $b->status_dispensasi === 'ditolak')
                                        <button type="button" 
                                                @click="openUploadSurat({{ $b->id }}, '{{ addslashes($b->judul_tagihan) }}', '{{ addslashes($b->jenis_surat_diminta) }}', '{{ addslashes($b->instruksi_surat) }}')"
                                                class="w-full inline-flex items-center justify-center px-2 py-1 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white font-semibold text-[10px] shadow-xs transition cursor-pointer">
                                            <span>Upload {{ $b->jenis_surat_diminta }}</span>
                                        </button>
                                    @endif

                                    <button type="button" @click="openUploadForBill({{ $b->id }}, {{ $b->sisa_tagihan }})" class="w-full inline-flex items-center justify-center px-2 py-1 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-200 font-semibold text-[10px] transition cursor-pointer">
                                        <span>Bayar Pos Ini &rarr;</span>
                                    </button>

                                    @if(!$b->status_dispensasi || $b->status_dispensasi === 'none')
                                        <button type="button" @click="ajukanBillId = '{{ $b->id }}'; modalAjukanKeringanan = true" class="w-full inline-flex items-center justify-center px-2 py-0.5 rounded-lg bg-white hover:bg-slate-50 text-slate-600 border border-slate-200 font-medium text-[10px] transition cursor-pointer">
                                            <span>Ajukan Keringanan</span>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-8 text-slate-400 text-xs">
                                    Tidak ada tagihan yang belum lunas. Semua kewajiban telah diselesaikan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if($unpaidBills->count() > 0)
                        <tfoot class="bg-slate-50 font-bold text-xs">
                            <tr>
                                <td colspan="7" class="px-3.5 py-3 text-right uppercase tracking-wider text-slate-600">
                                    Total Sisa Tunggakan Aktif:
                                </td>
                                <td class="px-3.5 py-3 text-right font-mono font-bold text-rose-700 text-xs sm:text-sm">
                                    Rp {{ number_format($totalTunggakan, 0, ',', '.') }}
                                </td>
                                <td colspan="2"></td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>

        <!-- TABEL 1B: RIWAYAT TAGIHAN SUDAH LUNAS -->
        <div x-show="tabTagihan === 'paid'" class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                <div>
                    <h3 class="text-xs sm:text-sm font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 inline-block"></span>
                        <span>Riwayat Tagihan Selesai &amp; Lunas</span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-50 border border-emerald-200 text-emerald-800 font-mono">
                            {{ $paidBills->count() }} Tagihan Lunas
                        </span>
                    </h3>
                    <p class="text-xs text-slate-400 mt-0.5">
                        Daftar seluruh pos yang telah dinyatakan lunas atau disetujui keringanan.
                    </p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs min-w-[650px]">
                    <thead class="bg-slate-50 text-slate-600 uppercase font-semibold text-[10px] tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="px-3.5 py-3 text-center w-10">No</th>
                            <th class="px-3.5 py-3">Pos &amp; Rincian Tagihan</th>
                            <th class="px-3.5 py-3 text-center">Bulan</th>
                            <th class="px-3.5 py-3 text-right">Biaya Asli</th>
                            <th class="px-3.5 py-3 text-right">Potongan</th>
                            <th class="px-3.5 py-3 text-right">Total Terbayar</th>
                            <th class="px-3.5 py-3 text-center">Status</th>
                            <th class="px-3.5 py-3">Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($paidBills as $idx => $pb)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="px-3.5 py-3 text-center text-slate-400 font-medium">{{ $loop->iteration }}</td>
                                <td class="px-3.5 py-3">
                                    <span class="inline-block px-1.5 py-0.2 rounded bg-slate-100 text-slate-700 font-semibold text-[10px] border border-slate-200 mb-0.5">
                                        {{ $pb->pos_biaya }}
                                    </span>
                                    <div class="font-bold text-slate-900">{{ $pb->judul_tagihan }}</div>
                                </td>
                                <td class="px-3.5 py-3 text-center font-medium text-slate-800">
                                    {{ $pb->bulan ? $pb->bulan . ' ' . $pb->tahun : ($pb->tahun ?: '—') }}
                                </td>
                                <td class="px-3.5 py-3 text-right font-mono text-slate-500">
                                    Rp {{ number_format($pb->nominal_asli, 0, ',', '.') }}
                                </td>
                                <td class="px-3.5 py-3 text-right font-mono text-emerald-700">
                                    @if($pb->nominal_potongan > 0)
                                        - Rp {{ number_format($pb->nominal_potongan, 0, ',', '.') }}
                                    @else
                                        <span class="text-slate-300">—</span>
                                    @endif
                                </td>
                                <td class="px-3.5 py-3 text-right font-mono font-bold text-emerald-700">
                                    Rp {{ number_format($pb->nominal_bayar, 0, ',', '.') }}
                                </td>
                                <td class="px-3.5 py-3 text-center">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                        Lunas
                                    </span>
                                    @if($pb->status_dispensasi === 'disetujui')
                                        <span class="block mt-0.5 text-[9px] font-semibold text-emerald-700">
                                            Keringanan Disetujui
                                        </span>
                                    @endif
                                </td>
                                <td class="px-3.5 py-3 text-slate-600 text-xs">
                                    @if($pb->catatan_pembebasan)
                                        <span class="text-emerald-800 font-medium block">{{ $pb->catatan_pembebasan }}</span>
                                    @elseif($pb->alasan_potongan)
                                        <span class="text-slate-700 block">{{ $pb->alasan_potongan }}</span>
                                    @elseif($pb->catatan)
                                        <span class="text-slate-500 italic block">{{ $pb->catatan }}</span>
                                    @else
                                        <span class="text-slate-400">—</span>
                                    @endif

                                    @if($pb->file_surat_dispensasi)
                                        <a href="{{ asset($pb->file_surat_dispensasi) }}" target="_blank" class="text-[10px] text-emerald-700 hover:underline font-semibold mt-1 inline-block">
                                            Lihat Surat Keringanan &rarr;
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-8 text-slate-400 text-xs">
                                    Belum ada catatan tagihan yang selesai atau lunas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- TABEL 2: RIWAYAT PEMBAYARAN RESMI & STATUS VERIFIKASI TRANSAKSI -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                <div>
                    <h3 class="text-xs sm:text-sm font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 inline-block"></span>
                        <span>Riwayat Pembayaran &amp; Bukti Setoran</span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-50 border border-emerald-200 text-emerald-800 font-mono">
                            {{ $payments->count() }} Transaksi
                        </span>
                    </h3>
                    <p class="text-xs text-slate-400 mt-0.5">Daftar transaksi pembayaran santri beserta status verifikasi kasir dan cetak kwitansi sah.</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs min-w-[700px]">
                    <thead class="bg-slate-50 text-slate-600 uppercase font-semibold text-[10px] tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="px-3.5 py-3 text-center w-10">No</th>
                            <th class="px-3.5 py-3">No. Kwitansi</th>
                            <th class="px-3.5 py-3 text-center">Tanggal</th>
                            <th class="px-3.5 py-3">Pos yang Dibayar</th>
                            <th class="px-3.5 py-3 text-center">Metode</th>
                            <th class="px-3.5 py-3 text-right">Jumlah</th>
                            <th class="px-3.5 py-3 text-center">Status Verifikasi</th>
                            <th class="px-3.5 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($payments as $idx => $p)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="px-3.5 py-3 text-center text-slate-400 font-medium">{{ $loop->iteration }}</td>
                                <td class="px-3.5 py-3 font-mono font-bold text-slate-900">
                                    {{ $p->no_transaksi }}
                                </td>
                                <td class="px-3.5 py-3 text-center text-slate-600 font-medium">
                                    {{ optional($p->tanggal_bayar)->format('d M Y') ?: $p->created_at->format('d M Y') }}
                                </td>
                                <td class="px-3.5 py-3">
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
                                <td class="px-3.5 py-3 text-center">
                                    <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 font-semibold text-[10px]">
                                        {{ $p->metode_pembayaran }}
                                    </span>
                                </td>
                                <td class="px-3.5 py-3 text-right font-mono font-bold text-emerald-800 text-xs sm:text-sm">
                                    {{ $p->formatted_nominal }}
                                </td>
                                <td class="px-3.5 py-3 text-center">
                                    @if($p->status === 'Menunggu Konfirmasi')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-amber-50 text-amber-900 border border-amber-200">
                                            Menunggu Verifikasi
                                        </span>
                                        @if($p->bukti_bayar)
                                            <button type="button" @click="previewImgUrl = '{{ asset($p->bukti_bayar) }}'; previewTitle = 'Bukti Transfer {{ $p->no_transaksi }}'; modalPreview = true" class="block text-[10px] text-emerald-700 font-semibold hover:underline mt-0.5 mx-auto">
                                                Lihat Bukti &rarr;
                                            </button>
                                        @endif
                                    @elseif($p->status === 'Ditolak')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-rose-50 text-rose-800 border border-rose-200">
                                            Ditolak
                                        </span>
                                        @if($p->catatan)
                                            <span class="text-[10px] text-rose-600 block mt-0.5 max-w-xs mx-auto">{{ $p->catatan }}</span>
                                        @endif
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                            Sah &amp; Lunas
                                        </span>
                                        <span class="text-[10px] text-slate-400 block mt-0.5">{{ $p->penerima_nama ?: 'Bendahara' }}</span>
                                    @endif
                                </td>
                                <td class="px-3.5 py-3 text-center">
                                    @if($p->status === 'Lunas')
                                        <a href="{{ route('santri.pembayaran.kwitansi', $p->id) }}" target="_blank" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white font-semibold text-xs shadow-xs transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                            <span>Cetak Kwitansi</span>
                                        </a>
                                    @else
                                        <span class="text-[11px] text-slate-400 italic">Menunggu verifikasi</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-8 text-slate-400 text-xs">
                                    Belum ada catatan riwayat transaksi pembayaran.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Section: Informasi Rekening Resmi & Prosedur Pembayaran -->
        <div class="bg-white rounded-2xl border border-slate-200 p-4 sm:p-6 shadow-xs space-y-4 text-xs">
            <h3 class="font-bold text-slate-900 text-xs sm:text-sm uppercase tracking-wider border-b border-slate-100 pb-2">
                Rekening Resmi Pembayaran Pondok Pesantren Hidayatullah Tuksongo
            </h3>

            <!-- Rekening Administrasi (SPP, Syahriyah, Kegiatan) -->
            <div>
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block mb-2">Rekening Administrasi (SPP / Syahriyah / SOT / PSB):</span>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">
                    <div class="p-3.5 sm:p-4 rounded-xl border border-slate-200 bg-slate-50/60 space-y-1.5">
                        <div class="flex items-center justify-between">
                            <span class="px-2 py-0.5 rounded bg-emerald-700 text-white font-bold text-[10px] uppercase">Bank BRI</span>
                            <span class="text-[10px] text-slate-500 font-mono font-bold">Kode: 002</span>
                        </div>
                        <div class="text-lg sm:text-xl font-mono font-bold text-slate-900 tracking-wide">{{ \App\Models\Setting::get('rek_admin_bri_no', '010201022009537') }}</div>
                        <p class="text-slate-600 text-xs">a.n. <strong>{{ \App\Models\Setting::get('rek_admin_bri_an', 'DIKY FACHRI HUSEIN') }}</strong></p>
                    </div>

                    <div class="p-3.5 sm:p-4 rounded-xl border border-slate-200 bg-slate-50/60 space-y-1.5">
                        <div class="flex items-center justify-between">
                            <span class="px-2 py-0.5 rounded bg-emerald-700 text-white font-bold text-[10px] uppercase">Bank BCA</span>
                            <span class="text-[10px] text-slate-500 font-mono font-bold">Kode: 014</span>
                        </div>
                        <div class="text-lg sm:text-xl font-mono font-bold text-slate-900 tracking-wide">{{ \App\Models\Setting::get('rek_admin_bca_no', '1221220167') }}</div>
                        <p class="text-slate-600 text-xs">a.n. <strong>{{ \App\Models\Setting::get('rek_admin_bca_an', 'DIKY FACHRI HUSEIN') }}</strong></p>
                    </div>
                </div>
            </div>

            <!-- Rekening Khusus Titipan Uang Saku -->
            <div class="pt-3 border-t border-slate-100">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block mb-2">Rekening Khusus Titipan Uang Saku Santri:</span>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div class="p-3 rounded-xl border border-slate-200 bg-slate-50/60 space-y-1">
                        <span class="text-[10px] font-bold text-slate-700 uppercase block">Uang Saku Putra (1)</span>
                        <div class="font-mono font-bold text-slate-900 text-xs sm:text-sm">BRI {{ \App\Models\Setting::get('rek_saku_putra_1_no', '366701032851533') }}</div>
                        <p class="text-slate-500 text-[11px]">a.n. <strong>{{ \App\Models\Setting::get('rek_saku_putra_1_an', 'ILHAM AKBAR ARIFIN') }}</strong></p>
                        <p class="text-emerald-700 text-[11px] font-medium pt-0.5">WA: {{ \App\Models\Setting::get('rek_saku_putra_1_phone', '0821-3342-5328') }}</p>
                    </div>
                    <div class="p-3 rounded-xl border border-slate-200 bg-slate-50/60 space-y-1">
                        <span class="text-[10px] font-bold text-slate-700 uppercase block">Uang Saku Putra (2)</span>
                        <div class="font-mono font-bold text-slate-900 text-xs sm:text-sm">BRI {{ \App\Models\Setting::get('rek_saku_putra_2_no', '025101062991507') }}</div>
                        <p class="text-slate-500 text-[11px]">a.n. <strong>{{ \App\Models\Setting::get('rek_saku_putra_2_an', 'ATA NUR RIFQI') }}</strong></p>
                        <p class="text-emerald-700 text-[11px] font-medium pt-0.5">WA: {{ \App\Models\Setting::get('rek_saku_putra_2_phone', '0858-6539-5879') }}</p>
                    </div>
                    <div class="p-3 rounded-xl border border-slate-200 bg-slate-50/60 space-y-1">
                        <span class="text-[10px] font-bold text-slate-700 uppercase block">Uang Saku Putri</span>
                        <div class="font-mono font-bold text-slate-900 text-xs sm:text-sm">BRI {{ \App\Models\Setting::get('rek_saku_putri_no', '366701040613539') }}</div>
                        <p class="text-slate-500 text-[11px]">a.n. <strong>{{ \App\Models\Setting::get('rek_saku_putri_an', 'LAELATUL MUNAWAROH') }}</strong></p>
                        <p class="text-emerald-700 text-[11px] font-medium pt-0.5">WA: {{ \App\Models\Setting::get('rek_saku_putri_phone', '0851-8484-2869') }}</p>
                    </div>
                </div>
            </div>

            <!-- Petunjuk Konfirmasi Transfer -->
            <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-700 text-xs leading-relaxed">
                <strong>Petunjuk Konfirmasi Transfer:</strong> Setelah transfer berhasil, silakan unggah foto struk pada tombol <strong>"Unggah Bukti Transfer"</strong> di atas, atau konfirmasi melalui WhatsApp Bendahara di <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', \App\Models\Setting::get('rek_admin_konfirmasi_phone', '085290429617')) }}" target="_blank" class="font-bold text-emerald-700 underline">{{ \App\Models\Setting::get('rek_admin_konfirmasi_phone', '085290429617') }} ({{ \App\Models\Setting::get('rek_admin_konfirmasi_nama', 'Ustdh. Harsih Nur A') }})</a> dengan menyertakan nama santri, kelas, dan nominal.
            </div>
        </div>

    </main>

    <!-- ========================================================================= -->
    <!-- MODAL FORM UNGGAH BUKTI PEMBAYARAN MANDIRI OLEH SANTRI                     -->
    <!-- ========================================================================= -->
    <div x-show="modalUpload" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
        <div @click.away="modalUpload = false" class="w-full max-w-lg rounded-2xl bg-white p-5 sm:p-6 shadow-xl border border-slate-200 space-y-4 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-sm sm:text-base font-bold text-slate-900">Unggah Bukti Transfer Mandiri</h3>
                    <p class="text-xs text-slate-500">Pilih pos tagihan yang dibayar dan kirimkan foto struk transfer.</p>
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

                    <div class="space-y-1.5 max-h-48 overflow-y-auto border border-slate-200 rounded-xl p-2.5 bg-slate-50/50">
                        @forelse($unpaidBills as $b)
                            <label class="flex items-center justify-between p-2 rounded-lg bg-white border border-slate-200/80 hover:border-emerald-300 transition cursor-pointer">
                                <div class="flex items-center gap-2.5">
                                    <input type="checkbox" name="bills[]" value="{{ $b->id }}" :checked="isBillChecked({{ $b->id }})" @change="toggleBill({{ $b->id }})" class="w-4 h-4 rounded text-emerald-700 focus:ring-emerald-600">
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
                        <input type="number" name="nominal" x-model="uploadNominal" min="1000" required class="w-full pl-9 pr-3 py-2 rounded-xl border border-slate-300 font-mono font-bold text-xs sm:text-sm text-slate-900 focus:border-emerald-600 outline-none">
                    </div>
                    <p class="text-[10px] text-slate-400 mt-1">Otomatis terisi sesuai total pos yang dicentang di atas.</p>
                </div>

                <!-- Step 3: Pilih Rekening Bank Tujuan -->
                <div>
                    <label class="block font-bold text-slate-800 mb-1">3. Rekening Bank Tujuan Pesantren:</label>
                    <select name="bank_tujuan" x-model="selectedBank" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-medium text-slate-800 focus:border-emerald-600 bg-white outline-none">
                        <option value="Bank BRI - {{ \App\Models\Setting::get('rek_admin_bri_no', '010201022009537') }} (a.n. {{ \App\Models\Setting::get('rek_admin_bri_an', 'DIKY FACHRI HUSEIN') }})">
                            Bank BRI - {{ \App\Models\Setting::get('rek_admin_bri_no', '010201022009537') }} (a.n. {{ \App\Models\Setting::get('rek_admin_bri_an', 'DIKY FACHRI HUSEIN') }})
                        </option>
                        <option value="Bank BCA - {{ \App\Models\Setting::get('rek_admin_bca_no', '1221220167') }} (a.n. {{ \App\Models\Setting::get('rek_admin_bca_an', 'DIKY FACHRI HUSEIN') }})">
                            Bank BCA - {{ \App\Models\Setting::get('rek_admin_bca_no', '1221220167') }} (a.n. {{ \App\Models\Setting::get('rek_admin_bca_an', 'DIKY FACHRI HUSEIN') }})
                        </option>
                    </select>
                </div>

                <!-- Step 4: Unggah Foto Struk Transfer -->
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 space-y-2">
                    <label class="block font-bold text-slate-800">4. Unggah Foto Bukti Transfer / Struk: <span class="text-rose-500">*</span></label>
                    <input type="file" name="bukti_file" accept="image/*" required @change="handleFileChange" class="w-full text-xs text-slate-600 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-700 file:text-white hover:file:bg-emerald-800 cursor-pointer">
                    <p class="text-[10px] text-slate-400">Pastikan gambar jelas memperlihatkan tanggal, nominal, dan nama rekening (JPG/PNG maks 5MB).</p>

                    <!-- Preview Thumbnail -->
                    <template x-if="filePreview">
                        <div class="mt-2 p-2 bg-white rounded-lg border border-slate-200">
                            <span class="text-[10px] font-bold text-slate-500 block mb-1">Pratinjau Foto Bukti:</span>
                            <img :src="filePreview" alt="Pratinjau Bukti" class="max-h-40 rounded-md mx-auto object-contain">
                        </div>
                    </template>
                </div>

                <!-- Step 5: Catatan / Keterangan Pengirim -->
                <div>
                    <label class="block font-bold text-slate-800 mb-1">5. Catatan / Nama Rekening Pengirim (Opsional):</label>
                    <input type="text" name="catatan" placeholder="Misal: Transfer via BSI a.n. Ayah (Ahmad)" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs text-slate-800 focus:border-emerald-600 outline-none">
                </div>

                <!-- Modal Actions -->
                <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                    <button type="button" @click="modalUpload = false" class="px-4 py-2 rounded-xl border border-slate-300 text-slate-700 font-semibold hover:bg-slate-50 transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold shadow-xs transition cursor-pointer">
                        Kirim Bukti Pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL PREVIEW FOTO BUKTI PEMBAYARAN -->
    <div x-show="modalPreview" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
        <div @click.away="modalPreview = false" class="w-full max-w-lg rounded-2xl bg-white p-5 sm:p-6 shadow-xl border border-slate-200">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-3">
                <h4 class="text-xs sm:text-sm font-bold text-slate-900" x-text="previewTitle"></h4>
                <button @click="modalPreview = false" class="text-slate-400 hover:text-slate-600 text-xl font-bold">&times;</button>
            </div>
            <div class="max-h-[70vh] overflow-y-auto flex items-center justify-center bg-slate-100 rounded-xl p-2">
                <img :src="previewImgUrl" alt="Bukti Transfer" class="max-h-[65vh] object-contain rounded-lg">
            </div>
        </div>
    </div>

    <!-- MODAL UPLOAD SURAT DISPENSASI / KERINGANAN SANTRI -->
    <div x-show="modalUploadSurat" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
        <div @click.away="modalUploadSurat = false" class="w-full max-w-lg rounded-2xl bg-white p-5 sm:p-6 shadow-xl border border-slate-200 text-left">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-3">
                <div>
                    <h4 class="text-sm sm:text-base font-bold text-slate-900">Unggah Dokumen Keringanan</h4>
                    <p class="text-xs text-slate-500 mt-0.5">Sesuai permintaan admin untuk verifikasi pemutihan/keringanan tagihan.</p>
                </div>
                <button @click="modalUploadSurat = false" class="text-slate-400 hover:text-slate-600 text-xl font-bold">&times;</button>
            </div>

            <!-- Target Bill Information -->
            <div class="mb-4 p-3 bg-slate-50 rounded-xl border border-slate-200 text-xs text-slate-800 space-y-1">
                <div class="font-bold text-slate-900 text-xs sm:text-sm" x-text="suratTargetBill.judul"></div>
                <div class="flex items-center gap-1.5">
                    <span class="font-semibold text-slate-600">Dokumen Diminta:</span> 
                    <span class="font-bold text-emerald-800 px-2 py-0.5 bg-emerald-50 rounded border border-emerald-200 font-mono text-[11px]" x-text="suratTargetBill.jenis_surat"></span>
                </div>
                <div x-show="suratTargetBill.instruksi">
                    <span class="font-semibold text-slate-600">Catatan Admin:</span> 
                    <span class="italic text-slate-700 bg-white px-2 py-0.5 rounded border border-slate-200 inline-block mt-0.5" x-text="suratTargetBill.instruksi"></span>
                </div>
            </div>

            <form :action="'/santri/pembayaran/tagihan/' + suratTargetBill.id + '/upload-surat'" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs">
                @csrf
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 space-y-2">
                    <label class="block font-bold text-slate-800">
                        Pilih File Scan / Foto Dokumen (PDF, JPG, PNG maks 5MB): <span class="text-rose-500">*</span>
                    </label>
                    <input type="file" name="file_surat" accept=".pdf,image/*" required class="w-full text-xs text-slate-600 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-700 file:text-white hover:file:bg-emerald-800 cursor-pointer">
                    <p class="text-[10px] text-slate-400">Pastikan tulisan surat, stempel, dan tanda tangan resmi terbaca jelas.</p>
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                    <button type="button" @click="modalUploadSurat = false" class="px-4 py-2 rounded-xl border border-slate-300 text-slate-700 font-semibold hover:bg-slate-50 transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold shadow-xs transition cursor-pointer">
                        Kirim Dokumen
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL PENGAJUAN KERINGANAN / DISPENSASI MANDIRI OLEH SANTRI -->
    <div x-show="modalAjukanKeringanan" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
        <div @click.away="modalAjukanKeringanan = false" class="w-full max-w-lg rounded-2xl bg-white p-5 sm:p-6 shadow-xl border border-slate-200 text-left space-y-4 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h4 class="text-sm sm:text-base font-bold text-slate-900">Pengajuan Keringanan Biaya / SKTM</h4>
                    <p class="text-xs text-slate-500 mt-0.5">Santri atau wali santri dapat mengajukan permohonan keringanan secara mandiri.</p>
                </div>
                <button @click="modalAjukanKeringanan = false" class="text-slate-400 hover:text-slate-600 text-xl font-bold">&times;</button>
            </div>

            <form action="{{ route('santri.pembayaran.ajukanKeringanan') }}" method="POST" enctype="multipart/form-data" class="space-y-3.5 text-xs">
                @csrf

                <!-- 1. Pilih Pos Tagihan Target -->
                <div>
                    <label class="block font-bold text-slate-800 mb-1">1. Pilih Pos Tagihan: <span class="text-rose-500">*</span></label>
                    <select name="student_bill_id" x-model="ajukanBillId" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-medium text-slate-800 focus:border-emerald-600 bg-white outline-none">
                        <option value="" disabled>-- Pilih Tagihan Belum Lunas --</option>
                        @foreach($unpaidBills as $ub)
                            <option value="{{ $ub->id }}">
                                {{ $ub->judul_tagihan }} ({{ $ub->pos_biaya }}) — Sisa: Rp {{ number_format($ub->sisa_tagihan, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- 2. Jenis Dokumen / Surat -->
                <div>
                    <label class="block font-bold text-slate-800 mb-1">2. Jenis Dokumen Pendukung: <span class="text-rose-500">*</span></label>
                    <select name="jenis_surat" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-medium text-slate-800 focus:border-emerald-600 bg-white outline-none">
                        <option value="Surat Keterangan Tidak Mampu (SKTM)">Surat Keterangan Tidak Mampu (SKTM Kelurahan/Desa)</option>
                        <option value="Surat Keterangan Yatim / Piatu">Surat Keterangan Yatim / Piatu</option>
                        <option value="Kartu Indonesia Pintar (KIP) / PKH">Kartu Indonesia Pintar (KIP) / PKH / KKS</option>
                        <option value="Surat Permohonan Keringanan Wali Santri">Surat Permohonan Tertulis dari Wali Santri</option>
                        <option value="Dokumen Keringanan Lainnya">Dokumen / Bukti Keringanan Lainnya</option>
                    </select>
                </div>

                <!-- 3. Nomor Surat / Berkas (Opsional) -->
                <div>
                    <label class="block font-bold text-slate-800 mb-1">3. Nomor Dokumen (Opsional):</label>
                    <input type="text" name="no_surat" placeholder="Contoh: 400/12/SKTM/2026 atau No. KIP" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs text-slate-800 focus:border-emerald-600 outline-none">
                </div>

                <!-- 4. Alasan / Keterangan -->
                <div>
                    <label class="block font-bold text-slate-800 mb-1">4. Alasan Permohonan Keringanan: <span class="text-rose-500">*</span></label>
                    <textarea name="alasan" rows="2" required placeholder="Tuliskan secara ringkas alasan permohonan keringanan..." class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs text-slate-800 focus:border-emerald-600 outline-none"></textarea>
                </div>

                <!-- 5. Unggah Berkas Dokumen -->
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 space-y-2">
                    <label class="block font-bold text-slate-800">
                        5. Unggah Foto / Scan Dokumen (PDF, JPG, PNG maks 5MB): <span class="text-rose-500">*</span>
                    </label>
                    <input type="file" name="file_surat" accept=".pdf,image/*" required class="w-full text-xs text-slate-600 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-700 file:text-white hover:file:bg-emerald-800 cursor-pointer">
                    <p class="text-[10px] text-slate-400">Pastikan stempel desa atau tanda tangan resmi terbaca jelas.</p>
                </div>

                <!-- Modal Actions -->
                <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                    <button type="button" @click="modalAjukanKeringanan = false" class="px-4 py-2 rounded-xl border border-slate-300 text-slate-700 font-semibold hover:bg-slate-50 transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold shadow-xs transition cursor-pointer">
                        Kirim Pengajuan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="mt-8 py-5 text-center text-xs text-slate-500 border-t border-slate-200 bg-white">
        <div class="max-w-6xl mx-auto px-4">
            <p>&copy; {{ date('Y') }} Pondok Pesantren Hidayatullah Tuksongo Pringsurat Temanggung.</p>
        </div>
    </footer>

</body>
</html>
