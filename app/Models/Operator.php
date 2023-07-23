<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operator extends Model
{
  use HasFactory;
  protected $table = 'mas_operators';

  protected $fillable = ['nm_operator', 'divisi'];

  /* RELATIONSHIP */
  public function inoutTools()
  {
    return $this->hasMany(InoutTool::class, 'operator', 'id');
  }

  public function pinjamTool1s()
  {
    return $this->hasMany(PinjamTool1::class, 'operator', 'id');
  }
}
