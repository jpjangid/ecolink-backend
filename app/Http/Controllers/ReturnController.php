<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\ReturnMail;
use App\Models\Coupon;
use App\Models\ReturnItems;
use Illuminate\Support\Collection;
use Yajra\DataTables\DataTables;
use GuzzleHttp\Client;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Product;
use App\Models\User;
use App\Models\Wallet;
use Exception;
use Illuminate\Support\Facades\Mail;

class ReturnController extends Controller
{
    public function index()
    {
        if (checkpermission('ReturnController@index')) {
            if (request()->ajax()) {
                /* Getting all records */
                $all_return_orders = ReturnItems::with('item.order', 'user', 'product')->orderby('created_at','desc')->get();

                /* Converting Selected Data into desired format */
                $return_orders = new Collection;
                foreach ($all_return_orders as $return_order) {
                    $return_orders->push([
                        'id'                => $return_order->id,
                        'order_no'          => '#' . $return_order->item->order->order_no,
                        'sku'               => $return_order->product->sku,
                        'return_no'         => $return_order->return_no,
                        'client'            => $return_order->user->name,
                        'reason'            => $return_order->reason,
                        'return_status'     => $return_order->status,
                        'date'              => date('d-m-Y H:i', strtotime($return_order->created_at)),
                    ]);
                }

                /* Sending data through yajra datatable for server side rendering */
                return Datatables::of($return_orders)
                    ->addIndexColumn()
                    /* Link to redirect on Return Order Detail Page */
                    ->addColumn('returnno', function ($row) {
                        $edit_url = url('admin/returns/return_detail', $row['id']);
                        $btn = '<a href="' . $edit_url . '">#' . $row['return_no'] . '</i></a>';
                        return $btn;
                    })
                    ->rawColumns(['returnno'])
                    ->make(true);
            }
            return view('returns.index');
        } else {
            return redirect()->back()->with('danger', 'You dont have required permission!');
        }
    }

    public function return_detail($id)
    {
        if (checkpermission('ReturnController@edit')) {
            /* Return Order Detail Page with user and order data */
            $return_order = ReturnItems::where('id', $id)->with('item', 'order', 'user', 'product')->first();
            $order_id = $return_order->order_id;
            $order = Order::where('id', $order_id)->with('items.product')->first();
            $total = 0.00;
            $itemtotal = 0.00;
            foreach ($order->items as $order_item) {
                if ($order_item->id == $return_order->order_item_id) {
                    $itemtotal = $order_item->product->sale_price * $order_item->quantity;
                }
            }

            if (!empty($order->coupon_id)) {
                $coupon = Coupon::find($order->coupon_id);
                $total = $this->coupon($coupon, $return_order);
            } else {
                $total = $itemtotal;
            }

            return view('returns.detail', compact('return_order', 'total', 'order'));
        } else {
            return redirect()->back()->with('danger', 'You dont have required permission!');
        }
    }

