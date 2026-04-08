<?php

use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogUdpHandler;
use Monolog\Processor\PsrLogMessageProcessor;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Log Channel
    |--------------------------------------------------------------------------
    |
    | Saya sarankan ubah dari 'stack' ke 'daily'. 
    | Dengan 'daily', Laravel akan membuat file log baru setiap harinya.
    | Contoh: laravel-2026-04-08.log
    |
    */

    'default' => env('LOG_CHANNEL', 'daily'),

    /* ... bagian lain biarkan ... */

    'channels' => [

        /* ... stack, single, dll ... */

        'daily' => [
            'driver' => 'daily',
            'path' => storage_path('logs/laravel.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => env('LOG_DAILY_DAYS', 14), // Simpan log selama 14 hari ke belakang
            'replace_placeholders' => true,
        ],

        /* ... sisanya biarkan default ... */
    ],

];