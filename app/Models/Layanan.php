<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    protected $table = 'ms_layanan';
    protected $primaryKey = 'id_layanan';
    public $timestamps = false;
}