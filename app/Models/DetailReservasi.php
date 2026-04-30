<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailReservasi extends Model
{
    use HasFactory;

    // 1. Nama tabel sesuai desain di database (XAMPP)
    protected $table = 'tr_detail_reservasi'; 

    // 2. Primary Key kustom (Bukan 'id')
    protected $primaryKey = 'id_detail'; 

    /**
     * 🚨 PENTING: Matikan timestamps karena tabel ini 
     * tidak memiliki kolom created_at dan updated_at.
     */
    public $timestamps = false;

    // 3. Kolom yang diizinkan untuk diisi secara massal
    protected $fillable = [
        'id_reservasi',
        'id_layanan',
        'harga',      // Harga saat transaksi (history)
        'jumlah',     // Jumlah sepatu per layanan
        'sub_total'   // Total per baris layanan (harga * jumlah)
    ];

    /**
     * Relasi ke tabel Reservasi (Induk)
     * Menghubungkan detail ini ke data reservasi utamanya
     */
    public function reservasi()
    {
        return $this->belongsTo(Reservasi::class, 'id_reservasi', 'id_reservasi'); 
    }

    /**
     * Relasi ke tabel Layanan
     * Mengambil info nama jasa (misal: Fast Clean) dari tabel ms_layanan
     */
    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'id_layanan', 'id_layanan');
    }
}