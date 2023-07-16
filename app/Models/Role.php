<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
  use HasFactory;

  protected $fillable = ['name', 'display_name', 'description'];

  /**
   * untuk menambah permission ke role
   * @param array|string $permission (id)
   */
  public function attachPermission($permissions)
  {
    $role_id = $this->id;
    if (gettype($permissions) == 'string') {
      $permissions = [$permissions];
    }

    foreach ($permissions as $permission) {
      DB::table('permission_role')
        ->upsert([
          ['permission_id' => $permission, 'role_id' => $role_id]
        ], ['permission_id', 'role_id'], []);
    }
  }

  public function permissions()
  {
    $role_id = $this->id;
    return DB::table('permission_role as pr')
      ->select('p.id', 'p.name', 'p.display_name', 'p.description')
      ->join('permissions as p', 'p.id', '=', 'pr.permission_id')
      ->where('pr.role_id', $role_id)
      ->orderBy('p.display_name')
      ->get();
  }
}
