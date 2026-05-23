<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservasi extends Model
{
    use HasFactory;

    protected $table = 'tr_reservasi';
    protected $primaryKey = 'id_reservasi';
    
    // Konfigurasi Primary Key Kustom
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_user',
        'tanggal_reservasi',
        'metode_layanan',
        'alamat_lengkap',
        'status',
        'status_bayar',
        'total_harga',
        'metode_masuk',
        'metode_keluar',
        'metode_bayar',   // <-- SUDAH DITAMBAHKAN
        'tanggal_bayar'   // <-- SUDAH DITAMBAHKAN
    ];

    protected $casts = [
        'tanggal_reservasi' => 'date',
        'total_harga' => 'integer',
        'tanggal_bayar' => 'datetime', // <-- DITAMBAHKAN AGAR LARAVEL OTOMATIS BACA SEBAGAI FORMAT WAKTU
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function detail()
    {
        return $this->hasMany(DetailReservasi::class, 'id_reservasi', 'id_reservasi');
    }

    // FUNGSI INI DIMATIKAN (DI-COMMENT) KARENA TABEL PEMBAYARAN SUDAH DIHAPUS
    // public function pembayaran()
    // {
    //     return $this->hasOne(Pembayaran::class, 'id_reservasi', 'id_reservasi');
    // }

    public function layanan()
    {
        return $this->belongsToMany(Layanan::class, 'tr_detail_reservasi', 'id_reservasi', 'id_layanan')
                    ->withPivot('id_detail', 'harga', 'jumlah', 'sub_total');
    }
}