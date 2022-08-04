<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Email</title>
</head>
<style>
    td, th, tr, p, h1, span, b {
        color: #000;
    }
    td, th {
        width:35%;
    }
</style>

<body style="margin:0;">
    <div style="font:normal 16px 'Poppins',sans-serif;width:600px;max-width:100%; margin:auto;background:#f1f1f1;line-height:1.3">
        <div style="border-bottom:1px solid #9e9e9e; background:#55b15c; margin:0 30px;text-align:center;padding:15px 15px 12px">
            <img src="{{ asset('ECOLINK_EMAIL_LOGO.png') }}" alt="Ecolink">
        </div>
        <div style="padding:0 15px;">
            {{-- <div style="padding:35px 20px;text-align:center">
                <p style="font-size:24px;font-weight:normal;margin:0;text-align:left;"><?php echo $order->email; ?></p>
                <p style="font-size:18px;font-weight:normal;margin:0;text-align:left; padding-left:0px;">Your Order Successfully Placed </p>
            </div> --}}
            <div style="text-align:center;margin-top:10px;">
                <h1 style="text-align: center" class="summary-head">Order Summary</h1>
            <p class="summary-body">Your Order with <span style="font-weight:700"> Order Id:{{$order->order_no}}</span> Has been Placed Successfully.</p>
            @foreach($order->items as $key => $item)
            <table class="table-sm table-borderless productTable">
                {{-- <tr>
                    <td rowspan="12"><img src="{{ asset('') }}" style="float:left; height:60%; width:60%;"></td>
                </tr> --}}
                <tr>
                    <th>Product</th>
                    <td>{{ $item->product->name }}</td>
                </tr>
                <tr>
                    <th>Quantity</th>
                    <td>{{ $item->quantity }}</td>
                </tr>
                <tr>
                    <th>Price</th>
                    <td>&#8377; {{ number_format((float)$item->product->regular_price, 2, '.', '') }}</td>
                </tr>
            </table>
            <hr>
            @endforeach
            <table class="table-sm table-borderless productTable" width="100%">
                <tr>
                    <th>Sub Total</th>
                    <td>&#8377; {{ number_format((float)$order->order_amount, 2, '.', '') }}</td>
                </tr>
                <tr>
                    <th>Coupon Discount</th>
                    <td>&#8377; {{ number_format((float)$order->discount_applied, 2, '.', '') }}</td>
                </tr>
                <tr>
                    <th>Tax Applied</th>
                    <td>&#8377; {{ number_format((float)$order->tax_amount, 2, '.', '') }}</td>
                </tr>
                <tr>
                    <th>Shipping Charges</th>
                    <td>&#8377; {{ number_format((float)$order->shippment_rate, 2, '.', '') }}</td>
                </tr>
                <tr>
                    <th>Total Amount</th>
                    <td>&#8377; {{ number_format((float)$order->payment_amount, 2, '.', '') }}</td>
                </tr>
            </table>
         
            <br>
            <b>Address Details</b>
            <table width="100%" style="border: 1px solid black;">
                <tr>
                    <td><b>Billing</b></td>
                    <td><b>Shipping</b></td>
                </tr>
                <tr>
                    <td>{{ $order->billing_name }}</td>
                    <td>{{ $order->shipping_name }}</td>
                </tr>
                <tr>
                    <td>{{ $order->billing_mobile }}</td>
                    <td>{{ $order->shipping_mobile }}</td>
                </tr>
                <tr>
                    <td>{{ $order->billing_address }}</td>
                    <td>{{ $order->shipping_address }}</td>
                </tr>
                <tr>
                    <td>{{ $order->billing_city }}</td>
                    <td>{{ $order->shipping_city }}</td>
                </tr>
                <tr>
                    <td>{{ $order->billing_state }}</td>
                    <td>{{ $order->shipping_state }}</td>
                </tr>
                <tr>
                    <td>{{ $order->billing_zip }}</td>
                    <td>{{ $order->shipping_zip }}</td>
                </tr>
            </table>
            <table width="100%" style="border: 1px solid black;">
                <tr>
                    <td><b>Payment Mode</b></td>
                    <td><b>Payment Status</b></td>
                </tr>
                <tr>
                    <td>{{ strtoupper($order->payment_via) }}</td>
                    <td>{{ strtoupper($order->payment_status) }}</td>
                </tr>
            </table>
            </div>
        </div>
        <div style="padding:0 15px;">
            <div style="max-width:532px;margin:5px auto 0;text-align:center;background:#fff;padding:0 10px 10px">
                <p style="background:#f4f4f4;width:150px;height:20px;margin:0 auto"></p>
                <p style="font-size:16px;margin:-8px 0 14px;text-align:center;"><span style="border-bottom:1px solid #e51a4b">Visit Us</span></p>
                <div>
                    <a href="#" target="_blank" style="margin:0 3px"><img src="{{ asset('storage/images/fb.png') }}" alt="Facebook"></a>
                    <a href="#" target="_blank" style="margin:0 3px"><img src="{{ asset('storage/images/twitter.png') }}" alt="Twitter"></a>
                    <a href="#" target="_blank" style="margin:0 3px"><img src="{{ asset('storage/images/insta.png') }}" alt="Instagram"></a>
                    <a href="#" target="_blank" style="margin:0 3px"><img src="{{ asset('storage/images/pinterest.png') }}" alt="Pinterest"></a>
                </div>
                <div>
                    <p style="text-align:center;"> For any Query/suggestion email on <a href="mailto:info@ecolink.com">info@ecolink.com</a></p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>