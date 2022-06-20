<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Category;
use App\Models\LinkCategory;
use App\Models\Page;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    public function index()
    {
        // $pagecategories = LinkCategory::select('id', 'name', 'slug')->with('sublink:id,category,title,slug')->get();

        // $pages = Page::select('id','title','slug')->with('links:page_id,link_id','links.relatedPage:id,title,slug')->get();

        $pages = Page::select('id', 'title', 'slug')->where('parent_id', NULL)->with('subpage:id,title,slug,parent_id', 'subpage.subpage:id,title,slug,parent_id')->where('status', 1)->where('tags', 'menu')->get();

        // $categories = Category::select('id', 'name', 'slug', 'short_desc', 'image', 'alt')->where(['flag' => 0, 'parent_id' => null, 'status' => 1])->with('subcategory:id,name,slug,parent_id', 'products:id,name,slug,parent_id')->where('status', 1)->get();

        // if ($categories->isNotEmpty()) {
        //     foreach ($categories as $category) {
        //         $category->image = asset('storage/category/' . $category->image);
        //     }
        // }

        // $blogs = DB::table('blogs')->select('id', 'title', 'slug', 'description', 'publish_date', 'status', 'image', 'alt')->where('status', 1)->orderby('id', 'desc')->get();

        // if ($blogs->isNotEmpty()) {
        //     foreach ($blogs as $blog) {
        //         $blog->image = asset('storage/blogs/' . $blog->image);
        //     }
        // }

        // $products = Product::select('id', 'name', 'regular_price', 'sale_price', 'slug', 'image', 'alt')->with('ratings:id,rating,product_id')->where(['status' => 1])->get();

        // if ($products->isNotEmpty()) {
        //     foreach ($products as $product) {
        //         $product->image = asset('storage/products/' . $product->image);
        //         $rate = $product->ratings->avg('rating');
        //         $product->rating = number_format((float)$rate, 2, '.', '');
        //         unset($product->ratings);
        //     }
        // }

        $data = collect(['pages' => $pages]);

        if ($data->isNotEmpty()) {
            return response()->json(['message' => 'Data fetched Successfully', 'code' => 200, 'data' => $data], 200);
        } else {
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

        $name = str_replace(" ", "%", $request->name);
        $products = Product::select('id', 'name', 'regular_price', 'sale_price', 'slug', 'image', 'alt', 'parent_id')->where('name', 'like', '%' . $name . '%')->where(['status' => 1])->with('ratings:id,rating,product_id', 'category:id,name,slug')->get();

        if ($products->isNotEmpty()) {
            foreach ($products as $product) {
                $product->image = asset('storage/products/' . $product->image);
                $rate = $product->ratings->avg('rating');
                $product->rating = number_format((float)$rate, 2, '.', '');
                unset($product->ratings);
            }

            return response()->json(['message' => 'Product search successfully', 'code' => 200, 'data' => $products], 200);
        } else {
            return response()->json(['message' => 'No Products Found', 'code' => 400], 400);
        }
    }

    public function filterProduct(Request $request)
    {
        

        if (!empty($request->category)) {
            $categories = DB::table('categories')->select('id')->where('parent_id', $request->parent_id)->whereIn('id', $request->category)->get();
        } else {
            $categories = DB::table('categories')->select('id', 'parent_id')->where('parent_id', $request->parent_id)->get();
        }

        $category_ids = [];
        foreach ($categories as $category) {
            array_push($category_ids, $category->id);
        }

        array_push($category_ids, $request->parent_id);

        $sortbyPrice = !empty($request->price_from) && !empty($request->price_to) ? 1 : 0;
        $price_from = $request->price_from;
        $price_to = $request->price_to;
        $lowtohigh = $request->sortby == 'lowtohigh' ? 1 : 0;
        $hightolow = $request->sortby == 'hightolow' ? 1 : 0;
        $name = $request->sortby == 'name' ? 1 : 0;

        $products = Product::select('id', 'name', 'regular_price', 'sale_price', 'slug', 'image', 'alt')->whereIn('parent_id', $category_ids)->where(['status' => 1])->when($sortbyPrice, function ($q) use ($price_from,$price_to) {
            $q->where('sale_price', '>=', $price_from)->where('sale_price', '<=', $price_to);
        })->when($lowtohigh, function ($q) {
            $q->orderBy('sale_price','asc');
        })->when($hightolow, function ($q) {
            $q->orderBy('sale_price','desc');
        })->when($name, function ($q) {
            $q->orderBy('name','asc');
        })->get();

        if ($products->isNotEmpty()) {
            foreach ($products as $product) {
                $product->image = asset('storage/products/' . $product->image);
                if($product->wishlist->isNotEmpty()) {
                    $product->is_wishlist_item = true;
                }else{
                    $product->is_wishlist_item = false;
                }
                unset($product->wishlist);
            }
        }
        
        if ($products->isNotEmpty()) {
            return response()->json(['message' => 'Product filtered successfully', 'code' => 200, 'data' => $products], 200);
        } else {
            return response()->json(['message' => 'No Products Found', 'code' => 400], 400);
        }
    }
}
