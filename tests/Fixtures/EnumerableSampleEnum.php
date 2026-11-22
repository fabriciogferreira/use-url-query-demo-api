<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use App\Traits\Enumerable;

/**
 * Mixed-case backed value so Enumerable::toProvider strtolower is observable in mutation tests.
 * Not a domain enum — App\Enums stays snake_case.
 */
enum EnumerableSampleEnum: string {
  use Enumerable;

  case MIXED = 'MiXeD';
}
