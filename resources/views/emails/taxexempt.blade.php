<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Email</title>
</head>

<body style="margin:0;">
    <div style="font:normal 16px 'Poppins',sans-serif;width:600px;max-width:100%; margin:auto;background:#f1f1f1;line-height:1.3">
        <div style="border-bottom:1px solid #9e9e9e; margin:0 30px;text-align:center;padding:15px 15px 12px">
            <img src="{{ asset('ECOLINK_EMAIL_LOGO.png') }}" alt="Ecolink">
        </div>
        <div style="padding:0 15px;">
            <div style="padding:35px 20px;text-align:center">
                <p style="font-size:24px;font-weight:normal;margin:0;text-align:left;">Hello <?php echo $user['name'] ?> </p>
                <p style="font-size:18px;font-weight:normal;margin:0;text-align:left; padding-left:0px;">Your request for tax exemption is approved. You can now shop on Ecolink. </p>
                <p style="font-size:18px;font-weight:normal;margin:0;text-align:left; padding-left:0px;">Thanks, </p>
            </div>
        </div>
        <div style="padding:0 15px;">
            <div style="max-width:532px;margin:5px auto 0;text-align:center;background:#fff;padding:0 10px 10px">
                <p style="background:#f4f4f4;width:150px;height:20px;margin:0 auto"></p>
                <p style="font-size:16px;margin:-8px 0 14px;text-align:center;"><span style="border-bottom:1px solid #e51a4b">Visit Us</span></p>
                <div>
                    <a href="#" target="_blank" style="margin:0 3px"><img src="{{ asset('storage/images/fb.png') }}" alt="Facebook"></a>
                    <a href="#" target="_blank" style="margin:0 3px"><img src="{{ asset('storage/images/twitter.png') }}" alt="Twitter"></a>
                    <a href="#" target="_blank" style="margin:0 3px"><img src="{{ asset('storage/images/insta.png') }}" alt="Instagram"></a>
                    <a href="#" target="_blank" style="margin:0 3px"><img src="{{ asset('storage/images/pinterest.png') }}" alt="Pinterest"></a>
                </div>
                <div>
                    <p style="text-align:center;"> For any Query/suggestion email on <a href="mailto:info@ecolink.com">info@ecolink.com</a></p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>