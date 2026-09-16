<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\PsbRegistration;
use App\Models\Setting;
use App\Models\User;
use App\Models\Student;
use App\Services\PhotoVerificationService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalArticles = Article::count();
        $publishedArticles = Article::where('status', 'published')->count();
        $totalPsb = PsbRegistration::count();
        $totalViews = Article::sum('views');

        $recentArticles = Article::latest()->take(5)->get();
        $recentPsb = PsbRegistration::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalArticles',
            'publishedArticles',
            'totalPsb',
            'totalViews',
            'recentArticles',
            'recentPsb'
        ));
    }

    public function articleIndex(Request $request)
    {
        $query = Article::latest();

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('kategori')) {
            $query->where('category', $request->kategori);
        }

        $articles = $query->paginate(10)->withQueryString();
        $categories = ['Kajian Subuh', 'Prestasi', 'Literasi Turats', 'Informasi PSB', 'Warta Kampus'];

        return view('admin.berita.index', compact('articles', 'categories'));
    }

    public function articleCreate()
    {
        $categories = ['Kajian Subuh', 'Prestasi', 'Literasi Turats', 'Informasi PSB', 'Warta Kampus'];
        return view('admin.berita.create', compact('categories'));
    }

    public function articleStore(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'image' => 'nullable|string',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:3072',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'author' => 'nullable|string|max:100',
            'status' => 'required|string|in:published,draft',
        ]);

        $imagePath = $request->image;

        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = time() . '_' . Str::slug($request->title) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/berita'), $filename);
            $imagePath = '/uploads/berita/' . $filename;
        }

        $slug = Str::slug($request->title);
        $originalSlug = $slug;
        $count = 1;
        while (Article::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        Article::create([
            'title' => $request->title,
            'slug' => $slug,
            'category' => $request->category,
            'image' => $imagePath,
            'excerpt' => $request->excerpt ?: Str::limit(strip_tags($request->content), 150),
            'content' => $request->content,
            'author' => $request->author ?: 'Humas Pesantren',
            'status' => $request->status,
            'published_at' => $request->status === 'published' ? now() : null,
        ]);

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil diterbitkan!');
    }

    public function articleEdit($id)
    {
        $article = Article::findOrFail($id);
        $categories = ['Kajian Subuh', 'Prestasi', 'Literasi Turats', 'Informasi PSB', 'Warta Kampus'];
        return view('admin.berita.edit', compact('article', 'categories'));
    }

    public function articleUpdate(Request $request, $id)
    {
        $article = Article::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'image' => 'nullable|string',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:3072',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'author' => 'nullable|string|max:100',
            'status' => 'required|string|in:published,draft',
        ]);

        $imagePath = $article->image;

        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = time() . '_' . Str::slug($request->title) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/berita'), $filename);
            $imagePath = '/uploads/berita/' . $filename;
        } elseif ($request->filled('image')) {
            $imagePath = $request->image;
        }

        $article->update([
            'title' => $request->title,
            'category' => $request->category,
            'image' => $imagePath,
            'excerpt' => $request->excerpt ?: Str::limit(strip_tags($request->content), 150),
            'content' => $request->content,
            'author' => $request->author ?: 'Humas Pesantren',
            'status' => $request->status,
            'published_at' => ($request->status === 'published' && !$article->published_at) ? now() : $article->published_at,
        ]);

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil diperbarui!');
    }

    public function articleDestroy($id)
    {
        $article = Article::findOrFail($id);
        $article->delete();

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil dihapus!');
    }

    public function psbIndex(Request $request)
    {
        $query = PsbRegistration::latest();

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%")
                  ->orWhere('no_whatsapp', 'like', "%{$search}%")
                  ->orWhere('asal_sekolah', 'like', "%{$search}%");
            });
        }

        if ($request->filled('jenjang')) {
            $query->where('jenjang', $request->jenjang);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('jenis_kelamin')) {
            $query->where('jenis_kelamin', $request->jenis_kelamin);
        }

        if ($request->filled('tahun')) {
            $query->whereYear('created_at', $request->tahun);
        }

        $years = PsbRegistration::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->filter()
            ->values();

        if ($years->isEmpty()) {
            $years = collect([date('Y')]);
        }

        $registrations = $query->paginate(12)->withQueryString();

        return view('admin.psb.index', compact('registrations', 'years'));
    }

    public function psbUpdateStatus(Request $request, $id)
    {
        $registration = PsbRegistration::findOrFail($id);
        $validated = $request->validate([
            'status' => 'required|string|in:Menunggu,Diterima,Ditolak',
        ]);

        $registration->update($validated);

        return redirect()->back()->with('success', 'Status pendaftaran santri ' . $registration->nama_lengkap . ' diubah menjadi: ' . $registration->status);
    }

    /**
     * Proses seleksi kelulusan / penerimaan santri secara otomatis.
     * Kriteria fleksibel: (1) Berdasarkan kelulusan CBT (Nilai >= KKM / Override Lulus),
     * (2) Nilai CBT Lulus + Bukti Pembayaran, atau (3) Kuota Top N nilai tertinggi.
     * Admin juga tetap dapat mengedit status secara manual kapan saja.
     */
    public function psbAutoSeleksi(Request $request)
    {
        $kkm = (int) Setting::get('cbt_passing_grade', 70);
        $kriteria = $request->input('kriteria', 'nilai_dan_bayar'); // 'nilai_dan_bayar', 'nilai_cbt', 'kuota'
        $statusTidakLulus = $request->input('status_tidak_lulus', 'tetap'); // 'tetap' atau 'tolak'
        $kuotaCount = (int) $request->input('kuota_count', 50);

        $registrations = PsbRegistration::all();
        $acceptedCount = 0;
        $rejectedCount = 0;

        if ($kriteria === 'kuota') {
            // Urutkan pendaftar berdasarkan nilai ujian tertinggi
            $sorted = $registrations->sortByDesc(function ($reg) {
                return $reg->nilai_ujian ?? -1;
            });

            $rank = 0;
            foreach ($sorted as $reg) {
                $rank++;
                if ($rank <= $kuotaCount && ($reg->nilai_ujian !== null && $reg->nilai_ujian > 0)) {
                    if ($reg->status !== 'Diterima') {
                        $reg->update(['status' => 'Diterima']);
                        $acceptedCount++;
                    }
                } elseif ($statusTidakLulus === 'tolak') {
                    if ($reg->status !== 'Ditolak') {
                        $reg->update(['status' => 'Ditolak']);
                        $rejectedCount++;
                    }
                }
            }
        } else {
            foreach ($registrations as $reg) {
                $evaluasi = $reg->evaluasiSyaratPenerimaan();
                $lulusCbt = ($reg->status_kelulusan === 'Lulus') || ($reg->nilai_ujian !== null && $reg->nilai_ujian >= $kkm);
                $isBayarLunas = ($reg->status_pembayaran === 'Lunas' || $reg->pembayaran_status === 'Lunas');

                $memenuhi = false;
                if ($kriteria === 'nilai_dan_bayar') {
                    $memenuhi = $lulusCbt && $isBayarLunas;
                } else {
                    // Nilai CBT Lulus saja
                    $memenuhi = $lulusCbt;
                }

                if ($memenuhi) {
                    if ($reg->status !== 'Diterima') {
                        $reg->update(['status' => 'Diterima']);
                        $acceptedCount++;
                    }
                } elseif ($statusTidakLulus === 'tolak' && $evaluasi['sudah_ujian'] && !$lulusCbt) {
                    if ($reg->status !== 'Ditolak') {
                        $reg->update(['status' => 'Ditolak']);
                        $rejectedCount++;
                    }
                }
            }
        }

        $kriteriaLabel = match($kriteria) {
            'nilai_dan_bayar' => 'Nilai CBT Lulus & Biaya 200rb Lunas',
            'kuota' => "Kuota Top {$kuotaCount} Nilai Tertinggi",
            default => "Nilai CBT Lulus Saja (>= KKM {$kkm})"
        };

        $msg = "Seleksi otomatis selesai berdasarkan kriteria [{$kriteriaLabel}]: {$acceptedCount} calon santri dinyatakan DITERIMA.";
        if ($rejectedCount > 0) {
            $msg .= " Serta {$rejectedCount} santri dinyatakan DITOLAK.";
        }
        $msg .= " Catatan: Anda tetap dapat mengedit status setiap santri secara bebas kapan saja melalui tabel.";

        return redirect()->back()->with('success', $msg);
    }

    public function psbDestroy($id)
    {
        $registration = PsbRegistration::findOrFail($id);
        $registration->delete();

        return redirect()->back()->with('success', 'Data pendaftaran santri berhasil dihapus.');
    }

    public function psbExport(Request $request)
    {
        $query = PsbRegistration::query();

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('no_registrasi', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('no_whatsapp', 'like', "%{$search}%")
                  ->orWhere('ayah_telepon', 'like', "%{$search}%");
            });
        }

        if ($request->filled('jenjang')) {
            $query->where('jenjang', $request->jenjang);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('jenis_kelamin')) {
            $query->where('jenis_kelamin', $request->jenis_kelamin);
        }

        if ($request->filled('tahun')) {
            $query->whereYear('created_at', $request->tahun);
        }

        if ($request->filled('ids')) {
            $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);
            $query->whereIn('id', $ids);
        }

        $registrations = $query->latest()->get();

        $filterTags = [];
        if ($request->filled('tahun')) $filterTags[] = 'Tahun_' . $request->tahun;
        if ($request->filled('jenis_kelamin')) $filterTags[] = Str::slug($request->jenis_kelamin);
        if ($request->filled('jenjang')) $filterTags[] = Str::slug($request->jenjang);
        if ($request->filled('status')) $filterTags[] = Str::slug($request->status);
        $filenameSuffix = !empty($filterTags) ? '_' . implode('_', $filterTags) : '';

        $filename = 'Data_Pendaftar_PSB_Hidayatullah' . $filenameSuffix . '_' . date('Ymd_His') . '.xls';

        $filterSubDesc = [];
        if ($request->filled('tahun')) $filterSubDesc[] = 'Tahun ' . $request->tahun;
        if ($request->filled('jenis_kelamin')) $filterSubDesc[] = 'Jenis Kelamin: ' . $request->jenis_kelamin;
        if ($request->filled('jenjang')) $filterSubDesc[] = 'Jenjang: ' . $request->jenjang;
        if ($request->filled('status')) $filterSubDesc[] = 'Status: ' . $request->status;
        $taText = Setting::get('tahun_ajaran', '2026/2027');
        $subtitleText = 'REKAPITULASI PENDAFTARAN SANTRI BARU (PSB) TAHUN AJARAN ' . $taText . (!empty($filterSubDesc) ? ' (' . implode(' • ', $filterSubDesc) . ')' : '');

        $headers = [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function() use ($registrations, $subtitleText) {
            echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
            echo '<head>';
            echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">';
            echo '<!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>Data Pendaftar PSB</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]-->';
            echo '<style>';
            echo 'body { font-family: Calibri, Arial, sans-serif; font-size: 10pt; }';
            echo 'table { border-collapse: collapse; width: 100%; }';
            echo 'th { background-color: #145a2e; color: #ffffff; font-weight: bold; border: 1px solid #0b381c; padding: 10px 8px; font-size: 10pt; text-align: center; vertical-align: middle; }';
            echo 'td { border: 1px solid #cbd5e1; padding: 7px 8px; font-size: 9.5pt; vertical-align: top; white-space: normal; }';
            echo '.text-center { text-align: center; }';
            echo '.text-right { text-align: right; }';
            echo '.txt { mso-number-format:"\@"; }';
            echo '.title { font-size: 15pt; font-weight: bold; color: #145a2e; text-align: center; }';
            echo '.subtitle { font-size: 10.5pt; color: #334155; text-align: center; }';
            echo '.meta { font-size: 9pt; color: #64748b; text-align: center; font-style: italic; }';
            echo '.status-diterima { background-color: #dcfce7; color: #15803d; font-weight: bold; text-align: center; }';
            echo '.status-menunggu { background-color: #fef3c7; color: #b45309; font-weight: bold; text-align: center; }';
            echo '.status-ditolak { background-color: #fee2e2; color: #b91c1c; font-weight: bold; text-align: center; }';
            echo '.foto-sesuai { color: #15803d; font-weight: bold; }';
            echo '.foto-revisi { color: #b45309; font-weight: bold; }';
            echo '</style>';
            echo '</head>';
            echo '<body>';

            echo '<table>';
            echo '<tr><td colspan="36" class="title">PONDOK PESANTREN HIDAYATULLAH TUKSONGO PRINGSURAT</td></tr>';
            echo '<tr><td colspan="36" class="subtitle">' . htmlspecialchars($subtitleText) . '</td></tr>';
            echo '<tr><td colspan="36" class="meta">Diekspor pada: ' . date('d F Y H:i') . ' WIB | Total Data: ' . count($registrations) . ' Calon Santri</td></tr>';
            echo '<tr><td colspan="36" style="border:none; height:12px;"></td></tr>';

            echo '<tr>';
            echo '<th>No</th>';
            echo '<th>No. Registrasi</th>';
            echo '<th>Status Seleksi</th>';
            echo '<th>Jalur</th>';
            echo '<th>Jenjang</th>';
            echo '<th>Nama Lengkap Santri</th>';
            echo '<th>Jenis Kelamin</th>';
            echo '<th>NISN</th>';
            echo '<th>NIK Santri</th>';
            echo '<th>No. Kartu Keluarga</th>';
            echo '<th>Tempat Lahir</th>';
            echo '<th>Tanggal Lahir</th>';
            echo '<th>Anak Ke</th>';
            echo '<th>Jml Sdr</th>';
            echo '<th>Hobi</th>';
            echo '<th>Alamat Lengkap</th>';
            echo '<th>Bantuan Sosial</th>';
            echo '<th>Asal Sekolah</th>';
            echo '<th>Tahun Lulus</th>';
            echo '<th>Nama Ayah</th>';
            echo '<th>NIK Ayah</th>';
            echo '<th>No. WA / HP Ayah</th>';
            echo '<th>Pekerjaan Ayah</th>';
            echo '<th>Penghasilan Ayah</th>';
            echo '<th>Nama Ibu</th>';
            echo '<th>NIK Ibu</th>';
            echo '<th>No. WA / HP Ibu</th>';
            echo '<th>Pekerjaan Ibu</th>';
            echo '<th>Penghasilan Ibu</th>';
            echo '<th>Nama Wali</th>';
            echo '<th>Hubungan Wali</th>';
            echo '<th>No. Telp Wali</th>';
            echo '<th>Status Pas Foto</th>';
            echo '<th>Catatan Foto</th>';
            echo '<th>Bukti Transfer</th>';
            echo '<th>Tanggal Pendaftaran</th>';
            echo '</tr>';

            $no = 1;
            foreach ($registrations as $r) {
                $statusClass = 'status-menunggu';
                if ($r->status === 'Diterima') $statusClass = 'status-diterima';
                elseif ($r->status === 'Ditolak') $statusClass = 'status-ditolak';

                $fotoClass = ($r->foto_status ?? 'Sesuai') === 'Sesuai' ? 'foto-sesuai' : 'foto-revisi';

                echo '<tr>';
                echo '<td class="text-center">' . $no++ . '</td>';
                echo '<td class="txt text-center" style="font-weight:bold;">' . ($r->no_registrasi ? "'" . htmlspecialchars($r->no_registrasi) : '-') . '</td>';
                echo '<td class="' . $statusClass . '">' . htmlspecialchars($r->status ?: 'Menunggu') . '</td>';
                echo '<td class="text-center">' . htmlspecialchars($r->jalur ?: 'Reguler') . '</td>';
                echo '<td class="text-center">' . htmlspecialchars($r->jenjang) . '</td>';
                echo '<td style="font-weight:bold;">' . htmlspecialchars($r->nama_lengkap) . '</td>';
                echo '<td class="text-center">' . htmlspecialchars($r->jenis_kelamin) . '</td>';
                echo '<td class="txt text-center">' . ($r->nisn ? "'" . htmlspecialchars($r->nisn) : '-') . '</td>';
                echo '<td class="txt text-center" style="font-weight:600;">' . ($r->nik ? "'" . htmlspecialchars($r->nik) : '-') . '</td>';
                echo '<td class="txt text-center">' . ($r->nomor_kk ? "'" . htmlspecialchars($r->nomor_kk) : '-') . '</td>';
                echo '<td>' . htmlspecialchars($r->tempat_lahir ?: '-') . '</td>';
                echo '<td class="text-center txt">' . ($r->tanggal_lahir ? $r->tanggal_lahir->format('d/m/Y') : '-') . '</td>';
                echo '<td class="text-center">' . htmlspecialchars($r->anak_ke ?: '-') . '</td>';
                echo '<td class="text-center">' . htmlspecialchars($r->jumlah_saudara ?: '-') . '</td>';
                echo '<td>' . htmlspecialchars($r->hobi ?: '-') . '</td>';
                echo '<td>' . htmlspecialchars($r->alamat_lengkap ?: $r->alamat ?: '-') . '</td>';
                echo '<td>' . htmlspecialchars($r->bantuan_sosial ?: 'Tidak Ada') . '</td>';
                echo '<td>' . htmlspecialchars($r->nama_sekolah ?: $r->asal_sekolah ?: '-') . '</td>';
                echo '<td class="text-center">' . htmlspecialchars($r->tahun_lulus ?: '-') . '</td>';
                echo '<td>' . htmlspecialchars($r->ayah_nama ?: $r->nama_wali ?: '-') . '</td>';
                echo '<td class="txt text-center">' . ($r->ayah_nik ? "'" . htmlspecialchars($r->ayah_nik) : '-') . '</td>';
                echo '<td class="txt">' . (($r->ayah_telepon ?: $r->no_whatsapp) ? "'" . htmlspecialchars($r->ayah_telepon ?: $r->no_whatsapp) : '-') . '</td>';
                echo '<td>' . htmlspecialchars($r->ayah_pekerjaan ?: '-') . '</td>';
                echo '<td>' . htmlspecialchars($r->ayah_penghasilan ?: '-') . '</td>';
                echo '<td>' . htmlspecialchars($r->ibu_nama ?: '-') . '</td>';
                echo '<td class="txt text-center">' . ($r->ibu_nik ? "'" . htmlspecialchars($r->ibu_nik) : '-') . '</td>';
                echo '<td class="txt">' . ($r->ibu_telepon ? "'" . htmlspecialchars($r->ibu_telepon) : '-') . '</td>';
                echo '<td>' . htmlspecialchars($r->ibu_pekerjaan ?: '-') . '</td>';
                echo '<td>' . htmlspecialchars($r->ibu_penghasilan ?: '-') . '</td>';
                echo '<td>' . htmlspecialchars($r->wali_nama ?: '-') . '</td>';
                echo '<td>' . htmlspecialchars($r->wali_hubungan ?: '-') . '</td>';
                echo '<td class="txt">' . ($r->wali_telepon ? "'" . htmlspecialchars($r->wali_telepon) : '-') . '</td>';
                echo '<td class="text-center ' . $fotoClass . '">' . htmlspecialchars($r->foto_status ?: 'Belum Diperiksa') . '</td>';
                echo '<td>' . htmlspecialchars($r->foto_catatan ?: '-') . '</td>';
                echo '<td class="text-center">' . ($r->bukti_transfer ? 'Ada (Terunggah)' : 'Belum Ada') . '</td>';
                echo '<td class="text-center txt">' . ($r->created_at ? $r->created_at->format('d/m/Y H:i') : '-') . '</td>';
                echo '</tr>';
            }

            echo '</table>';
            echo '</body>';
            echo '</html>';
        };

        return response()->stream($callback, 200, $headers);
    }

    public function psbPrint(Request $request)
    {
        $query = PsbRegistration::query();

        if ($request->filled('ids')) {
            $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);
            $query->whereIn('id', $ids);
        } else {
            if ($request->filled('q')) {
                $search = $request->q;
                $query->where(function($q) use ($search) {
                    $q->where('nama_lengkap', 'like', "%{$search}%")
                      ->orWhere('no_registrasi', 'like', "%{$search}%")
                      ->orWhere('nisn', 'like', "%{$search}%")
                      ->orWhere('nik', 'like', "%{$search}%")
                      ->orWhere('no_whatsapp', 'like', "%{$search}%")
                      ->orWhere('ayah_telepon', 'like', "%{$search}%");
                });
            }
            if ($request->filled('jenjang')) {
                $query->where('jenjang', $request->jenjang);
            }
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            if ($request->filled('jenis_kelamin')) {
                $query->where('jenis_kelamin', $request->jenis_kelamin);
            }
            if ($request->filled('tahun')) {
                $query->whereYear('created_at', $request->tahun);
            }
        }

        $years = PsbRegistration::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->filter()
            ->values();

        if ($years->isEmpty()) {
            $years = collect([date('Y')]);
        }

        $registrations = $query->oldest('id')->get();
        $mode = $request->get('mode', 'all'); // 'all', 'cv', 'berkas'

        return view('admin.psb.print', compact('registrations', 'mode', 'years'));
    }

    public function psbUpdateFotoStatus(Request $request, $id)
    {
        $registration = PsbRegistration::findOrFail($id);
        $validated = $request->validate([
            'foto_status' => 'required|string|in:Sesuai,Perlu Perbaikan,Belum Diperiksa',
            'foto_catatan' => 'nullable|string|max:255',
        ]);

        $registration->update($validated);

        return redirect()->back()->with('success', 'Status kesesuaian foto santri ' . $registration->nama_lengkap . ' berhasil diperbarui.');
    }

    public function psbUploadFoto(Request $request, $id)
    {
        $registration = PsbRegistration::findOrFail($id);
        $request->validate([
            'pas_foto' => 'required|image|mimes:jpeg,png,jpg,webp|max:10240',
        ]);

        $file = $request->file('pas_foto');
        $filename = time() . '_foto_revised_' . Str::slug($registration->nama_lengkap) . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/psb/foto'), $filename);

        $registration->update([
            'pas_foto' => '/uploads/psb/foto/' . $filename,
            'foto_status' => 'Sesuai',
            'foto_catatan' => 'Telah diperbarui oleh admin (' . date('d/m/Y H:i') . ')',
        ]);

        return redirect()->back()->with('success', 'Pas foto santri berhasil diganti dan otomatis ditandai sesuai ketentuan!');
    }

    /**
     * Jalankan verifikasi otomatis aspek rasio (3x4) dan background merah untuk seluruh pas foto santri.
     */
    public function psbAutoVerifyAll()
    {
        $registrations = PsbRegistration::whereNotNull('pas_foto')->get();
        $verifiedCount = 0;
        $validCount = 0;
        $needFixCount = 0;

        foreach ($registrations as $reg) {
            $fullPath = public_path($reg->pas_foto);
            if (file_exists($fullPath)) {
                $res = PhotoVerificationService::verify($fullPath);
                $reg->update([
                    'foto_status' => $res['status'],
                    'foto_catatan' => $res['catatan'],
                ]);
                $verifiedCount++;
                if ($res['status'] === 'Sesuai') {
                    $validCount++;
                } else {
                    $needFixCount++;
                }
            }
        }

        return redirect()->back()->with('success', "Auto-verifikasi selesai untuk {$verifiedCount} foto santri ({$validCount} Sesuai, {$needFixCount} Perlu Perbaikan).");
    }

    public function settingsIndex()
    {
        $settings = Setting::all()->pluck('value', 'key');
        $jadwalSantri = json_decode($settings['jadwal_santri_json'] ?? '[]', true) ?: [];

        // Data default Tabel Biaya Awal (jika belum ada di DB)
        $defaultBiayaAwal = [
            ['komponen' => 'Santri Baru KTS', 'mts_mukim' => 'Rp 60.000', 'mts_laju' => 'Rp 60.000', 'ma_mukim' => 'Rp 60.000', 'ma_laju' => 'Rp 60.000', 'is_total' => false],
            ['komponen' => 'Pangkal Masuk', 'mts_mukim' => 'Rp 1.200.000', 'mts_laju' => 'Rp 1.500.000', 'ma_mukim' => 'Rp 1.400.000', 'ma_laju' => 'Rp 1.800.000', 'is_total' => false],
            ['komponen' => 'Kertas @ 1 TH', 'mts_mukim' => 'Rp 200.000', 'mts_laju' => 'Rp 200.000', 'ma_mukim' => 'Rp 200.000', 'ma_laju' => 'Rp 200.000', 'is_total' => false],
            ['komponen' => 'Syahriah Juli', 'mts_mukim' => 'Rp 410.000', 'mts_laju' => 'Rp 80.000', 'ma_mukim' => 'Rp 430.000', 'ma_laju' => 'Rp 100.000', 'is_total' => false],
            ['komponen' => 'Kesehatan @ 1 TH', 'mts_mukim' => 'Rp 200.000', 'mts_laju' => 'Rp 200.000', 'ma_mukim' => 'Rp 200.000', 'ma_laju' => 'Rp 200.000', 'is_total' => false],
            ['komponen' => 'Kegiatan @ 1 TH', 'mts_mukim' => 'Rp 300.000', 'mts_laju' => 'Rp 300.000', 'ma_mukim' => 'Rp 300.000', 'ma_laju' => 'Rp 300.000', 'is_total' => false],
            ['komponen' => 'Pembelian Almari', 'mts_mukim' => 'Rp 350.000', 'mts_laju' => '—', 'ma_mukim' => 'Rp 350.000', 'ma_laju' => '—', 'is_total' => false],
            ['komponen' => 'Uang Gedung', 'mts_mukim' => 'Rp 500.000', 'mts_laju' => 'Rp 500.000', 'ma_mukim' => 'Rp 500.000', 'ma_laju' => 'Rp 500.000', 'is_total' => false],
            ['komponen' => 'Biaya Pendaftaran PSB', 'mts_mukim' => 'Rp 200.000', 'mts_laju' => 'Rp 200.000', 'ma_mukim' => 'Rp 200.000', 'ma_laju' => 'Rp 200.000', 'is_total' => false],
            ['komponen' => 'TOTAL BIAYA AWAL MASUK', 'mts_mukim' => 'Rp 3.420.000', 'mts_laju' => 'Rp 3.040.000', 'ma_mukim' => 'Rp 3.640.000', 'ma_laju' => 'Rp 3.360.000', 'is_total' => true],
        ];

        // Data default Tabel Biaya Bulanan (SPP)
        $defaultBiayaBulanan = [
            ['komponen' => 'Uang Makan 3x Sehari', 'mts_mukim' => 'Rp 300.000', 'mts_laju' => '—', 'ma_mukim' => 'Rp 300.000', 'ma_laju' => '—', 'is_total' => false],
            ['komponen' => 'Syahriyah Pendidikan', 'mts_mukim' => 'Rp 85.000', 'mts_laju' => 'Rp 55.000', 'ma_mukim' => 'Rp 105.000', 'ma_laju' => 'Rp 75.000', 'is_total' => false],
            ['komponen' => 'Tabungan Wajib Santri', 'mts_mukim' => 'Rp 25.000', 'mts_laju' => 'Rp 25.000', 'ma_mukim' => 'Rp 25.000', 'ma_laju' => 'Rp 25.000', 'is_total' => false],
            ['komponen' => 'TOTAL IURAN BULANAN', 'mts_mukim' => 'Rp 410.000 / bln', 'mts_laju' => 'Rp 80.000 / bln', 'ma_mukim' => 'Rp 430.000 / bln', 'ma_laju' => 'Rp 100.000 / bln', 'is_total' => true],
        ];

        $biayaAwal = json_decode($settings['biaya_awal_json'] ?? 'null', true) ?: $defaultBiayaAwal;
        $biayaBulanan = json_decode($settings['biaya_bulanan_json'] ?? 'null', true) ?: $defaultBiayaBulanan;

        // Data default Panca Jiwa
        $defaultPancaJiwa = [
            ['nomor' => '01', 'judul' => 'Keikhlasan', 'deskripsi' => "Beramal semata-mata karena Allah (lillahi ta'ala), bebas dari pamrih keduniaan, menjaga kemurnian niat dalam menuntut ilmu dan berkhidmat."],
            ['nomor' => '02', 'judul' => 'Kesederhanaan', 'deskripsi' => "Pola hidup wajar, bersahaja, hemat, dan terukur. Sederhana bukan berarti melarat, melainkan keteguhan jiwa yang menghindarkan sikap berlebihan."],
            ['nomor' => '03', 'judul' => 'Berdikari (Mandiri)', 'deskripsi' => "Sanggup menolong diri sendiri (Zelf Berdruiping System). Santri dididik mengurus kebutuhan sendiri, disiplin, dan pantang berpangku tangan."],
            ['nomor' => '04', 'judul' => 'Ukhuwah Islamiyah', 'deskripsi' => "Persaudaraan akrab yang menembus sekat kesukuan dan kedaerahan. Kesulitan ditanggung bersama, kesenangan dirasakan bersama."],
            ['nomor' => '05', 'judul' => 'Kebebasan Positif', 'deskripsi' => "Bebas berpikir dan berbuat dalam bingkai syariat, disiplin positif, dan bertanggung jawab penuh tanpa diperbudak hawa nafsu."],
        ];

        // Data default Makna Filosofi Lambang Pesantren
        $defaultFilosofiLambang = [
            ['elemen' => 'Segi Lima Luar', 'makna' => 'Rukun Islam sebagai landasan kokoh pembinaan kepribadian santri.'],
            ['elemen' => 'Kubah Masjid', 'makna' => 'Rukun Iman dan keterikatan batin pada masjid sebagai pusat ibadah.'],
            ['elemen' => 'Bintang Satu', 'makna' => 'Derajat Ihsan dan cita-cita luhur menerangi ummat dengan petunjuk Ilahi.'],
            ['elemen' => 'Kitab & Pena', 'makna' => "Berdasar pada Al-Qur'an dan As-Sunnah serta kegigihan menuntut ilmu."],
            ['elemen' => 'Toga Wisuda', 'makna' => 'Pencapaian prestasi akademik dan kelulusan santri yang bermartabat.'],
            ['elemen' => 'Warna Hijau & Merah', 'makna' => 'Hijau melambangkan Keislaman; Merah melambangkan Semangat Kemasyarakatan.'],
            ['elemen' => 'Warna Putih & Kuning', 'makna' => 'Putih simbol Keilmuan & Kesucian; Kuning simbol Kejayaan dan Kemuliaan Ummat.'],
            ['elemen' => 'Semboyan Hidup', 'makna' => '"Sebesar keinsyafan seseorang, sebesar itu pula keuntungan yang diraihnya."'],
        ];

        // Data default Fondasi Pendidikan Integral (4 Pilar)
        $defaultPilarPendidikan = [
            [
                'judul' => 'Tauhid & Akhlakul Karimah',
                'deskripsi' => "Penanaman akidah shahihah bermanhaj ahlussunnah wal jama'ah, bimbingan adab nabawi di asrama, birrul walidain, serta keteladanan akhlak 24 jam bersama dewan ustadz.",
                'tag' => 'Tarbiyah 24 Jam'
            ],
            [
                'judul' => "Tahfidzul Qur'an & Turats",
                'deskripsi' => "Bimbingan tahfidz intensif, simakan Al-Qur'an mingguan, kajian tajwid, serta pendalaman kitab turats (Nahwu, Shorof, Fiqih Ghoyatu Taqrib, Hadits Mukhtarul Hadits).",
                'tag' => 'Tahfidz & Kitab Kuning'
            ],
            [
                'judul' => 'Bahasa Arab & Inggris Aktif',
                'deskripsi' => "Disiplin komunikasi dwibahasa harian di lingkungan pesantren, pemberian mufrodat setiap pagi ba'da subuh, serta latihan khitobah (Muhadhoroh) tiga bahasa.",
                'tag' => 'Bilingual Daily Life'
            ],
            [
                'judul' => 'Kemandirian & Kepemimpinan',
                'deskripsi' => "Wadah kaderisasi OSPPH (Organisasi Santri Pondok Pesantren Hidayatullah), kepanduan Pramuka wajib, beladiri pencak silat, dan pembekalan khidmat masyarakat.",
                'tag' => 'Leadership & OSPPH'
            ],
        ];

        // Data default Agenda Berkala Santri
        $defaultAgendaBerkala = [
            ['hari' => 'Ahad', 'kegiatan' => "Simakan Al-Qur'an & Latihan Pencak Silat", 'keterangan' => "Tasmi' Al-Qur'an santri putri ba'da Shubuh, silat pagi, dan Sholawat Simtudduror malam"],
            ['hari' => 'Senin', 'kegiatan' => 'Upacara Bendera Kebangsaan', 'keterangan' => 'Pembinaan kedisiplinan dan rasa cinta tanah air di halaman kampus'],
            ['hari' => 'Kamis', 'kegiatan' => 'Muhadhoroh 3 Bahasa & Ziarah', 'keterangan' => "Latihan pidato (Arab, Inggris, Indonesia), ziarah makam, dan pembacaan Diba'"],
            ['hari' => 'Jumat', 'kegiatan' => 'Mujahadah Rutin Bersama Masyarakat', 'keterangan' => 'Doa bersama warga sekitar pondok mempererat jalinan ukhuwah sosial'],
            ['hari' => 'Sabtu', 'kegiatan' => 'Kepanduan Pramuka Wajib', 'keterangan' => 'Latihan keterampilan survival, pionering, dan kepemimpinan di alam terbuka'],
            ['hari' => 'Bulanan & Tahunan', 'kegiatan' => "Selapanan Wali Santri & Khutbatul 'Arsy", 'keterangan' => 'Silaturahmi Ahad Legi, Panggung Gembira (PG) kelas 6 TMI, dan wisuda hafidz'],
        ];

        $defaultHeroSlides = [
            ['id' => 1, 'image' => '/uploads/settings/hero_slide_1.jpg', 'caption' => 'Kampus Alam Tuksongo Madani', 'subcaption' => "Dusun Tuksongo, Nglorog, Pringsurat — Asri, hening, dan kondusif untuk tholabul 'ilmi", 'active' => true],
            ['id' => 2, 'image' => '/uploads/settings/hero_slide_2.jpg', 'caption' => "Halaqah Tahfidzul Qur'an Bersanad", 'subcaption' => "Bimbingan intensif mutqin bersama asatidz penghafal Al-Qur'an", 'active' => true],
            ['id' => 3, 'image' => '/uploads/settings/hero_slide_3.jpg', 'caption' => 'Kompleks Asrama & Kampus Modern', 'subcaption' => 'Lingkungan hunian santri yang bersih, tertib, sehat, dan islami 24 jam', 'active' => true],
            ['id' => 4, 'image' => '/uploads/settings/hero_slide_4.jpg', 'caption' => 'Majelis Asatidz & Pendidik Amanah', 'subcaption' => 'Kaderisasi alumni Gontor & salafiyah berdedikasi mengabdi', 'active' => true],
            ['id' => 5, 'image' => '/uploads/settings/hero_slide_5.jpg', 'caption' => 'Laboratorium CBT & Penunjang Digital', 'subcaption' => 'Fasilitas ujian mandiri dan penguasaan sains teknologi modern', 'active' => true],
        ];

        $pancaJiwa = json_decode($settings['panca_jiwa_json'] ?? 'null', true) ?: $defaultPancaJiwa;
        $filosofiLambang = json_decode($settings['filosofi_lambang_json'] ?? 'null', true) ?: $defaultFilosofiLambang;
        $pilarPendidikan = json_decode($settings['pilar_pendidikan_json'] ?? 'null', true) ?: $defaultPilarPendidikan;
        $agendaBerkala = json_decode($settings['agenda_berkala_json'] ?? 'null', true) ?: $defaultAgendaBerkala;
        $heroSlides = json_decode($settings['hero_slides_json'] ?? 'null', true) ?: $defaultHeroSlides;

        return view('admin.pengaturan.index', compact('settings', 'jadwalSantri', 'biayaAwal', 'biayaBulanan', 'pancaJiwa', 'filosofiLambang', 'pilarPendidikan', 'agendaBerkala', 'heroSlides'));
    }

    public function settingsUpdate(Request $request)
    {
        $activeTab = $request->input('active_tab', 'tab-hero');
        $sectionName = $request->input('section_name', 'Pengaturan website');

        $phpUploadErrors = [
            UPLOAD_ERR_INI_SIZE => 'Ukuran file melebihi batas konfigurasi server PHP (upload_max_filesize).',
            UPLOAD_ERR_FORM_SIZE => 'Ukuran file melebihi batas form formulir.',
            UPLOAD_ERR_PARTIAL => 'File hanya terunggah sebagian. Silakan ulangi proses upload.',
            UPLOAD_ERR_NO_FILE => 'Tidak ada file yang dipilih.',
            UPLOAD_ERR_NO_TMP_DIR => 'Folder temporary server tidak ditemukan.',
            UPLOAD_ERR_CANT_WRITE => 'Gagal menulis file ke disk server.',
            UPLOAD_ERR_EXTENSION => 'Upload dihentikan oleh konfigurasi modul PHP.',
        ];

        // 1. Handle File Upload Hero Slider (3-5 Foto Kampus) & Konfigurasi Animasi
        if ($sectionName === 'Foto Utama Kampus' || $sectionName === 'Hero Slider & Foto Kampus' || $request->has('hero_slider_animation') || $request->hasFile('hero_image_file') || $request->filled('hero_image')) {
            $dest = public_path('uploads/settings');
            if (!file_exists($dest)) {
                mkdir($dest, 0777, true);
            }

            if ($request->filled('hero_slider_animation')) {
                Setting::set('hero_slider_animation', $request->input('hero_slider_animation', 'fade'), 'hero');
            }
            if ($request->filled('hero_slider_duration')) {
                Setting::set('hero_slider_duration', (string) $request->input('hero_slider_duration', '5'), 'hero');
            }
            if ($request->filled('hero_slide_count')) {
                Setting::set('hero_slide_count', (string) $request->input('hero_slide_count', '5'), 'hero');
            }

            $currentSlides = json_decode(Setting::get('hero_slides_json', '[]'), true) ?: [];
            $newSlides = [];

            // Handle individual slides (1 s/d 5)
            for ($i = 1; $i <= 5; $i++) {
                $slideImage = $request->input("hero_slide_image_{$i}") ?? ($currentSlides[$i - 1]['image'] ?? "/uploads/settings/hero_slide_{$i}.jpg");

                if ($request->hasFile("hero_slide_file_{$i}")) {
                    $file = $request->file("hero_slide_file_{$i}");
                    if ($file->isValid()) {
                        $filename = 'hero_slide_' . $i . '_' . time() . '.' . $file->getClientOriginalExtension();
                        $file->move($dest, $filename);
                        $slideImage = '/uploads/settings/' . $filename;
                    }
                }

                $caption = $request->input("hero_slide_caption_{$i}", $currentSlides[$i - 1]['caption'] ?? "Kampus Alam Tuksongo Madani");
                $subcaption = $request->input("hero_slide_subcaption_{$i}", $currentSlides[$i - 1]['subcaption'] ?? "Dusun Tuksongo, Nglorog, Pringsurat");
                $active = $request->has("hero_slide_active_{$i}") || ($request->input('hero_slide_count', 5) >= $i);

                $newSlides[] = [
                    'id' => $i,
                    'image' => $slideImage,
                    'caption' => $caption,
                    'subcaption' => $subcaption,
                    'active' => (bool) $active,
                ];
            }

            // Fallback for single hero_image_file legacy upload
            if ($request->hasFile('hero_image_file')) {
                $file = $request->file('hero_image_file');
                if ($file->isValid()) {
                    $filename = 'hero_' . time() . '.' . $file->getClientOriginalExtension();
                    $file->move($dest, $filename);
                    $newSlides[0]['image'] = '/uploads/settings/' . $filename;
                }
            } elseif ($request->filled('hero_image')) {
                $newSlides[0]['image'] = trim($request->hero_image);
            }

            Setting::set('hero_slides_json', json_encode($newSlides, JSON_PRETTY_PRINT), 'hero');

            // Sync legacy keys to slide 1
            if (!empty($newSlides[0]['image'])) {
                Setting::set('hero_image', $newSlides[0]['image'], 'hero');
                Setting::set('hero_caption', $newSlides[0]['caption'] ?? '', 'hero');
                Setting::set('hero_subcaption', $newSlides[0]['subcaption'] ?? '', 'hero');
            }
        }

        // 2. Handle File Upload Foto Pimpinan Pesantren
        if ($sectionName === 'Kalam Pimpinan Pesantren' || $request->hasFile('sambutan_foto_file') || $request->filled('sambutan_foto')) {
            if ($request->hasFile('sambutan_foto_file')) {
                $file = $request->file('sambutan_foto_file');
                if (!$file->isValid()) {
                    $errText = $phpUploadErrors[$file->getError()] ?? $file->getErrorMessage();
                    return redirect()->back()
                        ->with('error', 'Gagal mengunggah foto pimpinan: ' . $errText)
                        ->with('active_tab', $activeTab)
                        ->withInput();
                }

                $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
                    'sambutan_foto_file' => 'required|image|mimes:jpeg,png,jpg,webp,jfif,gif,bmp,avif|max:12288',
                ], [
                    'sambutan_foto_file.image' => 'File yang diunggah harus berupa file gambar valid.',
                    'sambutan_foto_file.mimes' => 'Format gambar yang didukung: JPG, JPEG, PNG, WEBP, GIF, BMP.',
                    'sambutan_foto_file.max' => 'Ukuran file foto pimpinan maksimal 12 MB.',
                ]);

                if ($validator->fails()) {
                    return redirect()->back()
                        ->withErrors($validator)
                        ->with('error', 'Validasi foto pimpinan gagal: ' . implode(' ', $validator->errors()->all()))
                        ->with('active_tab', $activeTab)
                        ->withInput();
                }

                try {
                    $filename = 'pimpinan_' . time() . '.' . $file->getClientOriginalExtension();
                    $dest = public_path('uploads/settings');
                    if (!file_exists($dest)) {
                        mkdir($dest, 0777, true);
                    }
                    $file->move($dest, $filename);
                    Setting::set('sambutan_foto', '/uploads/settings/' . $filename, 'profil');
                } catch (\Exception $e) {
                    return redirect()->back()
                        ->with('error', 'Gagal menyimpan foto pimpinan ke server: ' . $e->getMessage())
                        ->with('active_tab', $activeTab)
                        ->withInput();
                }
            } elseif ($request->filled('sambutan_foto')) {
                Setting::set('sambutan_foto', trim($request->sambutan_foto), 'profil');
            }
        }

        // 3. Handle File Upload Brosur PSB (PDF / Gambar)
        if ($sectionName === 'File Brosur PSB' || $request->hasFile('brosur_file') || $request->filled('brosur_file_url')) {
            if ($request->hasFile('brosur_file')) {
                $file = $request->file('brosur_file');
                if (!$file->isValid()) {
                    $errText = $phpUploadErrors[$file->getError()] ?? $file->getErrorMessage();
                    return redirect()->back()
                        ->with('error', 'Gagal mengunggah file brosur: ' . $errText)
                        ->with('active_tab', $activeTab)
                        ->withInput();
                }

                $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
                    'brosur_file' => 'required|file|mimes:pdf,jpeg,png,jpg,webp|max:25600',
                ], [
                    'brosur_file.mimes' => 'File brosur harus berformat PDF atau Gambar (JPG, PNG, WEBP).',
                    'brosur_file.max' => 'Ukuran file brosur maksimal 25 MB.',
                ]);

                if ($validator->fails()) {
                    return redirect()->back()
                        ->withErrors($validator)
                        ->with('error', 'Validasi brosur gagal: ' . implode(' ', $validator->errors()->all()))
                        ->with('active_tab', $activeTab)
                        ->withInput();
                }

                try {
                    $filename = 'brosur_' . time() . '.' . $file->getClientOriginalExtension();
                    $dest = public_path('uploads/settings');
                    if (!file_exists($dest)) {
                        mkdir($dest, 0777, true);
                    }
                    $file->move($dest, $filename);
                    Setting::set('brosur_file_url', '/uploads/settings/' . $filename, 'general');
                } catch (\Exception $e) {
                    return redirect()->back()
                        ->with('error', 'Gagal menyimpan file brosur ke server: ' . $e->getMessage())
                        ->with('active_tab', $activeTab)
                        ->withInput();
                }
            } elseif ($request->filled('brosur_file_url')) {
                Setting::set('brosur_file_url', trim($request->brosur_file_url), 'general');
            }
        }

        // 4. Handle File Upload Buku Panduan Santri (PDF Resmi)
        if ($sectionName === 'Buku Panduan Santri' || $request->hasFile('panduan_file') || $request->filled('panduan_file_url')) {
            if ($request->hasFile('panduan_file')) {
                $file = $request->file('panduan_file');
                if (!$file->isValid()) {
                    $errText = $phpUploadErrors[$file->getError()] ?? $file->getErrorMessage();
                    return redirect()->back()
                        ->with('error', 'Gagal mengunggah file buku panduan: ' . $errText)
                        ->with('active_tab', $activeTab)
                        ->withInput();
                }

                $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
                    'panduan_file' => 'required|file|mimes:pdf|max:35840',
                ], [
                    'panduan_file.mimes' => 'File buku panduan resmi harus berformat PDF.',
                    'panduan_file.max' => 'Ukuran file buku panduan maksimal 35 MB.',
                ]);

                if ($validator->fails()) {
                    return redirect()->back()
                        ->withErrors($validator)
                        ->with('error', 'Validasi buku panduan gagal: ' . implode(' ', $validator->errors()->all()))
                        ->with('active_tab', $activeTab)
                        ->withInput();
                }

                try {
                    $filename = 'panduan_' . time() . '.' . $file->getClientOriginalExtension();
                    $dest = public_path('uploads/settings');
                    if (!file_exists($dest)) {
                        mkdir($dest, 0777, true);
                    }
                    $file->move($dest, $filename);
                    Setting::set('panduan_file_url', '/uploads/settings/' . $filename, 'general');
                } catch (\Exception $e) {
                    return redirect()->back()
                        ->with('error', 'Gagal menyimpan file buku panduan ke server: ' . $e->getMessage())
                        ->with('active_tab', $activeTab)
                        ->withInput();
                }
            } elseif ($request->filled('panduan_file_url')) {
                Setting::set('panduan_file_url', trim($request->panduan_file_url), 'general');
            }
        }

        // 4b. Handle Tanda Tangan Digital Pengurus & Stempel Resmi PSB
        if ($sectionName === 'Tanda Tangan Digital & Stempel PSB' || $request->hasFile('ttd_digital_pengurus_file') || $request->filled('ttd_digital_pengurus_canvas') || $request->hasFile('ttd_digital_stempel_file')) {
            $dest = public_path('uploads/settings');
            if (!file_exists($dest)) {
                mkdir($dest, 0777, true);
            }

            // Upload File TTD Digital
            if ($request->hasFile('ttd_digital_pengurus_file')) {
                $file = $request->file('ttd_digital_pengurus_file');
                if ($file->isValid()) {
                    $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
                        'ttd_digital_pengurus_file' => 'required|image|mimes:jpeg,png,jpg,webp,svg|max:10240',
                    ], [
                        'ttd_digital_pengurus_file.image' => 'File tanda tangan harus berupa file gambar.',
                        'ttd_digital_pengurus_file.mimes' => 'Format file gambar tanda tangan: PNG, JPG, JPEG, WEBP, SVG.',
                        'ttd_digital_pengurus_file.max' => 'Ukuran file tanda tangan maksimal 10 MB.',
                    ]);

                    if ($validator->fails()) {
                        return redirect()->back()
                            ->withErrors($validator)
                            ->with('error', 'Validasi file tanda tangan gagal: ' . implode(' ', $validator->errors()->all()))
                            ->with('active_tab', $activeTab)
                            ->withInput();
                    }

                    $filename = 'ttd_' . time() . '.' . $file->getClientOriginalExtension();
                    $file->move($dest, $filename);
                    Setting::set('ttd_digital_pengurus_image', '/uploads/settings/' . $filename, 'psb');
                }
            } elseif ($request->filled('ttd_digital_pengurus_canvas')) {
                // TTD dari Canvas Gambar Langsung
                $canvasData = $request->input('ttd_digital_pengurus_canvas');
                if (preg_match('/^data:image\/(\w+);base64,/', $canvasData)) {
                    $base64Image = substr($canvasData, strpos($canvasData, ',') + 1);
                    $decodedImage = base64_decode($base64Image);
                    if ($decodedImage) {
                        $filename = 'ttd_canvas_' . time() . '.png';
                        file_put_contents($dest . '/' . $filename, $decodedImage);
                        Setting::set('ttd_digital_pengurus_image', '/uploads/settings/' . $filename, 'psb');
                    }
                }
            } elseif ($request->filled('ttd_digital_pengurus_image')) {
                Setting::set('ttd_digital_pengurus_image', trim($request->ttd_digital_pengurus_image), 'psb');
            }

            // Upload File Stempel Resmi Pesantren
            if ($request->hasFile('ttd_digital_stempel_file')) {
                $file = $request->file('ttd_digital_stempel_file');
                if ($file->isValid()) {
                    $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
                        'ttd_digital_stempel_file' => 'required|image|mimes:jpeg,png,jpg,webp,svg|max:10240',
                    ], [
                        'ttd_digital_stempel_file.image' => 'File stempel harus berupa file gambar.',
                        'ttd_digital_stempel_file.mimes' => 'Format file gambar stempel: PNG, JPG, JPEG, WEBP, SVG.',
                        'ttd_digital_stempel_file.max' => 'Ukuran file stempel maksimal 10 MB.',
                    ]);

                    if ($validator->fails()) {
                        return redirect()->back()
                            ->withErrors($validator)
                            ->with('error', 'Validasi file stempel gagal: ' . implode(' ', $validator->errors()->all()))
                            ->with('active_tab', $activeTab)
                            ->withInput();
                    }

                    $filename = 'stempel_' . time() . '.' . $file->getClientOriginalExtension();
                    $file->move($dest, $filename);
                    Setting::set('ttd_digital_stempel_image', '/uploads/settings/' . $filename, 'psb');
                }
            } elseif ($request->filled('ttd_digital_stempel_image')) {
                Setting::set('ttd_digital_stempel_image', trim($request->ttd_digital_stempel_image), 'psb');
            }

            // Konfigurasi Nama, Jabatan, NIP, Kota
            if ($request->filled('ttd_digital_nama')) {
                Setting::set('ttd_digital_nama', trim($request->ttd_digital_nama), 'psb');
            }
            if ($request->filled('ttd_digital_jabatan')) {
                Setting::set('ttd_digital_jabatan', trim($request->ttd_digital_jabatan), 'psb');
            }
            if ($request->has('ttd_digital_nip')) {
                Setting::set('ttd_digital_nip', trim($request->ttd_digital_nip), 'psb');
            }
            if ($request->filled('ttd_digital_kota')) {
                Setting::set('ttd_digital_kota', trim($request->ttd_digital_kota), 'psb');
            }
            Setting::set('ttd_digital_show_stempel', $request->has('ttd_digital_show_stempel') ? '1' : '0', 'psb');
        }

        // 5. Handle Jadwal Santri 24 Jam
        if ($request->has('jadwal_waktu')) {
            $jadwalList = [];
            $waktuArr = (array) $request->jadwal_waktu;
            $judulArr = (array) $request->jadwal_judul;
            $ketArr = (array) $request->jadwal_keterangan;

            for ($i = 0; $i < count($waktuArr); $i++) {
                if (!empty(trim($waktuArr[$i])) && !empty(trim($judulArr[$i]))) {
                    $jadwalList[] = [
                        'waktu' => trim($waktuArr[$i]),
                        'judul' => trim($judulArr[$i]),
                        'keterangan' => trim($ketArr[$i] ?? ''),
                    ];
                }
            }
            Setting::set('jadwal_santri_json', json_encode($jadwalList, JSON_PRETTY_PRINT), 'jadwal');
        }

        // 6. Handle Agenda Berkala Santri
        if ($request->has('agenda_hari')) {
            $agendaList = [];
            $hArr = (array) $request->agenda_hari;
            $kArr = (array) $request->agenda_kegiatan;
            $ketArr = (array) $request->agenda_keterangan;

            for ($i = 0; $i < count($hArr); $i++) {
                if (!empty(trim($hArr[$i])) && !empty(trim($kArr[$i] ?? ''))) {
                    $agendaList[] = [
                        'hari' => trim($hArr[$i]),
                        'kegiatan' => trim($kArr[$i] ?? ''),
                        'keterangan' => trim($ketArr[$i] ?? ''),
                    ];
                }
            }
            Setting::set('agenda_berkala_json', json_encode($agendaList, JSON_PRETTY_PRINT), 'jadwal');
        }

        // 7. Handle Panca Jiwa Falsafah
        if ($request->has('panca_judul')) {
            $pancaList = [];
            $nArr = (array) $request->panca_nomor;
            $jArr = (array) $request->panca_judul;
            $dArr = (array) $request->panca_deskripsi;

            for ($i = 0; $i < count($jArr); $i++) {
                if (!empty(trim($jArr[$i]))) {
                    $pancaList[] = [
                        'nomor' => trim($nArr[$i] ?? sprintf('%02d', $i + 1)),
                        'judul' => trim($jArr[$i]),
                        'deskripsi' => trim($dArr[$i] ?? ''),
                    ];
                }
            }
            Setting::set('panca_jiwa_json', json_encode($pancaList, JSON_PRETTY_PRINT), 'profil');
        }

        // 8. Handle Makna Filosofi Lambang Pesantren
        if ($request->has('filosofi_elemen')) {
            $filosofiList = [];
            $eArr = (array) $request->filosofi_elemen;
            $mArr = (array) $request->filosofi_makna;

            for ($i = 0; $i < count($eArr); $i++) {
                if (!empty(trim($eArr[$i]))) {
                    $filosofiList[] = [
                        'elemen' => trim($eArr[$i]),
                        'makna' => trim($mArr[$i] ?? ''),
                    ];
                }
            }
            Setting::set('filosofi_lambang_json', json_encode($filosofiList, JSON_PRETTY_PRINT), 'profil');
        }

        // 9. Handle Fondasi Pendidikan Integral (4 Pilar)
        if ($request->has('pilar_judul')) {
            $pilarList = [];
            $jArr = (array) $request->pilar_judul;
            $dArr = (array) $request->pilar_deskripsi;
            $tArr = (array) $request->pilar_tag;

            for ($i = 0; $i < count($jArr); $i++) {
                if (!empty(trim($jArr[$i]))) {
                    $pilarList[] = [
                        'judul' => trim($jArr[$i]),
                        'deskripsi' => trim($dArr[$i] ?? ''),
                        'tag' => trim($tArr[$i] ?? ''),
                    ];
                }
            }
            Setting::set('pilar_pendidikan_json', json_encode($pilarList, JSON_PRETTY_PRINT), 'program');
        }

        // 10. Handle Tabel Biaya Awal PSB
        if ($request->has('biaya_awal_komponen')) {
            $biayaAwalList = [];
            $kArr = (array) $request->biaya_awal_komponen;
            $mmArr = (array) $request->biaya_awal_mts_mukim;
            $mlArr = (array) $request->biaya_awal_mts_laju;
            $amArr = (array) $request->biaya_awal_ma_mukim;
            $alArr = (array) $request->biaya_awal_ma_laju;
            $totArr = (array) $request->biaya_awal_is_total;

            for ($i = 0; $i < count($kArr); $i++) {
                if (!empty(trim($kArr[$i]))) {
                    $biayaAwalList[] = [
                        'komponen' => trim($kArr[$i]),
                        'mts_mukim' => trim($mmArr[$i] ?? '—'),
                        'mts_laju' => trim($mlArr[$i] ?? '—'),
                        'ma_mukim' => trim($amArr[$i] ?? '—'),
                        'ma_laju' => trim($alArr[$i] ?? '—'),
                        'is_total' => !empty($totArr[$i]),
                    ];
                }
            }
            Setting::set('biaya_awal_json', json_encode($biayaAwalList, JSON_PRETTY_PRINT), 'biaya');
        }

        // 11. Handle Tabel Biaya Bulanan (SPP)
        if ($request->has('biaya_bulanan_komponen')) {
            $biayaBulananList = [];
            $kArr = (array) $request->biaya_bulanan_komponen;
            $mmArr = (array) $request->biaya_bulanan_mts_mukim;
            $mlArr = (array) $request->biaya_bulanan_mts_laju;
            $amArr = (array) $request->biaya_bulanan_ma_mukim;
            $alArr = (array) $request->biaya_bulanan_ma_laju;
            $totArr = (array) $request->biaya_bulanan_is_total;

            for ($i = 0; $i < count($kArr); $i++) {
                if (!empty(trim($kArr[$i]))) {
                    $biayaBulananList[] = [
                        'komponen' => trim($kArr[$i]),
                        'mts_mukim' => trim($mmArr[$i] ?? '—'),
                        'mts_laju' => trim($mlArr[$i] ?? '—'),
                        'ma_mukim' => trim($amArr[$i] ?? '—'),
                        'ma_laju' => trim($alArr[$i] ?? '—'),
                        'is_total' => !empty($totArr[$i]),
                    ];
                }
            }
            Setting::set('biaya_bulanan_json', json_encode($biayaBulananList, JSON_PRETTY_PRINT), 'biaya');
        }

        // 12. Simpan field text & konfigurasi lainnya
        $exclude = [
            '_token', '_method', 'section_name', 'active_tab',
            'hero_image_file', 'hero_image',
            'sambutan_foto_file', 'sambutan_foto',
            'brosur_file', 'brosur_file_url',
            'panduan_file', 'panduan_file_url',
            'jadwal_waktu', 'jadwal_judul', 'jadwal_keterangan',
            'agenda_hari', 'agenda_kegiatan', 'agenda_keterangan',
            'panca_nomor', 'panca_judul', 'panca_deskripsi',
            'filosofi_elemen', 'filosofi_makna',
            'pilar_judul', 'pilar_deskripsi', 'pilar_tag',
            'biaya_awal_komponen', 'biaya_awal_mts_mukim', 'biaya_awal_mts_laju', 'biaya_awal_ma_mukim', 'biaya_awal_ma_laju', 'biaya_awal_is_total',
            'biaya_bulanan_komponen', 'biaya_bulanan_mts_mukim', 'biaya_bulanan_mts_laju', 'biaya_bulanan_ma_mukim', 'biaya_bulanan_ma_laju', 'biaya_bulanan_is_total',
            'ttd_digital_pengurus_file', 'ttd_digital_pengurus_canvas', 'ttd_digital_stempel_file',
            'hero_slide_file_1', 'hero_slide_file_2', 'hero_slide_file_3', 'hero_slide_file_4', 'hero_slide_file_5',
            'hero_slide_image_1', 'hero_slide_image_2', 'hero_slide_image_3', 'hero_slide_image_4', 'hero_slide_image_5',
            'hero_slide_caption_1', 'hero_slide_caption_2', 'hero_slide_caption_3', 'hero_slide_caption_4', 'hero_slide_caption_5',
            'hero_slide_subcaption_1', 'hero_slide_subcaption_2', 'hero_slide_subcaption_3', 'hero_slide_subcaption_4', 'hero_slide_subcaption_5',
            'hero_slide_active_1', 'hero_slide_active_2', 'hero_slide_active_3', 'hero_slide_active_4', 'hero_slide_active_5',
        ];
        $data = $request->except($exclude);

        foreach ($data as $key => $val) {
            $group = 'general';
            if (str_starts_with($key, 'hero_')) {
                $group = 'hero';
            } elseif (str_starts_with($key, 'sosmed_') || str_starts_with($key, 'kontak_') || str_starts_with($key, 'rek_') || str_starts_with($key, 'footer_') || $key === 'alamat_kampus') {
                $group = 'kontak';
            } elseif (str_starts_with($key, 'profil_') || str_starts_with($key, 'sejarah_') || str_starts_with($key, 'sambutan_') || str_starts_with($key, 'quran_') || in_array($key, ['visi', 'misi', 'nspp', 'mts_npsn', 'ma_npsn', 'status_tanah', 'falsafah_judul', 'falsafah_subjudul', 'filosofi_judul', 'filosofi_subjudul', 'pilar_title', 'pilar_subtitle'])) {
                $group = 'profil';
            }
            Setting::set($key, $val, $group);
        }

        // Sinkronisasi otomatis nomor hotline panitia/CS ke semua alias key
        if ($request->filled('kontak_hotline')) {
            $hotlineVal = trim($request->kontak_hotline);
            Setting::set('kontak_hotline_1', $hotlineVal, 'kontak');
            Setting::set('kontak_cs_phone', $hotlineVal, 'kontak');
            Setting::set('kontak_wa_psb', $hotlineVal, 'kontak');
        }

        return redirect()->back()
            ->with('success', "{$sectionName} berhasil disimpan dan diperbarui!")
            ->with('active_tab', $activeTab);
    }

    /*
    |--------------------------------------------------------------------------
    | MANAJEMEN PENGGUNA (SUPERADMIN & ADMIN)
    |--------------------------------------------------------------------------
    */

    /**
     * Tampilkan daftar pengguna. Hanya dapat diakses oleh Superadmin.
     */
    public function userIndex(Request $request)
    {
        if (!auth()->user()->isSuperAdmin()) {
            return redirect()->route('admin.profile')->with('warning', 'Hanya Superadmin yang berhak mengelola akun pengguna lain. Anda dapat memperbarui profil dan kata sandi Anda di halaman ini.');
        }

        $query = User::query();

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->orderBy('role', 'asc')->latest()->paginate(10)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Form tambah pengguna baru. Hanya untuk Superadmin.
     */
    public function userCreate()
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Akses ditolak. Hanya Superadmin yang diizinkan menambah akun admin.');
        }

        return view('admin.users.create');
    }

    /**
     * Simpan pengguna baru ke database.
     */
    public function userStore(Request $request)
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Akses ditolak. Hanya Superadmin yang diizinkan menambah akun admin.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'role' => 'required|string|in:superadmin,admin,bendahara',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.unique' => 'Email ini sudah terdaftar untuk pengguna lain.',
            'role.required' => 'Pilih peran (role) pengguna.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'jabatan' => $request->input('jabatan'),
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.users.index')->with('success', "Pengguna baru dengan peran {$request->role} berhasil ditambahkan!");
    }

    /**
     * Form ubah data pengguna.
     */
    public function userEdit($id)
    {
        $user = User::findOrFail($id);

        // Jika bukan superadmin dan bukan mengedit dirinya sendiri, tolak
        if (!auth()->user()->isSuperAdmin() && auth()->id() !== $user->id) {
            abort(403, 'Akses ditolak.');
        }

        return view('admin.users.edit', compact('user'));
    }

    /**
     * Perbarui data pengguna.
     */
    public function userUpdate(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $isSelf = auth()->id() === $user->id;

        if (!auth()->user()->isSuperAdmin() && !$isSelf) {
            abort(403, 'Akses ditolak.');
        }

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
        ];

        // Hanya superadmin yang boleh mengubah role
        if (auth()->user()->isSuperAdmin()) {
            $rules['role'] = 'required|string|in:superadmin,admin,bendahara';
        }

        $request->validate($rules, [
            'name.required' => 'Nama pengguna wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.unique' => 'Email ini sudah digunakan oleh akun lain.',
            'password.min' => 'Kata sandi minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'jabatan' => $request->input('jabatan'),
        ];

        if (auth()->user()->isSuperAdmin() && $request->filled('role')) {
            // Mencegah superadmin menurunkan role dirinya sendiri jika dia adalah satu-satunya superadmin
            if ($isSelf && $request->role !== 'superadmin' && User::where('role', 'superadmin')->count() <= 1) {
                return back()->withErrors(['role' => 'Anda adalah satu-satunya Superadmin. Role tidak dapat diubah menjadi Admin.']);
            }
            $userData['role'] = $request->role;
        }

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        if (!auth()->user()->isSuperAdmin()) {
            return redirect()->route('admin.profile')->with('success', 'Profil dan kata sandi Anda berhasil diperbarui!');
        }

        return redirect()->route('admin.users.index')->with('success', "Data pengguna {$user->name} berhasil diperbarui!");
    }

    /**
     * Hapus pengguna. Hanya untuk Superadmin.
     */
    public function userDestroy($id)
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Akses ditolak. Hanya Superadmin yang berhak menghapus akun.');
        }

        $user = User::findOrFail($id);

        if (auth()->id() === $user->id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', "Pengguna {$userName} berhasil dihapus dari sistem.");
    }

    /**
     * Halaman profil & edit password sendiri (Dapat diakses oleh Admin biasa maupun Superadmin).
     */
    public function profile()
    {
        $user = auth()->user();
        return view('admin.users.profile', compact('user'));
    }

    /**
     * Proses perbarui profil, jabatan, tanda tangan digital, & password sendiri.
     */
    public function profileUpdate(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'jabatan' => 'nullable|string|max:100',
            'signature_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'signature_canvas' => 'nullable|string',
            'stempel_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'current_password' => 'required_with:password|string',
            'password' => 'nullable|string|min:6|confirmed',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'signature_file.image' => 'File tanda tangan harus berupa gambar (PNG/JPG/WEBP).',
            'signature_file.max' => 'Ukuran file tanda tangan maksimal 3MB.',
            'stempel_file.image' => 'File cap stempel harus berupa gambar (PNG/JPG/WEBP).',
            'stempel_file.max' => 'Ukuran file cap stempel maksimal 3MB.',
            'current_password.required_with' => 'Masukkan kata sandi saat ini untuk menetapkan kata sandi baru.',
            'password.min' => 'Kata sandi baru minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak sesuai.',
        ]);

        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Kata sandi saat ini salah.']);
            }
            $user->password = Hash::make($request->password);
        }

        $user->name = $request->name;
        if ($request->has('jabatan')) {
            $user->jabatan = $request->jabatan;
        }

        // Hapus TTD jika diminta
        if ($request->boolean('remove_signature')) {
            if ($user->signature_image && file_exists(public_path($user->signature_image))) {
                @unlink(public_path($user->signature_image));
            }
            $user->signature_image = null;
        }
        // Unggah file TTD baru
        elseif ($request->hasFile('signature_file')) {
            if ($user->signature_image && file_exists(public_path($user->signature_image))) {
                @unlink(public_path($user->signature_image));
            }
            $file = $request->file('signature_file');
            $filename = 'ttd_user_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/signatures'), $filename);
            $user->signature_image = '/uploads/signatures/' . $filename;
        }
        // Atau simpan dari Canvas Pad
        elseif ($request->filled('signature_canvas') && str_starts_with($request->signature_canvas, 'data:image')) {
            $dataUrl = $request->signature_canvas;
            if (preg_match('/^data:image\/(\w+);base64,/', $dataUrl)) {
                $rawBase64 = substr($dataUrl, strpos($dataUrl, ',') + 1);
                $decoded = base64_decode($rawBase64);
                if ($decoded !== false) {
                    if ($user->signature_image && file_exists(public_path($user->signature_image))) {
                        @unlink(public_path($user->signature_image));
                    }
                    $filename = 'ttd_canvas_user_' . $user->id . '_' . time() . '.png';
                    file_put_contents(public_path('uploads/signatures/' . $filename), $decoded);
                    $user->signature_image = '/uploads/signatures/' . $filename;
                }
            }
        }

        // Hapus Cap Stempel pribadi jika diminta
        if ($request->boolean('remove_stempel')) {
            if ($user->stempel_image && file_exists(public_path($user->stempel_image))) {
                @unlink(public_path($user->stempel_image));
            }
            $user->stempel_image = null;
        }
        // Unggah file Cap Stempel baru
        elseif ($request->hasFile('stempel_file')) {
            if ($user->stempel_image && file_exists(public_path($user->stempel_image))) {
                @unlink(public_path($user->stempel_image));
            }
            $file = $request->file('stempel_file');
            $filename = 'stempel_user_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/signatures'), $filename);
            $user->stempel_image = '/uploads/signatures/' . $filename;
        }

        $user->save();

        return back()->with('success', 'Profil, jabatan, tanda tangan digital, dan cap stempel Anda berhasil diperbarui!');
    }
}
