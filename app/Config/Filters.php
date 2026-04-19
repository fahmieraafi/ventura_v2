<?php

namespace Config;

use CodeIgniter\Config\Filters as BaseFilters;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\SecureHeaders;
use CodeIgniter\Filters\PerformanceMetrics;

class Filters extends BaseFilters
{
    public array $aliases = [
        'csrf'          => CSRF::class,
        'toolbar'       => DebugToolbar::class,
        'honeypot'      => Honeypot::class,
        'invalidchars'  => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
        'performance'   => PerformanceMetrics::class,
        'auth'          => \App\Filters\AuthFilter::class,
        'role'          => \App\Filters\RoleFilter::class,
    ];

    public array $required = [
        'before' => [],
        'after'  => [
            'performance',
            'toolbar',
        ],
    ];

    public array $globals = [
        'before' => [
            /**
             * PERBAIKAN:
             * Kita tambahkan 'proses-login' dan 'users/store' ke daftar except 
             * agar kamu bisa login dan daftar user tanpa error 403.
             */
            'csrf' => [
                'except' => [
                    'proses-login',             // WAJIB: agar bisa login
                    'barang/store',             // Agar bisa simpan barang
                    'barang/update/*',          // Agar bisa edit barang
                    'barang/hapusFotoSatuan',   // Agar bisa hapus foto via AJAX
                    'users/store',              // Agar bisa tambah user baru
                    'users/update/*',
                    'gunung/tambah',
                    'gunung/update/*',      // Agar bisa edit user
                ]
            ],
        ],
        'after' => [],
    ];

    public array $methods = [];

    public array $filters = [
        'auth' => [
            'before' => [
                'barang',
                'barang/*',
                'dashboard',
                'dashboard/*',
                'users',
                // PERBAIKAN: Mengunci halaman spesifik saja, agar /create & /store bisa diakses publik
                'users/index',
                'users/edit/*',
                'users/update/*',
                'users/delete/*',
                'gunung',
                'gunung/*',

            ]
        ],
    ];
}
