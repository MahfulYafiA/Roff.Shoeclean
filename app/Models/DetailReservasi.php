<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailReservasi extends Model
{
    use HasFactory;

    // 1. Sesuaikan nama tabel dengan ERD & Migration (tr_detail_reservasi)
    protected $table = 'tr_detail_reservasi'; 

    // 2. Samakan Primary Key-nya
    protected $primaryKey = 'id_detail'; 

    // 3. Kita matikan timestamps karena di migration detail tidak ada created_at
    public $timestamps = false;

    // 4. Kolom yang boleh diisi (Hanya id_reservasi, id_layanan, dan harga)
    protected $fillable = [
        'id_reservasi',
        'id_layanan',
        'harga' // Kita simpan harga di sini untuk record history
    ];

    /**
     * Relasi ke tabel Reservasi (Satu detail ini milik satu reservasi)
     */
    public function reservasi()
    {
        return $this->belongsTo(Reservasi::class, 'id_reservasi', 'id_reservasi'); 
    }

    /**
     * Relasi ke tabel Layanan (Wajib ada agar bisa panggil nama layanan)
     */
    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'id_layanan', 'id_layanan');
    }
}