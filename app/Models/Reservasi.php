<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservasi extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'tr_reservasi';

    // Primary Key
    protected $primaryKey = 'id_reservasi';

    // Kolom yang boleh diisi
    protected $fillable = [
        'id_user',
        'tanggal_reservasi',
        'jumlah_sepatu',
        'metode_layanan',
        'alamat_jemput',
        'metode_pengembalian',
        'status',
        'total_harga',
        'wa_pengantaran',      // 🚨 TAMBAHAN BARU: WA Kurir
        'alamat_pengantaran'   // 🚨 TAMBAHAN BARU: Alamat Kurir
    ];

    /**
     * Relasi ke User (Pemilik Reservasi)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    /**
     * Relasi ke Detail Reservasi (Pintu menuju data Layanan)
     */
    public function detail()
    {
        return $this->hasOne(DetailReservasi::class, 'id_reservasi', 'id_reservasi');
    }

    /**
     * Relasi ke Pembayaran
     */
    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'id_reservasi', 'id_reservasi');
    }

    /**
     * Relasi ke Layanan (INI YANG BIKIN ERROR DI HALAMAN LAPORAN!)
     * Menghubungkan Reservasi langsung ke tabel Layanan melalui tabel detail_reservasi
     */
    public function layanan()
    {
        return $this->belongsToMany(Layanan::class, 'tr_detail_reservasi', 'id_reservasi', 'id_layanan');
    }
}