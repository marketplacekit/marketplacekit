@component('mail::message')
# Hello {{$name}}

You have a new order/request. Please review the details by clicking on the link below.

@component('mail::button', ['url' => $url])
View Order
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
