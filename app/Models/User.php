<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // 1. Nama tabel sesuai ms_user di database
    protected $table = 'ms_user';
    
    // 2. Primary Key menggunakan 'id_user'
    protected $primaryKey = 'id_user';

    // 3. Timestamps aktif (karena di ERD ada created_at & updated_at)
    public $timestamps = true; 

    /**
     * 4. Daftar kolom yang boleh diisi (Mass Assignment)
     */
    protected $fillable = [
        'nama',
        'email',
        'password',
        'role',       // ✨ UPDATE: Menggunakan role (ENUM)
        'status',     // ✨ UPDATE: Menggunakan status (ENUM)
        'no_telp',
        'alamat',
        'foto_profil',
        'google_id',
    ];

    // 5. Sembunyikan data sensitif
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting tipe data otomatis
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            // is_active dihapus karena sekarang full menggunakan 'status'
        ];
    }
}