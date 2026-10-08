<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Model;

$path = 'App\Models';
$scopesPath = $path . '\Scopes';

arch("only models in {$path}")
  ->expect($path)
  ->toExtend(Model::class)
  ->ignoring($scopesPath);
