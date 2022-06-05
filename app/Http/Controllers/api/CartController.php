<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function getCartItems(Request $request)
    {
        //Get Cart Items By User id with Product Detail
        $usertoken = request()->bearerToken();
        $user = DB::table('users')->select('id')->where('api_token', $usertoken)->first();
        $carts = Cart::select('id', 'user_id', 'product_id', 'quantity')->where('user_id', $user->id)->with('product:id,name,sale_price,image,alt,minimum_qty')->get();

        if ($carts->isNotEmpty()) {
            foreach ($carts as $cart) {
                $cart->product->image = asset('storage/products/' . $cart->product->image);
            }

            return response()->json(['message' => 'Data fetched Successfully', 'code' => 200, 'data' => $carts], 200);
        } else {
            return response()->json(['message' => 'No Product Found in Cart', 'code' => 400], 400);
        }
    }

    public function addCartItems(Request $request)
    {
        /* Storing Cart Items */
        $validator = Validator::make($request->all(), [
            'user_id'       => 'required',
            'product_id'    => 'required',
            'quantity'      => 'required',
            'action'        => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors(), 'code' => 400], 400);
        }

        $usertoken = request()->bearerToken();
        $user = DB::table('users')->select('id')->where('api_token', $usertoken)->first();

        $cart = Cart::where(['user_id' => $user->id, 'product_id' => $request->product_id])->first();

        if (!empty($cart)) {
            if ($request->action == 'add') {
                Cart::where('id', $cart->id)->update([
                    'quantity'      =>  $cart->quantity + $request->quantity,
                ]);
            } else {
                Cart::where('id', $cart->id)->update([
                    'quantity'      =>  $cart->quantity - $request->quantity,
                ]);
            }
        } else {
            Cart::create([
                'user_id'       =>  $user->id,
                'product_id'    =>  $request->product_id,
                'quantity'      =>  $request->quantity,
            ]);
        }

        $carts = Cart::select('id', 'user_id', 'product_id', 'quantity')->where('user_id', $user->id)->with('product:id,name,sale_price,image,alt,minimum_qty')->get();

        if ($carts->isNotEmpty()) {
            foreach ($carts as $cart) {
                $cart->product->image = asset('storage/products/' . $cart->product->image);
            }
        }

        return response()->json(['message' => 'Item added in cart successfully', 'code' => 200, 'data' => $carts], 200);
    }

    public function deleteCartItems(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'       => 'required',
            'product_id'    => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors(), 'code' => 400], 400);
        }

        $usertoken = request()->bearerToken();
        $user = DB::table('users')->select('id')->where('api_token', $usertoken)->first();

        $cart = Cart::where(['user_id' => $user->d, 'product_id' => $request->product_id])->first();

        if (!empty($cart)) {
            $cart->delete();

            $remainCartItems = Cart::select('id', 'user_id', 'product_id', 'quantity')->where('user_id', $user->id)->with('product:id,name,sale_price,image,alt,minimum_qty')->get();

            return response()->json(['message' => 'Product delete from cart successfully', 'data' => $remainCartItems, 'code' => 200], 200);
        } else {
            return response()->json(['message' => 'No Product Found in Cart', 'code' => 400], 400);
        }
    }
}
