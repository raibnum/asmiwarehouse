<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PinjamTool1 extends Model
{
  use HasFactory;

  protected $table = 'tr_pinj_tool1s';
  protected $primaryKey = 'kd_pinj';
  protected $keyType = 'string';
  public $increment = false;

  public function pinjamTool2s()
  {
    return $this->hasMany(PinjamTool2::class, 'kd_pinj', 'kd_pinj');
  }
}
