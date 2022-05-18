<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Email</title>
</head>

<body style="margin:0;">
    <div style="font:normal 16px 'Poppins',sans-serif;width:600px;max-width:100%; margin:auto;background:#f1f1f1;line-height:1.3">
        <div style="border-bottom:1px solid #9e9e9e; margin:0 30px;text-align:center;padding:15px 15px 12px">
            <img src="{{ asset('img/New_Ecolink_Logo-33.png') }}" alt="Ecolink">
        </div>
        <div style="padding:0 15px;">
            <div style="padding:35px 20px;text-align:center">
                <p style="font-size:24px;font-weight:normal;margin:0;text-align:left;">Dear <?php echo $user->name ?> </p>
                <p style="font-size:18px;font-weight:normal;margin:0;text-align:left; padding-left:0px;">Your are receiving this email because we received a password reset request for your account. </p>
            </div>
            <div style="text-align:center;margin-top:10px;">
                @component('mail::button', ['url' => $user->url])
                Change Password
                @endcomponent
            </div>
        </div>
        <div style="padding:0 15px;">
            <div style="max-width:532px;margin:5px auto 0;text-align:center;background:#fff;padding:0 10px 10px">
                <p style="background:#f4f4f4;width:150px;height:20px;margin:0 auto"></p>
            </div>
        </div>
    </div>
</body>

</html>