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
		$rate = 0;
		if (!empty($usertoken)) {
			$user = DB::table('users')->select('id')->where('api_token', $usertoken)->first();
			if (!empty($user)) {
				$carts = DB::table('carts')->where('user_id',$user->id)
				->leftJoin('products','carts.product_id','=','products.id')
				->leftJoin('dropships', 'products.dropship', '=', 'dropships.id')
				->select('products.id','products.dropship','products.weight','dropships.zip','dropships.state','dropships.country','dropships.city','carts.quantity')
				->get()->groupBy('zip');
				
				foreach($carts as $key => $product_datas) {
					$products_array = array(); 
					$state = "";
					$city = "";
					$zip = "";
					$country = "";
					foreach($product_datas as $product_data) {
						empty($state) ? $state = $product_data->state: '';
						empty($city) ? $city = $product_data->city: '';
						empty($zip) ? $zip = $product_data->zip: '';
						empty($country) ? $country = $product_data->country: '';
						if(!empty($product_data) && !empty($product_data->quantity) && $product_data->quantity > 0) {
							for ($x = 1; $x <= intval($product_data->quantity); $x++) {
								$weight = collect(["units" => "LB", "value" => $product_data->weight]);
								$item = collect(['weight' => $weight]);
								array_push($products_array, $item);
							}
						}
					}
					if(count($products_array) > 0 && !empty($state) && !empty($city) && !empty($zip) && !empty($country)) {
						$state_collection['city'] = $city;
						$state_collection['state'] = $state;
						$state_collection['country'] = $country;
						$state_collection['zip'] = $zip;
						$rate += $this->getFedShipRate($request,$products_array,$state_collection);
					}
				}
			}
		} else {
			if($request['product_id'] != "" && count($request['product_id']) > 0) {
				$product_ids = array_column($request['product_id'],'id');
				$products = DB::table('products')->whereIn('products.id',$product_ids)->leftJoin('dropships', 'products.dropship', '=', 'dropships.id')->select('products.id','products.dropship','products.weight','dropships.zip','dropships.state','dropships.country','dropships.city')->get()->groupBy('zip');
				foreach($products as $key => $product_datas) {
					$products_array = array(); 
					$state = "";
					$city = "";
					$zip = "";
					$country = "";
					foreach($product_datas as $product_data) {
						empty($state) ? $state = $product_data->state: '';
						empty($city) ? $city = $product_data->city: '';
						empty($zip) ? $zip = $product_data->zip: '';
						empty($country) ? $country = $product_data->country: '';
						$data = collect($request['product_id'])->where('id',$product_data->id)->first();
						if(!empty($data) && !empty($data['qty']) && $data['qty'] > 0) {
							for ($x = 1; $x <= intval($data['qty']); $x++) {
								$weight = collect(["units" => "LB", "value" => $product_data->weight]);
								$item = collect(['weight' => $weight]);
								array_push($products_array, $item);
							}
						}
					}
					if(count($products_array) > 0 && !empty($state) && !empty($city) && !empty($zip) && !empty($country)) {
						$state_collection['city'] = $city;
						$state_collection['state'] = $state;
						$state_collection['country'] = $country;
						$state_collection['zip'] = $zip;
						$rate += $this->getFedShipRate($request,$products_array,$state_collection);
					}
				}
			}
		}
	
		return $rate;
	}

	public function getFedShipRate($request,$item,$product) {

		$authtoken = getFedexAuthToken();
		$content = json_decode($authtoken);

		$data['accountNumber']['value'] = config('fedex.account_no');

		$data['requestedShipment']['shipper']['address']['city'] = $product['city'];
		$data['requestedShipment']['shipper']['address']['stateOrProvinceCode'] = $product['state'];
		$data['requestedShipment']['shipper']['address']['postalCode'] = intval($product['zip']);
		$data['requestedShipment']['shipper']['address']['countryCode'] = $product['country'];

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
		$data['requestedShipment']['requestedPackageLineItems'] = $item;

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
		$fedex_markup = DB::table('static_values')->where('name', 'FedEx Markup')->first();
		$fedex_markup_percent = $fedex_markup->value ?? 0;
		return round($rate* ((100 + ($fedex_markup_percent)) / 100),2);
	} 

	public function getSaiaShipRate(Request $request)
	{
		$lineitems = '';
		$rate = 0;
		$usertoken = request()->bearerToken();
		if (!empty($usertoken)) {
			$user = DB::table('users')->select('id')->where('api_token', $usertoken)->first();
			if (!empty($user)) {
				$carts = DB::table('carts')->where('user_id',$user->id)
				->leftJoin('products','carts.product_id','=','products.id')
				->leftJoin('dropships', 'products.dropship', '=', 'dropships.id')
				->select('products.id','products.dropship','products.weight','products.width','products.length','products.height','dropships.zip','dropships.state','dropships.country','dropships.city','carts.quantity')
				->get()->groupBy('zip');

				foreach($carts as $key => $product_datas) {
					if(count($product_datas) > 0){
						$state = "";
						$city = "";
						$zip = "";
						$country = "";
						$lineitems = '<Details>';
						$item = '';
						foreach($product_datas as $product_data) {
							empty($state) ? $state = $product_data->state: '';
							empty($city) ? $city = $product_data->city: '';
							empty($zip) ? $zip = $product_data->zip: '';
							empty($country) ? $country = $product_data->country: '';
							if(!empty($product_data) && !empty($product_data->quantity) && $product_data->quantity > 0) {
								for ($x = 1; $x <= intval($product_data->quantity); $x++) {
									$item .= '<DetailItem>
												<Width>' . $product_data->width . '</Width>
												<Length>' . $product_data->length . '</Length>
												<Height>' . $product_data->height . '</Height>
												<Weight>' . (int)$product_data->weight . '</Weight>
												<Class>50</Class>
											</DetailItem>';
								}
							}
						}
						$lineitems .= $item . '</Details>';
						if(count($product_datas) > 0 && !empty($state) && !empty($city) && !empty($zip) && !empty($country)) {
							$state_collection['city'] = $city;
							$state_collection['state'] = $state;
							$state_collection['country'] = $country;
							$state_collection['zip'] = $zip;
							$rate += $this->getSaiaRate($request,$lineitems,$state_collection);
						}
					}	
				}
			}
		} else {
			if($request['product_id'] != "" && count($request['product_id']) > 0) {
				$product_ids = array_column($request['product_id'],'id');
				$products = DB::table('products')->whereIn('products.id',$product_ids)->leftJoin('dropships', 'products.dropship', '=', 'dropships.id')->select('products.id','products.dropship','products.weight','products.width','products.length','products.height','dropships.zip','dropships.state','dropships.country','dropships.city')->get()->groupBy('zip');
				foreach($products as $key => $product_datas) {
					$state = "";
					$city = "";
					$zip = "";
					$country = "";
					$lineitems = '<Details>';
					$item = '';
					foreach($product_datas as $product_data) {
						empty($state) ? $state = $product_data->state: '';
						empty($city) ? $city = $product_data->city: '';
						empty($zip) ? $zip = $product_data->zip: '';
						empty($country) ? $country = $product_data->country: '';
						$data = collect($request['product_id'])->where('id',$product_data->id)->first();
						if(!empty($data) && !empty($data['qty']) && $data['qty'] > 0) {
							for ($x = 1; $x <= intval($data['qty']); $x++) {
								$item .= '<DetailItem>
												<Width>' . $product_data->width . '</Width>
												<Length>' . $product_data->length . '</Length>
												<Height>' . $product_data->height . '</Height>
												<Weight>' . (int)$product_data->weight . '</Weight>
												<Class>50</Class>
											</DetailItem>';
							}
						}
					}
					$lineitems .= $item . '</Details>';
					if(count($product_datas) > 0 && !empty($state) && !empty($city) && !empty($zip) && !empty($country)) {
						$state_collection['city'] = $city;
						$state_collection['state'] = $state;
						$state_collection['country'] = $country;
						$state_collection['zip'] = $zip;
						$rate += $this->getSaiaRate($request,$lineitems,$state_collection);
					}
				}
			}
		}

		return (float)$rate;
	}

	public function getSaiaRate($request,$lineitems,$state_collection)
	{
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
                        <OriginCity>'.$state_collection['city'].'</OriginCity>
                        <OriginState>'.$state_collection['state'].'</OriginState>
                        <OriginZipcode>'.$state_collection['zip'].'</OriginZipcode>
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
		$saia_markup = DB::table('static_values')->where('name', 'SAIA Markup')->first();
		$saia_markup_percent = $saia_markup->value ?? 0;
		return round((float)($rate * ((100 + ($saia_markup_percent)) / 100)),2);
	}
}
