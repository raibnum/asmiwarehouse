<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PpTool1 extends Model
{
	use HasFactory;

	protected $table = 'tr_pp_tool1s';
	protected $primaryKey = 'no_pp';
	protected $keyType = 'string';
	public $increment = false;

	protected $fillable = ['no_pp', 'tgl_pp', 'keterangan', 'tgl_approve', 'submit_prch', 'tgl_receive', 'file'];
	protected $appends = ['status_pp'];

	/* ACCESSOR & MUTATOR */
	public function statusPp(): Attribute
	{
		return Attribute::make(
			get: function () {
				$tgl_approve = is_null($this->tgl_approve);
				$submit_prch = is_null($this->submit_prch);
				$tgl_receive = is_null($this->tgl_receive);
				if ($tgl_approve && $submit_prch && $tgl_receive) {
					return 'INPUT';
				} else if (!$tgl_approve && $submit_prch && $tgl_receive) {
					return 'APPROVE';
				} else if (!$tgl_approve && !$submit_prch && $tgl_receive) {
					return 'BOUGHT';
				} else if (!$tgl_approve && !$submit_prch && !$tgl_receive) {
					return 'RECEIVE';
				}
			}
		);
	}

	/* RELATIONSHIP */
	public function ppTool2s()
	{
		return $this->hasMany(PpTool2::class, 'no_pp', 'no_pp');
	}

	/* SCOPE */
	public function scopePeriode(Builder $query, $tgl_awal, $tgl_akhir)
	{
		$query->whereBetween('tgl_pp', [$tgl_awal, $tgl_akhir]);
	}

	public function scopeStatus(Builder $query, $status)
	{
		$query->when($status != 'ALL', function ($q) use ($status) {
			if ($status == 'INPUT') {
				return $q->whereNull('tgl_approve')
					->whereNull('submit_prch')
					->whereNull('tgl_receive');
			} else if ($status == 'APPROVE') {
				return $q->whereNotNull('tgl_approve')
					->whereNull('submit_prch')
					->whereNull('tgl_receive');
			} else if ($status == 'BOUGHT') {
				return $q->whereNotNull('tgl_approve')
					->whereNotNull('submit_prch')
					->whereNull('tgl_receive');
			} else if ($status == 'RECEIVE') {
				return $q->whereNotNull('tgl_approve')
					->whereNotNull('submit_prch')
					->whereNotNull('tgl_receive');
			} else {
				return $q;
			}
		});
	}

	/* CUSTOM */
	public static function fileUploadPath($namaFile = '')
	{
		return public_path() . DIRECTORY_SEPARATOR . 'upload' . DIRECTORY_SEPARATOR . 'pp_tool' . DIRECTORY_SEPARATOR . $namaFile;
	}

	public static function newNoPp()
	{
		$periode = Carbon::now()->format('my');
		$last = self::where(DB::raw("date_format(tgl_pp, '%m%y')"), $periode)
			->orderBy('no_pp', 'desc')
			->first();

		if ($last == null) {
			$no = 0;
		} else {
			$no = (int) substr($last->no_pp, 9);
		}

		return 'PPWHS' . $periode . str_pad($no + 1, 4, '0', STR_PAD_LEFT);
	}

	public static function status()
	{
		return ['INPUT', 'APPROVE', 'BOUGHT', 'RECEIVE'];
	}

	public static function totalPp()
	{
		$pp = self::all();
		return count($pp);
	}
}
