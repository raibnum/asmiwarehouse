<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
  public function index()
  {
    if (!Auth::user()->isAble(['admin-user-view', 'admin-user-create'])) return view('error.403');

    return view('admin.user.index');
  }

  public function create()
  {
    if (!Auth::user()->isAble(['admin-user-create'])) return view('error.403');

    $roles = Role::orderBy('display_name')->get()->all();

    return view('admin.user.create', ['roles' => $roles]);
  }

  public function store(Request $request)
  {
    if (!Auth::user()->isAble(['admin-user-create'])) return view('error.403');

    try {
      $data = $request->all();
      $username = trim($data['username']) != '' ? trim($data['username']) : null;
      $name = trim($data['name']) != '' ? trim($data['name']) : null;
      $email = trim($data['email']) != '' ? trim($data['email']) : null;
      $password = $data['password'] != '' ? $data['password'] : $this->generatePassword();

      DB::beginTransaction();

      User::create([
        'username' => $username,
        'name' => $name,
        'email' => $email,
        'password' => Hash::make($password)
      ]);

      $user = User::where('username', $username)->first();

      $roles = $data['roles'] ?? [];
      foreach ($roles as $role) {
        DB::table('role_user')
          ->insert(['role_id' => $role, 'user_id' => $user->id]);
      }

      DB::commit();

      $request->session()->flash('alert-status', 'success');
      $request->session()->flash('alert-message', 'Berhasil menambah user. Username: ' . $username . ($data['password'] == '' ? '. Password: ' . $password : ''));

      return  redirect()->route('user.index');
    } catch (\Exception $ex) {
      DB::rollBack();

      $request->session()->flash('alert-status', 'danger');
      $request->session()->flash('alert-message', 'Gagal menambah user. ' . $ex->getMessage());

      return  redirect()->route('user.index');
    }
  }

  public function edit($id)
  {
    if (!Auth::user()->isAble(['admin-user-create'])) return view('error.403');

    $id = base64_decode($id);

    $user = User::find($id);
    $roles = Role::roleWithUser($id)->all();

    return view('admin.user.edit', ['id' => $id, 'roles' => $roles, 'user' => $user]);
  }

  public function update(Request $request, $id)
  {
    if (!Auth::user()->isAble(['admin-user-create'])) return view('error.403');

    try {
      $id = base64_decode($id);
      $data = $request->all();
      $username = trim($data['username']) != '' ? trim($data['username']) : null;
      $name = trim($data['name']) != '' ? trim($data['name']) : null;
      $email = trim($data['email']) != '' ? trim($data['email']) : null;

      DB::beginTransaction();

      User::find($id)
        ->update([
          'username' => $username,
          'name' => $name,
          'email' => $email,
        ]);

      $roles = $data['roles'] ?? [];

      $role_user = User::find($id)->roles()->pluck('id')->all();
      foreach ($role_user as $role) {
        if (in_array($role, $roles)) continue;

        DB::table('role_user')
          ->where('role_id', $role)
          ->where('user_id', $id)
          ->delete();
      }

      foreach ($roles as $role) {
        if (in_array($role, $role_user)) continue;

        DB::table('role_user')
          ->insert([
            'role_id' => $role,
            'user_id' => $id
          ]);
      }

      DB::commit();

      $request->session()->flash('alert-status', 'success');
      $request->session()->flash('alert-message', 'Berhasil menambah user. Username: ' . $username);

      return  redirect()->route('user.index');
    } catch (\Exception $ex) {
      DB::rollBack();

      $request->session()->flash('alert-status', 'danger');
      $request->session()->flash('alert-message', 'Gagal menambah user. ' . $ex->getMessage());

      return  redirect()->route('user.index');
    }
  }

  public function destroy(Request $request, $id)
  {
    if (!$request->ajax()) return redirect()->route('home');

    if (!Auth::user()->isAble(['admin-user-create'])) return response()->json([
      'title' => 'Forbidden',
      'message' => 'Maaf, Anda tidak memiliki izin untuk akses ini',
      'status' => 'warning'
    ], 403);

    try {
      $id = base64_decode($id);

      DB::beginTransaction();

      DB::table('role_user')
        ->where('user_id', $id)
        ->delete();

      $user = User::find($id);
      $user->delete();

      DB::commit();
      return response()->json([
        'title' => 'Success',
        'message' => 'User berhasil dihapus. Username: ' . $user->username,
        'status' => 'success'
      ], 200);
    } catch (\Exception $ex) {
      DB::rollBack();
      return response()->json([
        'title' => 'Failed',
        'message' => 'User gagal dihapus. ' . $ex->getMessage(),
        'status' => 'error'
      ], 500);
    }
  }

  /**
   * utility
   */
  private function generatePassword($length = 5)
  {
    $char = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    $password = '';
    for ($i = 0; $i < $length; $i++) {
      $index = rand(0, strlen($char) - 1);
      $password .= $char[$index];
    }

    return $password;
  }
}
