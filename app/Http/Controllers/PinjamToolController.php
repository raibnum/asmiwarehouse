<?php

namespace App\Http\Controllers;

use App\Models\InoutTool;
use Carbon\Carbon;
use App\Models\Tool;
use App\Models\Operator;
use App\Models\PinjamTool1;
use App\Models\PinjamTool2;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class PinjamToolController extends Controller
{
  public function index()
  {
    if (!Auth::user()->isAble(['whs-pinj-tool*'])) return view('error.403');

    $kd_pinj = PinjamTool1::newKodePinjam();
    $opt_operator = Operator::orderBy('divisi')->orderBy('nm_operator')->get()->all();
    $opt_status = PinjamTool1::status();
    $opt_tool = Tool::aktif()->with('jenisTool')->get()->all();

    return view('transaksi.pinjam_tool.index', [
      'kd_pinj' => $kd_pinj,
      'opt_operator' => $opt_operator,
      'opt_status' => $opt_status,
      'opt_tool' => $opt_tool
    ]);
  }

  public function dashboard(Request $request)
  {
    if (!$request->ajax()) return redirect()->route('home');

    $tgl_awal = $request->tgl_awal;
    $tgl_akhir = $request->tgl_akhir;
    $status = $request->status;

    $pinjtool = PinjamTool1::periode($tgl_awal, $tgl_akhir)->status($status)->with(['opr',  'pinjamTool2s', 'pinjamTool2s.tool', 'pinjamTool2s.tool.jenisTool'])->orderBy('tgl', 'desc');

    return DataTables::eloquent($pinjtool)
      ->editColumn('status', function ($pinjtool) {
        $level = 'danger';
        $status = $pinjtool->status;
        $text = $pinjtool->getStatus();

        if ($status == 0) {
          $level = 'danger';
        } else if ($status == 1) {
          $level = 'warning';
        } else if ($status == 2) {
          $level = 'success';
        }

        return '<span class="font-weight-bold text-' . $level . '">' . $text . '</span>';
      })
      ->addColumn('action', function ($pinjtool) {
        $create = Auth::user()->isAble(['whs-pinj-tool-create']);

        $action = '
          <button type="button" class="btn btn-xs btn-info" data-toggle="tooltip" data-placement="top" title="Detail" onclick="popupModalDetail(this);">
            <i class="fas fa-info-circle"></i>
          </button>
        ';

        if ($create) {
          if ($pinjtool->status < 2) {
            $action .= '
            <button type="button" class="btn btn-xs btn-success" onclick="popupModalEdit(this);" data-toggle="tooltip" data-placement="top" title="Input Kembali">
              <i class="fas fa-exchange-alt"></i>
            </button>
          ';
          }

          if ($pinjtool->status == 0) {
            $action .= '
            <button type="button" class="btn btn-xs btn-danger" onclick="deletePinjamTool(\'' . $pinjtool->kd_pinj . '\');" data-toggle="tooltip" data-placement="top" title="Hapus">
              <i class="fas fa-trash-alt"></i>
            </button>
          ';
          }
        }

        return $action;
      })
      ->filterColumn('tgl', function ($query, $keyword) {
        $query->where(DB::raw("date_format(tgl, '%d-%m-%Y %H:%i')"), 'like', ["%$keyword%"]);
      })
      ->rawColumns(['action', 'status'])
      ->toJson();
  }

  public function store(Request $request)
  {
    if (!$request->ajax()) return redirect()->route('home');

    if (!Auth::user()->isAble(['whs-pinj-tool-create'])) return response()->json([
      'title' => 'Forbidden',
      'message' => 'Maaf, Anda tidak memiliki izin untuk akses ini',
      'status' => 'warning'
    ], 403);

    try {
      $data = $request->all();
      $kd_pinj = trim($data['kd_pinj']) != '' ? trim($data['kd_pinj']) : null;
      $tgl = trim($data['tgl']) != '' ? trim($data['tgl']) : Carbon::now()->format('Y-m-d');
      $jam = trim($data['jam']) != '' ? trim($data['jam']) : Carbon::now()->format('H:i:s');
      $operator = trim($data['operator']) != '' ? trim($data['operator']) : null;

      $tgl = $tgl . ' ' . $jam;

      if ($kd_pinj == null || $operator == null) throw new \Exception('Kode dan operator tidak boleh kosong');

      DB::beginTransaction();

      PinjamTool1::create([
        'kd_pinj' => $kd_pinj,
        'tgl' => $tgl,
        'operator' => $operator,
        'status' => 0
      ]);

      $kd_tool = $data['kd_tool'] ?? [];
      foreach ($kd_tool as $i => $kode) {
        $kode = trim($kode) != '' ? trim($kode) : null;
        $qty = trim($data['qty_tool'][$i]) != '' ? trim($data['qty_tool'][$i]) : null;

        if ($kode == null) continue;

        PinjamTool2::create([
          'kd_pinj' => $kd_pinj,
          'kd_tool' => $kode,
          'qty' => $qty
        ]);
      }

      DB::commit();

      $kd_pinj_baru = PinjamTool1::newKodePinjam();
      $opt_tool = Tool::aktif()->with('jenisTool')->get()->all();

      return response()->json([
        'title' => 'Success',
        'message' => 'Berhasil menyimpan transaksi. Kode: ' . $kd_pinj,
        'status' => 'success',
        'data' => [
          'kd_pinj_baru' => $kd_pinj_baru,
          'opt_tool' => $opt_tool
        ]
      ], 200);
    } catch (\Exception $ex) {
      DB::rollBack();
      return response()->json([
        'title' => 'Failed',
        'message' => 'Gagal menyimpan transaksi. ' . $ex->getMessage(),
        'status' => 'error'
      ], 500);
    }
  }

  public function update(Request $request, $kd_pinj)
  {
    if (!$request->ajax()) return redirect()->route('home');

    if (!Auth::user()->isAble(['whs-pinj-tool-create'])) return response()->json([
      'title' => 'Forbidden',
      'message' => 'Maaf, Anda tidak memiliki izin untuk akses ini',
      'status' => 'warning'
    ], 403);


    try {
      $kd_pinj = base64_decode($kd_pinj);
      $data = $request->all();

      DB::beginTransaction();

      $st_kembali = $data['st_kembali'] ?? [];
      foreach ($st_kembali as $i => $stk) {
        $kd_tool = $data['kd_tool'][$i];
        $tgl = Carbon::now();
        $qty = trim($data['qty_tool'][$i]) != '' ? trim($data['qty_tool'][$i]) : null;

        if ($stk != 'T') continue;

        PinjamTool2::where('kd_pinj', $kd_pinj)
          ->where('kd_tool', $kd_tool)
          ->update([
            'tgl_kembali' => $tgl
          ]);

        $tool = Tool::find($kd_tool);

        if ($tool->st_sekali_pakai == true) {
          $tool->update(['stok' => DB::raw("stok - $qty")]);
          $pinjam1 = PinjamTool1::find($kd_pinj);

          InoutTool::create([
            'kd_tool' => $kd_tool,
            'tgl' => $tgl,
            'operator' => $pinjam1->operator,
            'status' => 'KELUAR',
            'qty' => $qty,
            'harga' => $tool->first()->harga
          ]);
        }
      }

      PinjamTool1::find($kd_pinj)->udpateStatus();

      DB::commit();

      $opt_tool = Tool::aktif()->with('jenisTool')->get()->all();

      return response()->json([
        'title' => 'Success',
        'message' => 'Berhasil menyimpan transaksi. Kode: ' . $kd_pinj,
        'status' => 'success',
        'data' => [
          'opt_tool' => $opt_tool
        ]
      ], 200);
    } catch (\Exception $ex) {
      DB::rollBack();
      return response()->json([
        'title' => 'Failed',
        'message' => 'Gagal menyimpan transaksi. ' . $ex->getMessage(),
        'status' => 'error'
      ], 500);
    }
  }

  public function destroy(Request $request, $kd_pinj)
  {
    if (!$request->ajax()) return redirect()->route('home');

    if (!Auth::user()->isAble(['whs-pinj-tool-create'])) return response()->json([
      'title' => 'Forbidden',
      'message' => 'Maaf, Anda tidak memiliki izin untuk akses ini',
      'status' => 'warning'
    ], 403);

    try {
      $kd_pinj = base64_decode($kd_pinj);

      DB::beginTransaction();

      PinjamTool2::find($kd_pinj)->delete();
      PinjamTool1::find($kd_pinj)->delete();

      DB::commit();

      $kd_pinj_baru = PinjamTool1::newKodePinjam();

      return response()->json([
        'title' => 'Success',
        'message' => 'Berhasil menghapus data. Kode: ' . $kd_pinj,
        'status' => 'success',
        'data' => [
          'kd_pinj_baru' => $kd_pinj_baru
        ]
      ], 200);
    } catch (\Exception $ex) {
      DB::rollBack();
      return response()->json([
        'title' => 'Failed',
        'message' => 'Gagal menghapus data. ' . $ex->getMessage(),
        'status' => 'error'
      ], 500);
    }
  }
}
