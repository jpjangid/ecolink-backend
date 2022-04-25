<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
class CategoryController extends Controller
{
    public function index()
    {
        $categories = DB::table('categories')->select('id','name','slug','short_desc','image','alt')->where('parent_id',NULL)->where('status',1)->get();

        if($categories->isNotEmpty()){
            foreach($categories as $category){
                $category->image = asset('storage/category/'.$category->image);
            }

            return response()->json(['message' => 'Data fetched Successfully', 'code' => 200, 'data' => $categories], 200);
        }else{
            return response()->json(['message' => 'No Data found', 'code' => 400], 400);
        }
    }

    public function category(Request $request)
    {
        /* Getting Category by Slug */
        $validator = Validator::make($request->all(), [
            'slug' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors(), 'code' => 400], 400);
        }

        $category = Category::where(['slug' => $request->slug,'flag' => 0, 'parent_id' => null,'status' => 1])->with('subcategory:id,name,slug,parent_id,image,alt', 'products:id,name,slug,parent_id,image,alt,sale_price,regular_price','products.ratings:id,rating,product_id')->first();

        if(!empty($category)){
            $category->image = asset('storage/category/'.$category->image);
            $category->og_image = asset('storage/category/og_image/'.$category->og_image);

            if($category->subcategory->isNotEmpty()){
                foreach($category->subcategory as $subcategory){
                    $subcategory->image = asset('storage/category/'.$subcategory->image);
                }
            }

            if($category->products->isNotEmpty()){
                foreach($category->products as $product){
                    $product->image = asset('storage/products/'.$product->image);
                    $rate = $product->ratings->avg('rating');
                    $product->rating = number_format((float)$rate, 2, '.', '');
                    unset($product->ratings);
                }
            }
            
            return response()->json(['message' => 'Data fetched Successfully', 'code' => 200, 'data' => $category], 200);
        }else{
            return response()->json(['message' => 'No Data Found', 'code' => 400], 400);
        }
    }
}
