<?php

declare(strict_types=1);

$path = 'App\Services';
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

arch("only services in {$path}")
  ->expect($path)
  ->toBeClasses();

arch('suffix services with Service')
  ->expect($path)
  ->toHaveSuffix('Service');

arch('services do not use HTTP concerns')
  ->expect($path)
  ->not->toUse([
    'Illuminate\Http',
    'Illuminate\Routing',
    Auth::class,
    Request::class,
    Route::class,
    'auth',
    'request',
    'response',
    'route',
  ]);
