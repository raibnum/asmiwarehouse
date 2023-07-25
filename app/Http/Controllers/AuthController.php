<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\ResetPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

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

    return redirect()->route('login.index');
  }

  /**
   * REGISTER
   */
  public function registerIndex()
  {
    return view('auth.register');
  }

  public function register(Request $request)
  {
    $credentials = $request->validate([
      'username' => ['required', 'max:20', 'unique:users,username'],
      'name' => ['required', 'max:150'],
      'email' => ['required', 'unique:users,email'],
      'password' => ['required', 'min:3'],
    ]);

    if ($credentials) {
      User::create([
        'username' => trim($request->username),
        'name' => trim($request->name),
        'email' => trim($request->email),
        'password' => Hash::make($request->password)
      ]);

      Session::flash('flash-notification', [
        'level' => 'success',
        'message' => 'Register berhasil silahkan login'
      ]);

      return redirect()->route('login.index');
    }

    return back();
  }

  /**
   * Forget Password
   */
  public function forgetPasswordIndex()
  {
    return view('auth.forget-password');
  }

  public function forgetPassword(Request $request)
  {
    $validate = $request->validate(['email' => ['required', 'email', 'exists:users,email']]);

    if ($validate) {
      try {
        $email = trim($request->email);
        $user = User::where('email', $email)->first();

        $token = $user->generateTokenForgetPassword();

        Mail::to($user->email)
          ->send(new ResetPassword($token, $user->email));

        Session::flash('flash_notification', [
          'level' => 'success',
          'message' => 'Berhasil mengirim link ke email Anda'
        ]);

        return redirect()->route('login.index');
      } catch (\Exception $ex) {
        return back()->withErrors('email', 'Gagal mengirim email')->withInput();
      }
    }

    return back()->withErrors(['forget_password' => 'Gagal'])->withInput();
  }

  /**
   * Reset Password
   */
  function resetPasswordIndex(Request $request, $token)
  {
    $email = $request->email ?? '';
    return view('auth.reset-password', ['email' => $email, 'token' => $token]);
  }

  function resetPassword(Request $request)
  {
    $errors = [];

    $request->validate([
      'token-reset' => ['required', 'exists:password_reset_tokens,token'],
      'email' => ['required', 'exists:users,email'],
      'password' => ['required', 'confirmed']
    ]);

    $user = User::where('email', $request->email)->first();

    $st_token = $user->updatePassword($request->password, $request->{'token-reset'});

    if ($st_token == true) {
      Session::flash('flash_notification', [
        'level' => 'success',
        'message' => 'Berhasil mengganti password'
      ]);
      return redirect()->route('login.index');
    }

    $erors['status'] = $st_token;

    return back()->withErrors($errors);
  }
}
