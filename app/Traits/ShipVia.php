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

        $detailItems = '';
        foreach ($order->items as $item) {
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
                                <ContactName></ContactName>
                                <Address1></Address1>
                                <City></City>
                                <State></State>
                                <Zipcode></Zipcode>
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

            $order->shipment_response = $newdata;
            $order->update();

            if (isset($newdata['Body']['CreateResponse']['CreateResult']['ProNumber'])) {
                return $newdata;
            } else {
                $return = ['message' => 'Oops! Something went wrong', 'code' => 400];
                return $return;
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function ShipViaFedex($order_id)
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

            $order->shipment_response = $response;
            $order->update();

            return $response;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}