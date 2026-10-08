---
paths:
  - app/Services/**
---

# Services

Services hold application and domain logic that must not live in Controllers. Do not introduce Action classes — this application uses Services. This overrides `laravel-best-practices` `architecture.md`.

- Each Service should have a cohesive responsibility.
- Services may call other Services when the delegated responsibility belongs to the called Service.
- Do not duplicate logic already owned by another Service; delegate instead.
- Inject Service dependencies through the constructor or method parameters; do not instantiate them manually.
- Services must not contain HTTP-specific concerns: Request objects, HTTP responses, route handling, or `auth()`.
- When a Service must scope work to the authenticated user, receive the user id as a method argument from the Controller. Do not call `auth()`, `Auth`, or `request()` inside a Service.
- Keep orchestration in the calling Service and responsibility-specific logic in the Service that owns that responsibility.
