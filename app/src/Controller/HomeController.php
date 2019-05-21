<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */
declare(strict_types=1);

namespace App\Controller;

use Spiral\Core\Container\SingletonInterface;
use Spiral\Views\ViewsInterface;

class HomeController implements SingletonInterface
{
    /** @var ViewsInterface */
    private $views;

    /**
     * @param ViewsInterface $views
     */
    public function __construct(ViewsInterface $views)
    {
        $this->views = $views;
    }

    /**
     * @return string
     */
    public function index(): string
    {
        return $this->views->render('home');
    }
}