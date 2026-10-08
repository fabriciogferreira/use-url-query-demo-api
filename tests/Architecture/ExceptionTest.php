<?php

declare(strict_types=1);

use Illuminate\Foundation\Exceptions\Handler;

$path = 'App\Exceptions';

// arch("only exceptions in {$path}")
//   ->expect($path)
//   ->toExtend(Handler::class);

arch("suffix exceptions with Exception")
  ->expect($path)
  ->toHaveSuffix('Exception');
