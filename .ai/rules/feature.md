---
paths:
  - tests/Feature/**
---

# Feature tests

## Describe order

Feature tests group cases in five Pest `describe` blocks, always in this order:

1. Authentication — unauthenticated access (401 or public)
2. Authorization — 403 and authorization-before-validation
3. Validation — 422
4. Behavior — persistence, business exceptions, notifications, and other side effects
5. Response — happy-path status and JSON body

Do not interleave cases across layers. Omit a `describe` only when that layer has no cases. Place FeatureTest helpers inside the matching `describe`.

Example:

```php
describe('Authentication', function () { /* ... */ });
describe('Authorization', function () { /* ... */ });
describe('Validation', function () { /* ... */ });
describe('Behavior', function () { /* ... */ });
describe('Response', function () { /* ... */ });
```

## Validation coverage

The Validation `describe` must cover every Form Request field and every rule on those fields through the HTTP endpoint (422). Each rule is its own case (`required`, type, `min`, `max`, `unique`, `in`, `same`, and any other constraint). Do not sample one field or one rule and stop. Omit Validation only when the endpoint has no validation rules.
