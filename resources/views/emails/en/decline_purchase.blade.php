@component('mail::message')
# Hello {{$name}}

Unfortunately, the seller isn't able to fulfill your order/request for the listing "{{$title}}". Your order has been refunded. Please contact the seller for any enquiries.

@component('mail::button', ['url' => $url])
Purchase History
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
