@component('mail::message')
# Hello! {{$user->firstname}} {{$user->lastname}}

{{$employeeData['description']}}

Regards,<br>
{{ config('app.name') }}

@component('mail::subcopy')
Click here to view time off request: 
<span class="break-all" style=" position: relative; word-break: break-all;">
	[{{Request::url().'/en/employee//' . $employeeData['employeeInfo']->id . '/timeoff'}}]({{Request::url().'/en/employee/' . $employeeData['employeeInfo']->id . '/timeoff'}})
</span>
@endcomponent

@endcomponent
