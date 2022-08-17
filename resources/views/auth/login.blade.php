@php
$configData = Helper::applClasses();
@endphp
@extends('layouts/fullLayoutMaster')

@section('title', 'Login Page')

@section('page-style')
{{-- Page Css files --}}
<link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('css/base/pages/page-auth.css')) }}">
@endsection

@section('content')
<style>
  .stealthy {
    left: 0;
    margin: 0;
    max-height: 1px;
    max-width: 1px;
    opacity: 0;
    outline: none;
    overflow: hidden;
    pointer-events: none;
    position: absolute;
    top: 0;
    z-index: -1;
  }
</style>
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
        @if(Auth::check())
        <div class="kt-login__head mt-5">
          <span class="kt-login__signup-label">{{__('language.Your Are Loged In')}} !</span>&nbsp;&nbsp;<a class="kt-link kt-login__signup-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{__('language.Click To SignOut')}}</a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
          </form>           
        </div>
        @else
        <h4 class="card-title mb-1">{{__('language.Welcome to Glowlogix HRM!')}}</h4>
        <p class="card-text mb-2">{{__('language.The ultimate HRM System.')}}</p>
        <h3>{{__('language.Sign In')}}</h3>
        @endif
        
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

        @if(!Auth::check())
        <form class="auth-login-form mt-2" autocomplete="nope" method="post" action="{{ route('login') }}" novalidate="novalidate">
          {{csrf_field()}}
          <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
            <label class="form-label" for="login-email">Email</label>
            <input class="form-control" type="text" placeholder="{{__('language.Email')}}" name="official_email"
            autocomplete="off" value="{{ old('official_email') }}"  tabindex="1"/>
            @if ($errors->has('official_email'))
            <span class="help-block">
             <strong style="color: red;">{{ $errors->first('official_email') }}</strong>
           </span>
           @endif
         </div>

         <!-- To stop password field from autofilling when selecting saved credentials from email field-->
         <input type="password" style="position: absolute; z-index: -1;"><input type="text" style="position: absolute; z-index: -1;">

         <div class="form-group">
          <div class="d-flex justify-content-between">
            <label for="login-password">Password</label>
            <a href="{{ route('password.request') }}">
              <small>{{__('language.Forgot Password ?')}}</small>
            </a>
          </div>
          <div class="input-group input-group-merge form-password-toggle {{ $errors->has('password') ? ' has-error' : '' }}">
            <input autocomplete="off" class="form-control js-text-to-password-onedit" type="text" placeholder="{{__('language.Password')}}" name="password" id="password" placeholder="············" tabindex="2" />
            <div class="input-group-append">
              <span class="input-group-text cursor-pointer">
                <i data-feather="eye"></i>
              </span>
            </div>
          </div>
          @if ($errors->has('password'))
          <span class="help-block">
            <strong style="color: red;">{{ $errors->first('password') }}</strong>
          </span>
          @endif
        </div>
        <div class="form-group">
          <div div class="custom-control custom-checkbox">
            <input class="custom-control-input" id="remember-me" type="checkbox" tabindex="3" />
            <label class="custom-control-label" for="remember-me">Remember Me</label>
          </div>
        </div>
        <button class="btn btn-primary btn-block" tabindex="4">{{__('language.Sign In')}}</button>
      </form>
      @else
      <div class="auth-bg">
        <h2><img class="img-fluid pr-5" src="{{url('asset/media/logos/logo-1.png')}}"></h2>
        <h3><a class="d-flex align-items-center pl-5" href=" @if(isset($locale)){{url($locale.'/dashboard')}}@else{{url('en/dashboard')}}@endif">{{__('language.Dashboard')}}</a></h3>
        <h3><a class="d-flex align-items-center pl-5" href="/applicant/apply">{{__('language.Apply For Job')}}</a></h3>
      </div>
      @endif
          <!-- <p class="text-center mt-2">
            <span>New on our platform?</span>
            <a href="{{url('auth/register-v2')}}"><span>&nbsp;Create an account</span></a>
          </p> -->
          <!-- <div class="divider my-2">
            <div class="divider-text">or</div>
          </div>
          <div class="auth-footer-btn d-flex justify-content-center">
            <a class="btn btn-facebook" href="javascript:void(0)">
              <i data-feather="facebook"></i>
            </a>
            <a class="btn btn-twitter white" href="javascript:void(0)">
              <i data-feather="twitter"></i>
            </a>
            <a class="btn btn-google" href="javascript:void(0)">
              <i data-feather="mail"></i>
            </a>
            <a class="btn btn-github" href="javascript:void(0)">
              <i data-feather="github"></i>
            </a>
          </div> -->
        </div>
      </div>
      <!-- /Login-->
    </div>
  </div>
  <script>
    $('.js-text-to-password-onedit').focus(function(){
      el = $(this);
      el.keydown(function(e){
        if(el.prop('type')=='text'){
          el.prop('type', 'password');
        }
      });
    });
  </script>
  @endsection

  @section('vendor-script')
  <script src="{{asset(mix('vendors/js/forms/validation/jquery.validate.min.js'))}}"></script>
  @endsection

  @section('page-script')
  <script src="{{asset(mix('js/scripts/pages/page-auth-login.js'))}}"></script>
  @endsection
