<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>

<body>
    <b>{{__('language.Hi')}} {{$name}}</b>,
    <br>
    <p>{{__('language.We are using Zoho Mail, an email solution for our business.')}} {{__('language.Please login using the given email address and Password via')}} https://mail.zoho.com/</p>


    {{__('language.Email')}}:{{$org_email}}
    <br>
    <br> {{__('language.Password')}}:{{$password}}
    <br>
    <br> {{__('language.Best Luck')}},
</body>

</html>