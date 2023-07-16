<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PermissionController extends Controller
{
  public function index()
  {
    // if (!Auth::user()->isAble(['admin_permission_view', 'admin_permission_create'])) return view('error.403');

    return view('admin.permission.index');
  }

  public function create()
  {
    // if (!Auth::user()->isAble(['admin_permission_create'])) return view('error.403');

    return view('admin.permission.create');
  }

  public function store(Request $request)
  {
    // if (!Auth::user()->isAble(['admin_permission_create'])) return view('error.403');

    try {
      $data = $request->all();

      $name = trim($data['name']) != '' ? trim($data['name']) : null;
      $display_name = trim($data['display_name']) != '' ? trim($data['display_name']) : null;
      $description = trim($data['description']) != '' ? trim($data['description']) : null;

      if ($name == null || $display_name == null) throw new \Exception('Permission dan name tidak boleh kosong');

      DB::beginTransaction();

      Permission::create([
        'name' => $name,
        'display_name' => $display_name,
        'description' => $description
      ]);

      DB::commit();

      $request->session()->flash('alert-status', 'success');
      $request->session()->flash('alert-message', 'Berhasil membuat permission. Permission: ' . $display_name);

      return redirect()->route('permission.index');
    } catch (\Exception $ex) {
      DB::rollback();

      $request->session()->flash('alert-status', 'danger');
      $request->session()->flash('alert-message', 'Gagal membuat permission. ' . $ex->getMessage());
      return redirect()->route('permission.index');
    }
  }

  public function edit(Request $request, $id)
  {
    // if (!Auth::user()->isAble(['admin_permission_create'])) return view('error.403');

    $id = base64_decode($id);
    $permission = Permission::find($id);

    return view('admin.permission.edit', ['permission' => $permission]);
  }

  public function update(Request $request, $id)
  {
    // if (!Auth::user()->isAble(['admin_permission_create'])) return view('error.403');

    try {
      $id = base64_decode($id);
      $permission = Permission::find($id);

      $data = $request->all();
      $name = trim($data['name']) != '' ? trim($data['name']) : null;
      $display_name = trim($data['display_name']) != '' ? trim($data['display_name']) : null;
      $description = trim($data['description']) != '' ? trim($data['description']) : null;

      if ($name == null || $display_name == null) throw new \Exception('Permission dan name tidak boleh kosong');

      DB::beginTransaction();

      $permission->update([
        'name' => $name,
        'display_name' => $display_name,
        'description' => $description
      ]);

      DB::commit();

      $request->session()->flash('alert-status', 'success');
      $request->session()->flash('alert-message', 'Berhasil mengubah permission. Permission: ' . $display_name);

      return  redirect()->route('permission.index');
    } catch (\Exception $ex) {
      DB::rollback();

      $request->session()->flash('alert-status', 'danger');
      $request->session()->flash('alert-message', 'Gagal mengubah permission. ' . $ex->getMessage());
      return redirect()->route('permission.index');
    }
  }

  public function destroy(Request $request, $id)
  {
    if (!$request->ajax()) return redirect()->route('home');
    // if (!Auth::user()->isAble(['admin_permission_create'])) return response()->json([
    //   'title' => 'Forbidden',
    //   'message' => 'Maaf, Anda tidak memiliki izin untuk akses ini'
    // ]);

    try {
      $id = base64_decode($id);
      $permission = Permission::find($id);

      DB::beginTransaction();

      $permission->delete();

      DB::commit();

      return response()->json([
        'title' => 'Success',
        'message' => 'Berhasil menghapus permission. Name: ' . $permission->display_name,
        'status' => 'success'
      ], 200);
    } catch (\Exception $ex) {
      DB::rollBack();
      return response()->json([
        'title' => 'Failed',
        'message' => 'Gagal menghapus permission. ' . $ex->getMessage(),
        'status' => 'error'
      ], 500);
    }
  }
}
