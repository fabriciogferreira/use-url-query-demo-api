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

## Validação — nomes de campos em pt_BR

- [ ] **Verificar `lang/pt_br/validation.php` (ou `langs/pt_br/validation.php`)** — parece haver algo pronto (ex. array `attributes`) para traduzir nomes de campos/colunas nas mensagens de validação. Confirmar o path real no projeto e o mecanismo (Laravel `attributes` vs pacote de lang).
- [ ] **Testar com mais colunas** — adicionar nomes de colunas de outras tabelas nesse arquivo e validar na API que a mensagem 422 usa o label traduzido (não o nome cru do atributo).

## Morphs — uso, cascade e testes

Problema: relacionamento polimórfico do Eloquent, por si só, **não** verifica se o registro alvo ainda está sendo referenciado. Falta API da app para checar uso e para definir o que acontece no delete (soft vs force, cascade), coberta por testes.

Tipos a cobrir (`Illuminate\Database\Eloquent\Relations\*`):

| Relação | Notas / comportamento desejado |
| --- | --- |
| `MorphTo` | _(preencher ao implementar)_ |
| `MorphOne` | _(preencher ao implementar)_ |
| `MorphMany` | _(preencher ao implementar)_ |
| `MorphToMany` | Ex.: `User` N—`musicalGenreable`—N `MusicalGenre` e `Event` N—`musicalGenreable`—N `MusicalGenre`. Se `User` tem soft delete → soft-delete do vínculo/`MusicalGenre` conforme regra; se `Event` não tem soft delete → delete “duro” do vínculo/`MusicalGenre` conforme regra. |

### Tarefas

- [ ] **Métodos de checagem de uso** — helpers/traits que respondem se um model (ex. `MusicalGenre`) ainda é referenciado por algum morph (`MorphTo` / `MorphOne` / `MorphMany` / `MorphToMany`), antes de apagar ou de permitir apagar.
- [ ] **Config de cascade no delete** — desenhar config (ou declaração no model) do tipo:

```php
[
  'deleted' => [
    [
      'relation' => MorphTo::class /* | MorphOne | MorphMany | MorphToMany */,
      // Qual o padrão do banco / Eloquent hoje?
      // Opções de cascade: null | delete | … ?
      'cascade' => 'delete', // ou 'forceDelete' — default: 'delete'
    ],
  ],
]
```

- [ ] **Alinhar soft delete vs force delete** — por parent: soft-deletable cascade soft; parent sem SoftDeletes cascade delete real (ver exemplo User vs Event acima). Documentar o default escolhido.
- [ ] **Testes dos métodos** — Feature/Service/unit cobrindo: registro em uso (bloqueia ou falha), registro sem uso (permite), cascade soft vs hard, cada tipo de morph relevante.
- [ ] **Cruzar com registry `tables`** — só depois de decidir fonte de verdade morph map vs `tables` (ver risco na seção anterior); não misturar `*_type` com `tables.id` sem desenho.

## Faker ORM (populator)

- [ ] **Avaliar [Faker ORM Integration](https://fakerphp.org/orm/)** — o FakerPHP tem adapter Eloquent (`Faker\ORM\…\Populator`) que popula entidades via guessers de nome/tipo de coluna, relaciona PKs entre `addEntity`, e aceita closures/`null` por coluna. Comparar com as factories Laravel do projeto: quando o populator ajuda (seed em massa, protótipo) vs quando factories + states continuam melhores (tests, contratos explícitos). Decidir se entra no boilerplate, só em seeders, ou fica de fora — e documentar a escolha.
