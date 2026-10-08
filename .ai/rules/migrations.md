---
paths:
  - database/migrations/**
---

# Migrations

## Primary keys

Use a simple `id` primary key on all tables, including many-to-many pivots. Do not use composite primary keys.

## Enum columns

Do not use native MySQL/PostgreSQL `ENUM` types. Map PHP backed enums to `string` or integer columns matching the backing type. When enum cases change, update the PHP enum only — never alter a database `ENUM` type.

Do not import or call `App\Enums` from migrations. Do not add CHECKs that freeze allowed enum values in the database.
