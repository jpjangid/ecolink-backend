<?php

namespace App\Http\Controllers\api;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItems;
use App\Models\UserAddress;
use App\Models\CouponUsedBy;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper;
use QuickBooksOnline\API\DataService\DataService;

class OrderController extends Controller
{

    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'               =>  'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors(), 'code' => 400], 400);
        }

        $usertoken = request()->bearerToken();
        $user = DB::table('users')->select('id')->where('api_token', $usertoken)->first();

        $orders = Order::where('user_id', $user->id)->with('items.product')->get();

        if ($orders->isNotEmpty()) {
            foreach ($orders as $order) {
                foreach ($order->items as $item) {
                    $item->product->image = url('storage/products', $item->product->image);
                }
            }
            return response()->json(['message' => 'Data fetched Successfully', 'code' => 200, 'data' => $orders], 200);
        } else {
            return response()->json(['message' => 'No Data Found', 'code' => 400], 400);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'               =>  'required',
            'order_amount'          =>  'required',
            'total_amount'          =>  'required',
            'product_discount'      =>  'required',
            'coupon_discount'       =>  'required',
            'no_items'              =>  'required',
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
            'payment_via'           =>  'required',
            'shippment_via'         =>  'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors(), 'code' => 400], 400);
        }

        $usertoken = request()->bearerToken();
        $user = DB::table('users')->select('id')->where('api_token', $usertoken)->first();

        $cartItems = Cart::where('user_id', $user->id)->with('product')->get();

        $orderNumber = $this->order_no();

        if (isset($request->addressSelect) && $request->addressSelect == 1) {
            $UserAddress = new UserAddress;
            $UserAddress->user_id   = $user->id;
            $UserAddress->name      = $request->billing_name;
            $UserAddress->mobile    = $request->billing_mobile;
            $UserAddress->address   = $request->billing_address;
            $UserAddress->zip       = $request->billing_zip;
            $UserAddress->landmark  = $request->billing_landmark;
            $UserAddress->state     = $request->billing_state;
            $UserAddress->city      = $request->billing_city;
            $UserAddress->save();

            if ($request->sameAsShip == 0) {
                $UserAddress = new UserAddress;
                $UserAddress->user_id   = $user->id;
                $UserAddress->name      = $request->shipping_name;
                $UserAddress->mobile    = $request->shipping_mobile;
                $UserAddress->address   = $request->shipping_address;
                $UserAddress->zip       = $request->shipping_zip;
                $UserAddress->landmark  = $request->shipping_landmark;
                $UserAddress->state     = $request->shipping_state;
                $UserAddress->city      = $request->shipping_city;
                $UserAddress->save();
            }
        }

        $coupon = '';
        if (isset($request->coupon_code) && $request->coupon_discount != 0) {
            $discount = $request->coupon_discount + (isset($request->product_discount) ? $request->product_discount : 0);
            $coupon = DB::table('coupons')->where('code', $request->coupon_code)->first();
            $coupon_id = $coupon->id;
        } else {
            $discount = isset($request->product_discount) ? $request->product_discount : 0;
        }

        $order = Order::create([
            'order_no'                  =>  $orderNumber,
            'user_id'                   =>  $user->id,
            'order_amount'              =>  $request->order_amount,
            'discount_applied'          =>  $discount,
            'total_amount'              =>  $request->total_amount,
            'no_items'                  =>  $request->no_items,
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
            'order_status'              =>  'pending',
            'payment_via'               =>  $request->payment_via,
            'payment_currency'          =>  'dollar',
            'payment_status'            =>  'pending',
            'shippment_via'             =>  $request->shippment_via,
            'shippment_status'          =>  'pending',
            'coupon_id'                 =>  isset($coupon_id) ? $coupon_id : '',
            'coupon_discount'           =>  $request->coupon_discount,
            'order_comments'            =>  $request->order_comments,
            'payment_amount'            =>  $request->total_amount,
        ]);

        foreach ($cartItems as $item) {
            OrderItems::create([
                'order_id'              =>  $order->id,
                'product_id'            =>  $item->product_id,
                'quantity'              =>  $item->quantity,
            ]);
        }

        if (!empty($coupon) && $request->coupon_discount != 0) {
            if ($coupon->type == 'merchandise' || $coupon->type == 'global' || $coupon->type == 'personal_code' || $coupon->type == 'cart_value_discount') {
                CouponUsedBy::create([
                    'coupon_id'         =>  $coupon->id,
                    'user_id'           =>  $user->id,
                    'order_id'          =>  $order->id,
                    'amount'            =>  $request->coupon_discount,
                    'applied_times'     =>  1,
                ]);

                $couponUsed = CouponUsedBy::where('coupon_id', $coupon->id)->get();

                if ($coupon->coupon_limit == count($couponUsed)) {
                    $coupon->status = 1;
                    $coupon->update();
                }
            } else {
                $coupon->times_applied  = $coupon->times_applied + 1;
                if ($coupon->coupon_limit == $coupon->times_applied + 1) {
                    $coupon->status = 1;
                }
                $coupon->update();
            }
        }

        /*
        if ($order->shippment_via == 'saia') {
            $response = $this->shipViaSaia($order->id);
        } else {
            $response = $this->shipViaFedex($order->id);
        }

        $qboresponse = $this->quickBookInvoice($order->user_id);

        $sosresponse = $this->sosItemUpdate();
        */

        if (!empty($order)) {
            foreach ($cartItems as $item) {
                $item->delete();
            }

            // $recent_order = Order::where('id', $order->id)->with('items.product.medias', 'user')->first();
            // $user = DB::table('users')->find($recent_order->user_id);
            // $image = array();
            // foreach ($recent_order->items as $item) {
            //     $media = DB::table('product_media')->where(['product_id' => $item->product->id, 'media_type' => 'image'])->orderby('sequence', 'asc')->first();
            //     if (!empty($media)) {
            //         array_push($image, $media->media);
            //     }
            // }
            // $status = 'ORDER PLACED!!';
        }
        // Notification::create(['title' => "New Order",'message' => 'New Order has beeen placed by '.auth()->user()->email.' with order no: '.$order->order_no]);
        return response()->json(['message' => 'Order Placed Successfully', 'code' => 200, 'data' => $order], 200);
    }

    public function cancelOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'        =>  'required',
            'user_id'   =>  'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors(), 'code' => 400], 400);
        }

        $usertoken = request()->bearerToken();
        $user = DB::table('users')->select('id')->where('api_token', $usertoken)->first();

        $order = Order::where(['id' => $request->id, 'user_id' => $user->id])->first();

        if (!empty($order)) {
            $order->order_status = 'cancelled';
            $order->update();

            return response()->json(['message' => 'Data fetched Successfully', 'data' => $order], 200);
        } else {
            return response()->json(['message' => 'No Order Found', 'code' => 400], 400);
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

    public function shipViaSaia($order_id)
    {
        $order = Order::find($order_id);
        $cartItems = Cart::where('user_id', $order->user_id)->with('product')->get();

        $detailItems = '';
        foreach ($cartItems as $item) {
            $detailItems .= '
            <DetailItem>
                <DestinationZipcode>' . $order->shipping_zip . '</DestinationZipcode>
                <DestinationCountry>' . $order->shipping_country . '</DestinationCountry>
                <Pieces>' . $item->quantity . '</Pieces>
                <Package>BG</Package>
                <Weight>' . $item->product->weight . '</Weight>
                <FoodItem>N</FoodItem>
                <Hazardous>' . $item->product->hazardous == 1 ? 'Y' : 'N' . '</Hazardous>
                <Description>' . $item->product->description . '</Description>
            </DetailItem>';
        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'www.saiasecure.com/webservice/BOL/soap.asmx',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '<?xml version="1.0" encoding="utf-8"?>
            <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
                <soap:Body>
                    <Create xmlns="http://www.SaiaSecure.com/WebService/BOL">
                        <request>
                            <UserID>' . config('saia.user_id') . '</UserID>
                            <Password>' . config('saia.password') . '</Password>
                            <TestMode>' . config('saia.mode') . '</TestMode>
                            <ShipmentDate>' . date('Y-m-d', strtotime($order->created)) . '</ShipmentDate>
                            <BillingTerms>Prepaid</BillingTerms>
                            <BLNumber></BLNumber>
                            <ShipperNumber></ShipperNumber>
                            <PONumber></PONumber>
                            <PrintRates>Y</PrintRates>
                            <Customs>N</Customs>
                            <VICS>N</VICS>
                            <WeightUnits></WeightUnits>
                            <Shipper>
                                <AccountNumber>0747932</AccountNumber>
                            </Shipper>
                            <Consignee>
                                <ContactName>' . $order->shipping_name . '</ContactName>
                                <Address1>' . $order->shipping_address . '</Address1>
                                <City>' . $order->shipping_city . '</City>
                                <State>' . $order->shipping_state . '</State>
                                <Zipcode>' . $order->shipping_zip . '</Zipcode>
                            </Consignee>
                            <Details>' . $detailItems . '</Details>
                        </request>
                    </Create>
                </soap:Body>
            </soap:Envelope>',
            CURLOPT_HTTPHEADER => array(
                'SOAPAction: http://www.SaiaSecure.com/WebService/BOL/Create',
                'Content-Type: text/xml'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return $response;
    }

    public function shipViaFedex($order_id)
    {
        $order = Order::where('id', $order_id)->with('items.product')->first();

        $paymentType = $order->payment_via == 'COD' ? 'RECIPIENT' : 'SENDER';

        $items = array();
        foreach ($order->items as $item) {
            $weight = collect(['value' => $item->product->weight, 'units' => 'LB']);
            $itemWeight = collect(['weight' => $weight]);
            array_push($items, $itemWeight);
        }

        $token = getFedexAuthToken();
        $token = json_decode($token);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => config('fedex.url') . 'ship/v1/shipments',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                "labelResponseOptions": "URL_ONLY",
                "requestedShipment": {
                    "shipper": {
                    "contact": {
                        "personName": "SHIPPER NAME",
                        "phoneNumber": 1234567890,
                        "companyName": "Shipper Company Name"
                    },
                    "address": {
                        "streetLines": [
                        "SHIPPER STREET LINE 1"
                        ],
                        "city": "HARRISON",
                        "stateOrProvinceCode": "AR",
                        "postalCode": 72601,
                        "countryCode": "US"
                    }
                    },
                    "recipients": [
                        {
                            "contact": {
                                "personName": "' . $order->shipping_name . '",
                                "phoneNumber": "' . $order->shipping_mobile . '",
                                "companyName": "' . $order->shipping_name . '"
                            },
                            "address": {
                                "streetLines": [
                                    "' . $order->shipping_address . '"
                                ],
                                "city": "' . $order->shipping_city . '",
                                "stateOrProvinceCode": "' . $order->shipping_state . '",
                                "postalCode": "' . $order->shipping_zip . '",
                                "countryCode": "' . $order->shipping_country . '"
                            }
                        }
                    ],
                    "shipDatestamp": "' . date('Y-m-d', strtotime($order->created_at)) . '",
                    "serviceType": "FEDEX_GROUND",
                    "packagingType": "YOUR_PACKAGING",
                    "pickupType": "USE_SCHEDULED_PICKUP",
                    "blockInsightVisibility": false,
                    "shippingChargesPayment": {
                        "paymentType": "' . $paymentType . '"
                    },
                    "labelSpecification": {
                    "imageType": "PDF",
                    "labelStockType": "PAPER_85X11_TOP_HALF_LABEL"
                    },
                    "requestedPackageLineItems": ' . json_encode($items) . '
                },
                "accountNumber": {
                    "value": "' . config('fedex.account_no') . '"
                }
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'x-customer-transaction-id: ' . config('fedex.customer_transaction_id') . '',
                'x-locale: en_US',
                'Authorization: Bearer ' . $token->access_token . ''
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    public function fedexAuth()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => config('fedex.url') . 'oauth/token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'grant_type=client_credentials&client_id=' . config('fedex.client_id') . '&client_secret=' . config('fedex.client_key') . '',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    public function quickBookInvoice($user_id)
    {
        $cartItems = Cart::where('user_id', $user_id)->with('product')->get();
        $lineItems = array();
        foreach ($cartItems as $item) {
            $name = $item->product->name . ' ' . $item->product->variant;
            // $name = preg_replace('/[^A-Za-z0-9\-]/', ' ', $name);
            // $name = str_replace('-','',$name);
            $itemRef = collect(['name' => $name, 'value' => (string)$item->product->id]);
            $salelineItem = collect(['Qty' => $item->quantity, 'UnitPrice' => $item->product->sale_price, 'ItemRef' => $itemRef]);
            $lineItem = collect(['Description' => $name, 'Amount' => $item->product->sale_price * $item->quantity, 'DetailType' => 'SalesItemLineDetail', 'SalesItemLineDetail' => $salelineItem]);

            array_push($lineItems, $lineItem);
        }

        $custRef = collect(['value' => (string)$user_id]);
        $requestBody = collect(['Line' => $lineItems, 'CustomerRef' => $custRef]);

        $file = file_get_contents('storage/qbo.json');
        $content = json_decode($file, true);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => config('qboconfig.url') . 'v3/company/' . config('qboconfig.company_id') . '/invoice?minorversion=' . config('qboconfig.minorversion') . '',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => "'" . $requestBody . "'",
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                'Authorization: Bearer ' . $content['original']['access_token'],
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $data = json_decode($response);
        if (isset($data->Invoice) && !empty($data->Invoice)) {
            return response()->json(['message' => 'QBO Invoice created successfully', 'code' => 200], 200);
        } else if (isset($data->Fault->Error)) {
            return response()->json(['message' => 'Error Occured', 'Error' => $data, 'code' => 400], 400);
        } else {
            $token = $this->qboRefershToken();
            $data = json_encode($token);
            file_put_contents('storage/qbo.json', $data);
            $this->quickBookInvoice($user_id);
        }
    }

    public function qboCustomer()
    {
        // $users = DB::table('wp_users')->get();
        // $customers = DB::table('wp_customer')->get();
        // return $customers;
        $file = file_get_contents('storage/qbo.json');
        $content = json_decode($file, true);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://sandbox-quickbooks.api.intuit.com/v3/company/4620816365226953830/query?query=select%20*%20from%20Customer&minorversion=65',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                'Authorization: Bearer ' . $content['access_token'],
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        return $response;

        curl_close($curl);
    }

    public function qboRefershToken()
    {
        $file = file_get_contents('storage/qbo.json');
        $content = json_decode($file, true);

        $oauth2LoginHelper = new OAuth2LoginHelper(config('qboconfig.client_id'), config('qboconfig.client_secret'));
        $accessTokenObj = $oauth2LoginHelper->refreshAccessTokenWithRefreshToken($content['original']['refresh_token']);
        $accessTokenValue = $accessTokenObj->getAccessToken();
        $refreshTokenValue = $accessTokenObj->getRefreshToken();

        $data = collect(['access_token' => $accessTokenValue, 'refresh_token' => $refreshTokenValue]);

        return $data;
    }

    public function sosItemUpdate()
    {
        $file = file_get_contents('storage/sos.json');
        $content = json_decode($file, true);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.sosinventory.com/api/v2/item',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded',
                'Host: api.sosinventory.com',
                'Authorization: Bearer ' . $content['access_token']
            ),
        ));

        $response = curl_exec($curl);
        // $data = json_decode($response, true);
        // foreach ($data['data'] as $key => $item){
        //     $product = Product::select('id')->where('sku', $item['sku'])->first();
        //     if(!empty($product)){
        //         $product->wp_id = $item['id'];
        //         $product->update();
        //     }
        // }

        curl_close($curl);
        return $response;

        $token = $this->sosRefreshToken();
        file_put_contents('storage/qbo.json', $token);
        $this->sosItemUpdate();
    }

    public function sosRefreshToken()
    {
        $file = file_get_contents('storage/sos.json');
        $content = json_decode($file, true);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.sosinventory.com/oauth2/token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_POSTFIELDS => 'grant_type=refresh_token&refresh_token=' . $content['refresh_token'],
            CURLOPT_HTTPHEADER => array(
                'Host: api.sosinventory.com',
                'Content-Type: application/x-www-form-urlencoded',
                'Cookie: ARRAffinity=732f04f98c62ba546a70c33d76f429eebd1bdad70935530c9ed3ede578156b3b; ARRAffinitySameSite=732f04f98c62ba546a70c33d76f429eebd1bdad70935530c9ed3ede578156b3b'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }
}
