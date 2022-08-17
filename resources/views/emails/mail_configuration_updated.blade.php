@component('mail::message')
# Hello {{$adminUser->firstname}} {{$adminUser->lastname}},

This email is to test new mailing credentials which are updated by {{$employee->firstname}} {{$employee->lastname}}

Regards,<br>
Gleam HR

@endcomponent
