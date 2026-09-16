<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\PsbRegistration;
use App\Models\Setting;
use App\Models\Classroom;
use App\Models\StudentPayment;
use App\Models\StudentMutation;
use App\Models\Dormitory;
use App\Models\User;
use App\Models\StudentBill;
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
            $jen = $request->jenjang;
            if ($jen === 'MTs Mukim') {
                $query->where('jenjang', 'like', '%MTs%')
                      ->where(function($q) {
                          $q->where('jenjang', 'like', '%Mukim%')
                            ->orWhere('jenjang', 'like', '%Pondok%')
                            ->orWhereNotNull('dormitory_id');
                      });
            } elseif ($jen === 'MTs Laju') {
                $query->where('jenjang', 'like', '%MTs%')
                      ->where(function($q) {
                          $q->where('jenjang', 'like', '%Laju%')
                            ->orWhere('kamar_asrama', 'like', '%Laju%')
                            ->orWhereNull('kamar_asrama');
                      });
            } elseif ($jen === 'MA Mukim') {
                $query->where('jenjang', 'like', '%MA%')
                      ->where(function($q) {
                          $q->where('jenjang', 'like', '%Mukim%')
                            ->orWhere('jenjang', 'like', '%Pondok%')
                            ->orWhereNotNull('dormitory_id');
                      });
            } elseif ($jen === 'MA Laju') {
                $query->where('jenjang', 'like', '%MA%')
                      ->where(function($q) {
                          $q->where('jenjang', 'like', '%Laju%')
                            ->orWhere('kamar_asrama', 'like', '%Laju%')
                            ->orWhereNull('kamar_asrama');
                      });
            } else {
                $query->where('jenjang', 'like', "%{$jen}%");
            }
        }

        if ($request->filled('status')) {
            if ($request->status === 'Mutasi' || $request->status === 'Mutasi Keluar') {
                $query->whereIn('status', ['Mutasi', 'Mutasi Keluar']);
            } else {
                $query->where('status', $request->status);
            }
        }

        if ($request->filled('kelas')) {
            $query->where('kelas', $request->kelas);
        }

        if ($request->filled('tahun_masuk')) {
            $query->where('tahun_masuk', $request->tahun_masuk);
        }

        if ($request->filled('jenis_kelamin')) {
            $query->where('jenis_kelamin', $request->jenis_kelamin);
        }

        $students = $query->paginate(20)->withQueryString();

        $stats = [
            'total'      => Student::count(),
            'aktif'      => Student::where('status', 'Aktif')->count(),
            'mts'        => Student::where('jenjang', 'like', '%MTs%')->count(),
            'ma'         => Student::where('jenjang', 'like', '%MA%')->count(),
            'laki'       => Student::where('jenis_kelamin', 'Laki-laki')->count(),
            'perempuan'  => Student::where('jenis_kelamin', 'Perempuan')->count(),
            'mukim'      => Student::where(function($q) {
                $q->where('jenjang', 'like', '%Mukim%')
                  ->orWhere('jenjang', 'like', '%Pondok%')
                  ->orWhereNotNull('dormitory_id');
            })->count(),
            'laju'       => Student::where('jenjang', 'like', '%Laju%')->count(),
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
     * Tampilkan kartu biodata detail santri beserta riwayat tagihan & pembayaran.
     */
    public function show($id)
    {
        $student = Student::with(['psbRegistration', 'payments', 'bills'])->findOrFail($id);
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

        // Hubungkan riwayat pembayaran PSB ke ID santri baru
        \App\Models\StudentPayment::where('psb_registration_id', $reg->id)
            ->whereNull('student_id')
            ->update(['student_id' => $student->id]);

        // Terbitkan / sinkronisasikan tagihan sisa daftar ulang PSB ke rekening santri
        $bill = $this->syncPsbDaftarUlangBill($student, $reg);
        $sisaTagihanFmt = 'Rp ' . number_format($bill->sisa_tagihan, 0, ',', '.');

        return redirect()->back()->with('success', "Alhamdulillah! Calon santri {$reg->nama_lengkap} ({$reg->no_registrasi}) resmi didaftarkan sebagai Santri Aktif! NIS: {$student->nis}, Kelas: {$student->kelas}, Sisa Tunggakan Daftar Ulang: {$sisaTagihanFmt}, Password Login: {$defaultPassword}");
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
                'tahun_masuk' => $tahunMasuk,
                'status' => 'Aktif',
                'nama_wali' => $reg->nama_wali ?: $reg->ayah_nama,
                'no_whatsapp' => $reg->no_whatsapp ?: $reg->ayah_telepon,
                'alamat' => $reg->alamat_lengkap ?: $reg->alamat,
                'foto' => $reg->pas_foto,
                'password' => Hash::make($defaultPassword),
                'catatan' => "Impor Massal PSB TA {$tahunMasuk} (No Reg: {$reg->no_registrasi})",
            ]);

            // Hubungkan riwayat pembayaran PSB ke ID santri baru
            \App\Models\StudentPayment::where('psb_registration_id', $reg->id)
                ->whereNull('student_id')
                ->update(['student_id' => $student->id]);

            // Sinkronisasi tagihan tunggakan daftar ulang PSB santri aktif
            $this->syncPsbDaftarUlangBill($student, $reg);

            $successCount++;
        }

        return redirect()->route('admin.siswa.index')->with('success', "Berhasil mengimpor massal {$successCount} calon santri yang lulus PSB ke Data Santri Aktif! Tagihan sisa daftar ulang otomatis diterbitkan ke buku tagihan santri.");
    }

    /**
     * Sinkronisasi otomatis penerbitan / update tagihan sisa daftar ulang PSB santri baru ke StudentBill.
     */
    public function syncPsbDaftarUlangBill(Student $student, PsbRegistration $reg): StudentBill
    {
        $tarif = PsbRegistration::getTarifBreakdown($reg->jenjang);
        $standardDaftarUlang = (float) $tarif['sisa_daftar_ulang'];

        // Cek apakah santri sudah memiliki tagihan daftar ulang
        $bill = StudentBill::where('student_id', $student->id)
            ->where(function ($q) {
                $q->where('pos_biaya', 'DAFTAR ULANG')
                  ->orWhere('pos_biaya', 'like', '%DAFTAR ULANG%')
                  ->orWhere('kategori', 'daftar_ulang');
            })
            ->first();

        // Hitung total cicilan/pembayaran daftar ulang yang sudah dibayar (di luar pendaftaran 200rb)
        $payments = StudentPayment::where(function ($q) use ($reg, $student) {
            $q->where('psb_registration_id', $reg->id)
              ->orWhere('student_id', $student->id);
        })
        ->where('status', 'Lunas')
        ->where('jenis_pembayaran', '!=', 'BIAYA PENDAFTARAN PSB')
        ->get();

        $terbayar = (float) $payments->sum('nominal');
        $sisa = max(0, $standardDaftarUlang - $terbayar);
        $status = $sisa <= 0 ? 'Lunas' : ($terbayar > 0 ? 'Cicilan' : 'Belum Bayar');

        if (!$bill) {
            $bill = StudentBill::create([
                'student_id' => $student->id,
                'kategori' => 'daftar_ulang',
                'pos_biaya' => 'DAFTAR ULANG',
                'judul_tagihan' => "Biaya Daftar Ulang PSB ({$tarif['kategori_label']})",
                'bulan' => 'Juli',
                'tahun' => $student->tahun_masuk ?: date('Y'),
                'nominal_asli' => $standardDaftarUlang,
                'nominal_potongan' => 0,
                'nominal_tagihan' => $standardDaftarUlang,
                'nominal_bayar' => $terbayar,
                'sisa_tagihan' => $sisa,
                'status' => $status,
                'jatuh_tempo' => Carbon::now()->addMonths(1),
                'created_by' => auth()->user()->name ?? 'Sistem PSB',
            ]);
        } else {
            if ($bill->status !== 'Lunas') {
                $bill->nominal_asli = $standardDaftarUlang;
                $bill->nominal_tagihan = max(0, $standardDaftarUlang - $bill->nominal_potongan);
                $bill->nominal_bayar = $terbayar;
                $bill->sisa_tagihan = max(0, $bill->nominal_tagihan - $terbayar);
                $bill->status = $bill->sisa_tagihan <= 0 ? 'Lunas' : ($terbayar > 0 ? 'Cicilan' : 'Belum Bayar');
                $bill->save();
            }
        }

        // Hubungkan payment items ke bill jika ada payment yang belum terhubung
        foreach ($payments as $payment) {
            \App\Models\StudentPaymentItem::where('payment_id', $payment->id)
                ->whereNull('student_bill_id')
                ->update(['student_bill_id' => $bill->id]);
        }

        return $bill;
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
     * Unduh template file Excel / CSV untuk Tambah Santri Secara Massal beserta Tagihan Tunggakan Lalu.
     */
    public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();
        
        // Sheet 1: Data Santri & Tunggakan
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Santri & Tunggakan');

        // Header Kolom Ringkas & Efisien Sesuai Kebutuhan Riil Bendahara (Hanya 10 Kolom)
        $columns = [
            'A' => ['title' => 'nama_lengkap', 'label' => 'Nama Lengkap Santri (Wajib)'],
            'B' => ['title' => 'nis', 'label' => 'NIS (Kosongkan jika santri baru)'],
            'C' => ['title' => 'jenis_kelamin', 'label' => 'L/P (Laki-laki / Perempuan)'],
            'D' => ['title' => 'tanggal_lahir', 'label' => 'Tanggal Lahir (DD/MM/YYYY)'],
            'E' => ['title' => 'kelas', 'label' => 'Kelas (VII-A, VII-B, X-A, dll)'],
            'F' => ['title' => 'nama_wali', 'label' => 'Nama Orang Tua / Wali'],
            'G' => ['title' => 'no_whatsapp', 'label' => 'No. WhatsApp Wali'],
            // Kolom Finansial Tunggakan Masa Lalu
            'H' => ['title' => 'tunggakan_daftar_ulang', 'label' => 'Sisa Uang Pangkal / Daftar Ulang (Rp)'],
            'I' => ['title' => 'tunggakan_spp_bulanan', 'label' => 'Tunggakan SPP Bulanan (Rp)'],
            'J' => ['title' => 'keterangan_tunggakan', 'label' => 'Keterangan Tunggakan Lalu'],
        ];

        foreach ($columns as $col => $info) {
            $sheet->setCellValueExplicit($col . '1', $info['title'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        }

        // Styling Header A1:G1 (Emerald Green) - Identitas Pokok Santri
        $sheet->getStyle('A1:G1')->applyFromArray([
            'font' => [
                'name' => 'Calibri',
                'bold' => true,
                'color' => ['argb' => 'FFFFFFFF'],
                'size' => 11,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF059669'], // Emerald Green
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FFD1D5DB'],
                ],
            ],
        ]);

        // Styling Header H1:J1 (Warm Amber) - Tagihan & Tunggakan Masa Lalu
        $sheet->getStyle('H1:J1')->applyFromArray([
            'font' => [
                'name' => 'Calibri',
                'bold' => true,
                'color' => ['argb' => 'FFFFFFFF'],
                'size' => 11,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFD97706'], // Warm Amber
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FFD1D5DB'],
                ],
            ],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(28);

        // Contoh Data 1 (Santri Lama Ada Tunggakan Uang Pangkal & SPP)
        $sampleRow1 = [
            'Ahmad Fauzan Hidayat',
            '20250012',
            'L',
            '15/05/2012',
            'VIII-A',
            'H. Budi Santoso',
            '081234567890',
            '1250000',
            '410000',
            'Sisa Uang Pangkal 2025 & SPP Juni 2025',
        ];

        // Contoh Data 2 (Santri Baru Murni Tanpa Tunggakan)
        $sampleRow2 = [
            'Nurul Aisyah Zahra',
            '',
            'P',
            '20/08/2009',
            'X-A',
            'Drs. Subhan',
            '085678901234',
            '0',
            '0',
            'Lunas / Tanpa Tunggakan',
        ];

        // Contoh Data 3 (Santri dengan Tunggakan SPP Saja)
        $sampleRow3 = [
            'Muhammad Rizky Pratama',
            '',
            'L',
            '10/11/2011',
            'VII-A',
            'Ahmad Syarifudin',
            '081398765432',
            '0',
            '820000',
            'SPP Mei-Juni 2025 (2 Bulan)',
        ];

        $samples = [$sampleRow1, $sampleRow2, $sampleRow3];
        $rowIdx = 2;
        foreach ($samples as $sample) {
            $colLetter = 'A';
            foreach ($sample as $val) {
                $sheet->setCellValueExplicit($colLetter . $rowIdx, (string)$val, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $colLetter++;
            }
            $sheet->getStyle("A{$rowIdx}:J{$rowIdx}")->applyFromArray([
                'font' => ['name' => 'Calibri', 'size' => 10],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
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

        // Sheet 2: Petunjuk Praktis Bendahara
        $guideSheet = $spreadsheet->createSheet();
        $guideSheet->setTitle('Petunjuk Singkat');

        $guideSheet->setCellValue('A1', 'PETUNJUK PENGISIAN DATA SANTRI & TUNGGAKAN (HANYA 10 KOLOM)');
        $guideSheet->getStyle('A1')->applyFromArray([
            'font' => ['name' => 'Calibri', 'bold' => true, 'size' => 13, 'color' => ['argb' => 'FF059669']]
        ]);

        $guideData = [
            ['Nama Kolom', 'Wajib / Opsional', 'Penjelasan & Contoh Pengisian'],
            ['nama_lengkap', 'Wajib', 'Nama lengkap santri sesuai berkas resmi.'],
            ['nis', 'Opsional', 'Nomor Induk Santri. Kosongkan jika santri baru (otomatis digenerate sistem). Isi NIS jika santri lama sudah ada di sistem agar tunggakan langsung tersambung.'],
            ['jenis_kelamin', 'Wajib', 'Ketik "L" untuk Laki-laki atau "P" untuk Perempuan.'],
            ['tanggal_lahir', 'Wajib', 'Format DD/MM/YYYY (contoh: 15/05/2012). Tanggal ini otomatis menjadi password login santri (contoh: 15052012).'],
            ['kelas', 'Wajib', 'Nama kelas santri, contoh: VII-A, VII-B, VIII-A, X-A, XI-A. (Jenjang MTs/MA otomatis terdeteksi dari nama kelas).'],
            ['nama_wali', 'Opsional', 'Nama ayah / ibu / wali santri.'],
            ['no_whatsapp', 'Opsional', 'Nomor WhatsApp wali untuk pengiriman tagihan & bukti pembayaran (contoh: 081234567890).'],
            ['tunggakan_daftar_ulang', 'Opsional', 'Nominal sisa Uang Pangkal / Daftar Ulang yang belum lunas. Ketik angka saja tanpa titik (contoh: 1250000). Isi 0 jika lunas.'],
            ['tunggakan_spp_bulanan', 'Opsional', 'Total sisa tunggakan SPP / syahriyah bulanan masa lalu (contoh: 410000). Isi 0 jika lunas.'],
            ['keterangan_tunggakan', 'Opsional', 'Rincian tunggakan agar wali paham (contoh: "Sisa Uang Pangkal 2025 & SPP Juni 2025").'],
        ];

        $gRow = 3;
        foreach ($guideData as $idx => $gItem) {
            $guideSheet->setCellValue('A' . $gRow, $gItem[0]);
            $guideSheet->setCellValue('B' . $gRow, $gItem[1]);
            $guideSheet->setCellValue('C' . $gRow, $gItem[2]);

            if ($idx === 0) {
                $guideSheet->getStyle("A{$gRow}:C{$gRow}")->applyFromArray([
                    'font' => ['name' => 'Calibri', 'bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF1F2937']],
                    'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                ]);
            } else {
                $guideSheet->getStyle("A{$gRow}:C{$gRow}")->applyFromArray([
                    'font' => ['name' => 'Calibri', 'size' => 10],
                    'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFE5E7EB']]],
                ]);
            }
            $guideSheet->getRowDimension($gRow)->setRowHeight(22);
            $gRow++;
        }

        $guideSheet->getColumnDimension('A')->setWidth(26);
        $guideSheet->getColumnDimension('B')->setWidth(20);
        $guideSheet->getColumnDimension('C')->setWidth(85);

        // Kembalikan active sheet ke Sheet 0
        $spreadsheet->setActiveSheetIndex(0);

        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, 'Format_Import_Santri_Dan_Tunggakan_Hidayatullah.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    /**
     * Import santri massal melalui file format Excel (.xlsx / .xls) beserta tagihan tunggakan lalu.
     */
    public function importExcel(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|file|mimes:xlsx,xls,csv,txt|max:15360',
        ], [
            'file_excel.required' => 'Pilih file template Excel (.xlsx) yang telah diisi data santri & tunggakan.',
            'file_excel.mimes' => 'Format file harus berupa Excel (.xlsx atau .xls).',
        ]);

        $file = $request->file('file_excel');
        $ext = strtolower($file->getClientOriginalExtension());
        $importedStudents = 0;
        $existingStudents = 0;
        $createdBills = 0;
        $totalNominalBills = 0;
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
                        $cleanKey = trim(strtolower(str_replace([' ', '-', '.'], '_', (string)$key)));
                        $item[$cleanKey] = isset($row[$idx]) ? trim($row[$idx]) : null;
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

            // Helper untuk membersihkan angka / format mata uang Rupiah
            $cleanNominal = function($val) {
                if (is_null($val) || trim((string)$val) === '') return 0;
                $s = trim((string)$val);
                $s = preg_replace('/^(rp|idr)\s*/i', '', $s);
                $s = preg_replace('/[,.]00$/', '', $s);
                $s = preg_replace('/[^0-9]/', '', $s);
                return (float) $s;
            };

            // Helper untuk mencari nilai dengan berbagai variasi alias header
            $findValue = function($row, $aliases) {
                foreach ($aliases as $alias) {
                    $cleanAlias = strtolower(str_replace([' ', '-', '.'], '_', $alias));
                    if (isset($row[$cleanAlias]) && trim((string)$row[$cleanAlias]) !== '') {
                        return trim((string)$row[$cleanAlias]);
                    }
                }
                return null;
            };

            // Helper untuk parsing tanggal jatuh tempo
            $parseJatuhTempo = function($val) {
                if (empty($val) || trim((string)$val) === '') {
                    return Carbon::now()->addDays(30)->toDateString();
                }
                $raw = trim((string)$val);
                try {
                    if (is_numeric($raw) && (float)$raw > 20000) {
                        $dt = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((float)$raw);
                        return $dt->format('Y-m-d');
                    }
                    $clean = str_replace('/', '-', $raw);
                    return Carbon::parse($clean)->format('Y-m-d');
                } catch (\Throwable $e) {
                    return Carbon::now()->addDays(30)->toDateString();
                }
            };

            $creatorName = auth()->user()->name ?? 'Import Massal Bendahara';

            // Proses setiap baris data
            foreach ($rows as $data) {
                $nama = $findValue($data, ['nama_lengkap', 'nama', 'nama_santri']);
                if (empty($nama)) {
                    $skipped++;
                    continue;
                }

                $tahunMasuk = $findValue($data, ['tahun_masuk', 'angkatan']) ?: date('Y');
                $nisInput = $findValue($data, ['nis', 'no_induk', 'nomor_induk']);
                $usernameInput = $findValue($data, ['username', 'user_id']);
                $nisnInput = $findValue($data, ['nisn']);
                $jkRaw = $findValue($data, ['jenis_kelamin', 'jk', 'gender']) ?: '';
                $kelasInput = $findValue($data, ['kelas', 'rombel']) ?: 'VII-A';
                $jenjangInput = $findValue($data, ['jenjang', 'tingkat']);
                if (empty($jenjangInput)) {
                    if (preg_match('/^(vii|viii|ix|7|8|9)/i', (string)$kelasInput)) {
                        $jenjangInput = 'MTs Pondok Mukim';
                    } elseif (preg_match('/^(x|xi|xii|10|11|12)/i', (string)$kelasInput)) {
                        $jenjangInput = 'MA Pondok Mukim';
                    } else {
                        $jenjangInput = 'MTs Pondok Mukim';
                    }
                }
                $kamarInput = $findValue($data, ['kamar_asrama', 'kamar', 'asrama']);
                $namaWali = $findValue($data, ['nama_wali', 'wali', 'orang_tua', 'nama_ortu']);
                $noWhatsapp = $findValue($data, ['no_whatsapp', 'whatsapp', 'no_wa', 'no_hp', 'telepon']);
                $alamat = $findValue($data, ['alamat', 'domisili']);
                $catatan = $findValue($data, ['catatan', 'keterangan_santri']);

                // Parsing Data Tagihan / Tunggakan Masa Lalu
                $du = $cleanNominal($findValue($data, ['tunggakan_daftar_ulang', 'sisa_daftar_ulang', 'tunggakan_pangkal', 'uang_pangkal_lalu', 'daftar_ulang']));
                $spp = $cleanNominal($findValue($data, ['tunggakan_spp_bulanan', 'tunggakan_spp', 'tunggakan_syahriyah', 'spp_lalu', 'sisa_spp', 'spp']));
                $lain = $cleanNominal($findValue($data, ['tunggakan_lainnya', 'tunggakan_lain', 'tunggakan_pos_lain', 'biaya_lainnya', 'tagihan_lain']));
                $totalLalu = $cleanNominal($findValue($data, ['total_tunggakan_lalu', 'total_tunggakan', 'tunggakan_lalu', 'total_piutang']));
                $ketTunggakan = $findValue($data, ['keterangan_tunggakan', 'catatan_tunggakan', 'rincian_tunggakan', 'keterangan_tagihan', 'keterangan']);
                $tempoRaw = $findValue($data, ['jatuh_tempo_tunggakan', 'jatuh_tempo', 'tgl_jatuh_tempo', 'due_date']);
                $jatuhTempo = $parseJatuhTempo($tempoRaw);

                // Pencocokan Asrama Otomatis & Auto-Create jika Kamar Belum Ada di Sistem
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
                    } else {
                        // Periksa apakah ini nama kamar riil (bukan laju / non-mukim)
                        $cleanKamarLower = strtolower(trim($kamarInput));
                        $ignoredKeywords = ['laju', 'non mukim', 'non-mukim', 'pulang', 'belum', 'tidak ada', 'tdk ada', 'none', '-'];
                        $isIgnored = false;
                        foreach ($ignoredKeywords as $kw) {
                            if ($cleanKamarLower === $kw || str_contains($cleanKamarLower, $kw)) {
                                $isIgnored = true;
                                break;
                            }
                        }
                        if (!$isIgnored && strlen($kamarInput) >= 3) {
                            // Otomatis daftarkan kamar baru ke master asrama di sistem
                            $genderKamar = (str_starts_with(strtoupper($jkRaw ?: ''), 'P')) ? 'Perempuan' : 'Laki-laki';
                            $namaAsrama = ($genderKamar === 'Perempuan') ? 'Asrama Putri' : 'Asrama Putra';

                            $newDorm = Dormitory::create([
                                'nama_asrama' => $namaAsrama,
                                'kamar' => $kamarInput,
                                'gender' => $genderKamar,
                                'kapasitas' => 8,
                                'musyrif' => 'Belum Ditentukan',
                                'lokasi' => 'Kompleks Pesantren',
                                'keterangan' => 'Dibuat otomatis dari Import Massal Santri',
                            ]);
                            $dormitoryId = $newDorm->id;
                            $kamarInput = $newDorm->full_name;
                            // Tambahkan ke koleksi $dormitories agar baris berikutnya langsung mencocokkan kamar yang sama
                            $dormitories->push($newDorm);
                        }
                    }
                }

                // Cek apakah santri sudah pernah terdaftar (berdasarkan NIS, NISN, atau Username)
                $student = null;
                if (!empty($nisInput)) {
                    $student = Student::where('nis', $nisInput)->first();
                }
                if (!$student && !empty($nisnInput)) {
                    $student = Student::where('nisn', $nisnInput)->first();
                }
                if (!$student && !empty($usernameInput)) {
                    $student = Student::where('username', $usernameInput)->first();
                }

                if ($student) {
                    $existingStudents++;
                    // Perbarui profil santri jika ada data baru yang terisi
                    if (empty($student->no_whatsapp) && !empty($noWhatsapp)) $student->no_whatsapp = $noWhatsapp;
                    if (empty($student->nama_wali) && !empty($namaWali)) $student->nama_wali = $namaWali;
                    if (empty($student->alamat) && !empty($alamat)) $student->alamat = $alamat;
                    if (!empty($dormitoryId) && empty($student->dormitory_id)) {
                        $student->dormitory_id = $dormitoryId;
                        $student->kamar_asrama = $kamarInput;
                    }
                    $student->save();
                } else {
                    // Penentuan NIS Baru
                    $nis = $nisInput;
                    if (empty($nis)) {
                        $count = Student::where('tahun_masuk', $tahunMasuk)->count() + 1;
                        $nis = $tahunMasuk . str_pad($count, 4, '0', STR_PAD_LEFT);
                    }
                    if (Student::where('nis', $nis)->exists()) {
                        $nis = $nis . '-' . rand(10, 99);
                    }

                    // Penentuan Username
                    $username = $usernameInput ?: ($nisnInput ?: ('santri_' . $nis));
                    if (Student::where('username', $username)->exists()) {
                        $username = $username . '_' . rand(10, 99);
                    }

                    // Password Tanggal Lahir (DDMMYYYY)
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

                    // Tentukan Jenis Kelamin
                    $jk = (str_starts_with(strtoupper($jkRaw), 'P')) ? 'Perempuan' : 'Laki-laki';

                    $student = Student::create([
                        'nis' => $nis,
                        'username' => $username,
                        'nisn' => $nisnInput ?: null,
                        'nama_lengkap' => $nama,
                        'jenis_kelamin' => $jk,
                        'tanggal_lahir' => $tglLahirDb,
                        'jenjang' => $jenjangInput,
                        'kelas' => $kelasInput,
                        'kamar_asrama' => $kamarInput,
                        'dormitory_id' => $dormitoryId,
                        'tahun_masuk' => $tahunMasuk,
                        'status' => 'Aktif',
                        'nama_wali' => $namaWali ?: null,
                        'no_whatsapp' => $noWhatsapp ?: null,
                        'alamat' => $alamat ?: null,
                        'catatan' => $catatan ?: 'Import Massal Santri & Tunggakan',
                        'password' => Hash::make($defaultPassword),
                    ]);

                    $importedStudents++;
                }

                // ============================================================
                // PEMBUATAN TAGIHAN / TUNGGAKAN MASA LALU (KEUANGAN BENDAHARA)
                // ============================================================

                // 1. Sisa Daftar Ulang / Uang Pangkal Masa Lalu
                if ($du > 0) {
                    $billTitle = 'Sisa Tunggakan Daftar Ulang / Uang Pangkal Lalu' . (!empty($ketTunggakan) ? " ({$ketTunggakan})" : "");
                    $existBill = StudentBill::where('student_id', $student->id)
                        ->where('kategori', 'daftar_ulang')
                        ->where('pos_biaya', 'DAFTAR ULANG')
                        ->where(function($q) use ($billTitle) {
                            $q->where('judul_tagihan', $billTitle)
                              ->orWhere('judul_tagihan', 'like', 'Sisa Tunggakan Daftar Ulang%');
                        })
                        ->first();
                    if (!$existBill) {
                        StudentBill::create([
                            'student_id' => $student->id,
                            'kategori' => 'daftar_ulang',
                            'pos_biaya' => 'DAFTAR ULANG',
                            'judul_tagihan' => $billTitle,
                            'bulan' => 'Juli',
                            'tahun' => (int)($student->tahun_masuk ?: date('Y')),
                            'nominal_asli' => $du,
                            'nominal_potongan' => 0,
                            'nominal_tagihan' => $du,
                            'nominal_bayar' => 0,
                            'sisa_tagihan' => $du,
                            'status' => 'Belum Bayar',
                            'jatuh_tempo' => $jatuhTempo,
                            'created_by' => $creatorName,
                        ]);
                        $createdBills++;
                        $totalNominalBills += $du;
                    }
                }

                // 2. Tunggakan SPP / Syahriyah Bulanan Masa Lalu
                if ($spp > 0) {
                    $billTitle = 'Tunggakan SPP / Syahriyah Masa Lalu' . (!empty($ketTunggakan) ? " ({$ketTunggakan})" : "");
                    $existBill = StudentBill::where('student_id', $student->id)
                        ->where('kategori', 'bulanan')
                        ->where('pos_biaya', 'SYAHRIYAH')
                        ->where(function($q) use ($billTitle) {
                            $q->where('judul_tagihan', $billTitle)
                              ->orWhere('judul_tagihan', 'like', 'Tunggakan SPP / Syahriyah Masa Lalu%');
                        })
                        ->first();
                    if (!$existBill) {
                        StudentBill::create([
                            'student_id' => $student->id,
                            'kategori' => 'bulanan',
                            'pos_biaya' => 'SYAHRIYAH',
                            'judul_tagihan' => $billTitle,
                            'bulan' => 'Lalu',
                            'tahun' => (int)($student->tahun_masuk ?: date('Y')) - 1,
                            'nominal_asli' => $spp,
                            'nominal_potongan' => 0,
                            'nominal_tagihan' => $spp,
                            'nominal_bayar' => 0,
                            'sisa_tagihan' => $spp,
                            'status' => 'Belum Bayar',
                            'jatuh_tempo' => $jatuhTempo,
                            'created_by' => $creatorName,
                        ]);
                        $createdBills++;
                        $totalNominalBills += $spp;
                    }
                }

                // 3. Tunggakan Pos Lainnya (Kitab, Seragam, Kegiatan, dll)
                if ($lain > 0) {
                    $billTitle = !empty($ketTunggakan) ? "Tunggakan Pos Lain: {$ketTunggakan}" : 'Tunggakan Biaya Pendidikan Lainnya';
                    $existBill = StudentBill::where('student_id', $student->id)
                        ->where('kategori', 'lainnya')
                        ->where('pos_biaya', 'TUNGGAKAN LALU')
                        ->where('judul_tagihan', $billTitle)
                        ->first();
                    if (!$existBill) {
                        StudentBill::create([
                            'student_id' => $student->id,
                            'kategori' => 'lainnya',
                            'pos_biaya' => 'TUNGGAKAN LALU',
                            'judul_tagihan' => $billTitle,
                            'bulan' => null,
                            'tahun' => (int)($student->tahun_masuk ?: date('Y')),
                            'nominal_asli' => $lain,
                            'nominal_potongan' => 0,
                            'nominal_tagihan' => $lain,
                            'nominal_bayar' => 0,
                            'sisa_tagihan' => $lain,
                            'status' => 'Belum Bayar',
                            'jatuh_tempo' => $jatuhTempo,
                            'created_by' => $creatorName,
                        ]);
                        $createdBills++;
                        $totalNominalBills += $lain;
                    }
                }

                // 4. Fallback jika hanya total tunggakan umum yang diisi
                if ($totalLalu > 0 && $du == 0 && $spp == 0 && $lain == 0) {
                    $billTitle = !empty($ketTunggakan) ? "Tunggakan Masa Lalu: {$ketTunggakan}" : 'Tunggakan Biaya Pendidikan Masa Lalu';
                    $existBill = StudentBill::where('student_id', $student->id)
                        ->where('kategori', 'lainnya')
                        ->where('pos_biaya', 'TUNGGAKAN LALU')
                        ->where('status', 'Belum Bayar')
                        ->first();
                    if (!$existBill) {
                        StudentBill::create([
                            'student_id' => $student->id,
                            'kategori' => 'lainnya',
                            'pos_biaya' => 'TUNGGAKAN LALU',
                            'judul_tagihan' => $billTitle,
                            'bulan' => null,
                            'tahun' => (int)($student->tahun_masuk ?: date('Y')) - 1,
                            'nominal_asli' => $totalLalu,
                            'nominal_potongan' => 0,
                            'nominal_tagihan' => $totalLalu,
                            'nominal_bayar' => 0,
                            'sisa_tagihan' => $totalLalu,
                            'status' => 'Belum Bayar',
                            'jatuh_tempo' => $jatuhTempo,
                            'created_by' => $creatorName,
                        ]);
                        $createdBills++;
                        $totalNominalBills += $totalLalu;
                    }
                }
            }
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Gagal memproses file Excel: ' . $e->getMessage());
        }

        $summaryParts = [];
        if ($importedStudents > 0) {
            $summaryParts[] = "{$importedStudents} santri baru berhasil ditambahkan";
        }
        if ($existingStudents > 0) {
            $summaryParts[] = "{$existingStudents} data santri lama diperbarui";
        }
        if ($createdBills > 0) {
            $formattedRp = 'Rp ' . number_format($totalNominalBills, 0, ',', '.');
            $summaryParts[] = "{$createdBills} tagihan tunggakan masa lalu berhasil dicatat (Total: {$formattedRp})";
        }

        $summaryText = !empty($summaryParts) ? implode(', ', $summaryParts) : "Data berhasil diproses (tidak ada penambahan data baru)";

        return redirect()->back()->with('success', "Alhamdulillah! Proses import massal Excel selesai: {$summaryText}. Akun login santri aktif otomatis dan tagihan tunggakan langsung tampil di menu Keuangan!");
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
            'kamar_asrama'  => null,
        ]);

        return redirect()->back()->with('success', "Santri {$student->nama_lengkap} berhasil dikeluarkan dari kamar {$oldRoom}.");
    }

    /* =========================================================
     *  MUTASI SANTRI
     * ========================================================= */

    /**
     * AJAX search santri untuk autocomplete di modal mutasi.
     */
    public function mutasiSearch(Request $request)
    {
        $q = $request->get('q', '');
        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $students = Student::with(['dormitory', 'classroom', 'bills'])
            ->where(function ($query) use ($q) {
                $query->where('nama_lengkap', 'like', "%{$q}%")
                      ->orWhere('nis', 'like', "%{$q}%")
                      ->orWhere('nisn', 'like', "%{$q}%");
            })
            ->select('id', 'nama_lengkap', 'nis', 'kelas', 'jenjang', 'status', 'kamar_asrama', 'dormitory_id', 'nama_sekolah')
            ->limit(15)
            ->get();

        $results = $students->map(function ($s) {
            $kamar = $s->kamar_asrama;
            if (!$kamar && $s->dormitory) {
                $kamar = $s->dormitory->nama_asrama . ' - ' . $s->dormitory->kamar;
            }

            return [
                'id'              => $s->id,
                'nama_lengkap'    => $s->nama_lengkap,
                'nis'             => $s->nis,
                'kelas'           => $s->kelas,
                'jenjang'         => $s->jenjang,
                'status'          => $s->status,
                'kamar_asrama'    => $kamar ?: '-',
                'nama_sekolah'    => $s->nama_sekolah ?: '-',
                'total_tunggakan' => $s->total_tunggakan,
            ];
        });

        return response()->json($results);
    }

    /**
     * Daftar seluruh riwayat mutasi santri dengan relasi lengkap.
     */
    public function mutasiIndex(Request $request)
    {
        $query = StudentMutation::with(['student.dormitory', 'user'])->latest('tanggal_mutasi')->latest('id');

        if ($request->filled('jenis')) {
            $query->where('jenis_mutasi', $request->jenis);
        }

        if ($request->filled('jenjang')) {
            $jen = $request->jenjang;
            $query->where(function ($q) use ($jen) {
                $q->where('jenjang', 'like', "%{$jen}%")
                  ->orWhereHas('student', function ($sq) use ($jen) {
                      $sq->where('jenjang', 'like', "%{$jen}%");
                  });
            });
        }

        if ($request->filled('kelas')) {
            $kls = $request->kelas;
            $query->where(function ($q) use ($kls) {
                $q->where('kelas_dari', $kls)
                  ->orWhere('kelas_ke', $kls)
                  ->orWhereHas('student', function ($sq) use ($kls) {
                      $sq->where('kelas', $kls);
                  });
            });
        }

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->whereHas('student', function ($sq) use ($search) {
                    $sq->where('nama_lengkap', 'like', "%{$search}%")
                       ->orWhere('nis', 'like', "%{$search}%")
                       ->orWhere('nisn', 'like', "%{$search}%");
                })->orWhere('nama_santri', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%")
                  ->orWhere('alasan', 'like', "%{$search}%")
                  ->orWhere('sekolah_asal_tujuan', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%")
                  ->orWhere('dicatat_oleh', 'like', "%{$search}%");
            });
        }

        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_mutasi', $request->tahun);
        }

        $mutations = $query->paginate(20)->withQueryString();

        $stats = [
            'total_keluar' => StudentMutation::where('jenis_mutasi', 'Keluar')->count(),
            'total_masuk'  => StudentMutation::where('jenis_mutasi', 'Masuk')->count(),
            'bulan_ini'    => StudentMutation::whereMonth('tanggal_mutasi', now()->month)
                                             ->whereYear('tanggal_mutasi', now()->year)->count(),
        ];

        // Daftar kelas lengkap dari master Classrooms
        $allClassrooms = Classroom::orderBy('jenjang')->orderBy('nama_kelas')->get();
        $allClasses = $allClassrooms->pluck('nama_kelas')->toArray();
        if (empty($allClasses)) {
            $allClasses = Student::select('kelas')->distinct()->whereNotNull('kelas')->pluck('kelas')->toArray();
            sort($allClasses);
        }

        // Daftar kamar asrama
        $allDormitories = Dormitory::orderBy('gender')->orderBy('nama_asrama')->orderBy('kamar')->get();

        $alasanOptions = StudentMutation::alasanOptions();

        // Ambil tahun dari riwayat mutasi + tahun masuk santri + tahun sekarang/lalu agar dropdown TIDAK KOSONG
        $thnMutasi = StudentMutation::selectRaw('YEAR(tanggal_mutasi) as tahun')->distinct()->pluck('tahun')->toArray();
        $thnSantri = Student::select('tahun_masuk')->distinct()->whereNotNull('tahun_masuk')->pluck('tahun_masuk')->map(fn($y) => (int)$y)->toArray();
        $curYear = (int) date('Y');
        $defaultYears = [$curYear, $curYear - 1, $curYear - 2, $curYear - 3];
        $tahunList = array_values(array_unique(array_filter(array_merge($thnMutasi, $thnSantri, $defaultYears))));
        rsort($tahunList);

        $jenjangList = ['MTs Mukim', 'MTs Laju', 'MA Mukim', 'MA Laju'];

        return view('admin.siswa.mutasi', compact(
            'mutations', 'stats', 'allClasses', 'allClassrooms', 'allDormitories', 'alasanOptions', 'tahunList', 'jenjangList'
        ));
    }

    /**
     * Simpan catatan mutasi baru dan sinkronkan status santri & kamar asrama.
     */
    public function mutasiStore(Request $request)
    {
        $request->validate([
            'student_id'         => 'required|exists:students,id',
            'jenis_mutasi'       => 'required|in:Keluar,Masuk',
            'tanggal_mutasi'     => 'required|date',
            'alasan'             => 'required_if:jenis_mutasi,Keluar|nullable|string|max:100',
            'sekolah_asal_tujuan'=> 'nullable|string|max:200',
            'kelas_ke'           => 'nullable|string|max:50',
            'kamar_ke'           => 'nullable|string|max:100',
            'keterangan'         => 'nullable|string|max:1000',
        ]);

        $student = Student::findOrFail($request->student_id);
        $statusLama = $student->status;

        // Catat mutasi dengan snapshot lengkap data santri
        StudentMutation::create([
            'student_id'          => $student->id,
            'user_id'             => auth()->id(),
            'nama_santri'         => $student->nama_lengkap,
            'nis'                 => $student->nis,
            'jenjang'             => $student->jenjang,
            'jenis_mutasi'        => $request->jenis_mutasi,
            'tanggal_mutasi'      => $request->tanggal_mutasi,
            'alasan'              => $request->alasan,
            'kelas_dari'          => $student->kelas,
            'kamar_dari'          => $student->kamar_asrama,
            'sekolah_asal_tujuan' => $request->sekolah_asal_tujuan,
            'kelas_ke'            => $request->kelas_ke,
            'kamar_ke'            => $request->kamar_ke,
            'status_sebelumnya'   => $statusLama,
            'keterangan'          => $request->keterangan,
            'dicatat_oleh'        => auth()->user()->name ?? 'Admin',
        ]);

        // Update status santri dan sinkronkan asrama/kamar
        if ($request->jenis_mutasi === 'Keluar') {
            // Status diset ke Mutasi (sesuai filter data santri)
            $student->update([
                'status'        => 'Mutasi',
                // Kosongkan kamar asrama agar kapasitas kamar di master asrama otomatis bertambah
                'dormitory_id'  => null,
                'kamar_asrama'  => null,
            ]);
        } elseif ($request->jenis_mutasi === 'Masuk') {
            $updateData = ['status' => 'Aktif'];
            if ($request->filled('kelas_ke')) {
                $updateData['kelas'] = $request->kelas_ke;
            }
            if ($request->filled('kamar_ke')) {
                $updateData['kamar_asrama'] = $request->kamar_ke;
                // Cari dormitory_id jika nama kamar cocok
                $dorm = Dormitory::where('kamar', $request->kamar_ke)
                    ->orWhereRaw("CONCAT(nama_asrama, ' - ', kamar) = ?", [$request->kamar_ke])
                    ->first();
                if ($dorm) {
                    $updateData['dormitory_id'] = $dorm->id;
                }
            }
            if ($request->filled('sekolah_asal_tujuan')) {
                $updateData['nama_sekolah'] = $request->sekolah_asal_tujuan;
            }
            $student->update($updateData);
        }

        return redirect()->route('admin.siswa.mutasi.index')
                         ->with('success', "Mutasi {$request->jenis_mutasi} santri {$student->nama_lengkap} berhasil dicatat & data santri telah disinkronkan.");
    }

    /**
     * Hapus catatan mutasi dan pulihkan status santri serta kamar asrama.
     */
    public function mutasiDestroy($id)
    {
        $mutation = StudentMutation::findOrFail($id);
        $student  = Student::find($mutation->student_id);

        if ($student) {
            // Restore status santri ke sebelum mutasi
            $restoreData = [
                'status' => $mutation->status_sebelumnya ?: 'Aktif',
            ];

            // Jika mutasi keluar dibatalkan dan santri belum punya kamar, pulihkan kamar asrama asalnya
            if ($mutation->jenis_mutasi === 'Keluar' && $mutation->kamar_dari && !$student->kamar_asrama) {
                $restoreData['kamar_asrama'] = $mutation->kamar_dari;
                $dorm = Dormitory::where('kamar', $mutation->kamar_dari)
                    ->orWhereRaw("CONCAT(nama_asrama, ' - ', kamar) = ?", [$mutation->kamar_dari])
                    ->first();
                if ($dorm) {
                    $restoreData['dormitory_id'] = $dorm->id;
                }
            }

            // Jika mutasi masuk dibatalkan, pulihkan kelas asalnya
            if ($mutation->jenis_mutasi === 'Masuk' && $mutation->kelas_dari) {
                $restoreData['kelas'] = $mutation->kelas_dari;
            }

            $student->update($restoreData);
        }

        $mutation->delete();

        return redirect()->route('admin.siswa.mutasi.index')
                         ->with('success', 'Catatan mutasi berhasil dibatalkan/dihapus. Status santri dan kamar asrama dipulihkan.');
    }
}


