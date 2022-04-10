<?php

namespace App\Http\Controllers\api;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\UserAddress;
use App\Models\CouponUsedBy;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{

    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'               =>  'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors(), 'code' => 400], 400);
        }

        $orders = Order::where('user_id', $request->user_id)->with('items.product')->get();

        if($orders->isNotEmpty()){
            foreach($orders as $order){
                foreach($order->items as $item){
                    $item->product->image = url('storage/products',$item->product->image);
                }
            }
            return response()->json(['message' => 'Data fetched Successfully', 'code' => 200, 'data' => $orders], 200);
        }else{
            return response()->json(['message' => 'No Data Found', 'code' => 400], 400);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'               =>  'required',
            'order_amount'          =>  'required',
            'total_amount'          =>  'required',
            'product_discount'      =>  'required',
            'coupon_discount'       =>  'required',
            'no_items'              =>  'required',
            'billing_name'          =>  'required',
            'billing_mobile'        =>  'required|digits:10',
            'billing_email'         =>  'required|email',
            'billing_address'       =>  'required',
            'billing_city'          =>  'required',
            'billing_state'         =>  'required',
            'billing_country'       =>  'required',
            'billing_zip'           =>  'required',
            'shipping_name'         =>  'required',
            'shipping_mobile'       =>  'required|digits:10',
            'shipping_email'        =>  'required|email',
            'shipping_address'      =>  'required',
            'shipping_city'         =>  'required',
            'shipping_state'        =>  'required',
            'shipping_country'      =>  'required',
            'shipping_zip'          =>  'required',
            'payment_via'           =>  'required',
            'shippment_via'         =>  'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors(), 'code' => 400], 400);
        }

        $cartItems = Cart::where('user_id', $request->user_id)->with('product')->get();

        $orderNumber = $this->order_no();

        if (isset($request->addressSelect) && $request->addressSelect == 1) {
            $UserAddress = new UserAddress;
            $UserAddress->user_id   = $request->user_id;
            $UserAddress->name      = $request->billing_name;
            $UserAddress->mobile    = $request->billing_mobile;
            $UserAddress->address   = $request->billing_address;
            $UserAddress->zip       = $request->billing_zip;
            $UserAddress->landmark  = $request->billing_landmark;
            $UserAddress->state     = $request->billing_state;
            $UserAddress->city      = $request->billing_city;
            $UserAddress->save();

            if ($request->sameAsShip == 0) {
                $UserAddress = new UserAddress;
                $UserAddress->user_id   = $request->user_id;
                $UserAddress->name      = $request->shipping_name;
                $UserAddress->mobile    = $request->shipping_mobile;
                $UserAddress->address   = $request->shipping_address;
                $UserAddress->zip       = $request->shipping_zip;
                $UserAddress->landmark  = $request->shipping_landmark;
                $UserAddress->state     = $request->shipping_state;
                $UserAddress->city      = $request->shipping_city;
                $UserAddress->save();
            }
        }

        $coupon = '';
        if (isset($request->coupon_code) && $request->coupon_discount != 0) {
            $discount = $request->coupon_discount + (isset($request->product_discount) ? $request->product_discount : 0);
            $coupon = DB::table('coupons')->where('code', $request->coupon_code)->first();
            $coupon_id = $coupon->id;
        } else {
            $discount = isset($request->product_discount) ? $request->product_discount : 0;
        }

        $order = Order::create([
            'order_no'                  =>  $orderNumber,
            'user_id'                   =>  $request->user_id,
            'order_amount'              =>  $request->order_amount,
            'discount_applied'          =>  $discount,
            'total_amount'              =>  $request->total_amount,
            'no_items'                  =>  $request->no_items,
            'billing_name'              =>  $request->billing_name,
            'billing_mobile'            =>  $request->billing_mobile,
            'billing_email'             =>  $request->billing_email,
            'billing_address'           =>  $request->billing_address,
            'billing_country'           =>  $request->billing_country,
            'billing_state'             =>  $request->billing_state,
            'billing_city'              =>  $request->billing_city,
            'billing_zip'               =>  $request->billing_zip,
            'billing_landmark'          =>  $request->billing_landmark,
            'shipping_name'             =>  $request->shipping_name,
            'shipping_mobile'           =>  $request->shipping_mobile,
            'shipping_email'            =>  $request->shipping_email,
            'shipping_address'          =>  $request->shipping_address,
            'shipping_country'          =>  $request->shipping_country,
            'shipping_state'            =>  $request->shipping_state,
            'shipping_city'             =>  $request->shipping_city,
            'shipping_zip'              =>  $request->shipping_zip,
            'shipping_landmark'         =>  $request->shipping_landmark,
            'order_status'              =>  'pending',
            'payment_via'               =>  $request->payment_via,
            'payment_currency'          =>  'dollar',
            'payment_status'            =>  'pending',
            'shippment_via'             =>  $request->shippment_via,
            'shippment_status'          =>  'pending',
            'coupon_id'                 =>  isset($coupon_id) ? $coupon_id : '',
            'coupon_discount'           =>  $request->coupon_discount,
            'order_comments'            =>  $request->order_comments,
            'payment_amount'            =>  $request->total_amount,
        ]);

        foreach ($cartItems as $item) {
            OrderItems::create([
                'order_id'              =>  $order->id,
                'product_id'            =>  $item->product_id,
                'quantity'              =>  $item->quantity,
            ]);
        }

        if (!empty($coupon) && $request->coupon_discount != 0) {
            if ($coupon->type == 'merchandise' || $coupon->type == 'global' || $coupon->type == 'personal_code' || $coupon->type == 'cart_value_discount') {
                CouponUsedBy::create([
                    'coupon_id'         =>  $coupon->id,
                    'user_id'           =>  $request->user_id,
                    'order_id'          =>  $order->id,
                    'amount'            =>  $request->coupon_discount,
                    'applied_times'     =>  1,
                ]);

                $couponUsed = CouponUsedBy::where('coupon_id', $coupon->id)->get();

                if($coupon->coupon_limit == count($couponUsed)){
                    $coupon->status = 1;
                    $coupon->update();
                }
            } else {
                $coupon->times_applied  = $coupon->times_applied + 1;
                if($coupon->coupon_limit == $coupon->times_applied + 1){
                    $coupon->status = 1;
                }
                $coupon->update();
            }
        }

        if (!empty($order)) {
            foreach ($cartItems as $item) {
                $item->delete();
            }

            // $recent_order = Order::where('id', $order->id)->with('items.product.medias', 'user')->first();
            // $user = DB::table('users')->find($recent_order->user_id);
            // $image = array();
            // foreach ($recent_order->items as $item) {
            //     $media = DB::table('product_media')->where(['product_id' => $item->product->id, 'media_type' => 'image'])->orderby('sequence', 'asc')->first();
            //     if (!empty($media)) {
            //         array_push($image, $media->media);
            //     }
            // }
            // $status = 'ORDER PLACED!!';
            // Mail::to($recent_order->billing_email)
            //     ->cc(['lakhansharma.webanix@gmail.com', 'mohsinwebanix@gmail.com'])
            //     ->send(
            //         new OrderPlaced(
            //             $user->name,
            //             $recent_order->order_no,
            //             $user->name . ' your order has been placed successfully. Your order no. is #' . $recent_order->order_no . ' and you can find your purchase information below.',
            //             $recent_order,
            //             $image,
            //             $status
            //         )
            // );

            // sendSms($recent_order->billing_mobile, "Thank you for placing an order with us. We will be processing it soon. For any assistance plz mail us at enquiry@vaibhavstores.in. Thank you, Vaibhav Stores. PH: +9180 41518183");
        }
        // Notification::create(['title' => "New Order",'message' => 'New Order has beeen placed by '.auth()->user()->email.' with order no: '.$order->order_no]);
        return response()->json(['message' => 'Order Placed Successfully', 'code' => 200, 'data' => $order], 200);
    }

    public function order_no()
    {
        $no = strtoupper(Str::random(8));
        $order = DB::table('orders')->where('order_no', $no)->first();
        if (!empty($order)) {
            return $this->order_no();
        } else {
            return $no;
        }
    }
}
