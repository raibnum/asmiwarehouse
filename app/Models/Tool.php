<?php

namespace App\Models;

use App\Models\JenisTool;
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

	public function jenisTool()
	{
		return $this->belongsTo(JenisTool::class, 'kd_jenis', 'kd_jenis');
	}
}
