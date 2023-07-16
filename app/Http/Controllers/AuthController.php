<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
  /**
   * LOGIN
   */
  public function loginIndex()
  {
    return view('auth.login');
  }

  public function login(Request $request)
  {
    $credentials = $request->validate([
      'username' => ['required'],
      'password' => ['required'],
    ]);

    $remember = $request->remember ? true : false;

    if (Auth::attempt($credentials, $remember)) {
      $request->session()->regenerate();

      return redirect()->intended('/');
    }

    return back()->withErrors(['login' => 'Gagal melakukan login'])->onlyInput('username');
  }

  public function logout(Request $request)
  {
    Auth::logout();

    $request->session()->invalidate();

    $request->session()->regenerateToken();

    return redirect()->route('loginIndex');
  }
}
