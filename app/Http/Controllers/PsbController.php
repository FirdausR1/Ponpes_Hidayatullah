<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PsbRegistration;
use App\Services\PhotoVerificationService;
use Illuminate\Support\Str;

class PsbController extends Controller
{
    /**
     * Tampilkan formulir pendaftaran santri baru lengkap.
     */
    public function create()
    {
        return view('psb.register');
    }

    /**
     * Simpan data formulir pendaftaran santri baru dan upload berkas.
     */
    public function store(Request $request)
    {
        $rules = [
            // Jalur & Ketentuan
            'jalur' => 'required|string|in:Reguler,Prestasi,Tahfidz',
            'jenjang' => 'nullable|string|in:MTs Mukim,MTs Laju,MA Mukim,MA Laju',
            'bukti_transfer' => 'required|file|mimes:jpeg,png,jpg,webp,pdf|max:10240',
            
            // Data Diri Santri
            'nama_lengkap' => 'required|string|max:255',
            'pas_foto' => 'required|file|mimes:jpeg,png,jpg,webp|max:10240',
            'nisn' => 'required|string|max:30',
            'jenis_kelamin' => 'required|string|in:Laki-laki,Perempuan',
            'nik' => 'required|string|max:30',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'nomor_kk' => 'required|string|max:30',
            'anak_ke' => 'required|integer|min:1',
            'jumlah_saudara' => 'required|integer|min:0',
            'hobi' => 'required|string|max:150',
            'alamat_lengkap' => 'required|string',
            'bantuan_sosial' => 'nullable|array',
            'bukti_bantuan_sosial' => 'nullable|file|mimes:jpeg,png,jpg,webp,pdf|max:10240',

            // Sekolah Asal
            'nama_sekolah' => 'required|string|max:255',
            'alamat_sekolah' => 'required|string',
            'tahun_lulus' => 'required|string|max:10',

            // Ayah Kandung
            'ayah_nama' => 'required|string|max:255',
            'ayah_nik' => 'required|string|max:30',
            'ayah_tahun_lahir' => 'required|string|max:10',
            'ayah_alamat' => 'required|string',
            'ayah_telepon' => 'required|string|max:30',
            'ayah_pendidikan' => 'required|string|max:50',
            'ayah_pekerjaan' => 'required|string|max:100',
            'ayah_penghasilan' => 'required|string|max:100',

            // Ibu Kandung
            'ibu_nama' => 'required|string|max:255',
            'ibu_nik' => 'required|string|max:30',
            'ibu_tahun_lahir' => 'required|string|max:10',
            'ibu_alamat' => 'required|string',
            'ibu_telepon' => 'required|string|max:30',
            'ibu_pendidikan' => 'required|string|max:50',
            'ibu_pekerjaan' => 'required|string|max:100',
            'ibu_penghasilan' => 'required|string|max:100',

            // Wali Opsional
            'wali_hubungan' => 'nullable|string|max:100',
            'wali_nama' => 'nullable|string|max:255',
            'wali_nik' => 'nullable|string|max:30',
            'wali_tahun_lahir' => 'nullable|string|max:10',
            'wali_alamat' => 'nullable|string',
            'wali_telepon' => 'nullable|string|max:30',
            'wali_pendidikan' => 'nullable|string|max:50',
            'wali_pekerjaan' => 'nullable|string|max:100',
            'wali_penghasilan' => 'nullable|string|max:100',
        ];

        // Validasi jalur khusus
        if ($request->jalur === 'Prestasi' || $request->jalur === 'Tahfidz') {
            $rules['bukti_prestasi_tahfidz'] = 'required|file|mimes:jpeg,png,jpg,webp,pdf|max:10240';
        } else {
            $rules['bukti_prestasi_tahfidz'] = 'nullable|file|mimes:jpeg,png,jpg,webp,pdf|max:10240';
        }

        $validated = $request->validate($rules);

        // Helper upload folder
        $fotoPath = null;
        $fotoVerification = [
            'status' => 'Belum Diperiksa',
            'catatan' => 'Menunggu verifikasi foto.',
        ];

        if ($request->hasFile('pas_foto')) {
            $file = $request->file('pas_foto');
            $filename = time() . '_foto_' . Str::slug($request->nama_lengkap) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/psb/foto'), $filename);
            $fotoPath = '/uploads/psb/foto/' . $filename;

            // Jalankan verifikasi otomatis aspek rasio (3x4) dan background merah
            $fotoVerification = PhotoVerificationService::verify(public_path('uploads/psb/foto/' . $filename));
        }

        $transferPath = null;
        if ($request->hasFile('bukti_transfer')) {
            $file = $request->file('bukti_transfer');
            $filename = time() . '_bayar_' . Str::slug($request->nama_lengkap) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/psb/transfer'), $filename);
            $transferPath = '/uploads/psb/transfer/' . $filename;
        }

        $prestasiPath = null;
        if ($request->hasFile('bukti_prestasi_tahfidz')) {
            $file = $request->file('bukti_prestasi_tahfidz');
            $filename = time() . '_prestasi_' . Str::slug($request->nama_lengkap) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/psb/prestasi'), $filename);
            $prestasiPath = '/uploads/psb/prestasi/' . $filename;
        }

        $bansosPath = null;
        if ($request->hasFile('bukti_bantuan_sosial')) {
            $file = $request->file('bukti_bantuan_sosial');
            $filename = time() . '_bansos_' . Str::slug($request->nama_lengkap) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/psb/bansos'), $filename);
            $bansosPath = '/uploads/psb/bansos/' . $filename;
        }

        // Simpan ke DB
        $bansosList = $request->has('bantuan_sosial') ? implode(', ', (array) $request->bantuan_sosial) : null;

        $registration = PsbRegistration::create([
            'jalur' => $request->jalur,
            'jenjang' => $request->jenjang ?: 'MTs Mukim',
            'bukti_transfer' => $transferPath,
            'bukti_prestasi_tahfidz' => $prestasiPath,
            'nama_lengkap' => $request->nama_lengkap,
            'pas_foto' => $fotoPath,
            'foto_status' => $fotoVerification['status'],
            'foto_catatan' => $fotoVerification['catatan'],
            'nisn' => $request->nisn,
            'jenis_kelamin' => $request->jenis_kelamin,
            'nik' => $request->nik,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'nomor_kk' => $request->nomor_kk,
            'anak_ke' => $request->anak_ke,
            'jumlah_saudara' => $request->jumlah_saudara,
            'hobi' => $request->hobi,
            'alamat_lengkap' => $request->alamat_lengkap,
            'bantuan_sosial' => $bansosList,
            'bukti_bantuan_sosial' => $bansosPath,
            'nama_sekolah' => $request->nama_sekolah,
            'asal_sekolah' => $request->nama_sekolah, // backward compatibility
            'alamat_sekolah' => $request->alamat_sekolah,
            'tahun_lulus' => $request->tahun_lulus,
            'ayah_nama' => $request->ayah_nama,
            'ayah_nik' => $request->ayah_nik,
            'ayah_tahun_lahir' => $request->ayah_tahun_lahir,
            'ayah_alamat' => $request->ayah_alamat,
            'ayah_telepon' => $request->ayah_telepon,
            'ayah_pendidikan' => $request->ayah_pendidikan,
            'ayah_pekerjaan' => $request->ayah_pekerjaan,
            'ayah_penghasilan' => $request->ayah_penghasilan,
            'ibu_nama' => $request->ibu_nama,
            'ibu_nik' => $request->ibu_nik,
            'ibu_tahun_lahir' => $request->ibu_tahun_lahir,
            'ibu_alamat' => $request->ibu_alamat,
            'ibu_telepon' => $request->ibu_telepon,
            'ibu_pendidikan' => $request->ibu_pendidikan,
            'ibu_pekerjaan' => $request->ibu_pekerjaan,
            'ibu_penghasilan' => $request->ibu_penghasilan,
            'wali_hubungan' => $request->wali_hubungan,
            'wali_nama' => $request->wali_nama,
            'wali_nik' => $request->wali_nik,
            'wali_tahun_lahir' => $request->wali_tahun_lahir,
            'wali_alamat' => $request->wali_alamat,
            'wali_telepon' => $request->wali_telepon,
            'wali_pendidikan' => $request->wali_pendidikan,
            'wali_pekerjaan' => $request->wali_pekerjaan,
            'wali_penghasilan' => $request->wali_penghasilan,
            'nama_wali' => $request->ayah_nama ?: $request->ibu_nama ?: $request->wali_nama,
            'no_whatsapp' => $request->ayah_telepon ?: $request->ibu_telepon ?: $request->wali_telepon,
            'alamat' => $request->alamat_lengkap,
            'status' => 'Menunggu',
        ]);

        return redirect()->route('psb.success', $registration->id)->with('success', 'Formulir Pendaftaran Berhasil Dikirim!');
    }

