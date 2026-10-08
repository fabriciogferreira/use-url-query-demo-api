<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Factories\Factory;

$path = 'database\factories';

arch("only factories in {$path}")
  ->expect($path)
  ->toExtend(Factory::class);

arch("suffix factories with Factory")
  ->expect($path)
  ->toHaveSuffix('Factory');
