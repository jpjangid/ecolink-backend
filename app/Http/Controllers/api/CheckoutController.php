<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $carts = Cart::where('user_id', $request->user_id)->with('product:id,name,sale_price,regular_price,image')->get();
        $user = DB::table('users')->find($request->user_id);

        $order_total = 0;
        $payable = 0;
        $total_discount = 0;
        $product_count = 0;
        $discount = 0;
        foreach($carts as $cart){
            $payable += $cart->product->sale_price * $cart->quantity;
            $order_total += $cart->product->regular_price * $cart->quantity;
            $discount = $cart->product->regular_price - $cart->product->sale_price;
            $total_discount += $discount * $cart->quantity;
            $product_count +=  $cart->quantity;
        }

        $data = collect(['carts' => $carts, 'user' => $user, 'order_total' => $order_total, 'payable' => $payable, 'total_discount' => $total_discount, 'product_count' => $product_count]);

        return response()->json(['message' => 'Data fetched Successfully', 'code' => 200, 'data' => $data], 200);
    }
}
