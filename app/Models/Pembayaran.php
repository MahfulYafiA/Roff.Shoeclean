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
     * 🚨 UPDATE PENTING:
     * Kita set ke false karena tabel 'tr_pembayaran' di database Mas 
     * belum punya kolom created_at dan updated_at.
     */
    public $timestamps = false; 

    // ✅ Kolom yang boleh diisi mass-assignment
    protected $fillable = [
        'id_reservasi',
        'metode_bayar',
        'tanggal',
        'jumlah'
    ];

    /**
     * Casting kolom 'tanggal' agar bisa diformat sebagai waktu di View
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