    public function update(Request $request)
    {
        $return_order = ReturnItems::find($request->id);
        $return_order->status = $request->status;
        $return_order->update();

        $update_order = OrderItems::where('id', $return_order->order_item_id)->with('product')->first();
        $update_order->item_status = $return_order->status;
        $update_order->flag = '1';
        $update_order->update();

        $placed_order = Order::find($return_order->order_id);
        $user = User::find($placed_order->user_id);

        if ($request->status == 'Return Approved') {
            $response = $this->create_return_order_on_ship_rocket($return_order);
            if (!empty($response['$m_response']) && $response['$m_response']->status == 'RETURN PENDING') {
                $update_return_order = ReturnItems::find($request->id);
                $update_return_order->shiprocket_order_id = $response['$m_response']->order_id;
                $update_return_order->shiprocket_shipment_id = $response['$m_response']->shipment_id;
                $update_return_order->amount = $response['total'];
                $update_return_order->update();

                $order = Order::find($return_order->order_id);
                $order->total_amount = $order->total_amount - $response['total'];
                $order->no_items = $order->no_items - 1;
                $order->update();
            }

            $subject = 'YOUR RETURN REQUEST IS ACCEPTED!';
            $message = $user->name . ' your return request has been approved & and the product will be picked within 10 business days. Your order no. is #' . $placed_order->order_no . ' and you can find your purchase information below.';
        }

        if ($request->status == 'Refund Completed via Acc') {
            $order_id = $return_order->order_id;
            $order = Order::where('id', $order_id)->with('items.product')->first();
            $total = 0.00;
            $itemtotal = 0.00;
            foreach ($order->items as $order_item) {
                if ($order_item->id == $return_order->order_item_id) {
                    $itemtotal = $order_item->product->sale_price * $order_item->quantity;
                }
            }

            if (!empty($order->coupon_id)) {
                $coupon = Coupon::find($order->coupon_id);
                $total = $this->coupon($coupon, $return_order);
            } else {
                $total = $itemtotal;
            }

            $order->total_amount = $order->total_amount - $response['total'];
            $order->no_items = $order->no_items - 1;
            $order->update();

            $subject = 'YOUR REFUND REQUEST IS ACCEPTED!';
            $message = $user->name . ' your refund request is approved and refund completed via Account. Your order no. is #' . $placed_order->order_no . ' and you can find your purchase information below.';
        }

        if ($request->status == 'Refund Completed via Voucher') {
            $order_id = $return_order->order_id;
            $order = Order::where('id', $order_id)->with('items.product')->first();
            $total = 0.00;
            $itemtotal = 0.00;
            foreach ($order->items as $order_item) {
                if ($order_item->id == $return_order->order_item_id) {
                    $itemtotal = $order_item->product->sale_price * $order_item->quantity;
                }
            }

            if (!empty($order->coupon_id)) {
                $coupon = Coupon::find($order->coupon_id);
                $total = $this->coupon($coupon, $return_order);
            } else {
                $total = $itemtotal;
            }

            $order->total_amount = $order->total_amount - $response['total'];
            $order->no_items = $order->no_items - 1;
            $order->update();

            if ($order->payment_status == 'success') {
                Wallet::create([
                    'user_id'       =>  $order->user_id,
                    'order_id'      =>  $order->id,
                    'amount'        =>  $total,
                    'remark'        =>  'Refund Amount',
                    'status'        =>  'credited',
                ]);
            }

            $subject = 'YOUR REFUND REQUEST IS ACCEPTED!';
            $message = $user->name . ' your refund request is approved and refund completed via wallet money. Your order no. is #' . $placed_order->order_no . ' and you can find your purchase information below.';
        }

        if ($request->status == 'Exchange Initiated') {
            $subject = 'YOUR EXCHANGE IS INITIATED!';
            $message = $user->name . ' your exchange initiated. Your order no. is #' . $placed_order->order_no . ' and you can find your purchase information below.';
        }

        if ($request->status == 'Exchange Approved') {
            $subject = 'YOUR EXCHANGE IS APPROVED!';
            $message = $user->name . ' your exchange approved. Your order no. is #' . $placed_order->order_no . ' and you can find your purchase information below.';
        }

        if ($request->status == 'Exchange Completed') {
            $subject = 'YOUR EXCHANGE ORDER IS COMPLETED!';
            $message = $user->name . ' your exchange completed. Your order no. is #' . $placed_order->order_no . ' and you can find your purchase information below.';
        }

        if ($request->status == 'Return/Exchange Rejected') {
            $subject = 'YOUR RETURN REQUEST IS NOT APPROVED!';
            $message = $user->name . ' your Return/Exchange Rejected. Your order no. is #' . $placed_order->order_no . ' and you can find your purchase information below.';
        }

        if ($request->status == 'Cancel Approved') {
            $subject = 'Cancel Request Approved!';
            $message = $user->name . ' your Cancel Approved. Your order no. is #' . $placed_order->order_no . ' and you can find your purchase information below.';
        }

        $status = $request->status;


        $data['message'] = 'Return Order Status Updated';
        return response()->json($data);
    }

    public function coupon($coupon, $return_order)
    {
        $item = OrderItems::find($return_order->order_item_id);
        $product = Product::where('id', $return_order->product_id)->with('category.parent.parent')->first();
        $cat_id = $product->category->id;

        if ($coupon->type == 'merchandise') {
            if (
                !empty($coupon->product_id) && !empty($product->id) && $coupon->product_id == $product->id
            ) {
                return $this->disc_apply($item, $coupon);
            } elseif (
                !empty($coupon->sub_cat_id) && !empty($sub_cat_id) && $coupon->sub_cat_id == $sub_cat_id
            ) {
                return $this->disc_apply($item, $coupon);
            } elseif (
                !empty($coupon->cat_id) && !empty($sub_cat_id) && $coupon->cat_id == $sub_cat_id
            ) {
                return $this->disc_apply($item, $coupon);
            } elseif (
                !empty($coupon->main_cat_id) && !empty($sub_cat_id) && $coupon->main_cat_id == $sub_cat_id
            ) {
                return $this->disc_apply($item, $coupon);
            } elseif (
                !empty($coupon->cat_id) && !empty($cat_id) && $coupon->cat_id == $cat_id
            ) {
                return $this->disc_apply($item, $coupon);
            } elseif (
                !empty($coupon->main_cat_id) && !empty($cat_id) && $coupon->main_cat_id == $cat_id
            ) {
                return $this->disc_apply($item, $coupon);
            } elseif (
                !empty($coupon->main_cat_id) && !empty($main_cat_id) && $coupon->main_cat_id == $main_cat_id
            ) {
                return $this->disc_apply($item, $coupon);
            } elseif (
                !empty($coupon->brand_id) && !empty($brand_id) && $coupon->brand_id == $brand_id
            ) {
                return $this->disc_apply($item, $coupon);
            } else {
                $example_coupon = '';
                return $this->disc_apply($item, $example_coupon);
            }
        } else {
            return $this->disc_apply($item, $coupon);
        }
    }

    public function disc_apply($item, $coupon)
    {
        $disc = 0.00;
        $sale = 0.00;
        if (!empty($coupon)) {
            if ($coupon->disc_type == 'percent') {
                $disc = floatval((floatval($item->product->sale_price) * $coupon->discount) / 100);
                $sale = (floatval($item->product->sale_price) - floatval($disc)) * $item->quantity;
            }
            if ($coupon->disc_type == 'amount') {
                $disc = $coupon->discount;
                $sale = (floatval($item->product->sale_price) - $coupon->discount) * $item->quantity;
            }
        } else {
            $sale = floatval($item->product->sale_price) * $item->quantity;
        }

        return $sale;
    }
}
