<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Đây là disk mặc định mà Laravel sẽ sử dụng. Bạn có thể thay đổi bằng cách
    | chỉnh biến môi trường FILESYSTEM_DISK trong file .env.
    |
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Bạn có thể cấu hình nhiều "disks" khác nhau. Mỗi disk có driver riêng
    | như local, s3, ftp... Dưới đây là ví dụ cho local và public.
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => rtrim(env('APP_URL', 'http://localhost'), '/').'/storage',
            'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Đây là cấu hình cho lệnh `php artisan storage:link`. Khi chạy lệnh này,
    | Laravel sẽ tạo symbolic link từ public/storage sang storage/app/public.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
