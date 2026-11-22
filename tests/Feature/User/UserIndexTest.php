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

    $names = collect($response->json('data'))->pluck('name')->all();

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
});
