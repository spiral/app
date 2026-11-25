# Spiral application installer

[**Documentation
**](https://spiral.dev/docs) | [Discord](https://discord.gg/V6EK4he) | [Twitter](https://twitter.com/spiralphp) | [Contributing](https://spiral.dev/docs/overview-contributing/)

![Installer](https://user-images.githubusercontent.com/773481/208850084-891a9d6f-3e70-4a06-af57-4e63c37c9c47.png)

### Hello developer!

Are you looking for an easier way to install [Spiral Framework](https://github.com/spiral/framework/)?

This package streamlines the installation process by allowing you to install only the desired components and packages 
for your application. Our installer handles all dependencies and configurations, making the process as smooth and 
hassle-free as possible.

This makes it super simple for you to get started with Spiral.

## Server Requirements

Make sure that your server is configured with following PHP version and extensions:

* PHP 8.2+, 64bit
* [mb-string](https://www.php.net/manual/en/intro.mbstring.php) extension

## Installation

```bash
composer create-project spiral/app my-app
```

After the project has been installed, open **README.md** file and follow the instructions.

### Installation without prompts

Pass the answers in the `SPIRAL_INSTALLER_ANSWERS` environment variable, e.g. in CI or a Dockerfile:

```bash
SPIRAL_INSTALLER_ANSWERS='{"application": "Web", "questions": {"CycleBridge": 1, "EventDispatcher": 1, "Validators": 0}}' \
  composer create-project spiral/app my-app --no-interaction
```

- `application` is the preset name: `Web`, `Cli` or `gRPC`.
- A question key is the class name of the question without the `Installer\Module\` (or `Installer\Application\`) prefix
  and the `\Question` suffix, with `/` instead of `\`: `CycleBridge`, `CycleBridge/CollectionsQuestion`,
  `RoadRunnerBridge/RoadRunnerMetrics`, `ApplicationSkeleton`. Keys are case-insensitive.
- An answer is the option number as shown in the prompt; `0` declines.
- Questions without an answer are asked as usual, or get their default answer with `--no-interaction`.

Good luck with your future project!

## License:

MIT License (MIT). Please see [`LICENSE`](./LICENSE) for more information. Maintained
by [Spiral Scout](https://spiralscout.com).
