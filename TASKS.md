# Tasks

Problema: Feature tests já fecham mail e jobs (ver `.ai/rules/feature.md`), mas ainda não há código reutilizável para inspecionar HTTP outbound, queries SQL e mudanças no banco — nem a documentação desses asserts. Este arquivo lista o trabalho para isso.

## Closed side effects — request, queries, database

- [ ] **HTTP requests (outbound)** — helper/fake que registra `Http::` (ou client do app) e permite assertar “nada saiu” ou a lista esperada de URLs/métodos; documentar em `.ai/rules/feature.md` no mesmo padrão de mail/jobs.
- [ ] **Queries** — helper que conta/lista queries durante o act (ex. `DB::listen` / query log) e asserta teto ou conjunto esperado; cobrir N+1 (e opcionalmente `Model::preventLazyLoading()` em `testing`); documentar quando o Feature test deve falhar por lazy load vs por contagem.
- [ ] **Database** — helper que registra inserts/updates/deletes (ou diff de estado) da request sob teste e permite assertar “nenhuma escrita” ou tabelas/linhas esperadas; alinhar com *Failure rolls back prior work* (falha mid-write sem estado parcial).
- [ ] **Integrar nos Feature tests** — usar os helpers em Authentication / Authorization / Validation / Behavior / Response quando a rota não deve disparar aquele side effect; exemplos mínimos num teste de referência.
- [ ] **Atualizar rules** — em `.ai/rules/feature.md` (e ponte em `tests.md`), tirar o aviso “só mail/jobs” e documentar request, queries e database como subjects fechados.
