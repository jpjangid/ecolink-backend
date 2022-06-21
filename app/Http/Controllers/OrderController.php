<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserAddress;
use App\Models\Order;
use App\Models\OrderItems;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Yajra\DataTables\DataTables;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        if (checkpermission('OrderController@index')) {
            if (request()->ajax()) {
                $active = $request->active == 'all' ? array('1', '2', '0') : array($request->active);
                /* Getting all records */
                $allorders = Order::select('id', 'order_no', 'order_status', 'payment_status', 'total_amount', 'created_at', 'order_comments', 'user_id')->where('flag', '0')->with([
                    'user:id,name,flag',
                    'user' => function ($q) use ($active) {
                        return $q->whereIn('flag', $active);
                    }
                ])->orderby('created_at','desc')->get();

                $orders = new Collection;
                foreach ($allorders as $order) {
                    if (!empty($order->user)) {
                        $orders->push([
                            'id'                => $order->id,
                            'order_no'          => $order->order_no,
                            'client'            => $order->user->name,
                            'order_status'      => $order->order_status,
                            'payment_status'    => $order->payment_status,
                            'total'             => '$' . number_format((float)$order->total_amount, 2, '.', ','),
                            'date'              => date('d-m-Y h:i A', strtotime($order->created_at)),
                            'order_comments'    => $order->order_comments,
                            'active'            => $order->user->flag == 0 ? 'Active' : 'Deactivated'
                        ]);
                    }
                }

                return Datatables::of($orders)
                    ->addIndexColumn()
                    /* Link to redirect on Order Detail Page */
                    ->addColumn('orderno', function ($row) {
                        $edit_url = url('admin/orders/order_detail', $row['id']);
                        $btn = '<a href="' . $edit_url . '">#' . $row['order_no'] . '</i></a>';
                        return $btn;
                    })
                    /* Adding Actions like edit, delete and show */
                    ->addColumn('action', function ($row) {
                        $delete_url = url('admin/orders/delete', $row['id']);
                        $edit_url = url('admin/orders/edit', $row['id']);
                        $btn = '<a class="btn btn-primary btn-xs ml-1" href="' . $edit_url . '"><i class="fas fa-edit"></i></a>';
                        return $btn;
                    })
                    ->rawColumns(['action', 'orderno'])
                    ->make(true);
            }
            return view('orders.index');
        } else {
            return redirect()->back()->with('danger', 'You dont have required permission!');
        }
    }

    public function create()
    {
        $products = DB::table('products')->where('status', 1)->orderBy('name', 'asc')->get();
        $users = DB::table('users')->where('role_id', '!=', 1)->where('flag', 0)->orderBy('name', 'asc')->get();

        return view('orders.create', compact('products', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
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
            'total_amt'             =>  'required',
            'total_qty'             =>  'required',
            'product_id.*'          =>  'required',
            'quantity.*'            =>  'required',
            'product_total'         =>  'required',
        ]);

        if (!empty($request->customer)) {
            $user_id = $request->customer;
        } else {
            /* Hashing password */
            $pass = Hash::make($request['billing_mobile']);

            /* Storing Data in Table */
            $user = User::create([
                'name'                  =>  $request['billing_name'],
                'email'                 =>  $request['billing_email'],
                'mobile'                =>  $request['billing_mobile'],
                'address'               =>  $request['billing_address'],
                'country'               =>  $request['billing_country'],
                'state'                 =>  $request['billing_state'],
                'city'                  =>  $request['billing_city'],
                'pincode'               =>  $request['billing_zip'],
                'password'              =>  $pass,
                'role_id'               =>  2,
            ]);

            UserAddress::create([
                'user_id'       =>  $user->id,
                'name'          =>  $request['billing_name'],
                'email'         =>  $request['billing_email'],
                'mobile'        =>  $request['billing_mobile'],
                'address'       =>  $request['billing_address'],
                'country'       =>  $request['billing_country'],
                'state'         =>  $request['billing_state'],
                'city'          =>  $request['billing_city'],
                'zip'           =>  $request['billing_zip'],
                'landmark'      =>  $request['billing_landmark'],
            ]);

            $user_id = $user->id;
        }
        $orderNumber = $this->order_no();

        $order = Order::create([
            'order_no'                  =>  $orderNumber,
            'user_id'                   =>  $user_id,
            'order_amount'              =>  $request->total_amt,
            'discount_applied'          =>  $request->discount,
            'total_amount'              =>  $request->total_amt,
            'no_items'                  =>  $request->total_qty,
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
            'order_status'              =>  $request->order_status,
            'payment_status'            =>  $request->payment_status,
            'shippment_via'             =>  $request->shippment_via,
            'payment_amount'            =>  $request->total_amt,
            'sale_price'                =>  $request->sale_price
        ]);

        foreach ($request->product_id as $key => $item) {
            $product = DB::table('products')->find($item);
            if (!empty($item) && !empty($request->quantity[$key])) {
                OrderItems::create([
                    'order_id'              =>  $order->id,
                    'product_id'            =>  $item,
                    'quantity'              =>  $request->quantity[$key],
                    'sale_price'            =>  $product->sale_price
                ]);
            }
        }

        return redirect('admin/orders')->with('success', 'Order Added Successfully');
    }

    public function edit($id)
    {
        $products = DB::table('products')->where('status', 1)->orderBy('name', 'asc')->get();
        $users = DB::table('users')->where('role_id', '!=', 1)->where('flag', 0)->orderBy('name', 'asc')->get();
        $order = Order::where('id', $id)->with('items')->first();
        
        return view('orders.edit', compact('products', 'users', 'order'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'billing_name'          =>  'required',
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
            'total_amt'             =>  'required',
            'total_qty'             =>  'required',
            'product_id.*'          =>  'required',
            'quantity.*'            =>  'required',
            'product_total'         =>  'required',
        ]);

        if (!empty($request->customer)) {
            $user_id = $request->customer;
        } else {
            $request->validate([
                'billing_mobile'        =>  'required|digits:10|unique:users,mobile',
                'billing_email'         =>  'required|email|unique:users,email',
            ]);
            /* Hashing password */
            $pass = Hash::make($request['billing_mobile']);

            /* Storing Data in Table */
            $user = User::create([
                'name'                  =>  $request['billing_name'],
                'email'                 =>  $request['billing_email'],
                'mobile'                =>  $request['billing_mobile'],
                'address'               =>  $request['billing_address'],
                'country'               =>  $request['billing_country'],
                'state'                 =>  $request['billing_state'],
                'city'                  =>  $request['billing_city'],
                'pincode'               =>  $request['billing_zip'],
                'password'              =>  $pass,
                'role_id'               =>  2,
            ]);

            UserAddress::create([
                'user_id'       =>  $user->id,
                'name'          =>  $request['billing_name'],
                'email'         =>  $request['billing_email'],
                'mobile'        =>  $request['billing_mobile'],
                'address'       =>  $request['billing_address'],
                'country'       =>  $request['billing_country'],
                'state'         =>  $request['billing_state'],
                'city'          =>  $request['billing_city'],
                'zip'           =>  $request['billing_zip'],
                'landmark'      =>  $request['billing_landmark'],
            ]);

            $user_id = $user->id;
        }

        $order = Order::where('id', $id)->update([
            'user_id'                   =>  $user_id,
            'order_amount'              =>  $request->total_amt,
            'discount_applied'          =>  $request->discount,
            'total_amount'              =>  $request->total_amt,
            'no_items'                  =>  $request->total_qty,
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
            'order_status'              =>  $request->order_status,
            'payment_status'            =>  $request->payment_status,
            'shippment_via'             =>  $request->shippment_via,
            'payment_amount'            =>  $request->total_amt,
            // 'sale_price'                =>  $request->sale_price
        ]);

        $items = OrderItems::where('order_id', $id)->get();
        foreach ($items as $item) {
            $item->delete();
        }

        foreach ($request->product_id as $key => $item) {
            if(!empty($item)){
                $product = DB::table('products')->find($item);
                if (!empty($item) && !empty($request->quantity[$key])) {
                    OrderItems::create([
                        'order_id'              =>  $id,
                        'product_id'            =>  $item,
                        'quantity'              =>  $request->quantity[$key],
                        'sale_price'            =>  $product->sale_price
                    ]);
                }
            }
        }

        return redirect('admin/orders')->with('success', 'Order Updated Successfully');
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

    public function order_detail($id)
    {
        if (checkpermission('OrderController@edit')) {
            /* Order Detail Page with user and order data */
            $order = Order::where('id', $id)->with('items.product', 'items.return', 'user')->first();
            $user = DB::table('users')->find($order->user_id);

            return view('orders.detail', compact('order'));
        } else {
            return redirect()->back()->with('danger', 'You dont have required permission!');
        }
    }

    public function update_detail(Request $request)
    {
        if ($request->order_status == 'success') {
            $update_order = Order::find($request->id);
            $update_order->order_status = $request->order_status;
            $update_order->payment_status = $request->order_status;
            $update_order->update();
        } else {
            $update_order = Order::find($request->id);
            $update_order->order_status = $request->order_status;
            $update_order->payment_status = $request->order_status;
            $update_order->update();
        }

        $data['message'] = 'Order Status Updated';
        return response()->json($data);
    }

    public function getAddresses(Request $request)
    {
        $addresses = DB::table('user_addresses')->select('id', 'address')->where('user_id', $request->id)->get();

        return response()->json($addresses);
    }

    public function getAddressDetail(Request $request)
    {
        $address = DB::table('user_addresses')->find($request->id);

        return response()->json($address);
    }

    public function getProductById(Request $request)
    {
        $product = DB::table('products')->find($request->id);

        return response()->json($product);
    }
}
