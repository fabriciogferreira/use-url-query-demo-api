---
paths:
  - database/factories/**
---

# Factories

## No nested creates

Factory `definition()` (and states) must not `create()` or otherwise persist related models. Define attributes and relationship fields declaratively; create related models explicitly in the test when needed.
