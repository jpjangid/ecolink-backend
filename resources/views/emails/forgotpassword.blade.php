@component('mail::message')
# Dear {{ $user->name }},

You can reset your Ecolink account password using the link


@component('mail::button', ['url' => $user->url])
Change Password
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
