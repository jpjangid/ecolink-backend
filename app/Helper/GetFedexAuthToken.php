<?php

use Illuminate\Support\Facades\Http;

function getFedexAuthToken()
{
    try {
        $response = Http::asForm()->withHeaders([
            'Content-Type' => 'application/x-www-form-urlencoded'
        ])->post(config('fedex.url') . 'oauth/token', [
            'grant_type'        =>  'client_credentials',
            'client_id'         =>  config('fedex.client_id'),
            'client_secret'     =>  config('fedex.client_key'),
        ]);

        return $response;
    } catch (\Exception $e) {
        return  $e->getMessage();
    }
}
