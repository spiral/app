<p align="center">
    <a href="https://spiral.dev"><img alt="Spiral Framework" src="https://user-images.githubusercontent.com/773481/228578125-8e0ad713-905f-4450-a473-5839bd650472.png" style="width: 6in; display: block"></a>
</p>

<p align="center">Spiral Framework Web application skeleton</p>

<div align="center">

[![Documentation](https://img.shields.io/badge/Documentation-blue?style=for-the-badge&logo=gitbook&logoColor=white)](https://spiral.dev/docs)
[![Discord](https://img.shields.io/discord/538114875570913290?style=for-the-badge&logo=discord&labelColor=7289d9&logoColor=white&color=39456d)](https://discord.gg/spiralphp)
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

### Development

```bash
composer serve:dev
```

- `APP_ENV=local` and debug mode: errors come with details and a stack trace.
- The tokenizer, view and ORM schema caches are off: code changes apply after a worker restart, without clearing caches.
- [Buggregator Trap](https://github.com/buggregator/trap) starts next to the application and receives what it sends.

Open the Trap web UI at http://127.0.0.1:5007; the [Buggregator plugin](https://github.com/buggregator/phpstorm-plugin) for PhpStorm connects to the same port. The command points the application at Trap:

| What | Sent to Trap by |
|---|---|
| `dump()` and `trap()` | `VAR_DUMPER_FORMAT=server`, `VAR_DUMPER_SERVER=127.0.0.1:9912` |
| Logs of the default channel | `MONOLOG_DEFAULT_CHANNEL=remote`, `MONOLOG_REMOTE_URL=127.0.0.1:9913` |
| Emails | `MAILER_DSN=smtp://127.0.0.1:1025` |
| Sentry events | `SENTRY_DSN=http://sentry@127.0.0.1:8000/1`, with [spiral/sentry-bridge](https://github.com/spiral/sentry-bridge) installed |
| OpenTelemetry traces, logs and metrics | `OTEL_EXPORTER_OTLP_ENDPOINT=http://127.0.0.1:4318`, `OTEL_EXPORTER_OTLP_PROTOCOL=http/json`, with [spiral/otel-bridge](https://github.com/spiral/otel-bridge) installed |

### Production

```bash
composer serve
```

- `APP_ENV=prod`, no debug mode and basic error verbosity: errors come without details.
- The tokenizer, view and ORM schema caches are on; the command clears them before the start.

Both commands override the matching variables of `.env`. To run the application in production without Composer, set them in `.env`:

```dotenv
APP_ENV=prod
DEBUG=false
VERBOSITY_LEVEL=basic
TOKENIZER_CACHE_TARGETS=true
VIEW_CACHE=true
CYCLE_SCHEMA_CACHE=true
CYCLE_SCHEMA_WARMUP=true
```

Clear the caches after each deploy:

```bash
php app.php cache:clean
```
