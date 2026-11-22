<?php

namespace App\Enums\User;

use App\Traits\Enumerable;

enum UserStatusEnum: string {
  use Enumerable;

  case ACTIVE = 'active';
  case INACTIVE = 'inactive';
}
