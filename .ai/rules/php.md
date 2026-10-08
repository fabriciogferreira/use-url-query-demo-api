---
paths:
  - app/**
  - database/factories/**
  - tests/**
---

# PHP

## Import organization

Organize imports into the following groups and keep the groups in this exact order, separated by a blank line:

1. Enums
2. Models
3. Traits
4. Requests
5. Services
6. Factories
7. Libraries

Do not create empty import groups.

| Group | Directory |
| --- | --- |
| Enums | `app/Enums` |
| Models | `app/Models` |
| Traits | `app/Traits` |
| Requests | `app/Http/Requests` |
| Services | `app/Services` |
| Factories | `database/factories` |
| Libraries | Any other imported namespace |

Example:

```php
<?php

use App\Enums\ExampleEnum;

use App\Models\Example;

use App\Traits\ExampleTrait;

use App\Http\Requests\ExampleRequest;

use App\Services\ExampleService;

use Database\Factories\ExampleFactory;

use Illuminate\Http\Request;
use Some\External\Library;
```

## throw_if / throw_unless

Prefer `throw_if()` when an `if` statement only throws an exception. Prefer `throw_unless()` for the inverse condition. Do not use `if` statements when their only purpose is to throw and the condition can be expressed clearly with `throw_if()` or `throw_unless()`.
