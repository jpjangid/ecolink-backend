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
                        <option value="completed" {{ $order->order_status == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ $order->order_status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
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
                                <th>Active/Inactive</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $key => $item)
                            @if($item->flag == '0')
                            <tr>
                                <td><img src="{{ asset('storage/products/'.$item->product->image) }}" alt="{{ $item->product->image }}" style="height: 5rem;"></td>
                                <td>{{ $item->product->name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>${{ number_format((float)$item->sale_price,2,'.',','),}}</td>
                                <td>${{ number_format((float)$item->sale_price * $item->quantity,2,'.',','), }}</td>
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
                    <table class="table table-row-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Payment Via</th>
                                <th>Payment Status</th>
                                <th>Total Amount</th>
                                <th>Hazardous Amount</th>
                                <th>Lift Gate Amount</th>
                                <th>CERT Fee</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $amount = number_format((float)$order->order_amount,2,'.',',');
                            @endphp
                            <tr>
                                <td>{{ strtoupper($order->payment_via) }}</td>
                                <td>{{ strtoupper($order->payment_status) }}</td>
                                <td>
                                    @if(!empty($order->order_amount))
                                    ${{ number_format((float)$order->order_amount,2,'.',','), }}
                                    @else
                                    $0
                                    @endif
                                </td>
                                <td>
                                    @if(!empty($order->hazardous_amt))
                                    ${{ number_format((float)$order->hazardous_amt,2,'.',','), }}
                                    @else
                                    $0
                                    @endif
                                </td>
                                <td>
                                    @if(!empty($order->lift_gate_amt))
                                    ${{ number_format((float)$order->lift_gate_amt,2,'.',','), }}
                                    @else
                                    $0
                                    @endif
                                </td>
                                <td>
                                    @if(!empty($order->cert_fee_amt))
                                    ${{ number_format((float)$order->cert_fee_amt,2,'.',','), }}
                                    @else
                                    $0
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="table table-row-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Coupon Discount</th>
                                <th>Shipping Via</th>
                                <th>Shipping Charge</th>
                                <th>Taxable Amount</th>
                                <th colspan="2">Paid Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $amount = number_format((float)$order->order_amount,2,'.',',');
                            @endphp
                            <tr>
                                <td>@if(!empty($order->coupon_discount))
                                    ${{ number_format((float)$order->coupon_discount,2,'.',','), }}
                                    @else
                                    $0
                                    @endif
                                </td>
                                <td>{{ strtoupper($order->shippment_via) }}</td>
                                <td>@if(!empty($order->shippment_rate))
                                    ${{ number_format((float)$order->shippment_rate,2,'.',','), }}
                                    @else
                                    $0
                                    @endif
                                </td>
                                <td>@if(!empty($order->tax_amount))
                                    ${{ number_format((float)$order->tax_amount,2,'.',','), }}
                                    @else
                                    $0
                                    @endif
                                </td>
                                <td>@if(!empty($order->total_amount))
                                    ${{ number_format((float)$order->total_amount,2,'.',','), }}
                                    @else
                                    $0
                                    @endif
                                </td>
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
            url: "{{ url('admin/orders/update_detail') }}",
            type: "POST",
            dataType: "json",
            data: {
                order_status: status,
                id: id,
                _token: '{{csrf_token()}}'
            },
            success: function(data) {
                swal("Good job!", data.message, "success").then((value) => {
                    location.reload();
                });
            }
        });
    });
</script>
@endsection