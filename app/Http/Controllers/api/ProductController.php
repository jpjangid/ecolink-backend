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
            return response()->json(['message' => $validator->errors(), 'code' => 400], 400);
        }

        $product = DB::table('products')->where(['slug' => $request->slug, 'status' => 1])->first();

        $related_products = collect();

        if(!empty($product)){
            $product->image = asset('storage/products/'.$product->image);

            return response()->json(['message' => 'Data fetched Successfully', 'code' => 200, 'data' => $product], 200);
        }else{
            return response()->json(['message' => 'No Data Found', 'code' => 400], 400);
        }
    }
}
