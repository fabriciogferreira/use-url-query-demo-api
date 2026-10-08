<?php

declare(strict_types=1);

use Illuminate\Database\Seeder;

$path = 'database\seeders';

arch("only seeders in {$path}")
  ->expect($path)
  ->toExtend(Seeder::class);

arch("suffix seeders with Seeder")
  ->expect($path)
  ->toHaveSuffix('Seeder');
