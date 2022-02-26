<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Yajra\DataTables\DataTables;
use GuzzleHttp\Client;

class OrderController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $allorders = Order::where('flag', '0')->with('items', 'user')->orderby('created_at', 'desc')->get();

            $orders = new Collection;
            foreach ($allorders as $order) {
                $orders->push([
                    'id'    => $order->id,
                    'order_no'  => $order->order_no,
                    'client'  => $order->user->name,
                    'order_status' => $order->order_status,
                    'payment_status' => $order->payment_status,
                    'total' => number_format((float)$order->total_amount, 2, '.', ''),
                    'date'  => date('d-m-Y h:i A', strtotime($order->created_at)),
                    'order_comments' => $order->order_comments
                ]);
            }

            return Datatables::of($orders)
                ->addIndexColumn()
                ->addColumn('orderno', function ($row) {
                    $edit_url = url('admin/orders/order_detail', $row['id']);
                    $btn = '<a href="' . $edit_url . '">#' . $row['order_no'] . '</i></a>';
                    return $btn;
                })
                ->rawColumns(['orderno'])
                ->make(true);
        }
        return view('orders.index');
    }

    public function order_detail($id)
    {
        $order = Order::where('id', $id)->with('items.product', 'items.return', 'user')->first();
        $user = DB::table('users')->find($order->user_id);

        return view('orders.detail', compact('order'));
    }

    public function update(Request $request)
    {
        if ($request->order_status == 'success') {
            $response = $this->create_shiprocket_order($request->id);
            if (!empty($response) && $response->status == 'NEW') {
                $update_order = Order::find($request->id);
                $update_order->shiprocket_order_id = $response->order_id;
                $update_order->shiprocket_shipment_id = $response->shipment_id;
                $update_order->order_status = $request->order_status;
                $update_order->payment_status = $request->order_status;
                $update_order->update();
            }
        } else {
            $update_order = Order::find($request->id);
            $update_order->order_status = $request->order_status;
            $update_order->payment_status = $request->order_status;
            $update_order->update();
        }

        $data['message'] = 'Order Status Updated';
        return response()->json($data);
    }
}
