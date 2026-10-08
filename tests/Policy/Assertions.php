<?php

/**
 * Asserts that a policy method returns the expected authorization result.
 *
 * Receives a set of cases where each case provides the expected boolean and a
 * callable that returns the arguments for the policy method.
 *
 * @param class-string $policyClass The policy class to test.
 * @param string $method The policy method to invoke.
 * @param array<string, array{bool, callable}> $cases Map of case names to [expectedResult, argumentsCallable].
 */
function assertPolicyPermissions(
  string $policyClass,
  string $method,
  array $cases
): void {
  it("authorized", function (
    bool $expected,
    callable $context
  ) use (
    $policyClass,
    $method
  ) {
    $arguments = $context();

    $policy = new $policyClass();

    $result = $policy->{$method}(...$arguments);

    expect($result)->toBe($expected);
  })->with($cases);
}
