---
paths:
  - app/Rules/**
---

# Validation Rules

## Class naming

Custom validation rule classes must end with the `Rule` suffix. `make:rule` does not append it — include `Rule` in the name.

Example: `php artisan make:rule UppercaseRule` → `app/Rules/UppercaseRule.php`
