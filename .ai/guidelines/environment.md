# Environment

This project runs in Docker. Run Artisan, tests, Pint, and other `vendor/bin/*` tooling inside the `workspace` service — never on the host. This overrides Laravel Boost's default of running those commands on the host.

Compose file: `compose.dev.yaml`. Service: `workspace`. Container name: `boilerplate-laravel-workspace`.

```bash
docker compose -f compose.dev.yaml exec workspace php artisan test
```

```bash
docker container exec boilerplate-laravel-workspace php artisan test
```

```bash
docker compose -f compose.dev.yaml exec workspace vendor/bin/pint --dirty --format agent
```
