@php
$configData = Helper::applClasses();
@endphp
@extends('layouts/fullLayoutMaster')

@section('title', 'Forget Password')

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
                <h3>{{__('language.Recover Your Password')}}</h3>

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

                <form class="auth-forgot-password-form mt-2" method="post" action="{{ route('password.reset.email') }}" novalidate="novalidate">
                    {{csrf_field()}}
                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <label class="form-label" for="login-email">Email <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" placeholder="{{__('language.Email')}}" name="official_email"
                        autocomplete="off" value="{{ old('official_email') }}"  tabindex="1"/>
                        @if ($errors->has('official_email'))
                        <span class="help-block">
                        <strong style="color: red;">{{ $errors->first('official_email') }}</strong>
                        </span>
                        @endif
                    </div>
                    <button class="btn btn-primary btn-block" tabindex="4">{{__('language.Recover Password')}}</button>
                </form><br>
                <div class="text-center">
                    <a href="{{ route('login') }}">{{__('language.Click here to go back to login page')}}</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('vendor-script')
<script src="{{asset(mix('vendors/js/forms/validation/jquery.validate.min.js'))}}"></script>
@endsection

@section('page-script')
<script src="{{asset(mix('js/scripts/pages/page-auth-forgot-password.js'))}}"></script>
@endsection