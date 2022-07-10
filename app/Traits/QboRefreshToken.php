<?php

namespace App\Traits;

use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper;

trait QboRefreshToken
{
	public function accessToken($type)
    {
        if ($type == "online") {
            $file = file_get_contents('storage/qbo.json');
            $content = json_decode($file, true);
        } else {
            $file = file_get_contents('storage/qbopayment.json');
            $content = json_decode($file, true);
        }

        $oauth2LoginHelper = new OAuth2LoginHelper(config('qboconfig.client_id'), config('qboconfig.client_secret'));
        $accessTokenObj = $oauth2LoginHelper->refreshAccessTokenWithRefreshToken($content['refresh_token']);
        $accessTokenValue = $accessTokenObj->getAccessToken();
        $refreshTokenValue = $accessTokenObj->getRefreshToken();

        $data = collect(['access_token' => $accessTokenValue, 'refresh_token' => $refreshTokenValue]);

        return $data;
    }
}
