<?php
// config/documents.php

return [

    /*
    |--------------------------------------------------------------------------
    | Document Validation Settings
    |--------------------------------------------------------------------------
    */

    'validation' => [
        // Enable/disable validation
        'enabled' => env('DOCUMENT_VALIDATION_ENABLED', false),

        // Validation mode: basic, mock, strict
        // basic = hanya cek format & ukuran (development)
        // mock = simulasi AI (testing)
        // strict = full OCR + AI (production)
        'mode' => env('DOCUMENT_VALIDATION_MODE', 'basic'),

        // Confidence threshold percentage
        'confidence_threshold' => [
            'ktp' => 40,
            'sim' => 30,
            'passport' => 35,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Document Encryption
    |--------------------------------------------------------------------------
    */

    'encryption' => [
        'enabled' => env('DOCUMENT_ENCRYPTION_ENABLED', true),
        'cipher' => 'AES-256-CBC',
    ],

    /*
    |--------------------------------------------------------------------------
    | Storage Settings
    |--------------------------------------------------------------------------
    */

    'storage' => [
        'disk' => 'local', // JANGAN pakai 'public'
        'path' => 'secure_documents',
    ],

    /*
    |--------------------------------------------------------------------------
    | File Validation Rules
    |--------------------------------------------------------------------------
    */

    'allowed_mimes' => ['image/jpeg', 'image/png', 'image/jpg'],
    'max_size' => 2048, // KB (2MB)
    'min_dimensions' => [
        'width' => 200,
        'height' => 200,
    ],

];
