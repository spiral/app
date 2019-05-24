# Spiral Application Skeleton
[![Latest Stable Version](https://poser.pugx.org/spiral/framework/version)](https://packagist.org/packages/spiral/framework)
[![Build Status](https://travis-ci.org/spiral/framework.svg?branch=master)](https://travis-ci.org/spiral/framework)
[![Codecov](https://codecov.io/gh/spiral/framework/graph/badge.svg)](https://codecov.io/gh/spiral/framework)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/spiral/framework/badges/quality-score.png)](https://scrutinizer-ci.com/g/spiral/framework/?branch=master)

<img src="https://raw.githubusercontent.com/spiral/guide/master/resources/logo.png" height="120px" alt="Spiral Framework" align="left"/>

Spiral is an open-source (MIT) micro-framework core that uses a set of open-source components and [an application server](https://github.com/spiral/roadrunner) which is designed to rapidly develop (RAD) high-performance PHP-applications that come with native support for HTTP/2, [GRPC](https://grpc.io/), distributed computations ([Queue](https://github.com/spiral/jobs)) and Golang extensions. 

[Website](https://spiral-framework.com) | <b>[App Skeleton](https://github.com/spiral/app)</b> ([cli](https://github.com/spiral/app-cli), [grpc](https://github.com/spiral/app-grpc)) | [Documentation (v1.0.0)](https://github.com/spiral/guide) | [Twitter](https://twitter.com/spiralphp) | [CHANGELOG](/CHANGELOG.md) | [Contributing](https://github.com/spiral/guide/blob/master/contributing.md)
<br/>

Server Requirements
--------
Make sure that your server is configured with following PHP version and extensions:
* PHP 7.1+
* mb-string Extension
* PDO Extension with desired database drivers

Installation
--------
```
composer create-project spiral/app
```

> Application server will be downloaded automatically.

Once application is installed you can ensure that it was configured properly by executing:

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

Read more about application server configuration [here](https://roadrunner.dev/docs).

License:
--------
MIT License (MIT). Please see [`LICENSE`](./LICENSE) for more information. Maintained by [Spiral Scout](https://spiralscout.com).
