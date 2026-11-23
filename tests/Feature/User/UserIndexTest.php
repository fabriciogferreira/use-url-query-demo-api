<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

uses(RefreshDatabase::class);

describe('Authentication', function () {
  it('allows public access to the users index', function () {
    Mail::fake();
    Queue::fake();

    $this->getJson('/api/users')
      ->assertStatus(SymfonyResponse::HTTP_OK);

    Mail::assertNothingSent();
    Queue::assertNothingPushed();
  });
});

describe('Validation', function () {
  it('rejects a non-array filter query', function () {
    Mail::fake();
    Queue::fake();

    $this->getJson('/api/users?filter=name')
      ->assertStatus(SymfonyResponse::HTTP_UNPROCESSABLE_ENTITY)
      ->assertJsonValidationErrors(['filter']);

    Mail::assertNothingSent();
    Queue::assertNothingPushed();
  });

  it('rejects a non-string filter.name', function () {
    Mail::fake();
    Queue::fake();

    $this->getJson('/api/users?'.http_build_query([
      'filter' => ['name' => ['not-a-string']],
    ]))
      ->assertStatus(SymfonyResponse::HTTP_UNPROCESSABLE_ENTITY)
      ->assertJsonValidationErrors(['filter.name']);

    Mail::assertNothingSent();
    Queue::assertNothingPushed();
  });

  it('rejects a non-string sort', function () {
    Mail::fake();
    Queue::fake();

    $this->getJson('/api/users?'.http_build_query([
      'sort' => ['name'],
    ]))
      ->assertStatus(SymfonyResponse::HTTP_UNPROCESSABLE_ENTITY)
      ->assertJsonValidationErrors(['sort']);

    Mail::assertNothingSent();
    Queue::assertNothingPushed();
  });

  it('rejects a non-array fields query', function () {
    Mail::fake();
    Queue::fake();

    $this->getJson('/api/users?fields=id,name')
      ->assertStatus(SymfonyResponse::HTTP_UNPROCESSABLE_ENTITY)
      ->assertJsonValidationErrors(['fields']);

    Mail::assertNothingSent();
    Queue::assertNothingPushed();
  });

  it('rejects a non-string fields.users', function () {
    Mail::fake();
    Queue::fake();

    $this->getJson('/api/users?'.http_build_query([
      'fields' => ['users' => ['id', 'name']],
    ]))
      ->assertStatus(SymfonyResponse::HTTP_UNPROCESSABLE_ENTITY)
      ->assertJsonValidationErrors(['fields.users']);

    Mail::assertNothingSent();
    Queue::assertNothingPushed();
  });
});

describe('Behavior', function () {
  it('rejects filters that are not allowed', function () {
    Mail::fake();
    Queue::fake();

    User::factory()->named('Alice Johnson')->create();

    $this->getJson('/api/users?filter[email]=alice@example.com')
      ->assertStatus(SymfonyResponse::HTTP_BAD_REQUEST);

    Mail::assertNothingSent();
    Queue::assertNothingPushed();
  });

  it('rejects sorts that are not allowed', function () {
    Mail::fake();
    Queue::fake();

    User::factory()->named('Alice Johnson')->create();

    $this->getJson('/api/users?sort=email')
      ->assertStatus(SymfonyResponse::HTTP_BAD_REQUEST);

    Mail::assertNothingSent();
    Queue::assertNothingPushed();
  });

  it('rejects fields that are not allowed', function () {
    Mail::fake();
    Queue::fake();

    User::factory()->named('Alice Johnson')->create();

    $this->getJson('/api/users?'.http_build_query([
      'fields' => ['users' => 'email'],
    ]))
      ->assertStatus(SymfonyResponse::HTTP_BAD_REQUEST);

    Mail::assertNothingSent();
    Queue::assertNothingPushed();
  });

  it('filters users by exact name', function () {
    Mail::fake();
    Queue::fake();

    User::factory()->named('Alice Johnson')->create([
      'email' => 'alice@example.com',
    ]);
    User::factory()->named('Bob Smith')->create([
      'email' => 'bob@example.com',
    ]);
    User::factory()->named('Alice Johnson Extra')->create([
      'email' => 'alice.extra@example.com',
    ]);

    $response = $this->getJson('/api/users?filter[name]=Alice%20Johnson')
      ->assertStatus(SymfonyResponse::HTTP_OK);

    /** @var list<array{name: string}> $data */
    $data = $response->json('data');
    $names = array_column($data, 'name');

    expect($names)->toBe(['Alice Johnson']);

    Mail::assertNothingSent();
    Queue::assertNothingPushed();
  });
});

describe('Response', function () {
  it('returns all users when no filter is applied', function () {
    Mail::fake();
    Queue::fake();

    User::factory()->named('Alice Johnson')->create([
      'email' => 'alice@example.com',
    ]);
    User::factory()->named('Bob Smith')->create([
      'email' => 'bob@example.com',
    ]);

    $this->getJson('/api/users')
      ->assertStatus(SymfonyResponse::HTTP_OK)
      ->assertJsonCount(2, 'data')
      ->assertJsonPath('data.0.name', 'Alice Johnson')
      ->assertJsonPath('data.1.name', 'Bob Smith');

    Mail::assertNothingSent();
    Queue::assertNothingPushed();
  });

  it('sorts users by name ascending', function () {
    Mail::fake();
    Queue::fake();

    User::factory()->named('Bob Smith')->create([
      'email' => 'bob@example.com',
    ]);
    User::factory()->named('Alice Johnson')->create([
      'email' => 'alice@example.com',
    ]);

    $response = $this->getJson('/api/users?sort=name')
      ->assertStatus(SymfonyResponse::HTTP_OK);

    /** @var list<array{name: string}> $data */
    $data = $response->json('data');
    $names = array_column($data, 'name');

    expect($names)->toBe(['Alice Johnson', 'Bob Smith']);

    Mail::assertNothingSent();
    Queue::assertNothingPushed();
  });

  it('sorts users by name descending', function () {
    Mail::fake();
    Queue::fake();

    User::factory()->named('Alice Johnson')->create([
      'email' => 'alice@example.com',
    ]);
    User::factory()->named('Bob Smith')->create([
      'email' => 'bob@example.com',
    ]);

    $response = $this->getJson('/api/users?sort=-name')
      ->assertStatus(SymfonyResponse::HTTP_OK);

    /** @var list<array{name: string}> $data */
    $data = $response->json('data');
    $names = array_column($data, 'name');

    expect($names)->toBe(['Bob Smith', 'Alice Johnson']);

    Mail::assertNothingSent();
    Queue::assertNothingPushed();
  });

  it('selects only allowed user fields', function () {
    Mail::fake();
    Queue::fake();

    User::factory()->named('Alice Johnson')->create([
      'email' => 'alice@example.com',
    ]);

    $response = $this->getJson('/api/users?'.http_build_query([
      'fields' => ['users' => 'id,name'],
    ]))
      ->assertStatus(SymfonyResponse::HTTP_OK)
      ->assertJsonCount(1, 'data')
      ->assertJsonPath('data.0.name', 'Alice Johnson');

    /** @var array<string, mixed> $user */
    $user = $response->json('data.0');

    expect(array_keys($user))->toEqualCanonicalizing(['id', 'name']);
    expect($user)->not->toHaveKey('email');

    Mail::assertNothingSent();
    Queue::assertNothingPushed();
  });
});
