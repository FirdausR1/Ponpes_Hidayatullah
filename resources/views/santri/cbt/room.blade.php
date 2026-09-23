<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ruang Ujian CBT — {{ $exam->mata_pelajaran }} ({{ $student->nama_lengkap }})</title>
    <link rel="icon" href="/logo.png" type="image/png">
    
    <!-- Google Fonts: Plus Jakarta Sans & Amiri -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:ital,wght@0,400;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        arabic: ['"Amiri"', 'serif'],
                    }
                }
            }
        }
    </script>
    <style>
        /* Anti-cheat text selection disable */
        body {
            user-select: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
        }
        .nav-grid-btn.answered {
            background-color: #15803d;
            color: #ffffff;
            border-color: #15803d;
        }
        .nav-grid-btn.doubtful {
            background-color: #eab308 !important;
            color: #ffffff !important;
            border-color: #ca8a04 !important;
        }
        .nav-grid-btn.current {
            ring: 3px;
            box-shadow: 0 0 0 3px rgba(15, 23, 42, 0.3);
            font-weight: 800;
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen flex flex-col font-sans text-slate-800">

    <!-- Top Sticky Bar -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-30 px-4 py-2.5 shadow-xs">
        <div class="max-w-7xl mx-auto flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <img src="/logo.png" alt="Logo" class="w-8 h-8 object-contain">
                <div>
                    <h1 class="text-xs sm:text-sm font-bold text-slate-900 leading-tight">
                        {{ $exam->mata_pelajaran }}
                    </h1>
                    <span class="text-[11px] text-slate-500 font-mono">
                        {{ $exam->nama_ujian }} &bull; Santri: <strong class="text-slate-700 uppercase">{{ $student->nama_lengkap }}</strong>
                    </span>
                </div>
            </div>

            <!-- Timer & Actions -->
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2 bg-slate-900 text-white px-3.5 py-1.5 rounded-xl font-mono text-sm font-bold shadow-xs">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    <span id="countdownDisplay">--:--:--</span>
                </div>

                <div class="hidden sm:flex items-center gap-1.5 text-[11px] text-emerald-700 font-semibold bg-emerald-50 px-2.5 py-1.5 rounded-xl border border-emerald-200">
                    <span class="w-2 h-2 rounded-full bg-emerald-600 animate-pulse"></span>
                    <span id="autosaveStatus">Tersimpan</span>
                </div>

                <button type="button" onclick="openFinishModal()" class="px-3.5 py-1.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow-xs transition">
                    Selesai &amp; Kumpulkan
                </button>
            </div>
        </div>
    </header>

    <!-- Main Workspace -->
    <main class="flex-1 max-w-7xl w-full mx-auto p-4 sm:p-6 grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Area Soal (Kolom 1 - 3) -->
        <div class="lg:col-span-3 space-y-4">
            @if($questions->isEmpty())
                <div class="bg-white rounded-3xl p-12 text-center border border-slate-200 text-slate-400">
                    Belum ada butir soal pada sesi ujian ini. Hubungi pengawas ruang.
                </div>
            @else
                @foreach($questions as $index => $q)
                @php
                    $qNum = $index + 1;
                    $selectedAns = $answers[$q->id] ?? null;
                    $isDoubt = in_array($q->id, $raguList);
                @endphp
                <div class="question-container bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-theme-xs space-y-6" id="qContainer-{{ $qNum }}" style="{{ $index === 0 ? '' : 'display: none;' }}" data-qnum="{{ $qNum }}" data-qid="{{ $q->id }}">
                    <!-- Header Soal -->
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <div class="flex items-center gap-2">
                            <span class="w-8 h-8 rounded-xl bg-slate-900 text-white font-mono font-bold text-sm flex items-center justify-center">
                                {{ $qNum }}
                            </span>
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">
                                Soal No. {{ $qNum }} dari {{ $questions->count() }}
                            </span>
                        </div>

                        <!-- Tombol Ragu-ragu -->
                        <button type="button" onclick="toggleRagu({{ $q->id }}, {{ $qNum }})" id="btnRagu-{{ $qNum }}" class="px-3 py-1.5 rounded-xl text-xs font-bold border transition flex items-center gap-1.5 {{ $isDoubt ? 'bg-amber-100 border-amber-300 text-amber-900 font-extrabold' : 'bg-slate-50 border-slate-200 text-slate-600 hover:bg-amber-50 hover:text-amber-800' }}">
                            <span>🤔</span>
                            <span id="textRagu-{{ $qNum }}">{{ $isDoubt ? 'Ragu-Ragu (Ditandai)' : 'Tandai Ragu-Ragu' }}</span>
                        </button>
                    </div>

                    <!-- Pertanyaan Soal -->
                    <div class="space-y-4">
                        <div class="text-sm sm:text-base font-semibold text-slate-900 leading-relaxed font-sans">
                            {!! nl2br(e($q->pertanyaan)) !!}
                        </div>
                        @if($q->gambar)
                        <div>
                            <img src="{{ $q->gambar }}" alt="Gambar Soal" class="max-h-72 rounded-2xl border border-slate-200 object-contain bg-slate-50 p-1">
                        </div>
                        @endif
                    </div>

                    <!-- Pilihan Jawaban A - E -->
                    <div class="space-y-3 pt-2">
                        @foreach(['A' => $q->opsi_a, 'B' => $q->opsi_b, 'C' => $q->opsi_c, 'D' => $q->opsi_d, 'E' => $q->opsi_e] as $key => $val)
                            @if(!empty($val))
                            <label onclick="pilihJawaban({{ $q->id }}, '{{ $key }}', {{ $qNum }})" class="option-label-{{ $qNum }} flex items-start gap-3.5 p-3.5 sm:p-4 rounded-2xl border-2 transition cursor-pointer {{ $selectedAns === $key ? 'bg-emerald-50/80 border-emerald-600 text-emerald-950 font-semibold shadow-xs' : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-50' }}" id="optLabel-{{ $qNum }}-{{ $key }}">
                                <input type="radio" name="jawaban_{{ $q->id }}" value="{{ $key }}" {{ $selectedAns === $key ? 'checked' : '' }} class="sr-only">
                                <span class="w-7 h-7 rounded-xl font-mono font-bold text-xs flex items-center justify-center shrink-0 transition {{ $selectedAns === $key ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-700' }}" id="optBadge-{{ $qNum }}-{{ $key }}">
                                    {{ $key }}
                                </span>
                                <span class="text-xs sm:text-sm flex-1 pt-0.5 leading-relaxed">{{ $val }}</span>
                            </label>
                            @endif
                        @endforeach
                    </div>

                    <!-- Navigasi Soal Bawah -->
                    <div class="pt-6 border-t border-slate-100 flex items-center justify-between gap-3">
                        <button type="button" onclick="pindahSoal({{ $qNum - 1 }})" {{ $qNum === 1 ? 'disabled' : '' }} class="px-4 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-xs font-bold text-slate-700 disabled:opacity-40 disabled:cursor-not-allowed transition">
                            &larr; Soal Sebelumnya
                        </button>

                        <div class="text-[11px] text-slate-400 font-mono">
                            {{ $qNum }} / {{ $questions->count() }}
                        </div>

                        @if($qNum < $questions->count())
                        <button type="button" onclick="pindahSoal({{ $qNum + 1 }})" class="px-5 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition">
                            Soal Selanjutnya &rarr;
                        </button>
                        @else
                        <button type="button" onclick="openFinishModal()" class="px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-md transition">
                            Selesai &amp; Kumpulkan &rarr;
                        </button>
                        @endif
                    </div>
                </div>
                @endforeach
            @endif
        </div>

        <!-- Sidebar Kisi-Kisi / Nomor Soal (Kolom 4) -->
        <div class="space-y-4">
            <div class="bg-white rounded-3xl p-5 border border-slate-200 shadow-theme-xs space-y-4 sticky top-20">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider">
                        Navigasi Soal
                    </h2>
                    <span class="text-[11px] font-mono font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full" id="progressCountText">
                        {{ count($answers) }} / {{ $questions->count() }} Terjawab
                    </span>
                </div>

                <!-- Petunjuk Warna -->
                <div class="grid grid-cols-3 gap-1.5 text-[10px] text-slate-500 font-medium">
                    <div class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded bg-emerald-600 shrink-0"></span>
                        <span>Dijawab</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded bg-amber-400 shrink-0"></span>
                        <span>Ragu</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded bg-slate-100 border border-slate-300 shrink-0"></span>
                        <span>Kosong</span>
                    </div>
                </div>

                <!-- Grid Tombol Nomor -->
                <div class="grid grid-cols-5 gap-2 max-h-[360px] overflow-y-auto pr-1">
                    @foreach($questions as $index => $q)
                    @php
                        $num = $index + 1;
                        $hasAns = isset($answers[$q->id]);
                        $isDoubt = in_array($q->id, $raguList);
                        $classState = '';
                        if ($isDoubt) {
                            $classState = 'doubtful';
                        } elseif ($hasAns) {
                            $classState = 'answered';
                        }
                    @endphp
                    <button type="button" 
                            id="gridBtn-{{ $num }}" 
                            onclick="pindahSoal({{ $num }})" 
                            class="nav-grid-btn w-full aspect-square rounded-xl text-xs font-mono font-bold border border-slate-200 bg-slate-50 text-slate-700 hover:border-slate-400 transition flex items-center justify-center {{ $classState }} {{ $index === 0 ? 'current' : '' }}">
                        {{ $num }}
                    </button>
                    @endforeach
                </div>

                <div class="pt-3 border-t border-slate-100">
                    <button type="button" onclick="openFinishModal()" class="w-full py-2.5 px-4 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold text-center block shadow-md transition">
                        Kumpulkan Ujian Sekarang
                    </button>
                </div>
            </div>
        </div>
    </main>

    <!-- Hidden Form untuk Submit Final -->
    <form id="submitExamForm" action="{{ route('santri.cbt.selesai') }}" method="POST" class="hidden">
        @csrf
        <input type="hidden" name="result_id" value="{{ $result->id }}">
    </form>

    <!-- Modal 1: Persiapan Mulai Layar Penuh (Anti-Cheat Intro) -->
    <div id="startModal" class="fixed inset-0 z-50 bg-slate-900/90 backdrop-blur-md flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl text-center space-y-5 animate-scale-up">
            <div class="w-16 h-16 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-700 mx-auto flex items-center justify-center text-3xl shadow-xs">
                🛡️
            </div>
            <div>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 uppercase tracking-wider">
                    Sistem CBT Kurikulum Merdeka
                </span>
                <h3 class="text-lg font-bold text-slate-900 mt-2">
                    Siap Memulai Ujian?
                </h3>
                <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                    Ujian akan berjalan dalam mode <strong>Layar Penuh (Fullscreen)</strong>. Pastikan tidak beralih aplikasi atau membuka tab lain selama ujian berlangsung.
                </p>
            </div>

            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-3.5 text-left text-xs text-amber-900 space-y-1">
                <span class="font-bold block">Toleransi Pelanggaran: {{ $exam->max_violations ?? 3 }} Kali</span>
                <p class="text-[11px] text-amber-800">
                    Pelanggaran dihitung jika Anda keluar fullscreen, menekan alt-tab, atau beralih jendela.
                </p>
            </div>

            <button type="button" onclick="startFullscreenExam()" class="w-full py-3 px-6 rounded-2xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold shadow-lg transition">
                Aktifkan Layar Penuh &amp; Mulai Ujian &rarr;
            </button>
        </div>
    </div>

    <!-- Modal 2: Peringatan Pelanggaran Anti-Cheat -->
    <div id="violationWarningModal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-md hidden items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl text-center space-y-5 border-2 border-rose-500 animate-bounce">
            <div class="w-16 h-16 rounded-2xl bg-rose-100 border border-rose-300 text-rose-700 mx-auto flex items-center justify-center text-3xl shadow-xs">
                ⚠️
            </div>
            <div>
                <span id="violationCountBadge" class="px-3 py-1 rounded-full text-xs font-extrabold bg-rose-600 text-white uppercase tracking-wider font-mono">
                    Pelanggaran 1 / {{ $exam->max_violations ?? 3 }}
                </span>
                <h3 class="text-lg font-bold text-rose-700 mt-2">
                    PERINGATAN KECURANGAN!
                </h3>
                <p id="violationWarningText" class="text-xs text-slate-600 mt-1 leading-relaxed">
                    Anda terdeteksi keluar dari layar ujian atau membuka aplikasi lain!
                </p>
            </div>

            <p class="text-[11px] text-slate-400">
                Aksi ini dicatat ke log pengawas ujian. Jika mencapai batas maksimal, ujian akan otomatis dikunci!
            </p>

            <button type="button" onclick="dismissViolationWarning()" class="w-full py-3 px-6 rounded-2xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow-md transition">
                Saya Mengerti &amp; Kembali ke Ujian
            </button>
        </div>
    </div>

    <!-- Modal 3: Konfirmasi Kumpulkan Ujian -->
    <div id="finishModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs hidden items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl text-center space-y-5">
            <div class="w-16 h-16 rounded-2xl bg-blue-50 border border-blue-200 text-blue-700 mx-auto flex items-center justify-center text-3xl">
                📝
            </div>

            <div>
                <h3 class="text-lg font-bold text-slate-900">
                    Kumpulkan Ujian Sekarang?
                </h3>
                <p id="finishSummaryText" class="text-xs text-slate-500 mt-1 leading-relaxed">
                    Memeriksa jawaban Anda...
                </p>
            </div>

            <div class="grid grid-cols-2 gap-2 text-xs">
                <button type="button" onclick="closeFinishModal()" class="py-2.5 px-4 rounded-xl border border-slate-200 hover:bg-slate-50 font-bold text-slate-700 transition">
                    Kembali Periksa
                </button>
                <button type="button" onclick="submitFinalExam()" class="py-2.5 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 font-bold text-white shadow-md transition">
                    Ya, Kumpulkan
                </button>
            </div>
        </div>
    </div>

    <script>
        const totalSoal = {{ $questions->count() }};
        const resultId = {{ $result->id }};
        const examId = {{ $exam->id }};
        const maxViolations = {{ $exam->max_violations ?? 3 }};
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        let currentQuestionNum = 1;
        let remainingSeconds = {{ $result->sisa_detik ?? ($exam->durasi_menit * 60) }};
        let isExamActive = false;
        let isSubmitting = false;

        // Data State
        let answeredQuestions = new Set({!! json_encode(array_keys($answers)) !!}.map(Number));
        let doubtfulQuestions = new Set({!! json_encode($raguList) !!}.map(Number));

        // 1. FULLSCREEN & START
        function startFullscreenExam() {
            isExamActive = true;
            document.getElementById('startModal').style.display = 'none';

            if (document.documentElement.requestFullscreen) {
                document.documentElement.requestFullscreen().catch(() => {});
            }
        }

        function dismissViolationWarning() {
            document.getElementById('violationWarningModal').style.display = 'none';
            if (document.documentElement.requestFullscreen) {
                document.documentElement.requestFullscreen().catch(() => {});
            }
        }

        // 2. ANTI-CHEAT LISTENERS
        function reportViolation(type, message) {
            if (!isExamActive || isSubmitting) return;

            fetch('{{ route("santri.cbt.pelanggaran") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    result_id: resultId,
                    type: type,
                    message: message
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.auto_submit) {
                    isSubmitting = true;
                    alert('BATAS PELANGGARAN HABIS!\nUjian Anda telah dikunci oleh sistem.');
                    document.getElementById('submitExamForm').submit();
                } else {
                    document.getElementById('violationCountBadge').innerText = `Pelanggaran ${data.violation_count} / ${data.max_violations}`;
                    document.getElementById('violationWarningText').innerText = data.message;
                    document.getElementById('violationWarningModal').style.display = 'flex';
                }
            })
            .catch(err => console.error('Violation report err:', err));
        }

        // Deteksi Pindah Tab / Window Blur
        document.addEventListener('visibilitychange', () => {
            if (document.hidden && isExamActive && !isSubmitting) {
                reportViolation('Pindah Tab Browser', 'Santri beralih ke tab browser lain atau meminimalisir aplikasi.');
            }
        });

        window.addEventListener('blur', () => {
            if (isExamActive && !isSubmitting) {
                reportViolation('Beralih Aplikasi', 'Santri beralih keluar dari jendela browser ujian.');
            }
        });

        // Deteksi Keluar Fullscreen
        document.addEventListener('fullscreenchange', () => {
            if (!document.fullscreenElement && isExamActive && !isSubmitting) {
                reportViolation('Keluar Fullscreen', 'Santri keluar dari mode layar penuh (fullscreen).');
            }
        });

        // Blokir Shortcut Keyboard (F12, Ctrl+C, Ctrl+V, Ctrl+U, Inspect)
        document.addEventListener('keydown', (e) => {
            if (e.key === 'F12' || 
                (e.ctrlKey && (e.key === 'u' || e.key === 'U' || e.key === 'c' || e.key === 'C' || e.key === 'v' || e.key === 'V' || e.key === 's' || e.key === 'S' || e.key === 'p' || e.key === 'P'))) {
                e.preventDefault();
                reportViolation('Shortcut Terlarang', `Santri mencoba shortcut keyboard terlarang (${e.key}).`);
                return false;
            }
        });

        // Blokir Klik Kanan
        document.addEventListener('contextmenu', (e) => {
            e.preventDefault();
            reportViolation('Klik Kanan', 'Santri mencoba klik kanan pada halaman ujian.');
            return false;
        });

        // 3. NAVIGASI SOAL
        function pindahSoal(targetNum) {
            if (targetNum < 1 || targetNum > totalSoal) return;

            document.querySelectorAll('.question-container').forEach(c => c.style.display = 'none');
            const targetContainer = document.getElementById('qContainer-' + targetNum);
            if (targetContainer) targetContainer.style.display = 'block';

            document.querySelectorAll('.nav-grid-btn').forEach(btn => btn.classList.remove('current'));
            const currentGridBtn = document.getElementById('gridBtn-' + targetNum);
            if (currentGridBtn) {
                currentGridBtn.classList.add('current');
                currentGridBtn.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }

            currentQuestionNum = targetNum;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // 4. PILIH JAWABAN & AUTOSAVE
        function pilihJawaban(qId, key, qNum) {
            // UI Update
            document.querySelectorAll('.option-label-' + qNum).forEach(lbl => {
                lbl.className = `option-label-${qNum} flex items-start gap-3.5 p-3.5 sm:p-4 rounded-2xl border-2 transition cursor-pointer bg-white border-slate-200 text-slate-700 hover:bg-slate-50`;
            });

            const activeLabel = document.getElementById(`optLabel-${qNum}-${key}`);
            if (activeLabel) {
                activeLabel.className = `option-label-${qNum} flex items-start gap-3.5 p-3.5 sm:p-4 rounded-2xl border-2 transition cursor-pointer bg-emerald-50/80 border-emerald-600 text-emerald-950 font-semibold shadow-xs`;
            }

            // Radio checked
            const radio = activeLabel.querySelector('input[type="radio"]');
            if (radio) radio.checked = true;

            answeredQuestions.add(Number(qId));

            // Grid button sync
            const gridBtn = document.getElementById('gridBtn-' + qNum);
            if (gridBtn && !doubtfulQuestions.has(Number(qId))) {
                gridBtn.classList.add('answered');
            }

            updateProgressCount();
            sendAutosave(qId, key, doubtfulQuestions.has(Number(qId)));
        }

        function toggleRagu(qId, qNum) {
            const numQId = Number(qId);
            const isCurrentlyDoubt = doubtfulQuestions.has(numQId);
            const gridBtn = document.getElementById('gridBtn-' + qNum);
            const btnRagu = document.getElementById('btnRagu-' + qNum);
            const textRagu = document.getElementById('textRagu-' + qNum);

            let newDoubt = false;
            if (isCurrentlyDoubt) {
                doubtfulQuestions.delete(numQId);
                btnRagu.className = 'px-3 py-1.5 rounded-xl text-xs font-bold border transition flex items-center gap-1.5 bg-slate-50 border-slate-200 text-slate-600 hover:bg-amber-50 hover:text-amber-800';
                textRagu.innerText = 'Tandai Ragu-Ragu';
                if (gridBtn) {
                    gridBtn.classList.remove('doubtful');
                    if (answeredQuestions.has(numQId)) {
                        gridBtn.classList.add('answered');
                    }
                }
            } else {
                doubtfulQuestions.add(numQId);
                newDoubt = true;
                btnRagu.className = 'px-3 py-1.5 rounded-xl text-xs font-bold border transition flex items-center gap-1.5 bg-amber-100 border-amber-300 text-amber-900 font-extrabold';
                textRagu.innerText = 'Ragu-Ragu (Ditandai)';
                if (gridBtn) {
                    gridBtn.classList.add('doubtful');
                }
            }

            // Dapatkan jawaban terpilih jika ada
            const container = document.getElementById('qContainer-' + qNum);
            const checkedRadio = container.querySelector('input[type="radio"]:checked');
            const ans = checkedRadio ? checkedRadio.value : null;

            sendAutosave(qId, ans, newDoubt);
        }

        function sendAutosave(qId, answer, isRagu) {
            const statusEl = document.getElementById('autosaveStatus');
            if (statusEl) statusEl.innerText = 'Menyimpan...';

            fetch('{{ route("santri.cbt.autosave") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    result_id: resultId,
                    question_id: qId,
                    answer: answer,
                    ragu: isRagu,
                    sisa_detik: remainingSeconds
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.force_finish) {
                    window.location.href = data.redirect;
                }
                if (statusEl) statusEl.innerText = 'Tersimpan';
            })
            .catch(err => {
                if (statusEl) statusEl.innerText = 'Offline';
            });
        }

        function updateProgressCount() {
            document.getElementById('progressCountText').innerText = `${answeredQuestions.size} / ${totalSoal} Terjawab`;
        }

        // 5. COUNTDOWN TIMER
        function updateTimer() {
            if (remainingSeconds <= 0) {
                isSubmitting = true;
                alert('WAKTU UJIAN TELAH HABIS!\nUjian Anda akan otomatis dikumpulkan.');
                document.getElementById('submitExamForm').submit();
                return;
            }

            remainingSeconds--;

            const h = Math.floor(remainingSeconds / 3600);
            const m = Math.floor((remainingSeconds % 3600) / 60);
            const s = remainingSeconds % 60;

            const str = `${String(h).padStart(2, '0')}:${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
            document.getElementById('countdownDisplay').innerText = str;

            // Autosave timer setiap 30 detik
            if (remainingSeconds % 30 === 0) {
                sendAutosave(null, null, false);
            }
        }
        setInterval(updateTimer, 1000);

        // 6. FINISH MODAL
        function openFinishModal() {
            const terjawab = answeredQuestions.size;
            const kosong = totalSoal - terjawab;
            const ragu = doubtfulQuestions.size;

            let text = `Anda telah menjawab <strong>${terjawab}</strong> dari <strong>${totalSoal}</strong> butir soal.`;
            if (kosong > 0) {
                text += `<br><span class="text-rose-600 font-semibold">• Masih ada ${kosong} soal belum dijawab.</span>`;
            }
            if (ragu > 0) {
                text += `<br><span class="text-amber-600 font-semibold">• Ada ${ragu} soal yang ditandai ragu-ragu.</span>`;
            }
            text += `<br>Apakah Anda yakin ingin menyelesaikan dan mengumpulkan lembar jawaban ujian sekarang?`;

            document.getElementById('finishSummaryText').innerHTML = text;
            document.getElementById('finishModal').style.display = 'flex';
        }

        function closeFinishModal() {
            document.getElementById('finishModal').style.display = 'none';
        }

        function submitFinalExam() {
            isSubmitting = true;
            document.getElementById('submitExamForm').submit();
        }
    </script>
</body>
</html>
