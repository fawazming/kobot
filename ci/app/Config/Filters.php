<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\SecureHeaders;

class Filters extends BaseConfig
{
    public $aliases = [
        'csrf'          => CSRF::class,
        'toolbar'       => DebugToolbar::class,
        'honeypot'      => Honeypot::class,
        'invalidchars'  => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
        'apiauth'       => \App\Filters\ApiAuth::class,
        'adminauth'     => \App\Filters\AdminAuth::class,
        // 'cors'          => \App\Filters\Cors::class,
    ];

    public $globals = [
        'before' => [
            // 'cors',
        ],
        'after' => [
            'toolbar',
        ],
    ];

    public $methods = [];

    public $filters = [
        'adminauth' => ['before' => ['admin/*']],
    ];
}
