<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Category;
use App\Models\LinkCategory;
use App\Models\Page;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
class HomeController extends Controller
{
    public function index()
    {
        $pagecategories = LinkCategory::select('id', 'name', 'slug')->with('sublink:id,category,title,slug')->get();

        $pages = Page::select('id','title','slug')->with('links:page_id,link_id','links.relatedPage:id,title,slug')->get();

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

        $data = collect(['pagecategories' => $pagecategories, 'categories' => $categories, 'blogs' => $blogs, 'products' => $products, 'pages' => $pages]);

        if($data->isNotEmpty()){
            return response()->json(['message' => 'Data fetched Successfully', 'code' => 200, 'data' => $data], 200);
        }else{
            return response()->json(['message' => 'No Data found', 'code' => 400], 400);
        }
    }

    public function globalSearch(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'         => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors(), 'code' => 400], 400);
        }

        $name = str_replace(" ","%",$request->name);
        $products = Product::select('id', 'name', 'regular_price', 'sale_price', 'slug', 'image', 'alt')->where('name','like','%' . $name . '%')->where(['status' => 1])->with('ratings:id,rating,product_id')->get();

        if($products->isNotEmpty()){
            foreach ($products as $product) {
                $product->image = asset('storage/products/'.$product->image);
                $rate = $product->ratings->avg('rating');
                $product->rating = number_format((float)$rate, 2, '.', '');
                unset($product->ratings);
            }

            return response()->json(['message' => 'Product search successfully', 'code' => 200, 'data' => $products], 200);
        }else{
            return response()->json(['message' => 'No Products Found', 'code' => 400], 400);
        }
    }

    public function filterProduct(Request $request)
    {
        if(!empty($request->category)){
            $categories = DB::table('categories')->select('id')->whereIn('id',$request->category)->orWhereIn('parent_id',$request->category)->get();
        }else{
            $categories = DB::table('categories')->select('id','parent_id')->get();
        }

        $category_ids = [];
        foreach($categories as $category){
            array_push($category_ids,$category->id);
        }

        $products = Product::select('id', 'name', 'regular_price', 'sale_price', 'slug', 'image', 'alt')->with('ratings:id,rating,product_id')->whereIn('parent_id', $category_ids)->where(['status' => 1])->get();

        if($products->isNotEmpty()){
            foreach ($products as $product) {
                $product->image = asset('storage/products/'.$product->image);
                $rate = $product->ratings->avg('rating');
                $product->rating = number_format((float)$rate, 2, '.', '');
                unset($product->ratings);
            }
        }

        if($products->isNotEmpty()){
            if(!empty($request->rating)){
                $products = $products->whereIn('rating', $request->rating);
            }
        }

        if($products->isNotEmpty()){
            if(!empty($request->price_from) && !empty($request->price_to)){
                $products = $products->where('sale_price','>=', $request->price_from)->where('sale_price','<=', $request->price_to);
            }
        }

        if($products->isNotEmpty()){
            if(!empty($request->sortby)){
                if($request->sortby == 'lowtohigh'){
                    $products = $products->sort(function($a, $b) {
                        return strcmp($a->sale_price, $b->sale_price);
                    });
                }
                if($request->sortby == 'hightolow'){
                    $products = $products->sort(function($a, $b) {
                        return strcmp($b->sale_price, $a->sale_price);
                    });
                }
                if($request->sortby == 'popularity'){
                    $products = $products->sort(function($a, $b) {
                        return strcmp($b->rating, $a->rating);
                    });
                }
                if($request->sortby == 'name'){
                    $products = $products->sort(function($a, $b) {
                        return strcmp($b->name, $a->name);
                    });
                }
            }
        }

        if($products->isNotEmpty()){
            return response()->json(['message' => 'Product filtered successfully', 'code' => 200, 'data' => $products], 200);
        }else{
            return response()->json(['message' => 'No Products Found', 'code' => 400], 400);
        }
    }
}
