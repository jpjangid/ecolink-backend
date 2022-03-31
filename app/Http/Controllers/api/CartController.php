<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function getCartItems(Request $request)
    {
        //Get Cart Items By User id with Product Detail
        $carts = Cart::where('user_id', $request->user_id)->with('user', 'product')->get();

        if($carts->isNotEmpty()){
            return response()->json(['carts' => $carts], 200);
        }else{
            return response()->json(['error' => 'No Product Found in Cart'], 400);
        }
    }
}
