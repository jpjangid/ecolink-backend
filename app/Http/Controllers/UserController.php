<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Location;
use App\Models\Permission;
use App\Models\RoleHasPermission;
use App\Models\UserAddress;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if (checkpermission('UserController@index')) {
            if (request()->ajax()) {
                /* Getting all records */
                $active = $request->active == 'all' ? array('1', '2', '0') : array($request->active);
                $allusers = DB::table('users')->select('id', 'name', 'email', 'address', 'mobile', 'city', 'state', 'pincode', 'flag')->whereIn('flag', $active)->orderby('id','desc')->get();

                /* Converting Selected Data into desired format */
                $users = new Collection;
                foreach ($allusers as $key => $user) {
                    $users->push([
                        'id'            =>  $user->id,
                        'name'          =>  $user->name,
                        'email'         =>  $user->email,
                        'address'       =>  $user->address,
                        'mobile'        =>  $user->mobile,
                        'city'          =>  $user->city,
                        'state'         =>  $user->state,
                        'pincode'       =>  $user->pincode,
                        'flag'          =>  $user->flag,
                    ]);
                }

                /* Sending data through yajra datatable for server side rendering */
                return Datatables::of($users)
                    ->addIndexColumn()
                    /* Status Active and Deactivated Checkbox */
                    ->addColumn('active', function ($row) {
                        $checked = $row['flag'] == '0' ? 'checked' : '';
                        $active  = '<div class="form-check form-switch form-check-custom form-check-solid">
                                        <input type="hidden" value="' . $row['id'] . '" class="user_id">
                                        <input type="checkbox" class="form-check-input js-switch  h-20px w-30px" id="customSwitch1" name="flag" value="' . $row['flag'] . '" ' . $checked . '>
                                    </div>';

                        return $active;
                    })
                    /* Adding Actions like edit, delete and show */
                    ->addColumn('action', function ($row) {
                        $delete_url = url('admin/users/delete', $row['id']);
                        $edit_url = url('admin/users/edit', $row['id']);
                        $btn = '';
                        $btn .= '<a class="btn btn-primary btn-xs ml-1" href="' . $edit_url . '"><i class="fas fa-edit"></i></a>';
                        // $btn .= '<a class="btn btn-danger btn-xs ml-1" href="' . $delete_url . '"><i class="fa fa-trash"></i></a>';
                        return $btn;
                    })
                    ->rawColumns(['action', 'active'])
                    ->make(true);
            }
            return view('users.index');
        } else {
            return redirect()->back()->with('danger', 'You dont have required permission!');
        }
    }

    public function create()
    {
        if (checkpermission('UserController@create')) {
            /* Loading Create Page with location data */
            $locations = Location::select('state')->distinct()->orderby('state')->get();
            $roles = Role::all();
            return view('users.create', compact('locations', 'roles'));
        } else {
            return redirect()->back()->with('danger', 'You dont have required permission!');
        }
    }

    public function store(Request $request)
    {
        if ($request->status == 1) {
            $user = User::where('email', $request->email)->first();
            $user->flag   = '0';
            $user->update();
            return redirect('admin/users')->with('success', 'User has been added successfully');
        } else {
            $request->validate([
                'name'              =>  'required|regex:/^[\pL\s\-]+$/u',
                'email'             =>  'required|email|max:255|unique:users,email',
                'mobile'            =>  'required|digits:10|unique:users,mobile',
                'address'           =>  'required',
                'landmark'           =>  'required',
                'state'             =>  'required|regex:/^[\pL\s\-]+$/u',
                'city'              =>  'required|regex:/^[\pL\s\-]+$/u',
                'pincode'           =>  'required',
                'country'           =>  'required|regex:/^[\pL\s\-]+$/u',
                'password'          =>  'required|min:8',
                'role_id'           =>  'required',
                'profile_image'     =>  'required'
            ], [
                'name.required'             =>  'Please Enter Name',
                'name.regex'                =>  'Please Enter Name in alphabets',
                'email.required'            =>  'Please Enter Email',
                'mobile.required'           =>  'Please Enter Mobile No.',
                'address.required'          =>  'Please Enter Address',
                'landmark.required'         =>  'Please Enter Landmark',
                'state.required'            =>  'Please Enter State',
                'name.regex'                =>  'Please Enter State in alphabets',
                'city.required'             =>  'Please Enter City',
                'name.regex'                =>  'Please Enter City in alphabets',
                'pincode.required'          =>  'Please Enter Zip Code',
                'country.required'          =>  'Please Enter Country',
                'country.regex'                =>  'Please Enter Country in alphabets',
                'role_id.required'          =>  'Please Select Role',
                'mobile.numeric'            =>  'The Mobile No. must be numeric',
                'password.required'         =>  'Please Enter Password',
                'profile_image.required'    =>  'Please Select Profile Image',
            ]);

            /* Storing Featured Image on local disk */
            $image_name = "";
            if ($request->hasFile('profile_image')) {
                $request->validate([
                    'profile_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                ]);
                if ($request->hasFile('profile_image')) {
                    $file = $request->file('profile_image');
                    $image_name = $file->getClientOriginalName();
                    $path = Storage::putFileAs('public/profile_image/', $file, $image_name);
                }
            }

            /* Hashing password */
            $pass = Hash::make($request['password']);

            /* Storing Data in Table */
            $user = User::create([
                'name'                  =>  $request['name'],
                'email'                 =>  $request['email'],
                'mobile'                =>  $request['mobile'],
                'address'               =>  $request['address'],
                'landmark'              =>  $request['landmark'],
                'country'               =>  $request['country'],
                'state'                 =>  $request['state'],
                'city'                  =>  $request['city'],
                'pincode'               =>  $request['pincode'],
                'password'              =>  $pass,
                'role_id'               =>  $request['role_id'],
                'profile_image'         =>  $image_name,
            ]);

            UserAddress::create([
                'user_id'       =>  $user->id,
                'name'          =>  $request['name'],
                'email'         =>  $request['email'],
                'mobile'        =>  $request['mobile'],
                'address'       =>  $request['address'],
                'landmark'      =>  $request['landmark'],
                'country'       =>  $request['country'],
                'state'         =>  $request['state'],
                'city'          =>  $request['city'],
                'zip'           =>  $request['pincode'],
            ]);

            if ($request['role_id'] != 2) {
                $permissions = Permission::all();
                foreach ($permissions as $permission) {
                    RoleHasPermission::create([
                        'permission_id'     => $permission->id,
                        'user_id'           => $user->id,
                        'role_id'           => $request['role_id']
                    ]);
                }
            }
        }
        /* Validating Input fields */


        /* After Successfull insertion of data redirecting to listing page with message */
        return redirect('admin/users')->with('success', 'User has been added successfully');
    }

    public function edit($id)
    {
        if (checkpermission('UserController@edit')) {
            /* Getting User data with location for edit using Id */
            $user = User::find($id);
            $roles = Role::all();
            $locations = Location::select('state')->distinct()->orderby('state')->get();
            return view('users.edit', compact('user', 'locations', 'id', 'roles'));
        } else {
            return redirect()->back()->with('danger', 'You dont have required permission!');
        }
    }

    public function update(Request $request, $id)
    {
        /* Validating Input fields */
        $request->validate([
            'name'              =>  'required|regex:/^[\pL\s\-]+$/u',
            'email'             =>  'required|email|unique:users,email,' . $id,
            'mobile'            =>  'required|digits:10|unique:users,mobile,' . $id,
            'address'           =>  'required',
            'landmark'          =>  'required',
            'state'             =>  'required|regex:/^[\pL\s\-]+$/u',
            'city'              =>  'required|regex:/^[\pL\s\-]+$/u',
            'pincode'           =>  'required',
            'country'           =>  'required|regex:/^[\pL\s\-]+$/u',
            'password'          =>  'nullable|min:8',
            'role_id'           =>  'required',
        ], [
            'name.required'             =>  'Please Enter Name',
            'name.regex'                =>  'Please Enter Name in alphabets',
            'email.required'            =>  'Please Enter Email',
            'mobile.required'           =>  'Please Enter Mobile No.',
            'address.required'          =>  'Please Enter Address',
            'landmark.required'          =>  'Please Enter Landmark',
            'state.required'            =>  'Please Enter State',
            'state.regex'               =>  'Please Enter State in alphabets',
            'city.required'             =>  'Please Enter City',
            'city.regex'                =>  'Please Enter City in alphabets',
            'pincode.required'          =>  'Please Enter Zip Code',
            'country.required'          =>  'Please Enter Country',
            'country.regex'             =>  'Please Enter Country in alphabets',
            'role_id.required'          =>  'Please Select Role',
            'mobile.numeric'            =>  'The Mobile No. must be numeric',
        ]);

        /* Fetching Blog Data using Id */
        $user = User::find($id);

        /* Storing Featured Image on local disk */
        $image_name = $user->profile_image;
        if ($request->hasFile('profile_image')) {
            $request->validate([
                'profile_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            if ($request->hasFile('profile_image')) {
                $file = $request->file('profile_image');
                $image_name = $file->getClientOriginalName();
                $path = Storage::putFileAs('public/profile_image/', $file, $image_name);
            }
        }

        $pass = $user->password;
        if (!empty($request['password'])) {
            $pass = Hash::make($request['password']);
        }

        /* Updating Data fetched by Id */
        $user->name             =   $request['name'];
        $user->email            =   $request['email'];
        $user->mobile           =   $request['mobile'];
        $user->address          =   $request['address'];
        $user->landmark         =   $request['landmark'];
        $user->country          =   $request['country'];
        $user->state            =   $request['state'];
        $user->city             =   $request['city'];
        $user->pincode          =   $request['pincode'];
        $user->password         =   $pass;
        $user->role_id          =   $request['role_id'];
        $user->profile_image    =   $image_name;
        $user->save();

        /* After successfull update of data redirecting to index page with message */
        return redirect('admin/users')->with('success', 'User updated successfully');
    }

    public function destroy($id)
    {
        if (checkpermission('UserController@destroy')) {
            /* Updating selected entry Flag to 1 for soft delete */
            User::where('id', $id)->update(['flag' => 1]);

            return redirect('admin/users')->with('danger', 'User deleted successfully');
        } else {
            return redirect()->back()->with('danger', 'You dont have required permission!');
        }
    }

    public function update_status(Request $request)
    {
        /* Updating status of selected entry */
        $user = User::find($request->user_id);
        $user->flag   = $request->flag == 1 ? 0 : 1;
        if($request->flag == 0){
            $user->api_token = '';
            $user->remember_token = '';
        }
        $user->update();

        if ($user->flag == 1) {
            $data['msg'] = 'danger';
            return response()->json($data);
        } else {
            $data['msg'] = 'success';
            return response()->json($data);
        }
    }

    public function userinfo(Request $request)
    {
        $data = User::where(['email' => $request->email, 'flag' => '1'])->first();
        return response()->json($data);
    }
}
