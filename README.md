<p align="center">
    <a href="https://spiral.dev"><img alt="Spiral Installer" src="https://user-images.githubusercontent.com/773481/208850084-891a9d6f-3e70-4a06-af57-4e63c37c9c47.png" style="width: 6in; display: block"></a>
</p>


<div align="center">

[![Documentation](https://img.shields.io/badge/Documentation-blue?style=for-the-badge&logo=gitbook&logoColor=white)](https://spiral.dev/docs/start-installation)
[![Discord](https://img.shields.io/discord/538114875570913290?style=for-the-badge&logo=discord&labelColor=7289d9&logoColor=white&color=39456d)](https://discord.gg/spiralphp)
[![Sponsor](https://img.shields.io/static/v1?style=for-the-badge&label=&message=Sponsor&logo=githubsponsors&logoColor=white&color=%23EA4AAA)](https://github.com/sponsors/spiral)

</div>

<br />

The installer creates a [Spiral Framework](https://github.com/spiral/framework) application with only the components you pick: Web, CLI or gRPC application, Cycle ORM, template engine, queues, cache, mailer, Temporal and others. It requires the packages and writes their configs, bootloaders and environment variables. Applications on RoadRunner also get the RoadRunner binary.

## Get Started

### Installation

```bash
composer create-project spiral/app my-app
```

[![PHP](https://img.shields.io/packagist/php-v/spiral/app.svg?style=flat-square&logo=php)](https://packagist.org/packages/spiral/app)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/spiral/app.svg?style=flat-square&logo=packagist)](https://packagist.org/packages/spiral/app)
[![License](https://img.shields.io/packagist/l/spiral/app.svg?style=flat-square)](LICENSE)
[![Total Downloads](https://img.shields.io/packagist/dt/spiral/app.svg?style=flat-square)](https://packagist.org/packages/spiral/app/stats)

The application needs the [mbstring](https://www.php.net/manual/en/book.mbstring.php) extension, and the [sockets](https://www.php.net/manual/en/book.sockets.php) extension when it runs on RoadRunner.

When the installation completes, open `README.md` of the new project: it describes the next steps for the installed components.

### Installation without prompts

Pass the answers in the `SPIRAL_INSTALLER_ANSWERS` environment variable, e.g. in CI or a Dockerfile:

```bash
SPIRAL_INSTALLER_ANSWERS='{"application": "Web", "questions": {"CycleBridge": 1, "EventDispatcher": 1, "Validators": 0}}' \
  composer create-project spiral/app my-app --no-interaction
```

- `application` is the preset name: `Web`, `Cli` or `gRPC`.
- A question key is the class name of the question without the `Installer\Module\` (or `Installer\Application\`) prefix and the `\Question` suffix, with `/` instead of `\`: `CycleBridge`, `CycleBridge/CollectionsQuestion`, `RoadRunnerBridge/RoadRunnerMetrics`, `ApplicationSkeleton`. Keys are case-insensitive.
- An answer is the option number as shown in the prompt; `0` declines.
- Questions without an answer are asked as usual, or get their default answer with `--no-interaction`.

## Ready-made application

The [`master`](https://github.com/spiral/app/tree/master) branch holds a Web application installed from the latest release with the answers in [`.github/master-preset.json`](https://github.com/spiral/app/blob/installer/.github/master-preset.json). Each release rebuilds it.
