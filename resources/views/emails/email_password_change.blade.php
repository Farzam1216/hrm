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

    <p>{{__('language.Your account has been created on')}} 
        <a href="{{URL::to('/')}}">{{__('language.HR')}} {{__('language.portal')}}</a>
        {{__('language.Please login using the given email address and Password')}}
    </p>
        {{__('language.Email')}}:{{$employee->official_email}}
    <br>
    <br> {{__('language.Password')}}: 123456
    <br>
    {{__('language.Note')}}: {{__('language.As soon as you log in change your password')}}.
    <br> {{__('language.Best Luck')}},
</body>

</html>