@component('mail::message')
# Hello!


You are receiving this email because we received a password reset request for your account.

@component('mail::button', ['url' => Request::url().'/'.$token.'?email='.$employee->email])
Reset Password
@endcomponent

This password reset link will expire in 60 minutes.

If you did not request a password reset, no further action is required.

Regards,<br>
{{ config('app.name') }}

@component('mail::subcopy')
If you’re having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser: 
<span class="break-all" style=" position: relative; word-break: break-all;">
	[{{Request::url().'/'.$token.'?email='.$employee->email}}]({{Request::url().'/'.$token.'?email='.$employee->email}})
</span>
@endcomponent

@endcomponent
