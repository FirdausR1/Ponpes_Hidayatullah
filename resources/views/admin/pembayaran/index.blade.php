@extends('admin.layout')

@section('title', 'Kasir Pembayaran Santri (POS) — Pondok Pesantren Hidayatullah')

@section('content')
<div class="space-y-6" x-data="{ 
    activeTab: '{{ $tab }}',
    paymentType: 'santri', // 'santri' or 'psb'
    selectedPerson: null,
    selectedStudentId: '{{ $santriId ?? '' }}',
    selectedPsbId: '',
    searchQuery: '',
    typeFilter: 'all', // 'all', 'santri', 'psb'
    selectedClassFilter: '',

    // Raw collections from controller
    allStudents: {{ json_encode($activeStudents) }},
    allPsbCandidates: {{ json_encode($psbCandidates) }},

    // Bills & Pricing State
    loadingBills: false,
    studentBills: [],
    studentDiscounts: [],
    tunggakanByMonth: [],
    totalTunggakanCount: 0,
    showTunggakanModal: false,
    standardTariffs: { MAKAN: 300000, SYAHRIYAH: 85000, SOT: 55000, TAB: 25000 },
    currentMonthName: 'September',
    currentYear: '{{ date('Y') }}',

    // Advance Payment (Bulan Depan / Di Muka)
    advanceMonth: 'Oktober',
    advanceYear: '{{ date('Y') }}',
    advanceItems: [],

    // Pos Kustom 40 Pos Bebas (Bisa langsung ketik tanpa ribet)
    customItems: [{ pos_biaya: '', nominal: '', custom_name: '' }],
    availablePos: {{ json_encode(array_keys($posBiayaList)) }},
    tabunganWithdrawal: '',

    // Calon PSB Specific State
    psbData: null,
    psbShowBreakdown: false,
    psbStandardNominal: 3220000,
    psbTerbayar: 0,
    psbSisa: 3220000,
    psbNominalInput: 3220000,
    psbChecked: true,

    // Checkout Parameters
    metodeBayar: 'Tunai',
    tanggalBayar: '{{ date('Y-m-d') }}',
    keteranganPeriode: '{{ date('M Y') }}',
    catatan: '',
    uangDiterima: '',

    // Modals
    modalPreviewFoto: false,
    previewImgUrl: '',
    previewTitle: '',
    modalPsbBayar: false,
    psbModalData: {
        id: '',
        nama: '',
        no_reg: '',
        jenjang: '',
        nominal: 3225000,
        metode: 'Tunai',
        tanggal: '{{ date('Y-m-d') }}',
        catatan: '',
        bukti_ada: false
    },

    openPsbModal(id, nama, no_reg, jenjang, nominal, metode, tanggal, catatan, bukti_ada) {
        this.psbModalData = {
            id: id,
            nama: nama,
            no_reg: no_reg,
            jenjang: jenjang,
            nominal: nominal || 3225000,
            metode: metode || 'Tunai',
            tanggal: tanggal || '{{ date('Y-m-d') }}',
            catatan: catatan || '',
            bukti_ada: bukti_ada || false
        };
        this.modalPsbBayar = true;
    },

    init() {
        if (this.selectedStudentId) {
            let st = this.allStudents.find(s => s.id == this.selectedStudentId);
            if (st) {
                this.selectPerson({
                    type: 'santri',
                    id: st.id,
                    nama: st.nama_lengkap,
                    nomor: st.nis,
                    kelas: st.kelas,
                    jenjang: st.jenjang,
                    jenjang_short: st.jenjang_short || (st.jenjang && st.jenjang.includes('MA') ? 'MA' : 'MTs'),
                    hunian: st.hunian || (st.jenjang && st.jenjang.toLowerCase().includes('laju') ? 'Laju' : 'Mukim'),
                    kategori_label: st.kategori_label || (st.jenjang || 'Santri'),
                    tarif_bulanan: st.tarif_bulanan || null,
                    asrama: st.kamar_asrama,
                    foto: st.foto,
                    saldo_tabungan: st.saldo_tabungan || 0,
                    raw: st
                });
            }
        }
    },

    get allPeople() {
        let list = [];
        (this.allStudents || []).forEach(s => {
            list.push({
                type: 'santri',
                id: s.id,
                nama: s.nama_lengkap,
                nomor: s.nis,
                kelas: s.kelas,
                jenjang: s.jenjang,
                jenjang_short: s.jenjang_short || (s.jenjang && s.jenjang.includes('MA') ? 'MA' : 'MTs'),
                hunian: s.hunian || (s.jenjang && s.jenjang.toLowerCase().includes('laju') ? 'Laju' : 'Mukim'),
                kategori_label: s.kategori_label || (s.jenjang || 'Santri'),
                tarif_bulanan: s.tarif_bulanan || null,
                asrama: s.kamar_asrama,
                foto: s.foto,
                saldo_tabungan: s.saldo_tabungan || 0,
                raw: s
            });
        });
        (this.allPsbCandidates || []).forEach(p => {
            list.push({
                type: 'psb',
                id: p.id,
                nama: p.nama_lengkap,
                nomor: p.no_registrasi,
                kelas: 'Calon Santri (' + (p.jenjang || 'PSB') + ')',
                jenjang: p.jenjang,
                asrama: null,
                foto: p.pas_foto,
                status_bayar: p.status_pembayaran,
                nominal_bayar: p.nominal_pembayaran,
                raw: p
            });
        });
        return list;
    },

    get filteredPeople() {
        return this.allPeople.filter(p => {
            if (this.typeFilter !== 'all' && p.type !== this.typeFilter) return false;
            if (this.selectedClassFilter !== '' && p.kelas !== this.selectedClassFilter) return false;
            if (this.searchQuery.trim() !== '') {
                let q = this.searchQuery.toLowerCase();
                let matches = (p.nama && p.nama.toLowerCase().includes(q)) ||
                              (p.nomor && p.nomor.toLowerCase().includes(q)) ||
                              (p.kelas && p.kelas.toLowerCase().includes(q));
                if (!matches) return false;
            }
            return true;
        });
    },

    selectPerson(person) {
        this.selectedPerson = person;
        if (person.type === 'santri') {
            this.paymentType = 'santri';
            this.selectedStudentId = person.id;
            this.selectedPsbId = '';
            this.loadStudentData(person.id);
        } else {
            this.paymentType = 'psb';
            this.selectedPsbId = person.id;
            this.selectedStudentId = '';
            this.loadPsbData(person.id);
        }
    },

    resetPerson() {
        this.selectedPerson = null;
        this.selectedStudentId = '';
        this.selectedPsbId = '';
        this.studentBills = [];
        this.studentDiscounts = [];
        this.tunggakanByMonth = [];
        this.totalTunggakanCount = 0;
        this.showTunggakanModal = false;
        this.advanceItems = [];
        this.customItems = [{ pos_biaya: '', nominal: '', custom_name: '' }];
        this.tabunganWithdrawal = '';
        this.uangDiterima = '';
    },

    loadStudentData(studentId) {
        if (!studentId) return;
        this.loadingBills = true;
        fetch('{{ url('/admin/pembayaran/ajax-tagihan') }}/' + studentId)
            .then(res => res.json())
            .then(data => {
                if (this.selectedPerson && data.saldo_tabungan !== undefined) {
                    this.selectedPerson.saldo_tabungan = data.saldo_tabungan;
                }
                // Santri Bills: Tagihan bulan ini otomatis dicentang, tunggakan lama dibiarkan uncheck (0)
                this.studentBills = (data.bills || []).map(b => {
                    let shouldCheck = b.is_current;
                    return {
                        ...b,
                        checked: shouldCheck,
                        nominal_input: shouldCheck ? b.sisa_tagihan : 0
                    };
                });
                this.studentDiscounts = data.discounts || [];
                this.tunggakanByMonth = data.tunggakan_by_month || [];
                this.totalTunggakanCount = data.tunggakan_count || 0;
                this.standardTariffs = data.standard_tariffs || this.standardTariffs;
                this.currentMonthName = data.current_month || 'September';
                this.currentYear = data.current_year || '{{ date('Y') }}';
                this.advanceMonth = this.getNextMonthName(this.currentMonthName);
                this.loadingBills = false;
                this.uangDiterima = this.calculateTotal();
            })
            .catch(err => {
                console.error(err);
                this.loadingBills = false;
            });
    },

    loadPsbData(psbId) {
        if (!psbId) return;
        this.loadingBills = true;
        fetch('{{ url('/admin/pembayaran/ajax-psb') }}/' + psbId)
            .then(res => res.json())
            .then(data => {
                this.psbData = data;
                this.psbStandardNominal = data.standard_nominal || 3220000;
                this.psbTerbayar = data.terbayar || 0;
                this.psbSisa = data.sisa !== undefined ? data.sisa : this.psbStandardNominal;
                this.psbNominalInput = this.psbSisa > 0 ? this.psbSisa : this.psbStandardNominal;
                this.psbChecked = true;
                this.loadingBills = false;
                this.uangDiterima = this.calculateTotal();
            })
            .catch(err => {
                console.error(err);
                this.loadingBills = false;
            });
    },

    // Quick Action Toggles
    toggleBill(b) {
        b.checked = !b.checked;
        b.nominal_input = b.checked ? b.sisa_tagihan : 0;
        this.uangDiterima = this.calculateTotal();
    },

    bayarBulanIniSaja() {
        this.studentBills.forEach(b => {
            if (b.is_current) {
                b.checked = true;
                b.nominal_input = b.sisa_tagihan;
            } else {
                b.checked = false;
                b.nominal_input = 0;
            }
        });
        this.advanceItems = [];
        this.customItems = [{ pos_biaya: '', nominal: '', custom_name: '' }];
        this.uangDiterima = this.calculateTotal();
    },

    lunasiSemua() {
        this.studentBills.forEach(b => {
            b.checked = true;
            b.nominal_input = b.sisa_tagihan;
        });
        this.uangDiterima = this.calculateTotal();
    },

    hanyaSyahriyah() {
        this.studentBills.forEach(b => {
            if (b.pos_biaya === 'SYAHRIYAH') {
                b.checked = true;
                b.nominal_input = b.sisa_tagihan;
            } else {
                b.checked = false;
                b.nominal_input = 0;
            }
        });
        this.uangDiterima = this.calculateTotal();
    },

    hanyaMakan() {
        this.studentBills.forEach(b => {
            if (b.pos_biaya === 'MAKAN') {
                b.checked = true;
                b.nominal_input = b.sisa_tagihan;
            } else {
                b.checked = false;
                b.nominal_input = 0;
            }
        });
        this.uangDiterima = this.calculateTotal();
    },

    kosongkanSemua() {
        this.studentBills.forEach(b => {
            b.checked = false;
            b.nominal_input = 0;
        });
        this.advanceItems = [];
        this.customItems = [{ pos_biaya: '', nominal: '', custom_name: '' }];
        this.psbChecked = false;
        this.psbNominalInput = 0;
        this.uangDiterima = 0;
    },

    // Advance Payment Helpers
    getNextMonthName(m) {
        let months = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
        let idx = months.indexOf(m);
        return (idx !== -1 && idx < 11) ? months[idx + 1] : 'Januari';
    },

    addAdvancePos(pos, nominal) {
        let defaultNom = nominal || (this.standardTariffs[pos] || 0);
        this.advanceItems.push({
            pos_biaya: pos,
            nominal: defaultNom,
            bulan: this.advanceMonth,
            tahun: this.advanceYear,
            keterangan: 'Pembayaran ' + pos + ' (' + this.advanceMonth + ' ' + this.advanceYear + ')'
        });
        this.uangDiterima = this.calculateTotal();
    },

    addAdvanceFullPackage() {
        let items = [
            { pos: 'MAKAN', nom: this.standardTariffs.MAKAN },
            { pos: 'SYAHRIYAH', nom: this.standardTariffs.SYAHRIYAH },
            { pos: 'SOT', nom: this.standardTariffs.SOT },
            { pos: 'TAB', nom: this.standardTariffs.TAB },
        ];
        items.forEach(it => {
            if (it.nom > 0) {
                this.addAdvancePos(it.pos, it.nom);
            }
        });
    },

    removeAdvanceItem(idx) {
        this.advanceItems.splice(idx, 1);
        this.uangDiterima = this.calculateTotal();
    },

    // Custom 40 Pos items (Bisa langsung ketik bebas tanpa muter-muter)
    addCustomItem() {
        this.customItems.push({ pos_biaya: '', nominal: '', custom_name: '' });
    },
    removeCustomItem(idx) {
        if (this.customItems.length > 1) {
            this.customItems.splice(idx, 1);
        } else {
            this.customItems[0] = { pos_biaya: '', nominal: '', custom_name: '' };
        }
        this.uangDiterima = this.calculateTotal();
    },

    // Grand Total
    calculateTotal() {
        let total = 0;
        if (this.paymentType === 'psb') {
            if (this.psbChecked) {
                total += parseFloat(this.psbNominalInput) || 0;
            }
        } else {
            this.studentBills.forEach(b => {
                total += parseFloat(b.nominal_input) || 0;
            });
            this.advanceItems.forEach(a => {
                total += parseFloat(a.nominal) || 0;
            });
            this.customItems.forEach(c => {
                let nom = parseFloat(c.nominal) || 0;
                let nama = (c.pos_biaya || c.custom_name || '').trim();
                if (nom > 0 && nama !== '') {
                    total += nom;
                }
            });
            total += parseFloat(this.tabunganWithdrawal) || 0;
        }
        return total;
    },

    get kembalian() {
        let total = this.calculateTotal();
        let bayar = parseFloat(this.uangDiterima) || 0;
        return bayar - total;
    },

    setUangPas() {
        this.uangDiterima = this.calculateTotal();
    },

    addUang(amount) {
        let curr = parseFloat(this.uangDiterima) || 0;
        this.uangDiterima = curr + amount;
    },

    formatRupiah(num) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(num || 0);
    }
}">

    <!-- Header Page (TailAdmin Card Style) -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 rounded-2xl border border-gray-200 bg-white p-5 sm:p-6 shadow-theme-xs">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="rounded-md bg-emerald-50 px-2 py-0.5 text-xs font-semibold text-emerald-700 border border-emerald-200">Keuangan &amp; Pembayaran</span>
                <span class="text-xs text-gray-400">•</span>
                <span class="text-xs font-medium text-gray-500">Kasir Pembayaran (POS)</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Kasir Pembayaran Santri &amp; PSB</h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">
                Pencatatan pembayaran fleksibel untuk <strong>Santri Aktif</strong> (bulanan, tunggakan lama, bayar di muka) dan <strong>Calon Santri Baru (PSB)</strong>.
            </p>
        </div>

        <!-- Action Buttons (TailAdmin Standard) -->
        <div class="flex flex-wrap items-center gap-2.5">
            <a href="{{ route('admin.pembayaran.rekapTunggakan') }}" class="inline-flex items-center gap-2 rounded-lg border border-rose-200 bg-rose-50 px-3.5 py-2.5 text-xs font-semibold text-rose-700 shadow-theme-xs hover:bg-rose-100 transition">
                <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                <span>Rekap Tunggakan &amp; Kekurangan</span>
            </a>
            <a href="{{ route('admin.pembayaran.tagihan.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-xs font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-emerald-700 transition">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                <span>Kelola Tagihan &amp; Tambahan</span>
            </a>
            <a href="{{ route('admin.pembayaran.potongan.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-xs font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-emerald-700 transition">
                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                <span>Keringanan (SKTM)</span>
            </a>
            <a href="{{ route('admin.pembayaran.jurnal.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-xs font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-emerald-700 transition">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>Buku Kas Matriks</span>
            </a>
            <a href="{{ route('admin.pembayaran.jurnal.exportTahunan') }}" class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2.5 text-xs font-semibold text-white shadow-theme-xs hover:bg-emerald-700 transition" title="Ekspor pembukuan 1 tahun penuh dalam 1 file Excel multi-sheet">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span>Ekspor Tahunan (13 Sheet)</span>
            </a>
        </div>
    </div>

    <!-- Alert Notifications -->
    @if(session('success'))
        <div class="rounded-lg border border-emerald-200 bg-emerald-50 p-3.5 text-xs text-emerald-900 shadow-theme-xs flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center gap-2.5">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>

            @if(session('last_payment_id'))
                <div class="flex items-center gap-2 shrink-0">
                    <a href="{{ route('admin.pembayaran.kwitansi', session('last_payment_id')) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-medium transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        <span>Cetak Kwitansi</span>
                    </a>
                    @if(session('last_psb_id'))
                        <a href="{{ route('admin.pembayaran.psb.kwitansi', session('last_psb_id')) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-gray-900 hover:bg-gray-800 text-white text-xs font-medium transition">
                            <span>Bukti Setoran PSB</span>
                        </a>
                    @endif
                </div>
            @endif
        </div>
    @endif
    @if(session('error'))
        <div class="rounded-lg border border-rose-200 bg-rose-50 p-3.5 text-xs text-rose-900 flex items-center gap-2.5 shadow-theme-xs">
            <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Tab Navigasi Utama (TailAdmin Standard) -->
    <div class="flex items-center gap-2 border-b border-gray-200 pb-3">
        <button type="button" @click="activeTab = 'kasir'" :class="activeTab === 'kasir' ? 'bg-emerald-600 text-white shadow-theme-xs font-semibold' : 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-300 font-medium'" class="px-4 py-2 rounded-lg text-xs transition inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            <span>Kasir POS (Santri &amp; PSB)</span>
        </button>
        <button type="button" @click="activeTab = 'santri'" :class="activeTab === 'santri' ? 'bg-emerald-600 text-white shadow-theme-xs font-semibold' : 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-300 font-medium'" class="px-4 py-2 rounded-lg text-xs transition inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <span>Riwayat Pembayaran Santri ({{ $studentPayments->total() }})</span>
        </button>
        <button type="button" @click="activeTab = 'psb'" :class="activeTab === 'psb' ? 'bg-emerald-600 text-white shadow-theme-xs font-semibold' : 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-300 font-medium'" class="px-4 py-2 rounded-lg text-xs transition inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            <span>Verifikasi Pembayaran PSB ({{ $psbPayments->total() }})</span>
            @if($psbPendingVerify > 0)
                <span class="px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-400 text-amber-950">{{ $psbPendingVerify }}</span>
            @endif
        </button>
    </div>

    <!-- ========================================================================= -->
    <!-- TAB 1: KASIR PEMBAYARAN TERPADU (SANTRI AKTIF & CALON PSB)                -->
    <!-- ========================================================================= -->
    <div x-show="activeTab === 'kasir'" class="space-y-6">
        
        <form action="{{ route('admin.pembayaran.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="payment_type" :value="paymentType">
            <input type="hidden" name="student_id" :value="paymentType === 'santri' ? selectedStudentId : ''">
            <input type="hidden" name="psb_id" :value="paymentType === 'psb' ? selectedPsbId : ''">
            <input type="hidden" name="psb_nominal" :value="paymentType === 'psb' ? psbNominalInput : ''">

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                
                <!-- KOLOM KIRI (7/12): PEMILIHAN ORANG & DAFTAR POS TAGIHAN -->
                <div class="lg:col-span-7 space-y-5">
                    
                    <!-- STEP 1: PILIH PEMBAYAR (SANTRI AKTIF / CALON PSB) -->
                    <div class="rounded-2xl border border-gray-200 bg-white p-5 sm:p-6 shadow-theme-xs">
                        
                        <!-- State 1A: Belum Memilih Santri/PSB -->
                        <div x-show="!selectedPerson" class="space-y-4">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-gray-100 pb-3">
                                <div>
                                    <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                                        <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold">1</span>
                                        Pilih Pembayar (Santri Aktif &amp; Calon PSB)
                                    </h3>
                                    <p class="text-xs text-gray-500">Cari nama, NIS, atau No. Registrasi untuk membuka tagihan</p>
                                </div>
                                <span class="text-xs text-gray-500 font-mono bg-gray-100 px-2.5 py-1 rounded-lg">
                                    <strong x-text="allPeople.length"></strong> Terdaftar (<span class="text-emerald-700 font-bold" x-text="allStudents.length + ' Santri'"></span> &bull; <span class="text-purple-700 font-bold" x-text="allPsbCandidates.length + ' PSB'"></span>)
                                </span>
                            </div>

                            <!-- Filter Kategori Pembayar (Pills) -->
                            <div class="flex flex-wrap items-center gap-2">
                                <button type="button" @click="typeFilter = 'all'" :class="typeFilter === 'all' ? 'bg-gray-900 text-white shadow-xs font-semibold' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 font-medium'" class="px-3 py-1.5 rounded-lg text-xs transition">
                                    Semua (<span x-text="allPeople.length"></span>)
                                </button>
                                <button type="button" @click="typeFilter = 'santri'" :class="typeFilter === 'santri' ? 'bg-emerald-600 text-white shadow-xs font-semibold' : 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-300 font-medium'" class="px-3 py-1.5 rounded-lg text-xs transition">
                                    Santri Aktif (<span x-text="allStudents.length"></span>)
                                </button>
                                <button type="button" @click="typeFilter = 'psb'" :class="typeFilter === 'psb' ? 'bg-blue-600 text-white shadow-xs font-semibold' : 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-300 font-medium'" class="px-3 py-1.5 rounded-lg text-xs transition">
                                    Calon Santri PSB (<span x-text="allPsbCandidates.length"></span>)
                                </button>
                            </div>

                            <!-- Filter & Search Controls -->
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
                                <div class="sm:col-span-2 relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                    </div>
                                    <input type="text" x-model="searchQuery" placeholder="Ketik Nama, NIS, atau No. Registrasi PSB..." class="w-full pl-9 pr-3 py-2.5 rounded-xl border border-gray-300 text-xs font-medium text-gray-900 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 outline-none">
                                </div>

                                <div>
                                    <select x-model="selectedClassFilter" class="w-full py-2.5 px-3 rounded-xl border border-gray-300 text-xs font-medium text-gray-700 focus:border-emerald-500 bg-white outline-none">
                                        <option value="">Semua Kelas</option>
                                        @foreach($classrooms as $c)
                                            <option value="{{ $c->nama_kelas }}">{{ $c->nama_kelas }} ({{ $c->jenjang }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Daftar Kartu Santri & PSB (Scrollable Grid) -->
                            <div class="max-h-[320px] overflow-y-auto pr-1 space-y-2 border border-gray-100 rounded-2xl p-2 bg-gray-50/50">
                                <template x-for="p in filteredPeople" :key="p.type + '_' + p.id">
                                    <div @click="selectPerson(p)" class="p-3 bg-white hover:bg-emerald-50/60 border border-gray-200/90 hover:border-emerald-300 rounded-xl cursor-pointer transition flex items-center justify-between gap-3 shadow-2xs group">
                                        <div class="flex items-center gap-3">
                                            <!-- Avatar Initial -->
                                            <div class="w-10 h-10 rounded-xl flex items-center justify-center font-bold text-sm shrink-0 shadow-2xs text-white" :class="p.type === 'santri' ? 'bg-gradient-to-br from-emerald-600 to-teal-700' : 'bg-gradient-to-br from-purple-600 to-indigo-700'">
                                                <span x-text="p.nama ? p.nama.charAt(0) : '?'"></span>
                                            </div>
                                            <div>
                                                <div class="font-bold text-xs text-gray-900 group-hover:text-emerald-800 transition" x-text="p.nama"></div>
                                                <div class="text-[11px] text-gray-500 flex flex-wrap items-center gap-1.5 mt-0.5">
                                                    <!-- Badge Tipe -->
                                                    <span class="px-1.5 py-0.2 rounded text-[10px] font-bold uppercase tracking-wider" :class="p.type === 'santri' ? 'bg-emerald-100 text-emerald-800' : 'bg-purple-100 text-purple-800'" x-text="p.type === 'santri' ? 'Santri Aktif' : 'Calon PSB'"></span>
                                                    <!-- Badge Kategori Jenjang & Hunian -->
                                                    <span x-show="p.type === 'santri' && p.kategori_label" class="px-1.5 py-0.2 rounded text-[10px] font-extrabold border shadow-2xs" :class="p.hunian === 'Mukim' ? 'bg-emerald-50 text-emerald-800 border-emerald-300' : 'bg-blue-50 text-blue-800 border-blue-300'" x-text="p.kategori_label"></span>
                                                    <span>&bull;</span>
                                                    <span x-text="(p.type === 'santri' ? 'NIS: ' : 'No Reg: ') + p.nomor" class="font-mono text-gray-700 font-semibold"></span>
                                                    <span>&bull;</span>
                                                    <span class="text-gray-600" x-text="p.kelas"></span>
                                                    <span x-show="p.type === 'santri' && p.tarif_bulanan" class="text-[10px] text-emerald-700 font-bold ml-1 font-mono" x-text="'&bull; ' + formatRupiah(p.tarif_bulanan.total_bulanan) + '/bln'"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="shrink-0">
                                            <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-emerald-600 group-hover:bg-emerald-700 text-white text-[11px] font-bold shadow-xs transition">
                                                <span>Pilih</span>
                                                <span>&rarr;</span>
                                            </span>
                                        </div>
                                    </div>
                                </template>

                                <div x-show="filteredPeople.length === 0" class="text-center py-8 text-gray-400 text-xs">
                                    Tidak ada data santri atau calon PSB yang cocok dengan kata kunci pencarian.
                                </div>
                            </div>
                        </div>

                        <!-- State 1B: Orang Sudah Terpilih (Profile Card Mewah) -->
                        <div x-show="selectedPerson" class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-[11px] font-bold text-emerald-800 uppercase tracking-wider flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    <span>Pembayar Terpilih</span>
                                </span>
                                <button type="button" @click="resetPerson()" class="inline-flex items-center gap-1 text-xs font-bold text-rose-600 hover:text-rose-700 hover:underline">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                    Ganti Pembayar Lain
                                </button>
                            </div>

                            <div class="p-4 rounded-2xl text-white shadow-md flex flex-col sm:flex-row sm:items-center justify-between gap-4" :class="paymentType === 'santri' ? 'bg-gradient-to-r from-emerald-900 via-emerald-800 to-teal-900' : 'bg-gradient-to-r from-purple-900 via-indigo-800 to-slate-900'">
                                <div class="flex items-center gap-3.5">
                                    <div class="w-13 h-13 rounded-2xl bg-white/20 backdrop-blur-md border border-white/30 text-white flex items-center justify-center text-xl font-bold shrink-0">
                                        <span x-text="selectedPerson ? selectedPerson.nama.charAt(0) : '?'"></span>
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h3 class="text-base font-black tracking-tight" x-text="selectedPerson ? selectedPerson.nama : ''"></h3>
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase" :class="paymentType === 'santri' ? 'bg-emerald-300 text-emerald-950' : 'bg-amber-300 text-purple-950'" x-text="paymentType === 'santri' ? 'Santri Aktif' : 'Calon Santri PSB'"></span>
                                            <span x-show="paymentType === 'santri' && selectedPerson && selectedPerson.kategori_label" class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase shadow-xs" :class="selectedPerson && selectedPerson.hunian === 'Mukim' ? 'bg-emerald-200 text-emerald-950 border border-emerald-300' : 'bg-blue-200 text-blue-950 border border-blue-300'" x-text="selectedPerson ? selectedPerson.kategori_label : ''"></span>
                                        </div>
                                        <div class="text-xs text-emerald-200 flex flex-wrap items-center gap-2 mt-0.5">
                                            <span x-text="(paymentType === 'santri' ? 'NIS: ' : 'No Reg: ') + (selectedPerson ? selectedPerson.nomor : '')" class="font-mono text-white font-bold"></span>
                                            <span>&bull;</span>
                                            <span class="px-2 py-0.5 rounded-md bg-white/20 text-white font-semibold" x-text="selectedPerson ? selectedPerson.kelas : ''"></span>
                                            <span x-show="selectedPerson && selectedPerson.asrama" class="text-emerald-100" x-text="'&bull; ' + (selectedPerson ? selectedPerson.asrama : '')"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-right shrink-0 bg-white/10 backdrop-blur-sm px-4 py-2.5 rounded-xl border border-white/20">
                                    <span class="text-[10px] uppercase font-bold text-emerald-200 block" x-text="paymentType === 'santri' ? 'Total Tunggakan Santri' : 'Sisa Biaya Masuk PSB'"></span>
                                    <span class="text-base sm:text-lg font-black font-mono text-amber-300" x-text="formatRupiah(paymentType === 'santri' ? (studentBills.reduce((acc, b) => acc + (b.sisa_tagihan || 0), 0)) : psbSisa)"></span>

                                    <!-- Tombol Buka Rincian Tunggakan Per Bulan -->
                                    <div class="mt-2" x-show="paymentType === 'santri' && tunggakanByMonth.length > 0">
                                        <button type="button" @click="showTunggakanModal = true" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-amber-400 hover:bg-amber-300 text-gray-950 text-[11px] font-black transition shadow-xs">
                                            <svg class="w-3.5 h-3.5 text-gray-950" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                            <span>Rincian Tunggakan (<span x-text="tunggakanByMonth.length"></span> Periode)</span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Info Standar Tagihan Bulanan Resmi Sesuai Brosur Pesantren -->
                            <div x-show="paymentType === 'santri' && selectedPerson && selectedPerson.tarif_bulanan" class="p-3 bg-emerald-50/90 border border-emerald-200 rounded-xl text-xs space-y-1.5 shadow-2xs">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-emerald-200/70 pb-1.5">
                                    <div class="font-bold text-emerald-950 flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-emerald-700 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        <span>Standar Tagihan Bulanan Resmi: <strong class="text-emerald-900" x-text="selectedPerson ? selectedPerson.kategori_label : ''"></strong></span>
                                    </div>
                                    <div class="text-right font-mono font-black text-emerald-900 text-xs sm:text-sm">
                                        Total Iuran: <span x-text="formatRupiah(selectedPerson && selectedPerson.tarif_bulanan ? selectedPerson.tarif_bulanan.total_bulanan : 0) + ' / bulan'"></span>
                                    </div>
                                </div>
                                <div class="flex flex-wrap items-center gap-3 text-[11px] text-emerald-900 pt-0.5">
                                    <span class="inline-flex items-center gap-1">
                                        &bull; Uang Makan 3x: <strong class="font-mono font-bold" x-text="formatRupiah(selectedPerson && selectedPerson.tarif_bulanan ? selectedPerson.tarif_bulanan.uang_makan : 0)"></strong>
                                        <span x-show="selectedPerson && selectedPerson.hunian === 'Laju'" class="text-emerald-700 italic font-semibold">(Non-Asrama / Rp 0)</span>
                                    </span>
                                    <span class="inline-flex items-center gap-1">
                                        &bull; Syahriah: <strong class="font-mono font-bold" x-text="formatRupiah(selectedPerson && selectedPerson.tarif_bulanan ? selectedPerson.tarif_bulanan.syahriah : 0)"></strong>
                                    </span>
                                    <span class="inline-flex items-center gap-1">
                                        &bull; Tabungan Wajib: <strong class="font-mono font-bold" x-text="formatRupiah(selectedPerson && selectedPerson.tarif_bulanan ? selectedPerson.tarif_bulanan.tabungan_wajib : 0)"></strong>
                                    </span>
                                </div>
                            </div>

                            <!-- Banner Informasi Keringanan SKTM / Beasiswa (Khusus Santri Aktif) -->
                            <div x-show="paymentType === 'santri' && studentDiscounts.length > 0" class="p-3 bg-purple-50 border border-purple-200 rounded-xl text-xs space-y-1">
                                <div class="font-bold text-purple-900 flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                    <span>Santri Memiliki Keringanan Subsidi SKTM / Beasiswa Aktif:</span>
                                </div>
                                <template x-for="d in studentDiscounts" :key="d.id">
                                    <div class="text-purple-800 text-[11px] flex items-center justify-between">
                                        <div>
                                            &bull; <strong x-text="d.jenis_potongan"></strong>
                                            <span x-show="d.no_surat_miskin" class="font-mono text-purple-600" x-text="'(SKTM: ' + d.no_surat_miskin + ')'"></span>
                                            <span>&mdash; Pos: <span class="font-bold font-mono" x-text="d.pos_biaya"></span></span>
                                        </div>
                                        <span class="font-bold font-mono bg-purple-100 text-purple-900 px-2 py-0.5 rounded" x-text="d.tipe_nilai === 'persen' ? d.nilai + '%' : formatRupiah(d.nilai)"></span>
                                    </div>
                                </template>
                            </div>
                        </div>

                    </div>

                    <!-- STEP 2: DAFTAR POS TAGIHAN YANG DIBAYAR -->
                    <div x-show="selectedPerson" class="rounded-2xl border border-gray-200 bg-white p-5 sm:p-6 shadow-theme-xs space-y-5">
                        
                        <!-- ========================================================= -->
                        <!-- JALUR A: TAGIHAN CALON SANTRI BARU (PSB)                  -->
                        <!-- ========================================================= -->
                        <div x-show="paymentType === 'psb'" class="space-y-4">
                            <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                                <div>
                                    <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                                        <span class="w-6 h-6 rounded-lg bg-purple-100 text-purple-700 flex items-center justify-center text-xs font-bold">2</span>
                                        Rincian Biaya Administrasi &amp; Daftar Ulang PSB
                                    </h3>
                                    <p class="text-xs text-gray-500">Calon santri baru dapat membayar lunas atau mencicil biaya masuk pesantren</p>
                                </div>
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-purple-100 text-purple-800">PSB 2026/2027</span>
                            </div>

                            <div class="p-4 rounded-2xl border transition" :class="psbNominalInput > 0 ? 'bg-purple-50/40 border-purple-300' : 'bg-white border-gray-200'">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                    <div class="flex items-start gap-3">
                                        <input type="checkbox" x-model="psbChecked" @change="psbNominalInput = psbChecked ? (psbSisa > 0 ? psbSisa : psbStandardNominal) : 0" class="w-4 h-4 rounded text-purple-600 focus:ring-purple-500 border-gray-300 mt-1 cursor-pointer">
                                        <div>
                                            <div class="flex flex-wrap items-center gap-2">
                                                <span class="px-2 py-0.5 rounded-md font-mono font-black text-[10px] bg-purple-200 text-purple-900 tracking-wide">DAFTAR ULANG</span>
                                                <span class="font-bold text-xs text-gray-900">Pendaftaran &amp; Daftar Ulang PSB</span>
                                                <!-- Badge Jenjang & Tipe Hunian (Mukim / Laju) -->
                                                <span x-show="psbData && psbData.jenjang_label" class="px-2.5 py-0.5 rounded-full text-[10.5px] font-extrabold shadow-2xs" :class="psbData && psbData.hunian === 'Mukim' ? 'bg-emerald-100 text-emerald-900 border border-emerald-300' : 'bg-blue-100 text-blue-900 border border-blue-300'" x-text="psbData ? psbData.jenjang_label : ''"></span>
                                            </div>
                                            
                                            <!-- Rincian Biaya Awal -->
                                            <div class="text-[11px] text-gray-500 flex flex-wrap items-center gap-2 mt-1.5">
                                                <span>Total Biaya Masuk: <strong class="text-gray-800 font-mono font-bold" x-text="formatRupiah(psbData ? psbData.total_biaya_masuk : psbStandardNominal)"></strong></span>
                                                <span>&bull;</span>
                                                <span>Biaya Pendaftaran: <strong :class="psbData && psbData.pendaftaran_lunas ? 'text-emerald-700' : 'text-amber-700'" x-text="psbData && psbData.pendaftaran_lunas ? 'Rp 200.000 (✓ Lunas)' : 'Rp 200.000 (Belum Lunas)'"></strong></span>
                                                <span>&bull;</span>
                                                <span>Sisa Tagihan Daftar Ulang: <strong class="text-rose-600 font-mono font-bold" x-text="formatRupiah(psbSisa)"></strong></span>
                                                <span>&bull;</span>
                                                <span>Terbayar: <strong class="text-emerald-700" x-text="formatRupiah(psbTerbayar)"></strong></span>
                                            </div>

                                            <!-- Toggle Lihat Rincian 9 Item -->
                                            <div class="mt-2">
                                                <button type="button" @click="psbShowBreakdown = !psbShowBreakdown" class="inline-flex items-center gap-1.5 text-[11px] font-bold text-purple-700 hover:text-purple-900 bg-purple-100/70 hover:bg-purple-100 px-2.5 py-1 rounded-md transition">
                                                    <svg class="w-3.5 h-3.5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                                    <span x-text="psbShowBreakdown ? 'Sembunyikan Rincian 9 Item Biaya' : 'Lihat Rincian 9 Item Komponen Biaya Resmi'"></span>
                                                    <span x-text="psbShowBreakdown ? '▲' : '▼'" class="text-[9px]"></span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Input Nominal Pembayaran PSB -->
                                    <div class="flex items-center gap-2 shrink-0">
                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-0 pl-2.5 flex items-center text-gray-400 text-xs font-bold pointer-events-none">Rp</span>
                                            <input type="number" x-model="psbNominalInput" min="0" :max="psbStandardNominal" placeholder="0" class="w-36 pl-8 pr-2 py-1.5 rounded-xl border border-gray-300 text-xs font-black text-purple-900 text-right focus:border-purple-500 outline-none">
                                        </div>
                                        <button type="button" @click="psbNominalInput = (psbSisa > 0 ? psbSisa : psbStandardNominal); psbChecked = true" class="px-2 py-1.5 rounded-lg bg-purple-100 hover:bg-purple-200 text-purple-800 text-[11px] font-bold transition" title="Lunasi Sisa">
                                            Pas
                                        </button>
                                        <button type="button" @click="psbNominalInput = Math.round((psbSisa > 0 ? psbSisa : psbStandardNominal) / 2); psbChecked = true" class="px-2 py-1.5 rounded-lg bg-blue-100 hover:bg-blue-200 text-blue-800 text-[11px] font-bold transition" title="Cicil Setengah">
                                            ½
                                        </button>
                                        <button type="button" @click="psbNominalInput = 0; psbChecked = false" class="px-2 py-1.5 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-600 text-[11px] font-semibold transition">
                                            0
                                        </button>
                                    </div>
                                </div>

                                <!-- Accordion Rincian 9 Item Biaya Resmi -->
                                <div x-show="psbShowBreakdown" x-transition class="mt-4 pt-3 border-t border-purple-200 text-xs">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="font-bold text-gray-800 uppercase tracking-wider text-[10px]">Rincian Komponen Biaya Resmi Sesuai Brosur SK Pondok</span>
                                        <span class="text-[10px] text-gray-500 font-semibold" x-text="'Kategori: ' + (psbData ? psbData.jenjang_label : '')"></span>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                        <template x-for="(item, idx) in (psbData ? psbData.items_daftar_ulang : [])" :key="idx">
                                            <div class="flex items-center justify-between px-3 py-1.5 bg-white rounded-lg border border-purple-100 text-[11px]">
                                                <span class="text-gray-700 font-medium" x-text="item.nama"></span>
                                                <span class="font-bold text-gray-900 font-mono" x-text="formatRupiah(item.nominal)"></span>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ========================================================= -->
                        <!-- JALUR B: TAGIHAN SANTRI AKTIF (FLEKSIBEL TUNGGAKAN & ADV) -->
                        <!-- ========================================================= -->
                        <div x-show="paymentType === 'santri'" class="space-y-4">
                            
                            <!-- Banner Kategori & Standar Tarif Resmi Santri Lama / Aktif -->
                            <div class="p-3 rounded-xl border flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-2xs"
                                 :class="selectedPerson && selectedPerson.hunian === 'Mukim' ? 'bg-emerald-50/70 border-emerald-300' : 'bg-blue-50/70 border-blue-300'">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-xs shrink-0"
                                         :class="selectedPerson && selectedPerson.hunian === 'Mukim' ? 'bg-emerald-600 text-white' : 'bg-blue-600 text-white'">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs font-bold text-gray-900">Kategori Tagihan Santri:</span>
                                            <span class="px-2 py-0.5 rounded-md text-[11px] font-black uppercase shadow-2xs"
                                                  :class="selectedPerson && selectedPerson.hunian === 'Mukim' ? 'bg-emerald-200 text-emerald-950 border border-emerald-400' : 'bg-blue-200 text-blue-950 border border-blue-400'"
                                                  x-text="selectedPerson ? selectedPerson.kategori_label : ''"></span>
                                        </div>
                                        <div class="text-[11px] text-gray-600 mt-0.5 flex flex-wrap items-center gap-1.5">
                                            <span>Standar Iuran:</span>
                                            <strong class="font-mono text-gray-900 font-bold" x-text="formatRupiah(selectedPerson && selectedPerson.tarif_bulanan ? selectedPerson.tarif_bulanan.total_bulanan : 0) + ' / bulan'"></strong>
                                            <span>(Uang Makan: <strong x-text="formatRupiah(selectedPerson && selectedPerson.tarif_bulanan ? selectedPerson.tarif_bulanan.uang_makan : 0)"></strong> &bull; Syahriah: <strong x-text="formatRupiah(selectedPerson && selectedPerson.tarif_bulanan ? selectedPerson.tarif_bulanan.syahriah : 0)"></strong> &bull; Tabungan: <strong x-text="formatRupiah(selectedPerson && selectedPerson.tarif_bulanan ? selectedPerson.tarif_bulanan.tabungan_wajib : 0)"></strong>)</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Header & Quick Action Buttons -->
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-gray-200 pb-3">
                                <div>
                                    <h3 class="text-sm font-bold text-gray-900">
                                        Pilih Tagihan yang Dibayar
                                    </h3>
                                    <p class="text-xs text-gray-500">Pilih pos tagihan yang akan dibayarkan santri hari ini</p>
                                </div>

                                <!-- Quick Buttons -->
                                <div class="flex flex-wrap items-center gap-1.5">
                                    <button type="button" @click="bayarBulanIniSaja()" class="px-2.5 py-1.5 rounded-lg bg-white hover:bg-gray-50 text-gray-700 text-xs font-medium border border-gray-300 transition" title="Pilih hanya tagihan bulan ini">
                                        Bulan Ini
                                    </button>
                                    <button type="button" @click="lunasiSemua()" class="px-2.5 py-1.5 rounded-lg bg-gray-900 hover:bg-gray-800 text-white text-xs font-medium transition">
                                        Lunasi Semua
                                    </button>
                                    <button type="button" @click="hanyaSyahriyah()" class="px-2.5 py-1.5 rounded-lg bg-white hover:bg-gray-50 text-gray-700 text-xs font-medium border border-gray-300 transition">
                                        Syahriyah
                                    </button>
                                    <button type="button" @click="hanyaMakan()" class="px-2.5 py-1.5 rounded-lg bg-white hover:bg-gray-50 text-gray-700 text-xs font-medium border border-gray-300 transition">
                                        Uang Makan
                                    </button>
                                    <button type="button" @click="kosongkanSemua()" class="px-2.5 py-1.5 rounded-lg bg-white hover:bg-gray-50 text-gray-500 text-xs font-medium border border-gray-200 transition">
                                        Kosongkan
                                    </button>
                                </div>
                            </div>

                            <!-- Loading Spinner -->
                            <div x-show="loadingBills" class="py-10 text-center text-xs text-gray-400">
                                Memuat rincian tagihan...
                            </div>

                            <!-- SECTION 1: TUNGGAKAN BULAN SEBELUMNYA -->
                            <div x-show="!loadingBills && studentBills.some(b => b.is_past)" class="space-y-2">
                                <div class="flex items-center justify-between pb-1.5 border-b border-gray-200 text-xs">
                                    <span class="font-bold text-gray-800">Tunggakan Periode Sebelumnya</span>
                                    <span class="text-[11px] text-gray-500">Pilih pos yang ingin dibayar / dicicil</span>
                                </div>

                                <template x-for="b in studentBills.filter(x => x.is_past)" :key="'past_' + b.id">
                                    <div class="p-3 rounded-xl border transition" :class="b.nominal_input > 0 ? 'bg-rose-50/20 border-rose-300' : 'bg-white border-gray-200'">
                                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                            <div class="flex items-start gap-3">
                                                <input type="checkbox" :checked="b.nominal_input > 0" @change="toggleBill(b)" class="w-4 h-4 rounded text-brand-600 focus:ring-brand-500 border-gray-300 mt-0.5 cursor-pointer">
                                                <div>
                                                    <div class="flex items-center gap-2 flex-wrap">
                                                        <span class="px-1.5 py-0.5 rounded font-mono font-bold text-[10px] bg-rose-50 text-rose-700 border border-rose-200" x-text="b.pos_biaya"></span>
                                                        <span class="font-bold text-xs text-gray-900" x-text="b.judul_tagihan"></span>
                                                        <span class="text-[11px] text-gray-500 font-medium" x-text="b.bulan ? (b.bulan + ' ' + b.tahun) : (b.tahun ? b.tahun : '')"></span>
                                                    </div>
                                                    <div class="text-[11px] text-gray-500 flex flex-wrap items-center gap-2 mt-0.5">
                                                        <span>Tagihan: <span class="font-medium text-gray-700" x-text="formatRupiah(b.nominal_tagihan)"></span></span>
                                                        <span>&bull;</span>
                                                        <span>Sisa: <span class="text-rose-600 font-mono font-bold" x-text="formatRupiah(b.sisa_tagihan)"></span></span>
                                                        <span x-show="b.nominal_potongan > 0" class="text-purple-700 font-semibold" x-text="'(Keringanan: -' + formatRupiah(b.nominal_potongan) + ')'"></span>
                                                        <span x-show="b.nominal_bayar > 0" class="text-gray-500" x-text="'(Dicicil: ' + formatRupiah(b.nominal_bayar) + ')'"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="flex items-center gap-1.5 shrink-0">
                                                <input type="hidden" :name="'items[' + b.id + '][pos_biaya]'" :value="b.pos_biaya">
                                                <input type="hidden" :name="'items[' + b.id + '][bill_id]'" :value="b.id">
                                                <input type="hidden" :name="'items[' + b.id + '][keterangan]'" :value="b.judul_tagihan">

                                                <div class="relative">
                                                    <span class="absolute inset-y-0 left-0 pl-2.5 flex items-center text-gray-400 text-xs font-bold pointer-events-none">Rp</span>
                                                    <input type="number" :name="'items[' + b.id + '][nominal]'" x-model="b.nominal_input" min="0" :max="b.sisa_tagihan" placeholder="0" class="w-28 pl-8 pr-2 py-1.5 rounded-lg border border-gray-300 text-xs font-bold text-gray-900 text-right focus:border-brand-500 outline-none">
                                                </div>
                                                <button type="button" @click="b.nominal_input = b.sisa_tagihan; b.checked = true" class="px-2.5 py-1.5 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-medium transition" title="Bayar Lunas">
                                                    Pas
                                                </button>
                                                <button type="button" @click="b.nominal_input = Math.round(b.sisa_tagihan / 2); b.checked = true" class="px-2.5 py-1.5 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-medium transition" title="Cicil Setengah">
                                                    ½
                                                </button>
                                                <button type="button" @click="b.nominal_input = 0; b.checked = false" class="px-2 py-1.5 rounded-lg text-gray-400 hover:text-gray-600 text-xs font-medium transition">
                                                    0
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <!-- SECTION 2: TAGIHAN RUTIN BULAN INI -->
                            <div x-show="!loadingBills && studentBills.some(b => b.is_current)" class="space-y-2">
                                <div class="flex items-center justify-between pb-1.5 border-b border-gray-200 text-xs">
                                    <span class="font-bold text-gray-800">Tagihan Rutin Bulan Ini (<span x-text="currentMonthName + ' ' + currentYear"></span>)</span>
                                    <span class="text-[11px] text-gray-500">Dapat dibayar langsung</span>
                                </div>

                                <template x-for="b in studentBills.filter(x => x.is_current)" :key="'cur_' + b.id">
                                    <div class="p-3 rounded-xl border transition" :class="b.nominal_input > 0 ? 'bg-emerald-50/20 border-emerald-300' : 'bg-white border-gray-200'">
                                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                            <div class="flex items-start gap-3">
                                                <input type="checkbox" :checked="b.nominal_input > 0" @change="toggleBill(b)" class="w-4 h-4 rounded text-brand-600 focus:ring-brand-500 border-gray-300 mt-0.5 cursor-pointer">
                                                <div>
                                                    <div class="flex items-center gap-2">
                                                        <span class="px-1.5 py-0.5 rounded font-mono font-bold text-[10px] bg-emerald-50 text-emerald-700 border border-emerald-200" x-text="b.pos_biaya"></span>
                                                        <span class="font-bold text-xs text-gray-900" x-text="b.judul_tagihan"></span>
                                                    </div>
                                                    <div class="text-[11px] text-gray-500 flex flex-wrap items-center gap-2 mt-0.5">
                                                        <span>Tagihan: <span class="font-medium text-gray-700" x-text="formatRupiah(b.nominal_tagihan)"></span></span>
                                                        <span>&bull;</span>
                                                        <span>Sisa: <span class="text-emerald-700 font-mono font-bold" x-text="formatRupiah(b.sisa_tagihan)"></span></span>
                                                        <span x-show="b.nominal_potongan > 0" class="text-purple-700 font-semibold" x-text="'(Keringanan: -' + formatRupiah(b.nominal_potongan) + ')'"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="flex items-center gap-1.5 shrink-0">
                                                <input type="hidden" :name="'items[' + b.id + '][pos_biaya]'" :value="b.pos_biaya">
                                                <input type="hidden" :name="'items[' + b.id + '][bill_id]'" :value="b.id">
                                                <input type="hidden" :name="'items[' + b.id + '][keterangan]'" :value="b.judul_tagihan">

                                                <div class="relative">
                                                    <span class="absolute inset-y-0 left-0 pl-2.5 flex items-center text-gray-400 text-xs font-bold pointer-events-none">Rp</span>
                                                    <input type="number" :name="'items[' + b.id + '][nominal]'" x-model="b.nominal_input" min="0" :max="b.sisa_tagihan" placeholder="0" class="w-28 pl-8 pr-2 py-1.5 rounded-lg border border-gray-300 text-xs font-bold text-gray-900 text-right focus:border-brand-500 outline-none">
                                                </div>
                                                <button type="button" @click="b.nominal_input = b.sisa_tagihan; b.checked = true" class="px-2.5 py-1.5 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-medium transition" title="Bayar Lunas">
                                                    Pas
                                                </button>
                                                <button type="button" @click="b.nominal_input = Math.round(b.sisa_tagihan / 2); b.checked = true" class="px-2.5 py-1.5 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-medium transition" title="Bayar Setengah">
                                                    ½
                                                </button>
                                                <button type="button" @click="b.nominal_input = 0; b.checked = false" class="px-2 py-1.5 rounded-lg text-gray-400 hover:text-gray-600 text-xs font-medium transition">
                                                    0
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <!-- SECTION 3: TAGIHAN TAMBAHAN / INSIDENTAL -->
                            <div x-show="!loadingBills && studentBills.some(b => b.is_incidental)" class="space-y-2">
                                <div class="flex items-center justify-between pb-1.5 border-b border-gray-200 text-xs">
                                    <span class="font-bold text-gray-800">Tagihan Tambahan / Insidental (Ziarah, Wisuda, dsb.)</span>
                                </div>

                                <template x-for="b in studentBills.filter(x => x.is_incidental)" :key="'inc_' + b.id">
                                    <div class="p-3 rounded-xl border transition" :class="b.nominal_input > 0 ? 'bg-blue-50/20 border-blue-300' : 'bg-white border-gray-200'">
                                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                            <div class="flex items-start gap-3">
                                                <input type="checkbox" :checked="b.nominal_input > 0" @change="toggleBill(b)" class="w-4 h-4 rounded text-brand-600 focus:ring-brand-500 border-gray-300 mt-0.5 cursor-pointer">
                                                <div>
                                                    <div class="flex items-center gap-2">
                                                        <span class="px-1.5 py-0.5 rounded font-mono font-bold text-[10px] bg-blue-50 text-blue-700 border border-blue-200" x-text="b.pos_biaya"></span>
                                                        <span class="font-bold text-xs text-gray-900" x-text="b.judul_tagihan"></span>
                                                    </div>
                                                    <div class="text-[11px] text-gray-500 flex flex-wrap items-center gap-2 mt-0.5">
                                                        <span>Tagihan: <span class="font-medium text-gray-700" x-text="formatRupiah(b.nominal_tagihan)"></span></span>
                                                        <span>&bull;</span>
                                                        <span>Sisa: <span class="text-blue-700 font-mono font-bold" x-text="formatRupiah(b.sisa_tagihan)"></span></span>
                                                        <span x-show="b.nominal_potongan > 0" class="text-purple-700 font-semibold" x-text="'(Keringanan: -' + formatRupiah(b.nominal_potongan) + ')'"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="flex items-center gap-1.5 shrink-0">
                                                <input type="hidden" :name="'items[' + b.id + '][pos_biaya]'" :value="b.pos_biaya">
                                                <input type="hidden" :name="'items[' + b.id + '][bill_id]'" :value="b.id">
                                                <input type="hidden" :name="'items[' + b.id + '][keterangan]'" :value="b.judul_tagihan">

                                                <div class="relative">
                                                    <span class="absolute inset-y-0 left-0 pl-2.5 flex items-center text-gray-400 text-xs font-bold pointer-events-none">Rp</span>
                                                    <input type="number" :name="'items[' + b.id + '][nominal]'" x-model="b.nominal_input" min="0" :max="b.sisa_tagihan" placeholder="0" class="w-28 pl-8 pr-2 py-1.5 rounded-lg border border-gray-300 text-xs font-bold text-gray-900 text-right focus:border-brand-500 outline-none">
                                                </div>
                                                <button type="button" @click="b.nominal_input = b.sisa_tagihan; b.checked = true" class="px-2.5 py-1.5 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-medium transition" title="Bayar Lunas">
                                                    Pas
                                                </button>
                                                <button type="button" @click="b.nominal_input = 0; b.checked = false" class="px-2 py-1.5 rounded-lg text-gray-400 hover:text-gray-600 text-xs font-medium transition">
                                                    0
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <!-- NOTIFIKASI: TIDAK ADA TUNGGAKAN -->
                            <div x-show="!loadingBills && studentBills.length === 0" class="p-5 rounded-2xl bg-emerald-50 border border-emerald-200 text-center space-y-1">
                                <div class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-emerald-100 text-emerald-700 mb-1">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <h4 class="text-xs font-bold text-emerald-900 uppercase tracking-wide">Semua Tagihan Lunas / Bebas</h4>
                                <p class="text-xs text-emerald-700">Santri ini tidak memiliki tunggakan tagihan yang belum dibayar (seluruh pos telah lunas atau disubsidi penuh oleh beasiswa/SKTM).</p>
                            </div>

                            <!-- SECTION 4: PEMBAYARAN BULAN DEPAN (DI MUKA) -->
                            <div class="p-4 rounded-xl border border-gray-200 bg-gray-50/60 space-y-3">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-gray-200 pb-2.5">
                                    <div>
                                        <h4 class="text-xs font-bold text-gray-900">Pembayaran Bulan Depan (Di Muka)</h4>
                                        <p class="text-[11px] text-gray-500">Tambahkan pos tagihan jika santri ingin membayar untuk bulan berikutnya</p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs text-gray-600">Pilih Bulan:</span>
                                        <select x-model="advanceMonth" class="h-8 py-1 px-2.5 rounded-lg border border-gray-300 text-xs font-medium text-gray-800 bg-white focus:border-brand-500 outline-none">
                                            <option value="Januari">Januari</option>
                                            <option value="Februari">Februari</option>
                                            <option value="Maret">Maret</option>
                                            <option value="April">April</option>
                                            <option value="Mei">Mei</option>
                                            <option value="Juni">Juni</option>
                                            <option value="Juli">Juli</option>
                                            <option value="Agustus">Agustus</option>
                                            <option value="September">September</option>
                                            <option value="Oktober">Oktober</option>
                                            <option value="November">November</option>
                                            <option value="Desember">Desember</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Pos Chips -->
                                <div class="flex flex-wrap items-center gap-1.5">
                                    <button type="button" @click="addAdvancePos('MAKAN')" class="px-2.5 py-1.5 rounded-lg bg-white hover:bg-gray-100 text-gray-700 border border-gray-300 text-xs font-medium transition">
                                        <span>+ Uang Makan</span>
                                        <span class="text-gray-400 font-mono text-[11px]" x-text="'(' + formatRupiah(standardTariffs.MAKAN) + ')'"></span>
                                    </button>
                                    <button type="button" @click="addAdvancePos('SYAHRIYAH')" class="px-2.5 py-1.5 rounded-lg bg-white hover:bg-gray-100 text-gray-700 border border-gray-300 text-xs font-medium transition">
                                        <span>+ Syahriyah (SPP)</span>
                                        <span class="text-gray-400 font-mono text-[11px]" x-text="'(' + formatRupiah(standardTariffs.SYAHRIYAH) + ')'"></span>
                                    </button>
                                    <button type="button" @click="addAdvancePos('SOT')" class="px-2.5 py-1.5 rounded-lg bg-white hover:bg-gray-100 text-gray-700 border border-gray-300 text-xs font-medium transition">
                                        <span>+ SOT</span>
                                        <span class="text-gray-400 font-mono text-[11px]" x-text="'(' + formatRupiah(standardTariffs.SOT) + ')'"></span>
                                    </button>
                                    <button type="button" @click="addAdvancePos('TAB')" class="px-2.5 py-1.5 rounded-lg bg-white hover:bg-gray-100 text-gray-700 border border-gray-300 text-xs font-medium transition">
                                        <span>+ Tabungan</span>
                                        <span class="text-gray-400 font-mono text-[11px]" x-text="'(' + formatRupiah(standardTariffs.TAB) + ')'"></span>
                                    </button>
                                    <button type="button" @click="addAdvanceFullPackage()" class="px-3 py-1.5 rounded-lg bg-gray-900 hover:bg-gray-800 text-white text-xs font-medium transition">
                                        + Paket Lengkap Bulan Depan
                                    </button>
                                </div>

                                <!-- Baris-baris Pembayaran Bulan Depan yang Ditambahkan -->
                                <div x-show="advanceItems.length > 0" class="space-y-2 pt-2 border-t border-gray-200">
                                    <template x-for="(a, aIdx) in advanceItems" :key="'adv_' + aIdx">
                                        <div class="flex items-center justify-between gap-3 p-2.5 bg-white rounded-lg border border-gray-200">
                                            <input type="hidden" :name="'items[adv_' + aIdx + '][is_advance]'" value="1">
                                            <input type="hidden" :name="'items[adv_' + aIdx + '][pos_biaya]'" :value="a.pos_biaya">
                                            <input type="hidden" :name="'items[adv_' + aIdx + '][bulan]'" :value="a.bulan">
                                            <input type="hidden" :name="'items[adv_' + aIdx + '][tahun]'" :value="a.tahun">
                                            <input type="hidden" :name="'items[adv_' + aIdx + '][keterangan]'" :value="a.keterangan">

                                            <div class="flex items-center gap-2">
                                                <span class="px-1.5 py-0.5 rounded font-mono font-bold text-[10px] bg-gray-100 text-gray-700" x-text="a.pos_biaya"></span>
                                                <span class="text-xs font-medium text-gray-900" x-text="a.keterangan"></span>
                                                <span class="px-1.5 py-0.5 rounded text-[10px] bg-gray-100 text-gray-600 font-medium" x-text="a.bulan + ' ' + a.tahun"></span>
                                            </div>

                                            <div class="flex items-center gap-2">
                                                <span class="text-xs text-gray-400 font-medium">Rp</span>
                                                <input type="number" :name="'items[adv_' + aIdx + '][nominal]'" x-model="a.nominal" placeholder="0" class="w-28 py-1 px-2 rounded-lg border border-gray-300 font-mono font-bold text-xs text-right text-gray-900 focus:border-brand-500 outline-none">
                                                <button type="button" @click="removeAdvanceItem(aIdx)" class="text-gray-400 hover:text-rose-600 p-1 rounded-lg text-sm font-bold" title="Hapus">
                                                    &times;
                                                </button>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- SECTION 6: POS PEMBAYARAN TAMBAHAN / INSIDENTAL (BISA LANGSUNG KETIK TANPA RIBET) -->
                            <div class="pt-3 border-t border-gray-200 mt-3">
                                <div class="flex items-center justify-between mb-2">
                                    <div>
                                        <span class="text-xs font-bold text-gray-800 flex items-center gap-1.5">
                                            <span>Tambah Pos Lainnya</span>
                                            <span class="px-1.5 py-0.5 rounded text-[10px] bg-blue-50 text-blue-700 font-semibold border border-blue-200">Bisa Langsung Ketik Bebas</span>
                                        </span>
                                        <p class="text-[11px] text-gray-500 mt-0.5">Ketik nama pos apa saja (misal: INFAQ, SERAGAM, KITAB, KAS) atau pilih rekomendasi yang otomatis muncul.</p>
                                    </div>
                                    <button type="button" @click="addCustomItem()" class="px-2.5 py-1 rounded-lg border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 text-xs font-semibold shadow-sm transition inline-flex items-center gap-1">
                                        <span>+ Tambah Baris</span>
                                    </button>
                                </div>

                                <datalist id="posBiayaDatalist">
                                    <template x-for="p in availablePos" :key="'dl_' + p">
                                        <option :value="p"></option>
                                    </template>
                                </datalist>

                                <div class="space-y-2">
                                    <template x-for="(c, cIdx) in customItems" :key="'cust_' + cIdx">
                                        <div class="flex flex-col sm:flex-row sm:items-center gap-2 p-2 bg-slate-50 hover:bg-gray-50 rounded-xl border border-gray-200 transition">
                                            <div class="flex-1 relative">
                                                <input type="text" 
                                                    :name="'items[custom_' + cIdx + '][pos_biaya]'" 
                                                    x-model="c.pos_biaya" 
                                                    list="posBiayaDatalist"
                                                    placeholder="Ketik nama pos di sini (misal: INFAQ, SERAGAM, UJIAN, LAINNYA)..." 
                                                    class="h-9 w-full uppercase rounded-lg border border-gray-300 px-3 text-xs font-bold text-gray-800 bg-white focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none placeholder:font-normal placeholder:normal-case placeholder:text-gray-400">
                                            </div>
                                            <div class="flex items-center gap-2 shrink-0">
                                                <div class="relative">
                                                    <span class="absolute inset-y-0 left-0 pl-2.5 flex items-center text-gray-400 font-bold text-xs pointer-events-none">Rp</span>
                                                    <input type="number" 
                                                        :name="'items[custom_' + cIdx + '][nominal]'" 
                                                        x-model="c.nominal" 
                                                        @input="uangDiterima = calculateTotal()"
                                                        placeholder="0" 
                                                        min="0" 
                                                        class="h-9 w-36 pl-8 pr-3 rounded-lg border border-gray-300 font-mono text-xs font-bold text-gray-900 bg-white focus:border-brand-500 outline-none">
                                                </div>
                                                <button type="button" @click="removeCustomItem(cIdx)" class="text-gray-400 hover:text-rose-600 p-1.5 rounded-lg text-base font-bold hover:bg-rose-50 transition leading-none" title="Hapus baris ini">
                                                    &times;
                                                </button>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                        </div>

                    </div>

                    <!-- STEP 3: PENARIKAN TABUNGAN SANTRI (SESUAI TEMPLATE TAILADMIN) -->
                    <div x-show="selectedPerson && paymentType === 'santri'" class="rounded-2xl border border-gray-200 bg-white p-5 sm:p-6 shadow-theme-xs space-y-4">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-gray-100 pb-3.5">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 flex items-center justify-center font-bold text-xs shrink-0">
                                    3
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                                        Penarikan Tabungan Santri
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-200">AMBIL TABUNGAN</span>
                                    </h3>
                                    <p class="text-xs text-gray-500">Fitur penarikan / pencairan saldo simpanan tabungan santri melalui kasir</p>
                                </div>
                            </div>
                            <div class="sm:text-right bg-gray-50 sm:bg-transparent p-2.5 sm:p-0 rounded-xl border sm:border-0 border-gray-200 flex sm:block items-center justify-between">
                                <span class="text-[10px] text-gray-500 uppercase font-bold tracking-wider block">Saldo Tabungan</span>
                                <div class="text-base font-mono font-bold text-emerald-700" x-text="formatRupiah(selectedPerson ? selectedPerson.saldo_tabungan : 0)"></div>
                            </div>
                        </div>

                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pt-1">
                            <div class="text-xs text-gray-600 leading-relaxed max-w-md">
                                Masukkan nominal saldo tabungan yang ditarik. Transaksi ini akan otomatis memotong saldo tabungan santri secara realtime setelah disimpan dan dicatat dalam kwitansi kasir.
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <span class="text-xs font-bold text-gray-500">Rp</span>
                                <input type="hidden" name="items[withdraw_tabungan][pos_biaya]" value="AMBIL TABUNGAN">
                                <div class="relative">
                                    <input type="number" name="items[withdraw_tabungan][nominal]" x-model="tabunganWithdrawal" placeholder="0" min="0" :max="selectedPerson ? selectedPerson.saldo_tabungan : 0" @input="uangDiterima = calculateTotal()" class="h-10 w-36 sm:w-40 rounded-lg border border-gray-300 px-3 text-sm font-bold font-mono text-gray-900 bg-white focus:border-brand-500 focus:ring-2 focus:ring-brand-500/10 outline-none text-right transition">
                                </div>
                                <button type="button" @click="tabunganWithdrawal = (selectedPerson ? selectedPerson.saldo_tabungan : 0); uangDiterima = calculateTotal();" class="h-10 px-3.5 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold transition shrink-0 active:scale-95" title="Tarik seluruh sisa saldo">
                                    Tarik Semua
                                </button>
                                <button type="button" @click="tabunganWithdrawal = ''; uangDiterima = calculateTotal();" class="h-10 w-10 rounded-lg bg-white hover:bg-gray-50 text-gray-400 hover:text-rose-600 border border-gray-300 text-base font-bold transition flex items-center justify-center shrink-0" title="Batal / Kosongkan">
                                    &times;
                                </button>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- KOLOM KANAN (5/12): STRUK KASIR & CHECKOUT COUNTER (STICKY) -->
                <div class="lg:col-span-5 sticky top-20 space-y-4">
                    
                    <div class="rounded-2xl border-2 border-emerald-600/30 bg-white p-5 sm:p-6 shadow-xl space-y-5">
                        
                        <!-- Header Struk Kasir -->
                        <div class="border-b border-gray-200 pb-3 flex items-center justify-between">
                            <div>
                                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">
                                    Struk Kasir Pesantren
                                </h3>
                                <p class="text-[11px] text-gray-500 mt-0.5">PP Hidayatullah Tuksongo POS</p>
                            </div>
                            <div class="text-right">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold" :class="paymentType === 'psb' ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200'" x-text="paymentType === 'psb' ? 'LOKET PSB' : 'LOKET SPP'"></span>
                            </div>
                        </div>

                        <!-- Itemized Struk Summary -->
                        <div class="space-y-2 border-b border-dashed border-gray-300 pb-4 text-xs">
                            <span class="text-[10px] font-bold uppercase text-gray-400 tracking-wider block mb-1">Rincian Pos yang Dibayar:</span>

                            <!-- If PSB -->
                            <template x-if="paymentType === 'psb' && psbChecked && psbNominalInput > 0">
                                <div class="flex items-center justify-between text-gray-800 py-1">
                                    <div>
                                        <strong class="font-mono text-purple-800">PENDAFTARAN PSB</strong>
                                        <span class="text-[11px] text-gray-500 block">Daftar Ulang / Pendaftaran PSB</span>
                                    </div>
                                    <span class="font-mono font-bold text-gray-900" x-text="formatRupiah(psbNominalInput)"></span>
                                </div>
                            </template>

                            <!-- If Santri: Bills yang nominalnya > 0 -->
                            <template x-for="b in studentBills.filter(x => x.nominal_input > 0)" :key="'struk_' + b.id">
                                <div class="flex items-center justify-between text-gray-800 py-1">
                                    <div>
                                        <strong class="font-mono text-emerald-800" x-text="b.pos_biaya"></strong>
                                        <span class="text-[11px] text-gray-500 block truncate max-w-[170px]" x-text="b.judul_tagihan"></span>
                                    </div>
                                    <span class="font-mono font-bold text-gray-900" x-text="formatRupiah(b.nominal_input)"></span>
                                </div>
                            </template>

                            <!-- Advance items yang nominalnya > 0 -->
                            <template x-for="a in advanceItems.filter(x => x.nominal > 0)" :key="a.keterangan">
                                <div class="flex items-center justify-between text-gray-800 py-1">
                                    <div>
                                        <strong class="font-mono text-teal-800" x-text="a.pos_biaya"></strong>
                                        <span class="text-[11px] text-teal-600 block" x-text="'Di muka: ' + a.bulan + ' ' + a.tahun"></span>
                                    </div>
                                    <span class="font-mono font-bold text-gray-900" x-text="formatRupiah(a.nominal)"></span>
                                </div>
                            </template>

                            <!-- Custom items yang nominalnya > 0 -->
                            <template x-for="c in customItems.filter(x => x.nominal > 0)" :key="c.pos_biaya">
                                <div class="flex items-center justify-between text-gray-800 py-1">
                                    <strong class="font-mono text-blue-800" x-text="c.pos_biaya"></strong>
                                    <span class="font-mono font-bold text-gray-900" x-text="formatRupiah(c.nominal)"></span>
                                </div>
                            </template>

                            <!-- Penarikan Tabungan (Jika ada) -->
                            <div x-show="tabunganWithdrawal > 0" class="flex items-center justify-between text-amber-800 py-1 mt-1 border-t border-dashed border-amber-200">
                                <div>
                                    <strong class="font-mono text-amber-800">AMBIL TABUNGAN</strong>
                                    <span class="text-[11px] text-amber-600 block">Penarikan / Pengeluaran Saldo Santri</span>
                                </div>
                                <span class="font-mono font-bold text-amber-900" x-text="formatRupiah(tabunganWithdrawal)"></span>
                            </div>

                            <div x-show="calculateTotal() === 0" class="py-3 text-center text-gray-400 text-[11px] italic">
                                Belum ada pos tagihan yang dipilih.
                            </div>
                        </div>

                        <!-- TOTAL REKAPITULASI -->
                        <div class="p-3.5 rounded-xl bg-gray-50 border border-gray-200 space-y-1">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-gray-700">Total Bayar:</span>
                                <span class="text-xl font-bold font-mono text-gray-900" x-text="formatRupiah(calculateTotal())"></span>
                            </div>
                        </div>

                        <!-- Parameter Pembayaran Kasir -->
                        <div class="space-y-3 text-xs">
                            
                            <!-- Tanggal Bayar -->
                            <div>
                                <label class="block font-medium text-gray-700 mb-1">Tanggal Transaksi *</label>
                                <input type="date" name="tanggal_bayar" x-model="tanggalBayar" required class="w-full py-2 px-3 rounded-lg border border-gray-300 text-xs font-medium text-gray-800 focus:border-brand-500 outline-none">
                            </div>

                            <!-- Metode Bayar (Pills Radio) -->
                            <div>
                                <label class="block font-medium text-gray-700 mb-1.5">Metode Pembayaran *</label>
                                <div class="grid grid-cols-2 gap-2">
                                    <label class="flex items-center justify-center p-2 rounded-lg border cursor-pointer font-semibold text-xs transition" :class="metodeBayar === 'Tunai' ? 'bg-gray-900 text-white border-gray-900 shadow-xs' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'">
                                        <input type="radio" name="metode_pembayaran" value="Tunai" x-model="metodeBayar" class="hidden">
                                        <span>Tunai (Cash)</span>
                                    </label>
                                    <label class="flex items-center justify-center p-2 rounded-lg border cursor-pointer font-semibold text-xs transition" :class="metodeBayar === 'Transfer Bank' ? 'bg-gray-900 text-white border-gray-900 shadow-xs' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'">
                                        <input type="radio" name="metode_pembayaran" value="Transfer Bank" x-model="metodeBayar" class="hidden">
                                        <span>Transfer Bank</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Kalkulator Tunai & Kembalian (Jika Tunai) -->
                            <div x-show="metodeBayar === 'Tunai'" class="p-3 bg-gray-50 rounded-lg border border-gray-200 space-y-2">
                                <label class="block font-medium text-gray-700 text-xs">Uang Diterima &amp; Kembalian</label>
                                
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 font-bold pointer-events-none">Rp</span>
                                    <input type="number" x-model="uangDiterima" placeholder="Nominal uang diterima" class="w-full pl-9 pr-3 py-2 rounded-lg border border-gray-300 font-mono font-bold text-sm text-gray-900 focus:border-brand-500 outline-none">
                                </div>

                                <!-- Quick Cash Chips -->
                                <div class="flex flex-wrap gap-1">
                                    <button type="button" @click="setUangPas()" class="px-2 py-1 rounded border border-gray-300 bg-white text-gray-700 font-medium text-[11px] hover:bg-gray-50">
                                        Uang Pas
                                    </button>
                                    <button type="button" @click="addUang(50000)" class="px-2 py-1 rounded border border-gray-300 bg-white text-gray-700 font-medium text-[11px] hover:bg-gray-50">
                                        +50k
                                    </button>
                                    <button type="button" @click="addUang(100000)" class="px-2 py-1 rounded border border-gray-300 bg-white text-gray-700 font-medium text-[11px] hover:bg-gray-50">
                                        +100k
                                    </button>
                                    <button type="button" @click="addUang(200000)" class="px-2 py-1 rounded border border-gray-300 bg-white text-gray-700 font-medium text-[11px] hover:bg-gray-50">
                                        +200k
                                    </button>
                                    <button type="button" @click="addUang(500000)" class="px-2 py-1 rounded border border-gray-300 bg-white text-gray-700 font-medium text-[11px] hover:bg-gray-50">
                                        +500k
                                    </button>
                                </div>

                                <!-- Display Kembalian -->
                                <div class="pt-1 flex items-center justify-between border-t border-gray-200 text-xs">
                                    <span class="text-gray-500 font-medium" x-text="kembalian >= 0 ? 'Kembalian:' : 'Kekurangan:'"></span>
                                    <span class="font-mono font-bold text-sm" :class="kembalian >= 0 ? 'text-emerald-700' : 'text-rose-600'" x-text="formatRupiah(Math.abs(kembalian))"></span>
                                </div>
                            </div>

                            <!-- Upload Bukti Transfer (Jika Transfer) -->
                            <div x-show="metodeBayar === 'Transfer Bank'" class="p-3 bg-gray-50 rounded-lg border border-gray-200 space-y-2">
                                <label class="block font-medium text-gray-700 text-xs">Unggah Bukti Transfer Bank</label>
                                <input type="file" name="bukti_file" accept="image/*" class="w-full text-xs text-gray-600 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-gray-900 file:text-white hover:file:bg-gray-800 cursor-pointer">
                                <p class="text-[10px] text-gray-400">Format gambar JPG, PNG, atau WEBP maks 5 MB.</p>
                            </div>

                            <!-- Keterangan Periode & Catatan -->
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block font-medium text-gray-700 mb-1">Singkatan Periode</label>
                                    <input type="text" name="keterangan_periode" x-model="keteranganPeriode" placeholder="Bulan / Periode" class="w-full py-1.5 px-2.5 rounded-lg border border-gray-300 text-xs font-medium text-gray-800 outline-none">
                                </div>
                                <div>
                                    <label class="block font-medium text-gray-700 mb-1">Catatan</label>
                                    <input type="text" name="catatan" x-model="catatan" placeholder="Opsional" class="w-full py-1.5 px-2.5 rounded-lg border border-gray-300 text-xs text-gray-800 outline-none">
                                </div>
                            </div>

                        </div>

                        <!-- SUBMIT BUTTON BESAR -->
                        <button type="submit" :disabled="!selectedPerson || calculateTotal() <= 0" class="w-full py-3 px-4 rounded-lg bg-emerald-600 hover:bg-emerald-700 disabled:opacity-50 disabled:cursor-not-allowed text-white font-semibold text-xs shadow-theme-xs transition flex items-center justify-center gap-2 cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Simpan Pembayaran &amp; Cetak Kwitansi</span>
                        </button>

                    </div>

                </div>

            </div>
        </form>

    </div>

    <!-- ========================================================================= -->
    <!-- TAB 2: RIWAYAT PEMBAYARAN KASIR SANTRI                                     -->
    <!-- ========================================================================= -->
    <div x-show="activeTab === 'santri'" class="space-y-4" x-data="{
        selectedPayments: [],
        selectAll: false,
        allIds: {{ json_encode($studentPayments->pluck('id')) }},
        toggleSelectAll() {
            if (this.selectAll) {
                this.selectedPayments = [...this.allIds];
            } else {
                this.selectedPayments = [];
            }
        },
        updateSelectAllState() {
            this.selectAll = this.allIds.length > 0 && this.selectedPayments.length === this.allIds.length;
        }
    }">
        <!-- Mini Stats Pembayaran Jenjang MTs vs MA -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div class="p-4 bg-white rounded-2xl border border-gray-200 flex items-center justify-between shadow-theme-xs">
                <div>
                    <span class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider block">Total Masuk Santri</span>
                    <span class="font-mono font-bold text-base text-gray-900">Rp {{ number_format($totalPemasukanSpp, 0, ',', '.') }}</span>
                </div>
                <span class="px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 font-bold text-xs border border-emerald-200">Total</span>
            </div>
            <div class="p-4 bg-white rounded-2xl border border-sky-200 flex items-center justify-between shadow-theme-xs">
                <div>
                    <span class="text-[11px] font-semibold text-sky-700 uppercase tracking-wider block">Pemasukan MTs</span>
                    <span class="font-mono font-bold text-base text-sky-900">Rp {{ number_format($totalPemasukanSppMts ?? 0, 0, ',', '.') }}</span>
                </div>
                <span class="px-2.5 py-1 rounded-lg bg-sky-50 text-sky-700 font-bold text-xs border border-sky-200">MTs</span>
            </div>
            <div class="p-4 bg-white rounded-2xl border border-emerald-200 flex items-center justify-between shadow-theme-xs">
                <div>
                    <span class="text-[11px] font-semibold text-emerald-700 uppercase tracking-wider block">Pemasukan MA</span>
                    <span class="font-mono font-bold text-base text-emerald-900">Rp {{ number_format($totalPemasukanSppMa ?? 0, 0, ',', '.') }}</span>
                </div>
                <span class="px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 font-bold text-xs border border-emerald-200">MA</span>
            </div>
        </div>

        <!-- Filter Bar (TailAdmin Form Style) -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-xs space-y-3">
            <form method="GET" action="{{ route('admin.pembayaran.index') }}" class="space-y-3">
                <input type="hidden" name="tab" value="santri">
                
                <div class="grid grid-cols-1 sm:grid-cols-12 gap-3">
                    <div class="sm:col-span-4">
                        <label class="block text-[11px] font-semibold text-gray-600 mb-1">Cari Santri / NIS / No. Kwitansi</label>
                        <input type="text" name="q_santri" value="{{ request('q_santri') }}" placeholder="Cari santri, NIS, kelas, no kwitansi..." class="h-10 w-full rounded-lg border border-gray-300 px-3 text-xs text-gray-800 placeholder:text-gray-400 focus:border-brand-500 outline-none">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-[11px] font-semibold text-gray-600 mb-1">Filter Kelas</label>
                        <select name="kelas" class="h-10 w-full rounded-lg border border-gray-300 px-3 text-xs text-gray-800 focus:border-brand-500 outline-none">
                            <option value="">Semua Kelas</option>
                            @foreach($classrooms as $cls)
                                <option value="{{ $cls->nama_kelas }}" {{ request('kelas') == $cls->nama_kelas ? 'selected' : '' }}>
                                    {{ $cls->nama_kelas }} ({{ $cls->jenjang }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-[11px] font-semibold text-gray-600 mb-1">Pos Biaya</label>
                        <input type="text" name="jenis_pembayaran" value="{{ request('jenis_pembayaran') }}" placeholder="MAKAN, SYAHRIYAH..." class="h-10 w-full rounded-lg border border-gray-300 px-3 text-xs text-gray-800 focus:border-brand-500 outline-none">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-[11px] font-semibold text-gray-600 mb-1">Mulai Tgl</label>
                        <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}" class="h-10 w-full rounded-lg border border-gray-300 px-2.5 text-xs text-gray-800 focus:border-brand-500 outline-none">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-[11px] font-semibold text-gray-600 mb-1">Sampai Tgl</label>
                        <input type="date" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}" class="h-10 w-full rounded-lg border border-gray-300 px-2.5 text-xs text-gray-800 focus:border-brand-500 outline-none">
                    </div>
                </div>

                <div class="flex flex-wrap items-center justify-between gap-3 pt-2 border-t border-gray-100">
                    <div class="flex items-center gap-2">
                        <button type="submit" class="h-9 px-4 rounded-lg bg-gray-900 text-white text-xs font-semibold hover:bg-gray-800 transition flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                            <span>Terapkan Filter</span>
                        </button>
                        <a href="{{ route('admin.pembayaran.index', ['tab' => 'santri']) }}" class="h-9 px-3 rounded-lg border border-gray-300 bg-white text-gray-600 text-xs font-medium flex items-center justify-center hover:bg-gray-50 transition">
                            Reset
                        </a>
                    </div>

                    <div class="flex items-center gap-2">
                        <!-- Rekap Register Kwitansi -->
                        <a href="{{ route('admin.pembayaran.rekapKwitansi', request()->except('tab') + ['tab' => 'santri']) }}" target="_blank" class="h-9 px-3.5 rounded-lg bg-teal-50 border border-teal-300 text-teal-800 text-xs font-bold hover:bg-teal-100 transition flex items-center gap-1.5 shadow-2xs" title="Buka Rekapitulasi Register Kwitansi Sesuai Filter">
                            <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span>Rekap Register Kwitansi</span>
                        </a>

                        <!-- Cetak Kwitansi Massal Sesuai Filter -->
                        <a href="{{ route('admin.pembayaran.kwitansiMassal', request()->except('tab') + ['tab' => 'santri']) }}" target="_blank" class="h-9 px-3.5 rounded-lg bg-[#208075] hover:bg-[#18665e] text-white text-xs font-bold transition flex items-center gap-1.5 shadow-xs" title="Cetak Seluruh Kwitansi Sesuai Filter Sekaligus">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            <span>Cetak Kwitansi Massal</span>
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Tabel Riwayat Pembayaran Santri (TailAdmin Table) -->
        <div class="rounded-2xl border border-gray-200 bg-white shadow-theme-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-200 bg-gray-50/70 text-xs font-semibold uppercase tracking-wider text-gray-500">
                            <th class="py-3.5 px-3 w-10 text-center">
                                <input type="checkbox" x-model="selectAll" @change="toggleSelectAll" class="rounded border-gray-300 text-brand-600 focus:ring-brand-500 cursor-pointer" title="Pilih Semua di Halaman Ini">
                            </th>
                            <th class="py-3.5 px-4">No. Kwitansi &amp; Tgl</th>
                            <th class="py-3.5 px-4">Santri &amp; Kelas</th>
                            <th class="py-3.5 px-4">Rincian Pos Pembayaran</th>
                            <th class="py-3.5 px-4">Keterangan</th>
                            <th class="py-3.5 px-4">Total Nominal</th>
                            <th class="py-3.5 px-4">Metode</th>
                            <th class="py-3.5 px-4">Penerima</th>
                            <th class="py-3.5 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-xs text-gray-700">
                        @forelse($studentPayments as $p)
                        <tr class="hover:bg-gray-50/80 transition" :class="selectedPayments.includes({{ $p->id }}) ? 'bg-teal-50/40' : ''">
                            <td class="py-3.5 px-3 text-center">
                                <input type="checkbox" value="{{ $p->id }}" x-model="selectedPayments" @change="updateSelectAllState" class="rounded border-gray-300 text-brand-600 focus:ring-brand-500 cursor-pointer">
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-mono font-bold text-gray-900 block">{{ $p->no_transaksi }}</span>
                                <span class="text-[11px] text-gray-400">{{ optional($p->tanggal_bayar)->format('d/m/Y') }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-gray-900 text-sm">
                                    {{ $p->student->nama_lengkap ?? ($p->psbRegistration->nama_lengkap ?? ($p->penerima_nama ?: '—')) }}
                                </div>
                                <div class="text-[11px] text-gray-400">
                                    @if($p->student)
                                        NIS: {{ $p->student->nis ?? '—' }} &bull; 
                                        <span class="font-semibold text-blue-600">Kls {{ $p->student->kelas ?? '—' }}</span> &bull; 
                                        <span class="inline-flex items-center px-1.5 py-0.2 rounded text-[10px] font-extrabold {{ $p->student->hunian === 'Mukim' ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-100 text-blue-800' }}">
                                            {{ $p->student->kategori_label }}
                                        </span>
                                    @elseif($p->psbRegistration)
                                        <span class="font-mono text-amber-700">PSB: {{ $p->psbRegistration->no_registrasi }}</span> &bull; 
                                        <span class="font-semibold text-blue-600">{{ $p->psbRegistration->jenjang }}</span>
                                    @else
                                        <span class="text-gray-400">Umum / Santri</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                @if($p->items->isNotEmpty())
                                    <div class="flex flex-wrap gap-1 max-w-xs">
                                        @foreach($p->items as $it)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                                <strong>{{ $it->pos_biaya }}</strong>: Rp {{ number_format($it->nominal, 0, ',', '.') }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                        {{ $p->jenis_pembayaran }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="text-xs text-gray-600 block">{{ $p->catatan ?: ($p->bulan ? "{$p->bulan} {$p->tahun}" : '—') }}</span>
                                @if($p->status === 'Menunggu Konfirmasi')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-amber-100 text-amber-900 border border-amber-300 mt-1">
                                        Menunggu Verifikasi
                                    </span>
                                    @if($p->bukti_bayar)
                                        <button type="button" @click="previewImgUrl = '{{ asset($p->bukti_bayar) }}'; previewTitle = 'Bukti Transfer: {{ addslashes($p->student->nama_lengkap ?? ($p->psbRegistration->nama_lengkap ?? 'Santri')) }}'; modalPreviewFoto = true" class="block text-[10px] font-medium text-brand-600 hover:underline mt-0.5">
                                            Lihat Bukti Transfer
                                        </button>
                                    @endif
                                @elseif($p->status === 'Ditolak')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-rose-100 text-rose-800 border border-rose-200 mt-1">
                                        Ditolak
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-emerald-100 text-emerald-800 border border-emerald-200 mt-1">
                                        Lunas
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 font-bold text-gray-900 text-sm font-mono">
                                {{ $p->formatted_nominal }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium {{ $p->metode_pembayaran === 'Transfer Bank' ? 'bg-blue-50 text-blue-700' : 'bg-gray-100 text-gray-700' }}">
                                    {{ $p->metode_pembayaran }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-gray-500 text-[11px]">
                                {{ $p->penerima_nama ?: 'Bendahara' }}
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    @if($p->status === 'Menunggu Konfirmasi')
                                        <form action="{{ route('admin.pembayaran.konfirmasi', $p->id) }}" method="POST" class="inline" onsubmit="return confirm('Konfirmasi dan setujui pembayaran {{ $p->no_transaksi }} atas nama {{ addslashes($p->student->nama_lengkap ?? ($p->psbRegistration->nama_lengkap ?? 'Santri')) }} sebagai LUNAS?');">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-medium shadow-theme-xs transition" title="Setujui Pembayaran">
                                                Setujui
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.pembayaran.tolak', $p->id) }}" method="POST" class="inline" onsubmit="return confirm('Tolak bukti transfer {{ $p->no_transaksi }}?');">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center gap-1 px-2 py-1 bg-white hover:bg-gray-50 text-rose-600 border border-rose-200 rounded-lg text-xs font-medium transition" title="Tolak Bukti Transfer">
                                                Tolak
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route('admin.pembayaran.kwitansi', $p->id) }}" target="_blank" class="inline-flex items-center gap-1 px-2.5 py-1 bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 rounded-lg text-xs font-medium transition" title="Cetak Kwitansi">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                            Kwitansi
                                        </a>

                                        @php
                                            $st = $p->student;
                                            $psb = $p->psbRegistration;
                                            $namaSantri = $st ? ($st->nama_lengkap ?? 'Santri') : ($psb ? ($psb->nama_lengkap ?? 'Calon Santri') : ($p->penerima_nama ?: 'Santri'));
                                            $infoKelas = $st ? " (Kelas {$st->kelas})" : ($psb ? " (PSB {$psb->jenjang})" : "");
                                            $waNum = $st ? ($st->no_whatsapp ?: ($st->no_hp ?: $st->no_hp_wali)) : ($psb ? ($psb->no_whatsapp ?? ($psb->no_hp ?? null)) : null);
                                            $cleanWa = $waNum ? preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $waNum)) : '';
                                            $itemLines = [];
                                            foreach($p->items as $it) {
                                                $itemLines[] = "- {$it->pos_biaya}: Rp " . number_format($it->nominal, 0, ',', '.');
                                            }
                                            $rincianStr = count($itemLines) > 0 ? implode("\n", $itemLines) : "- {$p->jenis_pembayaran}: {$p->formatted_nominal}";
                                            $kwitansiUrl = route('admin.pembayaran.kwitansi', $p->id);
                                            $waText = "Assalamu'alaikum Wr. Wb.\n\n"
                                                . "Yth. Wali Santri dari *{$namaSantri}*{$infoKelas}\n\n"
                                                . "Pembayaran pesantren telah diterima dan diverifikasi:\n\n"
                                                . "No. Transaksi: {$p->no_transaksi}\n"
                                                . "Tanggal: " . optional($p->tanggal_bayar)->format('d/m/Y') . "\n"
                                                . "Total: {$p->formatted_nominal} ({$p->metode_pembayaran})\n"
                                                . "Penerima: " . ($p->penerima_nama ?: 'Bendahara Pesantren') . "\n\n"
                                                . "Rincian Pembayaran:\n{$rincianStr}\n\n"
                                                . "Bukti Kwitansi:\n{$kwitansiUrl}\n\n"
                                                . "Terima kasih.\n"
                                                . "Bendahara Pondok Pesantren Hidayatullah Tuksongo";
                                            $waUrlKasir = $cleanWa ? "https://wa.me/{$cleanWa}?text=" . rawurlencode($waText) : null;
                                        @endphp

                                        @if($waUrlKasir)
                                            <a href="{{ $waUrlKasir }}" target="_blank" class="inline-flex items-center gap-1 px-2.5 py-1 bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 rounded-lg text-xs font-medium transition" title="Kirim Kwitansi via WhatsApp">
                                                WA
                                            </a>
                                        @endif

                                        <form action="{{ route('admin.pembayaran.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan kwitansi {{ $p->no_transaksi }}? Saldo tagihan santri akan dikembalikan.');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Batalkan Transaksi">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="py-8 text-center text-gray-400">
                                Belum ada riwayat pembayaran santri yang dicatat.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($studentPayments->hasPages())
            <div class="p-4 border-t border-gray-100">
                {{ $studentPayments->links() }}
            </div>
            @endif
        </div>

        <!-- FLOATING BAR FOR BULK ACTIONS (TailAdmin / Modern Floating Pill) -->
        <div x-show="selectedPayments.length > 0" 
             x-transition:enter="transition ease-out duration-200" 
             x-transition:enter-start="opacity-0 translate-y-4" 
             x-transition:enter-end="opacity-100 translate-y-0" 
             x-transition:leave="transition ease-in duration-150" 
             x-transition:leave-start="opacity-100 translate-y-0" 
             x-transition:leave-end="opacity-0 translate-y-4" 
             class="fixed bottom-6 left-1/2 transform -translate-x-1/2 z-50 bg-gray-900/95 text-white px-5 py-3 rounded-2xl shadow-2xl flex flex-wrap items-center gap-4 border border-gray-700 backdrop-blur-md">
            <div class="flex items-center gap-2.5">
                <span class="w-7 h-7 rounded-full bg-[#208075] text-white font-black text-xs flex items-center justify-center font-mono" x-text="selectedPayments.length"></span>
                <span class="text-xs font-bold text-gray-100">Kwitansi Dipilih</span>
            </div>
            
            <div class="h-4 w-px bg-gray-700 hidden sm:block"></div>

            <div class="flex items-center gap-2">
                <!-- Cetak Kwitansi Terpilih -->
                <a :href="'{{ route('admin.pembayaran.kwitansiMassal') }}?ids=' + selectedPayments.join(',')" target="_blank" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-[#208075] hover:bg-[#18665e] text-white text-xs font-bold shadow-md shadow-[#208075]/30 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>Cetak Kwitansi Terpilih</span>
                </a>

                <!-- Rekap Register Terpilih -->
                <a :href="'{{ route('admin.pembayaran.rekapKwitansi') }}?ids=' + selectedPayments.join(',')" target="_blank" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-teal-700 hover:bg-teal-800 text-white text-xs font-bold transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Rekap Register Terpilih</span>
                </a>

                <!-- Batalkan Pilihan -->
                <button type="button" @click="selectedPayments = []; selectAll = false" class="px-2.5 py-1.5 text-xs text-gray-400 hover:text-white transition">
                    Batalkan
                </button>
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- TAB 3: VERIFIKASI PEMBAYARAN PSB                                          -->
    <!-- ========================================================================= -->
    <div x-show="activeTab === 'psb'" class="space-y-4">
        <!-- Filter Bar (TailAdmin Form Style) -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-xs">
            <form method="GET" action="{{ route('admin.pembayaran.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3">
                <input type="hidden" name="tab" value="psb">
                <div class="sm:col-span-5">
                    <input type="text" name="q_psb" value="{{ request('q_psb') }}" placeholder="Cari calon santri, no registrasi, WA..." class="h-10 w-full rounded-lg border border-gray-300 px-3 text-xs text-gray-800 placeholder:text-gray-400 focus:border-brand-500 outline-none">
                </div>
                <div class="sm:col-span-3">
                    <select name="filter_kelulusan" class="h-10 w-full rounded-lg border border-gray-300 bg-white px-3 text-xs text-gray-800 focus:border-brand-500 font-medium cursor-pointer">
                        <option value="lulus" {{ ($filterKelulusan ?? 'lulus') === 'lulus' ? 'selected' : '' }}>Hanya Lulus / Diterima</option>
                        <option value="semua" {{ ($filterKelulusan ?? '') === 'semua' ? 'selected' : '' }}>Tampilkan Semua Calon Santri</option>
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <select name="status_psb" class="h-10 w-full rounded-lg border border-gray-300 bg-white px-3 text-xs text-gray-800 focus:border-brand-500 cursor-pointer">
                        <option value="">Semua Status Bayar</option>
                        <option value="Lunas" {{ request('status_psb') === 'Lunas' ? 'selected' : '' }}>Lunas</option>
                        <option value="Menunggu Verifikasi" {{ request('status_psb') === 'Menunggu Verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                        <option value="Belum Bayar" {{ request('status_psb') === 'Belum Bayar' ? 'selected' : '' }}>Belum Bayar</option>
                    </select>
                </div>
                <div class="sm:col-span-2 flex items-center gap-1.5">
                    <button type="submit" class="flex-1 h-10 rounded-lg bg-gray-900 text-white text-xs font-semibold hover:bg-gray-800 transition">
                        Filter
                    </button>
                    <a href="{{ route('admin.pembayaran.index', ['tab' => 'psb']) }}" class="px-3 h-10 rounded-lg border border-gray-300 bg-white text-gray-600 text-xs font-medium flex items-center justify-center hover:bg-gray-50 transition">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Info Banner Kelulusan PSB (TailAdmin Alert) -->
        <div class="p-4 bg-emerald-50/80 border border-emerald-200 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-emerald-950 shadow-theme-xs">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0 font-bold text-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 14l9-5-9-5-9 5 9 5z"/><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                </div>
                <div>
                    @if(($filterKelulusan ?? 'lulus') === 'lulus')
                        Menampilkan calon santri yang <strong>Lulus Seleksi CBT (Nilai &ge; {{ $kkm ?? 70 }}) &amp; Membayar Infaq</strong> atau yang <strong>Diluluskan oleh Admin</strong>.
                    @else
                        Menampilkan <strong>Seluruh Data Pendaftar PSB</strong> (termasuk yang belum lulus atau belum ujian).
                    @endif
                </div>
            </div>
            <div class="flex items-center gap-2">
                @php
                    $psbPendingTfBendahara = \App\Models\PsbRegistration::whereNotNull('bukti_transfer')
                        ->where('bukti_transfer', '!=', '')
                        ->where(function($q) {
                            $q->where('status_pembayaran', '!=', 'Lunas')->orWhereNull('status_pembayaran');
                        })->count();
                @endphp
                @if($psbPendingTfBendahara > 0)
                    <form action="{{ route('admin.pembayaran.psbBulkVerify') }}" method="POST" class="inline" onsubmit="return confirm('Verifikasi pembayaran pendaftaran (Rp 200.000 Lunas) untuk SELURUH {{ $psbPendingTfBendahara }} calon santri yang telah mengunggah bukti transfer?')">
                        @csrf
                        <input type="hidden" name="mode" value="all_with_proof">
                        <button type="submit" class="px-3 py-1.5 rounded-lg bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs shadow-theme-xs transition inline-flex items-center gap-1.5 cursor-pointer" title="Verifikasi serentak semua yang sudah upload bukti transfer">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>⚡ Verifikasi Semua Slip TF ({{ $psbPendingTfBendahara }})</span>
                        </button>
                    </form>
                @endif
                <span class="px-2.5 py-1 rounded-lg bg-white border border-emerald-200 text-emerald-800 font-semibold text-xs shrink-0">
                    {{ $psbPayments->total() }} Calon Santri
                </span>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white shadow-theme-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-200 bg-gray-50/70 text-xs font-semibold uppercase tracking-wider text-gray-500">
                            <th class="py-3.5 px-5">No. Reg &amp; Santri</th>
                            <th class="py-3.5 px-5">Jenjang</th>
                            <th class="py-3.5 px-5">Status Kelulusan</th>
                            <th class="py-3.5 px-5">Nominal &amp; Metode</th>
                            <th class="py-3.5 px-5">Bukti Transfer / Kwitansi</th>
                            <th class="py-3.5 px-5">Status Bayar</th>
                            <th class="py-3.5 px-5 text-right">Verifikasi &amp; Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-xs text-gray-700">
                        @forelse($psbPayments as $psb)
                        <tr class="hover:bg-gray-50/80 transition">
                            <td class="py-3.5 px-5">
                                <div class="font-bold text-gray-900 text-sm">{{ $psb->nama_lengkap }}</div>
                                <div class="text-[11px] text-gray-400 font-mono">No: {{ $psb->no_registrasi }} &bull; WA: {{ $psb->no_whatsapp }}</div>
                            </td>
                            <td class="py-3.5 px-5">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $psb->jenjang === 'MA' ? 'bg-purple-50 text-purple-700' : 'bg-blue-50 text-blue-700' }}">
                                    {{ $psb->jenjang }}
                                </span>
                            </td>
                            <td class="py-3.5 px-5">
                                @if($psb->status === 'Diterima')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        Diterima Admin
                                    </span>
                                @elseif($psb->status_kelulusan_override === 'Lulus' || $psb->status_kelulusan_override === 'Lulus Bersyarat')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        {{ $psb->status_kelulusan_override }}
                                    </span>
                                @elseif($psb->status_ujian === 'Selesai' && $psb->nilai_ujian !== null && $psb->nilai_ujian >= ($kkm ?? 70))
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-blue-100 text-blue-800 border border-blue-200">
                                        Lulus CBT: {{ $psb->nilai_ujian }}
                                    </span>
                                @elseif($psb->status === 'Ditolak' || $psb->status_kelulusan_override === 'Tidak Lulus')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-rose-100 text-rose-800 border border-rose-200">
                                        Tidak Lolos
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-gray-100 text-gray-700">
                                        Menunggu Seleksi
                                    </span>
                                @endif

                                @if($psb->status_ujian === 'Selesai' && $psb->nilai_ujian !== null)
                                    <span class="text-[10px] text-gray-400 font-mono block mt-0.5">Nilai CBT: {{ $psb->nilai_ujian }} (KKM: {{ $kkm ?? 70 }})</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-5">
                                <div class="font-mono font-bold text-gray-900 text-sm">
                                    Rp {{ number_format($psb->nominal_pembayaran ?: 200000, 0, ',', '.') }}
                                </div>
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium {{ $psb->metode_pembayaran === 'Transfer Bank' ? 'bg-blue-50 text-blue-700' : 'bg-gray-100 text-gray-700' }}">
                                    {{ $psb->metode_pembayaran ?: 'Tunai' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-5">
                                @if($psb->bukti_transfer)
                                    <button type="button" @click="previewImgUrl = '{{ asset($psb->bukti_transfer) }}'; previewTitle = 'Bukti Bayar PSB: {{ addslashes($psb->nama_lengkap) }}'; modalPreviewFoto = true" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-white hover:bg-gray-50 text-brand-600 border border-gray-300 text-xs font-medium transition">
                                        Lihat Bukti
                                    </button>
                                @else
                                    <span class="text-gray-400 italic text-[11px]">Belum Upload</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-5">
                                @if($psb->status_pembayaran === 'Lunas')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        Lunas
                                    </span>
                                @elseif($psb->bukti_transfer)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-amber-100 text-amber-900 border border-amber-300">
                                        Menunggu Verifikasi
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-rose-100 text-rose-800 border border-rose-200">
                                        Belum Bayar
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-5 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    @if($psb->status_pembayaran === 'Lunas')
                                        @if($psb->student)
                                            <a href="{{ route('admin.siswa.show', $psb->student->id) }}" class="inline-flex items-center gap-1 px-2.5 py-1 bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 rounded-lg text-xs font-medium transition" title="Lihat Profil Santri Aktif">
                                                Santri Aktif (NIS: {{ $psb->student->nis }})
                                            </a>
                                        @else
                                            <form action="{{ route('admin.siswa.importPsb', $psb->id) }}" method="POST" class="inline" onsubmit="return confirm('Daftarkan calon santri {{ addslashes($psb->nama_lengkap) }} menjadi Santri Aktif Pondok?');">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-medium shadow-theme-xs transition cursor-pointer" title="Konversi langsung ke data Santri Aktif">
                                                    Jadikan Santri Aktif
                                                </button>
                                            </form>
                                        @endif

                                        <a href="{{ route('admin.pembayaran.psb.kwitansi', $psb->id) }}" target="_blank" class="inline-flex items-center gap-1 px-2.5 py-1 bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 rounded-lg text-xs font-medium transition" title="Cetak Bukti Setoran Resmi">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                            Bukti Setoran
                                        </a>

                                        @php
                                            $cleanWaPsb = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $psb->no_whatsapp));
                                            $nominalPsb = number_format($psb->nominal_pembayaran ?: 200000, 0, ',', '.');
                                            $metodePsb = $psb->metode_pembayaran ?: 'Tunai';
                                            $kwitansiPsbUrl = route('admin.pembayaran.psb.kwitansi', $psb->id);
                                            $waMsgPsb = "Assalamu'alaikum Wr. Wb.\n\n"
                                                . "Yth. Orang Tua / Wali Calon Santri: *{$psb->nama_lengkap}*\n"
                                                . "No. Registrasi: *{$psb->no_registrasi}* ({$psb->jenjang})\n\n"
                                                . "Pembayaran pendaftaran PSB Pondok Pesantren Hidayatullah Tuksongo sebesar *Rp {$nominalPsb}* ({$metodePsb}) telah diterima dan lunas.\n\n"
                                                . "Bukti Setoran dapat diakses pada tautan berikut:\n{$kwitansiPsbUrl}\n\n"
                                                . "Terima kasih.\n"
                                                . "Panitia PSB & Bendahara Pondok Pesantren Hidayatullah Tuksongo";
                                            $waUrlPsb = $cleanWaPsb ? ("https://wa.me/{$cleanWaPsb}?text=" . rawurlencode($waMsgPsb)) : null;
                                        @endphp

                                        @if($waUrlPsb)
                                            <a href="{{ $waUrlPsb }}" target="_blank" class="inline-flex items-center gap-1 px-2.5 py-1 bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 rounded-lg text-xs font-medium transition" title="Kirim Konfirmasi via WA">
                                                WA
                                            </a>
                                        @endif

                                        <button type="button" @click="openPsbModal({{ $psb->id }}, '{{ addslashes($psb->nama_lengkap) }}', '{{ $psb->no_registrasi }}', '{{ $psb->jenjang }}', {{ $psb->nominal_pembayaran ?: 200000 }}, '{{ $psb->metode_pembayaran ?: 'Tunai' }}', '{{ $psb->tanggal_bayar ? date('Y-m-d', strtotime($psb->tanggal_bayar)) : date('Y-m-d') }}', '{{ addslashes($psb->catatan_pembayaran ?: '') }}', {{ $psb->bukti_transfer ? 'true' : 'false' }})" class="p-1.5 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition" title="Edit Data">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                        </button>

                                        <form action="{{ route('admin.pembayaran.psbVerify', $psb->id) }}" method="POST" class="inline" onsubmit="return confirm('Batalkan status lunas calon santri {{ addslashes($psb->nama_lengkap) }}?')">
                                            @csrf
                                            <input type="hidden" name="status" value="Belum Bayar">
                                            <button type="submit" class="px-2.5 py-1 text-rose-600 hover:bg-rose-50 rounded-lg text-xs font-medium border border-rose-200 transition" title="Batalkan Lunas">
                                                Batal Lunas
                                            </button>
                                        </form>
                                    @else
                                        <button type="button" @click="openPsbModal({{ $psb->id }}, '{{ addslashes($psb->nama_lengkap) }}', '{{ $psb->no_registrasi }}', '{{ $psb->jenjang }}', {{ $psb->nominal_pembayaran ?: 200000 }}, '{{ $psb->metode_pembayaran ?: 'Tunai' }}', '{{ date('Y-m-d') }}', '{{ addslashes($psb->catatan_pembayaran ?: '') }}', {{ $psb->bukti_transfer ? 'true' : 'false' }})" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-medium shadow-theme-xs transition inline-flex items-center gap-1.5">
                                            <span>Verifikasi Bayar</span>
                                        </button>

                                        @php
                                            $cleanWaPsb = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $psb->no_whatsapp));
                                            $nominalPsb = number_format($psb->nominal_pembayaran ?: 200000, 0, ',', '.');
                                            $rekBriNo = \App\Models\Setting::get('rek_admin_bri_no', '010201022009537');
                                            $rekBriAn = \App\Models\Setting::get('rek_admin_bri_an', 'DIKY FACHRI HUSEIN');
                                            $rekBcaNo = \App\Models\Setting::get('rek_admin_bca_no', '1221220167');
                                            $rekBcaAn = \App\Models\Setting::get('rek_admin_bca_an', 'DIKY FACHRI HUSEIN');
                                            $rekKonfPhone = \App\Models\Setting::get('rek_admin_konfirmasi_phone', '085290429617');
                                            $rekKonfNama = \App\Models\Setting::get('rek_admin_konfirmasi_nama', 'Ustdh. Harsih Nur A');
                                            $waRemindPsb = "Assalamu'alaikum Wr. Wb.\n\n"
                                                . "Yth. Orang Tua / Wali Calon Santri Baru: *{$psb->nama_lengkap}*\n"
                                                . "No. Registrasi: *{$psb->no_registrasi}* ({$psb->jenjang})\n\n"
                                                . "Mengingatkan untuk kelengkapan administrasi pembayaran biaya pendaftaran santri baru (PSB) sebesar *Rp {$nominalPsb}*.\n\n"
                                                . "Pembayaran dapat dilakukan di Kasir Kantor Pesantren (Tunai) atau melalui Transfer ke Rekening Resmi Administrasi:\n"
                                                . "• BRI: *{$rekBriNo}* (a.n. {$rekBriAn})\n"
                                                . "• BCA: *{$rekBcaNo}* (a.n. {$rekBcaAn})\n\n"
                                                . "KONFIRMASI BUKTI TRANSFER DENGAN MENYERTAKAN DATA SEBAGAI BERIKUT:\n"
                                                . "• NAMA : {$psb->nama_lengkap}\n"
                                                . "• NO REGISTRASI : {$psb->no_registrasi} ({$psb->jenjang})\n"
                                                . "• JENIS PEMBAYARAN : Biaya Pendaftaran Santri Baru (PSB)\n"
                                                . "• NOMINAL : Rp {$nominalPsb}\n\n"
                                                . "Kirim konfirmasi bukti transfer ke WA Keuangan: {$rekKonfPhone} ({$rekKonfNama})\n\n"
                                                . "Panitia PSB & Keuangan Pondok Pesantren Hidayatullah Tuksongo";
                                            $waRemindUrl = $cleanWaPsb ? ("https://wa.me/{$cleanWaPsb}?text=" . rawurlencode($waRemindPsb)) : null;
                                        @endphp

                                        @if($waRemindUrl)
                                            <a href="{{ $waRemindUrl }}" target="_blank" class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 rounded-lg text-xs font-medium transition" title="Ingatkan via WhatsApp">
                                                WA
                                            </a>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-gray-400">
                                Tidak ada data pembayaran PSB yang sesuai filter.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($psbPayments->hasPages())
            <div class="p-4 border-t border-gray-100">
                {{ $psbPayments->links() }}
            </div>
            @endif
        </div>
    </div>

    <!-- MODAL FORM VERIFIKASI PEMBAYARAN PSB -->
    <div x-show="modalPsbBayar" x-cloak class="ta-modal-backdrop">
        <div @click.away="modalPsbBayar = false" class="ta-modal max-w-lg">
            <div class="ta-modal-header">
                <div>
                    <h3 class="text-base font-bold text-gray-900">Verifikasi Pembayaran PSB</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Pencatatan pembayaran biaya masuk santri baru</p>
                </div>
                <button type="button" @click="modalPsbBayar = false" class="text-gray-400 hover:text-gray-600 text-lg">&times;</button>
            </div>

            <form :action="'{{ url('/admin/pembayaran/psb') }}/' + psbModalData.id + '/verify'" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="status" value="Lunas">

                <div class="ta-modal-body space-y-4 text-xs">
                    <!-- Santri Info -->
                    <div class="p-3 bg-gray-50 rounded-lg border border-gray-200 flex items-center justify-between">
                        <div>
                            <span class="text-xs font-semibold text-gray-900 block" x-text="psbModalData.nama"></span>
                            <span class="text-[11px] font-mono text-gray-500" x-text="'No: ' + psbModalData.no_reg + ' • Jenjang: ' + psbModalData.jenjang"></span>
                        </div>
                        <span class="px-2 py-0.5 rounded bg-white text-gray-700 border border-gray-200 font-medium text-[11px]" x-text="psbModalData.jenjang"></span>
                    </div>

                    <!-- Pilihan Metode Pembayaran -->
                    <div>
                        <label class="block font-medium text-gray-700 mb-1.5">Metode Pembayaran</label>
                        <div class="grid grid-cols-2 gap-2">
                            <button type="button" @click="psbModalData.metode = 'Tunai'" class="py-2 px-3 rounded-lg border text-xs font-medium transition text-center" :class="psbModalData.metode === 'Tunai' ? 'bg-gray-900 text-white border-gray-900 shadow-theme-xs' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'">
                                Tunai (Kasir)
                            </button>
                            <button type="button" @click="psbModalData.metode = 'Transfer Bank'" class="py-2 px-3 rounded-lg border text-xs font-medium transition text-center" :class="psbModalData.metode === 'Transfer Bank' ? 'bg-gray-900 text-white border-gray-900 shadow-theme-xs' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'">
                                Transfer Bank
                            </button>
                        </div>
                        <input type="hidden" name="metode_pembayaran" :value="psbModalData.metode">
                    </div>

                    <!-- Nominal Bayar -->
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">Nominal Pembayaran</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 font-medium text-xs pointer-events-none">Rp</span>
                            <input type="number" name="nominal_pembayaran" x-model="psbModalData.nominal" required class="w-full pl-9 pr-3 py-2 rounded-lg border border-gray-300 font-mono font-medium text-xs text-gray-900 focus:border-brand-500 outline-none">
                        </div>
                        <div class="flex items-center gap-1.5 mt-1.5">
                            <button type="button" @click="psbModalData.nominal = 3225000" class="px-2 py-0.5 rounded bg-gray-100 hover:bg-gray-200 text-gray-700 text-[10px] font-medium">
                                Standar (Rp 3.225.000)
                            </button>
                        </div>
                    </div>

                    <!-- Tanggal Bayar -->
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">Tanggal Bayar</label>
                        <input type="date" name="tanggal_bayar" x-model="psbModalData.tanggal" class="w-full px-3 py-2 rounded-lg border border-gray-300 text-xs text-gray-800 focus:border-brand-500 outline-none">
                    </div>

                    <!-- Upload Bukti Pembayaran -->
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">
                            Foto Bukti Transfer / Kwitansi Fisik <span class="text-gray-400 font-normal">(Opsional)</span>
                        </label>
                        <input type="file" name="bukti_transfer" accept="image/*" class="w-full text-xs text-gray-600 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 cursor-pointer">
                    </div>

                    <!-- Catatan Pembayaran -->
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">Catatan (Opsional)</label>
                        <input type="text" name="catatan_pembayaran" x-model="psbModalData.catatan" placeholder="Keterangan pembayaran (opsional)..." class="w-full px-3 py-2 rounded-lg border border-gray-300 text-xs text-gray-800 focus:border-brand-500 outline-none">
                    </div>
                </div>

                <div class="ta-modal-footer">
                    <button type="button" @click="modalPsbBayar = false" class="ta-btn-outline text-xs">
                        Batal
                    </button>
                    <button type="submit" class="ta-btn-primary text-xs">
                        Simpan Pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL PREVIEW BUKTI TRANSFER -->
    <div x-show="modalPreviewFoto" x-cloak class="ta-modal-backdrop">
        <div @click.away="modalPreviewFoto = false" class="ta-modal max-w-lg">
            <div class="ta-modal-header">
                <h4 class="text-sm font-bold text-gray-900" x-text="previewTitle"></h4>
                <button @click="modalPreviewFoto = false" class="text-gray-400 hover:text-gray-600 text-lg">&times;</button>
            </div>
            <div class="ta-modal-body flex flex-col items-center justify-center bg-gray-100 rounded-lg p-3 min-h-[220px] max-h-[70vh] overflow-y-auto">
                <template x-if="previewImgUrl && previewImgUrl.toLowerCase().endsWith('.pdf')">
                    <div class="w-full flex flex-col items-center justify-center gap-3 py-6">
                        <svg class="w-16 h-16 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-6 4h4"/></svg>
                        <div class="text-center">
                            <span class="text-sm font-bold text-gray-800 block">Dokumen Berformat PDF</span>
                            <span class="text-xs text-gray-500">Klik tombol di bawah untuk membuka dokumen di tab baru</span>
                        </div>
                        <a :href="previewImgUrl" target="_blank" class="mt-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg shadow-sm transition inline-flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            Buka File PDF
                        </a>
                    </div>
                </template>
                <template x-if="!previewImgUrl || !previewImgUrl.toLowerCase().endsWith('.pdf')">
                    <img :src="previewImgUrl" alt="Bukti Transfer" class="max-h-[65vh] object-contain rounded-lg">
                </template>
            </div>
        </div>
    </div>

    <!-- MODAL RINCIAN SELURUH TUNGGAKAN SANTRI PER BULAN -->
    <div x-show="showTunggakanModal" x-cloak class="ta-modal-backdrop">
        <div @click.away="showTunggakanModal = false" class="ta-modal max-w-xl max-h-[90vh] overflow-y-auto space-y-4">
            <div class="ta-modal-header">
                <div>
                    <h3 class="text-base font-bold text-gray-900">Rincian Tunggakan Santri</h3>
                    <p class="text-xs text-gray-500 mt-0.5">
                        Santri: <strong class="text-gray-800" x-text="selectedPerson ? selectedPerson.nama : ''"></strong> 
                        (<span x-text="selectedPerson ? selectedPerson.kelas : ''"></span>)
                    </p>
                </div>
                <button type="button" @click="showTunggakanModal = false" class="text-gray-400 hover:text-gray-600 text-lg">&times;</button>
            </div>

            <!-- Total Banner -->
            <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-gray-700 block">Total Tunggakan</span>
                    <span class="text-[11px] text-gray-500" x-text="totalTunggakanCount + ' tagihan belum lunas'"></span>
                </div>
                <span class="text-base font-bold font-mono text-rose-600" x-text="formatRupiah(studentBills.reduce((acc, b) => acc + (b.sisa_tagihan || 0), 0))"></span>
            </div>

            <!-- Daftar Tunggakan Per Periode -->
            <div class="space-y-3">
                <template x-for="(grp, gIdx) in tunggakanByMonth" :key="'grp_' + gIdx">
                    <div class="rounded-lg border border-gray-200 overflow-hidden">
                        <div class="px-3.5 py-2 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
                            <span class="font-semibold text-xs text-gray-800">
                                Periode: <span x-text="grp.periode"></span>
                            </span>
                            <span class="text-xs font-bold font-mono text-rose-600" x-text="'Sisa: ' + formatRupiah(grp.total_sisa)"></span>
                        </div>
                        <div class="p-2.5 bg-white divide-y divide-gray-100">
                            <template x-for="it in grp.items" :key="'it_' + it.id">
                                <div class="py-2 flex items-center justify-between text-xs">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="px-1.5 py-0.5 rounded font-mono font-medium text-[10px] bg-gray-100 text-gray-700" x-text="it.pos_biaya"></span>
                                            <span class="font-medium text-gray-900" x-text="it.judul_tagihan"></span>
                                        </div>
                                        <div class="text-[11px] text-gray-500 mt-0.5">
                                            Tagihan Asli: <span x-text="formatRupiah(it.nominal_tagihan)"></span>
                                            <span x-show="it.nominal_bayar > 0" class="text-gray-700" x-text="'• Sudah Dicicil: ' + formatRupiah(it.nominal_bayar)"></span>
                                        </div>
                                    </div>
                                    <div class="text-right font-mono font-semibold text-rose-600 text-xs" x-text="formatRupiah(it.sisa_tagihan)"></div>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>

                <div x-show="tunggakanByMonth.length === 0" class="text-center py-6 text-xs text-gray-400">
                    Tidak ada riwayat tunggakan.
                </div>
            </div>

            <div class="ta-modal-footer">
                <button type="button" @click="showTunggakanModal = false" class="ta-btn-outline text-xs">
                    Tutup
                </button>
            </div>
        </div>
    </div>

</div>
@endsection
