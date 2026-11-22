<?php

namespace Tests\Feature;

use App\Http\Requests\Common\BaseRequest;

use Illuminate\Database\Eloquent\Model;

/**
 * @template TModel of Model
 */
abstract class FeatureTest {
  public string $baseUrl;
  public string $url;
  public string $httpMethod;
  /** @var array<string, mixed> */
  public array $payload;
  public string $modelClass;
  public int $responseCode;
  public bool $isAuthentic;
  /** @var TModel */
  public Model $model;
  /** @var class-string<BaseRequest> */
  public string $formRequestClass;

  /** @param array<string, mixed> $payload */
  public function __construct(
    string $httpMethod,
    string $path = '',
    array $payload = [],
    int $responseCode = 200,
    bool $isAuthentic = true
  ) {
    $this->httpMethod = $httpMethod;
    $this->baseUrl = $this->baseUrl() . $path;
    $this->modelClass = $this->modelClass();
    $this->payload = $payload;
    $this->url = $this->baseUrl;
    $this->responseCode = $responseCode;
    $this->isAuthentic = $isAuthentic;
    $this->modelClass = $this->modelClass();
  }

  abstract protected function baseUrl(): string;
  abstract protected function modelClass(): string;

  /**
   * @param array<int, mixed> $items
   * @return array<int, array<int, mixed>>
   */
  private function powerSet(array $items, bool $includeEmpty = true): array {
    $result = [[]];

    foreach ($items as $item) {
      foreach ($result as $subset) {
        $result[] = [...$subset, $item];
      }
    }

    if (! $includeEmpty) {
      $result = array_values(
        array_filter($result, fn ($subset) => $subset !== [])
      );
    }

    return $result;
  }

  /**
   * @param array<string, string|int> ...$payloads
   * @return array<string, list<array<string, string|int>>>
   */
  public function makePayloadCases(array ...$payloads): array {
    /** @var list<array<string, string|int>> $items */
    $items = array_values($payloads);
    $payloadPowerSet = $this->powerSet($items, false);

    $payloadPowerSet = collect($payloadPowerSet);

    $cases = $payloadPowerSet->mapWithKeys(function (array $combination) {
      /** @var list<array<string, string|int>> $combination */
      $payloadMerged = array_merge(...$combination);

      $key = implode(',', array_keys($payloadMerged));

      return [$key => [$payloadMerged]];
    });

    return $cases->all();
  }
}
