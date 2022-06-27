<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Coupon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\ShippingRate;

class CheckoutController extends Controller
{
	use ShippingRate;
 
	public function index(Request $request)
	{
		$user = $request->user();

		$carts = Cart::select('id', 'user_id', 'product_id', 'quantity', 'lift_gate')->where('user_id', $user->id)->with('product')->get();

		$user = DB::table('users')->select('id', 'name', 'email', 'address', 'city', 'state', 'country', 'pincode', 'mobile')->find($user->id);

		$addresses = DB::table('user_addresses')->where('user_id', $user->id)->get();

		$order_total = 0;
		$payable = 0;
		$product_discount = 0;
		$coupon_discount = 0;
		$total_discount = 0;
		$product_count = 0;
		$hazardous = 0;
		$hazardous_amt = 0;
		if ($carts->isNotEmpty()) {
			foreach ($carts as $cart) {
				if (!isset($cart->product->coupon_discount)) {
					$cart->product->coupon_discount = 0;
				}
				$cart->product->image = asset('storage/products/' . $cart->product->image);
				$product_discount = $cart->product->regular_price - $cart->product->sale_price;
				$product_discount = $product_discount * $cart->quantity;
				$order_total += $cart->product->sale_price * $cart->quantity;
				$coupon_discount += $cart->product->coupon_discount * $cart->quantity;
				$total_discount += $product_discount + $coupon_discount;
				$product_count +=  $cart->quantity;
				if ($cart->product->hazardous == 1) {
					$hazardous += 1;
				}
			}
			$payable = $order_total - $coupon_discount;
			if ($hazardous > 0) {
				$staticvalue = DB::table('static_values')->where('name', 'Hazardous')->first();
				if (!empty($staticvalue)) {
					$hazardous_amt = $staticvalue->value;
					#$payable = $payable + $staticvalue->value;
				}
			}
		}

		$current = date('Y-m-d H:i:s');

		$coupons = Coupon::select('id', 'name', 'code', 'disc_type', 'discount')->where(['flag' => 0])->where([['offer_start', '<=', $current], ['offer_end', '>=', $current]])->orWhere('user_id', $user->id)->get();

		$data = collect(['carts' => $carts, 'user' => $user, 'order_total' => $order_total, 'payable' => $payable, 'total_discount' => $total_discount, 'product_count' => $product_count, 'coupons' => $coupons, 'addresses' => $addresses, 'hazardous_amt' => $hazardous_amt]);

		return response()->json(['message' => 'Data fetched Successfully', 'code' => 200, 'data' => $data], 200);
	}

	public function getFedexShippingRates(Request $request): \Illuminate\Http\JsonResponse
  {
		$rate = $this->getFedexShipRate($request);
		return response()->json(['message' => 'Rate fetched successfully.', 'rate' => $rate, 'code' => '200'], 200);
	}

	public function getSaiaShippingRates(Request $request): \Illuminate\Http\JsonResponse
  {
		$rate = $this->getSaiaShipRate($request);
		return response()->json(['message' => 'Rate fetched successfully.', 'rate' => $rate, 'code' => '200'], 200);
	}
}
