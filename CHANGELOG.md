# Changelog

## [4.0.0](https://github.com/spiral/app/compare/3.9.0...4.0.0) (2026-11-25)


### ⚠ BREAKING CHANGES

* **grpc:** grpc:generate needs buf, protoc and protoc-gen-php-grpc from dload.xml instead of rr download-protoc-binary; binaryPath in app/config/grpc.php is replaced by bufPath, protocPath and pluginPath.
* move to RoadRunner 3 and spiral/roadrunner-bridge 5
generated applications need RoadRunner 3 and use spiral/roadrunner-bridge 5 with the roadrunner/* packages (roadrunner/cli, roadrunner/grpc) instead of spiral/roadrunner-*.

### Features

* add the `composer serve` script ([030d871](https://github.com/spiral/app/commit/030d87130992fa83f4567d852a85a446f625d03f))
* answer the installer questions from SPIRAL_INSTALLER_ANSWERS ([030d871](https://github.com/spiral/app/commit/030d87130992fa83f4567d852a85a446f625d03f))
* download RoadRunner with DLoad: dload.xml lists rr ^3.0 and the installer runs dload get instead of rr get-binary ([aac4cb3](https://github.com/spiral/app/commit/aac4cb3e1e2ac1610443e9e99164098d43de57a5))
* **grpc:** generate gRPC code with buf ([aac4cb3](https://github.com/spiral/app/commit/aac4cb3e1e2ac1610443e9e99164098d43de57a5))
* install llm/skills with .agents/skills and .claude/skills ([aac4cb3](https://github.com/spiral/app/commit/aac4cb3e1e2ac1610443e9e99164098d43de57a5))
* move to RoadRunner 3 and spiral/roadrunner-bridge 5 ([aac4cb3](https://github.com/spiral/app/commit/aac4cb3e1e2ac1610443e9e99164098d43de57a5))
* offer Temporal again with spiral/temporal-bridge 3.4 ([aac4cb3](https://github.com/spiral/app/commit/aac4cb3e1e2ac1610443e9e99164098d43de57a5))
* ship Stempler error pages and the spiralVersion and phpVersion directives ([030d871](https://github.com/spiral/app/commit/030d87130992fa83f4567d852a85a446f625d03f))
* support PHP 8.2 to 8.5 ([aac4cb3](https://github.com/spiral/app/commit/aac4cb3e1e2ac1610443e9e99164098d43de57a5))


### Bug Fixes

* **grpc:** call services through spiral/grpc-client ([aac4cb3](https://github.com/spiral/app/commit/aac4cb3e1e2ac1610443e9e99164098d43de57a5))
* **grpc:** point RoadRunner at the proto file of the gRPC skeleton ([aac4cb3](https://github.com/spiral/app/commit/aac4cb3e1e2ac1610443e9e99164098d43de57a5))
* hide development hints in the error pages in production ([030d871](https://github.com/spiral/app/commit/030d87130992fa83f4567d852a85a446f625d03f))
* inject ViewsInterface into HomeController and type UuidTypecast values for Psalm 7.20 ([aac4cb3](https://github.com/spiral/app/commit/aac4cb3e1e2ac1610443e9e99164098d43de57a5))
* set the application description and sort the packages in composer.json ([030d871](https://github.com/spiral/app/commit/030d87130992fa83f4567d852a85a446f625d03f))


### Dependencies

* bump Spiral to 3.18, cycle-bridge to 2.13, doctrine/collections to 3, illuminate/collections to 13 and Psalm to 7 ([aac4cb3](https://github.com/spiral/app/commit/aac4cb3e1e2ac1610443e9e99164098d43de57a5))
