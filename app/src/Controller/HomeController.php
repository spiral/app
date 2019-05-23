<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */
declare(strict_types=1);

namespace App\Controller;

use App\Job\Ping;
use Spiral\Jobs\QueueInterface;
use Spiral\Views\ViewsInterface;

class HomeController
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

    /**
     * @param QueueInterface $queue
     * @return string
     */
    public function ping(QueueInterface $queue): string
    {
        return $queue->push(new Ping([
            'value' => 'hello world'
        ]));
    }
}