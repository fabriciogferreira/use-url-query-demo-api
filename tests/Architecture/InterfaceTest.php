<?php

declare(strict_types=1);

$path = 'App\Interfaces';

arch("only interfaces in {$path}")
  ->expect($path)
  ->toBeInterfaces();
