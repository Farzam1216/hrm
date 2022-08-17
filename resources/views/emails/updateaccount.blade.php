<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>

<body>
<b>{{__('language.Hi')}} {{$employee->firstname}} {{$employee->lastname}}</b>,
<br>

<p>{{__('language.Your account has been Updated')}}
</p>
{{__('language.Email')}}:{{$employee->official_email}}
<br>
@if($password!='')
    <br>{{__('language.Your New Password')}}: {{$password}}
@endif
<br>
<br>
{{__('language.Note')}}: {{__('language.If is there any problem then contact')}} hr@glowlogix.com.
<br>
</body>
</html>