@component('mail::message')
Hello,

You have recieved a request for more information regarding listing #{{ $listing->id }} - {{ $listing->title }}.

Here's what {{$fullname}} had to say:
{{$comment}}

Email: {{$email}}
Phone number: {{$phone}}

@component('mail::button', ['url' => $url])
Reply to Message
@endcomponent

regards,<br>
{{ config('app.name') }}
@endcomponent
