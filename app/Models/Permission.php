<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Permission extends Model
{
  use HasFactory;

  protected $fillable = ['name', 'display_name', 'description'];

  /* CUSTOM */
  public static function permissionWithRole($role_id)
  {
    $permission = self::all();
    foreach ($permission as $p) {
      $permission_role = DB::table('permission_role as pr')
        ->join('permissions as p', 'p.id', '=', 'pr.permission_id')
        ->where('pr.permission_id', $p->id)
        ->where('pr.role_id', $role_id)
        ->orderBy('p.display_name')
        ->first();

      $p->selected = !is_null($permission_role) ? true : false;
    }

    return $permission;
  }
}
