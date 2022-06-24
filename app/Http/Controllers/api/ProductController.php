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
    $usertoken = request()->bearerToken();
    $user_id = '';
    if (empty($usertoken)) {
      $user = DB::table('users')->select('id')->where('api_token', $usertoken)->first();
      if (!empty($user)) {
        $user_id = $user->id;
      }
    }
    
    $product = Product::where(['slug' => $request->slug, 'status' => 1])->with(['wishlist' => function ($query) use ($user_id) {
      $query->where('user_id', $user_id);
    }, 'category:id,slug'])->first();
    
    if (!empty($product)) {
      $product->image = asset('storage/products/' . $product->image);
      $related_products = $this->relatedProducts($product);
      $variants = $this->variants($product);
      
      if ($product->wishlist->isNotEmpty()) {
        $product->is_wishlist_item = true;
      } else {
        $product->is_wishlist_item = false;
      }
      unset($product->wishlist);
      
      $data = collect(['product' => $product, 'related_products' => $related_products, 'variants' => $variants]);
      
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
    
    $usertoken = request()->bearerToken();
    $user_id = '';
    if (empty($usertoken)) {
      $user = DB::table('users')->select('id')->where('api_token', $usertoken)->first();
      if (!empty($user)) {
        $user_id = $user->id;
      }
    }
    
    $product = Product::where(['id' => $request->product_id, 'status' => 1])->with('wishlist', function ($query) use ($user_id) {
      $query->where('user_id', $user_id);
    })->first();
    
    if (!empty($product)) {
      $product->image = asset('storage/products/' . $product->image);
      $related_products = $this->relatedProducts($product);
      $variants = $this->variants($product);
      if ($product->wishlist->isNotEmpty()) {
        $product->is_wishlist_item = true;
      } else {
        $product->is_wishlist_item = false;
      }
      unset($product->wishlist);
      
      return response()->json(['message' => 'Data fetched Successfully', 'code' => 200, 'data' => $product], 200);
    } else {
      return response()->json(['message' => 'No Data Found', 'code' => 400], 400);
    }
  }
  
  public function variants($product)
  {
    $variants = Product::select('id', 'name', 'slug', 'variant')->where(['status' => 1, 'name' => $product->name])->where('id', '!=', $product->id)->get();
    
    return $variants;
  }
  
  public function relatedProducts($product)
  {
    $related_products = collect();
    $usertoken = request()->bearerToken();
    $user_id = '';
    if (empty($usertoken)) {
      $user = DB::table('users')->select('id')->where('api_token', $usertoken)->first();
      if (!empty($user)) {
        $user_id = $user->id;
      }
    }
    
    $related = Product::select('id', 'name', 'slug', 'image', 'alt', 'sale_price', 'regular_price', 'variant')->where(['status' => 1, 'parent_id' => $product->parent_id])->where('id', '!=', $product->id)->with('wishlist', function ($query) use ($user_id) {
      $query->where('user_id', $user_id);
    })->get();
    
    $related_products = $related_products->concat($related);
    
    $categories = collect();
    if ($related_products->isEmpty()) {
      $parent = Category::find($product->parent_id);
      if (!empty($parent->parent_id)) {
        $related = Product::select('id', 'name', 'slug', 'image', 'alt', 'sale_price', 'regular_price', 'variant')->where(['status' => 1, 'parent_id' => $parent->parent_id])->where('id', '!=', $product->id)->with('wishlist', function ($query) use ($user_id) {
          $query->where('user_id', $user_id);
        })->get();
        
        $related_products = $related_products->concat($related);
        
        $categories = DB::table('categories')->select('id')->where('id', '!=', $parent->id)->where('parent_id', $parent->parent_id)->get();
        
        if ($categories->isEmpty()) {
          $maincats = DB::table('categories')->select('id')->where('id', '!=', $parent->id)->where('parent_id', NULL)->get();
          $maincat_ids = array();
          foreach ($maincats as $maincat) {
            array_push($maincat_ids, $maincat->id);
          }
          $categories = DB::table('categories')->select('id')->whereIn('parent_id', $maincat_ids)->get();
        }
      }
    }
    
    if ($related_products->isEmpty()) {
      $maincats = DB::table('categories')->select('id')->where('id', '!=', $parent->id)->where('parent_id', NULL)->get();
      $maincat_ids = array();
      foreach ($maincats as $maincat) {
        array_push($maincat_ids, $maincat->id);
      }
      
      $related = Product::select('id', 'name', 'slug', 'image', 'alt', 'sale_price', 'regular_price', 'variant')->where(['status' => 1])->where('id', '!=', $product->id)->whereIn('parent_id', $maincat_ids)->with('wishlist', function ($query) use ($user_id) {
        $query->where('user_id', $user_id);
      })->get();
      
      $related_products = $related_products->concat($related);
      
      $categories = DB::table('categories')->select('id')->whereIn('parent_id', $maincat_ids)->get();
    }
    
    if ($categories->isNotEmpty()) {
      $cat_ids = array();
      foreach ($categories as $cat) {
        array_push($cat_ids, $cat->id);
      }
      
      $related = Product::select('id', 'name', 'slug', 'image', 'alt', 'sale_price', 'regular_price', 'variant')->where(['status' => 1])->where('id', '!=', $product->id)->whereIn('parent_id', $cat_ids)->with('wishlist', function ($query) use ($user_id) {
        $query->where('user_id', $user_id);
      })->get();
      
      $related_products = $related_products->concat($related);
    }
    
    foreach ($related_products as $related_product) {
      $related_product->image = asset('storage/products/' . $related_product->image);
      $related_product->sale_price = number_format((float)$related_product->sale_price, 2, '.', '');
      $related_product->regular_price = number_format((float)$related_product->regular_price, 2, '.', '');
      if ($related_product->wishlist->isNotEmpty()) {
        $related_product->is_wishlist_item = true;
      } else {
        $related_product->is_wishlist_item = false;
      }
      unset($related_product->wishlist);
    }
    
    return $related_products;
  }
}
