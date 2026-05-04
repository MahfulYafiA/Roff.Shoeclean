<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'ms_user';
    protected $primaryKey = 'id_user';

    public $incrementing = true;
    protected $keyType = 'int';

    public $timestamps = true; 

    protected $fillable = [
        'nama',
        'email',
        'password',
        'role',       
        'status',     
        'no_telp',
        'alamat',
        'foto_profil',
        'google_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime', 
            'password' => 'hashed',            
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}