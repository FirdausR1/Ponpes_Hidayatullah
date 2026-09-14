<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\PsbRegistration;
use App\Models\Setting;
use App\Models\Classroom;
use App\Models\StudentPayment;
use App\Models\Dormitory;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class StudentController extends Controller
{
    /**
     * Tampilkan daftar seluruh siswa / santri aktif.
     */
    public function index(Request $request)
    {
        $query = Student::with('psbRegistration')->latest();

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%")
                  ->orWhere('nama_wali', 'like', "%{$search}%");
            });
        }

        if ($request->filled('jenjang')) {
            $query->where('jenjang', $request->jenjang);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('kelas')) {
            $query->where('kelas', $request->kelas);
        }

        if ($request->filled('tahun_masuk')) {
            $query->where('tahun_masuk', $request->tahun_masuk);
        }

        $students = $query->paginate(20)->withQueryString();

        $stats = [
            'total' => Student::count(),
            'aktif' => Student::where('status', 'Aktif')->count(),
            'mts' => Student::where('jenjang', 'like', '%MTs%')->count(),
            'ma' => Student::where('jenjang', 'like', '%MA%')->count(),
        ];

        // Ambil daftar kelas dari database Classroom secara dinamis
        $allClasses = Classroom::orderBy('jenjang')->orderBy('nama_kelas')->pluck('nama_kelas')->toArray();
        if (empty($allClasses)) {
            $allClasses = Student::select('kelas')->distinct()->whereNotNull('kelas')->pluck('kelas')->toArray();
            sort($allClasses);
        }

        $allYears = Student::select('tahun_masuk')->distinct()->whereNotNull('tahun_masuk')->orderBy('tahun_masuk', 'desc')->pluck('tahun_masuk')->toArray();
        if (!in_array(date('Y'), $allYears)) {
            array_unshift($allYears, (string) date('Y'));
        }

        // Hitung calon santri PSB berstatus DITERIMA yang belum ditarik ke data siswa aktif
        $importedPsbIds = Student::whereNotNull('psb_registration_id')->pluck('psb_registration_id')->toArray();
        $unimportedPsbCount = PsbRegistration::where('status', 'Diterima')
            ->whereNotIn('id', $importedPsbIds)
            ->count();

        return view('admin.siswa.index', compact('students', 'stats', 'allClasses', 'allYears', 'unimportedPsbCount'));
    }

    /**
     * Form tambah santri baru manual dengan data lengkap.
     */
    public function create()
    {
        $allClassrooms = Classroom::orderBy('jenjang')->orderBy('nama_kelas')->get();
        $allClasses = $allClassrooms->pluck('nama_kelas')->toArray();
        if (empty($allClasses)) {
            $allClasses = ['VII-A', 'VII-B', 'VIII-A', 'VIII-B', 'IX-A', 'IX-B', 'X-A', 'X-B', 'XI-A', 'XI-B', 'XII-A', 'XII-B'];
        }
        return view('admin.siswa.create', compact('allClasses', 'allClassrooms'));
    }

    /**
     * Simpan data santri baru ke database dengan data komprehensif.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // Data Pokok
            'nama_lengkap' => 'required|string|max:255',
            'nis' => 'required|string|max:30|unique:students,nis',
            'username' => 'nullable|string|max:50|unique:students,username',
            'nisn' => 'nullable|string|max:30',
            'jenis_kelamin' => 'required|string|in:Laki-laki,Perempuan',
            'tanggal_lahir' => 'nullable|string|max:30',
            'jenjang' => 'required|string|max:50',
            'kelas' => 'required|string|max:50',
            'kamar_asrama' => 'nullable|string|max:50',
            'tahun_masuk' => 'required|string|max:10',
            'status' => 'required|string|in:Aktif,Alumni,Mutasi,Cuti',

            // Data Pribadi Lengkap
            'nik' => 'nullable|string|max:30',
            'nomor_kk' => 'nullable|string|max:30',
            'tempat_lahir' => 'nullable|string|max:100',
            'anak_ke' => 'nullable|integer',
            'jumlah_saudara' => 'nullable|integer',
            'golongan_darah' => 'nullable|string|max:10',
            'hobi' => 'nullable|string|max:150',
            'riwayat_penyakit' => 'nullable|string',
            'alamat_lengkap' => 'nullable|string',
            'no_whatsapp' => 'nullable|string|max:30',

            // Asal Sekolah
            'nama_sekolah' => 'nullable|string|max:255',
            'alamat_sekolah' => 'nullable|string',
            'tahun_lulus' => 'nullable|string|max:10',

            // Data Orang Tua (Ayah)
            'ayah_nama' => 'nullable|string|max:255',
            'ayah_nik' => 'nullable|string|max:30',
            'ayah_telepon' => 'nullable|string|max:30',
            'ayah_pekerjaan' => 'nullable|string|max:100',
            'ayah_penghasilan' => 'nullable|string|max:50',
            'ayah_pendidikan' => 'nullable|string|max:50',

            // Data Orang Tua (Ibu)
            'ibu_nama' => 'nullable|string|max:255',
            'ibu_nik' => 'nullable|string|max:30',
            'ibu_telepon' => 'nullable|string|max:30',
            'ibu_pekerjaan' => 'nullable|string|max:100',
            'ibu_penghasilan' => 'nullable|string|max:50',
            'ibu_pendidikan' => 'nullable|string|max:50',

            // Data Wali
            'nama_wali' => 'nullable|string|max:255',
            'wali_nik' => 'nullable|string|max:30',
            'wali_telepon' => 'nullable|string|max:30',
            'wali_hubungan' => 'nullable|string|max:50',
            'wali_pekerjaan' => 'nullable|string|max:100',
            'wali_penghasilan' => 'nullable|string|max:50',

            'alamat' => 'nullable|string',
            'catatan' => 'nullable|string',
            'foto_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto_file')) {
            $file = $request->file('foto_file');
            $filename = time() . '_siswa_' . Str::slug($request->nama_lengkap) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/siswa'), $filename);
            $fotoPath = '/uploads/siswa/' . $filename;
        }

        $validated['foto'] = $fotoPath;

        // Tentukan password default: tanggal lahir (DDMMYYYY) atau santri123
        $defaultPassword = 'santri123';
        if (!empty($request->tanggal_lahir)) {
            try {
                $defaultPassword = Carbon::parse($request->tanggal_lahir)->format('dmY');
            } catch (\Throwable $e) {
                $clean = preg_replace('/[^0-9]/', '', $request->tanggal_lahir);
                if (!empty($clean)) $defaultPassword = $clean;
            }
        }
        $validated['password'] = Hash::make($defaultPassword);

        if (empty($validated['username'])) {
            $validated['username'] = $validated['nis'];
        }

        if (empty($validated['alamat']) && !empty($validated['alamat_lengkap'])) {
            $validated['alamat'] = $validated['alamat_lengkap'];
        }

        Student::create($validated);


        return redirect()->route('admin.siswa.index')->with('success', "Data santri {$request->nama_lengkap} berhasil ditambahkan! Akun login dibuat dengan Password default: {$defaultPassword}");
    }

    /**
     * Tampilkan kartu biodata detail santri.
     */
    public function show($id)
    {
        $student = Student::with(['psbRegistration', 'payments'])->findOrFail($id);
        return view('admin.siswa.show', compact('student'));
    }

    /**
     * Form edit data santri lengkap.
     */
    public function edit($id)
    {
        $student = Student::findOrFail($id);
        $allClassrooms = Classroom::orderBy('jenjang')->orderBy('nama_kelas')->get();
        $allClasses = $allClassrooms->pluck('nama_kelas')->toArray();
        if (empty($allClasses)) {
            $allClasses = ['VII-A', 'VII-B', 'VIII-A', 'VIII-B', 'IX-A', 'IX-B', 'X-A', 'X-B', 'XI-A', 'XI-B', 'XII-A', 'XII-B'];
        }
        return view('admin.siswa.edit', compact('student', 'allClasses', 'allClassrooms'));
    }

    /**
     * Perbarui data santri dengan data lengkap.
     */
    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $validated = $request->validate([
            // Data Pokok
            'nama_lengkap' => 'required|string|max:255',
            'nis' => 'required|string|max:30|unique:students,nis,' . $student->id,
            'username' => 'nullable|string|max:50|unique:students,username,' . $student->id,
            'nisn' => 'nullable|string|max:30',
            'jenis_kelamin' => 'required|string|in:Laki-laki,Perempuan',
            'tanggal_lahir' => 'nullable|string|max:30',
            'jenjang' => 'required|string|max:50',
            'kelas' => 'required|string|max:50',
            'kamar_asrama' => 'nullable|string|max:50',
            'tahun_masuk' => 'required|string|max:10',
            'status' => 'required|string|in:Aktif,Alumni,Mutasi,Cuti',

            // Data Pribadi Lengkap
            'nik' => 'nullable|string|max:30',
            'nomor_kk' => 'nullable|string|max:30',
            'tempat_lahir' => 'nullable|string|max:100',
            'anak_ke' => 'nullable|integer',
            'jumlah_saudara' => 'nullable|integer',
            'golongan_darah' => 'nullable|string|max:10',
            'hobi' => 'nullable|string|max:150',
            'riwayat_penyakit' => 'nullable|string',
            'alamat_lengkap' => 'nullable|string',
            'no_whatsapp' => 'nullable|string|max:30',

            // Asal Sekolah
            'nama_sekolah' => 'nullable|string|max:255',
            'alamat_sekolah' => 'nullable|string',
            'tahun_lulus' => 'nullable|string|max:10',

            // Data Orang Tua (Ayah)
            'ayah_nama' => 'nullable|string|max:255',
            'ayah_nik' => 'nullable|string|max:30',
            'ayah_telepon' => 'nullable|string|max:30',
            'ayah_pekerjaan' => 'nullable|string|max:100',
            'ayah_penghasilan' => 'nullable|string|max:50',
            'ayah_pendidikan' => 'nullable|string|max:50',

            // Data Orang Tua (Ibu)
            'ibu_nama' => 'nullable|string|max:255',
            'ibu_nik' => 'nullable|string|max:30',
            'ibu_telepon' => 'nullable|string|max:30',
            'ibu_pekerjaan' => 'nullable|string|max:100',
            'ibu_penghasilan' => 'nullable|string|max:50',
            'ibu_pendidikan' => 'nullable|string|max:50',

            // Data Wali
            'nama_wali' => 'nullable|string|max:255',
            'wali_nik' => 'nullable|string|max:30',
            'wali_telepon' => 'nullable|string|max:30',
            'wali_hubungan' => 'nullable|string|max:50',
            'wali_pekerjaan' => 'nullable|string|max:100',
            'wali_penghasilan' => 'nullable|string|max:50',

            'alamat' => 'nullable|string',
            'catatan' => 'nullable|string',
            'password_baru' => 'nullable|string|min:6',
            'foto_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        if ($request->hasFile('foto_file')) {
            $file = $request->file('foto_file');
            $filename = time() . '_siswa_' . Str::slug($request->nama_lengkap) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/siswa'), $filename);
            $validated['foto'] = '/uploads/siswa/' . $filename;
        }

        if (!empty($request->password_baru)) {
            $validated['password'] = Hash::make($request->password_baru);
        }

        if (empty($validated['username'])) {
            $validated['username'] = $student->username ?: $validated['nis'];
        }

        if (empty($validated['alamat']) && !empty($validated['alamat_lengkap'])) {
            $validated['alamat'] = $validated['alamat_lengkap'];
        }

        $student->update($validated);

        return redirect()->route('admin.siswa.index')->with('success', "Data santri {$student->nama_lengkap} berhasil diperbarui!");
    }


    /**
     * Hapus data santri.
     */
    public function destroy($id)
    {
        $student = Student::findOrFail($id);
        $name = $student->nama_lengkap;
        $student->delete();

        return redirect()->route('admin.siswa.index')->with('success', "Data santri {$name} berhasil dihapus.");
    }

    /**
     * Konversi instan satu calon santri PSB yang diterima menjadi Siswa/Santri Aktif.
     */
    public function importFromPsb($psbId)
    {
        $reg = PsbRegistration::findOrFail($psbId);

        $existing = Student::where('psb_registration_id', $reg->id)->first();
        if ($existing) {
            return redirect()->back()->with('info', "Santri {$reg->nama_lengkap} sudah terdaftar sebagai siswa aktif dengan NIS: {$existing->nis} (Kelas {$existing->kelas})");
        }

        $tahun = date('Y');
        $count = Student::where('tahun_masuk', $tahun)->count() + 1;
        $nis = $tahun . str_pad($count, 4, '0', STR_PAD_LEFT);
        $username = $reg->nisn ?: ('santri_' . $nis);

        // Password default dari tanggal lahir santri (format DDMMYYYY)
        $defaultPassword = 'santri123';
        if (!empty($reg->tanggal_lahir)) {
            try {
                $defaultPassword = Carbon::parse($reg->tanggal_lahir)->format('dmY');
            } catch (\Throwable $e) {
                $clean = preg_replace('/[^0-9]/', '', $reg->tanggal_lahir);
                if (!empty($clean)) $defaultPassword = $clean;
            }
        }

        $kelas = str_contains($reg->jenjang, 'MA') ? 'X-A' : 'VII-A';

        $student = Student::create([
            'psb_registration_id' => $reg->id,
            'nis' => $nis,
            'username' => $username,
            'nisn' => $reg->nisn,
            'nama_lengkap' => $reg->nama_lengkap,
            'jenis_kelamin' => $reg->jenis_kelamin ?? 'Laki-laki',
            'tanggal_lahir' => $reg->tanggal_lahir,
            'jenjang' => $reg->jenjang,
            'kelas' => $kelas,
            'kamar_asrama' => str_contains($reg->jenjang, 'Mukim') ? 'Asrama Utama' : null,
            'tahun_masuk' => $tahun,
            'status' => 'Aktif',
            'nama_wali' => $reg->nama_wali ?: $reg->ayah_nama,
            'no_whatsapp' => $reg->no_whatsapp ?: $reg->ayah_telepon,
            'alamat' => $reg->alamat_lengkap ?: $reg->alamat,
            'foto' => $reg->pas_foto,
            'password' => Hash::make($defaultPassword),
            'catatan' => "Diterima melalui PSB: {$reg->jalur} (No Reg: {$reg->no_registrasi})",
        ]);

        if ($reg->status !== 'Diterima') {
            $reg->update(['status' => 'Diterima']);
        }

        return redirect()->back()->with('success', "Alhamdulillah! Calon santri {$reg->nama_lengkap} ({$reg->no_registrasi}) resmi didaftarkan sebagai Santri Aktif! NIS: {$student->nis}, Kelas: {$student->kelas}, Username: {$student->username}, Password Login: {$defaultPassword}");
    }

    /**
     * Impor massal calon santri yang DITERIMA dari PSB ke data santri aktif.
     */
    public function bulkImportFromPsb(Request $request)
    {
        $importedPsbIds = Student::whereNotNull('psb_registration_id')->pluck('psb_registration_id')->toArray();
        $query = PsbRegistration::where('status', 'Diterima')->whereNotIn('id', $importedPsbIds);

        if ($request->filled('selected_ids') && is_array($request->selected_ids)) {
            $query->whereIn('id', $request->selected_ids);
        }

        $acceptedRegistrations = $query->orderBy('id')->get();

        if ($acceptedRegistrations->isEmpty()) {
            return redirect()->back()->with('info', 'Tidak ada calon santri baru berstatus Diterima yang perlu diimpor saat ini.');
        }

        $tahunMasuk = $request->input('tahun_masuk', date('Y'));
        $countStart = Student::where('tahun_masuk', $tahunMasuk)->count();
        $successCount = 0;

        foreach ($acceptedRegistrations as $reg) {
            $countStart++;
            $nis = $tahunMasuk . str_pad($countStart, 4, '0', STR_PAD_LEFT);
            $username = $reg->nisn ?: ('santri_' . $nis);

            // Password tanggal lahir
            $defaultPassword = 'santri123';
            if (!empty($reg->tanggal_lahir)) {
                try {
                    $defaultPassword = Carbon::parse($reg->tanggal_lahir)->format('dmY');
                } catch (\Throwable $e) {
                    $clean = preg_replace('/[^0-9]/', '', $reg->tanggal_lahir);
                    if (!empty($clean)) $defaultPassword = $clean;
                }
            }

            // Tentukan kelas default sesuai jenjang
            $kelas = str_contains($reg->jenjang, 'MA') ? 'X-A' : 'VII-A';
            if ($request->filled('kelas_default')) {
                $kelas = $request->kelas_default;
            }

            Student::create([
                'psb_registration_id' => $reg->id,
                'nis' => $nis,
                'username' => $username,
                'nisn' => $reg->nisn,
                'nama_lengkap' => $reg->nama_lengkap,
                'jenis_kelamin' => $reg->jenis_kelamin ?? 'Laki-laki',
                'tanggal_lahir' => $reg->tanggal_lahir,
                'jenjang' => $reg->jenjang,
                'kelas' => $kelas,
                'kamar_asrama' => str_contains($reg->jenjang, 'Mukim') ? 'Asrama Utama' : null,
                'tahun_masuk' => $tahunMasuk,
                'status' => 'Aktif',
                'nama_wali' => $reg->nama_wali ?: $reg->ayah_nama,
                'no_whatsapp' => $reg->no_whatsapp ?: $reg->ayah_telepon,
                'alamat' => $reg->alamat_lengkap ?: $reg->alamat,
                'foto' => $reg->pas_foto,
                'password' => Hash::make($defaultPassword),
                'catatan' => "Impor Massal PSB TA {$tahunMasuk} (No Reg: {$reg->no_registrasi})",
            ]);

            $successCount++;
        }

        return redirect()->route('admin.siswa.index')->with('success', "Berhasil mengimpor massal {$successCount} calon santri yang lulus PSB ke Data Santri Aktif! Semua akun dibuat dengan password tanggal lahir masing-masing santri.");
    }

    /**
     * Ekspor Data Siswa / Santri Lengkap ke Excel (.xlsx)
     */
    public function exportExcel(Request $request)
    {
        $query = Student::query();

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('nama_wali', 'like', "%{$search}%");
            });
        }

        if ($request->filled('jenjang')) {
            $query->where('jenjang', $request->jenjang);
        }

        if ($request->filled('kelas')) {
            $query->where('kelas', $request->kelas);
        }

        if ($request->filled('tahun_masuk')) {
            $query->where('tahun_masuk', $request->tahun_masuk);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('kamar_asrama')) {
            $query->where('kamar_asrama', $request->kamar_asrama);
        }

        $students = $query->orderBy('kelas')->orderBy('nama_lengkap')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Santri');

        // Judul Header
        $sheet->setCellValue('A1', 'DATA POKOK SANTRI / SISWA PONDOK PESANTREN HIDAYATULLAH');
        $sheet->setCellValue('A2', 'Dusun Tuksongo, Kec. Pringsurat, Kab. Temanggung, Jawa Tengah');
        $sheet->setCellValue('A3', 'Tanggal Unduh: ' . date('d F Y, H:i') . ' WIB' . ($request->filled('kelas') ? ' | Kelas: ' . $request->kelas : '') . ($request->filled('status') ? ' | Status: ' . $request->status : ''));
        $sheet->getStyle('A1:A2')->getFont()->setBold(true)->setSize(13);

        $headers = [
            'No',
            'NIS',
            'NISN',
            'Nama Lengkap',
            'Jenis Kelamin',
            'Jenjang',
            'Kelas',
            'Kamar Asrama',
            'Tahun Masuk',
            'Status',
            'Tempat Lahir',
            'Tanggal Lahir',
            'NIK',
            'No. KK',
            'Nama Orang Tua / Wali',
            'No. WhatsApp / HP',
            'Alamat Lengkap',
            'Sekolah Asal',
            'Username Login'
        ];

        $colLetters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S'];

        $headerRow = 5;
        foreach ($headers as $idx => $h) {
            $col = $colLetters[$idx];
            $sheet->setCellValue($col . $headerRow, $h);
            $sheet->getStyle($col . $headerRow)->getFont()->setBold(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFFFFFFF'));
            $sheet->getStyle($col . $headerRow)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF059669'); // Emerald 600
            $sheet->getStyle($col . $headerRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        }
        $sheet->getRowDimension($headerRow)->setRowHeight(28);

        $rowNum = 6;
        $no = 1;
        foreach ($students as $st) {
            $sheet->setCellValue('A' . $rowNum, $no++);
            $sheet->setCellValueExplicit('B' . $rowNum, $st->nis ?: '-', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('C' . $rowNum, $st->nisn ?: '-', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('D' . $rowNum, $st->nama_lengkap);
            $sheet->setCellValue('E' . $rowNum, $st->jenis_kelamin);
            $sheet->setCellValue('F' . $rowNum, $st->jenjang);
            $sheet->setCellValue('G' . $rowNum, $st->kelas);
            $sheet->setCellValue('H' . $rowNum, $st->kamar_asrama ?: '-');
            $sheet->setCellValue('I' . $rowNum, $st->tahun_masuk ?: '-');
            $sheet->setCellValue('J' . $rowNum, $st->status);
            $sheet->setCellValue('K' . $rowNum, $st->tempat_lahir ?: '-');
            $sheet->setCellValue('L' . $rowNum, $st->tanggal_lahir ?: '-');
            $sheet->setCellValueExplicit('M' . $rowNum, $st->nik ?: '-', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('N' . $rowNum, $st->nomor_kk ?: '-', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('O' . $rowNum, $st->nama_wali ?: ($st->ayah_nama ?: ($st->ibu_nama ?: '-')));
            $sheet->setCellValueExplicit('P' . $rowNum, $st->no_whatsapp ?: '-', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('Q' . $rowNum, $st->alamat_lengkap ?: ($st->alamat ?: '-'));
            $sheet->setCellValue('R' . $rowNum, $st->nama_sekolah ?: '-');
            $sheet->setCellValueExplicit('S' . $rowNum, $st->username ?: ($st->nis ?: '-'), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

            // Alignment center
            $sheet->getStyle('A' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('B' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('C' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('E' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('G' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('H' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('I' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('J' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('L' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('M' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('N' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('P' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('S' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $rowNum++;
        }

        // Borders
        if ($rowNum > 6) {
            $sheet->getStyle('A5:S' . ($rowNum - 1))->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => 'FFCBD5E1'],
                    ],
                ],
            ]);
        }

        foreach ($colLetters as $c) {
            $sheet->getColumnDimension($c)->setAutoSize(true);
        }

        $filename = 'Data_Santri_Hidayatullah_' . date('Ymd_His') . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        $tempPath = tempnam(sys_get_temp_dir(), 'santri_export_');
        $writer->save($tempPath);

        return response()->download($tempPath, $filename)->deleteFileAfterSend(true);
    }

    /**
     * Unduh template file Excel / CSV untuk Tambah Santri Secara Massal.
     */
    public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template Import Santri');

        // Header Kolom
        $columns = [
            'A' => ['title' => 'nama_lengkap', 'label' => 'Nama Lengkap (Wajib)'],
            'B' => ['title' => 'nis', 'label' => 'NIS (Kosongkan jika otomatis)'],
            'C' => ['title' => 'username', 'label' => 'Username (Opsional)'],
            'D' => ['title' => 'nisn', 'label' => 'NISN (10 Digit)'],
            'E' => ['title' => 'jenis_kelamin', 'label' => 'Jenis Kelamin (Laki-laki / Perempuan)'],
            'F' => ['title' => 'tanggal_lahir', 'label' => 'Tanggal Lahir (YYYY-MM-DD atau DD/MM/YYYY)'],
            'G' => ['title' => 'jenjang', 'label' => 'Jenjang (MTs Pondok Mukim / MA Pondok Mukim)'],
            'H' => ['title' => 'kelas', 'label' => 'Kelas (VII-A, VII-B, X-A, dll)'],
            'I' => ['title' => 'tahun_masuk', 'label' => 'Tahun Masuk (Contoh: 2026)'],
            'J' => ['title' => 'kamar_asrama', 'label' => 'Kamar Asrama (Contoh: Kamar 01 (Al-Fatih))'],
            'K' => ['title' => 'nama_wali', 'label' => 'Nama Orang Tua / Wali'],
            'L' => ['title' => 'no_whatsapp', 'label' => 'No. WhatsApp / HP Wali'],
            'M' => ['title' => 'alamat', 'label' => 'Alamat Lengkap Santri'],
            'N' => ['title' => 'catatan', 'label' => 'Catatan Khusus Santri'],
        ];

        foreach ($columns as $col => $info) {
            $sheet->setCellValueExplicit($col . '1', $info['title'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        }

        // Styling Header (Emerald 600, White Bold Text, Centered)
        $sheet->getStyle('A1:N1')->applyFromArray([
            'font' => [
                'name' => 'Calibri',
                'bold' => true,
                'color' => ['argb' => 'FFFFFFFF'],
                'size' => 11,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF059669'], // Emerald Green
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FFD1D5DB'],
                ],
            ],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(28);

        // Contoh Data 1 (Santri Putra MTs)
        $sampleRow1 = [
            'Ahmad Fauzan Hidayat',
            '20260001',
            'fauzan26',
            '0098765432',
            'Laki-laki',
            '2012-05-15',
            'MTs Pondok Mukim',
            'VII-A',
            '2026',
            'Kamar 01 (Al-Fatih)',
            'H. Budi Santoso',
            '081234567890',
            'Dusun Tuksongo RT 02 RW 01 Pringsurat Temanggung',
            'Santri program tahfidz 30 juz'
        ];

        // Contoh Data 2 (Santri Putri MA)
        $sampleRow2 = [
            'Nurul Aisyah Zahra',
            '20260002',
            'aisyah26',
            '0087654321',
            'Perempuan',
            '2009-08-20',
            'MA Pondok Mukim',
            'X-A',
            '2026',
            'Kamar 01 (Maryam)',
            'Drs. Subhan',
            '085678901234',
            'Jl. Magelang KM 10 Secang',
            'Santri jurusan Keagamaan'
        ];

        $samples = [$sampleRow1, $sampleRow2];
        $rowIdx = 2;
        foreach ($samples as $sample) {
            $colLetter = 'A';
            foreach ($sample as $val) {
                $sheet->setCellValueExplicit($colLetter . $rowIdx, $val, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $colLetter++;
            }
            $sheet->getStyle("A{$rowIdx}:N{$rowIdx}")->applyFromArray([
                'font' => ['name' => 'Calibri', 'size' => 10],
                'alignment' => ['vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => 'FFE5E7EB'],
                    ],
                ],
            ]);
            $sheet->getRowDimension($rowIdx)->setRowHeight(22);
            $rowIdx++;
        }

        // Auto column width
        foreach (array_keys($columns) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, 'Format_Import_Santri_Hidayatullah.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    /**
     * Import santri massal melalui file format Excel (.xlsx / .xls).
     */
    public function importExcel(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|file|mimes:xlsx,xls,csv,txt|max:15360',
        ], [
            'file_excel.required' => 'Pilih file template Excel (.xlsx) yang telah diisi data santri.',
            'file_excel.mimes' => 'Format file harus berupa Excel (.xlsx atau .xls).',
        ]);

        $file = $request->file('file_excel');
        $ext = strtolower($file->getClientOriginalExtension());
        $imported = 0;
        $skipped = 0;

        try {
            // Ambil daftar kamar asrama yang ada di database untuk pemetaan otomatis
            $dormitories = Dormitory::all();

            if ($ext === 'csv' || $ext === 'txt') {
                // Fallback pembacaan file CSV jika user mengunggah CSV
                $path = $file->getRealPath();
                $handle = fopen($path, 'r');
                $header = fgetcsv($handle, 10000, ",");
                if (!$header || count($header) < 5) {
                    rewind($handle);
                    $header = fgetcsv($handle, 10000, ";");
                    $delimiter = ";";
                } else {
                    $delimiter = ",";
                }
                $cleanHeader = array_map(fn($h) => trim(str_replace(["\xEF\xBB\xBF", '"', "'"], '', strtolower($h))), $header);

                $rows = [];
                while (($row = fgetcsv($handle, 10000, $delimiter)) !== false) {
                    if (empty(array_filter($row))) continue;
                    $item = [];
                    foreach ($cleanHeader as $idx => $key) {
                        $item[$key] = isset($row[$idx]) ? trim($row[$idx]) : null;
                    }
                    $rows[] = $item;
                }
                fclose($handle);
            } else {
                // Pembacaan Format Murni Microsoft Excel (.xlsx / .xls)
                $spreadsheet = IOFactory::load($file->getRealPath());
                $sheet = $spreadsheet->getActiveSheet();
                $sheetData = $sheet->toArray(null, true, true, true);

                if (empty($sheetData) || count($sheetData) < 2) {
                    return redirect()->back()->with('error', 'File Excel kosong atau tidak memiliki baris data santri.');
                }

                // Ambil baris pertama sebagai Header
                $headerRow = array_shift($sheetData);
                $headerMap = [];
                foreach ($headerRow as $col => $headerName) {
                    if (!empty($headerName)) {
                        $clean = trim(strtolower(str_replace([' ', '-', '.'], '_', (string)$headerName)));
                        $headerMap[$col] = $clean;
                    }
                }

                $rows = [];
                foreach ($sheetData as $r) {
                    if (empty(array_filter($r, fn($v) => !is_null($v) && trim((string)$v) !== ''))) {
                        continue;
                    }
                    $item = [];
                    foreach ($headerMap as $col => $key) {
                        $item[$key] = isset($r[$col]) ? trim((string)$r[$col]) : null;
                    }
                    $rows[] = $item;
                }
            }

            // Proses setiap baris data
            foreach ($rows as $data) {
                $nama = $data['nama_lengkap'] ?? null;
                if (empty($nama)) {
                    $skipped++;
                    continue;
                }

                $tahunMasuk = $data['tahun_masuk'] ?? date('Y');
                $nis = $data['nis'] ?? null;
                if (empty($nis)) {
                    $count = Student::where('tahun_masuk', $tahunMasuk)->count() + 1;
                    $nis = $tahunMasuk . str_pad($count, 4, '0', STR_PAD_LEFT);
                }

                if (Student::where('nis', $nis)->exists()) {
                    $nis = $nis . '-' . rand(10, 99);
                }

                $username = $data['username'] ?? ($data['nisn'] ?: ('santri_' . $nis));
                if (Student::where('username', $username)->exists()) {
                    $username = $username . '_' . rand(10, 99);
                }

                // Password Tanggal Lahir (DDMMYYYY)
                $tglLahirRaw = $data['tanggal_lahir'] ?? null;
                $defaultPassword = 'santri123';
                $tglLahirDb = null;

                if (!empty($tglLahirRaw)) {
                    try {
                        if (is_numeric($tglLahirRaw) && (float)$tglLahirRaw > 20000) {
                            $dateTime = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((float)$tglLahirRaw);
                            $tglLahirDb = $dateTime->format('Y-m-d');
                            $defaultPassword = $dateTime->format('dmY');
                        } else {
                            $cleanTgl = str_replace('/', '-', $tglLahirRaw);
                            $parsed = Carbon::parse($cleanTgl);
                            $tglLahirDb = $parsed->format('Y-m-d');
                            $defaultPassword = $parsed->format('dmY');
                        }
                    } catch (\Throwable $e) {
                        $clean = preg_replace('/[^0-9]/', '', $tglLahirRaw);
                        if (strlen($clean) === 8) {
                            $defaultPassword = $clean;
                        }
                    }
                }

                // Pencocokan Asrama Otomatis
                $kamarInput = $data['kamar_asrama'] ?? null;
                $dormitoryId = null;
                if (!empty($kamarInput)) {
                    $matchedDorm = $dormitories->first(function($d) use ($kamarInput) {
                        return str_contains(strtolower($d->kamar), strtolower($kamarInput)) 
                            || str_contains(strtolower($d->nama_asrama), strtolower($kamarInput))
                            || str_contains(strtolower($d->full_name), strtolower($kamarInput));
                    });
                    if ($matchedDorm) {
                        $dormitoryId = $matchedDorm->id;
                        $kamarInput = $matchedDorm->full_name;
                    }
                }

                // Tentukan Jenis Kelamin
                $jkRaw = $data['jenis_kelamin'] ?? '';
                $jk = (str_starts_with(strtoupper($jkRaw), 'P')) ? 'Perempuan' : 'Laki-laki';

                Student::create([
                    'nis' => $nis,
                    'username' => $username,
                    'nisn' => $data['nisn'] ?? null,
                    'nama_lengkap' => $nama,
                    'jenis_kelamin' => $jk,
                    'tanggal_lahir' => $tglLahirDb,
                    'jenjang' => $data['jenjang'] ?? 'MTs Pondok Mukim',
                    'kelas' => $data['kelas'] ?? 'VII-A',
                    'kamar_asrama' => $kamarInput,
                    'dormitory_id' => $dormitoryId,
                    'tahun_masuk' => $tahunMasuk,
                    'status' => 'Aktif',
                    'nama_wali' => $data['nama_wali'] ?? null,
                    'no_whatsapp' => $data['no_whatsapp'] ?? null,
                    'alamat' => $data['alamat'] ?? null,
                    'catatan' => $data['catatan'] ?? 'Import Massal Excel (.xlsx)',
                    'password' => Hash::make($defaultPassword),
                ]);

                $imported++;
            }
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Gagal memproses file Excel: ' . $e->getMessage());
        }

        return redirect()->route('admin.siswa.index')->with('success', "Alhamdulillah! Proses import massal Excel (.xlsx) selesai. Berhasil menambahkan {$imported} santri baru ke dalam sistem. Akun login dan password default (tanggal lahir format DDMMYYYY) otomatis siap digunakan!");
    }

    /**
     * Halaman Kenaikan Kelas Massal & Mutasi Santri.
     */
    public function kenaikanKelasIndex(Request $request)
    {
        $kelasAsal = $request->query('kelas_asal');

        $allClasses = Classroom::orderBy('jenjang')->orderBy('nama_kelas')->pluck('nama_kelas')->toArray();
        if (empty($allClasses)) {
            $allClasses = Student::select('kelas')->distinct()->whereNotNull('kelas')->pluck('kelas')->toArray();
            sort($allClasses);
        }

        $students = collect();
        if (!empty($kelasAsal)) {
            $students = Student::where('status', 'Aktif')
                ->where('kelas', $kelasAsal)
                ->orderBy('nama_lengkap')
                ->get();
        }

        return view('admin.siswa.kenaikan_kelas', compact('allClasses', 'kelasAsal', 'students'));
    }

    /**
     * Proses Kenaikan Kelas Massal.
     * Misal: Seluruh santri Kelas X naik ke Kelas XI, dan yang tidak dicentang tetap tinggal di kelas asal.
     */
    public function kenaikanKelasProcess(Request $request)
    {
        $request->validate([
            'kelas_asal' => 'required|string',
            'aksi_tujuan' => 'required|string', // 'naik_kelas' atau 'lulus_alumni'
            'kelas_tujuan' => 'required_if:aksi_tujuan,naik_kelas|nullable|string',
            'student_ids' => 'required|array|min:1',
        ], [
            'student_ids.required' => 'Pilih minimal satu santri yang akan diproses naik kelas.',
            'kelas_tujuan.required_if' => 'Tentukan kelas tujuan untuk santri yang naik kelas.',
        ]);

        $studentIds = $request->student_ids;
        $aksi = $request->aksi_tujuan;
        $kelasTujuan = $request->kelas_tujuan;
        $kelasAsal = $request->kelas_asal;

        $count = count($studentIds);

        if ($aksi === 'lulus_alumni') {
            Student::whereIn('id', $studentIds)->update([
                'status' => 'Alumni',
                'catatan' => \DB::raw("CONCAT(IFNULL(catatan, ''), ' [Lulus/Alumni dari {$kelasAsal} pada " . date('Y-m-d') . "]')")
            ]);
            $msg = "Berhasil memproses kelulusan! {$count} santri dari kelas {$kelasAsal} resmi dialihkan statusnya menjadi Alumni.";
        } else {
            Student::whereIn('id', $studentIds)->update([
                'kelas' => $kelasTujuan,
            ]);
            $msg = "Alhamdulillah! {$count} santri dari kelas {$kelasAsal} berhasil dinaikkan ke kelas {$kelasTujuan}. Santri yang tidak dicentang tetap di kelas {$kelasAsal} (tinggal kelas).";
        }

        return redirect()->route('admin.siswa.kenaikanKelas', ['kelas_asal' => $kelasAsal])->with('success', $msg);
    }

    /**
     * Halaman Pengelolaan Akun & Akses Login Santri.
     */
    public function akunLoginIndex(Request $request)
    {
        $query = Student::latest();

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($w) use ($q) {
                $w->where('nama_lengkap', 'like', "%{$q}%")
                  ->orWhere('nis', 'like', "%{$q}%")
                  ->orWhere('username', 'like', "%{$q}%")
                  ->orWhere('nisn', 'like', "%{$q}%");
            });
        }

        if ($request->filled('kelas')) {
            $query->where('kelas', $request->kelas);
        }

        $students = $query->paginate(20)->withQueryString();

        $allClasses = Classroom::orderBy('jenjang')->orderBy('nama_kelas')->pluck('nama_kelas')->toArray();
        if (empty($allClasses)) {
            $allClasses = Student::select('kelas')->distinct()->whereNotNull('kelas')->pluck('kelas')->toArray();
            sort($allClasses);
        }

        return view('admin.siswa.akun_login', compact('students', 'allClasses'));
    }

    /**
     * Reset password santri ke tanggal lahir (format DDMMYYYY) atau santri123.
     */
    public function resetPasswordSantri($id)
    {
        $student = Student::findOrFail($id);
        $newPass = $student->getFormattedBirthdatePassword();

        $student->update([
            'password' => Hash::make($newPass),
        ]);

        return redirect()->back()->with('success', "Password akun santri {$student->nama_lengkap} berhasil direset menjadi Tanggal Lahir: {$newPass}");
    }

    /**
     * Ubah Kata Sandi Santri oleh Admin (Password Kustom Bebas atau Reset Tanggal Lahir).
     */
    public function updatePasswordByAdmin(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        if ($request->input('mode') === 'reset_birthdate') {
            $newPass = $student->getFormattedBirthdatePassword();
            $student->update([
                'password' => Hash::make($newPass),
            ]);
            return redirect()->back()->with('success', "Password santri {$student->nama_lengkap} berhasil direset ke Tanggal Lahir ({$newPass})!");
        }

        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ], [
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.min' => 'Kata sandi minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        $student->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()->with('success', "Kata sandi baru untuk santri {$student->nama_lengkap} berhasil diperbarui!");
    }

    /**
     * Master Jenjang & Kelas: Tampilkan daftar kelas aktif, kapasitas & santri.
     */
    public function kelasIndex()
    {
        $classrooms = Classroom::withCount(['students' => function($q) {
            $q->where('status', 'Aktif');
        }])->orderBy('jenjang')->orderBy('nama_kelas')->get();

        $totalKelas = $classrooms->count();
        $totalKapasitas = $classrooms->sum('kapasitas');
        $totalSantri = Student::where('status', 'Aktif')->count();

        return view('admin.siswa.kelas', compact('classrooms', 'totalKelas', 'totalKapasitas', 'totalSantri'));
    }

    /**
     * Master Jenjang & Kelas: Tambah kelas / jenjang baru.
     */
    public function kelasStore(Request $request)
    {
        $validated = $request->validate([
            'jenjang' => 'required|string|max:30',
            'tingkat' => 'required|string|max:20',
            'nama_kelas' => 'required|string|max:50|unique:classrooms,nama_kelas',
            'wali_kelas' => 'nullable|string|max:255',
            'kontak_wali' => 'nullable|string|max:50',
            'nip_wali' => 'nullable|string|max:50',
            'kapasitas' => 'nullable|integer|min:1|max:100',
            'keterangan' => 'nullable|string|max:255',
        ], [
            'jenjang.required' => 'Pilih jenjang pendidikan (MTs/MA/dll).',
            'tingkat.required' => 'Pilih tingkatan kelas (VII, VIII, X, dll).',
            'nama_kelas.required' => 'Nama kelas wajib diisi.',
            'nama_kelas.unique' => 'Nama kelas ini sudah terdaftar sebelumnya.',
        ]);

        if (empty($validated['kapasitas'])) {
            $validated['kapasitas'] = 30;
        }

        Classroom::create($validated);

        return redirect()->route('admin.siswa.kelas.index')->with('success', "Kelas baru {$validated['nama_kelas']} ({$validated['jenjang']}) berhasil ditambahkan!");
    }

    /**
     * Master Jenjang & Kelas: Ubah / Perbarui data kelas.
     */
    public function kelasUpdate(Request $request, $id)
    {
        $classroom = Classroom::findOrFail($id);

        $validated = $request->validate([
            'jenjang' => 'required|string|max:30',
            'tingkat' => 'required|string|max:20',
            'nama_kelas' => 'required|string|max:50|unique:classrooms,nama_kelas,' . $classroom->id,
            'wali_kelas' => 'nullable|string|max:255',
            'kontak_wali' => 'nullable|string|max:50',
            'nip_wali' => 'nullable|string|max:50',
            'kapasitas' => 'nullable|integer|min:1|max:100',
            'keterangan' => 'nullable|string|max:255',
        ], [
            'jenjang.required' => 'Pilih jenjang pendidikan (MTs/MA/dll).',
            'tingkat.required' => 'Pilih tingkatan kelas (VII, VIII, X, dll).',
            'nama_kelas.required' => 'Nama kelas wajib diisi.',
            'nama_kelas.unique' => 'Nama kelas ini sudah digunakan oleh kelas lain.',
        ]);

        if (empty($validated['kapasitas'])) {
            $validated['kapasitas'] = 30;
        }

        $oldName = $classroom->nama_kelas;
        $newName = $validated['nama_kelas'];

        $classroom->update($validated);

        // Jika nama kelas berubah, sinkronkan data santri yang sedang berada di kelas tersebut
        if ($oldName !== $newName) {
            Student::where('kelas', $oldName)->update(['kelas' => $newName]);
        }

        return redirect()->route('admin.siswa.kelas.index')->with('success', "Data kelas {$newName} ({$validated['jenjang']}) berhasil diperbarui!");
    }

    /**
     * Manajemen & Pengaturan Wali Kelas (Homeroom Teachers).
     * Menampilkan seluruh rombel kelas, data wali kelas, kontak WhatsApp, dan santri aktif.
     */
    public function waliKelasIndex(Request $request)
    {
        $jenjangFilter = $request->input('jenjang', 'all');
        $statusFilter = $request->input('status', 'all'); // 'all', 'sudah', 'belum'
        $search = $request->input('q');

        $query = Classroom::withCount(['students' => function($q) {
            $q->where('status', 'Aktif');
        }]);

        if ($jenjangFilter !== 'all' && !empty($jenjangFilter)) {
            $query->where('jenjang', $jenjangFilter);
        }

        if ($statusFilter === 'sudah') {
            $query->whereNotNull('wali_kelas')->where('wali_kelas', '!=', '');
        } elseif ($statusFilter === 'belum') {
            $query->where(function($q) {
                $q->whereNull('wali_kelas')->orWhere('wali_kelas', '');
            });
        }

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('nama_kelas', 'like', "%{$search}%")
                  ->orWhere('wali_kelas', 'like', "%{$search}%")
                  ->orWhere('tingkat', 'like', "%{$search}%")
                  ->orWhere('nip_wali', 'like', "%{$search}%")
                  ->orWhere('kontak_wali', 'like', "%{$search}%");
            });
        }

        $classrooms = $query->orderBy('jenjang')->orderBy('nama_kelas')->get();

        // Metrik statistik
        $allClassrooms = Classroom::withCount(['students' => function($q) {
            $q->where('status', 'Aktif');
        }])->get();

        $totalKelas = $allClassrooms->count();
        $totalBerwali = $allClassrooms->filter(fn($c) => !empty(trim($c->wali_kelas)))->count();
        $totalBelumBerwali = $totalKelas - $totalBerwali;
        $totalSantri = $allClassrooms->sum('students_count');

        // Kumpulkan daftar saran ustadz/ustadzah (dari data existing classrooms, musyrif dormitories, & users)
        $existingWali = Classroom::whereNotNull('wali_kelas')->where('wali_kelas', '!=', '')->pluck('wali_kelas')->toArray();
        $dormMusyrif = Dormitory::whereNotNull('musyrif')->where('musyrif', '!=', '')->pluck('musyrif')->toArray();
        $userNames = User::pluck('name')->toArray();

        $guruSuggestions = collect(array_merge($existingWali, $dormMusyrif, $userNames))
            ->map(fn($item) => trim($item))
            ->filter(fn($item) => !empty($item) && !str_contains(strtolower($item), 'admin'))
            ->unique()
            ->values()
            ->all();

        return view('admin.siswa.wali_kelas', compact(
            'classrooms',
            'jenjangFilter',
            'statusFilter',
            'search',
            'totalKelas',
            'totalBerwali',
            'totalBelumBerwali',
            'totalSantri',
            'guruSuggestions'
        ));
    }

    /**
     * Perbarui Wali Kelas untuk sebuah rombel kelas.
     */
    public function waliKelasUpdate(Request $request, $id)
    {
        $classroom = Classroom::findOrFail($id);

        $validated = $request->validate([
            'wali_kelas' => 'nullable|string|max:255',
            'kontak_wali' => 'nullable|string|max:50',
            'nip_wali' => 'nullable|string|max:50',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $classroom->update($validated);

        $namaWali = $classroom->wali_kelas ?: 'Dikosongkan (Belum Ada)';
        return redirect()->back()->with('success', "Wali kelas {$classroom->nama_kelas} ({$classroom->jenjang}) berhasil diperbarui: {$namaWali}.");
    }

    /**
     * Perbarui Wali Kelas secara massal/sekaligus (Batch Assign).
     */
    public function waliKelasBulkUpdate(Request $request)
    {
        $assignments = $request->input('assignments', []);

        if (!is_array($assignments) || empty($assignments)) {
            return redirect()->back()->with('error', 'Tidak ada data perubahan wali kelas yang dikirimkan.');
        }

        $updatedCount = 0;
        foreach ($assignments as $classId => $data) {
            $classroom = Classroom::find($classId);
            if ($classroom) {
                $payload = [];
                if (array_key_exists('wali_kelas', $data)) {
                    $payload['wali_kelas'] = !empty(trim($data['wali_kelas'])) ? trim($data['wali_kelas']) : null;
                }
                if (array_key_exists('kontak_wali', $data)) {
                    $payload['kontak_wali'] = !empty(trim($data['kontak_wali'])) ? trim($data['kontak_wali']) : null;
                }
                if (array_key_exists('nip_wali', $data)) {
                    $payload['nip_wali'] = !empty(trim($data['nip_wali'])) ? trim($data['nip_wali']) : null;
                }

                if (!empty($payload)) {
                    $classroom->update($payload);
                    $updatedCount++;
                }
            }
        }

        return redirect()->back()->with('success', "Berhasil memperbarui data wali kelas untuk {$updatedCount} rombel kelas secara serentak!");
    }

    /**
     * Master Jenjang & Kelas: Hapus kelas.
     */
    public function kelasDestroy($id)
    {
        $classroom = Classroom::findOrFail($id);
        $studentCount = Student::where('kelas', $classroom->nama_kelas)->where('status', 'Aktif')->count();

        if ($studentCount > 0) {
            return redirect()->back()->with('error', "Gagal menghapus! Kelas {$classroom->nama_kelas} masih ditempati oleh {$studentCount} santri aktif. Pindahkan santri terlebih dahulu atau lakukan kenaikan/mutasi kelas.");
        }

        $name = $classroom->nama_kelas;
        $classroom->delete();

        return redirect()->route('admin.siswa.kelas.index')->with('success', "Kelas {$name} berhasil dihapus dari sistem.");
    }

    /**
     * Halaman Penempatan / Pembagian Kelas & Asrama Santri Baru.
     * Mendukung pembagian otomatis berdasarkan peringkat CBT atau pilihan bebas admin (suka-suka admin).
     */
    public function penempatanKelasIndex(Request $request)
    {
        $currentYear = $request->input('tahun', date('Y'));
        $jenjangTab = $request->input('jenjang', 'all'); // 'all', 'MTs', 'MA'
        $genderFilter = $request->input('gender');
        $statusKelas = $request->input('status_kelas'); // 'all', 'belum', 'sudah'
        $search = $request->input('q');

        // Otomatis sinkronisasi: jika ada santri PSB 'Diterima' yang belum masuk tabel students, sediakan count
        $importedPsbIds = Student::whereNotNull('psb_registration_id')->pluck('psb_registration_id')->toArray();
        $unimportedPsbCount = PsbRegistration::where('status', 'Diterima')
            ->whereNotIn('id', $importedPsbIds)
            ->count();

        // Query santri baru: Santri dengan tahun_masuk = $currentYear atau yang berasal dari PSB
        $query = Student::with(['psbRegistration', 'dormitory'])
            ->where('status', 'Aktif')
            ->where(function($q) use ($currentYear) {
                $q->where('tahun_masuk', $currentYear)
                  ->orWhereNotNull('psb_registration_id')
                  ->orWhere('kelas', 'like', 'VII%')
                  ->orWhere('kelas', 'like', 'X%');
            });

        if ($jenjangTab === 'MTs') {
            $query->where('jenjang', 'like', '%MTs%');
        } elseif ($jenjangTab === 'MA') {
            $query->where('jenjang', 'like', '%MA%');
        }

        if (!empty($genderFilter)) {
            $query->where('jenis_kelamin', $genderFilter);
        }

        if ($statusKelas === 'belum') {
            $query->where(function($w) {
                $w->whereNull('kelas')
                  ->orWhere('kelas', '')
                  ->orWhere('kelas', 'like', '%Belum%');
            });
        } elseif ($statusKelas === 'sudah') {
            $query->whereNotNull('kelas')
                  ->where('kelas', '!=', '')
                  ->where('kelas', 'not like', '%Belum%');
        }

        if (!empty($search)) {
            $query->where(function($w) use ($search) {
                $w->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%");
            });
        }

        $allStudents = $query->get();

        // Hitung peringkat CBT per kelompok jenjang (MTs & MA)
        $mtsList = $allStudents->filter(fn($s) => str_contains($s->jenjang, 'MTs'))->sortByDesc(function($s) {
            return $s->psbRegistration ? ($s->psbRegistration->nilai_ujian ?? -1) : -1;
        })->values();

        $maList = $allStudents->filter(fn($s) => str_contains($s->jenjang, 'MA'))->sortByDesc(function($s) {
            return $s->psbRegistration ? ($s->psbRegistration->nilai_ujian ?? -1) : -1;
        })->values();

        // Beri nilai peringkat / ranking
        $mtsRank = 1;
        foreach ($mtsList as $s) {
            $s->cbt_rank = ($s->psbRegistration && $s->psbRegistration->nilai_ujian !== null) ? $mtsRank++ : '—';
        }
        $maRank = 1;
        foreach ($maList as $s) {
            $s->cbt_rank = ($s->psbRegistration && $s->psbRegistration->nilai_ujian !== null) ? $maRank++ : '—';
        }

        // Urutkan kembali berdasarkan skor CBT tertinggi
        $students = $allStudents->sortBy(function($s) {
            $score = $s->psbRegistration ? ($s->psbRegistration->nilai_ujian ?? -1) : -1;
            return 1000 - (int) $score;
        })->values();

        // Rombel kelas MTs (Tingkat VII) dan MA (Tingkat X)
        $classroomsMts = Classroom::where('jenjang', 'MTs')
            ->where('tingkat', 'VII')
            ->withCount(['students' => function($q) { $q->where('status', 'Aktif'); }])
            ->orderBy('nama_kelas')
            ->get();

        if ($classroomsMts->isEmpty()) {
            $classroomsMts = Classroom::where('jenjang', 'MTs')
                ->withCount(['students' => function($q) { $q->where('status', 'Aktif'); }])
                ->orderBy('nama_kelas')
                ->get();
        }

        $classroomsMa = Classroom::where('jenjang', 'MA')
            ->where('tingkat', 'X')
            ->withCount(['students' => function($q) { $q->where('status', 'Aktif'); }])
            ->orderBy('nama_kelas')
            ->get();

        if ($classroomsMa->isEmpty()) {
            $classroomsMa = Classroom::where('jenjang', 'MA')
                ->withCount(['students' => function($q) { $q->where('status', 'Aktif'); }])
                ->orderBy('nama_kelas')
                ->get();
        }

        $allClassrooms = Classroom::withCount(['students' => function($q) { $q->where('status', 'Aktif'); }])
            ->orderBy('jenjang')->orderBy('nama_kelas')->get();

        // Asrama Putra dan Putri
        $dormPutra = Dormitory::where('gender', 'Laki-laki')
            ->withCount(['students' => function($q) { $q->where('status', 'Aktif'); }])
            ->orderBy('nama_asrama')->orderBy('kamar')->get();

        $dormPutri = Dormitory::where('gender', 'Perempuan')
            ->withCount(['students' => function($q) { $q->where('status', 'Aktif'); }])
            ->orderBy('nama_asrama')->orderBy('kamar')->get();

        // KPI Ringkas
        $totalSantriBaru = $allStudents->count();
        $sudahAdaKelas = $allStudents->filter(fn($s) => !empty($s->kelas) && !str_contains($s->kelas, 'Belum'))->count();
        $belumAdaKelas = $totalSantriBaru - $sudahAdaKelas;
        $santriMukim = $allStudents->filter(fn($s) => str_contains($s->jenjang, 'Mukim'));
        $mukimBelumKamar = $santriMukim->filter(fn($s) => empty($s->dormitory_id))->count();

        return view('admin.siswa.penempatan_kelas', compact(
            'students',
            'currentYear',
            'jenjangTab',
            'genderFilter',
            'statusKelas',
            'search',
            'unimportedPsbCount',
            'classroomsMts',
            'classroomsMa',
            'allClassrooms',
            'dormPutra',
            'dormPutri',
            'totalSantriBaru',
            'sudahAdaKelas',
            'belumAdaKelas',
            'mukimBelumKamar'
        ));
    }

    /**
     * Proses Pembagian / Plotting Kelas Santri Baru Otomatis Berdasarkan Peringkat CBT.
     */
    public function penempatanKelasRanking(Request $request)
    {
        $request->validate([
            'jenjang' => 'required|string|in:MTs,MA',
            'target_classes' => 'required|array|min:1',
            'mode' => 'required|string|in:unggulan,seimbang',
            'gender_filter' => 'nullable|string|in:all,Laki-laki,Perempuan',
            'auto_asrama' => 'nullable',
        ], [
            'target_classes.required' => 'Pilih minimal satu kelas tujuan rombel.',
            'target_classes.min' => 'Pilih minimal satu kelas tujuan rombel.',
        ]);

        $jenjang = $request->jenjang;
        $targetClasses = $request->target_classes;
        $mode = $request->mode; // 'unggulan' atau 'seimbang'
        $genderFilter = $request->input('gender_filter', 'all');
        $autoAsrama = $request->filled('auto_asrama');

        // Ambil santri baru jenjang tersebut
        $query = Student::with(['psbRegistration', 'dormitory'])
            ->where('status', 'Aktif')
            ->where('jenjang', 'like', "%{$jenjang}%");

        if ($genderFilter !== 'all') {
            $query->where('jenis_kelamin', $genderFilter);
        }

        $students = $query->get();

        if ($students->isEmpty()) {
            return redirect()->back()->with('error', "Tidak ada data santri aktif {$jenjang} yang cocok dengan kriteria.");
        }

        // Urutkan santri berdasarkan skor CBT (nilai_ujian) DESC
        $sortedStudents = $students->sortByDesc(function($s) {
            return $s->psbRegistration ? ($s->psbRegistration->nilai_ujian ?? -1) : -1;
        })->values();

        $numClasses = count($targetClasses);
        $assignedCount = 0;

        if ($mode === 'seimbang') {
            // Distribusi paralel seimbang / Snake distribution (1->A, 2->B, 3->B, 4->A, ...)
            $patternIndex = 0;
            $ascending = true;

            foreach ($sortedStudents as $student) {
                $targetClass = $targetClasses[$patternIndex];
                $student->update(['kelas' => $targetClass]);
                $assignedCount++;

                if ($ascending) {
                    $patternIndex++;
                    if ($patternIndex >= $numClasses) {
                        $patternIndex = $numClasses - 1;
                        $ascending = false;
                    }
                } else {
                    $patternIndex--;
                    if ($patternIndex < 0) {
                        $patternIndex = 0;
                        $ascending = true;
                    }
                }
            }
        } else {
            // Mode Unggulan (Peringkat atas masuk kelas prioritas pertama, lalu kelas berikutnya)
            $classrooms = Classroom::whereIn('nama_kelas', $targetClasses)->get()->keyBy('nama_kelas');

            $currentClassIdx = 0;
            $currentClassCount = 0;

            foreach ($sortedStudents as $student) {
                $targetClassName = $targetClasses[$currentClassIdx];
                $cap = isset($classrooms[$targetClassName]) ? $classrooms[$targetClassName]->kapasitas : 30;

                $student->update(['kelas' => $targetClassName]);
                $assignedCount++;
                $currentClassCount++;

                if ($currentClassCount >= $cap && $currentClassIdx < ($numClasses - 1)) {
                    $currentClassIdx++;
                    $currentClassCount = 0;
                }
            }
        }

        // Jika auto_asrama dicentang: tempatkan santri mukim yang belum ada kamar ke asrama kosong
        $asramaPlaced = 0;
        if ($autoAsrama) {
            $mukimStudents = $sortedStudents->filter(fn($s) => str_contains($s->jenjang, 'Mukim') && empty($s->dormitory_id));
            foreach ($mukimStudents as $st) {
                $availableDorm = Dormitory::where('gender', $st->jenis_kelamin)
                    ->withCount(['students' => function($q) { $q->where('status', 'Aktif'); }])
                    ->get()
                    ->filter(fn($d) => $d->students_count < $d->kapasitas)
                    ->first();

                if ($availableDorm) {
                    $st->update([
                        'dormitory_id' => $availableDorm->id,
                        'kamar_asrama' => $availableDorm->full_name,
                    ]);
                    $asramaPlaced++;
                }
            }
        }

        $classesList = implode(', ', $targetClasses);
        $modeName = $mode === 'unggulan' ? 'Ranking Unggulan' : 'Distribusi Paralel Seimbang';
        $msg = "Alhamdulillah! Berhasil menempatkan {$assignedCount} santri {$jenjang} ke kelas {$classesList} berdasarkan {$modeName}!";
        if ($asramaPlaced > 0) {
            $msg .= " Sekaligus berhasil menempatkan {$asramaPlaced} santri mukim ke kamar asrama kosong.";
        }

        return redirect()->route('admin.siswa.penempatanKelas', ['jenjang' => $jenjang])->with('success', $msg);
    }

    /**
     * Penempatan Kelas & Asrama Santri Baru Manual / Massal (Suka-suka Admin).
     */
    public function penempatanKelasManual(Request $request)
    {
        $request->validate([
            'student_ids' => 'required|array|min:1',
            'kelas' => 'required|string',
            'dormitory_id' => 'nullable',
        ], [
            'student_ids.required' => 'Pilih minimal satu santri yang akan ditempatkan.',
            'kelas.required' => 'Pilih kelas tujuan santri.',
        ]);

        $studentIds = $request->student_ids;
        $kelas = $request->kelas;
        $dormitoryId = $request->dormitory_id;

        $updateData = ['kelas' => $kelas];

        if (!empty($dormitoryId)) {
            $dorm = Dormitory::find($dormitoryId);
            if ($dorm) {
                $updateData['dormitory_id'] = $dorm->id;
                $updateData['kamar_asrama'] = $dorm->full_name;
            }
        }

        Student::whereIn('id', $studentIds)->update($updateData);

        $count = count($studentIds);
        return redirect()->back()->with('success', "Alhamdulillah! Berhasil menempatkan {$count} santri ke kelas {$kelas}!");
    }

    /**
     * Quick Update Kelas & Asrama per Baris Santri (Dropdown Cepat / AJAX).
     */
    public function penempatanKelasQuickUpdate(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'kelas' => 'nullable|string',
            'dormitory_id' => 'nullable',
        ]);

        $student = Student::findOrFail($request->student_id);

        if ($request->has('kelas')) {
            $student->kelas = $request->kelas;
        }

        if ($request->has('dormitory_id')) {
            if (!empty($request->dormitory_id)) {
                $dorm = Dormitory::findOrFail($request->dormitory_id);
                $student->dormitory_id = $dorm->id;
                $student->kamar_asrama = $dorm->full_name;
            } else {
                $student->dormitory_id = null;
                $student->kamar_asrama = null;
            }
        }

        $student->save();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => "Data santri {$student->nama_lengkap} berhasil disimpan!",
                'student' => $student,
            ]);
        }

        return redirect()->back()->with('success', "Data santri {$student->nama_lengkap} berhasil diperbarui!");
    }

    /**
     * Auto-Plotting Santri Mukim Baru ke Kamar Asrama Kosong.
     */
    public function penempatanKelasAutoAsrama(Request $request)
    {
        $unassignedMukim = Student::where('status', 'Aktif')
            ->whereNull('dormitory_id')
            ->where('jenjang', 'like', '%Mukim%')
            ->get();

        if ($unassignedMukim->isEmpty()) {
            return redirect()->back()->with('info', 'Semua santri mukim saat ini sudah mendapatkan kamar asrama.');
        }

        $placed = 0;
        foreach ($unassignedMukim as $st) {
            $availableDorm = Dormitory::where('gender', $st->jenis_kelamin)
                ->withCount(['students' => function($q) { $q->where('status', 'Aktif'); }])
                ->get()
                ->filter(fn($d) => $d->students_count < $d->kapasitas)
                ->first();

            if ($availableDorm) {
                $st->update([
                    'dormitory_id' => $availableDorm->id,
                    'kamar_asrama' => $availableDorm->full_name,
                ]);
                $placed++;
            }
        }

        return redirect()->back()->with('success', "Alhamdulillah! Berhasil menempatkan {$placed} santri mukim ke kamar asrama kosong yang sesuai gender.");
    }

    /**
     * Manajemen Asrama & Kamar: Tampilkan KPI hunian, kamar, dan santri mukim.
     */
    public function asramaIndex(Request $request)
    {
        $query = Dormitory::with(['students' => function($q) {
            $q->where('status', 'Aktif');
        }]);

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($w) use ($q) {
                $w->where('nama_asrama', 'like', "%{$q}%")
                  ->orWhere('kamar', 'like', "%{$q}%")
                  ->orWhere('musyrif', 'like', "%{$q}%");
            });
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        $dormitories = $query->orderBy('gender')->orderBy('nama_asrama')->orderBy('kamar')->get();

        // KPI
        $totalKamar = Dormitory::count();
        $totalKapasitas = Dormitory::sum('kapasitas');
        $totalSantriBermukim = Student::where('status', 'Aktif')->whereNotNull('dormitory_id')->count();
        $sisaRanjang = max(0, $totalKapasitas - $totalSantriBermukim);
        $totalKamarPutra = Dormitory::where('gender', 'Laki-laki')->count();
        $totalKamarPutri = Dormitory::where('gender', 'Perempuan')->count();

        // Santri aktif mukim yang belum mendapat kamar
        $unassignedStudents = Student::where('status', 'Aktif')
            ->whereNull('dormitory_id')
            ->where(function($q) {
                $q->where('jenjang', 'like', '%Mukim%')
                  ->orWhere('kamar_asrama', 'like', '%Belum%')
                  ->orWhereNull('kamar_asrama');
            })
            ->orderBy('nama_lengkap')
            ->get(['id', 'nama_lengkap', 'nis', 'kelas', 'jenjang', 'jenis_kelamin']);

        return view('admin.siswa.asrama', compact(
            'dormitories',
            'totalKamar',
            'totalKapasitas',
            'totalSantriBermukim',
            'sisaRanjang',
            'totalKamarPutra',
            'totalKamarPutri',
            'unassignedStudents'
        ));
    }

    /**
     * Tambah Kamar Asrama Baru.
     */
    public function asramaStore(Request $request)
    {
        $validated = $request->validate([
            'nama_asrama' => 'required|string|max:100',
            'kamar' => 'required|string|max:50',
            'gender' => 'required|string|in:Laki-laki,Perempuan',
            'kapasitas' => 'required|integer|min:1|max:50',
            'musyrif' => 'nullable|string|max:100',
            'lokasi' => 'nullable|string|max:100',
            'keterangan' => 'nullable|string|max:255',
        ], [
            'nama_asrama.required' => 'Nama gedung/asrama wajib diisi.',
            'kamar.required' => 'Nama/Nomor kamar wajib diisi.',
            'gender.required' => 'Pilih peruntukan gender asrama (Laki-laki / Perempuan).',
            'kapasitas.required' => 'Kapasitas kamar wajib diisi.',
            'kapasitas.min' => 'Kapasitas minimal 1 santri.',
        ]);

        Dormitory::create($validated);

        return redirect()->route('admin.siswa.asrama.index')->with('success', "Kamar {$validated['nama_asrama']} - {$validated['kamar']} berhasil ditambahkan!");
    }

    /**
     * Update Data Kamar Asrama.
     */
    public function asramaUpdate(Request $request, $id)
    {
        $dormitory = Dormitory::findOrFail($id);

        $validated = $request->validate([
            'nama_asrama' => 'required|string|max:100',
            'kamar' => 'required|string|max:50',
            'gender' => 'required|string|in:Laki-laki,Perempuan',
            'kapasitas' => 'required|integer|min:1|max:50',
            'musyrif' => 'nullable|string|max:100',
            'lokasi' => 'nullable|string|max:100',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $dormitory->update($validated);

        // Perbarui string kamar_asrama pada santri yang menghuni
        Student::where('dormitory_id', $dormitory->id)->update([
            'kamar_asrama' => "{$dormitory->nama_asrama} - {$dormitory->kamar}"
        ]);

        return redirect()->route('admin.siswa.asrama.index')->with('success', "Data kamar {$dormitory->full_name} berhasil diperbarui!");
    }

    /**
     * Hapus Kamar Asrama.
     */
    public function asramaDestroy($id)
    {
        $dormitory = Dormitory::findOrFail($id);
        $residentCount = $dormitory->students()->where('status', 'Aktif')->count();

        if ($residentCount > 0) {
            return redirect()->back()->with('error', "Gagal menghapus! Kamar {$dormitory->full_name} masih dihuni oleh {$residentCount} santri aktif. Kosongkan penghuni kamar terlebih dahulu.");
        }

        $name = $dormitory->full_name;
        $dormitory->delete();

        return redirect()->route('admin.siswa.asrama.index')->with('success', "Kamar {$name} berhasil dihapus dari sistem.");
    }

    /**
     * Plotting / Masukkan Santri ke Kamar Asrama.
     */
    public function asramaAssignStudent(Request $request)
    {
        $request->validate([
            'dormitory_id' => 'required|exists:dormitories,id',
            'student_id' => 'required|exists:students,id',
        ], [
            'dormitory_id.required' => 'Pilih kamar tujuan.',
            'student_id.required' => 'Pilih santri yang akan ditempatkan.',
        ]);

        $dormitory = Dormitory::findOrFail($request->dormitory_id);
        $student = Student::findOrFail($request->student_id);

        // Cek kapasitas
        if ($dormitory->is_penuh) {
            return redirect()->back()->with('error', "Gagal! Kamar {$dormitory->full_name} sudah PENUH (Kapasitas maksimal {$dormitory->kapasitas} santri).");
        }

        $student->update([
            'dormitory_id' => $dormitory->id,
            'kamar_asrama' => $dormitory->full_name,
        ]);

        return redirect()->back()->with('success', "Santri {$student->nama_lengkap} berhasil ditempatkan di kamar {$dormitory->full_name}!");
    }

    /**
     * Keluarkan Santri dari Kamar Asrama (Unassign).
     */
    public function asramaRemoveStudent($studentId)
    {
        $student = Student::findOrFail($studentId);
        $oldRoom = $student->kamar_asrama;

        $student->update([
            'dormitory_id' => null,
            'kamar_asrama' => null,
        ]);

        return redirect()->back()->with('success', "Santri {$student->nama_lengkap} berhasil dikeluarkan dari kamar {$oldRoom}.");
    }
}


