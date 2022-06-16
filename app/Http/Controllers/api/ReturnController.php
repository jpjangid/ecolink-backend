<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\ReturnItems;
use App\Models\Order;
use App\Models\OrderItems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ReturnController extends Controller
{

    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'           =>  'required',
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

        $return_orders = ReturnItems::where('user_id', $user->id)->with('item','order')->get();

        if($return_orders->isNotEmpty()){
            foreach($return_orders as $item){
                $item->product->image = url('storage/products',$item->product->image);
            }
            return response()->json(['message' => 'Data fetched Successfully', 'code' => 200, 'data' => $return_orders], 200);
        }else{
            return response()->json(['message' => 'No Data Found', 'code' => 400], 400);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'           =>  'required',
            'order_id'          =>  'required',
            'order_item_id'     =>  'required',
            'product_id'        =>  'required',
            'quantity'          =>  'required',
            'reason'            =>  'required',
            'description'       =>  'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors(), 'code' => 400], 400);
        }

        $product = DB::table('products')->find($request->product_id);
        $amount = $product->sale_price * $request->quantity;
        $orderNumber = $this->order_no();

        $usertoken = request()->bearerToken();
		if(empty($usertoken)){
			return response()->json(['message' => 'User is not logged in', 'code' => 400], 400);
		}
		$user = DB::table('users')->select('id')->where('api_token', $usertoken)->first();
		if(empty($user)){
			return response()->json(['message' => 'User is not logged in', 'code' => 400], 400);
		}

        $return = ReturnItems::create([
            'return_no'         =>  $orderNumber,
            'user_id'           =>  $user->id,
            'order_id'          =>  $request->order_id,
            'order_item_id'     =>  $request->order_item_id,
            'product_id'        =>  $request->product_id,
            'quantity'          =>  $request->quantity,
            'reason'            =>  $request->reason,
            'description'       =>  $request->description,
            'amount'            =>  $amount,
            'status'            =>  'pending'
        ]);

        $order = Order::where('id', $request->order_id)->with('items.return')->first();
        $total_order_items = $order->items->count();
        $return_items = 0;
        foreach ($order->items as $item) {
            // $orderitem = OrderItems::find($item->id);
            // $orderitem->quantity = $orderitem->quantity - $request->quantity;
            // $orderitem->update();
            if (!empty($item->return)) {
                $return_items += 1;
            }
        }
        if ($total_order_items == $return_items) {
            $order->flag = 1;
            $order->update();
        }

        return response()->json(['message' => 'Product return request placed successfully', 'code' => 200, 'data' => $return], 200);
    }

    public function order_no()
    {
        $no = strtoupper(Str::random(8));
        $order = DB::table('return_items')->where('return_no', $no)->first();
        if (!empty($order)) {
            return $this->order_no();
        } else {
            return $no;
        }
    }
}
