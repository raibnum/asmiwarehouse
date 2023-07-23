<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
  public function index()
  {
    if (!Auth::user()->isAble(['admin-role-view', 'admin-role-create'])) return view('error.403');

    return view('admin.role.index');
  }

  public function create()
  {
    if (!Auth::user()->isAble(['admin-role-create'])) return view('error.403');

    $permissions = Permission::orderBy('display_name')->get()->all();

    return view('admin.role.create', ['permissions' => $permissions]);
  }

  public function store(Request $request)
  {
    if (!Auth::user()->isAble(['admin-role-create'])) return view('error.403');

    try {
      $data = $request->all();

      $name = trim($data['name']) != '' ? trim($data['name']) : null;
      $display_name = trim($data['display_name']) != '' ? trim($data['display_name']) : null;
      $description = trim($data['description']) != '' ? trim($data['description']) : null;
      $permissions = $data['permissions'] ? $data['permissions'] : [];

      if ($name == null || $display_name == null) throw new \Exception('Role dan name tidak boleh kosong');

      DB::beginTransaction();

      $role = Role::create([
        'name' => $name,
        'display_name' => $display_name,
        'description' => $description
      ]);

      $role->attachPermission($permissions);

      DB::commit();

      $request->session()->flash('alert-status', 'success');
      $request->session()->flash('alert-message', 'Berhasil membuat role. Role: ' . $display_name);

      return  redirect()->route('role.index');
    } catch (\Exception $ex) {
      DB::rollBack();

      $request->session()->flash('alert-status', 'danger');
      $request->session()->flash('alert-message', 'Gagal membuat role. ' . $ex->getMessage());

      return  redirect()->route('role.index');
    }
  }

  public function edit(Request $request, $id)
  {
    if (!Auth::user()->isAble(['admin-role-create'])) return view('error.403');

    $id = base64_decode($id);
    $role = Role::find($id);
    $permissions = Permission::permissionWithRole($id)->all();

    return view('admin.role.edit', ['id' => $id, 'permissions' => $permissions, 'role' => $role]);
  }

  public function update(Request $request, $id)
  {
    if (!Auth::user()->isAble(['admin-role-create'])) return view('error.403');

    try {
      $id = base64_decode($id);
      $data = $request->all();

      $name = trim($data['name']) != '' ? trim($data['name']) : null;
      $display_name = trim($data['display_name']) != '' ? trim($data['display_name']) : null;
      $description = trim($data['description']) != '' ? trim($data['description']) : null;

      if ($name == null || $display_name == null) throw new \Exception('Role dan name tidak boleh kosong');

      DB::beginTransaction();

      $role = Role::find($id)
        ->update([
          'name' => $name,
          'display_name' => $display_name,
          'description' => $description
        ]);

      $permissions = $data['permissions'] ?? [];

      $permission_role = Role::find($id)->permissions()->pluck('id')->all();
      foreach ($permission_role as $pr) {
        if (in_array($pr, $permissions)) continue;

        DB::table('permission_role')
          ->where('permission_id', $pr)
          ->where('role_id', $id)
          ->delete();
      }

      foreach ($permissions as $p) {
        if (in_array($p, $permission_role)) continue;

        DB::table('permission_role')
          ->insert([
            'permission_id' => $p,
            'role_id' => $id
          ]);
      }

      DB::commit();

      $request->session()->flash('alert-status', 'success');
      $request->session()->flash('alert-message', 'Berhasil membuat role. Role: ' . $display_name);

      return  redirect()->route('role.index');
    } catch (\Exception $ex) {
      DB::rollBack();

      $request->session()->flash('alert-status', 'danger');
      $request->session()->flash('alert-message', 'Gagal membuat role. ' . $ex->getMessage());

      return  redirect()->route('role.index');
    }
  }

  public function destroy(Request $request, $id)
  {
    if (!$request->ajax()) return redirect()->route('home');

    if (!Auth::user()->isAble(['admin-role-create'])) return response()->json([
      'title' => 'Forbidden',
      'message' => 'Maaf, Anda tidak memiliki akses ini',
      'status' => 'warning'
    ], 403);

    try {
      $id = base64_decode($id);

      DB::beginTransaction();

      DB::table('permission_role')
        ->where('role_id', $id)
        ->delete();

      $role = Role::find($id);
      $role->delete();

      DB::commit();

      return response()->json([
        'title' => 'Success',
        'message' => 'Berhasil menghapus role. Nama: ' . $role->display_name,
        'status' => 'success'
      ], 200);
    } catch (\Exception $ex) {
      DB::rollBack();
      return response()->json([
        'title' => 'Failed',
        'message' => 'Gagal menghapus role. ' . $ex->getMessage(),
        'status' => 'error'
      ], 500);
    }
  }
}
