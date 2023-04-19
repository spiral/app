<?php

declare(strict_types=1);

namespace App\Endpoint\Web;

use Exception;
use Spiral\Prototype\Traits\PrototypeTrait;
use Spiral\Router\Annotation\Route;

final class HomeController
{
    /**
     * Read more about Prototyping:.
     *
     * @link https://spiral.dev/docs/basics-prototype
     */
    use PrototypeTrait;

    #[Route(route: '/', name: 'home')]
    public function index(): string
    {
        return $this->views->render('home.dark.php');
    }

    /**
     * Example of exception page.
     */
    #[Route(route: '/exception', name: 'exception')]
    public function exception(): never
    {
        throw new Exception('This is a test exception.');
    }
}
