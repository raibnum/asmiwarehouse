<?php

namespace App\Http\Controllers;

use App\Models\Tool;
use App\Models\Operator;
use App\Models\PinjamTool1;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;

class PinjamToolController extends Controller
{
  public function index()
  {
    if (!Auth::user()->isAble(['whs-pinj-tool*'])) return view('error.403');

    $kd_pinj = PinjamTool1::newKodePinjam();
    $opt_operator = Operator::orderBy('divisi')->orderBy('nm_operator')->get()->all();
    $opt_status = PinjamTool1::status();
    $opt_tool = Tool::aktif()->with('jenis_tool')->get()->all();

    return view('transaksi.pinjam_tool.index', [
      'kd_pinj' => $kd_pinj,
      'opt_operator' => $opt_operator,
      'opt_status' => $opt_status,
      'opt_tool' => $opt_tool
    ]);
  }

  public function dashboard(Request $request)
  {
    $tgl_awal = $request->tgl_awal;
    $tgl_akhir = $request->tgl_akhir;
    $status = $request->status;

    $pinjtool = PinjamTool1::periode($tgl_awal, $tgl_akhir)->status($status)->get()->all();
    return DataTables::of($pinjtool)
      ->addColumn('status_text', function ($pinjtool) {
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

        return '<span class="text-' . $level . '">' . $text . '</span>';
      })
      ->rawColumns(['status_text'])
      ->make(true);
  }
}
