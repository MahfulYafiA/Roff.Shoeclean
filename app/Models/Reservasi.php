<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservasi extends Model
{
    use HasFactory;

    protected $table = 'tr_reservasi';
    protected $primaryKey = 'id_reservasi';

    // Update Fillable sesuai Migrasi (Tambahkan status_pengambilan)
    protected $fillable = [
        'id_user',
        'tanggal_reservasi',
        'jumlah_sepatu',
        'metode_layanan',
        'alamat_jemput',
        'metode_pengembalian',
        'status_pengambilan', // 🚨 Pastikan ini ada agar tidak error saat update status
        'status',
        'total_harga',
        'wa_pengantaran',      
        'alamat_pengantaran'   
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
     * Diubah ke hasMany karena satu nota bisa banyak baris detail
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
     * SOLUSI ERROR LAPORAN:
     * Menambahkan withPivot('harga') agar kita bisa mengambil harga 
     * yang tersimpan di tabel detail saat transaksi terjadi.
     */
    public function layanan()
    {
        return $this->belongsToMany(Layanan::class, 'tr_detail_reservasi', 'id_reservasi', 'id_layanan')
                    ->withPivot('harga', 'id_detail'); // Membawa data harga dari tabel tengah
    }
}