<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Category;
use App\Models\LinkCategory;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $pagecategories = LinkCategory::select('id', 'name', 'slug')->with('sublink:id,category,title,slug')->get();

        $categories = Category::select('id', 'name', 'slug', 'short_desc', 'image', 'alt')->where(['flag' => 0, 'parent_id' => null,'status' => 1])->with('subcategory:id,name,slug,parent_id', 'products:id,name,slug,parent_id')->get();

        if($categories->isNotEmpty()){
            foreach($categories as $category){
                $category->image = asset('storage/category/'.$category->image);
            }
        }

        $blogs = DB::table('blogs')->select('id', 'title', 'slug', 'description', 'publish_date', 'status', 'image', 'alt')->where('status', 1)->orderby('id','desc')->get();

        if($blogs->isNotEmpty()){
            foreach($blogs as $blog){
                $blog->image = asset('storage/blogs/'.$blog->image);
            }
        }

        $products = Product::select('id', 'name', 'regular_price', 'sale_price', 'slug', 'image', 'alt')->with('ratings:id,rating,product_id')->where(['status' => 1])->get();

        if($products->isNotEmpty()){
            foreach ($products as $product) {
                $product->image = asset('storage/products/'.$product->image);
                $rate = $product->ratings->avg('rating');
                $product->rating = number_format((float)$rate, 2, '.', '');
                unset($product->ratings);
            }
        }

        $cart = Cart::where('user_id', $request->user_id)->get();
        $cart_count = count($cart);

        $data = collect(['pagecategories' => $pagecategories, 'categories' => $categories, 'blogs' => $blogs, 'products' => $products, 'cart_count' => $cart_count]);

        if($data->isNotEmpty()){
            return response()->json(['message' => 'Data fetched Successfully', 'code' => 200, 'data' => $data], 200);
        }else{
            return response()->json(['message' => 'No Data found', 'code' => 400], 400);
        }
    }
}
