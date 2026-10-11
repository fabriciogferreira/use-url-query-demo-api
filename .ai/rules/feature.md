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

## Failure rolls back prior work

When an operation can fail after it has already mutated state or queued side effects, Behavior must assert that the failure leaves no partial result: database writes from that request are rolled back or undone, and mail/jobs that must not survive the failure are not left sent or pushed.

Cover each failure path that can interrupt a multi-step write (business exception, downstream failure, etc.). Assert the error response and the absence of the intermediate changes — not only the final status code.

## Closed side effects

Keep Feature tests as closed as possible: every case for a route must assert the side effects that route is allowed to produce — including that it produces none when it never should.

Today this applies only to mail and queued jobs. Database tables, HTTP requests, and other subjects may join later; do not invent those checks until they are documented here.

### Mail

If the route never sends mail on any path (success, validation failure, auth failure, etc.), every test for that route must fake mail and assert that nothing was sent (e.g. `Mail::fake()` + `Mail::assertNothingSent()`).

If the route sends mail on some paths, assert the expected mailable(s) on those paths, and assert that nothing was sent on every path that must not send mail.

### Queued jobs

If the route never dispatches a job on any path, every test for that route must fake the queue and assert that nothing was pushed (e.g. `Queue::fake()` + `Queue::assertNothingPushed()`).

If the route dispatches jobs on some paths, assert the expected job class(es) on those paths, and assert that nothing was pushed on every path that must not queue work.

Place the fake before the HTTP call in each test that needs it. Prefer asserting the absence of side effects in Authentication, Authorization, and Validation cases as well — not only in Behavior.
