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
    $admin = User::create([
      'username' => 'admin',
      'name' => 'Admin Aristo',
      'email' => 'admin@aristo.com',
      'password' => Hash::make('admin'),
    ]);

    $roleAdmin = Role::create([
      'name' => 'admin',
      'display_name' => 'admin',
      'description' => 'Operator Gudang'
    ]);

    $permissionAdmin1 = Permission::create([
      'name' => 'admin-permission-view',
      'display_name' => 'ADMIN - PERMISSION - VIEW',
      'description' => 'ADMIN - PERMISSION - VIEW'
    ]);

    $permissionAdmin2 = Permission::create([
      'name' => 'admin-permission-create',
      'display_name' => 'ADMIN - PERMISSION - CREATE',
      'description' => 'ADMIN - PERMISSION - CREATE'
    ]);

    $admin->attachRole([$roleAdmin->id]);
    $roleAdmin->attachPermission([$permissionAdmin1->id, $permissionAdmin2->id]);
  }
}
