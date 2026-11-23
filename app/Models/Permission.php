<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['name'])]
class Permission extends Model {
  /** @use HasFactory<\Database\Factories\PermissionFactory> */
  use HasFactory;

  /**
   * @return BelongsToMany<User, $this>
   */
  public function users(): BelongsToMany {
    return $this->belongsToMany(User::class);
  }
}
