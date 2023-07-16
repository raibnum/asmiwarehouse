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

    /* Gudang */
    $gudang = User::create([
      'username' => 'gudang',
      'name' => 'Operator Gudang',
      'email' => 'gudang@aristo.com',
      'password' => Hash::make('123'),
    ]);

    $roleGudang = Role::create([
      'name' => 'gudang',
      'display_name' => 'gudang',
      'description' => 'Operator Gudang'
    ]);

    $permissionGudang1 = Permission::create([
      'name' => 'whs-operator-view',
      'display_name' => 'GUDANG - OPERATOR - VIEW',
      'description' => 'GUDANG - OPERATOR - VIEW'
    ]);

    $permissionGudang2 = Permission::create([
      'name' => 'whs-operator-create',
      'display_name' => 'GUDANG - OPERATOR - CREATE',
      'description' => 'GUDANG - OPERATOR - CREATE'
    ]);

    $gudang->attachRole([$roleGudang->id]);
    $roleGudang->attachPermission([$permissionGudang1->id, $permissionGudang2->id]);
  }
}
