<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */
declare(strict_types=1);

namespace App\Job;

use Spiral\Core\ResolverInterface;
use Spiral\Jobs\AbstractJob;

class PingJob extends AbstractJob
{
    /**
     * @param string                 $value
     * @param ResolverInterface|null $resolver
     */
    public function __construct(string $value, ResolverInterface $resolver = null)
    {
        parent::__construct(compact('value'), $resolver);
    }

    /**
     * @param string $id
     * @param string $value
     */
    public function do(string $id, string $value)
    {
        // do something
        error_log("pong by {$id}, value `{$value}`");
    }
}