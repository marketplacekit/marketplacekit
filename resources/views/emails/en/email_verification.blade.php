@component('mail::message')

You're well on your way to setting up your {{ config('app.name') }} account. We just need to verify your email. Click the button below to let us know this is really you.

@component('mail::button', ['url' => route('email-verification.check', $user->verification_token) . '?email=' . urlencode($user->email) ])
Click here to verify your account
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
