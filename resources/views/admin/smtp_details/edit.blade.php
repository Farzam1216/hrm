@extends('layouts/contentLayoutMaster')
@section('title','Edit SMTP Detail')

@section('vendor-style')
    {{-- Vendor Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/pickadate/pickadate.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
@endsection

@section('page-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-pickadate.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
@endsection

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
            <div class="card-body pt-2">
                <form id="smtp-details-form" action="@if(isset($locale)){{route('smtp-details.update', [$locale, $smtp_detail->id])}} @else {{route('smtp-details.update', ['en', $smtp_detail->id])}} @endif" method="post">
                    <input type="hidden" name="_method" value="PUT">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">{{__('language.Name')}}</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $smtp_detail->name) }}" placeholder="Enter mail name" value="{{$smtp_detail->name}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">{{__('language.Mail Address')}}</label><span class="text-danger"> *</span>
                                <input type="email" class="form-control" id="mail_address" name="mail_address" value="{{ old('mail_address', $smtp_detail->mail_address) }}" placeholder="Enter mail address" value="{{$smtp_detail->mail_address}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">{{__('language.Mail Driver')}}</label><span class="text-danger"> *</span>
                                <input type="text" class="form-control" id="driver" name="driver" value="{{ old('driver', $smtp_detail->driver) }}" placeholder="Enter mail driver" value="{{$smtp_detail->driver}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">{{__('language.Mail Host')}}</label><span class="text-danger"> *</span>
                                <input type="text" class="form-control" id="host" name="host" value="{{ old('host', $smtp_detail->host) }}" placeholder="Enter mail host" value="{{$smtp_detail->host}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">{{__('language.Mail Port')}}</label><span class="text-danger"> *</span>
                                <input type="text" class="form-control" id="port" name="port" value="{{ old('port', $smtp_detail->port) }}" placeholder="Enter mail port" value="{{$smtp_detail->port}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">{{__('language.Mail Username')}}</label><span class="text-danger"> *</span>
                                <input type="text" class="form-control" id="username" name="username" value="{{ old('username', $smtp_detail->username) }}" placeholder="Enter mail username" value="{{$smtp_detail->username}}">
                            </div>
                        </div>

                        @php
                            try {
                                $decryptedPassword = \Crypt::decryptString($smtp_detail->password);
                            } catch (Illuminate\Contracts\Encryption\DecryptException $e) {
                                $decryptedPassword = $smtp_detail->password;
                            }
                        @endphp

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">{{__('language.Mail Password')}}</label><span class="text-danger"> *</span>
                                <input type="password" class="form-control" id="password" name="password" value="{{ old('password', $decryptedPassword) }}" placeholder="Enter mail password">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">{{__('language.Status')}}</label><span class="text-danger"> *</span>
                                <select class="form-control" type="text" id="status" name="status" value="{{ old('status') }}" autofocus>
                                    <option value="inactive" @if(old('status', $smtp_detail->status) == "inactive") selected @endif>Inactive</option>
                                    <option value="active" @if(old('status', $smtp_detail->status) == "active") selected @endif>Active</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div>
                        <button type="submit" class="btn btn-primary mr-1">
                            <span class=" d-lg-none d-md-none d-sm-none"><i data-feather="check-circle"></i></span>
                            <span class="d-none d-lg-inline d-md-inline d-sm-inline">{{__('language.Update')}}</span>
                        </button>
                        <button type="reset" onclick="window.location.href='@if(isset($locale)){{route('smtp-details.index', [$locale])}} @else {{route('smtp-details.index', ['en'])}} @endif'" class="btn btn-outline-warning waves-effect">
                            <span class="d-lg-none d-md-none d-sm-none"><i data-feather="x-circle"></i></span>
                            <span class="d-none d-lg-inline d-md-inline d-sm-inline">{{__('language.Cancel')}}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@section('vendor-script')
    {{-- Vendor js files --}}
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
    <script src="{{ asset(mix('js/scripts/forms/validations/form-smtp-details-validation.js'))}}"></script>
    <script src="{{ asset(mix('js/scripts/forms/pickers/form-pickers.js')) }}"></script>
@endsection
@stop