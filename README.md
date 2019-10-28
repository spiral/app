# Spiral HTTP Application Skeleton [![Latest Stable Version](https://poser.pugx.org/spiral/app/version)](https://packagist.org/packages/spiral/app)

<img src="https://user-images.githubusercontent.com/796136/67560465-9d827780-f723-11e9-91ac-9b2fafb027f2.png" height="135px" alt="Spiral Framework" align="left"/>

Spiral Framework makes developing in PHP exciting again. It optimizes on the power of PHP to quickly develop business logic while uniquely leveraging Golang to craft an elegant infrastructure layer with native support for HTTP/2, GRPC, Queue, and more. Build faster, more efficient applications with this flexible and PSR compliant PHP7 framework (and have fun while you’re at it). 

[App Skeleton](https://github.com/spiral/app) ([CLI](https://github.com/spiral/app-cli), [GRPC](https://github.com/spiral/app-grpc)) | [**Documentation**](https://spiral.dev/docs) | [Twitter](https://twitter.com/spiralphp) | [CHANGELOG](/CHANGELOG.md) | [Contributing](https://github.com/spiral/guide/blob/master/contributing.md)

<br/>

Server Requirements
--------
Make sure that your server is configured with following PHP version and extensions:
* PHP 7.2+, 64bit
* *mb-string* extension
* PDO Extension with desired database drivers

Application Bundle
--------
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

Installation
--------
```
composer create-project spiral/app
```

> Application server will be downloaded automatically (`php-curl` and `php-zip` required).

Once the application is installed you can ensure that it was configured properly by executing:

```
$ php ./app.php configure
```

To start application server execute:

```
$ ./spiral serve -v -d
```

On Windows:

```$xslt
$ spiral.exe serve -v -d
```

Application will be available on `http://localhost:8080`.

> Read more about application server configuration [here](https://roadrunner.dev/docs).

License:
--------
MIT License (MIT). Please see [`LICENSE`](./LICENSE) for more information. Maintained by [Spiral Scout](https://spiralscout.com).
