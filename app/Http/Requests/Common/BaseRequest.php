<?php

namespace App\Http\Requests\Common;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

abstract class BaseRequest extends FormRequest {
  public function authorize(): bool {
    return true;
  }

  /**
   * @return array<string, ValidationRule|array<mixed>|string>
   */
  abstract public function rules(): array;
}
