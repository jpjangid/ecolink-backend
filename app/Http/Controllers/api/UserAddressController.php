<?php

namespace App\Http\Controllers\api;

use App\Models\UserAddress;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserAddressController extends Controller
{
  public function index(Request $request): \Illuminate\Http\JsonResponse
  {
    $user = $request->user();
    $addresses = DB::table('user_addresses')->where('user_id', $user->id)->where('address_type', 'shipping')->get();
    if ($addresses->isNotEmpty()) {
      return response()->json(['message' => 'Data fetched Successfully', 'code' => 200, 'data' => $addresses], 200);
    } else {
      return response()->json(['message' => 'No Data Found', 'code' => 400], 400);
    }
  }
  
  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'user_id' => 'required',
      'name' => 'required',
      'email' => 'required',
      'mobile' => 'required',
      'address' => 'required',
      'country' => 'required',
      'state' => 'required',
      'city' => 'required',
      'zip' => 'required',
    ]);
    
    if ($validator->fails()) {
      return response()->json(['message' => $validator->errors(), 'code' => 400], 400);
    }
    
    $usertoken = request()->bearerToken();
    if (empty($usertoken)) {
      return response()->json(['message' => 'User is not logged in', 'code' => 400], 400);
    }
    $user = DB::table('users')->select('id')->where('api_token', $usertoken)->first();
    if (empty($user)) {
      return response()->json(['message' => 'User is not logged in', 'code' => 400], 400);
    }
    
    $address = UserAddress::create([
      'user_id' => $user->id,
      'name' => $request['name'],
      'email' => $request['email'],
      'mobile' => $request['mobile'],
      'address' => $request['address'],
      // 'landmark' => $request['landmark'],
      'country' => $request['country'],
      'state' => $request['state'],
      'city' => $request['city'],
      'zip' => $request['zip'],
    ]);
    
    if (!empty($address)) {
      return response()->json(['message' => 'Address added successfully', 'code' => 200, 'data' => $address], 200);
    } else {
      return response()->json(['message' => 'Something went wrong', 'code' => 400], 400);
    }
  }
  
  public function update(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'address_id' => 'required',
      'user_id' => 'required',
      'email' => 'required',
      'mobile' => 'required',
      'address' => 'required',
      'country' => 'required',
      'state' => 'required',
      'city' => 'required',
      'zip' => 'required',
    ]);
    
    if ($validator->fails()) {
      return response()->json(['message' => $validator->errors(), 'code' => 400], 400);
    }
    
    $usertoken = request()->bearerToken();
    if (empty($usertoken)) {
      return response()->json(['message' => 'User is not logged in', 'code' => 400], 400);
    }
    $user = DB::table('users')->select('id')->where('api_token', $usertoken)->first();
    if (empty($user)) {
      return response()->json(['message' => 'User is not logged in', 'code' => 400], 400);
    }
    
    $address = UserAddress::where('id', $request->address_id)->update([
      'user_id' => $user->id,
      'name' => $request['name'],
      'email' => $request['email'],
      'mobile' => $request['mobile'],
      'address' => $request['address'],
      // 'landmark' => $request['landmark'],
      'country' => $request['country'],
      'state' => $request['state'],
      'city' => $request['city'],
      'zip' => $request['zip'],
    ]);
    
    if (!empty($address)) {
      return response()->json(['message' => 'Address updated successfully', 'code' => 200, 'data' => $address], 200);
    } else {
      return response()->json(['message' => 'Something went wrong', 'code' => 400], 400);
    }
  }
  
  public function delete(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'address_id' => 'required',
    ]);
    
    if ($validator->fails()) {
      return response()->json(['message' => $validator->errors(), 'code' => 400], 400);
    }
    
    $address = UserAddress::find($request->address_id);
    
    if (!empty($address)) {
      $address->delete();
      
      return response()->json(['message' => 'Address deleted successfully', 'code' => 200, 'data' => $address], 200);
    } else {
      return response()->json(['message' => 'No address found', 'code' => 400], 400);
    }
  }
}
