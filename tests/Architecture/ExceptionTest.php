<?php

declare(strict_types=1);

use Throwable;

$path = 'App\Exceptions';

arch("exceptions in {$path} implement Throwable")
    ->expect($path)
    ->classes()
    ->toImplement(Throwable::class);

arch('suffix exceptions with Exception')
    ->expect($path)
    ->toHaveSuffix('Exception');