    /**
     * Halaman konfirmasi sukses pendaftaran santri baru.
     */
    public function success($id)
    {
        $registration = PsbRegistration::findOrFail($id);
        return view('psb.success', compact('registration'));
    }

    /**
     * Halaman publik mandiri untuk mengecek status verifikasi pendaftaran calon santri baru.
     */
    public function checkStatus(Request $request)
    {
        $identifier = trim($request->input('identifier', $request->input('no_reg', $request->input('wa', ''))));
        $registration = null;
        $searched = false;

        if (!empty($identifier)) {
            $searched = true;
            $cleanWa = preg_replace('/[^0-9]/', '', $identifier);

            $registration = PsbRegistration::where('no_registrasi', $identifier)
                ->orWhere('nik', $identifier)
                ->orWhere('nisn', $identifier)
                ->orWhere(function($query) use ($identifier, $cleanWa) {
                    $query->where('no_whatsapp', 'like', "%{$identifier}%")
                          ->orWhere('ayah_telepon', 'like', "%{$identifier}%")
                          ->orWhere('ibu_telepon', 'like', "%{$identifier}%");
                    if (!empty($cleanWa) && strlen($cleanWa) >= 8) {
                        $query->orWhere('no_whatsapp', 'like', "%{$cleanWa}%")
                              ->orWhere('ayah_telepon', 'like', "%{$cleanWa}%");
                    }
                })
                ->latest()
                ->first();
        }

        return view('psb.check_status', compact('registration', 'searched', 'identifier'));
    }

