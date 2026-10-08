---
paths:
  - .ai/**
---

# AI instructions (`.ai`)

## Language

Write all agent-facing content under `.ai/` in English.

## Layering

- Guidelines (`.ai/guidelines/**`) — short, always-on or Boost overrides
- Skills (`.ai/skills/**`) — on-demand workflows
- Rules (`.ai/rules/**`) — path-scoped application conventions; keep `index.md` in sync

Do not put product/domain business-rule catalogs in `.ai/rules` when an external system is the source of truth.

## Authoring workflow

When creating or reviewing `.ai` content, activate the `ai-guidelines-authoring` skill.
