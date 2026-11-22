# Environment

This project runs in Docker. Run Artisan, tests, php-cs-fixer, and other `vendor/bin/*` tooling inside the `workspace` service — never on the host. This overrides Laravel Boost's default of running those commands on the host.

Compose file: `compose.dev.yaml`. Service: `workspace`. Container name: `use-url-query-demo-api-workspace`.

```bash
docker compose -f compose.dev.yaml exec workspace php artisan test
```

```bash
docker container exec use-url-query-demo-api-workspace php artisan test
```

```bash
docker compose -f compose.dev.yaml exec workspace \
  vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.dist.php path/to/ChangedFile.php
```

Do **not** use Laravel Pint (`vendor/bin/pint`). Formatting is php-cs-fixer only (see `.php-cs-fixer.dist.php`). php-cs-fixer has no `--dirty` flag — pass the paths you changed.

The workspace image installs **Xdebug** (debug) and **PCOV** (coverage). For `--coverage` / `--mutate`, always disable Xdebug so Pest uses PCOV (~2.8× faster line coverage — [PCOV or Xdebug?](https://phpunit.expert/articles/pcov-or-xdebug.html)):

```bash
docker compose -f compose.dev.yaml exec -e XDEBUG_MODE=off workspace \
  php artisan test --compact --coverage
```
