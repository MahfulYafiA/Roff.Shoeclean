<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    use HasFactory;

    protected $table = 'ms_layanan';
    protected $primaryKey = 'id_layanan';
    
    public $incrementing = true;
    protected $keyType = 'int';
    
    public $timestamps = false;

    protected $fillable = [
        'nama_layanan',
        'harga',
        'deskripsi',
        'status',
        'gambar', 
    ];

    protected $casts = [
        'harga' => 'integer',
    ];

    public function reservasi()
    {
        return $this->belongsToMany(Reservasi::class, 'tr_detail_reservasi', 'id_layanan', 'id_reservasi')
                    ->withPivot('id_detail', 'harga', 'jumlah', 'sub_total');
    }
}