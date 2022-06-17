<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'               =>  'required',
            'coupon_id'             =>  'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors(), 'code' => 400], 400);
        }
        
		$usertoken = request()->bearerToken();
		if(empty($usertoken)){
			return response()->json(['message' => 'User is not logged in', 'code' => 400], 400);
		}
		$user = DB::table('users')->select('id')->where('api_token', $usertoken)->first();
		if(empty($user)){
			return response()->json(['message' => 'User is not logged in', 'code' => 400], 400);
		}

        $coupon = Coupon::where('id', $request->coupon_id)->with('coupon_used')->get();

        $carts = Cart::where('user_id', $user->id)->with('product')->get();

        $order_total = 0;
        $payable = 0;
        $product_discount = 0;
        $coupon_discount = 0;
        $total_discount = 0;
        $product_count = 0;
        if($carts->isNotEmpty()){
            foreach($carts as $cart){
                if(!isset($cart->product->coupon_discount)){
                    $cart->product->coupon_discount = 0;
                }
                $cart->product->image = asset('storage/products/'.$cart->product->image);
                $product_discount = $cart->product->regular_price - $cart->product->sale_price;
                $product_discount = $product_discount * $cart->quantity;
                $order_total += $cart->product->sale_price * $cart->quantity;
                $coupon_discount += $cart->product->coupon_discount * $cart->quantity;
                $total_discount += $product_discount + $coupon_discount;
                $product_count +=  $cart->quantity;
            }
            $payable = $order_total - $coupon_discount;
        }

        return response($carts);
    }

    public function couponDiscount($carts, $coupon)
    {
        if($coupon->type = 'global' && $coupon->coupon_limit < $coupon->times_applied){
            foreach($carts as $cart){
                if($coupon->disc_type == 'percent'){
                    $cart->product->coupon_discount += ($cart->product->sale_price * $coupon->discount) / 100;
                }else{
                    $cart->product->coupon_discount += $coupon->discount;
                }
            }
        }
        // cart_value_discount
        // referral_discount
        // personal_code
        // customer_based
        // global
        // merchandise
    }
}
