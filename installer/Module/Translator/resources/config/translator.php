<?php

/**
 * Translator component configuration.
 *
 * @link https://spiral.dev/docs/advanced-i18n#configuration
 */
return [
    'locale' => env('LOCALE', 'en'),
    'fallbackLocale' => env('LOCALE', 'en'),
    'directory' => directory('locale'),
    'autoRegister' => env('DEBUG', true),
];
