<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DatatableController;
use App\Http\Controllers\MasterToolController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PinjamToolController;
use App\Http\Controllers\MasterOperatorController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/* START ROUTE */

/* AUTH */

Route::group(['middleware' => ['guest']], function () {
  Route::get('login', [AuthController::class, 'loginIndex'])->name('loginIndex');
  Route::post('login', [AuthController::class, 'login'])->name('login');

  Route::get('register', [AuthController::class, 'registerIndex'])->name('registerIndex');
  Route::post('register', [AuthController::class, 'register'])->name('register');
});

Route::group(['middleware' => ['auth']], function () {
  Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});

/* HOME */
Route::group(['middleware' => ['auth']], function () {
  Route::get('/', [HomeController::class, 'index'])->name('home');
});

Route::group(['middleware' => ['guest']], function () {
  //
});

/* ADMIN */
Route::group(['middleware' => ['auth']], function () {
  Route::prefix('admin')->group(function () {
    Route::resource('user', UserController::class)->only(['index', 'create', 'store', 'edit', 'udpate', 'destroy']);
    Route::resource('role', RoleController::class)->only(['index', 'create', 'store', 'edit', 'udpate', 'destroy']);
    Route::resource('permission', PermissionController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
  });
});

/* ALL ROUTE */
Route::group(['middleware' => ['auth']], function () {
  // master
  Route::prefix('master')->group(function () {
    Route::resource('operator', MasterOperatorController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('tool', MasterToolController::class)->only(['index', 'store', 'update', 'destroy']);
  });

  Route::prefix('transaksi')->group(function () {
    // transaksi
    Route::resource('pinjtool', PinjamToolController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::get('pinjtool/dashboard', [PinjamToolController::class, 'dashboard'])->name('pinjtool.dashboard');
  });

  // datatable
  Route::prefix('datatable')->group(function () {
    Route::get('operator', [DatatableController::class, 'operator'])->name('datatable.operator');
    Route::get('permission', [DatatableController::class, 'permission'])->name('datatable.permission');
    Route::get('role', [DatatableController::class, 'role'])->name('datatable.role');
    Route::get('tool', [DatatableController::class, 'tool'])->name('datatable.tool');
    Route::get('user', [DatatableController::class, 'user'])->name('datatable.user');
  });

  // redirect
  Route::get('redirect', function (Request $request) {
    $target = $request->target ?? 'home';

    $status = $request->status ?? 'success';
    $message = $request->message ?? 'Aksi berhasil dilakukan';

    $request->session()->flash('alert-status', $status);
    $request->session()->flash('alert-message', $message);

    return redirect()->route($target);
  })->name('redirect');
});
