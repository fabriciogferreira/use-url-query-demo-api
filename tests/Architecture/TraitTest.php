<?php

$path = 'App\Traits';

arch("only traits in {$path}")
  ->expect($path)
  ->toBeTraits();
