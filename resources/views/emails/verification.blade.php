@component('mail::message')
# Hello {{ $user->name }},

Welcome onboard with Ecolink. We look forward to a long association with you.

Please click on the link below to verify your account with us.

@component('mail::button', ['url' => $user->url])
Verify Account
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
