<?php

namespace App\Http\Requests\User;

use App\Http\Requests\Common\BaseRequest;

class UserIndexRequest extends BaseRequest {
  public function rules(): array {
    return [
      'filter'       => ['sometimes', 'array'],
      'filter.name'  => ['sometimes', 'string'],
      'sort'         => ['sometimes', 'string'],
      'fields'       => ['sometimes', 'array'],
      'fields.users' => ['sometimes', 'string'],
    ];
  }
}
