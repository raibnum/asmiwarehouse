<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PpTool2 extends Model
{
	use HasFactory;

	protected $table = 'tr_pp_tool2s';
	// protected $primaryKey = 'no_pp';
	// protected $keyType = 'string';
	// public $increment = false;
	public $timestamps = false;

	protected $fillable = ['no_pp', 'kd_tool', 'qty'];

	/* RELATIONSHIP */
	public function ppTool1()
	{
		return $this->belongsTo(PpTool1::class, 'no_pp', 'no_pp');
	}

	public function tool()
	{
		return $this->belongsTo(Tool::class, 'kd_tool', 'kd_tool');
	}
}
