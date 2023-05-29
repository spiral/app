<?php

return [
    'locale' => env('LOCALE', 'en'),
    'fallbackLocale' => env('LOCALE', 'en'),
    'directory' => directory('locale'),
    'autoRegister' => env('DEBUG', true),
];
