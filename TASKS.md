# Tasks

Problema: Feature tests já fecham mail e jobs (ver `.ai/rules/feature.md`), mas ainda não há código reutilizável para inspecionar HTTP outbound, queries SQL e mudanças no banco — nem a documentação desses asserts. Este arquivo lista o trabalho para isso.

## Closed side effects — request, queries, database

- [ ] **HTTP requests (outbound)** — helper/fake que registra `Http::` (ou client do app) e permite assertar “nada saiu” ou a lista esperada de URLs/métodos; documentar em `.ai/rules/feature.md` no mesmo padrão de mail/jobs.
- [ ] **Queries** — helper que conta/lista queries durante o act (ex. `DB::listen` / query log) e asserta teto ou conjunto esperado; cobrir N+1 (e opcionalmente `Model::preventLazyLoading()` em `testing`); documentar quando o Feature test deve falhar por lazy load vs por contagem.
- [ ] **Database** — helper que registra inserts/updates/deletes (ou diff de estado) da request sob teste e permite assertar “nenhuma escrita” ou tabelas/linhas esperadas; alinhar com *Failure rolls back prior work* (falha mid-write sem estado parcial).
- [ ] **Integrar nos Feature tests** — usar os helpers em Authentication / Authorization / Validation / Behavior / Response quando a rota não deve disparar aquele side effect; exemplos mínimos num teste de referência.
- [ ] **Atualizar rules** — em `.ai/rules/feature.md` (e ponte em `tests.md`), tirar o aviso “só mail/jobs” e documentar request, queries e database como subjects fechados.

## Registry `tables` (Model::class → id)

Problema: o front manda nomes curtos de model (`user`, `category`, `Store`); o back precisa resolver isso para FQCN de Eloquent de forma estável, consultável e com id inteiro — sem espalhar strings soltas nem depender só de convenção de nome.

### Schema proposto

Tabela `tables`:

| Coluna | Tipo | Notas |
| --- | --- | --- |
| `id` | int PK | |
| `class` | string unique | FQCN Eloquent, ex. `App\Models\User` |

Uso desejado:

```php
Table::whereClass(User::class)->first();
// idealmente também:
Table::findOrFail(User::class);
```

### Tarefas

- [ ] **Migration + Model `Table`** — criar `tables` com `id` e `class` unique; model Eloquent correspondente.
- [ ] **Scope / lookup por class** — `whereClass(Model::class)` (ou equivalente tipado) retornando o registro.
- [ ] **Avaliar `findOrFail` por class** — verificar se dá para sobrescrever / customizar `findOrFail` (ou método dedicado, ex. `findOrFailByClass`) para aceitar FQCN além do id int. Se conflitar com a assinatura/semântica do Eloquent (`findOrFail($id)`), preferir API explícita e documentar o porquê.
- [ ] **Parser front → FQCN** — método que mapeia o valor enviado pelo front para `Model::class`, ex.:
  - `user` → `User::class`
  - `category` → `Category::class` (normalizar case)
  - `Store` → `Store::class`
  - Rejeitar / 422 para nomes desconhecidos ou fora da allowlist.
- [ ] **Seed / sync** — popular `tables` a partir dos Models conhecidos da app (artisan ou seeder) para `class` não ficar órfão.
- [ ] **Feature/Request tests** — cobertura do parser e do lookup; casos inválidos do front.

### Risco: relacionamentos polimórficos

Morphs do Laravel (`morphTo` / `morphMany`) já persistem `*_type` como `Model::class` (FQCN) ou alias do morph map. Um registry `tables` que também usa `Model::class` pode:

- **conflitar** se o código misturar `table_id` (int desta tabela) com `*_type` string do morph;
- **divergir** se a app usar `Relation::enforceMorphMap([...])` com aliases curtos enquanto `tables.class` guardar FQCN;
- **duplicar conceitos** (mapa morph vs registry) se os dois resolverem “nome → model” de jeitos diferentes.

Antes de integrar com morphs: decidir uma fonte de verdade (morph map da app **ou** `tables`), ou isolar o registry só para o contrato front↔back e **não** reutilizar `tables.id` / `tables.class` como `*_type` polimórfico sem desenho explícito.
