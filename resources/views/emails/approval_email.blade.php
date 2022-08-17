@component('mail::message')
{{-- Greeting --}}
@if (isset($data['greetings']))
# {{ $data['greetings'] }}
@else
@if ($level === 'error')
# @lang('Whoops!')
@else
# @lang('Hello!')
@endif
@endif

{{-- Body --}}
@isset($data['body'])
    {{$data['body'] }}
@endisset


{{-- body Information --}}
<hr>
{{$data['body_information'] }}

{{-- Salutation --}}
@if (! empty($salutation))
{{ $salutation }}
@else
@lang('Regards'),<br>
{{ config('app.name') }}
@endif

{{-- Subcopy --}}
@isset($actionText)
@slot('subcopy')
@lang(
    "If you’re having trouble clicking the \":actionText\" button, copy and paste the URL below\n".
    'into your web browser: [:actionURL](:actionURL)',
    [
        'actionText' => $actionText,
        'actionURL' => $actionUrl,
    ]
)
@endslot
@endisset
{{-- Action Button --}}
@isset($data['InboxURL'])
<?php
    switch ($level) {
        case 'success':
        case 'error':
            $color = $level;
            break;
        default:
            $color = 'primary';
    }
?>
@component('mail::button', ['url' => $data['InboxURL'], 'color' => $color])
{{ 'Goto Mailbox' }}
@endcomponent
@endisset
@endcomponent
