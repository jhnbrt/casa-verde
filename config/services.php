<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Resend, Postmark, AWS, and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    // AI chat assistant. Keys go in the .env file (never in the admin).
    // AI_PROVIDER = gemini | openai | anthropic. Leave it empty to use whichever key is present.
    // With no key the chat still works from the quick answers written in the admin.
    'ai_provider' => env('AI_PROVIDER'),

    'gemini' => [
        'key' => env('GEMINI_API_KEY'),
        // A model that has a free tier; see the models list in Google AI Studio.
        'model' => env('GEMINI_MODEL', 'gemini-2.5-flash-lite'),
    ],

    'openai' => [
        'key' => env('OPENAI_API_KEY'),
        // A current low-cost model from OpenAI's model list, for example gpt-6-luna.
        'model' => env('OPENAI_MODEL', 'gpt-6-luna'),
        // For models that think before answering (such as gpt-6-luna): none keeps answers fast and cheap.
        // Leave empty for models that do not accept this setting.
        'reasoning_effort' => env('OPENAI_REASONING_EFFORT'),
    ],

    'anthropic' => [
        'key' => env('ANTHROPIC_API_KEY'),
        'model' => env('ANTHROPIC_MODEL', 'claude-haiku-5-5'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

];
