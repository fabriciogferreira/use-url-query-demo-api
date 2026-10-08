<?php

declare(strict_types=1);

use Illuminate\Support\ServiceProvider;

$path = 'App\Providers';

arch("only providers in {$path}")
  ->expect($path)
  ->toExtend(ServiceProvider::class);

arch("suffix providers with Provider")
  ->expect($path)
  ->toHaveSuffix('Provider');
