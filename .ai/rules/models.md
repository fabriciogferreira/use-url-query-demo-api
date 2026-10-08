---
paths:
  - app/Models/**
---

# Models

## Mass assignment and serialization

Declare mass assignment with `#[Fillable([...])]`, hidden attributes with `#[Hidden([...])]`, and bind factories with `#[UseFactory(...)]` plus `HasFactory`. Do not add `$fillable`, `$hidden`, or `$factory` properties on new models. This overrides Laravel property defaults and `laravel-best-practices` `eloquent.md` when they conflict.

## Enum casts

Cast enum-backed attributes to the PHP enum class (e.g. `'status' => OrderStatusEnum::class`). Do not leave them as raw strings or integers in application code.
