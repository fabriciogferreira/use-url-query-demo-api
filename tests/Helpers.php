<?php

use App\Models\User;

/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
|
| Here you can define common functions that you can reuse in all your test files. For example, you can define a function to authenticate a user and then use it in all your test files.
|
*/

/**
 * Retrieves a user by email from the database.
 *
 * @param  string  $email  The email of the user to retrieve. Defaults to 'user@user.com'.
 */
function getUser(string $email = 'user@user.com'): User {
  return User::query()
    ->where(['email' => $email])
    ->firstOrFail();
}

/**
 * This function takes an associative array of test cases, where each key is a field name and each value is an associative array of validation rules and their corresponding wrong values. It generates a new associative array of payloads, where each key is a unique dataset name (combination of field and rule) and each value is an array containing the field name and the wrong value. This is useful for testing validation scenarios in a structured way.
 *
 * @param  array<string, array<string, mixed>>  $cases
 * @return array<string, array<mixed>>
 */
function makePayloads(array $cases): array {
  $payloads = [];

  foreach ($cases as $field => $wrongValues) {
    foreach ($wrongValues as $rule => $wrongValue) {
      $datasetName = implode('_', [$field, $rule]);

      $payloads[$datasetName] = [$field, $wrongValue];
    }
  }

  return $payloads;
}

/**
 * Returns a non-existing integer ID for testing model not found scenarios.
 *
 * @return int A non-existing ID (0).
 */
function nonExistingId(): int {
  return 0;
}

/**
 * Returns a non-existing UUID for testing model not found scenarios.
 *
 * @return string A non-existing UUID ('00000000-0000-0000-0000-000000000000').
 */
function nonExistingUuid(): string {
  return '00000000-0000-0000-0000-000000000000';
}
