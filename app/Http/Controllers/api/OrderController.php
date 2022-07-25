<?php

namespace App\Http\Controllers\api;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\UserAddress;
use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Traits\ShippingRate;
use App\Traits\QboRefreshToken;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Models\Product;
use App\Models\CouponUsedBy;
use Illuminate\Support\Facades\Log;
use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper;
use QuickBooksOnline\API\DataService\DataService;

class OrderController extends Controller
{
    use ShippingRate;
    use QboRefreshToken;

    public function index(Request $request)
    {
        $user = $request->user();

        $orders = Order::where('user_id', $user->id)->with('items:order_id,product_id,quantity,item_status', 'items.product:id,parent_id,name,variant,regular_price,sale_price,image,alt,slug', 'items.product.category:id,name,parent_id', 'items.product.category.parent:id,name')->latest()->paginate(20);

        if ($orders->isNotEmpty()) {
            return response()->json(['message' => 'Data fetched Successfully', 'code' => 200, 'data' => $orders], 200);
        } else {
            return response()->json(['message' => 'No Data Found', 'code' => 400], 400);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'billing_name'      => 'required',
            'billing_mobile'    => 'required|digits:10',
            'billing_email'     => 'required|email',
            'billing_address'   => 'required',
            'billing_city'      => 'required',
            'billing_state'     => 'required',
            'billing_country'   => 'required',
            'billing_zip'       => 'required',
            'shipping_name'     => 'required',
            'shipping_mobile'   => 'required|digits:10',
            'shipping_email'    => 'required|email',
            'shipping_address'  => 'required',
            'shipping_city'     => 'required',
            'shipping_state'    => 'required',
            'shipping_country'  => 'required',
            'shipping_zip'      => 'required',
            'payment_via'       => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors(), 'code' => 400], 400);
        }

        $user = $request->user();

        DB::beginTransaction();
        try {
            $cartItems = Cart::select('id', 'user_id', 'product_id', 'quantity', 'lift_gate')->where('user_id', $user->id)->with('product:id,wp_id,discount_type,discount,regular_price,sale_price,weight,hazardous')->get();
            if ($cartItems->isEmpty()) {
                return response()->json(['message' => 'Cart is empty. Please add one or more products for purchase.', 'code' => 400], 400);
            }

            $orderNumber = $this->order_no();

            if ($request->filled($request->addressSelect) && $request->addressSelect == 1) {
                $UserAddress = new UserAddress;
                $UserAddress->user_id = $user->id;
                $UserAddress->name = $request->billing_name;
                $UserAddress->mobile = $request->billing_mobile;
                $UserAddress->address = $request->billing_address;
                $UserAddress->zip = $request->billing_zip;
                $UserAddress->landmark = $request->billing_landmark;
                $UserAddress->state = $request->billing_state;
                $UserAddress->city = $request->billing_city;
                $UserAddress->save();

                if ($request->sameAsShip == 0) {
                    $UserAddress = new UserAddress;
                    $UserAddress->user_id = $user->id;
                    $UserAddress->name = $request->shipping_name;
                    $UserAddress->mobile = $request->shipping_mobile;
                    $UserAddress->address = $request->shipping_address;
                    $UserAddress->zip = $request->shipping_zip;
                    $UserAddress->landmark = $request->shipping_landmark;
                    $UserAddress->state = $request->shipping_state;
                    $UserAddress->city = $request->shipping_city;
                    $UserAddress->save();
                }
            }

            $coupon = '';
            $coupon_id = '';
            $current = date('Y-m-d H:i:s');
            if (isset($request->coupon_code) && !empty($request->coupon_code)) {
                $coupon = Coupon::where([['code', $request->coupon_code], ['offer_start', '<=', $current], ['offer_end', '>=', $current], ['flag', 0], ['status', 0]])->first();
                if ($coupon != null) {
                    $coupon_id = $coupon->id;
                } else {
                    return response()->json(['message' => 'Coupon code is not valid', 'code' => 400], 400);
                }
            }

            $lift_gate = DB::table('static_values')->where('name', 'Lift Gate')->first();
            $hazardous = DB::table('static_values')->where('name', 'Hazardous')->first();

            $lift_gate_amount = $lift_gate->value ?? 0;
            $hazardous_amount = $hazardous->value ?? 0;

            $order_total_amt    = 0;
            $payable_total_amt  = 0;
            $product_discount   = 0;
            $coupon_discount    = 0;
            $discount           = 0;
            $lift_gate_qty      = 0;
            $lift_gate_amt      = 0;
            $item_lift_gate_amt = 0;
            $total_weight       = 0;
            $no_items           = 0;
            $hazardous_qty      = 0;
            $hazardous_amt      = 0;
            $item_hazardous_amt = 0;
            $product_id = array();

            $order_items = array();

            foreach ($cartItems as $cartItem) {
                $order_total_amt += $cartItem->product->regular_price * $cartItem->quantity;
                $payable_total_amt += $cartItem->product->sale_price * $cartItem->quantity;
                $product_discount += ($cartItem->product->regular_price - $cartItem->product->sale_price) * $cartItem->quantity;
                $total_weight += $cartItem->product->weight * $cartItem->quantity;
                $no_items += $cartItem->quantity;
                if ($cartItem->product->hazardous == 1) {
                    $hazardous_qty += 1;
                    $item_hazardous_amt = $hazardous_amount;
                }
                array_push($product_id, $cartItem->product->id);
                $item = ['product_id' => $cartItem->product->id, 'quantity' => $cartItem->quantity, 'sale_price' => $cartItem->product->sale_price, 'lift_gate_amt' => $item_lift_gate_amt, 'hazardous_amt' => $item_hazardous_amt];
                array_push($order_items, $item);
            }

            if ($hazardous_qty > 0) {
                $hazardous_amt = $hazardous_amount;
            }

            if ($request->lift_gate) {
                $lift_gate_amt = $lift_gate_amount;
            }

            $newRequest = new Request(['city' => $request->shipping_city, 'state' => $request->shipping_state, 'zip' => $request->shipping_zip, 'country' => $request->shipping_country, 'product_id' => $product_id]);

            $shipment_via = 0;
            $shipping_charge = 0;
            if ($total_weight >= 71) {
                $shipment_via = 'saia';
                $shipping_charge = $this->getSaiaShipRate($newRequest);
            } else {
                $shipment_via = 'fedex';
                $shipping_charge = $this->getFedexShipRate($newRequest);
            }

            $taxAmount = 0;
            $tax = DB::table('tax_rates')->select('rate')->where('zip', $request->shipping_zip)->first();
            if ($tax != null) {
                if ($coupon != null && $coupon->type == 'cart_value_discount' && $coupon->disc_type == 'percent' && $coupon->discount == '100') {
                    $taxAmount = 0;
                    $coupon_id = '';
                } else {
                    $taxAmount = ($payable_total_amt * $tax->rate) / 100;
                }
            }

            if ($coupon != null) {
                if ($coupon->disc_type == 'percent') {
                    $coupon_discount = ($payable_total_amt * $coupon->discount) / 100;
                } else {
                    $coupon_discount = $coupon->discount;
                }
            }

            $payable_total_amt = $payable_total_amt - $coupon_discount + $lift_gate_amt + $hazardous_amt + $shipping_charge + $taxAmount;
            $discount = $product_discount + $coupon_discount;

            $order = Order::create([
                'order_no'          =>  $orderNumber,
                'user_id'           =>  $user->id,
                'order_amount'      =>  $order_total_amt,
                'discount_applied'  =>  $discount,
                'total_amount'      =>  $payable_total_amt,
                'lift_gate_amt'     =>  $lift_gate_amt,
                'tax_amount'        =>  $taxAmount,
                'hazardous_amt'     =>  $hazardous_amt,
                'no_items'          =>  $no_items,
                'billing_name'      =>  $request->billing_name,
                'billing_mobile'    =>  $request->billing_mobile,
                'billing_email'     =>  $request->billing_email,
                'billing_address'   =>  $request->billing_address,
                'billing_country'   =>  $request->billing_country,
                'billing_state'     =>  $request->billing_state,
                'billing_city'      =>  $request->billing_city,
                'billing_zip'       =>  $request->billing_zip,
                'billing_landmark'  =>  $request->billing_landmark,
                'shipping_name'     =>  $request->shipping_name,
                'shipping_mobile'   =>  $request->shipping_mobile,
                'shipping_email'    =>  $request->shipping_email,
                'shipping_address'  =>  $request->shipping_address,
                'shipping_country'  =>  $request->shipping_country,
                'shipping_state'    =>  $request->shipping_state,
                'shipping_city'     =>  $request->shipping_city,
                'shipping_zip'      =>  $request->shipping_zip,
                'shipping_landmark' =>  $request->shipping_landmark,
                'order_status'      =>  'pending',
                'payment_via'       =>  $request->payment_via,
                'payment_currency'  =>  'dollar',
                'payment_status'    =>  'pending',
                'shippment_via'     =>  $shipment_via,
                'shippment_status'  =>  'pending',
                'coupon_id'         =>  $coupon_id ?? '',
                'coupon_discount'   =>  $coupon_discount,
                'order_comments'    =>  $request->order_comments,
                'payment_amount'    =>  $payable_total_amt,
                'shippment_rate'    =>  $shipping_charge
            ]);

            foreach ($order_items as $item) {
                OrderItems::create([
                    'order_id'      => $order->id,
                    'product_id'    => $item['product_id'],
                    'quantity'      => $item['quantity'],
                    'sale_price'    => $item['sale_price'],
                    'lift_gate_amt' => $item['lift_gate_amt'],
                    'hazardous_amt' => $item['hazardous_amt'],
                ]);
            }

            if (!empty($coupon)) {
                $time_applied = 0;
                if ($coupon != null && $coupon->type == 'cart_value_discount' && $coupon->disc_type == 'percent' && $coupon->discount == '100') {
                    $time_applied = 0;
                }else{
                    $time_applied = 1;
                }
                $coupon->times_applied = $coupon->times_applied + $time_applied;
                if ($coupon->coupon_limit == $coupon->times_applied + $time_applied) {
                    $coupon->status = 1;
                    $coupon->flag = 1;
                }
                $coupon->update();
            }

            if (!empty($order)) {
                foreach ($cartItems as $item) {
                    $item->delete();
                }
            }

            // if ($order->shippment_via == 'saia') {
            //     $response = $this->shipViaSaia($order->id);
            // } else {
            //     $response = $this->shipViaFedex($order->id);
            // }

            // if($user->wp_id == null && $user->company_name != null){
            //     $this->qboCustomer($user->company_name, $user->id);
            // }

            // if($user->wp_id != null){
            //     $qboresponse = $this->quickBookInvoice($order->user_id, $order->id);
            // }

            // $sosresponse = $this->sosItemUpdate();

            DB::commit();

            return response()->json(['message' => 'Order Placed Successfully', 'code' => 200, 'data' => $order], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['message' => $e->getMessage(), 'code' => 400], 400);
        }
    }

    public function cancelOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors(), 'code' => 400], 400);
        }

        $user = $request->user();

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

    public function shipViaSaia()
    {
        $order_id = 1;
        $order = Order::find($order_id);
        $cartItems = Cart::where('user_id', $order->user_id)->with('product')->get();

        $detailItems = '';
        foreach ($cartItems as $item) {
            $hazardous = $item->product->hazardous == 1 ? 'Y' : 'N';
            $description = strip_tags($item->product->description);
            $detailItems .= '
            <DetailItem>
                <DestinationZipcode>' . $order->shipping_zip . '</DestinationZipcode>
                <DestinationCountry>' . $order->shipping_country . '</DestinationCountry>
                <Class>50</Class>
                <Package>BG</Package>
                <Pieces>' . $item->quantity . '</Pieces>
                <Weight>' . $item->product->weight . '</Weight>
                <FoodItem>N</FoodItem>
                <Hazardous>' . $hazardous . '</Hazardous>
                <Description>' . $description . '</Description>
            </DetailItem>';
        }

        try {
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
                            <ShipmentDate>' . date('Y-m-d', strtotime($order->created_at)) . '</ShipmentDate>
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
                    'Content-Type: text/xml; charset=utf-8',
                    'except:application/json'
                ),
            ));

            $response = curl_exec($curl);

            $clean_xml = str_ireplace(['SOAP-ENV:', 'SOAP:'], '', $response);
            $xml = simplexml_load_string($clean_xml);
            $data = json_encode($xml, true);
            $newdata = json_decode($data, true);

            curl_close($curl);

            if (isset($newdata['Body']['CreateResponse']['CreateResult']['ProNumber'])) {
                return response()->json(['message' => 'Shippment created successfully', 'response' => $newdata, 'code' => 200], 200);
            } else {
                return response()->json(['message' => 'Oops! Something went wrong', 'code' => 400], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'code' => 400], 400);
        }
    }

    public function shipViaFedex()
    {
        $order_id = 1;
        $order = Order::where('id', $order_id)->with('items.product')->first();

        $paymentType = $order->payment_via == 'COD' ? 'RECIPIENT' : 'SENDER';

        $items = array();
        foreach ($order->items as $item) {
            $weight = collect(['value' => $item->product->weight, 'units' => 'LB']);
            $itemWeight = collect(['weight' => $weight]);
            array_push($items, $itemWeight);
        }

        $token = getFedexAuthToken();
        $content = json_decode($token);

        $data['labelResponseOptions'] = "URL_ONLY";

        //Shipper Info
        $data['requestedShipment']['shipper']['contact']['personName'] = "SHIPPER NAME";
        $data['requestedShipment']['shipper']['contact']['phoneNumber'] = 1234567890;
        $data['requestedShipment']['shipper']['contact']['companyName'] = "Shipper Company Name";
        $data['requestedShipment']['shipper']['address']['streetLines'] = ["SHIPPER STREET LINE 1"];
        $data['requestedShipment']['shipper']['address']['city'] = "HARRISON";
        $data['requestedShipment']['shipper']['address']['stateOrProvinceCode'] = "AR";
        $data['requestedShipment']['shipper']['address']['postalCode'] = 72601;
        $data['requestedShipment']['shipper']['address']['countryCode'] = "US";

        //Recipient Info
        $contact['personName'] = $order->shipping_name;
        $contact['phoneNumber'] = $order->shipping_mobile;
        $contact['companyName'] = $order->shipping_name;
        $address['streetLines'] = [$order->shipping_address];
        $address['city'] = $order->shipping_city;
        $address['stateOrProvinceCode'] = $order->shipping_state;
        $address['postalCode'] = $order->shipping_zip;
        $address['countryCode'] = $order->shipping_country;

        $recipients = ['contact' => $contact, 'address' => $address];
        $data['requestedShipment']['recipients'] = [$recipients];

        $data['requestedShipment']['shipDatestamp'] = date('Y-m-d', strtotime($order->created_at));
        $data['requestedShipment']['serviceType'] = "FEDEX_GROUND";
        $data['requestedShipment']['packagingType'] = "YOUR_PACKAGING";
        $data['requestedShipment']['pickupType'] = "USE_SCHEDULED_PICKUP";
        $data['requestedShipment']['blockInsightVisibility'] = false;
        $data['requestedShipment']['shippingChargesPayment']['paymentType'] = $paymentType;
        $data['requestedShipment']['labelSpecification']['imageType'] = "PDF";
        $data['requestedShipment']['labelSpecification']['labelStockType'] = "PAPER_85X11_TOP_HALF_LABEL";
        $data['requestedShipment']['requestedPackageLineItems'] = $items;
        $data['accountNumber']['value'] = config('fedex.account_no');

        try {
            $response = Http::accept('application/json')->withHeaders([
                'Authorization' => 'Bearer ' . $content->access_token,
                'Content-Type' => 'application/json',
                'x-customer-transaction-id' => config('fedex.customer_transaction_id'),
                'x-locale' => 'en_US'
            ])->post(config('fedex.url') . 'ship/v1/shipments', $data);

            return response()->json(['message' => 'Shippment created successfully', 'response' => $response, 'code' => 200], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'code' => 400], 400);
        }
    }

    public function quickBookInvoice($user_id, $order_id)
    {
        $cartItems = Cart::where('user_id', $user_id)->with('product')->get();
        $user = DB::table('users')->find($user_id);
        $lineItems = array();
        foreach ($cartItems as $item) {
            $name = $item->product->name . ' ' . $item->product->variant;
            $itemRef = ['name' => $name, 'value' => (string)$item->product->wp_id];
            $salelineItem = ['Qty' => $item->quantity, 'UnitPrice' => $item->product->sale_price, 'ItemRef' => $itemRef];
            $lineItem = ['Description' => $name, 'Amount' => $item->product->sale_price * $item->quantity, 'DetailType' => 'SalesItemLineDetail', 'SalesItemLineDetail' => $salelineItem];

            array_push($lineItems, $lineItem);
        }

        $custRef = ['value' => (string)$user->wp_id];
        $requestBody = ['Line' => $lineItems, 'CustomerRef' => $custRef];

        $file = file_get_contents('storage/qbo.json');
        $content = json_decode($file, true);

        try {
            $response = Http::accept('application/json')->withHeaders([
                'Authorization' => 'Bearer ' . $content['access_token'],
                'Content-Type' => 'application/json'
            ])->post(config('qboconfig.accounting_url') . 'v3/company/' . config('qboconfig.company_id') . '/invoice?minorversion=' . config('qboconfig.minorversion'), $requestBody);

            $data = json_decode($response);

            if (isset($data->Invoice) && !empty($data->Invoice)) {
                return response()->json(['message' => 'QBO Invoice created successfully', 'response' => $data, 'code' => 200], 200);
            }
            if (isset($data->fault->error[0]->code)) {
                $type = "online";
                $token = $this->accessToken($type);
                $data = json_encode($token);
                file_put_contents('storage/qbo.json', $data);
                return $this->quickBookInvoice($user_id, $order_id);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'code' => 400], 400);
        }
    }

    public function qboCustomer($companyName, $user_id)
    {
        $file = file_get_contents('storage/qbo.json');
        $content = json_decode($file, true);
        $company_name = str_replace("'", "\'", $companyName);
        $company_name = str_replace(' ', '%20', $company_name);

        try {
            $response = Http::accept('application/json')->withHeaders([
                'Authorization' => 'Bearer ' . $content['access_token'],
                'Content-Type' => 'application/json'
            ])->get(config('qboconfig.accounting_url') . 'v3/company/' . config('qboconfig.company_id') . '/query?query=select%20*%20from%20Customer%20Where%20CompanyName%20=%20\'' . $company_name . '\'&minorversion=' . config('qboconfig.minorversion'));

            $data = json_decode($response);
            if (isset($data->fault->error[0]->code)) {
                $type = "online";
                $token = $this->accessToken($type);
                $data = json_encode($token);
                file_put_contents('storage/qbo.json', $data);
                return $this->qboCustomer($companyName, $user_id);
            }
            if (isset($data->QueryResponse->Customer)) {
                $user = User::find($user_id);
                $user->wp_id = $data->QueryResponse->Customer[0]->Id;
                $user->update();

                return response()->json(['message' => 'Customer data fetched Successfully', 'code' => 200], 200);
            }
            if (!empty($data->QueryResponse)) {
                $response = $this->createQboCustomer($companyName, $user_id);

                return $response;
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'code' => 400], 400);
        }
    }

    public function createQboCustomer($companyName, $user_id)
    {
        $user = User::find($user_id);

        $file = file_get_contents('storage/qbo.json');
        $content = json_decode($file, true);

        $data['FullyQualifiedName'] = $user->name;
        $data['PrimaryEmailAddr']['Address'] = $user->email;
        $data['DisplayName'] = $user->name;
        $data['PrimaryPhone']['FreeFormNumber'] = $user->phone;
        $data['CompanyName'] = $user->company_name;
        $data['BillAddr']['CountrySubDivisionCode'] = $user->state;
        $data['BillAddr']['City'] = $user->city;
        $data['BillAddr']['PostalCode'] = $user->pincode;
        $data['BillAddr']['Line1'] = $user->address;
        $data['BillAddr']['Country'] = 'USA';
        $data['GivenName'] = $user->name;

        try {
            $response = Http::accept('application/json')->withHeaders([
                'Authorization' => 'Bearer ' . $content['access_token'],
                'Content-Type' => 'application/json'
            ])->post(config('qboconfig.accounting_url') . 'v3/company/' . config('qboconfig.company_id') . '/customer', $data);

            $data = json_decode($response);

            if (isset($data->Customer)) {
                $user = User::find($user_id);
                $user->wp_id = $data->Customer->Id;
                $user->update();

                return response()->json(['message' => 'Customer created Successfully', 'code' => 200], 200);
            }

            if (isset($data->fault->error[0]->code) && $data->fault->error[0]->code == 3200) {
                $type = "online";
                $token = $this->accessToken($type);
                $data = json_encode($token);
                file_put_contents('storage/qbo.json', $data);
                return $this->createQboCustomer($companyName, $user_id);
            }

            if (isset($data->Fault->Error[0]->code) && $data->Fault->Error[0]->code == 6240) {
                return response()->json(['message' => $data->Fault->Error[0]->Message, 'code' => 400], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'code' => 400], 400);
        }
    }

    public function qboPayment(Request $request)
    {
        $card_details['card']['name'] = $request->card_name;
        $card_details['card']['number'] = $request->card_number;
        $card_details['card']['expMonth']  = $request->card_expMonth;
        $card_details['card']['expYear'] = $request->card_expYear;
        $card_details['card']['cvc'] = $request->card_cvc;
        $request_id = strtoupper(Str::random(20));
        $card_token = '';
        try {
            $qboPaymentTokenresponse = $this->qboPaymentToken($card_details, $request_id);
            if (isset($qboPaymentTokenresponse['value']) && !empty($qboPaymentTokenresponse['value'])) {
                $card_token = $qboPaymentTokenresponse['value'];
            } else {
                return response()->json(['message' => $qboPaymentTokenresponse, 'code' => 400], 400);
            }

            $file = file_get_contents('storage/qbopayment.json');
            $content = json_decode($file, true);

            $payment_data['token'] = $card_token;
            $payment_data['currency'] = 'USD';
            $payment_data['amount'] = $request->amount;
            $payment_data['context']['mobile'] = false;
            $payment_data['context']['isEcommerce'] = true;

            $qboPaymentresponse = Http::accept('application/json')->withHeaders([
                'request-id' => $request_id,
                'Authorization' => 'Bearer ' . $content['access_token'],
                'Content-Type' => 'application/json'
            ])->post(config('qboconfig.payment_url') . 'quickbooks/v4/payments/charges', $payment_data);

            if (isset($qboPaymentresponse['code']) && $qboPaymentresponse['code'] == 'AuthenticationFailed') {
                $type = "payment";
                $token = $this->accessToken($type);
                $data = json_encode($token);
                file_put_contents('storage/qbopayment.json', $data);
                return $this->qboPayment($request);
            }

            $data = ['payment_id' => $request_id, 'response' => json_decode($qboPaymentresponse)];

            return response()->json(['message' => 'Payment Successfull', 'data' => $data, 'code' => 200], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'code' => 400], 400);
        }
    }

    public function qboPaymentToken($card_details, $request_id)
    {
        try {
            $response = Http::withHeaders([
                'request-id' => $request_id,
                'Content-Type' => 'application/json'
            ])->post(config('qboconfig.payment_url') . 'quickbooks/v4/payments/tokens', $card_details);

            return $response;
        } catch (\Exception $e) {
            return  $e->getMessage();
        }
    }

    public function sosItemUpdate()
    {
        $file = file_get_contents('storage/sos.json');
        $content = json_decode($file, true);
        $response = Http::withHeaders([
            'Host' => 'api.sosinventory.com',
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Authorization' => 'Bearer ' . $content['access_token']
        ])->get('https://api.sosinventory.com/api/v2/item');

        $data = json_decode($response, true);

        if (isset($data['data'])) {
            foreach ($data['data'] as $key => $item) {
                $product = Product::select('id')->where('sku', $item['sku'])->first();
                if (!empty($product)) {
                    $product->wp_id = $item['id'];
                    $product->sale_price = $item['salesPrice'];
                    $product->regular_price = $item['baseSalesPrice'];
                    if ($item['customFields'] != null) {
                        foreach ($item['customFields'] as $customField) {
                            if ($customField['name'] == 'Insurance') {
                                $product->insurance = $customField['value'] == true ? 1 : 0;
                            }
                        }
                    }
                    $product->update();
                }
            }
            return $response;
        }

        if (isset($data['Message'])) {
            $token = $this->sosRefreshToken();
            file_put_contents('storage/sos.json', $token);
            return $this->sosItemUpdate();
        }
    }

    public function sosRefreshToken()
    {
        $file = file_get_contents('storage/sos.json');
        $content = json_decode($file, true);

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
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return $response;
    }
}
