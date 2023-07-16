<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Operator;
use App\Models\Permission;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class DatatableController extends Controller
{
  public function operator(Request $request)
  {
    $operator = Operator::orderBy('divisi')->orderBy('nm_operator')->get()->all();

    return DataTables::of($operator)
      ->addColumn('action', function ($operator) {
        return '
          <button type="button" class="btn btn-xs btn-success" onclick="popupModalEdit(\'' . $operator->id . '\');" data-toggle="tooltip" data-placement="top" title="Edit">
            <i class="fas fa-edit"></i>
          </button>
          <button type="submit" class="btn btn-xs btn-danger" onclick="deleteOperator(\'' . $operator->id . '\');" data-toggle="tooltip" data-placement="top" title="Delete">
            <i class="fas fa-trash-alt"></i>
          </button>
        ';
      })
      ->rawColumns(['action'])
      ->make(true);
  }

  public function permission(Request $request)
  {
    $permission = Permission::orderBy('display_name')->get()->all();

    return DataTables::of($permission)
      ->addColumn('action', function ($permission) {
        return '
          <a href="' . route('permission.edit', base64_encode($permission->id)) . '" class="btn btn-xs btn-success" data-toggle="tooltip" data-placement="top" title="Edit">
            <i class="fas fa-edit"></i>
          </a>
          <button type="submit" class="btn btn-xs btn-danger" onclick="deletePermission(\'' . $permission->id . '\');" data-toggle="tooltip" data-placement="top" title="Delete">
            <i class="fas fa-trash-alt"></i>
          </button>
        ';
      })
      ->rawColumns(['action'])
      ->make(true);
  }

  public function role(Request $request)
  {
    $role = Role::orderBy('display_name')->get()->all();

    return DataTables::of($role)
      ->addColumn('action', function ($role) {
        return '
          <a href="' . route('role.edit', base64_encode($role->id)) . '" class="btn btn-xs btn-success" data-toggle="tooltip" data-placement="top" title="Edit">
            <i class="fas fa-edit"></i>
          </a>
          <button type="submit" class="btn btn-xs btn-danger" onclick="deleteRole(\'' . $role->id . '\');" data-toggle="tooltip" data-placement="top" title="Delete">
            <i class="fas fa-trash-alt"></i>
          </button>
        ';
      })
      ->rawColumns(['action'])
      ->make(true);
  }

  public function user(Request $request)
  {
    $user = User::orderBy('username')->get()->all();

    return DataTables::of($user)
      ->addColumn('roles', function ($user) {
        $roles = $user->roles()->pluck('name')->all();

        return join(' | ', $roles);
      })
      ->addColumn('action', function ($user) {
        return '
          <a href="' . route('user.edit', base64_encode($user->id)) . '" class="btn btn-xs btn-success" data-toggle="tooltip" data-placement="top" title="Edit">
            <i class="fas fa-edit"></i>
          </a>
          <button type="submit" class="btn btn-xs btn-danger" onclick="deleteUser(\'' . $user->id . '\');" data-toggle="tooltip" data-placement="top" title="Delete">
            <i class="fas fa-trash-alt"></i>
          </button>
        ';
      })
      ->rawColumns(['action'])
      ->make(true);
  }
}
