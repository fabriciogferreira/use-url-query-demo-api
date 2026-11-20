<?php

declare(strict_types=1);

$path = 'App\Traits';

arch("only traits in {$path}")
    ->expect($path)
    ->toBeTraits();
