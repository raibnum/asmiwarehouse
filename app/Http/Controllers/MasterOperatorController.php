<?php

namespace App\Http\Controllers;

use App\Models\Operator;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MasterOperatorController extends Controller
{
  public function index()
  {
    // if (!Auth::user()->isAble(['mas_operator_view'])) return view('error.403');

    $divisi = DB::table('mas_operators')
      ->selectRaw('distinct divisi')
      ->orderBy('divisi')
      ->pluck('divisi');

    return view('master.operator.index', ['divisi' => $divisi]);
  }

  public function store(Request $request)
  {
    if (!$request->ajax()) return redirect()->route('home');

    // if (!Auth::user()->isAble(['mas_operator_create'])) return response()->json([
    //   'title' => 'Forbidden',
    //   'message' => 'Maaf, Anda tidak memiliki izin untuk akses ini',
    //   'status' => 'warning'
    // ], 403);

    try {
      $data = $request->all();
      $nm_operator = trim($data['nm_operator']) != '' ? trim($data['nm_operator']) : null;
      $divisi = trim($data['divisi']) != '' ? trim($data['divisi']) : null;

      if ($nm_operator == null) throw new \Exception('Nama operator tidak boleh kosong');
      if ($divisi == null) throw new \Exception('Divisi tidak boleh kosong');

      DB::beginTransaction();

      Operator::create([
        'nm_operator' => $data['nm_operator'],
        'divisi' => $data['divisi']
      ]);

      DB::commit();

      $divisi = Operator::pluck('divisi')->unique()->values()->all();

      return response()->json([
        'title' => 'Success',
        'message' => 'Berhasil menyimpan operator. Nama: ' . $data['nm_operator'],
        'status' => 'success',
        'data' => [
          'divisi' => $divisi
        ]
      ], 200);
    } catch (\Exception $ex) {
      DB::rollBack();
      return response()->json([
        'title' => 'Failed',
        'message' => 'Gagal menyimpan operator. ' . $ex->getMessage(),
        'status' => 'error'
      ], 500);
    }
  }

  public function update(Request $request, $id)
  {
    if (!$request->ajax()) return redirect()->route('home');

    // if (!Auth::user()->isAble(['mas_operator_create'])) return response()->json([
    //   'title' => 'Forbidden',
    //   'message' => 'Maaf, Anda tidak memiliki izin untuk akses ini',
    //   'status' => 'warning'
    // ], 403);

    try {
      $data = $request->all();
      $id = base64_decode($id);
      $operator = Operator::find($id);

      $nm_operator = trim($data['nm_operator']) != '' ? trim($data['nm_operator']) : null;
      $divisi = trim($data['divisi']) != '' ? trim($data['divisi']) : null;

      if ($nm_operator == null) throw new \Exception('Nama operator tidak boleh kosong');
      if ($divisi == null) throw new \Exception('Divisi tidak boleh kosong');

      DB::beginTransaction();

      $operator->update([
        'nm_operator' => $data['nm_operator'],
        'divisi' => $data['divisi']
      ]);

      DB::commit();

      $divisi = Operator::pluck('divisi')->unique()->values()->all();

      return response()->json([
        'title' => 'Success',
        'message' => 'Berhasil mengubah operator. Nama: ' . $data['nm_operator'],
        'status' => 'success',
        'data' => [
          'divisi' => $divisi
        ]
      ], 200);
    } catch (\Exception $ex) {
      DB::rollBack();
      return response()->json([
        'title' => 'Failed',
        'message' => 'Gagal mengubah operator. ' . $ex->getMessage(),
        'status' => 'error'
      ], 500);
    }
  }

  public function destroy(Request $request, $id)
  {
    if (!$request->ajax()) return redirect()->route('home');

    // if (!Auth::user()->isAble(['mas_operator_create'])) return response()->json([
    //   'title' => 'Forbidden',
    //   'message' => 'Maaf, Anda tidak memiliki izin untuk akses ini',
    //   'status' => 'warning'
    // ], 403);

    try {
      $id = base64_decode($id);
      $operator = Operator::find($id);

      DB::beginTransaction();

      $operator->delete();

      DB::commit();

      $divisi = Operator::pluck('divisi')->unique()->values()->all();

      return response()->json([
        'title' => 'Success',
        'message' => 'Berhasil menghapus operator. Nama: ' . $operator->nm_operator,
        'status' => 'success',
        'data' => [
          'divisi' => $divisi
        ]
      ], 200);
    } catch (\Exception $ex) {
      DB::rollBack();
      return response()->json([
        'title' => 'Failed',
        'message' => 'Gagal menghapus operator. ' . $ex->getMessage(),
        'status' => 'error'
      ], 500);
    }
  }
}
