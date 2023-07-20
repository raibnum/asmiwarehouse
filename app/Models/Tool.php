<?php

namespace App\Models;

use App\Models\JenisTool;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tool extends Model
{
	use HasFactory;

	protected $table = 'mas_tools';
	protected $primaryKey = 'kd_tool';
	protected $keyType = 'string';
	public $increment = false;

	protected $fillable = ['kd_tool', 'nm_tool', 'kd_jenis', 'stok', 'stok_minima', 'harga', 'st_aktif'];
	// protected $visible = ['stok_available'];

	/* ACCESSOR */
	protected function stokAvailable()
	{
		$stok = $this->stok - array_sum($this->pinjamTool2()->pluck('qty')->all());
		return Attribute::make(
			get: fn () => $stok,
			set: fn () => $stok
		);
	}

	/* RELATIONSHIP */
	public function jenisTool()
	{
		return $this->belongsTo(JenisTool::class, 'kd_jenis', 'kd_jenis');
	}

	public function pinjamTool2()
	{
		return $this->hasMany(PinjamTool2::class, 'kd_tool', 'kd_tool');
	}

	/* SCOPE */
	public function scopeAktif(Builder $query)
	{
		$query->where('st_aktif', true);
	}

	/* CUSTOM */
}
