<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolePermissionSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    /* ADMIN */
    $this->roleAdmin();
    $this->roleGudang();
  }

  private function roleAdmin(): void
  {
    $admin = Role::create([
      'name' => 'admin',
      'display_name' => 'ADMIN',
      'description' => 'Admin Aplikasi'
    ]);

    $permission_view = Permission::create([
      'name' => 'admin-permission-view',
      'display_name' => 'ADMIN - PERMISSION - VIEW',
      'description' => 'Untuk melihat daftar permission'
    ]);

    $permission_create = Permission::create([
      'name' => 'admin-permission-create',
      'display_name' => 'ADMIN - PERMISSION - CREATE',
      'description' => 'Untuk CRUD permission'
    ]);

    $role_view = Permission::create([
      'name' => 'admin-role-view',
      'display_name' => 'ADMIN - ROLE - VIEW',
      'description' => 'Untuk melihat daftar role'
    ]);

    $role_create = Permission::create([
      'name' => 'admin-role-create',
      'display_name' => 'ADMIN - ROLE - CREATE',
      'description' => 'Untuk CRUD role'
    ]);

    $user_view = Permission::create([
      'name' => 'admin-user-view',
      'display_name' => 'ADMIN - USER - VIEW',
      'description' => 'Untuk melihat daftar user'
    ]);

    $user_create = Permission::create([
      'name' => 'admin-user-create',
      'display_name' => 'ADMIN - USER - CREATE',
      'description' => 'Untuk CRUD user'
    ]);

    $admin->attachPermission([
      $permission_view->id,
      $permission_create->id,

      $role_view->id,
      $role_create->id,

      $user_view->id,
      $user_create->id
    ]);
  }

  private function roleGudang(): void
  {
    $whs_master_operator = Role::create([
      'name' => 'whs_master_operator',
      'display_name' => 'MASTER OPERATOR',
      'description' => 'Untuk mengakses menu master operator'
    ]);

    $mas_operator_view = Permission::create([
      'name' => 'whs-operator-view',
      'display_name' => 'MASTER OPERATOR VIEW',
      'description' => 'Untuk melihat menu master operator'
    ]);

    $mas_operator_create = Permission::create([
      'name' => 'whs-operator-create',
      'display_name' => 'MASTER OPERATOR CREATE',
      'description' => 'Untuk CRUD menu master operator'
    ]);

    $whs_master_operator->attachPermission([$mas_operator_view->id, $mas_operator_create->id]);

    $whs_master_tool = Role::create([
      'name' => 'whs_master_tool',
      'display_name' => 'MASTER TOOL',
      'description' => 'Untuk mengakses menu master tool'
    ]);

    $mas_tool_view = Permission::create([
      'name' => 'whs-tool-view',
      'display_name' => 'MASTER TOOL VIEW',
      'description' => 'Untuk melihat menu master tool'
    ]);

    $mas_tool_create = Permission::create([
      'name' => 'whs-tool-create',
      'display_name' => 'MASTER TOOL CREATE',
      'description' => 'Untuk CRUD menu master tool'
    ]);

    $whs_master_tool->attachPermission([$mas_tool_view->id, $mas_tool_create->id]);

    $whs_pinjam_tool = Role::create([
      'name' => 'whs_pinj_tool',
      'display_name' => 'PINJAM TOOL',
      'description' => 'Untuk mengakses menu pinjam tool'
    ]);

    $mas_pinjam_tool_view = Permission::create([
      'name' => 'whs-pinj-tool-view',
      'display_name' => 'TRANSAKSI PINJAM TOOL VIEW',
      'description' => 'Untuk melihat menu transaksi pinjam tool'
    ]);

    $mas_pinjam_tool_create = Permission::create([
      'name' => 'whs-pinj-tool-create',
      'display_name' => 'TRANSAKSI PINJAM TOOL CREATE',
      'description' => 'Untuk CRUD menu transaksi pinjam tool'
    ]);

    $whs_pinjam_tool->attachPermission([$mas_pinjam_tool_view->id, $mas_pinjam_tool_create->id]);

    $whs_keluar_masuk_tool = Role::create([
      'name' => 'whs_keluar_masuk_tool',
      'display_name' => 'KELUAR MASUK TOOL',
      'description' => 'Untuk mengakses menu keluar masuk tool'
    ]);

    $mas_keluar_masuk_tool_view = Permission::create([
      'name' => 'whs-keluar-masuk-tool-view',
      'display_name' => 'TRANSAKSI KELUAR MASUK TOOL VIEW',
      'description' => 'Untuk melihat menu transaksi keluar masuk tool'
    ]);

    $mas_keluar_masuk_tool_create = Permission::create([
      'name' => 'whs-keluar-masuk-tool-create',
      'display_name' => 'TRANSAKSI KELUAR MASUK TOOL CREATE',
      'description' => 'Untuk CRUD menu transaksi keluar masuk tool'
    ]);

    $whs_keluar_masuk_tool->attachPermission([$mas_keluar_masuk_tool_view->id, $mas_keluar_masuk_tool_create->id]);

    $whs_permintaan_pembelian_tool = Role::create([
      'name' => 'whs_permintaan_pembelian_tool',
      'display_name' => 'PERMINTAAN PEMBELIAN TOOL',
      'description' => 'Untuk mengakses menu permintaan pembelian tool'
    ]);

    $mas_permintaan_pembelian_tool_view = Permission::create([
      'name' => 'whs-permintaan-pembelian-tool-view',
      'display_name' => 'TRANSAKSI PERMINTAAN PEMBELIAN TOOL VIEW',
      'description' => 'Untuk melihat menu transaksi permintaan pembelian tool'
    ]);

    $mas_permintaan_pembelian_tool_create = Permission::create([
      'name' => 'whs-permintaan-pembelian-tool-create',
      'display_name' => 'TRANSAKSI PERMINTAAN PEMBELIAN TOOL CREATE',
      'description' => 'Untuk CRUD menu transaksi permintaan pembelian tool'
    ]);

    $whs_permintaan_pembelian_tool->attachPermission([$mas_permintaan_pembelian_tool_view->id, $mas_permintaan_pembelian_tool_create->id]);

    $whs_inout_tool = Role::create([
      'name' => 'whs_inout_tool',
      'display_name' => 'INOUT TOOL',
      'description' => 'Untuk mengakses menu inout tool'
    ]);

    $whs_inout_tool_view = Permission::create([
      'name' => 'whs-inout-tool-view',
      'display_name' => 'TRANSAKSI INOUT TOOL VIEW',
      'description' => 'Untuk melihat menu transaksi inout tool'
    ]);

    $whs_inout_tool_create = Permission::create([
      'name' => 'whs-inout-tool-create',
      'display_name' => 'TRANSAKSI INOUT TOOL CREATE',
      'description' => 'Untuk CRUD menu transaksi inout tool'
    ]);

    $whs_inout_tool->attachPermission([$whs_inout_tool_view->id, $whs_inout_tool_create->id]);

    $whs_pp_tool = Role::create([
      'name' => 'whs_pp_tool',
      'display_name' => 'PP TOOL',
      'description' => 'Untuk mengakses menu pp tool'
    ]);

    $whs_pp_tool_view = Permission::create([
      'name' => 'whs-pp-tool-view',
      'display_name' => 'TRANSAKSI PP TOOL VIEW',
      'description' => 'Untuk melihat menu transaksi pp tool'
    ]);

    $whs_pp_tool_create = Permission::create([
      'name' => 'whs-pp-tool-create',
      'display_name' => 'TRANSAKSI PP TOOL CREATE',
      'description' => 'Untuk CRUD menu transaksi pp tool'
    ]);

    $whs_pp_tool->attachPermission([$whs_pp_tool_view->id, $whs_pp_tool_create->id]);

    $whs_pp_tool_appr = Role::create([
      'name' => 'whs_pp_tool_approve',
      'display_name' => 'PP TOOL APPROVE',
      'description' => 'Untuk mengakses menu approve pp tool'
    ]);

    $whs_pp_tool_approve = Permission::create([
      'name' => 'whs-pp-tool-approve',
      'display_name' => 'TRANSAKSI PP TOOL APPROVE',
      'description' => 'Untuk mengakses menu approve pp tool'
    ]);

    $whs_pp_tool_appr->attachPermission([$whs_pp_tool_approve->id]);

    $prch_pp_tool = Role::create([
      'name' => 'prch_pp_tool',
      'display_name' => 'PP TOOL PURCHASING',
      'description' => 'Untuk mengakses menu purchasing pp tool'
    ]);

    $prch_pp_tool_submit = Permission::create([
      'name' => 'prch-pp-tool-submit',
      'display_name' => 'TRANSAKSI PP TOOL PURCHASING',
      'description' => 'Untuk mengakses menu purchasing pp tool'
    ]);

    $prch_pp_tool->attachPermission([$prch_pp_tool_submit->id]);
  }
}
