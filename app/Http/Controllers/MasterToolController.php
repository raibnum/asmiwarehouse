<?php

namespace App\Http\Controllers;

use App\Models\Tool;
use App\Models\JenisTool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MasterToolController extends Controller
{
	public function index()
	{
		if (!Auth::user()->isAble(['whs-tool-view', 'whs-tool-create'])) return view('error.403');

		$jenis_tool = JenisTool::all();

		return view('master.tool.index', ['jenis_tool' => $jenis_tool]);
	}

	public function store(Request $request)
	{
		if (!$request->ajax()) return redirect()->route('home');

		if (!Auth::user()->isAble(['whs-tool-create'])) return response()->json([
			'title' => 'Forbidden',
			'message' => 'Maaf, Anda tidak memiliki izin untuk akses ini',
			'status' => 'warning'
		], 403);

		try {
			$data = $request->all();
			$kd_tool = trim($data['kd_tool']) != '' ? trim($data['kd_tool']) : null;
			$nm_tool = trim($data['nm_tool']) != '' ? trim($data['nm_tool']) : null;
			$kd_jenis = trim($data['kd_jenis']) != '' ? trim($data['kd_jenis']) : null;
			$stok = trim($data['stok']) != '' ? trim($data['stok']) : 0;
			$stok_minimal = trim($data['stok_minimal']) != '' ? trim($data['stok_minimal']) : 0;
			$harga = trim($data['harga']) != '' ? trim($data['harga']) : 0;
			$st_aktif = trim($data['st_aktif']) != '' ? trim($data['st_aktif']) : true;
			if ($st_aktif == 'T') {
				$st_aktif = true;
			} else if ($st_aktif == 'F') {
				$st_aktif = false;
			}

			$st_sekali_pakai = trim($data['st_sekali_pakai']) != '' ? trim($data['st_sekali_pakai']) : false;
			if ($st_sekali_pakai == 'T') {
				$st_sekali_pakai = true;
			} else if ($st_sekali_pakai == 'F') {
				$st_sekali_pakai = false;
			}

			if ($kd_tool == null || $nm_tool == null || $kd_jenis == null) throw new \Exception('Kode, nama, dan jenis tidak boleh kosong');

			DB::beginTransaction();

			$checkKdJenis = JenisTool::find($kd_jenis);
			if ($checkKdJenis == null) {
				JenisTool::create([
					'kd_jenis' => $kd_jenis,
					'nm_jenis' => $kd_jenis
				]);
			}

			Tool::create([
				'kd_tool' => $kd_tool,
				'nm_tool' => $nm_tool,
				'kd_jenis' => $kd_jenis,
				'stok' => $stok,
				'stok_minimal' => $stok_minimal,
				'harga' => $harga,
				'st_aktif' => $st_aktif,
				'st_sekali_pakai' => $st_aktif,
			]);

			DB::commit();

			$jenis = JenisTool::orderBy('kd_jenis')->get()->all();

			return response()->json([
				'title' => 'Success',
				'message' => 'Berhasil menyimpan tool. Kode: ' . $kd_tool,
				'status' => 'success',
				'data' => [
					'jenis' => $jenis
				]
			], 200);
		} catch (\Exception $ex) {
			DB::rollBack();
			return response()->json([
				'title' => 'Failed',
				'message' => 'Gagal menyimpan tool. ' . $ex->getMessage(),
				'status' => 'error'
			], 500);
		}
	}

	public function update(Request $request, $kd_tool)
	{
		if (!$request->ajax()) return redirect()->route('home');

		if (!Auth::user()->isAble(['whs-tool-create'])) return response()->json([
			'title' => 'Forbidden',
			'message' => 'Maaf, Anda tidak memiliki izin untuk akses ini',
			'status' => 'warning'
		], 403);

		try {
			$data = $request->all();

			$kd_tool = base64_decode($kd_tool);
			$tool = Tool::find($kd_tool);

			$nm_tool = trim($data['nm_tool']) != '' ? trim($data['nm_tool']) : null;
			$kd_jenis = trim($data['kd_jenis']) != '' ? trim($data['kd_jenis']) : null;
			$stok = trim($data['stok']) != '' ? trim($data['stok']) : 0;
			$stok_minimal = trim($data['stok_minimal']) != '' ? trim($data['stok_minimal']) : 0;
			$harga = trim($data['harga']) != '' ? trim($data['harga']) : 0;
			$st_aktif = trim($data['st_aktif']) != '' ? trim($data['st_aktif']) : true;
			if ($st_aktif == 'T') {
				$st_aktif = true;
			} else if ($st_aktif == 'F') {
				$st_aktif = false;
			}
			$st_sekali_pakai = trim($data['st_sekali_pakai']) != '' ? trim($data['st_sekali_pakai']) : true;
			if ($st_sekali_pakai == 'T') {
				$st_sekali_pakai = true;
			} else if ($st_sekali_pakai == 'F') {
				$st_sekali_pakai = false;
			}

			if ($nm_tool == null || $kd_jenis == null) throw new \Exception('Nama dan jenis tidak boleh kosong');

			DB::beginTransaction();

			$checkKdJenis = JenisTool::find($kd_jenis);
			if ($checkKdJenis == null) {
				JenisTool::create([
					'kd_jenis' => $kd_jenis,
					'nm_jenis' => $kd_jenis
				]);
			}

			$tool->update([
				'nm_tool' => $nm_tool,
				'kd_jenis' => $kd_jenis,
				'stok' => $stok,
				'stok_minimal' => $stok_minimal,
				'harga' => $harga,
				'st_aktif' => $st_aktif,
				'st_sekali_pakai' => $st_sekali_pakai,
			]);

			DB::commit();

			$jenis = JenisTool::orderBy('kd_jenis')->orderBy('nm_jenis')->get()->all();

			return response()->json([
				'title' => 'Success',
				'message' => 'Berhasil mengubah tool. Kode: ' . $kd_tool,
				'status' => 'success',
				'data' => [
					'jenis' => $jenis
				]
			], 200);
		} catch (\Exception $ex) {
			DB::rollBack();
			return response()->json([
				'title' => 'Failed',
				'message' => 'Gagal mengubah tool. ' . $ex->getMessage(),
				'status' => 'error'
			], 500);
		}
	}

	public function destroy(Request $request, $kd_tool)
	{
		if (!$request->ajax()) return redirect()->route('home');

		if (!Auth::user()->isAble(['whs-tool-create'])) return response()->json([
			'title' => 'Forbidden',
			'message' => 'Maaf, Anda tidak memiliki izin untuk akses ini',
			'status' => 'warning'
		], 403);

		try {
			$kd_tool = base64_decode($kd_tool);
			$tool = Tool::find($kd_tool);

			DB::beginTransaction();

			$tool->delete();

			DB::commit();
			return response()->json([
				'title' => 'Success',
				'message' => 'Berhasil menghapus tool. Kode: ' . $kd_tool,
				'status' => 'success'
			], 200);
		} catch (\Exception $ex) {
			DB::rollBack();
			return response()->json([
				'title' => 'Failed',
				'message' => 'Gagal menghapus tool. ' . $ex->getMessage(),
				'status' => 'error'
			], 500);
		}
	}
}
