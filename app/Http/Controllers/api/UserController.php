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
use Illuminate\Support\Facades\Mail;
use App\Mail\ForgotPassword;
use App\Mail\VerificationMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'          =>  'required|string|max:255',
            'email'         =>  'required|string|email|max:255|unique:users,email',
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

        if ($validator->fails()) {
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

        $role = Role::where('name', 'client')->first();
        $token = Str::random(80);

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
            'tax_exempt'            =>  $request->tax_exempt,
            'remember_token'        =>  $token,
            'api_token'             =>  $token,
            'flag'                  =>  1
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
            'landmark'      =>  $request['landmark'],
            'name'          =>  $request['name'],
        ]);

        $user->profile_image = asset('storage/profile_image/' . $user->profile_image);
        $user->url = url('') . '/ecolinkfrontend/home/' . $user->api_token;
        Mail::to($request->email)->send(new VerificationMail($user));

        $data = collect(['access_token' => $token, 'token_type' => 'Bearer', 'user_id' => $user->id, 'user' => $user]);

        if (!empty($user)) {
            return response()->json(['message' => 'Hi ' . $user->name . ', welcome to home', 'code' => 200, 'data' => $data], 200);
        } else {
            return response()->json(['message' => 'Credentials Invalid', 'code' => 400], 400);
        }
    }

    public function verifyEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'         => 'required|email',
            'token'         => 'required'
        ]);

        $user = User::where(['email' => $request->email, 'api_token' => $request->token])->first();

        if (!empty($user)) {
            $user->email_verified = 1;
            $user->flag = 0;
            $user->update();

            return response()->json(['message' => 'User Account verified successfully', 'code' => 200, 'data' => $user], 200);
        } else {
            return response()->json(['message' => 'No User Found', 'code' => 400], 400);
        }
    }

    public function login(Request $request)
    {
        $login_credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        $user = DB::table('users')->where(['email' => $request->email, 'flag' => 0])->first();

        if (empty($user)) {
            return response()->json(['message' => 'User not found or inactive', 'code' => 400], 400);
        }

        $check_password = Hash::check($request->password, $user->password);

        if ($check_password == false) {
            return response()->json(['message' => 'Please enter correct password', 'code' => 400], 400);
        }

        if ($user->flag == 1) {
            return response()->json(['message' => 'User is Deactivated', 'code' => 400], 400);
        } else {

            if ($user->role_id == 1) {
                if (auth()->attempt($login_credentials)) {

                    return response()->json([
                        'user_type' => 'admin',
                        'redirect_url' => '/admin/home'
                    ]);
                } else {
                    //wrong login credentials, return, user not authorised to our system, return error code 401
                    return response()->json(['error' => 'UnAuthorised Access'], 401);
                }
            } else {
                if ($user->email_verified = 0) {
                    return response()->json(['message' => 'Email is not verified.', 'code' => 400], 400);
                }
                $token = Str::random(80);
                $affected = DB::table('users')->where('id', $user->id)->update(['api_token' => $token]);

                $user->profile_image = asset('storage/profile_image/' . $user->profile_image);

                $data = collect(['access_token' => $token, 'token_type' => 'Bearer', 'user_id' =>  $user->id, 'user' => $user]);
                //now return this token on success login attempt
                return response()->json(['message' => 'Hi ' . $user->id . ', welcome to home', 'code' => 200, 'data' => $data], 200);
            }
        }
    }

    // method for user logout and delete token
    public function logout()
    {
        if (Auth::check()) {
            $usertoken = request()->bearerToken();
            $user = User::select('id')->where('api_token', $usertoken)->first();
            $user->api_token = '';
            $user->update();

            return response()->json(['message' => 'User Logout Successfully', 'code' => 200], 200);
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

        $usertoken = request()->bearerToken();
        $user = DB::table('users')->select('id')->where('api_token', $usertoken)->first();

        $user = User::find($user->id);
        $user->profile_image = asset('storage/profile_image/' . $user->profile_image);

        if (!empty($user)) {
            return response()->json(['message' => 'User Info fetched successfully', 'code' => 200, 'data' => $user], 200);
        } else {
            return response()->json(['message' => 'No User Found', 'code' => 400], 400);
        }
    }

    public function forgotPasswordEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'         => 'required|email'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors(), 'code' => 400], 400);
        }

        $user = User::where('email', $request->email)->first();

        if (!empty($user) && $user->flag == 0) {
            $randomString = Str::random(80);
            $user->remember_token   = $randomString;
            $user->api_token        = $randomString;
            $user->update();

            $user->url = url('') . 'ecolink/profile/reset-password/' . $user->api_token;

            Mail::to($request->email)->send(new ForgotPassword($user));
            return response()->json(['message' => 'Forgot password email sent successfully', 'code' => 200], 200);
        } else {
            return response()->json(['message' => 'No User Found associated with this email', 'code' => 400], 400);
        }
    }

    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'         =>  'required|email',
            'token'         =>  'required',
            'password'      =>  'required|confirmed|min:8'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors(), 'code' => 400], 400);
        }

        $user = User::where(['email' => $request->email, 'api_token' => $request->token])->first();

        $updated_at = date('Y-m-d H:i:s', strtotime($user->updated_at . '+2 hours'));

        $datetime = date('Y-m-d H:i:s');

        if ($updated_at < $datetime) {
            return response()->json(['message' => 'Token expired', 'code' => 400], 400);
        }

        if (!empty($user)) {
            $user->password = Hash::make($request->password);
            $user->update();

            return response()->json(['message' => 'User password changed successfully', 'code' => 200, 'data' => $user], 200);
        } else {
            return response()->json(['message' => 'No User Found', 'code' => 400], 400);
        }
    }

    public function editUserInfo(Request $request)
    {
        $usertoken = request()->bearerToken();
        $user = DB::table('users')->select('id')->where('api_token', $usertoken)->first();

        $validator = Validator::make($request->all(), [
            'name'          =>  'required|regex:/^[\pL\s\-]+$/u|max:255',
            'email'         =>  'required|email|unique:users,email,' . $user->id,
            'password'      =>  'min:8',
            'mobile'        =>  'required|digits:10|unique:users,mobile,' . $user->id,
            'address'       =>  'required',
            'state'         =>  'required|regex:/^[\pL\s\-]+$/u',
            'city'          =>  'required|regex:/^[\pL\s\-]+$/u',
            'pincode'       =>  'required',
            'user_id'       =>  'required',
        ], [
            'name.required'         =>  'Please Enter Name',
            'name.regex'            =>  'Please Enter Name in alphabets',
            'email.required'        =>  'Please Enter Email',
            'mobile.required'       =>  'Please Enter Mobile No.',
            'address.required'      =>  'Please Enter Address',
            'state.required'        =>  'Please Select State',
            'state.regex'           =>  'Please Enter State in alphabets',
            'city.required'         =>  'Please Select City',
            'city.regex'            =>  'Please Enter City in alphabets',
            'pincode.required'      =>  'Please Select Pincode',
            'mobile.numeric'        =>  'The Mobile No. must be numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors(), 'code' => 400], 400);
        }

        $user = User::find($user->id);

        if (!empty($user)) {
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
            if (isset($request['password']) && !empty($request['password'])) {
                $pass = Hash::make($request['password']);
            }

            /* Updating Data fetched by Id */
            $user->name             =   $request['name'];
            $user->email            =   $request['email'];
            $user->mobile           =   $request['mobile'];
            $user->address          =   $request['address'];
            $user->country          =   $request['country'];
            $user->state            =   $request['state'];
            $user->city             =   $request['city'];
            $user->pincode          =   $request['pincode'];
            $user->password         =   $pass;
            $user->profile_image    =   $image_name;
            $user->save();

            $user->profile_image = asset('storage/profile_image/' . $user->profile_image);

            return response()->json(['message' => 'User info update successfully', 'code' => 200, 'data' => $user], 200);
        } else {
            return response()->json(['message' => 'No User Found', 'code' => 400], 400);
        }
    }
}
