---
paths:
  - tests/Architecture/**
---

# Architecture

## Keep arch tests in sync

Architecture tests enforce what they currently assert: layer placement (expected namespaces), base classes (e.g. Form Requests extend `BaseRequest`), naming suffixes, enums using `Enumerable`, migrations not importing `App\Enums`, Pest `php`/`security` presets, Services without HTTP/`auth`/`request` concerns, Controllers limited to resource public methods, and Exceptions implementing `Throwable`. Domain-folder layout and other conventions in `.ai/rules/requests.md`, `.ai/rules/enums.md`, and `.ai/rules/tests.md` are project rules — update matching architecture tests in the same change only when those rules become (or already are) arch-enforced.
