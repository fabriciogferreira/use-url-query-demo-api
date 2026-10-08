<?php

declare(strict_types=1);

use App\Http\Controllers\Controller;

$path = 'App\Http\Controllers';

arch("only controllers in {$path}")
  ->expect($path)
  ->toExtend(Controller::class)
  ->ignoring(Controller::class);

arch("suffix controllers with Controller")
  ->expect($path)
  ->toHaveSuffix('Controller');
