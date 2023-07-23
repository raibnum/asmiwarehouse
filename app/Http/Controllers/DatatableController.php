<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Tool;
use App\Models\User;
use App\Models\Operator;
use App\Models\Permission;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;

class DatatableController extends Controller
{
  /**
   * NOTE:
   * - please kindly order function alphabetically by its name
   */

  public function operator(Request $request)
  {
    $operator = Operator::orderBy('divisi')->orderBy('nm_operator')->get()->all();

    return DataTables::of($operator)
      ->addColumn('action', function ($operator) {
        if (!Auth::user()->isAble(['whs-operator-create'])) return '';

        return '
          <button type="button" class="btn btn-xs btn-success" onclick="popupModalEdit(\'' . $operator->id . '\');" data-toggle="tooltip" data-placement="top" title="Edit">
            <i class="fas fa-edit"></i>
          </button>
          <button type="button" class="btn btn-xs btn-danger" onclick="deleteOperator(\'' . $operator->id . '\');" data-toggle="tooltip" data-placement="top" title="Delete">
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
        if (!Auth::user()->isAble(['admin-permission-create'])) return '';

        return '
          <a href="' . route('permission.edit', base64_encode($permission->id)) . '" class="btn btn-xs btn-success" data-toggle="tooltip" data-placement="top" title="Edit">
            <i class="fas fa-edit"></i>
          </a>
          <button type="button" class="btn btn-xs btn-danger" onclick="deletePermission(\'' . $permission->id . '\');" data-toggle="tooltip" data-placement="top" title="Delete">
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
      ->addColumn('permissions', function ($role) {
        $permissions = $role->permissions()->pluck('name')->all();

        return join(' | ', $permissions);
      })
      ->addColumn('action', function ($role) {
        if (!Auth::user()->isAble(['admin-role-create'])) return '';

        return '
          <a href="' . route('role.edit', base64_encode($role->id)) . '" class="btn btn-xs btn-success" data-toggle="tooltip" data-placement="top" title="Edit">
            <i class="fas fa-edit"></i>
          </a>
          <button type="button" class="btn btn-xs btn-danger" onclick="deleteRole(\'' . $role->id . '\');" data-toggle="tooltip" data-placement="top" title="Delete">
            <i class="fas fa-trash-alt"></i>
          </button>
        ';
      })
      ->rawColumns(['action'])
      ->make(true);
  }

  public function tool(Request $request)
  {
    $st_aktif = $request->st_aktif ?? true;

    $tool = Tool::when($st_aktif != 'ALL', function ($coll) use ($st_aktif) {
      return $coll->where('st_aktif', $st_aktif);
    })
      ->orderBy('kd_jenis')
      ->orderBy('kd_tool')
      ->get()->all();

    return DataTables::of($tool)
      ->addColumn('st_aktif_html', function ($tool) {
        $context = 'danger';
        $text = 'NON AKTIF';

        if ($tool->st_aktif == true) {
          $context = 'success';
          $text = 'AKTIF';
        }

        return '<b class="text-' . $context . '">' . $text . '</b>';
      })
      ->addColumn('nm_jenis', function ($tool) {
        return $tool->jenisTool->nm_jenis;
      })
      ->addColumn('action', function ($tool) {
        if (!Auth::user()->isAble(['whs-tool-create'])) return '';

        return '
          <button type="button" class="btn btn-xs btn-success" onclick="popupModalEdit(\'' . $tool->kd_tool . '\');" data-toggle="tooltip" data-placement="top" title="Edit">
            <i class="fas fa-edit"></i>
          </button>
          <button type="button" class="btn btn-xs btn-danger" onclick="deleteTool(\'' . $tool->kd_tool . '\');" data-toggle="tooltip" data-placement="top" title="Delete">
            <i class="fas fa-trash-alt"></i>
          </button>
        ';
      })
      ->rawColumns(['st_aktif_html', 'action'])
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
        if (!Auth::user()->isAble(['admin-user-create'])) return '';

        return '
          <a href="' . route('user.edit', base64_encode($user->id)) . '" class="btn btn-xs btn-success" data-toggle="tooltip" data-placement="top" title="Edit">
            <i class="fas fa-edit"></i>
          </a>
          <button type="button" class="btn btn-xs btn-danger" onclick="deleteUser(\'' . $user->id . '\');" data-toggle="tooltip" data-placement="top" title="Delete">
            <i class="fas fa-trash-alt"></i>
          </button>
        ';
      })
      ->rawColumns(['action'])
      ->make(true);
  }
}
