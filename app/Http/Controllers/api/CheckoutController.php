<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Coupon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $usertoken = request()->bearerToken();
        $user = DB::table('users')->select('id')->where('api_token', $usertoken)->first();

        $carts = Cart::select('id', 'user_id', 'product_id', 'quantity')->where('user_id', $user->id)->with('product')->get();

        $user = DB::table('users')->select('id', 'name', 'email', 'address', 'city', 'state', 'country', 'pincode', 'mobile')->find($user->id);

        $addresses = DB::table('user_addresses')->where('user_id', $user->id)->get();

        $order_total = 0;
        $payable = 0;
        $product_discount = 0;
        $coupon_discount = 0;
        $total_discount = 0;
        $product_count = 0;
        if ($carts->isNotEmpty()) {
            foreach ($carts as $cart) {
                if (!isset($cart->product->coupon_discount)) {
                    $cart->product->coupon_discount = 0;
                }
                $cart->product->image = asset('storage/products/' . $cart->product->image);
                $product_discount = $cart->product->regular_price - $cart->product->sale_price;
                $product_discount = $product_discount * $cart->quantity;
                $order_total += $cart->product->sale_price * $cart->quantity;
                $coupon_discount += $cart->product->coupon_discount * $cart->quantity;
                $total_discount += $product_discount + $coupon_discount;
                $product_count +=  $cart->quantity;
            }
            $payable = $order_total - $coupon_discount;
        }

        $current = date('Y-m-d H:i:s');

        $coupons = Coupon::select('id', 'name', 'code', 'disc_type', 'discount')->where(['flag' => 0])->where([['offer_start', '<=', $current], ['offer_end', '>=', $current]])->orWhere('user_id', $user->id)->get();

        $data = collect(['carts' => $carts, 'user' => $user, 'order_total' => $order_total, 'payable' => $payable, 'total_discount' => $total_discount, 'product_count' => $product_count, 'coupons' => $coupons, 'addresses' => $addresses]);

        return response()->json(['message' => 'Data fetched Successfully', 'code' => 200, 'data' => $data], 200);
    }

    public function getFedexShippingRates(Request $request)
    {
        $usertoken = request()->bearerToken();
        $user = DB::table('users')->select('id')->where('api_token', $usertoken)->first();
        $carts = Cart::where('user_id', $user->id)->with('product:id,weight')->get();

        $lineitems = array();
        foreach ($carts as $cart) {
            $weight = collect(["units" => "LB", "value" => $cart->product->weight]);
            $item = collect(['weight' => $weight]);
            array_push($lineitems, $item);
        }

        $authtoken = getFedexAuthToken();
        $decodedtoken = json_decode($authtoken);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => config('fedex.url') . 'rate/v1/rates/quotes',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                "accountNumber": {
                    "value": "' . config('fedex.account_no') . '"
                },
                "requestedShipment": {
                    "shipper": {
                        "address": {
                            "city": "Memphis",
                            "stateOrProvinceCode": "TN",
                            "postalCode": 38104,
                            "countryCode": "US"
                        }
                    },
                    "recipient": {
                        "address": {
                            "city": "' . $request->city . '",
                            "stateOrProvinceCode": "' . $request->state . '",
                            "postalCode": ' . $request->zip . ',
                            "countryCode": "' . $request->country . '",
                            "residential": true
                        }
                    },
                    "pickupType": "DROPOFF_AT_FEDEX_LOCATION",
                    "serviceType": "GROUND_HOME_DELIVERY",
                    "shipmentSpecialServices": {
                        "specialServiceTypes": [
                            "HOME_DELIVERY_PREMIUM"
                        ],
                        "homeDeliveryPremiumDetail": {
                            "homedeliveryPremiumType": "APPOINTMENT"
                        }
                    },
                    "rateRequestType": [
                        "LIST",
                        "ACCOUNT"
                    ],
                    "requestedPackageLineItems": ' . json_encode($lineitems) . '
                }
            }',
            CURLOPT_HTTPHEADER => array(
                'x-customer-transaction-id: ' . config('fedex.customer_transaction_id') . '',
                'x-locale: en_US',
                'Authorization: Bearer ' . $decodedtoken->access_token,
                'Content-Type: application/json'
            ),
        ));

        $totalNetCharge = 0;
        $response = curl_exec($curl);
        $decodedresponse = json_decode($response, true);
        if(isset($decodedresponse['errors']) && !empty($decodedresponse['errors'])){
            return response()->json(['message' => $decodedresponse['errors'][0]['message'], 'code' => 400], 400);
        }else{
            foreach ($decodedresponse['output']['rateReplyDetails'] as $rateReplyDetails) {
                foreach ($rateReplyDetails['ratedShipmentDetails'] as $ratedShipmentDetails) {
                    $totalNetCharge = $ratedShipmentDetails['totalNetFedExCharge'];
                }
            }
        }

        curl_close($curl);

        return response()->json(['message' => 'Rate fetched successfully.', 'rate' => $totalNetCharge, 'code' => '200'], 200);
    }

    public function getSaiaShippingRates(Request $request)
    {
        $usertoken = request()->bearerToken();
        $user = DB::table('users')->select('id')->where('api_token', $usertoken)->first();
        $carts = Cart::where('user_id', $user->id)->with('product:id,weight,width,length,height')->get();

        $lineitems = '<Details>';
        $item = '';
        foreach ($carts as $cart) {
            $item .= '<DetailItem>
                <Width>' . $cart->product->width . '</Width>
                <Length>' . $cart->product->length . '</Length>
                <Height>' . $cart->product->height . '</Height>
                <Weight>' . (int)$cart->product->weight . '</Weight>
                <Class>50</Class>
            </DetailItem>';
        }
        $lineitems .= $item . '</Details>';

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://www.saiasecure.com/webservice/ratequote/soap.asmx',
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
                    <Create xmlns="http://www.saiasecure.com/WebService/ratequote/">
                    <request>
                        <UserID>'.config('saia.user_id').'</UserID>
                        <Password>'.config('saia.password').'</Password>
                        <TestMode>'.config('saia.mode').'</TestMode>
                        <BillingTerms>Prepaid</BillingTerms>
                        <AccountNumber>'.config('saia.account_no').'</AccountNumber>
                        <Application>Outbound</Application>
                        <OriginCity>Tucker</OriginCity>
                        <OriginState>GA</OriginState>
                        <OriginZipcode>30085</OriginZipcode>
                        <DestinationCity>'.$request->city.'</DestinationCity>
                        <DestinationState>'.$request->state.'</DestinationState>
                        <DestinationZipcode>'.$request->zip.'</DestinationZipcode>
                        '.$lineitems.'
                    </request>
                    </Create>
                </soap:Body>
            </soap:Envelope>',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: text/xml; charset=utf-8',
                'Cookie: TS01cfb1b0=01dd6f358ae465ecd43d9146ca0088707409e4db6a3f6310de09bc0afdc7f957947346a4240262af504afb92453b7eefbdf31f61e9',
                'except:application/json'
            ),
        ));

        $response = curl_exec($curl);
        $clean_xml = str_ireplace(['SOAP-ENV:', 'SOAP:'], '', $response);
        $xml = simplexml_load_string($clean_xml);
        $data = json_encode($xml, true);
        $newdata = json_decode($data, true);
        $rate = $newdata['Body']['CreateResponse']['CreateResult']['TotalInvoice'];

        curl_close($curl);
        return response()->json(['message' => 'Rate fetched successfully.', 'rate' => $rate, 'code' => '200'], 200);
    }
}
