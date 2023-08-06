<?php

namespace Database\Seeders;

use App\Models\JenisTool;
use App\Models\Operator;
use App\Models\Tool;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class WarehouseSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		JenisTool::create([
			'kd_jenis' => 'IN',
			'nm_jenis' => 'INSERT'
		]);

		JenisTool::create([
			'kd_jenis' => 'BG',
			'nm_jenis' => 'BATU GRINDING'
		]);

		Tool::create([
			'kd_tool' => 'IN-APMT-11-35',
			'nm_tool' => 'APMT 1135 PDER-PM',
			'kd_jenis' => 'IN',
			'stok' => 10,
			'stok_minimal' => 10,
			'harga' => 10000,
			'st_aktif' => true,
			'st_sekali_pakai' => true
		]);

		Tool::create([
			'kd_tool' => 'IN-APMT-16-08',
			'nm_tool' => 'APMT160408PDER',
			'kd_jenis' => 'IN',
			'stok' => 10,
			'stok_minimal' => 10,
			'harga' => 10000,
			'st_aktif' => true,
			'st_sekali_pakai' => true
		]);

		Operator::create([
			'nm_operator' => 'Muhammad Rafli',
			'divisi' => 'IT'
		]);
		Operator::create([
			'nm_operator' => 'Raihan Ibnu M.',
			'divisi' => 'Gudang'
		]);
		Operator::create([
			'nm_operator' => 'Jordan Vibesco',
			'divisi' => 'HR'
		]);
	}
}
