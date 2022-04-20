<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
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
        if (!empty($product)) {
            $product->image = asset('storage/products/' . $product->image);

            $related = Product::select('id','name','slug','image','alt','sale_price','regular_price')->where(['status' => 1, 'parent_id' => $product->parent_id])->where('id', '!=', $product->id)->with('ratings:id,rating,product_id')->get();

            $related_products = $related_products->concat($related);
    
            $categories = collect();
            if ($related_products->isEmpty()) {
                $parent = Category::find($product->parent_id);
                if (!empty($parent->parent_id)) {
                    $related = Product::select('id','name','slug','image','alt','sale_price','regular_price')->where(['status' => 1, 'parent_id' => $parent->parent_id])->where('id', '!=', $product->id)->with('ratings:id,rating,product_id')->get();

                    $related_products = $related_products->concat($related);

                    $categories = DB::table('categories')->select('id')->where('id', '!=', $parent->id)->where('parent_id', $parent->parent_id)->get();

                    if($categories->isEmpty()){
                        $maincats = DB::table('categories')->select('id')->where('id', '!=', $parent->id)->where('parent_id', NULL)->get();
                        $maincat_ids = array();
                        foreach ($maincats as $maincat) {
                            array_push($maincat_ids, $maincat->id);
                        }
                        $categories = DB::table('categories')->select('id')->whereIn('parent_id', $maincat_ids)->get();
                    }
                }
            }

            if($related_products->isEmpty()){
                $maincats = DB::table('categories')->select('id')->where('id', '!=', $parent->id)->where('parent_id', NULL)->get();
                $maincat_ids = array();
                foreach ($maincats as $maincat) {
                    array_push($maincat_ids, $maincat->id);
                }

                $related = Product::select('id','name','slug','image','alt','sale_price','regular_price')->where(['status' => 1])->where('id', '!=', $product->id)->whereIn('parent_id', $maincat_ids)->with('ratings:id,rating,product_id')->get();

                $related_products = $related_products->concat($related);

                $categories = DB::table('categories')->select('id')->whereIn('parent_id', $maincat_ids)->get();
            }
    
            if ($categories->isNotEmpty()) {
                $cat_ids = array();
                foreach ($categories as $cat) {
                    array_push($cat_ids, $cat->id);
                }
    
                $related = Product::select('id','name','slug','image','alt','sale_price','regular_price')->where(['status' => 1])->where('id', '!=', $product->id)->whereIn('parent_id', $cat_ids)->with('ratings:id,rating,product_id')->get();

                $related_products = $related_products->concat($related);
            }
            
            foreach($related_products as $related_product){
                $related_product->image = asset('storage/products/' . $related_product->image);
                $rate = $related_product->ratings->avg('rating');
                $related_product->rating = number_format((float)$rate, 2, '.', '');
                $related_product->sale_price = number_format((float)$related_product->sale_price, 2, '.', '');
                $related_product->regular_price = number_format((float)$related_product->regular_price, 2, '.', '');
                unset($related_product->ratings);
            }

            $data = collect(['product' => $product,'related_products' => $related_products]);

            return response()->json(['message' => 'Data fetched Successfully', 'code' => 200, 'data' => $data], 200);
        } else {
            return response()->json(['message' => 'No Data Found', 'code' => 400], 400);
        }
    }

    public function getProductById(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors(), 'code' => 400], 400);
        }

        $product = DB::table('products')->where(['id' => $request->product_id, 'status' => 1])->first();

        $related_products = collect();

        if (!empty($product)) {
            $product->image = asset('storage/products/' . $product->image);

            return response()->json(['message' => 'Data fetched Successfully', 'code' => 200, 'data' => $product], 200);
        } else {
            return response()->json(['message' => 'No Data Found', 'code' => 400], 400);
        }
    }
}
