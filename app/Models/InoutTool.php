<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InoutTool extends Model
{
  use HasFactory;

  protected $table = 'tr_inout_tools';
  protected $fillable = ['kd_tool', 'tgl', 'operator', 'status', 'qty'];

  /* RELATIONSHIP */
  public function tool()
  {
    return $this->belongsTo(Tool::class, 'kd_tool', 'kd_tool');
  }

  public function opr()
  {
    return $this->belongsTo(Operator::class, 'operator', 'id');
  }

  /* SCOPE */
  public function scopePeriode(Builder $query, $tgl_awal, $tgl_akhir)
  {
    $query->whereBetween('tgl', [$tgl_awal, $tgl_akhir]);
  }

  public function scopeStatus(Builder $query, $status)
  {
    $query->when($status != 'ALL', function ($q) use ($status) {
      return $q->where('status', $status);
    });
  }
}
