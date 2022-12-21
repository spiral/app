# Spiral HTTP Application Skeleton [![Latest Stable Version](https://poser.pugx.org/spiral/app/version)](https://packagist.org/packages/spiral/app)

![Github cover spiral application](https://user-images.githubusercontent.com/773481/208879494-75df40ec-483c-4efb-b621-fed863927e96.jpg)

[App installer](https://github.com/spiral/installer) | [**Documentation**](https://spiral.dev/docs) | [Discord](https://discord.gg/TFeEmCs) | [Twitter](https://twitter.com/spiralphp) | [Contributing](https://spiral.dev/docs/about-contributing/)

Spiral Framework is a High-Performance PHP/Go Full-Stack framework and group of over sixty PSR-compatible components. The Framework execution model based on a hybrid runtime where some services (GRPC, Queue, WebSockets, etc.) handled by Application Server [RoadRunner](https://github.com/spiral/roadrunner) and the PHP code of your application stays in memory permanently (anti-memory leak tools included).

<br/>

## Server Requirements

Make sure that your server is configured with following PHP version and extensions:
* PHP 8.1+, 64bit
* *mb-string* extension
* PDO Extension with desired database drivers

## Application Bundle

Application bundle includes the following components:
* High-performance HTTP, HTTP/2 server based on [RoadRunner](https://roadrunner.dev)
* Console commands via Symfony/Console
* Translation support by Symfony/Translation
* Queue support for AMQP, Beanstalk, Amazon SQS, in-Memory
* Stempler template engine
* Security, validation, filter models
* PSR-7 HTTP pipeline, session, encrypted cookies
* DBAL and migrations support
* Monolog, Dotenv
* Prometheus metrics
* [Cycle DataMapper ORM](https://github.com/cycle)

## Installation

```bash
composer create-project spiral/app
```

> Application server will be downloaded automatically (`php-curl` and `php-zip` required).

Once the application is installed you can ensure that it was configured properly by executing:

```bash
php ./app.php configure
```

To start application server execute:

```bash
./rr serve
```

Application will be available on `http://localhost:8080`.

> Read more about application server configuration [here](https://roadrunner.dev/docs).

## Testing:

To test an application:

```bash
./vendor/bin/phpunit
```

## Cloning:

Make sure to properly configure project if you cloned the existing repository.

```bash
copy .env.sample .env
php app.php encrypt:key -m .env
php app.php configure -vv
./vendor/bin/rr get-binary
```

## License:

MIT License (MIT). Please see [`LICENSE`](./LICENSE) for more information. Maintained by [Spiral Scout](https://spiralscout.com).
