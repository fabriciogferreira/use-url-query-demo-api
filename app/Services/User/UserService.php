<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\Enums\FilterOperator;
use Spatie\QueryBuilder\QueryBuilder;

class UserService {
  /**
   * @param  array<string, mixed>  $validatedData  Form Request payload; filters are applied via QueryBuilder from the request.
   * @return Collection<int, User>
   */
  public function index(array $validatedData): Collection {
    /** @var Collection<int, User> $users */
    $users = QueryBuilder::for(User::class)
      ->allowedFilters(
        AllowedFilter::operator('name', FilterOperator::EQUAL),
      )
      ->allowedSorts('name')
      ->allowedFields('id', 'name')
      ->allowedIncludes('posts', 'permissions')
      ->get();

    return $users;
  }
}
