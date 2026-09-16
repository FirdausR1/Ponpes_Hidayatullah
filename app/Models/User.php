<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'role',
        'signature_image',
        'stempel_image',
        'jabatan',
        'password',
    ];

    /**
     * Cek apakah user memiliki peran superadmin.
     */
    public function isSuperAdmin(): bool
    {
        return ($this->role ?? 'admin') === 'superadmin';
    }

    /**
     * Cek apakah user memiliki peran admin biasa.
     */
    public function isAdmin(): bool
    {
        return ($this->role ?? 'admin') === 'admin';
    }

    /**
     * Cek apakah user memiliki peran bendahara.
     */
    public function isBendahara(): bool
    {
        return ($this->role ?? '') === 'bendahara';
    }

    /**
     * Cek izin kelola Pengguna (Superadmin saja).
     */
    public function canManageUsers(): bool
    {
        return $this->isSuperAdmin();
    }

    /**
     * Cek izin kelola Pengaturan Web & Berita (Superadmin & Admin).
     */
    public function canManageSettings(): bool
    {
        return in_array($this->role ?? '', ['superadmin', 'admin']);
    }

    public function canManageBerita(): bool
    {
        return in_array($this->role ?? '', ['superadmin', 'admin']);
    }

    /**
     * Cek izin kelola Bank Soal CBT (Superadmin & Admin).
     */
    public function canManageCbt(): bool
    {
        return in_array($this->role ?? '', ['superadmin', 'admin']);
    }

    /**
     * Cek izin kelola Data Santri (Superadmin, Admin, & Bendahara).
     */
    public function canManageStudents(): bool
    {
        return in_array($this->role ?? '', ['superadmin', 'admin', 'bendahara']);
    }

    /**
     * Cek izin kelola Pembayaran (Hanya Superadmin & Bendahara, Admin Biasa TIDAK BISA).
     */
    public function canManagePayments(): bool
    {
        return in_array($this->role ?? '', ['superadmin', 'bendahara']);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Relasi ke transaksi pembayaran santri yang dicatat oleh user ini.
     */
    public function studentPayments()
    {
        return $this->hasMany(StudentPayment::class, 'user_id');
    }

    /**
     * Relasi ke catatan beban kas pengeluaran operasional yang dicatat oleh user ini.
     */
    public function operationalExpenses()
    {
        return $this->hasMany(OperationalExpense::class, 'user_id');
    }
}
