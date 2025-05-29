<?php

return [
    'http' => [
        'host' => env('SWOOLE_HTTP_HOST', '0.0.0.0'),
        'port' => env('SWOOLE_HTTP_PORT', '1215'),
        'public_path' => base_path('public'),
        'options' => [
            'worker_num' => env('SWOOLE_HTTP_WORKER_NUM', 4),
            'task_worker_num' => env('SWOOLE_HTTP_TASK_WORKER_NUM', 4),
            'reactor_num' => env('SWOOLE_HTTP_REACTOR_NUM', 2),
            'enable_static_handler' => true,
            'document_root' => base_path('public'),
            'package_max_length' => 20 * 1024 * 1024,
            'buffer_output_size' => 32 * 1024 * 1024,
            'socket_buffer_size' => 128 * 1024 * 1024,
        ],
    ],
    'hot_reload' => [
        'enabled' => env('SWOOLE_HOT_RELOAD_ENABLE', false),
        'recursively' => env('SWOOLE_HOT_RELOAD_RECURSIVELY', true),
        'directory' => env('SWOOLE_HOT_RELOAD_DIRECTORY', base_path()),
        'files' => [],
    ],
    'providers' => [
        // App\Providers\AuthServiceProvider::class,
    ],
]; 