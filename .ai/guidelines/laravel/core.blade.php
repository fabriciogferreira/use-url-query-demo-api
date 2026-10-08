@php
/** @var \Laravel\Boost\Install\GuidelineAssist $assist */
@endphp
# Do Things the Laravel Way

- Follow the project Environment guideline when running Artisan, including `make:` and `make:test`.
- Use `{{ $assist->artisanCommand('make:') }}` commands to create new files (i.e. migrations, controllers, models, etc.). You can list available Artisan commands using `{{ $assist->artisanCommand('list') }}` and check their parameters with `{{ $assist->artisanCommand('[command] --help') }}`.
- If you're creating a generic PHP class, use `{{ $assist->artisanCommand('make:class') }}`.
- Pass `--no-interaction` to all Artisan commands to ensure they work without user input. You should also pass the correct `--options` to ensure correct behavior.

@scoped(['app/Models/**'])
### Model Creation

- When creating new models, create useful factories and seeders for them too. Ask the user if they need any other things, using `{{ $assist->artisanCommand('make:model --help') }}` to check the available options.
@endscoped

@scoped(['app/Http/**', 'routes/**'])
## APIs & HTTP responses

- Follow `.ai/rules/controllers.md` for API responses: return `response([...], SymfonyResponse::HTTP_*)` with an explicit status. Do not introduce Eloquent API Resources unless that pattern already exists in the application.
@endscoped

## URL Generation
- When generating links to other pages, prefer named routes and the `route()` function.

@scoped(['tests/**'])
## Testing

- Follow the project Environment guideline when running tests and `make:test`.
- Follow `.ai/rules/tests.md` (and `.ai/rules/feature.md` for Feature HTTP tests). Do not use `tests/Unit` or mirror `app/` under `tests/`.
- When creating models for tests, use the factories for the models. Check if the factory has custom states that can be used before manually setting up the model.
- Faker: Use methods such as `$this->faker->word()` or `fake()->randomDigit()`. Follow existing conventions whether to use `$this->faker` or `fake()`.
- Create tests with `{{ $assist->artisanCommand('make:test --pest {name}') }}`. Do not include the suite directory in `{name}`.
@endscoped
