<?php

use Aslnbxrz\FileManager\Models\Files;

return [

    /*
    |--------------------------------------------------------------------------
    | Image Driver
    |--------------------------------------------------------------------------
    |
    | Intervention Image supports "GD Library" and "Imagick" to process images
    | internally. You may choose one of them according to your PHP
    | configuration. By default, PHP's "GD Library" implementation is used.
    |
    | Supported: "gd", "imagick"
    |
    */

    'driver' => 'gd',

    'thumbs' => [
        'icon' => [
            'w' => 50,
            'h' => 50,
            'q' => 80,
            'slug' => 'icon'
        ],
        'small' => [
            'w' => 320,
            'h' => 240,
            'q' => 70,
            'slug' => 'small'
        ],
        'low' => [
            'w' => 640,
            'h' => 480,
            'q' => 70,
            'slug' => 'low'
        ],
        'normal' => [
            'w' => 1024,
            'h' => 728,
            'q' => 70,
            'slug' => 'normal'
        ]
    ],
    'images_ext' => explode(",", Files::IMG_EXT),

];
