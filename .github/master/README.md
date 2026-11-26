<p align="center">
    <a href="https://spiral.dev"><img alt="Spiral Framework" src="https://user-images.githubusercontent.com/773481/228578125-8e0ad713-905f-4450-a473-5839bd650472.png" style="width: 6in; display: block"></a>
</p>

<p align="center">Spiral Framework Web application skeleton</p>

<div align="center">

[![Documentation](https://img.shields.io/badge/Documentation-blue?style=for-the-badge&logo=gitbook&logoColor=white)](https://spiral.dev/docs)
[![Sponsor](https://img.shields.io/static/v1?style=for-the-badge&label=&message=Sponsor&logo=githubsponsors&logoColor=white&color=%23EA4AAA)](https://github.com/sponsors/spiral)

</div>

<br />

A ready-to-run Web application on [RoadRunner](https://roadrunner.dev) with Cycle ORM, Stempler templates, queues, cache, mailer, events, translations and sample pages. The [installer](https://github.com/spiral/app/tree/installer) rebuilds this branch on each release.

To pick only the components you need, install the application with the installer instead:

```bash
composer create-project spiral/app my-app
```

## Get Started

The application needs PHP 8.2+ with the [mbstring](https://www.php.net/manual/en/book.mbstring.php) and [sockets](https://www.php.net/manual/en/book.sockets.php) extensions.

```bash
git clone https://github.com/spiral/app.git my-app
cd my-app
composer install
cp .env.sample .env
php app.php encrypt:key -m .env
php app.php configure
composer rr:download
composer serve:dev
```

Open http://127.0.0.1:8080 in the browser.

### Development and production

Both commands start RoadRunner and override the matching variables of `.env`:

| Command | Environment |
|---|---|
| `composer serve:dev` | `APP_ENV=local`, debug mode with detailed errors; the tokenizer, view and ORM schema caches are off, so code changes apply after a worker restart |
| `composer serve` | `APP_ENV=prod`, no debug mode, basic error verbosity; the tokenizer, view and ORM schema caches are on and cleared before the start |

To run the application in production without Composer, set the same variables in `.env`: `APP_ENV=prod`, `DEBUG=false`, `VERBOSITY_LEVEL=basic`, `TOKENIZER_CACHE_TARGETS=true`, `VIEW_CACHE=true`, `CYCLE_SCHEMA_CACHE=true`, `CYCLE_SCHEMA_WARMUP=true`. Run `php app.php cache:clean` after each deploy.
