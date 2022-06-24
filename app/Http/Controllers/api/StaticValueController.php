<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StaticValueController extends Controller
{
  public function getStaticValue(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'name' => 'required',
    ]);
    if ($validator->fails()) {
      return response()->json(['message' => $validator->errors(), 'code' => 400], 400);
    }
    $value = DB::table('static_values')->select('value', 'type')->where('name', $request->name)->first();
    if (!empty($value)) {
      return response()->json(['message' => 'Data fetched Successfully', 'code' => 200, 'data' => $value], 200);
    } else {
      return response()->json(['message' => 'No Data Found', 'code' => 400], 400);
    }
  }
}
