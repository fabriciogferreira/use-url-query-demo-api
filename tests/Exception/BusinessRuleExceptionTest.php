<?php

declare(strict_types=1);

use App\Exceptions\BusinessRuleException;

test('BusinessRuleException uses unprocessable entity status', function () {
  $exception = new BusinessRuleException('Duplicate values are not allowed.');

  expect($exception->getStatusCode())->toBe(422);
  expect($exception->getMessage())->toBe('Duplicate values are not allowed.');
});

test('BusinessRuleException keeps previous and headers', function () {
  $previous = new RuntimeException('cause');
  $exception = new BusinessRuleException('rule broken', $previous, ['X-Test' => '1']);

  expect($exception->getPrevious())->toBe($previous)
    ->and($exception->getHeaders())->toBe(['X-Test' => '1']);
});
