@php
$configData = Helper::applClasses();
@endphp
@extends('layouts/fullLayoutMaster')

@section('title', 'Reset Password')

@section('page-style')
  {{-- Page Css files --}}
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('css/base/pages/page-auth.css')) }}">
@endsection

@section('content')
<div class="auth-wrapper auth-v2">
    <div class="auth-inner row m-0">
        <!-- Brand logo-->
        <a class="brand-logo" href="javascript:void(0);">
        <img class="dashboard-logo" src="{{asset('images/ico/glowlogixpng.png')}}" />
        <h2 class="brand-text ml-1">GleamHR</h2>
        </a>
        <!-- /Brand logo-->
        <!-- Left Text-->
        <div class="d-none d-lg-flex col-lg-8 align-items-center p-5">
            <div class="w-100 d-lg-flex align-items-center justify-content-center px-5">
              <img class="img-fluid" src="{{asset('images/pages/login-v2.svg')}}" alt="Login V2" />
            </div>
        </div>
        <!-- /Left Text-->
        <!-- Login-->
        <div class="d-flex col-lg-4 align-items-center auth-bg px-2 p-lg-5">
            <div class="col-12 col-sm-8 col-md-6 col-lg-12 px-xl-2 mx-auto">
                <h4 class="card-title mb-1">{{__('language.Welcome to Glowlogix HRM!')}}</h4>
                <p class="card-text mb-2">{{__('language.The ultimate HRM System.')}}</p>
                <h3>{{__('language.Reset Password')}}</h3>

                @if(Session::has('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <div class="alert-body">
                            <strong>Success!</strong> {{Session::get('success')}}
                        </div>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                          </button>
                    </div>
                @endif
                @if(Session::has('error'))
                    <div class="demo-spacing-0">
                      <div class="alert alert-danger alert-dismissible fade mb-1 show">
                            <div class="alert-body">
                                <strong>Error!</strong> {{Session::get('error')}}
                            </div>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                @endif
                
                <form class="mt-2 auth-reset-password-form" method="post" action="{{ route('reset.employee.password') }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="token" value="{{ $token }}">
                    <div class="form-group {{ $errors->has('official_email') ? ' has-error' : '' }} ">
                        <div class="col-xs-12">
                            <input id="email" class="form-control" type="email" name="official_email" placeholder="E-Mail Address" value="{{$email}}" autofocus>
                            @if ($errors->has('official_email'))
                                <span class="text-danger">
                                    <strong>{{ $errors->first('official_email') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                        <div class="col-xs-12">
                            <input class="form-control" type="password" id="passowrd" name="password" placeholder="Password">
                            @if ($errors->has('password'))
                                <span class="text-danger">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                        <div class="col-xs-12">
                            <input class="form-control" type="password" name="password_confirmation" placeholder="Password">
                            @if ($errors->has('password_confirmation'))
                                <span class="text-danger">
                                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group text-center m-t-20">
                        <div class="col-xs-12">
                            <button class="btn btn-primary btn-block waves-effect waves-light" type="submit">Reset</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('vendor-script')
<script src="{{asset(mix('vendors/js/forms/validation/jquery.validate.min.js'))}}"></script>
@endsection

@section('page-script')
<script src="{{asset(mix('js/scripts/pages/page-auth-reset-password.js'))}}"></script>
@endsection