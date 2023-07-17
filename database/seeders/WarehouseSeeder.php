<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class WarehouseSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		/* Tool */
		$pTool1 = Permission::create([
			'name' => 'whs-tool-create',
			'display_name' => 'WHS - TOOL - CREATE',
			'description' => 'WHS - TOOL - CREATE'
		]);

		$pTool2 = Permission::create([
			'name' => 'whs-tool-view',
			'display_name' => 'WHS - TOOL - VIEW',
			'description' => 'WHS - TOOL - VIEW'
		]);

		$rTool = Role::create([
			'name' => 'whs_master_tool',
			'display_name' => 'WHS - MASTER TOOL',
			'description' => 'WHS - MASTER TOOL'
		]);

		$rTool->attachPermission([$pTool1->id, $pTool2->id]);

		/* Operator */
		$pOperator1 = Permission::create([
			'name' => 'whs-operator-create',
			'display_name' => 'WHS - OPERATOR - CREATE',
			'description' => 'WHS - OPERATOR - CREATE'
		]);

		$pOperator2 = Permission::create([
			'name' => 'whs-operator-view',
			'display_name' => 'WHS - OPERATOR - VIEW',
			'description' => 'WHS - OPERATOR - VIEW'
		]);

		$rOperator = Role::create([
			'name' => 'whs_master_operator',
			'display_name' => 'WHS - MASTER OPERATOR',
			'description' => 'WHS - MASTER OPERATOR'
		]);

		$rOperator->attachPermission([$pOperator1->id, $pOperator2->id]);

		/* attach role gudang */
		$user = User::create([
			'username' => 'gudang',
			'name' => 'Operator Gudang',
			'email' => 'gudang@aristo.com',
			'password' => Hash::make('123'),
		]);

		$user->attachRole([$rTool->id, $rOperator->id]);
	}
}
