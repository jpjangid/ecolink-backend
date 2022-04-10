@extends('layouts.main')

@section('title', 'Order Detail')

@section('content')
<div class="content">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Order Detail: #{{ $order->order_no }}</h1>
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
            <div class="row">
                <div class="col-md-6 mb-4">
                    <h4>Order Status</h4>
                </div>
                <div class="col-md-6 mb-4">
                    <input type="hidden" value="{{ $order->id }}" id="order_id">
                    <select class="form-control" id="order_status">
                        <option value="pending" {{ $order->order_status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="success" {{ $order->order_status == 'success' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>
                <div class="col-md-12 mb-4">
                    <h4>Order Details</h4>
                    <table class="table table-row-bordered table-hover">
                        <thead>
                            <tr>
                                <th colspan="2">Ordered Item</th>
                                <th>Qty.</th>
                                <th>Item Price</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $key => $item)
                            @if($item->flag == '0')
                            <tr>
                                <td><img src="{{ asset('storage/products/'.$item->product->image) }}" alt="{{ $item->product->image }}" style="height: 5rem;"></td>
                                <td>{{ $item->product->name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ $item->product->sale_price }}</td>
                                <td>{{ $item->product->sale_price * $item->quantity }}</td>
                                <td>{{ !empty($item->return) ? 'Return' : '' }}</td>
                            </tr>
                            @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- <hr>
                <h4>Shiprocket Tracking Details</h4>
                <div class="col-md-12 mb-4">
                    <table class="table table-row-bordered table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Shipment ID</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $order->shiprocket_order_id }}</td>
                                <td>{{ $order->shiprocket_shipment_id }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div> -->

                <hr>
                <h4>Address Information</h4>
                <div class="col-md-12 mb-4">
                    <table class="table table-row-bordered table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>Billing Address</th>
                                <th>Shpping Address</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $order->billing_name }} ({{$order->billing_mobile}})
                                    {{ $order->billing_address }} {{ $order->billing_city }}
                                    {{ $order->billing_state }} ({{ $order->billing_zip }})
                                </td>
                                <td>{{ $order->shipping_name }} ({{$order->shipping_mobile}})
                                    {{ $order->shipping_address }} {{ $order->shipping_city }}
                                    {{ $order->shipping_state }} ({{ $order->shipping_zip }})
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <hr>
                <h4>Payment Details</h4>
                <div class="col-md-12 mb-4">
                    <table class="table table-row-bordered table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>Payment Via</th>
                                <th>Payment Status</th>
                                <th>Total Amount</th>
                                <th>Coupon Discount</th>
                                <th>Shipping Charge</th>
                                <th>Wallet Amount</th>
                                <th>Paid Amount</th>
                                <!-- <th>Action</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ strtoupper($order->payment_via) }}</td>
                                <td>{{ strtoupper($order->payment_status) }}</td>
                                <td>{{ !empty($order->order_amount) ? $order->order_amount : 0 }}</td>
                                <td>{{ !empty($order->coupon_discount) ? $order->coupon_discount : 0 }}</td>
                                <td>{{ !empty($order->service_charge_applied) ? $order->service_charge_applied : 0 }}</td>
                                <td>{{ !empty($order->wallet_amount) ? $order->wallet_amount : 0 }}</td>
                                <td>{{ !empty($order->total_amount) ? $order->total_amount : 0 }}</td>
                                <!-- <td></td> -->
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script>
    $(document).on('change', '#order_status', function() {
        var status = $('#order_status').val();
        var id = $('#order_id').val();
        $.ajax({
            url: "{{ url('admin/orders/update') }}",
            type: "POST",
            dataType: "json",
            data: {
                order_status: status,
                id: id,
                _token: '{{csrf_token()}}'
            },
            success: function(data) {
                location.reload();
                swal("Good job!", data.message, "success");

            }
        });
    });
</script>
@endsection