<?php

declare(strict_types=1);

$path = 'App\Services';

arch("only services in {$path}")
  ->expect($path)
  ->toBeClasses();

arch('suffix services with Service')
  ->expect($path)
  ->toHaveSuffix('Service');
