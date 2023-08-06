<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Tool;
use App\Models\PpTool1;
use App\Models\PpTool2;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Intervention\Image\ImageManagerStatic as Image;

class PpToolController extends Controller
{
	/* INPUT */
	public function index()
	{
		if (!Auth::user()->isAble(['whs-pp-tool*'])) return view('error.403');

		$no_pp = PpTool1::newNoPp();
		$opt_status = PpTool1::status();
		$opt_tool = Tool::aktif()->with('jenisTool')->get()->all();

		return view('transaksi.pp_tool.index', [
			'index' => 'index',
			'no_pp' => $no_pp,
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
		$page = $request->page;

		$pptool = PpTool1::periode($tgl_awal, $tgl_akhir)
			->status($status)
			->with(['ppTool2s', 'ppTool2s.tool', 'ppTool2s.tool.jenisTool'])
			->orderBy(DB::raw("tgl_pp desc, no_pp"), 'desc');

		return DataTables::eloquent($pptool)
			->editColumn('status_pp', function ($pptool) {
				$context = 'primary';
				if ($pptool->status_pp == 'INPUT') {
					$context = 'navy';
				} else if ($pptool->status_pp == 'APPROVE') {
					$context = 'primary';
				} else if ($pptool->status_pp == 'BOUGHT') {
					$context = 'info';
				} else if ($pptool->status_pp == 'RECEIVE') {
					$context = 'success';
				}

				return '<span class="font-weight-bold text-' . $context . '">' . $pptool->status_pp . '</span>';
			})
			->addColumn('action', function ($pptool) use ($page) {
				$input = Auth::user()->isAble(['whs-pp-tool-create']);
				$approve = Auth::user()->isAble(['whs-pp-tool-approve']);
				$prch = Auth::user()->isAble(['prch-pp-tool-submit']);

				$tgl_approve = !is_null($pptool->tgl_approve);
				$submit_prch = !is_null($pptool->submit_prch);
				$tgl_receive = !is_null($pptool->tgl_receive);

				$action = '
					<button type="button" class="btn btn-xs btn-info" data-toggle="tooltip" data-placement="top" title="Detail" onclick="popupModalDetail(this);">
						<i class="fas fa-info-circle"></i>
					</button>
				';

				if (!is_null($pptool->file)) {
					$action .= '
						<button type="button" class="btn btn-xs btn-warning" data-toggle="tooltip" data-placement="top" title="Invoice" onclick="popupModalInvoice(\'' . $pptool->no_pp . '\');">
							<i class="fas fa-receipt"></i>
						</button>
					';
				}

				if ($page == 'INPUT' && $input) {
					if (!$tgl_approve && !$submit_prch && !$tgl_receive) {
						$action .= '
							<button type="button" class="btn btn-xs btn-success" data-toggle="tooltip" data-placement="top" title="Edit" onclick="popupModalEdit(this);">
								<i class="fas fa-edit"></i>
							</button>
							<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Hapus" onclick="deletePpTool(\'' . $pptool->no_pp . '\');">
								<i class="fas fa-trash-alt"></i>
							</button>
						';
					}

					if ($tgl_approve && $submit_prch && !$tgl_receive) {
						$action .= '
							<button type="button" class="btn btn-xs btn-success" data-toggle="tooltip" data-placement="top" title="Receive" onclick="receivePpTool(\'' . $pptool->no_pp . '\');">
								<i class="fas fa-check-double"></i>
							</button>
						';
					}
				}

				if ($page == 'APPROVE' && $approve) {
					if (!$tgl_approve && !$submit_prch && !$tgl_receive)
						$action .= '
							<button type="button" class="btn btn-xs btn-success" data-toggle="tooltip" data-placement="top" title="Approve" onclick="approvePpTool(\'' . $pptool->no_pp . '\');">
								<i class="fas fa-check"></i>
							</button>
						';
				}

				if ($page == 'PRCH' && $prch) {
					if ($tgl_approve && !$submit_prch && !$tgl_receive) {
						$action .= '
							<button type="button" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="top" title="Tandai Dibeli" onclick="prchPpTool(\'' . $pptool->no_pp . '\');">
								<i class="fas fa-file-invoice"></i>
							</button>
						';
					}
				}

				return $action;
			})
			->rawColumns(['action', 'status_pp'])
			->toJson();
	}

	public function store(Request $request)
	{
		if (!$request->ajax()) return redirect()->route('home');

		if (!Auth::user()->isAble(['whs-pp-tool-create'])) return response()->json([
			'title' => 'Forbidden',
			'message' => 'Maaf, Anda tidak memiliki izin untuk akses ini',
			'status' => 'warning'
		], 403);

		try {
			$data = $request->all();
			$no_pp = trim($data['no_pp']) != '' ? trim($data['no_pp']) : null;
			$tgl_pp = trim($data['tgl_pp']) != '' ? trim($data['tgl_pp']) : null;
			$keterangan = trim($data['keterangan']) != '' ? trim($data['keterangan']) : null;

			if (in_array(null, [$no_pp, $tgl_pp])) throw new \Exception('No PP dan tanggal tidak boleh kosong');

			DB::beginTransaction();

			PpTool1::create([
				'no_pp' => $no_pp,
				'tgl_pp' => $tgl_pp,
				'keterangan' => $keterangan
			]);

			$kd_tool = $data['kd_tool'] ?? [];
			foreach ($kd_tool as $i => $kd) {
				$kd = trim($kd) != '' ? trim($kd) : null;
				$qty = trim($data['qty'][$i]) != '' ? trim($data['qty'][$i]) : null;

				if (in_array(null, [$kd, $qty])) throw new \Exception('Kode tool dan qty tidak boleh kosong');

				PpTool2::create([
					'no_pp' => $no_pp,
					'kd_tool' => $kd,
					'qty' => $qty
				]);
			}

			DB::commit();

			$no_pp_baru = PpTool1::newNoPp();

			return response()->json([
				'title' => 'Success',
				'message' => 'Berhasil menyimpan transaksi. No PP: ' . $no_pp,
				'status' => 'success',
				'data' => [
					'no_pp_baru' => $no_pp_baru
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

	public function update(Request $request, $no_pp)
	{
		if (!$request->ajax()) return redirect()->route('home');

		if (!Auth::user()->isAble(['whs-pp-tool-create'])) return response()->json([
			'title' => 'Forbidden',
			'message' => 'Maaf, Anda tidak memiliki izin untuk akses ini',
			'status' => 'warning'
		], 403);

		try {
			$no_pp = base64_decode($no_pp);
			$data = $request->all();
			$keterangan = trim($data['keterangan']) != '' ? trim($data['keterangan']) : null;

			DB::beginTransaction();

			PpTool1::find($no_pp)
				->update([
					'keterangan' => $keterangan
				]);

			$kd_tool = $data['kd_tool'] ?? [];
			foreach ($kd_tool as $i => $kd) {
				$kd = trim($kd) != '' ? trim($kd) : null;
				$qty = trim($data['qty'][$i]) != '' ? trim($data['qty'][$i]) : null;

				if (in_array(null, [$kd, $qty])) throw new \Exception('Kode tool dan qty tidak boleh kosong');

				PpTool2::upsert([
					'no_pp' => $no_pp,
					'kd_tool' => $kd,
					'qty' => $qty
				], ['no_pp', 'kd_tool'], ['qty']);
			}

			DB::commit();
			return response()->json([
				'title' => 'Success',
				'message' => 'Berhasil mengubah transaksi. No PP: ' . $no_pp,
				'status' => 'success'
			], 200);
		} catch (\Exception $ex) {
			DB::rollBack();
			return response()->json([
				'title' => 'Failed',
				'message' => 'Gagal mengubah transaksi. ' . $ex->getMessage(),
				'status' => 'error'
			], 500);
		}
	}

	public function destroy(Request $request, $no_pp)
	{
		if (!$request->ajax()) return redirect()->route('home');

		if (!Auth::user()->isAble(['whs-pp-tool-create'])) return response()->json([
			'title' => 'Forbidden',
			'message' => 'Maaf, Anda tidak memiliki izin untuk akses ini',
			'status' => 'warning'
		], 403);

		try {
			$no_pp = base64_decode($no_pp);

			DB::beginTransaction();

			PpTool2::where('no_pp', $no_pp)->delete();
			PpTool1::find($no_pp)->delete();

			DB::commit();

			$no_pp_baru = PpTool1::newNoPp();

			return response()->json([
				'title' => 'Succes',
				'message' => 'Berhasil menghapus transaksi. No PP: ' . $no_pp,
				'status' => 'success',
				'data' => [
					'no_pp_baru' => $no_pp_baru
				]
			], 200);
		} catch (\Exception $ex) {
			DB::rollBack();
			return response()->json([
				'title' => 'Failed',
				'message' => 'Gagal menghapus transaksi. ' . $ex->getMessage(),
				'status' => 'error'
			], 500);
		}
	}

	public function destroyItem(Request $request, $no_pp, $kd_tool)
	{
		if (!$request->ajax()) return redirect()->route('home');

		if (!Auth::user()->isAble(['whs-pp-tool-create'])) return response()->json([
			'title' => 'Forbidden',
			'message' => 'Maaf, Anda tidak memiliki izin untuk akses ini',
			'status' => 'warning'
		], 403);

		try {
			$no_pp = base64_decode($no_pp);
			$kd_tool = base64_decode($kd_tool);

			DB::beginTransaction();

			PpTool2::where('no_pp', $no_pp)
				->where('kd_tool', $kd_tool)
				->delete();

			DB::commit();
			return response()->json([
				'title' => 'Success',
				'message' => 'Berhasil menghapus item',
				'status' => 'success'
			], 200);
		} catch (\Exception $ex) {
			DB::rollBack();
			return response()->json([
				'title' => 'Failed',
				'message' => 'Gagal menghapus item. ' . $ex->getMessage(),
				'status' => 'error'
			], 500);
		}
	}

	/* APPROVE */
	public function indexApprove()
	{
		if (!Auth::user()->isAble(['whs-pp-tool-approve'])) return view('error.403');

		$opt_status = PpTool1::status();
		$opt_tool = Tool::aktif()->with('jenisTool')->get()->all();

		return view('transaksi.pp_tool.index_approve', [
			'opt_status' => $opt_status,
			'opt_tool' => $opt_tool
		]);
	}

	public function approve(Request $request, $no_pp)
	{
		if (!$request->ajax()) return redirect()->route('home');

		if (!Auth::user()->isAble(['whs-pp-tool-approve'])) return response()->json([
			'title' => 'Forbidden',
			'message' => 'Maaf, Anda tidak memiliki izin untuk akses ini',
			'status' => 'warning'
		], 403);

		try {
			$no_pp = base64_decode($no_pp);

			DB::beginTransaction();

			$tgl_approve = Carbon::now();

			PpTool1::find($no_pp)->update(['tgl_approve' => $tgl_approve]);

			DB::commit();
			return response()->json([
				'title' => 'Success',
				'message' => 'Berhasil meng-approve pp. No PP: ' . $no_pp,
				'status' => 'success'
			], 200);
		} catch (\Exception $ex) {
			DB::rollBack();
			return response()->json([
				'title' => 'Failed',
				'message' => 'Gagal meng-approve pp. ' . $ex->getMessage(),
				'status' => 'error'
			], 500);
		}
	}

	/* PRCH */
	public function indexPrch()
	{
		if (!Auth::user()->isAble(['prch-pp-tool-submit'])) return view('error.403');

		$opt_status = PpTool1::status();
		$opt_tool = Tool::aktif()->with('jenisTool')->get()->all();

		return view('transaksi.pp_tool.index_prch', [
			'opt_status' => $opt_status,
			'opt_tool' => $opt_tool
		]);
	}

	public function prch(Request $request, $no_pp)
	{
		if (!$request->ajax()) return redirect()->route('home');

		if (!Auth::user()->isAble(['prch-pp-tool-submit'])) return response()->json([
			'title' => 'Forbidden',
			'message' => 'Maaf, Anda tidak memiliki izin untuk akses ini',
			'status' => 'warning'
		], 403);

		try {
			$no_pp = base64_decode($no_pp);
			$path = PpTool1::fileUploadPath();
			$name = null;

			DB::beginTransaction();

			if ($request->hasFile('invoice')) {
				try {
					$file = $request->file('invoice');
					$ext = $file->getClientOriginalExtension();
					$name = $no_pp . '-invoice.' . $ext;

					if (in_array($ext, ['jpeg', 'jpg', 'png'])) {
						$img = Image::make($file->getRealPath());
						$persen = 90;

						if ($img->filesize() / 1024 > 1024) {
							$persen = persenCompress($img->filesize());
						}

						$img->save($path . $name, $persen);
					} else {
						$file->move($path, $name);
					}
				} catch (\Exception $ex) {
					throw new \Exception('Gagal mengupload invoice');
				}
			}

			$submit_prch = Carbon::now();
			PpTool1::find($no_pp)->update([
				'submit_prch' => $submit_prch,
				'file' => $name
			]);

			DB::commit();
			return response()->json([
				'title' => 'Success',
				'message' => 'Berhasil menandai sudah dibeli. No PP: ' . $no_pp,
				'status' => 'success'
			], 200);
		} catch (\Exception $ex) {
			DB::rollBack();
			return response()->json([
				'title' => 'Failed',
				'message' => 'Gagal menandai sudah dibeli. ' . $ex->getMessage(),
				'status' => 'error'
			], 500);
		}
	}

	/* RECEIVE */
	public function receive(Request $request, $no_pp)
	{
		if (!$request->ajax()) return redirect()->route('home');

		if (!Auth::user()->isAble(['whs-pp-tool-create'])) return response()->json([
			'title' => 'Forbidden',
			'message' => 'Maaf, Anda tidak memiliki izin untuk akses ini',
			'status' => 'warning'
		], 403);

		try {
			$no_pp = base64_decode($no_pp);

			DB::beginTransaction();

			$tgl_receive = Carbon::now();
			PpTool1::find($no_pp)->update(['tgl_receive' => $tgl_receive]);

			DB::commit();
			return response()->json([
				'title' => 'Success',
				'message' => 'Berhasil menandai receive. No PP: ' . $no_pp,
				'status' => 'success'
			], 200);
		} catch (\Exception $ex) {
			DB::rollBack();
			return response()->json([
				'title' => 'Failed',
				'message' => 'Gagal menandai receive. ' . $ex->getMessage(),
				'status' => 'error'
			], 500);
		}
	}

	/* OTHER */
	public function getInvoice(Request $request, $no_pp)
	{
		if (!$request->ajax()) return redirect()->route('home');

		if (!Auth::user()->isAble(['whs-pp-tool-view'])) return response()->json([
			'title' => 'Forbidden',
			'message' => 'Maaf, Anda tidak memiliki izin untuk akses ini',
			'status' => 'warning'
		], 403);

		$no_pp = base64_decode($no_pp);
		$path = public_path() . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'no-image.jpeg';

		$pp = PpTool1::find($no_pp);

		if (!is_null($pp->file)) {
			$path_invoice = PpTool1::fileUploadPath($pp->file);
			if (file_exists($path_invoice)) $path = $path_invoice;
		}

		$ext = pathinfo($path, PATHINFO_EXTENSION);
		$loc_file = file_get_contents('file:///' . str_replace("\\\\", "\\", $path));
		$data_src = "data:" . mime_content_type($path) . ";charset=utf-8;base64," . base64_encode($loc_file);

		if ($ext == 'pdf') {
			$invoice = '<iframe src="' . $data_src . '" class="w-100 h-100 position-absolute"></iframe>';
		} else if (in_array($ext, ['jpeg', 'jpg', 'png'])) {
			$invoice = '<img src="' . $data_src . '" alt="Invoice" class="w-100 h-100">';
		}

		return response()->json([
			'title' => 'Success',
			'message' => 'Berhasil mengambil invoice',
			'status' => 'success',
			'data' => [
				'invoice' => $invoice
			]
		], 200);
	}
}
