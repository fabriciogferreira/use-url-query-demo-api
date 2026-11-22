<?php

namespace Database\Seeders;

use App\Enums\User\UserStatusEnum;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
  use WithoutModelEvents;

  /**
   * Seed the application's database.
   */
  public function run(): void {
    User::factory()
      ->count(6)
      ->sequence(
        [
          'name'  => 'Alice Johnson',
          'email' => 'alice.johnson@example.com',
        ],
        [
          'name'  => 'Bob Smith',
          'email' => 'bob.smith@example.com',
        ],
        [
          'name'  => 'Maria Silva',
          'email' => 'maria.silva@example.com',
        ],
        [
          'name'  => 'John Doe',
          'email' => 'john.doe@example.com',
        ],
        [
          'name'              => 'Ana Costa',
          'email'             => 'ana.costa@example.com',
          'email_verified_at' => null,
        ],
        [
          'name'   => 'Inactive User',
          'email'  => 'inactive.user@example.com',
          'status' => UserStatusEnum::INACTIVE,
        ],
      )
      ->create();

    User::factory(20)->create();
  }
}
