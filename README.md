# Spiral Application installer

[App Skeleton](https://github.com/spiral/app) | [**Documentation**](https://spiral.dev/docs) | [Discord](https://discord.gg/TFeEmCs) | [Twitter](https://twitter.com/spiralphp) | [Contributing](https://spiral.dev/docs/about-contributing/)

![Installer](https://user-images.githubusercontent.com/773481/208850084-891a9d6f-3e70-4a06-af57-4e63c37c9c47.png)

The package has been developed for the [Spiral Framework](https://github.com/spiral/framework/), providing a convenient command line interface for installing and configuring the framework and any desired packages. This makes it simple for developers to get started with Spiral.

<br />

## Server Requirements

Make sure that your server is configured with following PHP version and extensions:

* PHP 8.1+, 64bit
* [mb-string](https://www.php.net/manual/en/intro.mbstring.php) extension

## Installation

```bash
composer create-project spiral/installer my-app
```

> **Note**:
> Application server will be downloaded automatically (`php-curl` and `php-zip` required).

<br />

After the project has been created and installed, start RoadRunner server using the following command:

```bash
cd my-app

./rr serve
```

<br />

Once you have started RoadRunner server, your application will be accessible in your web browser
at  `http://localhost:8080`.

> **Note**:
> Read more about application server configuration [here](https://roadrunner.dev/docs).

## License:

MIT License (MIT). Please see [`LICENSE`](./LICENSE) for more information. Maintained
by [Spiral Scout](https://spiralscout.com).
