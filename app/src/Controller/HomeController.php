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
        return $this->views->render('home.dark.php');
    }

    /**
     * Example of exception page.
     */
    public function exception()
    {
        echo $undefined;
    }

    /**
     * @param QueueInterface $queue
     * @return string
     */
    public function ping(QueueInterface $queue): string
    {
        return sprintf("Job ID: %s", $queue->push(new Ping([
            'value' => 'hello world'
        ])));
    }
}