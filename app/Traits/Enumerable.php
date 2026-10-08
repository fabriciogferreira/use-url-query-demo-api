<?php

namespace App\Traits;

use App\Exceptions\BusinessRuleException;
use BackedEnum;

trait Enumerable {
  public static function validate(string|int ...$cases): void {
    $hasDuplicate = count($cases) !== count(array_unique($cases));

    if ($hasDuplicate) {
      throw new BusinessRuleException('Duplicate values are not allowed.');
    }

    $keysValue = collect(self::cases())
      ->reduce(function ($carry, $case) {
        $carry[$case->value] = $case->value;
        $carry[$case->name] = $case->value;

        return $carry;
      }, []);

    foreach ($cases as $case) {
      if (! array_key_exists($case, $keysValue)) {
        throw new \ValueError('Value or key "'.$case.'" is not part of the enum '.self::class);
      }
    }
  }

  /**
   * @param  string[]  $only
   * @param  string[]  $except
   * @return array<int, int | string>
   */
  public static function values(array $only = [], array $except = []): array {
    $values = self::cases();

    $exceptOnly = array_merge($only, $except);

    self::validate(...$exceptOnly);

    if (count($only) > 0) {
      $values = array_filter(
        $values,
        fn (BackedEnum $case) => in_array($case->name, $exceptOnly) || in_array($case->value, $exceptOnly)
      );
    }

    if (count($except)) {
      $values = array_filter(
        $values,
        fn (BackedEnum $case) => ! (in_array($case->name, $exceptOnly) || in_array($case->value, $exceptOnly))
      );
    }

    $values = array_column($values, 'value');

    return $values;
  }

  /**
   * @param  string[]  $only
   * @param  string[]  $except
   */
  public static function toInValidation(array $only = [], array $except = []): string {
    $values = self::values(only: $only, except: $except);

    return 'in:'.implode(',', $values);
  }

  /**
   * @param  string[]  $only
   * @param  string[]  $except
   * @return array<string, array<int, string>>
   */
  public static function toProvider(array $only = [], array $except = []): array {
    return collect(self::values(only: $only, except: $except))
      ->mapWithKeys(fn ($value) => [strtolower($value) => [$value]])
      ->toArray();
  }
}
