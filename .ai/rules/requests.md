---
paths:
  - app/Http/Requests/**
---

# Form Requests

## Directory and naming

Organize Form Requests by domain:

```text
app/Http/Requests/
├── {DomainName}/
│   ├── {DomainName}{RouteRelatedFunctionName}Request.php
│   └── ...
└── ...
```

Example: `app/Http/Requests/Order/OrderStoreRequest.php`

## Base class

Every Form Request must extend `App\Http\Requests\Common\BaseRequest`.

## Enum `in:` rules

When using `in:` validation rules with enum values, use the enum's `toInValidation()` method instead of listing the values.

## Confirmed rule

Do not use Laravel's `confirmed` validation rule — API docs generators cannot infer rules for the referenced confirmation field. Prefer explicit rules on both fields instead.

## Payload-dependent authorization

When authorization depends on request payload data, implement it in `authorize()`. Do not leave that check for the Controller or Service.

Do not override `authorize()` when the Policy or `BaseRequest` already covers the operation. Do not reimplement Policy logic in the Form Request — delegate with `can()` (or equivalent).

For the create/edit workflow, activate the `form-request-development` skill.
