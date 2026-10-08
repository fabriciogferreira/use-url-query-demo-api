<?php

declare(strict_types=1);

$path = 'App\Policies';

arch("only policies in {$path}")
  ->expect($path)
  ->toBeClasses();

arch("suffix policies with Policy")
  ->expect($path)
  ->toHaveSuffix('Policy')
  ->ignoring('App\Policies\Policy');
