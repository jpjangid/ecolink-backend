<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\Order;
use App\Models\OrderItems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Traits\ShippingRate;
use App\Traits\QboRefreshToken;
use App\Traits\ShipVia;
use App\Traits\QuickBooksOnline;

class OrderController extends Controller
{
    use ShippingRate;
    use QboRefreshToken;
    use ShipVia;
    use QuickBooksOnline;

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
                ])->orderby('created_at', 'desc')->get();

                $orders = new Collection;
                foreach ($allorders as $order) {
                    if (!empty($order->user)) {
                        $orders->push([
                            'id'                    => $order->id,
                            'order_no'              => $order->order_no,
                            'client'                => $order->user->name,
                            'order_status'          => ucfirst(strtolower($order->order_status)),
                            'payment_status'        => ucfirst(strtolower($order->payment_status)),
                            'total'                 => '$' . number_format((float)$order->total_amount, 2, '.', ','),
                            'date'                  => date('m-d-Y H:i', strtotime($order->created_at)),
                            'order_comments'        => $order->order_comments,
                            'active'                => $order->user->flag == 0 ? 'Active' : 'Deactivated'
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
        $products = DB::table('products')->where(['status' => 1, 'flag' => 0])->orderBy('name', 'asc')->get();
        $users = DB::table('users')->where('role_id', '!=', 1)->where('flag', 0)->orderBy('name', 'asc')->get();
        $lift_gate = DB::table('static_values')->where('name', 'Lift Gate')->first();
        $cert_fee = DB::table('static_values')->where('name', 'CERT Fee')->first();

        return view('orders.create', compact('products', 'users', 'lift_gate', 'cert_fee'));
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

        DB::beginTransaction();

        try {
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
                    // 'landmark'      =>  $request['billing_landmark'],
                ]);

                $user_id = $user->id;
            }

            $orderNumber = $this->order_no();

            $sale_price = 0;
            $hazardous_qty = 0;
            $hazardous_amt = 0;
            $lift_gate_amt = 0;
            $cert_fee_amt = 0;
            $discount_amt = 0;
            $items = 0;
            $total_weight = 0;
            foreach ($request->product_id as $key => $product_id) {
                $items += 1;
                $product = DB::table('products')->find($product_id);
                $sale_price += $product->sale_price * $request->quantity[$key];
                $total_weight += $product->weight * $request->quantity[$key];
                if ($product->hazardous == 1) {
                    $hazardous_qty += 1;
                }
            }

            $newRequest = new Request(['city' => $request->shipping_city, 'state' => $request->shipping_state, 'zip' => $request->shipping_zip, 'country' => $request->shipping_country, 'product_id' => $request->product_id]);

            $shipment_via = 0;
            $shipping_charge = 0;
            if ($total_weight >= 71) {
                $shipment_via = 'saia';
                $shipping_charge = $this->getSaiaShipRate($newRequest);
            } else {
                $shipment_via = 'fedex';
                $shipping_charge = $this->getFedexShipRate($newRequest);
            }

            $order_amt = $sale_price;
            $lift_gate = DB::table('static_values')->where('name', 'Lift Gate')->first();
            $cert_fee = DB::table('static_values')->where('name', 'CERT Fee')->first();
            $hazardous = DB::table('static_values')->where('name', 'Hazardous')->first();
            $lift_gate_amount = $lift_gate->value ?? 0;
            $cert_fee_amount = $cert_fee->value ?? 0;
            $hazardous_amount = $hazardous->value ?? 0;
            if ($hazardous_qty > 0) {
                $hazardous_amt = $hazardous_amount;
            }

            if ($request->lift_gate == 1) {
                $lift_gate_amt = $lift_gate_amount;
            }

            if ($request->cert_fee == 1) {
                $cert_fee_amt = $cert_fee_amount;
            }

            $coupon_id = '';
            $coupon = '';
            if (!empty($request->coupon)) {
                $coupon = DB::table('coupons')->find($request->coupon);
                if ($coupon != null) {
                    $coupon_id = $coupon->id;
                    if ($coupon->disc_type == 'percent') {
                        $discount_amt = ($order_amt * $coupon->discount) / 100;
                        $discount_amt = number_format((float)$discount_amt, 2, '.', '');
                    } else {
                        $discount_amt = $coupon->discount;
                    }
                }
            }

            $taxAmount = 0;
            $tax = DB::table('tax_rates')->select('rate')->where('zip', $request->shipping_zip)->first();
            $user = DB::table('users')->find($user_id);
            if($user->tax_exempt == 0) {
                if ($tax != null) {
                    if ($coupon != null && $coupon->type == 'cart_value_discount' && $coupon->disc_type == 'percent' && $coupon->discount == '100') {
                        $taxAmount = 0;
                        $coupon_id = '';
                    } else {
                        $taxAmount = ($order_amt * $tax->rate) / 100;
                        $taxAmount = number_format((float)$taxAmount, 2, '.', '');
                    }
                }
            }

            $order_total = $order_amt + $lift_gate_amt + $cert_fee_amt + $hazardous_amt + $taxAmount + $shipping_charge - $discount_amt;
            $order = Order::create([
                'order_no'                  =>  $orderNumber,
                'user_id'                   =>  $user_id,
                'order_amount'              =>  $order_amt,
                'discount_applied'          =>  $discount_amt,
                'lift_gate_amt'             =>  $lift_gate_amt,
                'cert_fee_amt'              =>  $cert_fee_amt,
                'hazardous_amt'             =>  $hazardous_amt,
                'tax_amount'                =>  $taxAmount,
                'total_amount'              =>  $order_total,
                'no_items'                  =>  $items,
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
                'payment_currency'          =>  'dollar',
                'payment_status'            =>  'pending',
                'shippment_via'             =>  $shipment_via,
                'shippment_status'          =>  'pending',
                'coupon_id'                 =>  $coupon_id ?? '',
                'coupon_discount'           =>  $discount_amt,
                'order_comments'            =>  $request->order_comments,
                'payment_amount'            =>  $order_total,
                'shippment_rate'            =>  $shipping_charge,
                'order_notes'               =>  $request->order_notes,
                'search_keywords'           =>  $request->search_keywords
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

            if (!empty($coupon)) {
                $time_applied = 0;
                $applied_coupon = Coupon::find($coupon->id);
                if ($applied_coupon != null && $applied_coupon->type == 'cart_value_discount' && $applied_coupon->disc_type == 'percent' && $applied_coupon->discount == '100') {
                    $time_applied = 0;
                } else {
                    $time_applied = 1;
                }
                $applied_coupon->times_applied = $applied_coupon->times_applied + $time_applied;
                if ($applied_coupon->coupon_limit == $applied_coupon->times_applied + $time_applied) {
                    $applied_coupon->status = 1;
                    $applied_coupon->flag = 1;
                }
                $applied_coupon->update();
            }

            // if ($order->shippment_via == 'saia') {
            //     $response = $this->ShipViaSaia($order->id);
            // } else {
            //     $response = $this->ShipViaFedex($order->id);
            // }

            // if($user->wp_id == null && $user->company_name != null){
            //     $this->qboCustomer($user->company_name, $user->id);
            // }

            // if($user->wp_id != null){
            //     $qboresponse = $this->quickBookInvoice($order->user_id, $order->id);
            // }

            DB::commit();
            return redirect('admin/orders')->with('success', 'Order Added Successfully');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('danger', $e->getMessage());
        }
    }

    public function edit($id)
    {
        $products = DB::table('products')->where('status', 1)->orderBy('name', 'asc')->get();
        $users = DB::table('users')->where('role_id', '!=', 1)->where('flag', 0)->orderBy('name', 'asc')->get();
        $order = Order::where('id', $id)->with('items')->first();
        foreach ($order->items as $item) {
            $order->total_qty += $item->quantity;
        }
        if ($order->lift_gate_amt > 0) {
            $order->lift_gate_status = '1';
        } else {
            $order->lift_gate_status = '0';
        }
        if ($order->cert_fee_amt > 0) {
            $order->cert_fee_status = '1';
        } else {
            $order->cert_fee_status = '0';
        }
        $lift_gate = DB::table('static_values')->where('name', 'Lift Gate')->first();
        $cert_fee = DB::table('static_values')->where('name', 'CERT Fee')->first();

        return view('orders.edit', compact('products', 'users', 'order', 'lift_gate', 'cert_fee'));
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

        DB::beginTransaction();
        try {
            $order = Order::where('id', $id)->first();

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
                    // 'landmark'      =>  $request['billing_landmark'],
                ]);

                $user_id = $user->id;
            }
            $sale_price = 0;
            $hazardous_qty = 0;
            $hazardous_amt = 0;
            $lift_gate_amt = 0;
            $cert_fee_amt = 0;
            $discount_amt = 0;
            $items = 0;
            $total_weight = 0;
            $products_array = array();
            foreach ($request->product_id as $key => $product_id) {
                $items += 1;
                $product = DB::table('products')->find($product_id);
                $sale_price += $product->sale_price * $request->quantity[$key];
                $total_weight += $product->weight * $request->quantity[$key];
                array_push($products_array,array('id' => $product_id,'qty' => $request->quantity[$key]));
                if ($product->hazardous == 1) {
                    $hazardous_qty += 1;
                }
            }
            $newRequest = new Request(['city' => $request->shipping_city, 'state' => $request->shipping_state, 'zip' => $request->shipping_zip, 'country' => $request->shipping_country, 'product_id' => $products_array]);

            $shipment_via = 0;
            $shipping_charge = 0;
            if ($total_weight >= 71) {
                $shipment_via = 'saia';
                $shipping_charge = $this->getSaiaShipRate($newRequest);
            } else {
                $shipment_via = 'fedex';
                $shipping_charge = $this->getFedexShipRate($newRequest);
            }

            $order_amt = $sale_price;
            $lift_gate = DB::table('static_values')->where('name', 'Lift Gate')->first();
            $cert_fee = DB::table('static_values')->where('name', 'CERT Fee')->first();
            $hazardous = DB::table('static_values')->where('name', 'Hazardous')->first();
            $lift_gate_amount = $lift_gate->value ?? 0;
            $cert_fee_amount = $cert_fee->value ?? 0;
            $hazardous_amount = $hazardous->value ?? 0;
            if ($hazardous_qty > 0) {
                $hazardous_amt = $hazardous_amount;
            }

            if ($request->lift_gate == 1) {
                $lift_gate_amt = $lift_gate_amount;
            }

            if ($request->cert_fee == 1) {
                $cert_fee_amt = $cert_fee_amount;
            }

            $coupon_id = '';
            $coupon = '';
            if (!empty($request->coupon)) {
                $coupon = DB::table('coupons')->find($request->coupon);
                if ($coupon != null) {
                    $coupon_id = $coupon->id;
                    if ($coupon->disc_type == 'percent') {
                        $discount_amt = ($order_amt * $coupon->discount) / 100;
                        $discount_amt = number_format((float)$discount_amt, 2, '.', '');
                    } else {
                        $discount_amt = $coupon->discount;
                    }
                }
            }else{
                $discount_amt = $order->coupon_discount;
            }

            $taxAmount = 0;
            $tax = DB::table('tax_rates')->select('rate')->where('zip', $request->shipping_zip)->first();
            $user = DB::table('users')->find($user_id);
            if($user->tax_exempt == 0) {
                if ($tax != null) {
                    if ($coupon != null && $coupon->type == 'cart_value_discount' && $coupon->disc_type == 'percent' && $coupon->discount == '100') {
                        $taxAmount = 0;
                        $coupon_id = '';
                    } else {
                        $taxAmount = ($order_amt * $tax->rate) / 100;
                        $taxAmount = number_format((float)$taxAmount, 2, '.', '');
                    }
                }
            }

            $order_total = $order_amt + $lift_gate_amt + $cert_fee_amt + $hazardous_amt + $taxAmount + $shipping_charge - $discount_amt;

            $order->user_id                   =  $user_id;
            $order->order_amount              =  $order_amt;
            $order->discount_applied          =  $discount_amt;
            $order->lift_gate_amt             =  $lift_gate_amt;
            $order->cert_fee_amt              =  $cert_fee_amt;
            $order->hazardous_amt             =  $hazardous_amt;
            $order->tax_amount                =  $taxAmount;
            $order->total_amount              =  $order_total;
            $order->no_items                  =  $items;
            $order->billing_name              =  $request->billing_name;
            $order->billing_mobile            =  $request->billing_mobile;
            $order->billing_email             =  $request->billing_email;
            $order->billing_address           =  $request->billing_address;
            $order->billing_country           =  $request->billing_country;
            $order->billing_state             =  $request->billing_state;
            $order->billing_city              =  $request->billing_city;
            $order->billing_zip               =  $request->billing_zip;
            // $order->billing_landmark          =  $request->billing_landmark;
            $order->shipping_name             =  $request->shipping_name;
            $order->shipping_mobile           =  $request->shipping_mobile;
            $order->shipping_email            =  $request->shipping_email;
            $order->shipping_address          =  $request->shipping_address;
            $order->shipping_country          =  $request->shipping_country;
            $order->shipping_state            =  $request->shipping_state;
            $order->shipping_city             =  $request->shipping_city;
            $order->shipping_zip              =  $request->shipping_zip;
            // $order->shipping_landmark         =  $request->shipping_landmark;
            $order->order_status              =  $request->order_status;
            $order->payment_status            =  $request->payment_status;
            $order->payment_currency          =  'dollar';
            $order->payment_status            =  'pending';
            $order->shippment_via             =  $shipment_via;
            $order->shippment_status          =  'pending';
            $order->coupon_id                 =  $coupon_id ?? '';
            $order->coupon_discount           =  $discount_amt;
            $order->order_comments            =  $request->order_comments;
            $order->payment_amount            =  $order_total;
            $order->shippment_rate            =  $shipping_charge;
            $order->order_notes               =  $request->order_notes;
            $order->search_keywords           =  $request->search_keywords;
            $order->update();

            $items = OrderItems::where('order_id', $id)->get();
            foreach ($items as $item) {
                $item->delete();
            }

            foreach ($request->product_id as $key => $item) {
                if (!empty($item)) {
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

            if (!empty($coupon)) {
                $time_applied = 0;
                $applied_coupon = Coupon::find($coupon->id);
                if ($applied_coupon != null && $applied_coupon->type == 'cart_value_discount' && $applied_coupon->disc_type == 'percent' && $applied_coupon->discount == '100') {
                    $time_applied = 0;
                } else {
                    $time_applied = 1;
                }
                $applied_coupon->times_applied = $applied_coupon->times_applied + $time_applied;
                if ($applied_coupon->coupon_limit == $applied_coupon->times_applied + $time_applied) {
                    $applied_coupon->status = 1;
                    $applied_coupon->flag = 1;
                }
                $applied_coupon->update();
            }

            // if ($order->shippment_via == 'saia') {
            //     $response = $this->ShipViaSaia($order->id);
            // } else {
            //     $response = $this->ShipViaFedex($order->id);
            // }

            // if($user->wp_id == null && $user->company_name != null){
            //     $this->qboCustomer($user->company_name, $user->id);
            // }

            // if($user->wp_id != null){
            //     $qboresponse = $this->quickBookInvoice($order->user_id, $order->id);
            // }

            DB::commit();

            return redirect('admin/orders')->with('success', 'Order Updated Successfully');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('danger', $e->getMessage());
        }
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

    public function StaticValue(Request $request)
    {
        $left_gate = DB::table('static_values')->select('id', 'name', 'value')->where('name', $request->name)->first();

        return response()->json($left_gate);
    }

    public function getHazardous(Request $request)
    {
        $products = DB::table('products')->select('hazardous')->whereIn('id', $request->product_ids)->get();
        $hazardous = DB::table('static_values')->where('name', 'Hazardous')->first();
        $hazardous_amt = 0;
        if ($products->isNotEmpty()) {
            foreach ($products as $product) {
                if ($product->hazardous == 1) {
                    $hazardous_amt = $hazardous->value;
                }
            }
        }

        return $hazardous_amt;
    }

    public function getCouponCode(Request $request)
    {
        $current = date('Y-m-d H:i:s');

        $coupons = Coupon::select('id', 'name', 'type', 'code', 'disc_type', 'discount')->where(['flag' => 0])->where([['type', '!=', 'customer_based'], ['offer_start', '<=', $current], ['offer_end', '>=', $current]])->orWhere([['type', 'customer_based'], ['user_id', $request->user_id]])->get();

        return $coupons;
    }

    public function codeApplied(Request $request)
    {
        $coupon = DB::table('coupons')->find($request->id);

        if (!empty($coupon->min_order_amount) && $coupon->min_order_amount > $request->total) {
            return response()->json(['message' => 'Cart total should be greater than or equal to ' . $coupon->min_order_amount, 'code' => 400], 400);
        }

        $discount_amt = 0;
        if ($coupon->disc_type == 'percent') {
            $discount_amt = ($request->total * $coupon->discount) / 100;
        } else {
            $discount_amt = $coupon->discount;
        }

        return number_format((float)$discount_amt, 2, '.', '');;
    }

    public function getTaxableAmount(Request $request)
    {
        $taxAmount = 0.00;
        $coupon = DB::table('coupons')->find($request->coupon);
        $tax = DB::table('tax_rates')->select('rate')->where('zip', $request->shipping_zip)->first();
        if(!empty($request->client_id)){
            $client = DB::table('users')->find($request->client_id);
            if ($client->tax_exempt == 0) {
                if ($tax != null) {
                    if ($coupon != null && $coupon->type == 'cart_value_discount' && $coupon->disc_type == 'percent' && $coupon->discount == '100') {
                        $taxAmount = 0.00;
                    } else {
                        $taxAmount = ($request->total * $tax->rate) / 100;
                    }
                }

                return number_format((float)$taxAmount, 2, '.', '');
            }
        }else{
            if ($tax != null) {
                if ($coupon != null && $coupon->type == 'cart_value_discount' && $coupon->disc_type == 'percent' && $coupon->discount == '100') {
                    $taxAmount = 0.00;
                } else {
                    $taxAmount = ($request->total * $tax->rate) / 100;
                }
            }

            return number_format((float)$taxAmount, 2, '.', '');
        }
    }

    public function getShippingCharge(Request $request)
    {
        $product_id_and_qty = array();
        foreach ($request->product_id as $key => $product_id){
            $prod['id'] = $product_id;
            $prod['qty'] = $request->quantity[$key];
            array_push($product_id_and_qty, $prod);
        }

        $newRequest = new Request(['city' => $request->shipping_city, 'state' => $request->shipping_state, 'zip' => $request->shipping_zip, 'country' => $request->shipping_country, 'product_id' => $product_id_and_qty]);

        $products = DB::table('products')->whereIn('id', $request->product_id)->get();
        $total_weight = 0;
        if ($products->isNotEmpty()) {
            foreach ($products as $product) {
                if (!empty($product->weight)) {
                    $total_weight += $product->weight * $request->quantity;
                }
            }
        }

        $shipment_via = 0;
        $shipping_charge = 0;
        if ($total_weight >= 71) {
            $shipment_via = 'Saia';
            $shipping_charge = $this->getSaiaShipRate($newRequest);
        } else {
            $shipment_via = 'Fedex';
            $shipping_charge = $this->getFedexShipRate($newRequest);
        }

        $data = ['shipment_via' => $shipment_via, 'shipping_charge' => $shipping_charge];
        return $data;
    }
}
