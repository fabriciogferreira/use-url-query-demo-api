{{-- Problem: Boost ships Pint guidelines; this project formats with php-cs-fixer only. --}}
# PHP CS Fixer (not Pint)

This project does **not** use Laravel Pint. Do not install or run `vendor/bin/pint`.

- Follow the project Environment guideline when running formatters (Docker `workspace` service — never on the host).
- Canonical style is `.php-cs-fixer.dist.php` (2-space indent, opening braces on the same line). The editor uses junstyle.php-cs-fixer via `.vscode/php-cs-fixer`.
- If you have modified any PHP files, run php-cs-fixer on those paths (there is no `--dirty` flag):

```bash
docker compose -f compose.dev.yaml exec workspace \
  vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.dist.php path/to/ChangedFile.php
```

Or several paths:

```bash
docker container exec use-url-query-demo-api-workspace \
  vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.dist.php app/Http/Controllers app/Services
```

- Do not use Pint presets or create `pint.json`.
