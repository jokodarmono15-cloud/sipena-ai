<?php

return [
    'ai' => [
        'provider' => env('AI_PROVIDER', 'openrouter'),
        'api_key' => env('AI_API_KEY'),
        'model' => env('AI_MODEL', 'auto'),
        'auto_fallback' => env('AI_AUTO_FALLBACK', true),
    ],

    'vector_db' => [
        'type' => env('VECTOR_DB_TYPE', 'qdrant'),
        'host' => env('VECTOR_DB_HOST', 'localhost'),
        'port' => env('VECTOR_DB_PORT', 6333),
        'api_key' => env('VECTOR_DB_API_KEY'),
    ],

    'providers' => [
        'openrouter' => [
            'base_url' => 'https://openrouter.io/api/v1',
            'key' => env('AI_API_KEY'),
        ],
        'openai' => [
            'base_url' => 'https://api.openai.com/v1',
            'key' => env('OPENAI_API_KEY'),
        ],
        'gemini' => [
            'base_url' => 'https://generativelanguage.googleapis.com/v1beta',
            'key' => env('GEMINI_API_KEY'),
        ],
        'groq' => [
            'base_url' => 'https://api.groq.com/openai/v1',
            'key' => env('GROQ_API_KEY'),
        ],
    ],
];
