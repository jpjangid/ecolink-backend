<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Location;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $allusers = DB::table('users')->select('id', 'name', 'email', 'address', 'mobile', 'city', 'state', 'pincode')->where('flag', '0')->get();

            $users = new Collection;
            foreach ($allusers as $user) {
                $users->push([
                    'id'            =>  $user->id,
                    'name'          =>  $user->name,
                    'email'         =>  $user->email,
                    'address'       =>  $user->address,
                    'mobile'        =>  $user->mobile,
                    'city'          =>  $user->city,
                    'state'         =>  $user->state,
                    'pincode'       =>  $user->pincode,
                ]);
            }

            return Datatables::of($users)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $delete_url = url('users/delete', $row['id']);
                    $edit_url = url('users/edit', $row['id']);
                    $btn = '<a class="btn btn-primary btn-xs ml-1" href="' . $edit_url . '"><i class="fas fa-edit"></i></a>';
                    $btn .= '<a class="btn btn-danger btn-xs ml-1" href="' . $delete_url . '"><i class="fa fa-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('users.index');
    }

    public function create()
    {
        $states = Location::select('state')->distinct()->orderby('state')->get();
        return view('users.create', compact('states'));
    }

    public function store(Request $request)
    {
        $request->validate($request->all(), [
            'name'          =>  'required',
            'email'         =>  'required|email|max:255|unique:users',
            'mobile'        =>  'required|digits:10|unique:users,mobile',
            'address'       =>  'required',
            'state'         =>  'required',
            'city'          =>  'required',
            'pincode'       =>  'required',
            'password'      =>  'required|min:8',
            'role'          =>  'required',
        ], [
            'name.required'         =>  'Please Enter Name',
            'mobile.required'       =>  'Please Enter Mobile number',
            'address.required'      =>  'Please Enter Address',
            'state.required'        =>  'Please Select State',
            'city.required'         =>  'Please Select City',
            'pincode.required'      =>  'Please Select Pincode',
            'role.required'         =>  'Please Select Role',
            'mobile.numeric'        =>  'The Mobile No. must be numeric',
            'password.required'     =>  'Please Enter Password',
        ]);

        $image_name = "";
        if (!empty($request->hasFile('profile_image'))) {
            $request->validate($request->all(), [
                'profile_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            if ($request->hasFile('profile_image')) {
                $files = $request->file('profile_image');
                foreach ($files as $file) {
                    $image_name = $file->getClientOriginalName();
                    $uid = auth()->user()->id;
                    $path = Storage::putFileAs('public/profile_image/' . $uid, $file, $image_name);
                }
            }
        }

        $pass = Hash::make($request['password']);

        User::create([
            'name'                  =>  $request['name'],
            'email'                 =>  $request['email'],
            'mobile'                =>  $request['mobile'],
            'address'               =>  $request['address'],
            'country'               =>  $request['country'],
            'state'                 =>  $request['state'],
            'city'                  =>  $request['city'],
            'pincode'               =>  $request['pincode'],
            'password'              =>  $pass,
            'role'                  =>  $request['role'],
            'profile_image'         =>  $image_name,
        ]);

        return redirect('/users')->with('success', 'User has been added successfully');
    }

    public function show($id)
    {
        $user = User::find($id);
        return view('brand.show', compact('brand'));
    }

    public function edit($id)
    {
        $user = User::find($id);
        return view('brand.edit', compact('brand'));
    }

    public function update(Request $request, $id)
    {
        $request->validate($request->all(), [
            'name'          =>  'required',
            'email'         =>  'required|email|max:255|unique:users',
            'mobile'        =>  'required|digits:10|unique:users,mobile',
            'address'       =>  'required',
            'state'         =>  'required',
            'city'          =>  'required',
            'pincode'       =>  'required',
            'role'          =>  'required',
        ], [
            'name.required'         =>  'Please Enter Name',
            'mobile.required'       =>  'Please Enter Mobile number',
            'address.required'      =>  'Please Enter Address',
            'state.required'        =>  'Please Select State',
            'city.required'         =>  'Please Select City',
            'pincode.required'      =>  'Please Select Pincode',
            'role.required'         =>  'Please Select Role',
            'mobile.numeric'        =>  'The Mobile No. must be numeric',
        ]);

        $user = User::find($id);
        $image_name = $user->profile_image;
        if (!empty($request->hasFile('profile_image'))) {
            $request->validate($request->all(), [
                'profile_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            if ($request->hasFile('profile_image')) {
                $files = $request->file('profile_image');
                foreach ($files as $file) {
                    $image_name = $file->getClientOriginalName();
                    $uid = auth()->user()->id;
                    $path = Storage::putFileAs('public/profile_image/' . $uid, $file, $image_name);
                }
            }
        }

        $pass = $user->password;
        if (!empty($request['password'])) {
            $pass = Hash::make($request['password']);
        }

        $user->name             =   $request['name'];
        $user->email            =   $request['email'];
        $user->mobile           =   $request['mobile'];
        $user->address          =   $request['address'];
        $user->country          =   $request['country'];
        $user->state            =   $request['state'];
        $user->city             =   $request['city'];
        $user->pincode          =   $request['pincode'];
        $user->password         =   $pass;
        $user->role             =   $request['role'];
        $user->profile_image    =   $image_name;
        $user->save();

        return redirect('/users')->with('success', 'User updated successfully');
    }

    public function destroy($id)
    {
        $user = User::find($id);
        $user->flag  = '1';
        $user->update();
        return redirect('/users')->with('danger', 'User deleted successfully');
    }
}
