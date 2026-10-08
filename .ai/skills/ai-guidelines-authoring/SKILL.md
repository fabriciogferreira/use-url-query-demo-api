---
name: ai-guidelines-authoring
description: "Create, edit, or review Laravel Boost .ai content — guidelines, project rules, and agent skills. Use when adding or changing files under .ai/, choosing between guideline vs rule vs skill, splitting path-scoped rules, overriding Boost built-ins, or reviewing AI instruction quality."
---

# AI guidelines authoring

Follow `.ai/rules/ai.md` for hard constraints. This skill is the create/review workflow for Boost's `.ai` folder.

## When to use this skill

- Adding or editing `.ai/guidelines/**`, `.ai/rules/**`, or `.ai/skills/**`
- Deciding whether content is a guideline, rule, or skill
- Reviewing existing `.ai` files for drift, bloat, or wrong layer
- Overriding a built-in Boost guideline or skill

## Choose the layer

| Layer | Path | Loaded | Use for |
| --- | --- | --- | --- |
| Guideline | `.ai/guidelines/**` (`.blade.php` or `.md`) | Always (upfront) | Short, foundational context; Boost package overrides; Environment that must apply to every Artisan/test run |
| Skill | `.ai/skills/{name}/SKILL.md` | On demand | Detailed workflows, examples, checklists for one domain |
| Project rule | `.ai/rules/*.md` + `index.md` | When path globs match | How *this* application is written: conventions, traps, standing constraints |

Laravel ecosystem how-to → guideline/skill. Application-specific conventions → rules.

Do **not** use `.ai/rules` as the catalog of product/domain business rules when an external system is the source of truth. Keep a short pointer rule if agents must not invent domain policy; put the catalog in the external tool and in code.

## Language

Write all agent-facing `.ai` content in **English**. Avoid bilingual or Portuguese-only instructions so the agent does not spend context translating.

## Guidelines

1. Keep them concise and actionable. Prefer bullets over essays.
2. **Custom:** any path under `.ai/guidelines/` (e.g. `environment.md`).
3. **Override:** same relative path as Boost (e.g. `.ai/guidelines/boost/core.blade.php`, `deployments/core.blade.php`).
4. When overriding, start from the **current** `vendor/laravel/boost/.ai/...` file. Change only what this project needs (e.g. Artisan → Environment). Do not re-apply stale full-file forks.
5. Prefer `$assist->artisanCommand()` / `composerCommand()` helpers over hard-coded `php artisan` so Sail/custom binaries keep working.
6. In `.blade.php`, wrap Blade examples in `@verbatim` or escape with `@@`.
7. After changes: `php artisan boost:update` (via the project Environment guideline — Docker `workspace`).

## Skills

1. Folder: `.ai/skills/{skill-name}/SKILL.md` with YAML frontmatter `name` and `description`.
2. `description` must state **when** to activate the skill (triggers).
3. Include: when to use, steps, short examples, review checklist if useful.
4. Reference project rules for standing constraints; put the long workflow here so rules stay short.
5. Override a Boost skill by using the **same skill name**.
6. After changes: `boost:update` so agents (e.g. `.cursor/skills`) pick it up.

## Project rules

1. One concern per file when possible. **Split by path** so agents only load what the edited files need.
2. Frontmatter `paths:` with the narrowest accurate globs (e.g. `app/Http/Requests/**`, not all of `app/**` unless necessary).
3. If one convention spans two areas (e.g. factories + tests), either one file with both globs **or** separate files — prefer separate when the content for each path differs.
4. Keep notes to a few lines. Add a one-line **why** when the rule is non-obvious (e.g. ban `confirmed` because API docs generators cannot infer the confirmation field).
5. Prefer Boost `record-rule` so `index.md` stays in sync. If editing by hand, update `.ai/rules/index.md` in the same change.
6. Do not record what is already obvious from reading the code, or what Pint/Rector already enforce.
7. Commit `.ai/rules` (shared). Generated `AGENTS.md` / `.mcp.json` / `boost.json` may stay regeneratable.

## Review checklist

- [ ] English only in agent-facing text
- [ ] Correct layer (guideline vs skill vs rule)
- [ ] Rules: globs match where the instruction matters; no unrelated path bloat
- [ ] No duplicate of another `.ai` file (link/point instead)
- [ ] Overrides based on current Boost vendor, minimal diff
- [ ] No domain business catalog dumped into rules when an external SoT exists
- [ ] `boost:update` run after guideline/skill changes
- [ ] `index.md` updated if rules were added/renamed/removed by hand
