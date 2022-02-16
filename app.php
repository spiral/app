<?php

/**
 * This file is part of Spiral package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

use App\App;

//
// If you forgot to configure some of this in your php.ini file,
// then don't worry, we will set the standard environment
// settings for you.
//

mb_internal_encoding('UTF-8');
error_reporting(E_ALL | E_STRICT ^ E_DEPRECATED);
ini_set('display_errors', 'stderr');

//
// Register Composer's auto loader.
//
require __DIR__ . '/vendor/autoload.php';


//
// Initialize shared container, bindings, directories and etc.
//
$app = App::init(['root' => __DIR__]);

if ($app === null) {
    exit(255);
}

$code = (int)$app->serve();
exit($code);
