<?php

namespace App\Traits;

use App\Models\OrderItems;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Traits\QboRefreshToken;

trait QuickBooksOnline
{
    use QboRefreshToken;

    public function quickBookInvoice($user_id, $order_id)
    {
        $items = OrderItems::where('order_id', $order_id)->with('product')->get();
        $user = DB::table('users')->find($user_id);
        $lineItems = array();
        foreach ($items as $item) {
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
            return $e->getMessage();
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
            return $e->getMessage();
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
                return true;
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}