### Download or update gRPC code generation tools

The `grpc:generate` command compiles proto files with [buf](https://buf.build). It needs the `buf`, `protoc` and
`protoc-gen-php-grpc` binaries, which are listed in `dload.xml` and downloaded with
[DLoad](https://github.com/php-internal/dload):

```bash
./vendor/bin/dload get
```
