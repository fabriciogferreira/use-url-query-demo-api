<?php

declare(strict_types=1);

use App\Enums\User\UserStatusEnum;
use App\Exceptions\BusinessRuleException;
use App\Http\Requests\Common\BaseRequest;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Tests\Fixtures\EnumerableSampleEnum;

test('UserStatusEnum exposes active and inactive values', function () {
  expect(UserStatusEnum::values())->toBe([
    UserStatusEnum::ACTIVE->value,
    UserStatusEnum::INACTIVE->value,
  ]);
});

test('UserStatusEnum validate rejects unknown cases with message', function () {
  expect(fn () => UserStatusEnum::validate('unknown'))
    ->toThrow(
      ValueError::class,
      'Value or key "unknown" is not part of the enum '.UserStatusEnum::class,
    );
});

test('UserStatusEnum validate rejects duplicates', function () {
  expect(fn () => UserStatusEnum::validate('active', 'active'))
    ->toThrow(BusinessRuleException::class, 'Duplicate values are not allowed.');
});

test('UserStatusEnum values validates only and except filters', function () {
  expect(fn () => UserStatusEnum::values(only: ['unknown']))
    ->toThrow(
      ValueError::class,
      'Value or key "unknown" is not part of the enum '.UserStatusEnum::class,
    );

  expect(fn () => UserStatusEnum::values(except: ['unknown']))
    ->toThrow(
      ValueError::class,
      'Value or key "unknown" is not part of the enum '.UserStatusEnum::class,
    );
});

test('User casts status password and email verified at', function () {
  $user = (new User)->forceFill([
    'name'              => 'Ada',
    'email'             => 'ada@example.com',
    'password'          => 'secret',
    'status'            => 'active',
    'email_verified_at' => '2024-06-15 12:00:00',
  ]);

  expect($user->status)->toBe(UserStatusEnum::ACTIVE);
  expect(Hash::check('secret', $user->password))->toBeTrue();
  expect($user->email_verified_at)->toBeInstanceOf(Carbon::class);
  expect($user->email_verified_at?->toDateTimeString())->toBe('2024-06-15 12:00:00');
});

test('User factory defaults to active and inactive state works', function () {
  $active = User::factory()->make();
  $inactive = User::factory()->inactive()->make();

  expect($active->status)->toBe(UserStatusEnum::ACTIVE)
    ->and($inactive->status)->toBe(UserStatusEnum::INACTIVE);
});

test('UserStatusEnum toInValidation and toProvider', function () {
  expect(UserStatusEnum::toInValidation())->toBe('in:active,inactive')
    ->and(UserStatusEnum::toProvider())->toBe([
      'active'   => ['active'],
      'inactive' => ['inactive'],
    ]);
});

test('Enumerable toProvider lowercases mixed case values', function () {
  expect(EnumerableSampleEnum::toProvider())->toBe([
    'mixed' => ['MiXeD'],
  ]);
});

test('UserStatusEnum values only filters by value or name', function () {
  expect(UserStatusEnum::values(only: ['active']))->toBe(['active'])
    ->and(UserStatusEnum::values(only: ['INACTIVE']))->toBe(['inactive']);
});

test('UserStatusEnum values except removes matching cases', function () {
  expect(UserStatusEnum::values(except: ['inactive']))->toBe(['active'])
    ->and(UserStatusEnum::values(except: ['ACTIVE']))->toBe(['inactive']);
});

test('UserStatusEnum toInValidation and toProvider honor only and except', function () {
  expect(UserStatusEnum::toInValidation(only: ['active']))->toBe('in:active')
    ->and(UserStatusEnum::toProvider(except: ['active']))->toBe([
      'inactive' => ['inactive'],
    ]);
});

test('BaseRequest authorize allows by default', function () {
  // Loaded via Pest helpers during this suite; exercise the only concrete line.
  $request = new class extends BaseRequest {
    public function rules(): array {
      return [];
    }
  };

  expect($request->authorize())->toBeTrue();
});
