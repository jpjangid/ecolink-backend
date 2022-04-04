<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
class ProductController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'slug' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $product = DB::table('products')->where(['slug' => $request->slug, 'status' => 1])->first();

        if(!empty($product)){
            return response()->json(['product' => $product], 200);
        }else{
            return response()->json(['error' => 'No Data Found'], 400);
        }
    }
}
