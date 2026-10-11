---
paths:
  - tests/**
---

# Tests

This project rule overrides the `testing-best-practices` skill for test layout, file naming, and mocking. That skill still applies for Pest syntax and generic Laravel test design.

## Layout

Do not use `tests/Unit` or mirror `app/` paths under `tests/`. Place tests in:

| Layer | Directory | File name |
| --- | --- | --- |
| HTTP | `tests/Feature/{Domain}/` | `{Domain}{Action}Test.php` |
| Service | `tests/Service/{Domain}/` | `{Domain}Service{Action}Test.php` |
| Policy | `tests/Policy/{Domain}/` | `{Domain}{Action}PolicyTest.php` |
| Form Request | `tests/Request/{Domain}/` | `{Domain}{Action}RequestTest.php` |
| Exception | `tests/Exception/` | `{Exception}Test.php` |
| Architecture | `tests/Architecture/` | `{Layer}Test.php` |

Examples: `tests/Feature/Order/OrderStoreTest.php`, `tests/Service/Order/OrderServiceStoreTest.php`, `tests/Exception/BusinessRuleExceptionTest.php`.

HTTP Feature tests should use a shared base (e.g. `Tests\Feature\FeatureTest` or a domain subclass) and shared helpers for authentication, status, JSON, and persistence when those helpers exist.

For Feature `describe` order and Validation coverage, follow `.ai/rules/feature.md`.

## What each layer tests

- Tests must verify behavior rather than implementation details whenever possible.
- Prefer testing observable outcomes over verifying internal method calls.
- Feature tests verify the HTTP contract: authentication, status, response body, and persisted state. Do not mock Policy, Form Request, or Service at this boundary.
- When a write can fail mid-way, tests must assert rollback of prior work from that request (no partial persistence or surviving side effects). See `.ai/rules/feature.md`.
- Policy tests own the authorization matrix. Feature Validation owns the HTTP validation matrix (see `.ai/rules/feature.md`). Request tests may still assert the Form Request in isolation; do not skip Feature cases because a Request test exists.
- Instance Feature tests (show, update, destroy) must call `assertAuthorizationRequired`. Update and destroy must also call `assertAuthorizationRunsBeforeValidation` with one invalid payload so a non-owner receives 403, not 422.
- Service tests verify the behavior of the Service under test. When one Service calls another, test the called Service in its own tests. In the calling Service's tests, verify the delegation only when that interaction is part of the calling Service's contract. Multi-step writes that can fail mid-way must assert the same rollback of prior work as Feature Behavior.
- Isolated tests may verify interactions with dependencies when the interaction itself is part of the unit's contract.
- Do not duplicate the same behavioral test across multiple layers unless each layer provides a distinct contract that needs independent verification.
- Architecture tests enforce directory conventions. They are not behavioral tests.
- Exception tests verify how application exceptions behave (status, payload, debug output). They are not domain Feature tests.

## Existing tests

Never delete an existing test unless the behavior covered by the test has intentionally been removed or the test is being replaced by a more appropriate test.
