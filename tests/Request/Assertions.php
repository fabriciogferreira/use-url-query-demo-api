<?php

use App\Http\Requests\Common\BaseRequest;
use Illuminate\Validation\ValidationException;

/**
 * Asserts that invalid values trigger a ValidationException on the form request.
 *
 * Validates each invalid value directly against the form request rules.
 *
 * @param  class-string<BaseRequest>  $formRequest  The form request class.
 * @param  array<string, array<string, mixed>>  $fieldWrongValues  Map of fields to invalid values grouped by rule.
 */
function assertThrowsValidationException(
  string $formRequest,
  array $fieldWrongValues
): void {
  it('should throw a validation exception', function (
    string $field,
    mixed $wrongValue,
  ) use ($formRequest) {
    $abilityStoreRequest = mock($formRequest)->makePartial();

    $wrongValue = is_callable($wrongValue) ? $wrongValue($abilityStoreRequest) : $wrongValue;

    // @phpstan-ignore method.notFound
    $rules = $abilityStoreRequest->rules();
    assert(is_array($rules));

    $validator = validator([$field => $wrongValue], $rules);

    $validator->validate();
  })
    ->throws(ValidationException::class)
    ->with(makePayloads($fieldWrongValues));
}
