# Spiral HTTP Application Skeleton [![Latest Stable Version](https://poser.pugx.org/spiral/app/version)](https://packagist.org/packages/spiral/app)

Spiral Framework is a High-Performance PHP Full-Stack framework and group of over sixty PSR-compatible components.

![Github cover spiral application](https://user-images.githubusercontent.com/773481/208879494-75df40ec-483c-4efb-b621-fed863927e96.jpg)

[**Documentation**](https://spiral.dev/docs) | [Discord](https://discord.gg/TFeEmCs) | [Twitter](https://twitter.com/spiralphp) | [Contributing](https://spiral.dev/docs/about-contributing/)

### Hello developer!

This branch is a ready-to-run Web application: RoadRunner, Cycle ORM, Stempler, queues, cache, mailer,
events and translations, with the sample pages. It is built automatically by the
[installer](https://github.com/spiral/app/tree/installer) on each release.

To pick only the components you need, use the installer:

```bash
composer create-project spiral/app my-app
```

## Server Requirements

* PHP 8.2+, 64bit
* [mb-string](https://www.php.net/manual/en/intro.mbstring.php) and [sockets](https://www.php.net/manual/en/intro.sockets.php) extensions

## Running this application

```bash
git clone https://github.com/spiral/app.git my-app
cd my-app
composer install
cp .env.sample .env
php app.php encrypt:key -m .env
php app.php configure
composer rr:download
composer serve
```

Open http://127.0.0.1:8080 in the browser.

Good luck with your future project!

## License:

MIT License (MIT). Please see [`LICENSE`](./LICENSE) for more information. Maintained
by [Spiral Scout](https://spiralscout.com).
