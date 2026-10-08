---
paths:
  - app/Http/Controllers/**
---

# Controllers

These conventions override `laravel-best-practices` `architecture.md`, `routing.md`, and `validation.md` when they conflict.

## Form Requests required

Validated endpoints must use a Form Request. Do not use inline `$request->validate()`. This overrides `laravel-best-practices` `validation.md`, which still allows inline validation for small rule sets.

## Authorization

Do not duplicate Form Request `authorize()` checks in the Controller. Do not defer payload-dependent authorization to the Service.

## Thin controllers

Controllers coordinate the HTTP layer only. Inject Services via the constructor (no `app()` / manual `new`). Do not introduce Action classes — delegate to Services. Do not duplicate Service logic. Pass the authenticated user id into the Service when scoping is needed.

## authorizeResource

For user-owned `apiResource` controllers, call `authorizeResource` with `except: ['index', 'store']`. Do not add `viewAny`/`create` policy methods that always return true only to satisfy `authorizeResource`.

## HTTP responses

Return `response([...], SymfonyResponse::HTTP_*)` with an explicit status: `HTTP_OK` for index/show/update, `HTTP_CREATED` for store, `HTTP_NO_CONTENT` for destroy.

## Validated data

Assign `$validatedData = $request->validated()` before the Service call; do not inline `$request->validated()` in the call.
