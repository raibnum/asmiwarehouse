<?php

namespace App\Http\Controllers;

use App\Models\Tool;
use App\Models\PpTool1;
use App\Models\InoutTool;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
  public function index()
  {
    $compact = [];

    if (Auth::user()->isAble(['whs-inout-tool-view'])) {
      $tool_out = InoutTool::totalKeluar();
      $compact['tool_out'] = $tool_out;

      $tool_in = InoutTool::totalMasuk();
      $compact['tool_in'] = $tool_in;
    }

    if (Auth::user()->isAble(['whs-pp-tool-view'])) {
      $total_pp = PpTool1::totalPp();
      $compact['total_pp'] = $total_pp;
    }

    if (Auth::user()->isAble(['whs-tool-view'])) {
      $total_tool = Tool::totalTool();
      $compact['total_tool'] = $total_tool;
    }

    return view('home.index', $compact);
  }
}
