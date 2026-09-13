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
        if (!Schema::hasTable('dormitories')) {
            Schema::create('dormitories', function (Blueprint $table) {
                $table->id();
                $table->string('nama_asrama', 100); // Contoh: Gedung Abu Bakar Ash-Shiddiq
                $table->string('kamar', 50);        // Contoh: Kamar 01 (Al-Fatih)
                $table->string('gender', 20)->default('Laki-laki'); // Laki-laki / Perempuan
                $table->integer('kapasitas')->default(8);
                $table->string('musyrif', 100)->nullable(); // Pembina / Musyrif Kamar
                $table->string('lokasi', 100)->nullable();  // Lantai 1 / Lantai 2
                $table->text('keterangan')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasColumn('students', 'dormitory_id')) {
            Schema::table('students', function (Blueprint $table) {
                $table->unsignedBigInteger('dormitory_id')->nullable()->after('kamar_asrama');
                $table->foreign('dormitory_id')->references('id')->on('dormitories')->nullOnDelete();
            });
        }

        // Seed kamar asrama awal jika kosong
        if (DB::table('dormitories')->count() === 0) {
            $initialRooms = [
                // Asrama Putra
                [
                    'nama_asrama' => 'Asrama Putra Abu Bakar',
                    'kamar' => 'Kamar 01 (Al-Fatih)',
                    'gender' => 'Laki-laki',
                    'kapasitas' => 8,
                    'musyrif' => 'Ust. Muhammad Zaki, S.Pd.I',
                    'lokasi' => 'Gedung A - Lantai 1',
                    'keterangan' => 'Kamar Santri Putra MTs Mukim',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'nama_asrama' => 'Asrama Putra Abu Bakar',
                    'kamar' => 'Kamar 02 (Al-Farabi)',
                    'gender' => 'Laki-laki',
                    'kapasitas' => 8,
                    'musyrif' => 'Ust. Muhammad Zaki, S.Pd.I',
                    'lokasi' => 'Gedung A - Lantai 1',
                    'keterangan' => 'Kamar Santri Putra MTs Mukim',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'nama_asrama' => 'Asrama Putra Umar Bin Khattab',
                    'kamar' => 'Kamar 03 (Thariq Bin Ziyad)',
                    'gender' => 'Laki-laki',
                    'kapasitas' => 8,
                    'musyrif' => 'Ust. Ahmad Fauzan, Lc',
                    'lokasi' => 'Gedung A - Lantai 2',
                    'keterangan' => 'Kamar Santri Putra MA Mukim',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'nama_asrama' => 'Asrama Putra Umar Bin Khattab',
                    'kamar' => 'Kamar 04 (Salahuddin Al-Ayyubi)',
                    'gender' => 'Laki-laki',
                    'kapasitas' => 8,
                    'musyrif' => 'Ust. Ahmad Fauzan, Lc',
                    'lokasi' => 'Gedung A - Lantai 2',
                    'keterangan' => 'Kamar Santri Putra MA Mukim',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                // Asrama Putri
                [
                    'nama_asrama' => 'Asrama Putri Khadijah Al-Kubra',
                    'kamar' => 'Kamar 01 (Maryam)',
                    'gender' => 'Perempuan',
                    'kapasitas' => 8,
                    'musyrif' => 'Ustzh. Siti Rahmawati, S.Ag',
                    'lokasi' => 'Gedung B - Lantai 1',
                    'keterangan' => 'Kamar Santri Putri MTs Mukim',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'nama_asrama' => 'Asrama Putri Khadijah Al-Kubra',
                    'kamar' => 'Kamar 02 (Fatimah)',
                    'gender' => 'Perempuan',
                    'kapasitas' => 8,
                    'musyrif' => 'Ustzh. Siti Rahmawati, S.Ag',
                    'lokasi' => 'Gedung B - Lantai 1',
                    'keterangan' => 'Kamar Santri Putri MTs Mukim',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'nama_asrama' => 'Asrama Putri Aisyah Binti Abu Bakar',
                    'kamar' => 'Kamar 03 (Asma Binti Abu Bakar)',
                    'gender' => 'Perempuan',
                    'kapasitas' => 8,
                    'musyrif' => 'Ustzh. Nur Hidayah, M.Pd',
                    'lokasi' => 'Gedung B - Lantai 2',
                    'keterangan' => 'Kamar Santri Putri MA Mukim',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'nama_asrama' => 'Asrama Putri Aisyah Binti Abu Bakar',
                    'kamar' => 'Kamar 04 (Khaula Binti Azwar)',
                    'gender' => 'Perempuan',
                    'kapasitas' => 8,
                    'musyrif' => 'Ustzh. Nur Hidayah, M.Pd',
                    'lokasi' => 'Gedung B - Lantai 2',
                    'keterangan' => 'Kamar Santri Putri MA Mukim',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ];

            DB::table('dormitories')->insert($initialRooms);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('students', 'dormitory_id')) {
            Schema::table('students', function (Blueprint $table) {
                $table->dropForeign(['dormitory_id']);
                $table->dropColumn('dormitory_id');
            });
        }

        Schema::dropIfExists('dormitories');
    }
};
