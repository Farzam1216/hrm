@component('mail::message')
# {{$subject}}

@component('mail::panel')
{{$message}}
@endcomponent


Thanks,<br>
{{$firstname}} {{$lastname}}
@endcomponent
