<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Session Driver
    |--------------------------------------------------------------------------
    |
    | Saya sarankan ganti 'database' menjadi 'file'.
    | Dengan 'file', data login Mas akan disimpan di folder storage.
    | Mas tidak perlu membuat tabel 'sessions' di MySQL Workbench.
    |
    */

    'driver' => env('SESSION_DRIVER', 'file'),

    /*
    |--------------------------------------------------------------------------
    | Session Lifetime
    |--------------------------------------------------------------------------
    */

    'lifetime' => (int) env('SESSION_LIFETIME', 120),

    'expire_on_close' => env('SESSION_EXPIRE_ON_CLOSE', false),

    /* ... bagian encrypt dan files biarkan default ... */

    /*
    |--------------------------------------------------------------------------
    | Session Cookie Name
    |--------------------------------------------------------------------------
    |
    | Nama cookie yang akan tersimpan di browser pelanggan.
    |
    */

    'cookie' => env(
        'SESSION_COOKIE',
        Str::slug((string) env('APP_NAME', 'roff_shoeclean')).'-session'
    ),

    /* ... sisa konfigurasi ke bawah biarkan default ... */

];