<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    use HasFactory;

    protected $table = 'ms_layanan';
    protected $primaryKey = 'id_layanan';
    
    /**
     * Sesuai migrasi, kita tidak menggunakan timestamps di ms_layanan
     */
    public $timestamps = false;

    // Agar fungsi create() dan update() di Controller lancar jaya
    protected $fillable = [
        'nama_layanan',
        'harga',
        'deskripsi'
    ];

    /**
     * Relasi ke Reservasi (Many-to-Many)
     * Memungkinkan Mas melihat daftar pesanan apa saja yang menggunakan layanan ini
     */
    public function reservasi()
    {
        return $this->belongsToMany(Reservasi::class, 'tr_detail_reservasi', 'id_layanan', 'id_reservasi')
                    ->withPivot('harga');
    }
}