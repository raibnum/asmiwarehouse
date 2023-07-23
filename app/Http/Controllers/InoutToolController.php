<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Tool;
use App\Models\Operator;
use App\Models\InoutTool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class InoutToolController extends Controller
{
  public function index()
  {
    if (!Auth::user()->isAble(['whs-inout-tool*'])) return view('error.403');

    $opt_operator = Operator::orderBy('divisi')->orderBy('nm_operator')->get()->all();
    $opt_tool = Tool::aktif()->with('jenisTool')->get()->all();

    return view('transaksi.inout_tool.index', [
      'opt_operator' => $opt_operator,
      'opt_tool' => $opt_tool
    ]);
  }

  public function dashboard(Request $request)
  {
    if (!$request->ajax()) return redirect()->route('home');

    $tgl_awal = $request->tgl_awal;
    $tgl_akhir = $request->tgl_akhir;
    $status = $request->status;

    $inout = InoutTool::periode($tgl_awal, $tgl_akhir)->status($status)->with(['opr', 'tool', 'tool.jenisTool']);

    return DataTables::eloquent($inout)
      ->addColumn('colored_status', function ($inout) {
        $context = 'primary';
        if ($inout->status == 'MASUK') {
          $context = 'success';
        } else if ($inout->status == 'KELUAR') {
          $context = 'danger';
        }

        return '<span class="font-weight-bold text-' . $context . '">' . $inout->status . '</span>';
      })
      ->addColumn('action', function ($inout) {
        if (!Auth::user()->isAble(['whs-inout-tool-create'])) return '';

        return '
          <button type="button" class="btn btn-xs btn-success" onclick="popupModalEdit(this);" data-toggle="tooltip" data-placement="top" title="Edit">
            <i class="fas fa-edit"></i>
          </button>
          <button type="button" class="btn btn-xs btn-danger" onclick="deleteInoutTool(\'' . $inout->id . '\');" data-toggle="tooltip" data-placement="Hapus">
            <i class="fas fa-trash-alt"></i>
          </button>
        ';
      })
      ->filterColumn('tgl', function ($query, $keyword) {
        $query->where(DB::raw("date_format(tgl, '%d-%m-%Y')"), 'like', "%$keyword%");
      })
      ->filterColumn('colored_status', function ($query, $keyword) {
        $query->where('status', 'like', "%$keyword%");
      })
      ->rawColumns(['action', 'colored_status'])
      ->toJson();
  }

  public function store(Request $request)
  {
    if (!$request->ajax()) return redirect()->route('home');

    if (!Auth::user()->isAble(['whs-inout-tool-create'])) return response()->json([
      'title' => 'Forbidden',
      'message' => 'Maaf, Anda tidak memiliki izin untuk akses ini',
      'status' => 'warning'
    ], 403);

    try {
      $data = $request->all();
      $kd_tool = trim($data['kd_tool']) != '' ? trim($data['kd_tool']) : null;
      $operator = trim($data['operator']) != '' ? trim($data['operator']) : null;
      $status = trim($data['status']) != '' ? trim($data['status']) : null;
      $qty = trim($data['qty']) != '' ? trim($data['qty']) : null;
      $harga = trim($data['harga']) != '' ? trim($data['harga']) : null;

      if (in_array(null, [$kd_tool, $operator, $status, $qty, $harga])) throw new \Exception('Kode, operator, status, dan qty tidak boleh kosong');

      DB::beginTransaction();

      InoutTool::create([
        'kd_tool' => $kd_tool,
        'tgl' => Carbon::now(),
        'operator' => $operator,
        'status' => $status,
        'qty' => $qty,
        'harga' => $harga
      ]);

      $stok = $status == 'MASUK' ? +$qty : -$qty;

      Tool::find($kd_tool)
        ->update(['stok' => DB::raw("stok + ($stok)")]);

      DB::commit();
      return response()->json([
        'title' => 'Success',
        'message' => 'Berhasil menyimpan data',
        'status' => 'success'
      ], 200);
    } catch (\Exception $ex) {
      DB::rollBack();
      return response()->json([
        'title' => 'Failed',
        'message' => 'Gagal menyimpan data. ' . $ex->getMessage(),
        'status' => 'error'
      ], 500);
    }
  }

  public function update(Request $request, $id)
  {
    if (!$request->ajax()) return redirect()->route('home');

    if (!Auth::user()->isAble(['whs-inout-tool-create'])) return response()->json([
      'title' => 'Forbidden',
      'message' => 'Maaf, Anda tidak memiliki izin untuk akses ini',
      'status' => 'warning'
    ], 403);

    try {
      $id = base64_decode($id);
      $data = $request->all();
      $kd_tool = trim($data['kd_tool']) != '' ? trim($data['kd_tool']) : null;
      $operator = trim($data['operator']) != '' ? trim($data['operator']) : null;
      $status = trim($data['status']) != '' ? trim($data['status']) : null;
      $qty = trim($data['qty']) != '' ? trim($data['qty']) : null;
      $harga = trim($data['harga']) != '' ? trim($data['harga']) : null;

      if (in_array(null, [$kd_tool, $operator, $status, $qty, $harga])) throw new \Exception('Kode, operator, status, dan qty tidak boleh kosong');

      DB::beginTransaction();

      $inout_lama = InoutTool::find($id);
      $qty_lama_inout = $inout_lama->status == 'MASUK' ? -$inout_lama->qty : +$inout_lama->qty;
      $stok_lama_tool = Tool::find($kd_tool)->stok + $qty_lama_inout;

      InoutTool::find($id)
        ->update([
          'kd_tool' => $kd_tool,
          'operator' => $operator,
          'status' => $status,
          'qty' => $qty,
          'harga' => $harga
        ]);

      $qty_baru = $status == 'MASUK' ? +$qty : -$qty;
      Tool::find($kd_tool)->update(['stok' => ($stok_lama_tool + $qty_baru)]);

      DB::commit();
      return response()->json([
        'title' => 'Success',
        'message' => 'Berhasil menyimpan data',
        'status' => 'success'
      ], 200);
    } catch (\Exception $ex) {
      DB::rollBack();
      return response()->json([
        'title' => 'Failed',
        'message' => 'Gagal menyimpan data. ' . $ex->getMessage(),
        'status' => 'error'
      ], 500);
    }
  }

  public function destroy(Request $request, $id)
  {
    if (!$request->ajax()) return redirect()->route('home');

    if (!Auth::user()->isAble(['whs-inout-tool-create'])) return response()->json([
      'title' => 'Forbidden',
      'message' => 'Maaf, Anda tidak memiliki izin untuk akses ini',
      'status' => 'warning'
    ], 403);

    try {
      $id = base64_decode($id);

      DB::beginTransaction();

      $inout_lama = InoutTool::find($id);
      $qty_lama_inout = $inout_lama->status == 'MASUK' ? -$inout_lama->qty : +$inout_lama->qty;
      $stok_lama_tool = Tool::find($inout_lama->kd_tool)->stok + $qty_lama_inout;

      InoutTool::find($id)->delete();

      Tool::find($inout_lama->kd_tool)->update(['stok' => $stok_lama_tool]);

      DB::commit();
      return response()->json([
        'title' => 'Success',
        'message' => 'Berhasil menghapus data',
        'status' => 'success'
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

  public function indexReport()
  {
    if (!Auth::user()->isAble(['whs-inout-tool-report'])) return view('error.403');

    return view('report.inout_tool.index');
  }

  public function pdf($tgl_awal, $tgl_akhir)
  {
    if (!Auth::user()->isAble(['whs-inout-tool-report'])) return view('error.403');

    $tgl_awal = base64_decode($tgl_awal);
    $tgl_akhir = base64_decode($tgl_akhir);

    $inout = InoutTool::periode($tgl_awal, $tgl_akhir)
      ->with(['opr', 'tool', 'tool.jenisTool'])
      ->orderBy(DB::raw("tgl, created_at"))
      ->get()
      ->all();

    $tgl_awal = Carbon::parse($tgl_awal)->format('d/m/Y');
    $tgl_akhir = Carbon::parse($tgl_akhir)->format('d/m/Y');

    $pdf = \PDF::loadView('report.inout_tool.pdf', [
      'inout' => $inout,
      'tgl_awal' => $tgl_awal,
      'tgl_akhir' => $tgl_akhir
    ]);

    return $pdf->setPaper('A4')->stream();
  }
}
