# boilerplate-laravel

Boilerplate to development in Laravel with PostgreSQL, PHPStan, php-cs-fixer, Laravel Boost, Sanctum and Pest. This boilerplate is focused to work with Cursor or VS Code.

## Starting a new project from this repository

After cloning or forking, rename every `boilerplate-laravel` occurrence to your project slug (example: `nome-do-seu-projeto`). Prefer kebab-case for Docker container names.

### 1. Project identity

- [ ] Update this README title and description for the new project
- [ ] Set `APP_NAME` in `.env`, `.env.example`, and `.env.testing`
- [ ] Set `DB_DATABASE` to the same slug as `APP_NAME` (username stays `root`) in `.env` and `.env.example`
- [ ] Set `.env.testing` `DB_DATABASE` to `{slug}_testing` (separate DB so tests do not wipe local data)
- [ ] Match Postgres init vars in Compose: `POSTGRES_DB` / defaults in `compose.dev.yaml` and `compose.prod.yaml`
- [ ] Point `git remote` at your repository (remove or replace the boilerplate remote)

### 2. Docker container names

Replace the `boilerplate-laravel-*` prefixes in:

- [ ] `compose.dev.yaml` (`web`, `php-fpm`, `workspace`, `postgres`, `redis`)
- [ ] `compose.prod.yaml` (`web`, `php-fpm`, `postgres`, `redis`)

Example: `boilerplate-laravel-workspace` → `nome-do-seu-projeto-workspace`

### 3. Agent / environment docs

Keep agent instructions aligned with the new container name:

- [ ] `.ai/guidelines/environment.md` — update the `workspace` container name and any `docker container exec …` examples
- [ ] Run `php artisan boost:update` inside the workspace container after guideline changes

### 4. First boot

```bash
cp .env.example .env
docker compose -f compose.dev.yaml up -d --build
docker compose -f compose.dev.yaml exec workspace php artisan key:generate
docker compose -f compose.dev.yaml exec workspace php artisan migrate
```

Quick check that nothing was left behind:

```bash
rg 'boilerplate-laravel' --glob '!.git/**'
```
