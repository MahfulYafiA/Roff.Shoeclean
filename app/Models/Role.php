<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    // Karena nama tabel Anda ms_role (bukan roles), harus didefinisikan manual
    protected $table = 'ms_role';
    
    // Primary key Anda adalah id_role
    protected $primaryKey = 'id_role';

    // Biarkan kosong jika tidak ingin menggunakan created_at/updated_at di tabel role
    public $timestamps = false;
}