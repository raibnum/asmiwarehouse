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
    // if (!Auth::user()->isAble(['admin_user_view', 'admin_user_create])) return view('error.403');

    return view('admin.user.index');
  }

  public function create()
  {
    // if (!Auth::user()->isAble(['admin_user_create'])) return view('error.403');

    $roles = Role::all();

    return view('admin.user.create', ['roles' => $roles]);
  }

  public function store(Request $request)
  {
    // if (!Auth::user()->isAble(['admin_user_create'])) return view('error.403');

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
