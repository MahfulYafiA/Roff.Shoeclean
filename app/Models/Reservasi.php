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
     * ✅ UPDATE: Mendukung 4 Kombinasi Logistik
     * Menambahkan metode_masuk & metode_keluar sesuai migrasi terbaru.
     */
    protected $fillable = [
        'id_user',
        'tanggal_reservasi',
        'metode_layanan',
        'alamat_jemput',
        'status',
        'status_bayar',
        'total_harga',
        'wa_pengantaran',      
        'alamat_pengantaran',
        'metode_masuk',  // 🚨 BARU: Antar Sendiri / Jemput Kurir
        'metode_keluar'  // 🚨 BARU: Ambil Sendiri / Antar Kurir
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