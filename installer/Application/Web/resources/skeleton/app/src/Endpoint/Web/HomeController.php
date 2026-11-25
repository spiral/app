<?php

declare(strict_types=1);

namespace App\Endpoint\Web;

use Spiral\Router\Annotation\Route;
use Spiral\Views\ViewsInterface;

/**
 * Simple home page controller. It renders home page template and also provides
 * an example of exception page.
 */
final class HomeController
{
    public function __construct(
        private readonly ViewsInterface $views,
    ) {}

    #[Route(route: '/', name: 'index')]
    public function index(): string
    {
        return $this->views->render('home');
    }

    /**
     * Example of exception page.
     */
    #[Route(route: '/exception', name: 'exception')]
    public function exception(): never
    {
        throw new \Exception('This is a test exception.');
    }
}
