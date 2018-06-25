@component('mail::message')
# Hello {{ $name }}

We're please to inform you that your listing has been verified and is now available on the website.

@component('mail::button', ['url' => $url])
View listing
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
