@extends('layouts/contentLayoutMaster')
@section('title','Edit Compensation')
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
            <div class="card-body">
                <form id="compensation-change-reason-form" action="@if(isset($locale)){{route('compensation-change-reasons.update', [$locale, $changeReason->id])}} @else {{route('compensation-change-reasons.update', ['en', $changeReason->id])}} @endif" method="post">
                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" class="form-control" id="id" name="id" value="{{ $changeReason->id }}">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">{{__('language.Name')}}</label><span class="text-danger"> *</span>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $changeReason->name) }}" placeholder="Enter change reason name">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 mt-2">
                            <label class="control-label h6">How should this change occur?</label>
                        </div>
                        <div class="col-12 ml-2">
                            <div class="form-group">
                                <div class="form-check form-check-inline col-12">
                                    <input class="form-check-input" type="radio" name="change_occur" id="future" value="future" @if(old('change_occur', $changeReason->change_occur) == 'future') checked="" @endif>
                                    <label class="form-check-label h6 font-weight-normal" for="future">Change for future but leave history alone.</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="change_occur" id="anywhere" value="anywhere" @if(old('change_occur', $changeReason->change_occur) == 'anywhere') checked="" @endif>
                                    <label class="form-check-label h6 font-weight-normal" for="anywhere">Change anywhere it is used, including history.</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div>
                        <button type="submit" class="btn btn-primary mr-1">
                            <span class=" d-lg-none d-md-none d-sm-none"><i data-feather="check-circle"></i></span>
                            <span class="d-none d-lg-inline d-md-inline d-sm-inline">{{__('language.Update')}} {{__('language.Change Reason')}}</span>
                        </button>
                        <button type="reset" onclick="window.location.href='@if(isset($locale)){{route('compensation-change-reasons.index', [$locale])}} @else {{route('compensation-change-reasons.index', ['en'])}} @endif'" class="btn btn-outline-warning waves-effect">
                            <span class=" d-lg-none d-md-none d-sm-none"><i data-feather="x-circle"></i></span>
                            <span class="d-none d-lg-inline d-md-inline d-sm-inline">{{__('language.Cancel')}}</span>
                        </button>
                    </div>
                </form>
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
<script src="{{ asset(mix('js/scripts/forms/validations/form-compensation-change-reason.js'))}}"></script>
<script src="{{ asset(mix('js/scripts/forms/pickers/form-pickers.js')) }}"></script>
@endsection
@stop