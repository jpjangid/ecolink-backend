<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
  public function getCartItems(Request $request): \Illuminate\Http\JsonResponse
  {
    //Get Cart Items By User id with Product Detail
    $user = $request->user();
    
    $carts = Cart::select('id', 'user_id', 'product_id', 'quantity', 'lift_gate')->where('user_id', $user->id)->with(['product:id,name,sale_price,image,alt,minimum_qty,slug,parent_id,hazardous', 'product.category:id,slug'])->get();
    
    if ($carts->isNotEmpty()) {
      foreach ($carts as $cart) {
        $cart->product->image = asset('storage/products/' . $cart->product->image);
      }
      return response()->json(['message' => 'Data fetched Successfully', 'code' => 200, 'data' => $carts], 200);
    } else {
      return response()->json(['message' => 'No Product Found in Cart', 'code' => 400], 400);
    }
  }
  
  public function addCartItems(Request $request): \Illuminate\Http\JsonResponse
  {
    /* Storing Cart Items */
    $validator = Validator::make($request->all(), [
      'user_id' => 'required',
      'product_id' => 'required',
      'quantity' => 'required',
      'action' => 'required',
    ]);
    
    if ($validator->fails()) {
      return response()->json(['message' => $validator->errors(), 'code' => 400], 400);
    }
    
    $user = $request->user();
    
    $product = Product::find($request->product_id);
    if ($product == null) {
      return response()->json(['message' => 'Product not found'], 404);
    }
    
    $cart = Cart::where(['user_id' => $user->id, 'product_id' => $request->product_id])->first();
    
    if (!empty($cart)) {
      if ($request->action == 'add') {
        Cart::where('id', $cart->id)->update(['quantity' => $cart->quantity + $request->quantity, 'lift_gate' => $request->lift_gate]);
      } else {
        if (($cart->quantity - $request->quantity) < $product->minimum_qty) {
          return response()->json(['message' => 'Invalid quantity'], 400);
        }
        Cart::where('id', $cart->id)->update([
          'quantity' => $cart->quantity - $request->quantity,
        ]);
      }
    } else {
      if ($request->quantity > $product->stock) {
        return response()->json(['message' => 'Quantity is out of stock. Only ' . $product->stock . 'product remains in stock'], 400);
      }
      Cart::create(['user_id' => $user->id, 'product_id' => $request->product_id, 'quantity' => $request->quantity]);
    }
    
    $carts = Cart::select('id', 'user_id', 'product_id', 'quantity', 'lift_gate')->where('user_id', $user->id)->with(['product:id,name,sale_price,image,alt,minimum_qty,slug,parent_id,hazardous', 'product.category:id,slug'])->get();
    
    if ($carts->isNotEmpty()) {
      foreach ($carts as $cart) {
        $cart->product->image = asset('storage/products/' . $cart->product->image);
      }
    }
    
    return response()->json(['message' => 'Item added in cart successfully', 'code' => 200, 'data' => $carts], 200);
  }

  public function addCookiCartItems(Request $request)
  {
    /*If user exist then add items to cart*/
    if(!empty($request->user())) {
        $user = $request->user();
        if(count($request->products) > 0) {
          foreach($request->products as $request_product) {
            if($request_product['product_id'] != "") {
              $product = Product::find($request_product['product_id']);
              if ($product == null) {
                return response()->json(['message' => 'Product not found'], 404);
              }
          
              $cart = Cart::where(['user_id' => $user->id, 'product_id' => $request_product['product_id']])->first();
          
              if (!empty($cart)) {
                if ($request_product['action'] == 'add') {
                  Cart::where('id', $cart->id)->update(['quantity' => $cart->quantity + $request_product['quantity'], 'lift_gate' => $request_product['lift_gate']]);
                } else {
                  if (($cart->quantity - $request_product['quantity']) < $product->minimum_qty) {
                    return response()->json(['message' => 'Invalid quantity'], 400);
                  }
                  Cart::where('id', $cart->id)->update([
                    'quantity' => $cart->quantity - $request_product['quantity'],
                  ]);
                }
              } else {
                if ($request_product['quantity'] > $product->stock) {
                  return response()->json(['message' => 'Quantity is out of stock. Only ' . $product->stock . 'product remains in stock'], 400);
                }
                Cart::create(['user_id' => $user->id, 'product_id' => $request_product['product_id'], 'quantity' => $request_product['quantity']]);
              }
            }
          }
        }
    
        /*Getting all cart times*/
        $carts = Cart::select('id', 'user_id', 'product_id', 'quantity', 'lift_gate')->where('user_id', $user->id)->with(['product:id,name,sale_price,image,alt,minimum_qty,slug,parent_id,hazardous', 'product.category:id,slug'])->get();
    
        /*Updating images path of cart products*/
        if ($carts->isNotEmpty()) {
          foreach ($carts as $cart) {
            $cart->product->image = asset('storage/products/' . $cart->product->image);
          }
        }
    
        return response()->json(['message' => 'Item added in cart successfully', 'code' => 200, 'data' => $carts], 200);
    }

    return response()->json(['message' => 'User not found', 'code' => 400], 400);
  }
  
  public function deleteCartItems(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'user_id' => 'required',
      'product_id' => 'required',
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
    
    $cart = Cart::where(['user_id' => $user->id, 'product_id' => $request->product_id])->first();
    
    if (!empty($cart)) {
      $cart->delete();
      
      $remainCartItems = Cart::select('id', 'user_id', 'product_id', 'quantity', 'lift_gate')->where('user_id', $user->id)->with(['product:id,name,sale_price,image,alt,minimum_qty,slug,parent_id,hazardous', 'product.category:id,slug'])->get();
      if ($remainCartItems->isNotEmpty()) {
        foreach ($remainCartItems as $remainCartItem) {
          $remainCartItem->product->image = asset('storage/products/' . $remainCartItem->product->image);
        }
      }
      
      return response()->json(['message' => 'Product delete from cart successfully', 'data' => $remainCartItems, 'code' => 200], 200);
    } else {
      return response()->json(['message' => 'No Product Found in Cart', 'code' => 400], 400);
    }
  }
}
