# use-url-query-demo-api

Demo Laravel API for the `use-url-query` playground (PostgreSQL, PHPStan, php-cs-fixer, Laravel Boost, Sanctum, Pest, Spatie Query Builder). Forked from `boilerplate-laravel`; identity slug is `use-url-query-demo-api`.

## Starting a new project from this repository

After cloning or forking, rename every `use-url-query-demo-api` occurrence to your project slug (example: `nome-do-seu-projeto`). Prefer kebab-case for Docker container names.

### 1. Project identity

- [ ] Update this README title and description for the new project
- [ ] Set `APP_NAME` in `.env`, `.env.example`, and `.env.testing`
- [ ] Set `DB_DATABASE` to the same slug as `APP_NAME` (username stays `root`) in `.env` and `.env.example`
- [ ] Keep `.env.testing` aligned with `.env` (same drivers: `SESSION_DRIVER=database`, `CACHE_STORE=database`, `QUEUE_CONNECTION=database`, etc.). Intentional deltas only: `APP_ENV=testing`, `DB_DATABASE={slug}_testing` (or `{local_db}_testing`), and `BCRYPT_ROUNDS=4` for suite speed. Tests load `.env.testing` via `Tests\TestCase` (Compose `env_file` would otherwise keep `.env` values).
- [ ] Match Postgres init vars in Compose: `POSTGRES_DB` / defaults in `compose.dev.yaml` and `compose.prod.yaml`
- [ ] Point `git remote` at your repository (remove or replace the boilerplate remote)

### 2. Docker container names

Replace the `use-url-query-demo-api-*` prefixes in:

- [ ] `compose.dev.yaml` (`web`, `php-fpm`, `workspace`, `postgres`, `redis`)
- [ ] `compose.prod.yaml` (`web`, `php-fpm`, `postgres`, `redis`)
- [ ] `.vscode/php-cs-fixer` — `CONTAINER=` must match the Compose `workspace` `container_name` (junstyle runs Fixer via `docker exec` on that container; leaving the boilerplate name after a rename → `No such container`)
- [ ] `.vscode/settings.json` — `phpstan.dockerContainerName` (same `*-workspace` name)

Example: `use-url-query-demo-api-workspace` → `nome-do-seu-projeto-workspace`

### 3. Example `users.status` (keep or remove)

The boilerplate ships a sample `users.status` column (`active` / `inactive`) plus `App\Enums\User\UserStatusEnum` (and factory `inactive()`). It exists so PHPStan can analyse `App\Traits\Enumerable`: without at least one enum using the trait, level 10 reports `trait.unused` and the ship gate fails. Prefer a real domain enum over a PHPStan `ignoreErrors` entry.

When forking, the agent must decide explicitly:

- [ ] **Keep** the sample if the new project needs user active/inactive status as-is (or rename/extend it to the real domain), **or remove** it if status is not part of the product — delete `UserStatusEnum`, drop `status` from the users migration / `User` model / `UserFactory`, and add the first real backed enum under `app/Enums/{Domain}/` that uses `Enumerable` before relying on PHPStan again

### 4. Agent / environment docs

Keep agent instructions aligned with the new container name:

- [ ] `.ai/guidelines/environment.md` — update the `workspace` container name and any `docker container exec …` examples
- [ ] Run `php artisan boost:update` inside the workspace container after guideline changes

### 5. First boot

```bash
cp .env.example .env
docker compose -f compose.dev.yaml up -d --build
docker compose -f compose.dev.yaml exec workspace php artisan key:generate
docker compose -f compose.dev.yaml exec workspace php artisan migrate
```

The `workspace` image includes **Xdebug** and **PCOV**. Day-to-day debug uses Xdebug; **coverage/mutation always use PCOV** via `XDEBUG_MODE=off` (see `.ai/guidelines/environment.md`).

Quick check that nothing was left behind:

```bash
rg 'use-url-query-demo-api' --glob '!.git/**'
```
