<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PinjamTool2 extends Model
{
  use HasFactory;

  protected $table = 'tr_pinj_tool2s';
  protected $primaryKey = 'kd_pinj';
  protected $keyType = 'string';
  public $increment = false;

  public function pinjamTool1()
  {
    return $this->belongsTo(PinjamTool1::class, 'kd_pinj', 'kd_pinj');
  }
}
