<?php

namespace App\Traits;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

trait ShippingRate
{
	public function getFedexShipRate(Request $request)
	{
		$lineitems = array();
		$usertoken = request()->bearerToken();
		if (!empty($usertoken)) {
			$user = DB::table('users')->select('id')->where('api_token', $usertoken)->first();
			if (!empty($user)) {
				$carts = Cart::where('user_id', $user->id)->with('product:id,weight')->get();
				foreach ($carts as $cart) {
					$weight = collect(["units" => "LB", "value" => $cart->product->weight]);
					$item = collect(['weight' => $weight]);
					array_push($lineitems, $item);
				}
			}
		} else {
			foreach ($request['product_id'] as $product_id) {
				$product = DB::table('products')->select('weight')->find($product_id);
				if (!empty($product)) {
					$weight = collect(["units" => "LB", "value" => $product->weight]);
					$item = collect(['weight' => $weight]);
					array_push($lineitems, $item);
				}
			}
		}

		$authtoken = getFedexAuthToken();
		$content = json_decode($authtoken);

		$data['accountNumber']['value'] = config('fedex.account_no');

		$data['requestedShipment']['shipper']['address']['city'] = "Memphis";
		$data['requestedShipment']['shipper']['address']['stateOrProvinceCode'] = "TN";
		$data['requestedShipment']['shipper']['address']['postalCode'] = 38104;
		$data['requestedShipment']['shipper']['address']['countryCode'] = "US";

		$data['requestedShipment']['recipient']['address']['city'] = $request->city;
		$data['requestedShipment']['recipient']['address']['stateOrProvinceCode'] = $request->state;
		$data['requestedShipment']['recipient']['address']['postalCode'] = $request->zip;
		$data['requestedShipment']['recipient']['address']['countryCode'] = $request->country;
		$data['requestedShipment']['recipient']['address']['residential'] = true;

		$data['requestedShipment']['pickupType'] = "DROPOFF_AT_FEDEX_LOCATION";
		$data['requestedShipment']['serviceType'] = "GROUND_HOME_DELIVERY";
		$data['requestedShipment']['shipmentSpecialServices']['specialServiceTypes'] = ["HOME_DELIVERY_PREMIUM"];
		$data['requestedShipment']['shipmentSpecialServices']['homeDeliveryPremiumDetail']['homedeliveryPremiumType'] = "APPOINTMENT";
		$data['requestedShipment']['rateRequestType'] = [
			"LIST",
			"ACCOUNT"
		];
		$data['requestedShipment']['requestedPackageLineItems'] = $lineitems;

		try {
			$response = Http::accept('application/json')->withHeaders([
				'Authorization' => 'Bearer ' . $content->access_token,
				'Content-Type' => 'application/json',
				'x-customer-transaction-id' => config('fedex.customer_transaction_id'),
				'x-locale' => 'en_US'
			])->post(config('fedex.url') . 'rate/v1/rates/quotes', $data);
		} catch (\Exception $e) {
			return response()->json(['message' => $e->getMessage(), 'code' => 400], 400);
		}

		$decodedresponse = json_decode($response, true);
		if (isset($decodedresponse['errors']) && !empty($decodedresponse['errors'])) {
			return response()->json(['message' => $decodedresponse['errors'][0]['message'], 'code' => 400], 400);
		} else {
			foreach ($decodedresponse['output']['rateReplyDetails'] as $rateReplyDetails) {
				foreach ($rateReplyDetails['ratedShipmentDetails'] as $ratedShipmentDetails) {
					$rate = $ratedShipmentDetails['totalNetFedExCharge'];
				}
			}
		}

		return $rate;
	}

	public function getSaiaShipRate(Request $request)
	{
		$lineitems = '';
		$usertoken = request()->bearerToken();
		if (!empty($usertoken)) {
			$user = DB::table('users')->select('id')->where('api_token', $usertoken)->first();
			if (!empty($user)) {
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
			}
		} else {
			$lineitems = '<Details>';
			$item = '';
			foreach ($request->product_id as $product_id) {
				$product = DB::table('products')->select('weight', 'width', 'length', 'height')->find($product_id);
				if (!empty($product)) {
					$item .= '<DetailItem>
                        <Width>' . $product->width . '</Width>
                        <Length>' . $product->length . '</Length>
                        <Height>' . $product->height . '</Height>
                        <Weight>' . (int)$product->weight . '</Weight>
                        <Class>50</Class>
                    </DetailItem>';
				}
			}
			$lineitems .= $item . '</Details>';
		}

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
                        <UserID>' . config('saia.user_id') . '</UserID>
                        <Password>' . config('saia.password') . '</Password>
                        <TestMode>' . config('saia.mode') . '</TestMode>
                        <BillingTerms>Prepaid</BillingTerms>
                        <AccountNumber>' . config('saia.account_no') . '</AccountNumber>
                        <Application>Outbound</Application>
                        <OriginCity>Tucker</OriginCity>
                        <OriginState>GA</OriginState>
                        <OriginZipcode>30085</OriginZipcode>
                        <DestinationCity>' . $request->city . '</DestinationCity>
                        <DestinationState>' . $request->state . '</DestinationState>
                        <DestinationZipcode>' . $request->zip . '</DestinationZipcode>
                        ' . $lineitems . '
                    </request>
                    </Create>
                </soap:Body>
            </soap:Envelope>',
			CURLOPT_HTTPHEADER => array(
				'Content-Type: text/xml; charset=utf-8',
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

		return (float)$rate;
	}
}
