<?php

namespace App\Traits;

use App\Models\Product;
use Illuminate\Support\Facades\Http;
use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper;

trait SosItemUpdate
{
    public function itemUpdate()
    {
        $file = file_get_contents(public_path('storage/sos.json'));
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
            file_put_contents(public_path('storage/sos.json'), $token);
            return $this->sosItemUpdate();
        }
    }

    public function sosRefreshToken()
    {
        $file = file_get_contents(public_path('storage/sos.json'));
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
