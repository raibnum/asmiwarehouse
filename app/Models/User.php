<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
  use HasApiTokens, HasFactory, Notifiable;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'username',
    'name',
    'email',
    'password',
  ];

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var array<int, string>
   */
  protected $hidden = [
    'password',
    'remember_token',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'email_verified_at' => 'datetime',
    'password' => 'hashed',
  ];

  /**
   * untuk mengecek permissions yang dimiliki oleh user
   * @param array|string permissions yang mau dicek
   */
  public function isAble($permissions)
  {
    $status = false;
    $permission_user = $this->permissions()->pluck('name')->toArray();
    if (gettype($permissions) === 'string') {
      $permissions = [$permissions];
    }

    foreach ($permissions as $p) {
      if (str_contains($p, '*')) {
        $p = str_replace('*', '', $p);
        if (count(preg_grep('/' . $p . '/', $permission_user)) == 0) continue;

        $status = true;
        break;
      }

      if (!in_array($p, $permission_user)) continue;

      $status = true;
      break;
    }

    return $status;
  }


  /* avatar */
  public function avatar()
  {
    return \Avatar::create($this->name)->toBase64();
  }

  /* utility */
  /**
   * untuk menambah role kepada user
   * @param array|string|integer $roles id
   */
  public function attachRole($roles)
  {
    $user_id = $this->id;
    if (in_array(gettype($roles), ['string', 'integer'])) {
      $roles = [$roles];
    }

    foreach ($roles as $role) {
      DB::table('role_user')
        ->upsert([
          ['role_id' => $role, 'user_id' => $user_id]
        ], ['role_id', 'username_id'], []);
    }
  }

  public function permissions()
  {
    $roles = $this->roles();

    return DB::table('permission_role as pr')
      ->select('p.id', 'p.name', 'p.display_name')
      ->join('permissions as p', 'p.id', '=', 'pr.permission_id')
      ->whereIn('role_id', $roles->pluck('id'))
      ->get();
  }

  public function roles()
  {
    $user_id = $this->id;

    return DB::table('role_user as ru')
      ->select('r.id', 'r.name', 'r.display_name')
      ->join('roles as r', 'r.id', '=', 'ru.role_id')
      ->where('user_id', $user_id)
      ->get();
  }
}
