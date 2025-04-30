<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Auth URL
    |--------------------------------------------------------------------------
    |
    | This URL is use to generate token.
    |
    */
    'authUrl' => 'https://prod-api.4portun.com/openapi/auth/token',

    /*
    |--------------------------------------------------------------------------
    | API Base URL
    |--------------------------------------------------------------------------
    |
    | This URL is the base endpoint used to communicate with the remote
    | ocean tracking API service. Ensure the URL is correct and accessible.
    |
    */
    'baseUrl' => 'https://prod-api.4portun.com/openapi/gateway',

    /*
    |--------------------------------------------------------------------------
    | APP ID
    |--------------------------------------------------------------------------
    |
    | This ID is used to authenticate the company making requests to the API.
    | It should be assigned by the API provider and kept consistent across uses.
    |
    */
    'app_id' => env('OCEAN_TRACKING_APP_ID','APP_ID'),

    /*
    |--------------------------------------------------------------------------
    | API Secret Key
    |--------------------------------------------------------------------------
    |
    | This secret key is used to authenticate API requests securely. It should
    | be kept confidential and not shared publicly or committed to source control.
    |
    */
    'secret' => env('OCEAN_TRACKING_APP_SECRET','APP_SECRET')

];