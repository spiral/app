<?php

declare(strict_types=1);

namespace Tests\Module;

use App\Endpoint\Web\Middleware\LocaleSelector;
use Installer\Internal\Application\ApplicationInterface;
use Installer\Module\Translator\Package;
use Spiral\Bootloader\I18nBootloader;
use Spiral\Bootloader\Views\TranslatedCacheBootloader;

final class Translator extends AbstractModule
{
    public function __construct()
    {
        parent::__construct(new Package());
    }

    public function getBootloaders(ApplicationInterface $application): array
    {
        return [
            I18nBootloader::class,
            TranslatedCacheBootloader::class,
        ];
    }

    public function getEnvironmentVariables(ApplicationInterface $application): array
    {
        return [
            'LOCALE' => 'en',
        ];
    }

    public function getMiddleware(ApplicationInterface $application): array
    {
        return [
            'global' => [
                LocaleSelector::class
            ]
        ];
    }
}
