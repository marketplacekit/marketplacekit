@component('mail::message')
# Hello Admin,

A new listing has been posted: {{$title}}

@component('mail::button', ['url' => $url])
View listing
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
