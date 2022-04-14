<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;
use App\Models\UserAddress;
class UserController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name'          =>  'required|string|max:255',
            'email'         =>  'required|string|email|max:255|unique:users',
            'password'      =>  'required|string|min:8',
            'mobile'        =>  'required|digits:10|unique:users,mobile',
            'address'       =>  'required',
            'state'         =>  'required',
            'city'          =>  'required',
            'pincode'       =>  'required',
        ], [
            'name.required'         =>  'Please Enter Name',
            'email.required'        =>  'Please Enter Email',
            'mobile.required'       =>  'Please Enter Mobile No.',
            'address.required'      =>  'Please Enter Address',
            'state.required'        =>  'Please Select State',
            'city.required'         =>  'Please Select City',
            'pincode.required'      =>  'Please Select Pincode',
            'mobile.numeric'        =>  'The Mobile No. must be numeric',
            'password.required'     =>  'Please Enter Password',
        ]);

        if($validator->fails()){
            return response()->json(['message' => $validator->errors(), 'code' => 400], 400);       
        }

        /* Storing Featured Image on local disk */
        $image_name = "";
        if ($request->hasFile('profile_image')) {
            $request->validate([
                'profile_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            if ($request->hasFile('profile_image')) {
                $file = $request->file('profile_image');
                $image_name = $file->getClientOriginalName();
                Storage::putFileAs('public/profile_image/', $file, $image_name);
            }
        }

        /* Hashing password */
        $pass = Hash::make($request['password']);

        $role = Role::where('name','client')->first();

        $user = User::create([
            'name'                  =>  $request['name'],
            'email'                 =>  $request['email'],
            'mobile'                =>  $request['mobile'],
            'address'               =>  $request['address'],
            'country'               =>  $request['country'],
            'state'                 =>  $request['state'],
            'city'                  =>  $request['city'],
            'pincode'               =>  $request['pincode'],
            'password'              =>  $pass,
            'role_id'               =>  $role->id,
            'profile_image'         =>  $image_name,
         ]);

         UserAddress::create([
             'user_id'       =>  $user->id,
             'email'         =>  $request['email'],
             'mobile'        =>  $request['mobile'],
             'address'       =>  $request['address'],
             'country'       =>  $request['country'],
             'state'         =>  $request['state'],
             'city'          =>  $request['city'],
             'zip'           =>  $request['pincode'],
         ]);

        $token = $user->createToken('MyApp')->accessToken;

        $data = collect(['access_token' => $token, 'token_type' => 'Bearer', 'user_id' => $user->id]);

        if(!empty($user)){
            return response()->json(['message' => 'Hi '.$user->name.', welcome to home','code' => 200, 'data' => $data], 200);
        }else{
            return response()->json(['message' => 'Credentials Invalid','code' => 400], 400);
        }
    }

    public function login(Request $request)
    {
        $login_credentials=[
            'email'=>$request->email,
            'password'=>$request->password,
        ];

        if(auth()->attempt($login_credentials)){
            //generate the token for the user
            $token = auth()->user()->createToken('MyApp')->accessToken;

            $data = collect(['access_token' => $token, 'token_type' => 'Bearer', 'user_id' => auth()->user()->id]);
            //now return this token on success login attempt
            return response()->json(['message' => 'Hi '.auth()->user()->name.', welcome to home','code' => 200, 'data' => $data], 200);
        }
        else{
            //wrong login credentials, return, user not authorised to our system, return error code 401
            return response()->json(['error' => 'UnAuthorised Access'], 401);
        }
    }

    // method for user logout and delete token
    public function logout()
    {
        if (Auth::check()) {
            Auth::user()->AauthAcessToken()->delete();
         }
    }

    //method for user info
    public function userInfo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'       => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors(), 'code' => 400], 400);
        }

        $user = User::find($request->user_id);

        if(!empty($user)){
            return response()->json(['message' => 'User Info fetched successfully', 'code' => 200, 'data' => $user], 200);
        }else{
            return response()->json(['message' => 'No User Found', 'code' => 400], 400);
        }
    }

    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'         => 'required|email',
            'password'      => 'required|confirmed|min:8'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors(), 'code' => 400], 400);
        }

        $user = User::where('email',$request->email)->first();

        if(!empty($user)){
            $user->password = Hash::make($request->password);
            $user->update();

            Auth::user()->AauthAcessToken()->delete();

            return response()->json(['message' => 'User password changed successfully', 'code' => 200, 'data' => $user], 200);
        }else{
            return response()->json(['message' => 'No User Found', 'code' => 400], 400);
        }
    }
}
