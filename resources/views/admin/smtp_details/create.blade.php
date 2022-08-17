@extends('layouts/contentLayoutMaster')
@section('title','Create SMTP Detail')

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
                <form id="smtp-details-form" action="@if(isset($locale)){{route('smtp-details.store', [$locale])}} @else {{route('smtp-details.store', ['en'])}} @endif" method="post">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">{{__('language.Name')}}</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Enter mail name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">{{__('language.Mail Address')}}</label><span class="text-danger"> *</span>
                                <input type="email" class="form-control" id="mail_address" name="mail_address" value="{{ old('mail_address') }}" placeholder="Enter mail address">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">{{__('language.Mail Driver')}}</label><span class="text-danger"> *</span>
                                <input type="text" class="form-control" id="driver" name="driver" value="{{ old('driver') }}" placeholder="Enter mail driver">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">{{__('language.Mail Host')}}</label><span class="text-danger"> *</span>
                                <input type="text" class="form-control" id="host" name="host" value="{{ old('host') }}" placeholder="Enter mail host">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">{{__('language.Mail Port')}}</label><span class="text-danger"> *</span>
                                <input type="text" class="form-control" id="port" name="port" value="{{ old('port') }}" placeholder="Enter mail port">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">{{__('language.Mail Username')}}</label><span class="text-danger"> *</span>
                                <input type="text" class="form-control" id="username" name="username" value="{{ old('username') }}" placeholder="Enter mail username">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">{{__('language.Mail Password')}}</label><span class="text-danger"> *</span>
                                <input type="password" class="form-control" id="password" name="password" value="{{ old('password') }}" placeholder="Enter mail password">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">{{__('language.Status')}}</label><span class="text-danger"> *</span>
                                <select class="form-control" type="text" id="status" name="status" value="{{ old('status') }}" autofocus>
                                    <option value="inactive" @if(old('status') == "inactive") selected @endif>Inactive</option>
                                    <option value="active" @if(old('status') == "active") selected @endif>Active</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div>
                        <button type="submit" class="btn btn-primary mr-1">
                            <span class=" d-lg-none d-md-none d-sm-none"><i data-feather="check-circle"></i></span>
                            <span class="d-none d-lg-inline d-md-inline d-sm-inline">{{__('language.Create')}}</span>
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