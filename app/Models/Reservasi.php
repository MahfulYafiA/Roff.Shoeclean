<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservasi extends Model
{
    use HasFactory;

    protected $table = 'tr_reservasi';
    protected $primaryKey = 'id_reservasi';

    /**
     * ✅ UPDATE: Menyesuaikan nama kolom alamat_lengkap
     * Menghapus alamat_jemput dan menggantinya dengan alamat_lengkap.
     */
    protected $fillable = [
        'id_user',
        'tanggal_reservasi',
        'metode_layanan',
        'alamat_lengkap',  // ✨ SUDAH DIUPDATE DISINI
        'status',
        'status_bayar',
        'total_harga',
        'metode_masuk',    // Antar Sendiri / Jemput Kurir
        'metode_keluar'    // Ambil Sendiri / Antar Kurir
    ];

    /**
     * Relasi ke User (Pemilik Reservasi)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    /**
     * Relasi ke Detail Reservasi (Banyak Item/Jasa)
     */
    public function detail()
    {
        return $this->hasMany(DetailReservasi::class, 'id_reservasi', 'id_reservasi');
    }

    /**
     * Relasi ke Pembayaran
     */
    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'id_reservasi', 'id_reservasi');
    }

    /**
     * Relasi ke Layanan (Pivot)
     */
    public function layanan()
    {
        return $this->belongsToMany(Layanan::class, 'tr_detail_reservasi', 'id_reservasi', 'id_layanan')
                    ->withPivot('id_detail', 'harga', 'jumlah', 'sub_total');
    }
}