@extends('layouts.main')

@section('title', 'Edit Order')

@section('content')
<div class="content">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Edit Order</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('admin/orders') }}" class="btn btn-info mt-o" style="float: right;">Back</a></li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <div id="loader"></div>

    <div class="card">
        <div class="card-body">
            <form action="{{ url('admin/orders/update', $order->id) }}" id="addData" method="post" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <div class="row">

                    <!-- Customer -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="required form-label" for="customer"> Customer </label>
                            <select class="form-control select2bs4" name="customer" id="customer">
                                <option value="">Select Customer</option>
                                @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ $order->user_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Customer Address -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="required form-label" for="customer_address"> Customer Address </label>
                            <select class="form-control select2bs4" name="customer_address" id="customer_address">
                                <option value="">Select Customer Address</option>
                            </select>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <!-- Billing Name -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="billing_name"><span style="color: red;">* </span> Billing Name </label>
                            <input type="text" class="form-control form-control-solid @error('billing_name') is-invalid @enderror" name="billing_name" id="billing_name" placeholder="Please Enter Billing Name" value="{{ $order->billing_name }}">
                            @error('billing_name')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Billing Mobile -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="billing_mobile"><span style="color: red;">* </span> Billing Mobile </label>
                            <input type="number" class="form-control form-control-solid @error('billing_mobile') is-invalid @enderror" name="billing_mobile" id="billing_mobile" placeholder="Please Enter Billing Mobile" value="{{ $order->billing_mobile }}">
                            @error('billing_mobile')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Billing Email -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="billing_email"><span style="color: red;">* </span> Billing Email </label>
                            <input type="email" class="form-control form-control-solid @error('billing_email') is-invalid @enderror" name="billing_email" id="billing_email" placeholder="Please Enter Billing Email" value="{{ $order->billing_email }}">
                            @error('billing_email')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Billing Address -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="billing_address"><span style="color: red;">* </span> Billing Address </label>
                            <input type="text" class="form-control form-control-solid @error('billing_address') is-invalid @enderror" name="billing_address" id="billing_address" placeholder="Please Enter Billing Address" value="{{ $order->billing_address }}">
                            @error('billing_address')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Billing Landmark -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="billing_landmark"> Billing Landmark </label>
                            <input type="text" class="form-control form-control-solid @error('billing_landmark') is-invalid @enderror" name="billing_landmark" id="billing_landmark" placeholder="Please Enter Billing Landmark" value="{{ $order->billing_landmark }}">
                            @error('billing_landmark')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Billing Country -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="billing_country"><span style="color: red;">* </span> Billing Country </label>
                            <input type="text" class="form-control form-control-solid @error('billing_country') is-invalid @enderror" name="billing_country" id="billing_country" placeholder="Please Enter Billing Country" value="{{ $order->billing_country }}">
                            @error('billing_country')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Billing State -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="billing_state"><span style="color: red;">* </span> Billing State </label>
                            <input type="text" class="form-control form-control-solid @error('billing_state') is-invalid @enderror" name="billing_state" id="billing_state" placeholder="Please Enter Billing State" value="{{ $order->billing_state }}">
                            @error('billing_state')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Billing City -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="billing_city"><span style="color: red;">* </span> Billing City </label>
                            <input type="text" class="form-control form-control-solid @error('billing_city') is-invalid @enderror" name="billing_city" id="billing_city" placeholder="Please Enter Billing City" value="{{ $order->billing_city }}">
                            @error('billing_city')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Billing Zip -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="billing_zip"><span style="color: red;">* </span> Billing Zip </label>
                            <input type="number" class="form-control form-control-solid @error('billing_zip') is-invalid @enderror" name="billing_zip" id="billing_zip" placeholder="Please Enter Billing Zip" value="{{ $order->billing_zip }}">
                            @error('billing_zip')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="same">Shipping same as Billing </label>
                            <select class="form-control" name="same" id="same">
                                <option value="no" {{ old('same') == 'no' ? 'selected' : '' }}>No</option>
                                <option value="yes" {{ old('same') == 'yes' ? 'selected' : '' }}>Yes</option>
                            </select>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <!-- Shipping Name -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="shipping_name"><span style="color: red;">* </span> Shipping Name </label>
                            <input type="text" class="form-control form-control-solid @error('shipping_name') is-invalid @enderror" name="shipping_name" id="shipping_name" placeholder="Please Enter Shipping Name" value="{{ $order->shipping_name }}">
                            @error('shipping_name')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Shipping Mobile -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="shipping_mobile"><span style="color: red;">* </span> Shipping Mobile </label>
                            <input type="number" class="form-control form-control-solid @error('shipping_mobile') is-invalid @enderror" name="shipping_mobile" id="shipping_mobile" placeholder="Please Enter Shipping Mobile" value="{{ $order->shipping_mobile }}">
                            @error('shipping_mobile')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Shipping Email -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="shipping_email"><span style="color: red;">* </span> Shipping Email </label>
                            <input type="email" class="form-control form-control-solid @error('shipping_email') is-invalid @enderror" name="shipping_email" id="shipping_email" placeholder="Please Enter Shipping Email" value="{{ $order->shipping_email }}">
                            @error('shipping_email')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Shipping Address -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="shipping_address"><span style="color: red;">* </span> Shipping Address </label>
                            <input type="text" class="form-control form-control-solid @error('shipping_address') is-invalid @enderror" name="shipping_address" id="shipping_address" placeholder="Please Enter Shipping Address" value="{{ $order->shipping_address }}">
                            @error('shipping_address')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Shipping Landmark -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="shipping_landmark"> Shipping Landmark </label>
                            <input type="text" class="form-control form-control-solid @error('shipping_landmark') is-invalid @enderror" name="shipping_landmark" id="shipping_landmark" placeholder="Please Enter Shipping Landmark" value="{{ $order->shipping_landmark }}">
                            @error('shipping_landmark')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Shipping Country -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="shipping_country"><span style="color: red;">* </span> Shipping Country </label>
                            <input type="text" class="form-control form-control-solid @error('shipping_country') is-invalid @enderror" name="shipping_country" id="shipping_country" placeholder="Please Enter Shipping Country" value="{{ $order->shipping_country }}">
                            @error('shipping_country')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Shipping State -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="shipping_state"><span style="color: red;">* </span> Shipping State </label>
                            <input type="text" class="form-control form-control-solid @error('shipping_state') is-invalid @enderror" name="shipping_state" id="shipping_state" placeholder="Please Enter Shipping State" value="{{ $order->shipping_state }}">
                            @error('shipping_state')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Shipping City -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="shipping_city"><span style="color: red;">* </span> Shipping City </label>
                            <input type="text" class="form-control form-control-solid @error('shipping_city') is-invalid @enderror" name="shipping_city" id="shipping_city" placeholder="Please Enter Shipping City" value="{{ $order->shipping_city }}">
                            @error('shipping_city')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Shipping Zip -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="shipping_zip"><span style="color: red;">* </span> Shipping Zip </label>
                            <input type="number" class="form-control form-control-solid @error('shipping_zip') is-invalid @enderror" name="shipping_zip" id="shipping_zip" placeholder="Please Enter Shipping Zip" value="{{ $order->shipping_zip }}">
                            @error('shipping_zip')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <table class="table table-bordered main_table" width="100%">
                        <thead>
                            <tr>
                                <th><span style="color: red;">* </span>Product</th>
                                <th><span style="color: red;">* </span>Quantity</th>
                                <th><span style="color: red;">* </span>Sale Price</th>
                                <th><span style="color: red;">* </span> Total</th>
                                {{-- <th><button type="button" class="btn btn-primary btn-sm add_row"><i class="fa fa-plus-circle"></i></button></th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>

                                <td>
                                    <input type="hidden" value="{{ $item->product_id }}" class="store_product_id" name="product_id[]" />
                                    <select class="form-control select2 product_id" disabled>
                                        <option value="">Select Product</option>
                                        @foreach($products as $product)
                                        <option value="{{ $product->id }}" {{ $item->product_id == $product->id ? 'selected' : '' }}>{{ $product->name }} - {{$product->variant}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="quantity[]" class="form-control quantity" placeholder="Enter Quantity" min="1" oninput="validity.valid||(value='');" value="{{ $item->quantity }}" required>
                                </td>
                                <td><input type="text" name="sale_price[]" value="{{$item->sale_price}}" class="form-control sale_price" placeholder="Enter Sale Price" readonly></td>
                                <td><input type="text" value="{{ $item->sale_price * $item->quantity }}" name="product_total[]" class="form-control product_total" placeholder="Enter Product Total" readonly></td>
                                {{-- <td><button type="button" class="btn btn-danger btn-sm delete_row"><i class="fa fa-minus-circle"></i></button></td> --}}
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <hr>
                <div class="row">
                    <!-- Total Quantity -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="total_qty"><span style="color: red;">* </span> Total Quantity </label>
                            <input type="number" class="form-control form-control-solid @error('total_qty') is-invalid @enderror total_qty" name="total_qty" id="total_qty" placeholder="Please Enter Total Quantity" value="{{ $order->total_qty }}" readonly>
                            @error('total_qty')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Total Order Amount -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="order_amount"> Total Order Amount </label>
                            <input type="number" step=".01" class="form-control form-control-solid @error('order_amount') is-invalid @enderror" name="order_amount" id="order_amount" min="0" oninput="validity.valid||(value='');" placeholder="Please Enter Total Order Amount" value="{{ $order->order_amount }}" readonly>
                            <input type="hidden" id="lift_gate_value" value="{{ $lift_gate->value }}" />
                        </div>
                    </div>

                    <!-- lift Gate Status -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label"> Lift Gate</label>
                            <select class="form-control" name="lift_gate" id="lift_gate" onchange="lift_amt_click_count_fun()">
                                <option value="" selected>Select Lift Gate</option>
                                <option value="1" {{ $order->lift_gate_status == '1' ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ $order->lift_gate_status == '0' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                    </div>

                    <!-- Lift Gate Amount -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="lift_gate_amt"> Lift Gate Amount </label>
                            <input type="number" step=".01" class="form-control form-control-solid @error('lift_gate_amt') is-invalid @enderror" name="lift_gate_amt" id="lift_gate_amt" min="0" oninput="validity.valid||(value='');" placeholder="Please Enter Lift Gate Amount" value="{{ $order->lift_gate_amt }}" readonly>
                            <input type="hidden" id="lift_gate_value" value="{{ $lift_gate->value }}" />
                        </div>
                    </div>

                    <!-- CERT Fee Status -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label"> CERT Fee</label>
                            <select class="form-control" name="cert_fee" id="cert_fee">
                                <option value="" selected>Select CERT Fee</option>
                                <option value="1" {{ $order->cert_fee_status == '1' ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ $order->cert_fee_status == '0' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                    </div>

                    <!-- CERT Fee Amount -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="cert_fee_amt"> CERT Fee Amount </label>
                            <input type="number" step=".01" class="form-control form-control-solid @error('cert_fee_amt') is-invalid @enderror" name="cert_fee_amt" id="cert_fee_amt" min="0" oninput="validity.valid||(value='');" placeholder="Please Enter CERT Fee Amount" value="{{ $order->cert_fee_amt }}" readonly>
                            <input type="hidden" id="cert_fee_value" value="{{ $cert_fee->value }}" />
                        </div>
                    </div>

                    <!-- Hazardous -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="hazardous_amt"> Hazardous </label>
                            <input type="number" step=".01" class="form-control form-control-solid @error('hazardous_amt') is-invalid @enderror" name="hazardous_amt" id="hazardous_amt" min="0" oninput="validity.valid||(value='');" placeholder="Please Enter Hazardous" value="{{ $order->hazardous_amt }}" readonly>
                        </div>
                    </div>

                    <!-- Coupon Code -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label"> Coupon Code</label>
                            <select class="form-control select2bs4" name="coupon" id="coupon">
                                <option value="">Select Coupon Code</option>
                            </select>
                            <input type="hidden" id="coupon_id" value="{{ $order->coupon_id }}" />
                        </div>
                    </div>

                    <!-- Discount -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="discount"> Discount Amount </label>
                            <input type="number" step=".01" class="form-control form-control-solid @error('discount') is-invalid @enderror" name="discount" id="discount" min="0" oninput="validity.valid||(value='');" placeholder="Please Enter Discount" value="{{ $order->discount_applied }}" readonly>
                        </div>
                    </div>

                    <!-- Ship Via -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label"> Ship Via </label>
                            <input type="text" id="shipVia" class="form-control" placeholder="Please Enter Ship Via" value="{{ ucfirst($order->shippment_via) }}" readonly/>
                        </div>
                    </div>

                    <!-- Shipping Charge -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="shipping_charge"> Shipping Charge </label>
                            <input type="number" step=".01" class="form-control form-control-solid @error('shipping_charge') is-invalid @enderror" name="shipping_charge" id="shipping_charge" min="0" oninput="validity.valid||(value='');" placeholder="Please Enter Shipping Charge" value="{{ $order->shippment_rate }}" readonly>
                        </div>
                    </div>

                    <!-- Taxable Amount -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="tax_amt"> Taxable Amount </label>
                            <input type="number" step=".01" class="form-control form-control-solid @error('tax_amt') is-invalid @enderror" name="tax_amt" id="tax_amt" min="0" oninput="validity.valid||(value='');" placeholder="Please Enter Taxable Amount" value="{{ $order->tax_amount }}" readonly>
                        </div>
                    </div>

                    <!-- Total Amount -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="total_amt"><span style="color: red;">* </span> Total Amount </label>
                            <input type="number" step=".01" class="form-control form-control-solid @error('total_amt') is-invalid @enderror total_amt" name="total_amt" id="total_amt" placeholder="Please Enter Total Amount" value="{{ $order->total_amount }}" readonly>
                            @error('total_amt')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Order Status -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label"> Order Status </label>
                            <select class="form-control" name="order_status">
                                <option value="pending" {{ $order->order_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="completed" {{ $order->order_status == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $order->order_status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                    </div>

                    <!-- Payment Status -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label"> Payment Status </label>
                            <select class="form-control" name="payment_status">
                                <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="completed" {{ $order->payment_status == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>
                    </div>
                    <!-- order notes -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="required form-label" for="order_notes"><span style="color: red;"> </span>Order Notes <span style="font-weight: normal;">(optional)</span></label>
                            <textarea class="form-control @error('order_notes') is-invalid @enderror" name="order_notes" placeholder="Notes about your order, e.g. special notes for delivery."><?php echo $order->order_notes; ?></textarea>
                            @error('order_notes')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <!-- search keywords  -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="required form-label" for="search_keywords"><span style="color: red;"></span>What keyword phrase did you enter to find our website? <span style="font-weight: normal;">(optional)</span> </label>
                            <input type="text" class="form-control form-control-solid @error('search_keywords') is-invalid @enderror" name="search_keywords" id="search_keywords" placeholder="(e.g. industrial degreasers, rust removers, etc.)" value="{{ $order->search_keywords }}">
                            @error('search_keywords')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mt-2">
                        <button type="submit" class="btn btn-info">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('js')
<script src="{{ asset('js/validations/orders/addorderrules.js') }}"></script>
<script>
    var actual_Lift_Amout = 0;
    var lift_amt_click_count = 0;
    function hazardous(product_ids) {
        return new Promise((resolve) => {
            if (product_ids.length !== 0) {
                $.ajax({
                    url: "{{ url('admin/orders/getHazardous') }}",
                    type: "POST",
                    dataType: "json",
                    data: {
                        product_ids: product_ids,
                        _token: '{{csrf_token()}}'
                    },
                    success: function(data) {
                        $('#hazardous_amt').val(data.toFixed(2));
                        resolve(true);
                    }
                });
            } else {
                $('#hazardous_amt').val(0);
                resolve(true);
            }
        });
    }

    function getShippingCharge(product_ids, total_qty) {
        return new Promise((resolve) => {
            var shipping_country = $('#shipping_country').val();
            var shipping_state = $('#shipping_state').val();
            var shipping_city = $('#shipping_city').val();
            var shipping_zip = $('#shipping_zip').val();
            if (shipping_country !== undefined && shipping_state !== undefined && shipping_city !== undefined && shipping_zip !== undefined && product_ids.length !== 0 && total_qty !== 0) {
                $.ajax({
                    url: "{{ url('admin/orders/getShippingCharge') }}",
                    type: "POST",
                    dataType: "json",
                    data: {
                        shipping_country: shipping_country,
                        shipping_state: shipping_state,
                        shipping_city: shipping_city,
                        shipping_zip: shipping_zip,
                        product_id: product_ids,
                        quantity: total_qty,
                        _token: '{{csrf_token()}}'
                    },
                    success: function(data) {
                        $('#shipping_charge').val(data.shipping_charge);
                        $('#shipVia').val(data.shipment_via);
                        resolve(true);
                    }
                });
            } else {
                $('#shipping_charge').val(0);
                $('#shipVia').val('');
                resolve(true);
            }
        });
    }

    function getTaxableAmount() {
        return new Promise((resolve) => {
            var shipping_zip = $('#shipping_zip').val();
            var total = $('#order_amount').val();
            var coupon = $('#coupon').val();
            var client_id = $('#customer').val();
            if (total > 0 && shipping_zip !== '') {
                $.ajax({
                    url: "{{ url('admin/orders/getTaxableAmount') }}",
                    type: "POST",
                    dataType: "json",
                    data: {
                        shipping_zip: shipping_zip,
                        total: total,
                        coupon: coupon,
                        client_id: client_id,
                        _token: '{{csrf_token()}}'
                    },
                    success: function(data) {
                        $('#tax_amt').val(data.toFixed(2));
                        resolve(true);
                    }
                });
            } else {
                $('#tax_amt').val(0);
                resolve(true);
            }
        });
    }

    // $(document).ready(function() {
    //     getCouponCode();
    //     getAddresses();
    //     lift_gate();
    //     cert_fee();
    //     calculateTotal();
    // });

    $(document).on('change', '#same', function() {
        var same = $('#same').val();
        if (same === 'yes') {
            bill_name = $('#billing_name').val();
            bill_phone = $('#billing_mobile').val();
            bill_email = $('#billing_email').val();
            bill_address = $('#billing_address').val();
            bill_landmark = $('#billing_landmark').val();
            bill_country = $('#billing_country').val();
            bill_state = $('#billing_state').val();
            bill_city = $('#billing_city').val();
            bill_zip = $('#billing_zip').val();

            $('#shipping_name').val(bill_name);
            $('#shipping_mobile').val(bill_phone);
            $('#shipping_email').val(bill_email);
            $('#shipping_address').val(bill_address);
            $('#shipping_landmark').val(bill_landmark);
            $('#shipping_country').val(bill_country);
            $('#shipping_state').val(bill_state);
            $('#shipping_city').val(bill_city);
            $('#shipping_zip').val(bill_zip);
        } else {
            $('#shipping_name').val("");
            $('#shipping_mobile').val("");
            $('#shipping_email').val("");
            $('#shipping_address').val("");
            $('#shipping_landmark').val("");
            $('#shipping_country').val("");
            $('#shipping_state').val("");
            $('#shipping_city').val("");
            $('#shipping_zip').val("");
        }
        getTaxableAmount();
    });

    $(document).on('change', '#customer', function() {
        getCouponCode();
        getAddresses();
    });

    $(document).on('change', '#coupon', function() {
        var id = $('#coupon').val();
        var total = $(".total_amt").val();
        if (id != '') {
            if (total > 0 || total != '') {
                $.ajax({
                    url: "{{ url('admin/orders/codeApplied') }}",
                    type: "POST",
                    dataType: "json",
                    data: {
                        id: id,
                        total: total,
                        _token: '{{csrf_token()}}'
                    },
                    success: function(data) {
                        $('#discount').val(data);
                        calculateTotal();
                    }
                });
            } else {
                swal({
                    title: 'Oops!',
                    text: "Please add product to use coupon.",
                    icon: 'error',
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'OK',
                    timer: 3000
                })
            }
        }
    });

    $(document).on('change', '#customer_address', function() {
        setAddress();
    });

    $(document).on('click', '.add_row', function() {
        var table = $('.main_table'),
            lastRow = table.find('tbody tr:last'),
            rowClone = lastRow.clone();
        rowClone.find("input").val("").end();
        rowClone.find("select").val("").end();
        rowClone.find(".product_id").attr('disabled', false).end();

        var newrow = table.find('tbody').append(rowClone);
    });

    $(document).on('click', '.delete_row', function() {
        var rowCount = $('.main_table tbody tr').length;
        if (rowCount > 1) {
            var row = $(this).closest('tr');
            row.remove();
            calculateTotal();
            discount();
        }
    });

    $(document).on('change', '.product_id', function() {
        var row = $(this).closest('tr');
        getSalePrice(row);
    });

    $(document).on('keyup', '.quantity', function() {
        var row = $(this).closest('tr');
        calculateProductTotal(row);
    });

    $(document).on('change', '#lift_gate', function() {
        lift_gate();
    });

    function lift_amt_click_count_fun(){
        lift_amt_click_count = lift_amt_click_count +1;
    }

    function lift_gate() {
        var id = $('#lift_gate').val();
        var name = 'Lift Gate';
        var lift_gate_value = $('#lift_gate_value').val();
       // actual_Lift_Amout = $('#lift_gate_amt').val();
        var Ttl_amt = $("#total_amt").val();
        if (id == 1) {
            if(parseInt(lift_gate_value) > 0 && parseInt(lift_amt_click_count) > 0 ){
                $("#total_amt").val( parseInt(Ttl_amt) + parseInt(lift_gate_value));
            }else{
                $("#total_amt").val();
            }
            $("#lift_gate_amt").val(parseFloat(lift_gate_value));

           
            // $.ajax({
            //     url: "{{ url('admin/orders/static_value') }}",
            //     type: "POST",
            //     dataType: "json",
            //     data: {
            //         id: id,
            //         name: name,
            //         _token: '{{csrf_token()}}'
            //     },
            //     success: function(data) {
            //         $("#lift_gate_amt").val(parseFloat(data.value));
            //     }
            // });
        } else {
            if(parseInt(lift_gate_value) > 0 ){
                $("#total_amt").val( parseInt(Ttl_amt) - parseInt(lift_gate_value));
            }else{
                $("#total_amt").val();
            }
            $('#lift_gate_amt').val(0);
               // $("#lift_gate_amt").val(parseFloat(lift_gate_value));
           
        }
        calculateTotal();
    }

    $(document).on('change', '#cert_fee', function() {
        cert_fee();
    });

    function cert_fee() {
        var id = $('#cert_fee').val();
        var name = 'CERT Fee';
        var cert_fee_value = $('#cert_fee_value').val();
        if (id == 1) {
            $("#cert_fee_amt").val(parseFloat(cert_fee_value));
        } else {
            $('#cert_fee_amt').val(0);
        }
        calculateTotal();
    }

    function getSalePrice(row) {
        var id = row.find(".product_id").val();
        row.find(".store_product_id").val(id);
        $.ajax({
            url: "{{ url('admin/orders/getProductById') }}",
            type: "POST",
            dataType: "json",
            data: {
                id: id,
                _token: '{{csrf_token()}}'
            },
            success: function(data) {
                row.find(".sale_price").val(data.sale_price);
                calculateProductTotal(row);
            }
        });
    }

    function setAddress() {
        var id = $('#customer_address').val();
        $.ajax({
            url: "{{ url('admin/orders/getAddressDetail') }}",
            type: "POST",
            dataType: "json",
            data: {
                id: id,
                _token: '{{csrf_token()}}'
            },
            success: function(data) {
                $('#billing_name').val(data.name);
                $('#billing_mobile').val(data.mobile);
                $('#billing_email').val(data.email);
                $('#billing_address').val(data.address);
                $('#billing_landmark').val(data.landmark);
                $('#billing_country').val(data.country);
                $('#billing_state').val(data.state);
                $('#billing_city').val(data.city);
                $('#billing_zip').val(data.zip);
            }
        });
    }

    function getAddresses() {
        var id = $('#customer').val();
        $.ajax({
            url: "{{ url('admin/orders/getAddresses') }}",
            type: "POST",
            dataType: "json",
            data: {
                id: id,
                _token: '{{csrf_token()}}'
            },
            success: function(data) {
                $('#customer_address').empty();
                $.each(data, function(key, value) {
                    $("#customer_address").append('<option value="' + value.id + '">' + value.address + '</option>');
                });
                setAddress();
            }
        });
    }

    function calculateProductTotal(row) {
        var price = row.find(".sale_price").val();
        var qty = row.find(".quantity").val();
        var total = price * qty;
        row.find(".product_total").val(total);
        calculateTotal();
    }

    function getCouponCode() {
        var user_id = $('#customer').val();
        var coupon_id = $('#coupon_id').val();
        $.ajax({
            url: "{{ url('admin/orders/getCouponCode') }}",
            type: "POST",
            dataType: "json",
            data: {
                user_id: user_id,
                _token: '{{csrf_token()}}'
            },
            success: function(data) {
                $('#coupon').empty();
                $("#coupon").append('<option value="">Select Coupon Code</option>');
                $.each(data, function(key, value) {
                    var value_type = value.disc_type == 'percent' ? value.discount + '%' : '$' + value.discount;
                    if (coupon_id == value.id) {
                        $("#coupon").append('<option value="' + value.id + '">' + value.code + '(' + value_type + ')' + '</option>');
                    } else {
                        $("#coupon").append('<option value="' + value.id + '">' + value.code + '(' + value_type + ')' + '</option>');
                    }
                });
            }
        });
    }

    function couponApplied() {
        var id = $('#coupon').val();
        var total = $('#order_amount').val();
        if (id !== '') {
            if (total > 0 || total !== '') {
                $.ajax({
                    url: "{{ url('admin/orders/codeApplied') }}",
                    type: "POST",
                    dataType: "json",
                    data: {
                        id: id,
                        total: total,
                        _token: '{{csrf_token()}}'
                    },
                    success: function(data) {
                        var discount = data.toFixed(2);
                        $('#discount').val(discount);
                        calculateTotal();
                    }
                });
            } else {
                swal({
                    title: 'Oops!',
                    text: "Please add product to use coupon.",
                    icon: 'error',
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'OK',
                    timer: 3000
                })
            }
        }
    }

    async function calculateTotal() {
        var total_amt = 0;
        var total_qty = 0;
        var product_ids = [];
        $('.main_table tr').each(function() {
            var ptotal = $(this).find(".product_total").val();
            var qty = $(this).find(".quantity").val();
            var product_id = $(this).find(".product_id").val();
            if (product_id != undefined) {
                product_ids.push(product_id);
            }
            if (!isNaN(ptotal) && ptotal != '') {
                total_amt += parseFloat(ptotal);
            }
            if (!isNaN(qty) && qty != '') {
                total_qty += parseInt(qty);
            }
        });

        $('#order_amount').val(total_amt);

        await hazardous(product_ids);
        await getShippingCharge(product_ids, total_qty)
        await getTaxableAmount();

        let hazardous_amt = $('#hazardous_amt').val();
        hazardous_amt = hazardous_amt != '' ? hazardous_amt : 0;

        let lift_gate_amt = $('#lift_gate_amt').val();
        lift_gate_amt = lift_gate_amt !== '' ? lift_gate_amt : 0;

        let cert_fee_amt = $('#cert_fee_amt').val();
        cert_fee_amt = cert_fee_amt !== '' ? cert_fee_amt : 0;

        let tax_amt = $('#tax_amt').val();
        let shipping_charge = $('#shipping_charge').val();

        let discount = $('#discount').val();
        discount = discount != '' ? discount : 0;

        tax_amt = tax_amt != '' ? tax_amt : 0;

        total_amt = parseFloat(total_amt) + parseFloat(hazardous_amt) + parseFloat(lift_gate_amt) + parseFloat(tax_amt) + parseFloat(shipping_charge) + parseFloat(cert_fee_amt) - parseFloat(discount);

        $(".total_qty").val(total_qty);
        $(".total_amt").val(total_amt.toFixed(2));
    }
</script>
@endsection