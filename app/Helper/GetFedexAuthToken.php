<?php

function getFedexAuthToken()
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://apis-sandbox.fedex.com/oauth/token',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => 'grant_type=client_credentials&client_id='.config('fedex.client_id').'&client_secret='.config('fedex.client_key').'',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/x-www-form-urlencoded',
            'Cookie: _abck=E19AF3FAD6527E1E990819F9CC8CB3CD~-1~YAAQHwTXF8FvlweAAQAA869+JgeB03Iuc9wLacxfX0+U9pyRT6L+sDSP9n2KXNSOH4zLSj4qDNYQtQ4Kcsh5VurQ5Z9BpoqT46XOkSb/Q5fnK77y11npqhaWxLaKuEJJftrXE4fFQgVHPDTrJo1XSyUP09SRvWA11j3rqNgQWGetr9x4FCr+FTmPFKI+FkRUd5fWMf612vRz7oQY5B1npIaotKDuarOiQ0iSwZcvydSuvJEj10lPWYpas0i1ZQUXYHpdi+txH75UWuJwIIB3NnZVWqjEZM3G9LPtHHf036eQs5wRwUNUi7Q95J2CfU3q8rqAvPKaJxxAKvvbiKyUPr22ypowQHTbAalqADWEwrV49VKz4ji13g==~-1~-1~-1'
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    return $response;
}
