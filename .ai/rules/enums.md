---
paths:
  - app/Enums/**
---

# Enums

## When to use enums

Use enums when a domain concept has a finite, well-defined set of standardized values and a separate reference table is not required.

## Enumerable trait

All backed enums must use the `App\Traits\Enumerable` trait.

## Case and value naming

Enum case names must use `UPPER_SNAKE_CASE` (not Boost/PHP default TitleCase). Backed enum values must use lowercase `snake_case`.

Example: `case PENDING_REVIEW = 'pending_review';`

## Directory layout

Store enums under `app/Enums/{Domain}/`, named after their domain. Do not leave enum classes in the `app/Enums` root.

Example: `app/Enums/Order/OrderStatusEnum.php`

## Class naming

- `{Domain}{Column}Enum` when the enum represents a specific column of the domain model (e.g. `OrderStatusEnum`).
- `{Domain}Enum` when the enum represents the domain itself rather than a specific column (e.g. `OrderEnum`).
