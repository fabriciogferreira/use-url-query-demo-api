<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Throwable;

class BusinessRuleException extends UnprocessableEntityHttpException {
  /**
   * @param  array<string, mixed>  $headers
   */
  public function __construct(
    string $message,
    ?Throwable $previous = null,
    array $headers = []
  ) {
    parent::__construct($message, $previous, 0, $headers);
  }
}
