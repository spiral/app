### Download or update gRPC code generation tools

The `grpc:generate` command compiles proto files with [buf](https://buf.build). It needs the `buf`, `protoc` and
`protoc-gen-php-grpc` binaries, which are listed in `dload.xml` next to RoadRunner and downloaded with it:

```bash
./vendor/bin/dload get
```

Keep the `protoc` major in `dload.xml` in line with the installed `google/protobuf` package (5.36 → protoc 36.x).
