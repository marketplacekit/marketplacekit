@component('mail::message')
# Hello {{$name}}

Your order/request for the listing "{{$title}}" been accepted and will be processed shortly. Please contact the seller for any enquiries.

@component('mail::button', ['url' => $url])
Purchase History
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
