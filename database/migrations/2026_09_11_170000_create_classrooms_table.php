<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('classrooms', function (Blueprint $table) {
            $table->id();
            $table->string('jenjang', 30); // MTs, MA, SMP, SMA, Tahfidz
            $table->string('tingkat', 20); // VII, VIII, IX, X, XI, XII
            $table->string('nama_kelas', 50)->unique(); // VII-A, VII-B, X-A, dll.
            $table->string('wali_kelas')->nullable();
            $table->integer('kapasitas')->default(30);
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });

        // Seed kelas-kelas awal
        $defaultClasses = [
            ['jenjang' => 'MTs', 'tingkat' => 'VII', 'nama_kelas' => 'VII-A', 'wali_kelas' => 'Ustadz Ahmad Fauzi, S.Pd.I', 'kapasitas' => 30],
            ['jenjang' => 'MTs', 'tingkat' => 'VII', 'nama_kelas' => 'VII-B', 'wali_kelas' => 'Ustadzah Siti Aminah, S.Pd', 'kapasitas' => 30],
            ['jenjang' => 'MTs', 'tingkat' => 'VIII', 'nama_kelas' => 'VIII-A', 'wali_kelas' => 'Ustadz Ridwan Malik, Lc', 'kapasitas' => 30],
            ['jenjang' => 'MTs', 'tingkat' => 'VIII', 'nama_kelas' => 'VIII-B', 'wali_kelas' => 'Ustadzah Nurul Hidayah, S.Ag', 'kapasitas' => 30],
            ['jenjang' => 'MTs', 'tingkat' => 'IX', 'nama_kelas' => 'IX-A', 'wali_kelas' => 'Ustadz M. Yusuf, M.Pd', 'kapasitas' => 30],
            ['jenjang' => 'MTs', 'tingkat' => 'IX', 'nama_kelas' => 'IX-B', 'wali_kelas' => 'Ustadzah Khadijah, S.Si', 'kapasitas' => 30],
            ['jenjang' => 'MA', 'tingkat' => 'X', 'nama_kelas' => 'X-A', 'wali_kelas' => 'Ustadz Abdullah, M.Ag', 'kapasitas' => 30],
            ['jenjang' => 'MA', 'tingkat' => 'X', 'nama_kelas' => 'X-B', 'wali_kelas' => 'Ustadzah Fatimah, M.Pd', 'kapasitas' => 30],
            ['jenjang' => 'MA', 'tingkat' => 'XI', 'nama_kelas' => 'XI-A', 'wali_kelas' => 'Ustadz Hasan Basri, Lc', 'kapasitas' => 30],
            ['jenjang' => 'MA', 'tingkat' => 'XI', 'nama_kelas' => 'XI-B', 'wali_kelas' => 'Ustadzah Maryam, S.Pd', 'kapasitas' => 30],
            ['jenjang' => 'MA', 'tingkat' => 'XII', 'nama_kelas' => 'XII-A', 'wali_kelas' => 'Ustadz Dr. H. Syarifuddin', 'kapasitas' => 30],
            ['jenjang' => 'MA', 'tingkat' => 'XII', 'nama_kelas' => 'XII-B', 'wali_kelas' => 'Ustadzah Hj. Halimah, M.A', 'kapasitas' => 30],
        ];

        $now = now();
        foreach ($defaultClasses as &$c) {
            $c['created_at'] = $now;
            $c['updated_at'] = $now;
        }

        DB::table('classrooms')->insert($defaultClasses);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classrooms');
    }
};
