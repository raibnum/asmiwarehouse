<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

  public function getStatus()
  {
    return static::status()[$this->status];
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

  /* CUSTOM */
  public static function newKodePinjam()
  {
    /* PTWHS07230001 */
    $last = self::orderBy('kd_pinj', 'desc')->first();

    $periode = Carbon::now()->format('Ym');

    if ($last == null) {
      $no = 0;
    } else {
      $no = (int) substr($last->kd_pinj, 9);
    }

    return 'PTWHS' . $periode . str_pad($no + 1, 4, '0', STR_PAD_LEFT);
  }

  public static function status()
  {
    return [0 => 'BELUM DIKEMBALIKAN', 1 => 'DIKEMBALIKAN SEBAGIAN', 2 => 'SELESAI'];
  }
}
