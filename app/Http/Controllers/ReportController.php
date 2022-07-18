<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ReportController extends Controller
{
    public function salesReport(Request $request)
    {   
        /* Getting all records */
        if (request()->ajax()) {
            $date = $request->date;
            $from_date = date('Y-m-d');
            if($request->date == 'week'){
                $day = date('w');
                $day = $day - 1;
                $to_date = date('Y-m-d', strtotime('-'.$day.' days'));
            }
            if($request->date == 'month'){
                $to_date = date('Y-m-d', strtotime(date('Y-m-01')));
            }
            if($request->date == 'year'){
                $to_date = date('Y-m-d', strtotime(date('Y-01-01')));
            }
            $allorders = Order::select('id', 'order_no', 'user_id', 'total_amount', 'no_items', 'order_status', 'payment_via', 'payment_status', 'shippment_via', 'shippment_status', 'lift_gate_amt', 'hazardous_amt', 'shippment_rate', 'tax_amount', 'created_at')->where('flag', '0')->where([['created_at','>=',$to_date],['created_at','<=',$from_date]])->with('items:id,order_id,product_id,quantity', 'items.product:id,name,variant', 'user:id,name')->orderBy('created_at','desc')->get();

            /* Converting Selected Data into desired format */
            $orders = new Collection();
            foreach ($allorders as $order) {
                $orders->push([
                    'order_no'          => $order->order_no,
                    'qty'               => $order->no_items,
                    'customer'          => $order->user->name,
                    'amount'            => '$'.$order->total_amount,
                    'created_at'        => date('d-m-Y h:i A', strtotime($order->created_at)),
                ]);
            }

            /* Sending data through yajra datatable for server side rendering */
            return DataTables::of($orders)
                ->addIndexColumn()
                ->make(true);
        }
        return view('reports.salesreport');
    }
}
