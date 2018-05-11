@component('mail::message')
# Hello {{ $name }}

You've received a new direct message from {{ $sender }}.

@component('mail::button', ['url' => $url])
View inbox
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
