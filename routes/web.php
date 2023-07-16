<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DatatableController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MasterOperatorController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PinjamToolController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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
    Route::get('operator/dashboard', [MasterOperatorController::class, 'dashboard'])->name('operator.dashboard');
  });

  // transaksi
  Route::resource('pinjtool', PinjamToolController::class)->only(['index', 'store', 'update', 'destroy']);

  // datatable
  Route::prefix('datatable')->group(function () {
    Route::get('operator', [DatatableController::class, 'operator'])->name('datatable.operator');
    Route::get('permission', [DatatableController::class, 'permission'])->name('datatable.permission');
    Route::get('role', [DatatableController::class, 'role'])->name('datatable.role');
    Route::get('user', [DatatableController::class, 'user'])->name('datatable.user');
  });
});
