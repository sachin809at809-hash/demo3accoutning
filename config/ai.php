<?php

return [

    /*
    |--------------------------------------------------------------------------
    | AI Assistant Provider and Key Configs
    |--------------------------------------------------------------------------
    |
    | Supported providers: "gemini", "groq"
    |
    */

    'provider' => env('AI_PROVIDER', 'gemini'),

    'gemini' => [
        'api_key' => env('GEMINI_API_KEY'),
        'model' => env('GEMINI_MODEL', 'gemini-2.5-flash'),
    ],

    'groq' => [
        'api_key' => env('GROQ_API_KEY'),
        'model' => env('GROQ_MODEL', 'llama-3.2-11b-vision-preview'),
    ],

];
