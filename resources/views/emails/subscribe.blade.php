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
        <!-- <div style="padding:35px 20px;text-align:center">

            <img src="{{ asset('storage/images/thank.png') }}" alt="Ecolink">
            <h2 style="font-size:49px;font-weight:normal;margin:0;text-align:center;">Thank you</h2>
            <p style="font-size:26px;font-weight:normal;margin:0;text-align:center;">For Registration !!</p>
        </div> -->
        <div style="padding:0 15px;">
            <div style="padding:35px 20px;text-align:center">
                <p style="font-size:24px;font-weight:normal;margin:0;text-align:left;"><?php echo $email; ?></p>
                <p style="font-size:18px;font-weight:normal;margin:0;text-align:left; padding-left:0px;">Thank you for subscribing us.
                    We're thrilled to welcome you onboard with the exciting news, trends, stories and much more. </p>
                <!-- <p style="font-size:22px;font-weight:normal;margin:0;text-align:left; padding-left:0px;">You're almost ready to get started. Please click on the button below to verify your e-mail address and enjoy the exclusive services of BuildMan.</p> -->
            </div>
            <div style="text-align:center;margin-top:10px;">
                <!--<a href="{{ url('verify/user') }}" style="background:#f78e20;color:#fff;font-size: 25px;font-weight: 600;text-decoration: none;padding: 7px 30px;border-radius: 3px;display: inline-block;margin:auto">Reset Password</a>-->
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