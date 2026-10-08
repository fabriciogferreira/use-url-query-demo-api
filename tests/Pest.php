<?php

use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind different classes or traits.
|
*/
/*
| in() must receive absolute paths (__DIR__ . '/...'). Relative names like
| 'Feature' are resolved from Pest's Backtrace::testFile(), not from this file.
| The Laravel LSP require()s Pest.php from its own helper template, so that
| backtrace points at the LSP script and in('Feature') globs the wrong folder.
| The generated storage/framework/testing/_pest.php then types $this as
| PHPUnit\Framework\TestCase and autocomplete disappears inside test closures.
*/

pest()->extend(TestCase::class)
  // ->use(Illuminate\Foundation\Testing\RefreshDatabase::class)
  ->in(
    __DIR__.'/Architecture',
    __DIR__.'/Exception',
    __DIR__.'/Feature',
    __DIR__.'/Policy',
    __DIR__.'/Request',
    __DIR__.'/Service',
  );

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
  return $this->toBe(1);
});
