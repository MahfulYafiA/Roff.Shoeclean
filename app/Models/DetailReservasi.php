<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailReservasi extends Model
{
    use HasFactory;

    // 1. Nama tabel sesuai desain Workbench
    protected $table = 'tr_detail_reservasi'; 

    // 2. Primary Key kustom
    protected $primaryKey = 'id_detail'; 

    // 3. Matikan timestamps karena tidak ada di migrasi tabel ini
    public $timestamps = false;

    // 4. Mencegah error Mass Assignment
    protected $fillable = [
        'id_reservasi',
        'id_layanan',
        'harga' // Penting untuk mengunci harga history
    ];

    /**
     * Relasi ke tabel Reservasi
     * Menghubungkan detail ini ke nota pesanan induknya
     */
    public function reservasi()
    {
        return $this->belongsTo(Reservasi::class, 'id_reservasi', 'id_reservasi'); 
    }

    /**
     * Relasi ke tabel Layanan
     * Memungkinkan kita mengambil info jasa (misal: Deep Clean) dari baris detail ini
     */
    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'id_layanan', 'id_layanan');
    }
}