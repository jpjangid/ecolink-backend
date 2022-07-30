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

		$user = DB::table('users')->select('id', 'name', 'email', 'address', 'city', 'state', 'country', 'pincode', 'mobile', 'company_name')->find($user->id);

		$addresses = DB::table('user_addresses')->where('user_id', $user->id)->get();
		$lift_gate = DB::table('static_values')->where('name', 'Lift Gate')->first();
		$hazardous = DB::table('static_values')->where('name', 'Hazardous')->first();

		$lift_gate_amount = $lift_gate->value ?? 0;
		$hazardous_amount = $hazardous->value ?? 0;

		$order_total = 0;
		$payable = 0;
		$product_discount = 0;
		$total_discount = 0;
		$product_count = 0;
		$hazardous = 0;
		$hazardous_amt = 0;
		$hazardous_qty = 0;
		$lift_gate_qty = 0;
		$lift_gate_amt = 0;
		if ($carts->isNotEmpty()) {
			foreach ($carts as $cart) {
				$cart->product->image = asset('storage/products/' . $cart->product->image);
				$product_discount = $cart->product->regular_price - $cart->product->sale_price;
				$product_discount = $product_discount * $cart->quantity;
				$order_total += $cart->product->sale_price * $cart->quantity;
				$total_discount += $product_discount;
				$product_count +=  $cart->quantity;
				if ($cart->lift_gate == 1) {
					$lift_gate_qty += 1;
				}
				if ($cart->product->hazardous == 1) {
					$hazardous_qty += 1;
				}
			}
			if ($hazardous_qty > 0) {
				$hazardous_amt = $hazardous_amount;
			}

			if ($lift_gate_qty > 0) {
				$lift_gate_amt = $lift_gate_amount;
			}
			$payable = $order_total + $hazardous_amt;
		}

		$current = date('Y-m-d H:i:s');

		$coupons = Coupon::select('id', 'name', 'type', 'code', 'disc_type', 'discount')->where(['flag' => 0])->where([['offer_start', '<=', $current], ['offer_end', '>=', $current], ['min_order_amount', '<=', $order_total], ['max_order_amount', '>=', $order_total]])->orWhere([['type', 'customer_based'], ['user_id', $user->id], ['offer_start', '<=', $current], ['offer_end', '>=', $current], ['min_order_amount', '<=', $order_total], ['max_order_amount', '>=', $order_total]])->get();

		$data = collect(['carts' => $carts, 'user' => $user, 'order_total' => $order_total, 'payable' => $payable, 'total_discount' => $total_discount, 'product_count' => $product_count, 'coupons' => $coupons, 'addresses' => $addresses, 'hazardous_amt' => $hazardous_amt, 'lift_gate_amt' => $lift_gate_amt]);

		return response()->json(['message' => 'Data fetched Successfully', 'code' => 200, 'data' => $data], 200);
	}

	public function getFedexShippingRates(Request $request): \Illuminate\Http\JsonResponse
	{
		// $fedex_markup = DB::table('static_values')->where('name', 'FedEx Markup')->first();
		// $fedex_markup_percent = $fedex_markup->value ?? 0;

		$rate = $this->getFedexShipRate($request);
		return response()->json(['message' => 'Rate fetched successfully.', 'rate' => $rate, 'code' => '200'], 200);
		//return response()->json(['message' => 'Rate fetched successfully.', 'rate' => $rate, 'code' => '200'], 200);
	}

	public function getSaiaShippingRates(Request $request): \Illuminate\Http\JsonResponse
	{
		// $saia_markup = DB::table('static_values')->where('name', 'SAIA Markup')->first();
		// $saia_markup_percent = $saia_markup->value ?? 0;

		$rate = $this->getSaiaShipRate($request);
		return response()->json(['message' => 'Rate fetched successfully.', 'rate' => $rate, 'code' => '200'], 200);
		// return response()->json(['message' => 'Rate fetched successfully.', 'rate' => $rate, 'code' => '200'], 200);
	}
}
