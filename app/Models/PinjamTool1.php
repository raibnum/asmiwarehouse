<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PinjamTool1 extends Model
{
  use HasFactory;

  protected $table = 'tr_pinj_tool1s';
  protected $primaryKey = 'kd_pinj';
  protected $keyType = 'string';
  public $increment = false;

  protected $fillable = ['kd_pinj', 'tgl', 'operator', 'status'];
  protected $appends = ['status_text'];

  /* ACCESSOR & MUTATOR */
  public function statusText(): Attribute
  {
    return Attribute::make(
      get: fn () => $this->getStatus()
    );
  }

  /* RELATIONSHIP */
  public function opr()
  {
    return $this->belongsTo(Operator::class, 'operator', 'id');
  }

  public function pinjamTool2s()
  {
    return $this->hasMany(PinjamTool2::class, 'kd_pinj', 'kd_pinj');
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
  public function getStatus()
  {
    return static::status()[$this->status];
  }

  public static function newKodePinjam()
  {
    /* PTWHS07230001 */
    $periode = Carbon::now()->format('my');
    $last = self::where(DB::raw("date_format(tgl, '%m%y')"), $periode)
      ->orderBy('kd_pinj', 'desc')
      ->first();

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

  public function udpateStatus()
  {
    $status = 0;
    $jumlah = 0;
    $p2ts = $this->pinjamTool2s->all();
    $total_tool = count($p2ts);

    foreach ($p2ts as $p2t) {
      if ($p2t->tgl_kembali != null) {
        $jumlah += 1;
        $status = 1;
      }
    }

    if ($jumlah == $total_tool) $status = 2;

    $this->update(['status' => $status]);
  }
}
