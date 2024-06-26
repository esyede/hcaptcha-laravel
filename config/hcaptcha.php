<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Site Key
    |--------------------------------------------------------------------------
    |
    | This is your site key. By default, we use a test key that is for testing
    | purposes only and does not trigger a challenge.
    |
    */

    'sitekey' => env('HCAPTCHA_SITEKEY', '10000000-ffff-ffff-ffff-000000000001'),

    /*
    |--------------------------------------------------------------------------
    | Secret Key
    |--------------------------------------------------------------------------
    |
    | This is your site key. By default, we use a test key that is for testing
    | purposes only and does not trigger a challenge.
    |
    */

    'secret' => env('HCAPTCHA_SECRET', '0x0000000000000000000000000000000000000000'),

    /*
    |--------------------------------------------------------------------------
    | Custom Guzzle Request Options
    |--------------------------------------------------------------------------
    |
    | This is custom options for the guzzle http client's request.
    | Docs: https://docs.guzzlephp.org/en/stable/request-options.html
    |
    */

    'guzzle_options' => [
        // 'verify' =>  false,
    ],

];
