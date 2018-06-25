@component('mail::message')
# Hello Admin,

{{$name}} has sent you the following message:

"{{$comment}}"

Thanks,<br>
{{ config('app.name') }}
@endcomponent
