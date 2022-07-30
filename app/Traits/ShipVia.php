<?php

namespace App\Traits;

use App\Models\Order;
use App\Models\OrderItems;
use Illuminate\Support\Facades\Http;

trait ShipVia
{
    public function ShipViaSaia($order_id)
    {
        $order = Order::where('id', $order_id)->with('items.product')->first();
        $items = OrderItems::where('order_id', $order->id)
            ->leftJoin('products', 'order_items.product_id', '=', 'products.id')
            ->leftJoin('dropships', 'products.dropship', '=', 'dropships.id')
            ->select('products.id', 'products.dropship', 'products.weight', 'products.hazardous', 'products.description', 'dropships.zip', 'dropships.state', 'dropships.country', 'dropships.city', 'dropships.name', 'order_items.quantity')
            ->get()->groupBy('zip');

        $allresponse = array();
        foreach ($items as $data) {
            $detailItems = '';
            $state = "";
            $city = "";
            $zip = "";
            $country = "";
            $name = "";
            foreach ($data as $item) {
                empty($state) ? $state = $item->state : '';
                empty($city) ? $city = $item->city : '';
                empty($zip) ? $zip = $item->zip : '';
                empty($country) ? $country = $item->country : '';
                empty($name) ? $name = $item->name : '';
                $hazardous = $item->hazardous == 1 ? 'Y' : 'N';
                $description = strip_tags($item->description);
                $detailItems .= '
                <DetailItem>
                    <DestinationZipcode>' . $order->shipping_zip . '</DestinationZipcode>
                    <DestinationCountry>' . $order->shipping_country . '</DestinationCountry>
                    <Class>50</Class>
                    <Package>BG</Package>
                    <Pieces>' . $item->quantity . '</Pieces>
                    <Weight>' . $item->weight . '</Weight>
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
                                <ShipmentDate>' . date('Y-m-d') . '</ShipmentDate>
                                <BillingTerms>Prepaid</BillingTerms>
                                <BLNumber></BLNumber>
                                <ShipperNumber></ShipperNumber>
                                <PONumber></PONumber>
                                <PrintRates>Y</PrintRates>
                                <Customs>N</Customs>
                                <VICS>N</VICS>
                                <WeightUnits></WeightUnits>
                                <Shipper>
                                    <ContactName>' . $name . '</ContactName>
                                    <Address1>' . $name . '</Address1>
                                    <City>' . $city . '</City>
                                    <State>' . $state . '</State>
                                    <Zipcode>' . $zip . '</Zipcode>
                                </Shipper>
                                <Consignee>
                                    <ContactName>' . $order->shipping_name . '</ContactName>
                                    <Address1>' . $order->shipping_address . '</Address1>
                                    <City>' . $order->shipping_city . '</City>
                                    <State>' . $order->shipping_state . '</State>
                                    <Zipcode>' . $order->shipping_zip . '</Zipcode>
                                </Consignee>
                                <BillTo>
                                    <AccountNumber>0747932</AccountNumber>
                                </BillTo>
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

                $order->shipment_response = $newdata;
                $order->update();

                if (isset($newdata['Body']['CreateResponse']['CreateResult']['ProNumber'])) {
                    array_push($allresponse, $newdata);
                    // return $newdata;
                } else {
                    $return = ['message' => 'Oops! Something went wrong', 'code' => 400];
                    array_push($allresponse, $return);
                    // return $return;
                }
            } catch (\Exception $e) {
                array_push($allresponse, $e->getMessage());
                // return $e->getMessage();
            }
        }

        $order->shipment_response = $allresponse;
        $order->update();

        return $allresponse;
    }

    public function ShipViaFedex($order_id)
    {
        $token = getFedexAuthToken();
        $content = json_decode($token);

        $order = Order::where('id', $order_id)->first();
        $order_items = OrderItems::where('order_id', $order_id)
            ->leftJoin('products', 'order_items.product_id', '=', 'products.id')
            ->leftJoin('dropships', 'products.dropship', '=', 'dropships.id')
            ->select('products.id', 'products.dropship', 'products.weight', 'products.hazardous', 'products.description', 'dropships.zip', 'dropships.state', 'dropships.country', 'dropships.city', 'dropships.name', 'order_items.quantity')
            ->get()->groupBy('zip');

        $allresponse = array();
        foreach ($order_items as $data) {
            $items = array();
            $state = "";
            $city = "";
            $zip = "";
            $country = "";
            $name = "";
            foreach ($data as $item) {
                empty($state) ? $state = $item->state : '';
                empty($city) ? $city = $item->city : '';
                empty($zip) ? $zip = $item->zip : '';
                empty($country) ? $country = $item->country : '';
                empty($name) ? $name = $item->name : '';
                $weight = collect(['value' => '10', 'units' => 'LB']);
                $itemWeight = collect(['weight' => $weight]);
                array_push($items, $itemWeight);
            }

            $location = array('state' => $state, 'city' => $city, 'zip' => $zip, 'country' => $country, 'name' => $name);

            $response = $this->Fedex($content, $order, $items, $location);
            $response = json_decode($response);
            array_push($allresponse, $response);
        }

        $order->shipment_response = $allresponse;
        $order->update();

        return $allresponse;
    }

    public function Fedex($content, $order, $items, $location)
    {
        $paymentType = $order->payment_via == 'COD' ? 'RECIPIENT' : 'SENDER';

        $data['labelResponseOptions'] = "URL_ONLY";

        //Shipper Info
        $data['requestedShipment']['shipper']['contact']['personName'] = $location['name'];
        $data['requestedShipment']['shipper']['contact']['phoneNumber'] = 1234567890;
        $data['requestedShipment']['shipper']['contact']['companyName'] = "Ecolink";
        $data['requestedShipment']['shipper']['address']['streetLines'] = ["SHIPPER STREET LINE 1"];
        $data['requestedShipment']['shipper']['address']['city'] = $location['city'];
        $data['requestedShipment']['shipper']['address']['stateOrProvinceCode'] = $location['state'];
        $data['requestedShipment']['shipper']['address']['postalCode'] = $location['zip'];
        $data['requestedShipment']['shipper']['address']['countryCode'] = $location['country'];

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

            $order->shipment_response = $response;
            $order->update();

            return $response;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
