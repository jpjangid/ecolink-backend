<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->products = DB::table('products')->where(['status' => 1, 'flag' => 0])->orderBy('name', 'asc')->get();
    }

    public function salesReport(Request $request)
    {
        $products = $this->products;
        /* Getting all records */
        if (request()->ajax()) {
            $date = $request->date;
            $product = $request->product;
            $from_date = date('Y-m-d', strtotime('+1 day'));
            if ($request->date == 'week') {
                $day = date('w');
                $day = $day - 1;
                $to_date = date('Y-m-d', strtotime('-' . $day . ' days'));
            }
            if ($request->date == 'month') {
                $to_date = date('Y-m-d', strtotime(date('Y-m-01')));
            }
            if ($request->date == 'year') {
                $to_date = date('Y-m-d', strtotime(date('Y-01-01')));
            }
            $allorders = Order::select('id', 'order_no', 'user_id', 'total_amount', 'no_items', 'order_status', 'payment_via', 'payment_status', 'shippment_via', 'shippment_status', 'lift_gate_amt', 'hazardous_amt', 'shippment_rate', 'tax_amount', 'created_at')->where('flag', '0')->where([['created_at', '>=', $to_date], ['created_at', '<=', $from_date]])->with(['items:id,order_id,product_id,quantity', 'items.product:id,name,variant', 'user:id,name'])->orderBy('created_at', 'desc')->get();

            /* Converting Selected Data into desired format */
            $orders = new Collection();
            foreach ($allorders as $order) {
                $products = '';
                $count = 0;
                foreach ($order->items as $key => $item) {
                    $no = $key + 1;
                    $products .= '(' . $no . ') ';
                    $products .= $item->product->name . '(' . $item->product->variant. ')';
                    if($item->product_id == $product){
                        $count += 1;
                    }
                    $order->product = $products;
                    $order->count = $count;
                }
                if(!empty($product)){
                    if($order->count > 0){
                        $orders->push([
                            'order_no'          => $order->order_no,
                            'qty'               => $order->no_items,
                            'customer'          => $order->user->name,
                            'product'           => $order->product,
                            'amount'            => '$' . $order->total_amount,
                            'created_at'        => date('d-m-Y h:i A', strtotime($order->created_at)),
                        ]);
                    }
                }else{
                    $orders->push([
                        'order_no'          => $order->order_no,
                        'qty'               => $order->no_items,
                        'customer'          => $order->user->name,
                        'product'           => $order->product,
                        'amount'            => '$' . $order->total_amount,
                        'created_at'        => date('d-m-Y h:i A', strtotime($order->created_at)),
                    ]);
                }
            }

            /* Sending data through yajra datatable for server side rendering */
            return DataTables::of($orders)
                ->addIndexColumn()
                ->make(true);
        }
        return view('reports.salesreport', compact('products'));
    }

    public function abandonedCartReport(Request $request)
    {
        $products = $this->products;
        if (request()->ajax()) {
            $date = $request->date;
            $product = $request->product;
            $from_date = date('Y-m-d', strtotime('+1 day'));
            if ($request->date == 'week') {
                $day = date('w');
                $day = $day - 1;
                $to_date = date('Y-m-d', strtotime('-' . $day . ' days'));
            }
            if ($request->date == 'month') {
                $to_date = date('Y-m-d', strtotime(date('Y-m-01')));
            }
            if ($request->date == 'year') {
                $to_date = date('Y-m-d', strtotime(date('Y-01-01')));
            }
            $allcarts = Cart::select('id', 'user_id', 'product_id', 'quantity', 'created_at')->where([['created_at', '>=', $to_date], ['created_at', '<=', $from_date]])->when($product, function ($query, $product) {
                return $query->where('product_id', $product);
            })->with('user:id,name', 'product:id,name,variant')->orderby('created_at','desc')->get();

            /* Converting Selected Data into desired format */
            $carts = new Collection;
            foreach ($allcarts as $cart) {
                $carts->push([
                    'id'            => $cart->id,
                    'user'          => $cart->user->name,
                    'product'       => $cart->product->name. '(' . $cart->product->variant. ')',
                    'quantity'      => $cart->quantity,
                    'created_at'    => date('d-m-Y h:i A', strtotime($cart->created_at)),
                ]);
            }

            /* Sending data through yajra datatable for server side rendering */
            return Datatables::of($carts)
                ->addIndexColumn()
                ->make(true);
        }
        return view('reports.cartreport', compact('products'));
    }
}
