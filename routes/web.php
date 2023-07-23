<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PpToolController;
use App\Http\Controllers\DatatableController;
use App\Http\Controllers\InoutToolController;
use App\Http\Controllers\MasterToolController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PinjamToolController;
use App\Http\Controllers\MasterOperatorController;
use App\Models\InoutTool;

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

    Route::resource('inouttool', InoutToolController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::get('inouttool/dashboard', [InoutToolController::class, 'dashboard'])->name('inouttool.dashboard');

    Route::resource('pptool', PpToolController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::get('pptool/dashboard', [PpToolController::class, 'dashboard'])->name('pptool.dashboard');
    Route::delete('pptool/item/{no_pp}/{kd_tool}', [PpToolController::class, 'destroyItem'])->name('pptool.destroyItem');

    Route::get('pptol/approve', [PpToolController::class, 'indexApprove'])->name('pptool.indexApprove');
    Route::patch('pptool/{no_pp}/approve', [PpToolController::class, 'approve'])->name('pptool.approve');
    Route::get('pptool/prch', [PpToolController::class, 'indexPrch'])->name('pptool.indexPrch');
    Route::post('pptool/{no_pp}/prch', [PpToolController::class, 'prch'])->name('pptool.prch');
    Route::patch('pptol/{no_pp}/receive', [PpToolController::class, 'receive'])->name('pptool.receive');
  });

  Route::prefix('report')->group(function () {
    Route::get('inouttool', [InoutToolController::class, 'indexReport'])->name('inouttool.indexReport');
    Route::get('inouttool/pdf/{tgl_awal}/{tgl_akhir}', [InoutToolController::class, 'pdf'])->name('inouttool.pdf');
  });

  // datatable
  Route::prefix('datatable')->group(function () {
    Route::get('operator', [DatatableController::class, 'operator'])->name('datatable.operator');
    Route::get('permission', [DatatableController::class, 'permission'])->name('datatable.permission');
    Route::get('role', [DatatableController::class, 'role'])->name('datatable.role');
    Route::get('tool', [DatatableController::class, 'tool'])->name('datatable.tool');
    Route::get('user', [DatatableController::class, 'user'])->name('datatable.user');
  });
});
