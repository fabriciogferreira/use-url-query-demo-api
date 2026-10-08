<?php

declare(strict_types=1);

use App\Traits\Enumerable;
use Symfony\Component\Finder\Finder;

$path = 'App\Enums';

arch("only enums in {$path}")
  ->expect($path)
  ->toBeEnums();

arch('suffix enums with Enum')
  ->expect($path)
  ->toHaveSuffix('Enum');

$enumsDir = dirname(__DIR__, 2).'/app/Enums';

if (is_dir($enumsDir) && Finder::create()->files()->in($enumsDir)->name('*.php')->hasResults()) {
  arch('should use '.Enumerable::class)
    ->expect($path)
    ->toUse(Enumerable::class);
}
