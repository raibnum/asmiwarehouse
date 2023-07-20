<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $this->gudang();
  }

  private function gudang(): void
  {
    $gudang = User::create([
      'username' => 'gudang',
      'name' => 'Operator Gudang',
      'email' => 'gudang@aristo.com',
      'password' => Hash::make('123')
    ]);

    $role = Role::where('name', 'like', 'whs%')->get()->all();
    foreach ($role as $r) {
      $gudang->attachRole([$r->id]);
    }
  }
}
