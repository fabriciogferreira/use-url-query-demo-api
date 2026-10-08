---
paths:
  - tests/Architecture/**
---

# Architecture

## Keep arch tests in sync

Architecture tests enforce what they currently assert: layer placement (expected namespaces), base classes (e.g. Form Requests extend `BaseRequest`), naming suffixes, enums using `Enumerable`, and migrations not importing `App\Enums`. Domain-folder layout and other conventions in `.ai/rules/requests.md`, `.ai/rules/enums.md`, and `.ai/rules/tests.md` are project rules — update matching architecture tests in the same change only when those rules become (or already are) arch-enforced.
