<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisTool extends Model
{
	use HasFactory;

	protected $table = 'mas_jenis_tools';
	protected $primaryKey = 'kd_jenis';
	protected $keyType = 'string';
	public $increment = false;
	public $timestamps = false;

	protected $fillable = ['kd_jenis', 'nm_jenis'];

	public function tools()
	{
		return $this->hasMany(Tool::class, 'kd_jenis', 'kd_jenis');
	}
}
