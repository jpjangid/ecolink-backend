<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

use App\Models\RoleHasPermission;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Eloquent\Collection;

class UserPermissionController extends Controller
{
    public function index()
    {
        if (checkpermission('UserPermissionController@index')) {
            if (request()->ajax()) {
                $allusers  = User::where(['role_id' => 1, 'flag' => 0])->where('id','!=',auth()->user()->id)->with('role')->orderby('created_at','desc')->get();

                /* Converting Selected Data into desired format */
                $users = new Collection;
                foreach ($allusers as $user) {
                    $users->push([
                        'id'            =>  $user->id,
                        'name'          =>  $user->name,
                        'role'          =>  $user->role->name,
                    ]);
                }
                return Datatables::of($users)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        $actionBtn = '<a href="' . url('admin/userpermissions/edit', $row['id']) . '" class="btn btn-primary btn-xs"><i class="fas fa-edit"></i></a>';
                        return $actionBtn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            return view('userpermissions.index');
        } else {
            return redirect()->back()->with('danger', 'You dont have required permission!');
        }
    }

    public function store(Request $request)
    {
        if ($request->permission_id == 'all') {
            $userhaspermission = RoleHasPermission::where(['role_id' => $request->role_id, 'user_id' => $request->user_id])->get();
            if (!$userhaspermission->isEmpty()) {
                $delete = RoleHasPermission::where(['role_id' => $request->role_id, 'user_id' => $request->user_id])->delete();
            }
            $permissions = Permission::all();
            foreach ($permissions as $permission) {
                RoleHasPermission::create([
                    'role_id'   =>  $request->role_id,
                    'permission_id' => $permission->id,
                    'user_id'   =>  $request->user_id,
                ]);
            }
            $data['msg'] = 'success';
            return response($data);
        } elseif ($request->permission_id == 'unselectall') {
            $rolehaspermissions = RoleHasPermission::where(['role_id' => $request->role_id, 'user_id' => $request->user_id])->delete();
            $data['msg'] = 'danger';
            return response($data);
        } else {
            $role_permission = RoleHasPermission::where(['user_id' => $request->user_id, 'permission_id' => $request->permission_id, 'role_id' => $request->role_id])->first();
            if (!empty($role_permission)) {
                RoleHasPermission::where(['user_id' => $request->user_id, 'permission_id' => $request->permission_id, 'role_id' => $request->role_id])->delete();
                $data['msg'] = 'danger';
                return response($data);
            } else {
                RoleHasPermission::create([
                    'role_id'   =>  $request->role_id,
                    'permission_id' => $request->permission_id,
                    'user_id'   =>  $request->user_id,
                ]);
                $data['msg'] = 'success';
                return response($data);
            }
        }
    }

    public function edit($id)
    {
        if (checkpermission('UserPermissionController@edit')) {
            $userpermissions = RoleHasPermission::where('user_id', $id)->get('permission_id');
            $user = User::find($id);
            // $role = Role::where('name', $user->role_id)->first();
            $role_id = $user->role_id;
            $data = array();
            foreach ($userpermissions as $userpermission) {
                array_push($data, $userpermission->permission_id);
            }
            $permissions = Permission::orderby('id', 'asc')->get();
            $names = Permission::select('title')->distinct('title')->get()->sort();

            return view('userpermissions.edit', compact('userpermissions', 'permissions', 'id', 'data', 'role_id', 'names'));
        } else {
            return redirect()->back()->with('danger', 'You dont have required permission!');
        }
    }
}
