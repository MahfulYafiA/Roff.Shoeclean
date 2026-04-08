<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    // Menentukan nama tabel yang digunakan di database
    protected $table = 'tr_pembayaran';

    // Menentukan primary key
    protected $primaryKey = 'id_pembayaran';

    /**
     * Kita aktifkan timestamps karena di file migrasi tr_pembayaran 
     * sebelumnya Mas sudah menambahkan $table->timestamps();
     */
    public $timestamps = true; 

    // Mendaftarkan kolom-kolom yang diizinkan untuk diisi
    protected $fillable = [
        'id_reservasi',
        'metode_pembayaran',
        'tanggal_bayar',
        'status_pembayaran',
        'bukti_pembayaran'
    ];

    /**
     * Agar tanggal_bayar bisa kita format dengan ->format('d M Y') di view,
     * kita tambahkan casting ke datetime.
     */
    protected $casts = [
        'tanggal_bayar' => 'datetime',
    ];

    /**
     * Relasi Balik (Inverse) ke Reservasi
     */
    public function reservasi()
    {
        return $this->belongsTo(Reservasi::class, 'id_reservasi', 'id_reservasi');
    }
}