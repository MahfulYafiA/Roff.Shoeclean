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
     * ada $table->timestamps();
     */
    public $timestamps = true; 

    // ✅ UPDATE: Mendaftarkan kolom-kolom baru sesuai ERD Dosen
    protected $fillable = [
        'id_reservasi',
        'metode_bayar', // Nama baru
        'tanggal',      // Nama baru
        'jumlah'        // Kolom baru
    ];

    /**
     * ✅ UPDATE: Agar 'tanggal' bisa kita format dengan ->format('d M Y') di view,
     * kita tambahkan casting ke datetime.
     */
    protected $casts = [
        'tanggal' => 'datetime',
    ];

    /**
     * Relasi Balik (Inverse) ke Reservasi
     */
    public function reservasi()
    {
        return $this->belongsTo(Reservasi::class, 'id_reservasi', 'id_reservasi');
    }
}