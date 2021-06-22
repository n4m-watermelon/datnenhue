<?php
return [
    'max_quota' => env('RV_MEDIA_MAX_QUOTA', 1024 * 1024 * 1024),
    'sizes' => [
        'thumb'     => '260x200',
        'featured'  => '420x260',
        'medium'    => '600x500'
    ],
    'permissions'             => [
        'folders.create',
        'folders.edit',
        'folders.trash',
        'folders.destroy',
        'files.create',
        'files.edit',
        'files.trash',
        'files.destroy',
        'files.favorite',
        'folders.favorite',
    ],
    'allow_external_services' => env('RV_MEDIA_ALLOW_EXTERNAL_SERVICES', false),
    'external_services'       => [
        'youtube',
        'vimeo',
        'dailymotion',
        'instagram',
        'vine',
    ],
    'default-img'   => env('RV_MEDIA_DEFAULT_IMAGE', 'templates/frontend/images/no_images.jpg'), // Default image
    'driver' => [
        'local' => [
            'root' => public_path('upload'),
            'path' => env('RV_MEDIA_UPLOAD_PATH', '/upload'),
        ]
    ],
    'mime_types'              => [
        'image'    => [
            'image/png',
            'image/jpeg',
            'image/gif',
            'image/bmp',
        ],
        'video'    => [
            'video/mp4',
        ],
        'document' => [
            'application/pdf',
            'application/vnd.ms-excel',
            'application/excel',
            'application/x-excel',
            'application/x-msexcel',
            'text/plain',
            'application/msword',
            'text/csv',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        ],
        'youtube'  => [
            'youtube',
        ],
    ],
    'max_file_size_upload'    => env('RV_MEDIA_MAX_FILE_SIZE_UPLOAD', 4 * 1024 * 1024), // Maximum size to upload
    'sidebar_display'         => env('RV_MEDIA_SIDEBAR_DISPLAY', 'horizontal'), // Use "vertical" or "horizontal"
    'watermark'               => [
        'source'   => env('RV_MEDIA_WATERMARK_SOURCE'),
        'position' => env('RV_MEDIA_WATERMARK_POSITION', 'bottom-right'),
        'x'        => env('RV_MEDIA_WATERMARK_X', 10),
        'y'        => env('RV_MEDIA_WATERMARK_Y', 10),
    ],
];
