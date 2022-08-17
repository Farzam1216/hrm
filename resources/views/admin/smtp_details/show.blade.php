@extends('layouts/contentLayoutMaster')
@section('title','Show SMTP Detail')
@section('heading')
@section('vendor-style')
<!-- vendor css files -->
<link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/pickadate/pickadate.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
@endsection

@section('page-style')
<link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-pickadate.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
@endsection

@stop
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header border-bottom pt-1 pb-1">
                <div class="dt-action-buttons text-right">
                    <div class="dt-buttons flex-wrap d-inline-flex">
                        <button type="button" class="btn btn-primary mr-1" onclick="window.location.href='@if(isset($locale)){{route('smtp-details.index', [$locale])}} @else {{route('smtp-details.index', ['en'])}} @endif'">
                            <i data-feather="chevron-left"></i> {{__('language.Back')}}
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body pt-1">
                <div class="col-12">
                    <div class="row pt-1">
                        <div class="col-md-6 row">
                            <h6>{{__('language.Name')}}:</h6>&nbsp;<h6 class="font-weight-normal">
                                @if($smtp_detail->name)
                                    {{$smtp_detail->name}}
                                @else
                                    N/A
                                @endif
                            </h6>
                        </div>
                        <div class="col-md-6 row">
                            <h6>{{__('language.Mail Address')}}:</h6>&nbsp;<h6 class="font-weight-normal">
                                @if($smtp_detail->mail_address)
                                    {{$smtp_detail->mail_address}}
                                @else
                                    N/A
                                @endif
                            </h6>
                        </div>
                    </div>
                    <div class="row pt-1">
                        <div class="col-md-6 row">
                            <h6>{{__('language.Mail Driver')}}:</h6>&nbsp;<h6 class="font-weight-normal">{{$smtp_detail->driver}}</h6>
                        </div>
                        <div class="col-md-6 row">
                            <h6>{{__('language.Mail Host')}}:</h6>&nbsp;<h6 class="font-weight-normal">{{$smtp_detail->host}}</h6>
                        </div>
                    </div>
                    <div class="row pt-1">
                        <div class="col-md-6 row">
                            <h6>{{__('language.Mail Port')}}:</h6>&nbsp;<h6 class="font-weight-normal">{{$smtp_detail->port}}</h6>
                        </div>
                        <div class="col-md-6 row">
                            <h6>{{__('language.Mail Username')}}:</h6>&nbsp;<h6 class="font-weight-normal">{{$smtp_detail->username}}</h6>
                        </div>
                    </div>
                    <div class="row pt-1">
                        <div class="col-md-6 row">
                            <h6>{{__('language.Status')}}:</h6>&nbsp;<h6 class="font-weight-normal">{{$smtp_detail->status}}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@section('vendor-script')
<!-- vendor files -->
<script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.date.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/pickers/pickadate/legacy.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.date.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.time.js')) }}"></script>
@endsection
@section('page-script')
{{-- Page js files --}}
<script src="{{ asset(mix('js/scripts/forms/pickers/form-pickers.js')) }}"></script>
@endsection
@stop