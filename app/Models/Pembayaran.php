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

    // Mematikan fitur timestamps bawaan karena kita tidak membuat kolom created_at & updated_at di tabel ini
    public $timestamps = false; 

    // Mendaftarkan kolom-kolom yang diizinkan untuk diisi secara otomatis (Mass Assignment)
    protected $fillable = [
        'id_reservasi',
        'metode_pembayaran',
        'tanggal_bayar',
        'status_pembayaran',
        'bukti_pembayaran'
    ];

    /**
     * Relasi Balik (Inverse) ke Reservasi
     * Satu data pembayaran ini adalah milik satu data reservasi
     */
    public function reservasi()
    {
        return $this->belongsTo(Reservasi::class, 'id_reservasi', 'id_reservasi');
    }
}