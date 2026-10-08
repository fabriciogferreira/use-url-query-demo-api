<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;

$path = 'database\migrations';

arch("only migrations in {$path}")
  ->expect($path)
  ->toExtend(Migration::class);

arch('migrations do not use application enums')
  ->expect($path)
  ->not->toUse('App\Enums');