    /**
     * Upload ulang pas foto bagi santri yang status fotonya "Perlu Perbaikan".
     */
    public function reuploadFoto(Request $request, $id)
    {
        $registration = PsbRegistration::findOrFail($id);

        $request->validate([
            'pas_foto' => 'required|image|mimes:jpeg,png,jpg,webp|max:10240',
        ], [
            'pas_foto.required' => 'Silakan pilih file pas foto pengganti.',
            'pas_foto.image' => 'File yang diunggah harus berupa gambar.',
            'pas_foto.mimes' => 'Format foto yang didukung: JPG, JPEG, PNG, WEBP.',
            'pas_foto.max' => 'Ukuran pas foto maksimal 10 MB.',
        ]);

        $file = $request->file('pas_foto');
        $filename = time() . '_reupload_' . Str::slug($registration->nama_lengkap) . '.' . $file->getClientOriginalExtension();
        $dest = public_path('uploads/psb/foto');
        if (!file_exists($dest)) {
            mkdir($dest, 0777, true);
        }
        $file->move($dest, $filename);

        $photoPath = '/uploads/psb/foto/' . $filename;
        $verification = PhotoVerificationService::verify(public_path($photoPath));

        $registration->update([
            'pas_foto' => $photoPath,
            'foto_status' => $verification['status'],
            'foto_catatan' => $verification['catatan'] . ' (Diunggah ulang oleh pendaftar pada ' . date('d/m/Y H:i') . ')',
        ]);

        return redirect()->route('psb.checkStatus', ['no_reg' => $registration->no_registrasi])
            ->with('success', 'Pas foto baru berhasil diunggah! Status foto Anda saat ini: ' . $verification['status']);
    }

    /**
     * Cetak mandiri Kartu Biodata Calon Santri Baru (Format CV) untuk pendaftar / wali santri.
     */
    public function printCard(Request $request, $id)
    {
        $reg = PsbRegistration::findOrFail($id);
        $mode = $request->input('mode', 'cv'); // 'cv' (default 1 lembar) atau 'all' (cv + lampiran berkas)
        return view('psb.print_card', compact('reg', 'mode'));
    }
}

