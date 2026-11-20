<?php

declare(strict_types=1);

use App\Http\Controllers\Controller;

$path = 'App\Http\Controllers';

arch("only controllers in {$path}")
    ->expect($path)
    ->toExtend(Controller::class)
    ->ignoring(Controller::class);

arch('suffix controllers with Controller')
    ->expect($path)
    ->toHaveSuffix('Controller');

arch('controllers only expose resource actions')
    ->expect($path)
    ->not->toHavePublicMethodsBesides([
        '__construct',
        '__invoke',
        'index',
        'show',
        'create',
        'store',
        'edit',
        'update',
        'destroy',
        'middleware',
    ]);
