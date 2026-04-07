<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Menyesuaikan dengan nama tabel di database
    protected $table = 'ms_user';
    protected $primaryKey = 'id_user';
    
    // Matikan timestamps otomatis Laravel karena kita handle manual/lewat DB
    // Pastikan di database kolom created_at memiliki default 'current_timestamp()'
    public $timestamps = false; 

    // Kolom yang boleh diisi (Mass Assignment)
    // 🚨 no_telp HARUS ADA DI SINI agar tidak NULL saat registrasi
    protected $fillable = [
        'nama',
        'email',
        'password',
        'no_telp',
        'alamat',
        'foto_profil',
        'google_id',
        'id_role', 
    ];

    // Menyembunyikan data sensitif agar tidak muncul saat return JSON/Array
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting kolom agar memiliki tipe data tertentu secara otomatis.
     * Fitur ini sangat penting agar fungsi ->format('d/m/Y') tidak error.
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'email_verified_at' => 'datetime',
            'created_at' => 'datetime', // Mengubah string DB menjadi Objek Tanggal (Carbon)
            'updated_at' => 'datetime', // Mengubah string DB menjadi Objek Tanggal (Carbon)
        ];
    }

    /**
     * Relasi ke Tabel Role (Opsional jika ingin panggil $user->role->nama_role)
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'id_role');
    }
}