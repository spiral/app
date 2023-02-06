# My super :app_name application

Was created by [spiral/installer](https://github.com/spiral/installer) with `:app_name` skeleton at **:date**.

:next_steps

## Usage

if you need to start RoadRunner server after the project has been configured use the following command:

```bash
./rr serve
```

> **Note**:
> Read more about the RoadRunner CLI commands in the [documentation](https://roadrunner.dev/docs/app-server-cli/2.x/en).

<br />

After starting the RoadRunner server with HTTP plugin, you will be able to access your application in a web
browser by going to a specific URL: http://127.0.0.1:8080.

## Console commands

### Download or update RoadRunner

Allows to install the latest version of the RoadRunner compatible with your environment (operating system, processor
architecture, runtime, etc...).

```bash
composer rr:download
# or
./vendor/bin/rr get-binary
```

### Download or update protoc-gen GRPC plugin

Allows to install the latest version of the `protoc-gen-php-grpc` file compatible with your environment (operating
system, processor architecture, runtime, etc...).

```bash
composer rr:download-protoc
# or
./vendor/bin/rr download-protoc-binary
```

## Useful resources

- [**Spiral Framework documentation**](https://spiral.dev/docs)
- [**RoadRunner documentation**](https://roadrunner.dev/docs)
- [Community packages](https://github.com/spiral-packages).
- [Birddog](https://github.com/roadrunner-server/birddog) — OpenSource tool for monitoring RoadRunner instances.
- [Support us](https://github.com/sponsors/roadrunner-server)
- [Contributing](https://spiral.dev/docs/about-contributing/)

## Support

If you have any questions or need help with the project, please don't hesitate to reach out! You can find us on Discord
at the following link:

[Discord Server](https://discord.gg/TFeEmCs)

Alternatively, you can create an issue on GitHub to report a bug or request a feature:

[Create an Issue on GitHub](https://github.com/spiral/framework/issues/new/choose)

We welcome any feedback or suggestions you may have, and are always happy to help troubleshoot any issues you may
encounter.
