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

    <div class="card">
        <div class="card-body">
            <form action="{{ url('admin/orders/update', $order->id) }}" method="post" enctype="multipart/form-data">
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
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Sale Price</th>
                                <th>Product Total</th>
                                <th><button type="button" class="btn btn-primary btn-sm add_row"><i class="fa fa-plus-circle"></i></button></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <select name="product_id[]" class="form-control select2 product_id" required>
                                        <option value="">Select Product</option>
                                        @foreach($products as $product)
                                        <option value="{{ $product->id }}" {{ $item->product_id == $product->id ? 'selected' : '' }}>{{ $product->name }} - {{$product->variant}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="quantity[]" class="form-control quantity" placeholder="Enter Quantity" value="{{ $item->quantity }}" required>
                                </td>
                                <td><input type="text" name="sale_price[]" class="form-control sale_price" placeholder="Enter Sale Price" readonly></td>
                                <td><input type="text" name="product_total[]" class="form-control product_total" placeholder="Enter Product Total" readonly></td>
                                <td><button type="button" class="btn btn-danger btn-sm delete_row"><i class="fa fa-minus-circle"></i></button></td>
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
                            <input type="number" class="form-control form-control-solid @error('total_qty') is-invalid @enderror total_qty" name="total_qty" id="total_qty" placeholder="Please Enter Total Quantity" value="{{ $order->no_items }}" readonly>
                            @error('total_qty')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Discount -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label" for="discount"> Discount Amount </label>
                            <input type="number" step=".01" class="form-control form-control-solid @error('discount') is-invalid @enderror" name="discount" id="discount" placeholder="Please Enter Discount" value="{{ $order->discount_applied }}">
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

                    <!-- Ship Via -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required form-label"> Ship Via </label>
                            <select class="form-control" name="shippment_via">
                                <option value="saia" {{ $order->shippment_via == 'saia' ? 'selected' : '' }}>Saia</option>
                                <option value="fedex" {{ $order->shippment_via == 'fedex' ? 'selected' : '' }}>Fedex</option>
                            </select>
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
<script>
    $(document).ready(function() {
        calculateTotal();
        getAddresses();
        $('.main_table tr').each(function() {
            var row = $(this);
            getSalePrice(row);
        });
    });

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
    });

    $(document).on('change', '#customer', function() {
        getAddresses();
    });

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
                console.log(data);
                $.each(data, function(key, value) {
                    $("#customer_address").append('<option value="' + value.id + '">' + value.address + '</option>');
                });
                setAddress();
            }
        });
    }

    $(document).on('change', '#customer_address', function() {
        setAddress();
    });

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

    $(document).on('click', '.add_row', function() {
        var table = $('.main_table'),
            lastRow = table.find('tbody tr:last'),
            rowClone = lastRow.clone();
        rowClone.find("input").val("").end();
        rowClone.find("select").val("").end();
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

    function getSalePrice(row) {
        var id = row.find(".product_id").val();
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

    $(document).on('keyup', '.quantity', function() {
        var row = $(this).closest('tr');
        calculateProductTotal(row);
    });

    function calculateProductTotal(row) {
        var price = row.find(".sale_price").val();
        var qty = row.find(".quantity").val();
        var total = price * qty;
        row.find(".product_total").val(total);
        calculateTotal();
        discount();
    }

    function calculateTotal() {
        var total_amt = 0;
        var total_qty = 0;
        $('.main_table tr').each(function() {
            var ptotal = $(this).find(".product_total").val();
            var qty = $(this).find(".quantity").val();
            if (!isNaN(ptotal) && ptotal != '') {
                total_amt += parseFloat(ptotal);
            }
            if (!isNaN(qty) && qty != '') {
                total_qty += parseInt(qty);
            }
        });

        $(".total_qty").val(total_qty);
        $(".total_amt").val(total_amt);
    }

    $(document).on('keyup', '#discount', function() {
        discount();
    });

    function discount() {
        var discount = $('#discount').val();
        var total_amt = 0;
        $('.main_table tr').each(function() {
            var ptotal = $(this).find(".product_total").val();
            if (!isNaN(ptotal) && ptotal != '') {
                total_amt += parseFloat(ptotal);
            }
        });
        if (!isNaN(discount) && discount != '') {
            total = parseFloat(total_amt) - parseFloat(discount);
            $(".total_amt").val(total);
        } else {
            calculateTotal();
        }
    }
</script>
@endsection