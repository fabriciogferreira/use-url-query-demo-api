<?php

declare(strict_types=1);

$path = 'App\Rules';

arch("only rules in {$path}")
  ->expect($path)
  ->toBeClasses();

arch('suffix rules with Rule')
  ->expect($path)
  ->toHaveSuffix('Rule');
