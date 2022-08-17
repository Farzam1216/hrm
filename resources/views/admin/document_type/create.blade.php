@extends('layouts/contentLayoutMaster')
@section('title','Add Document Type')
@section('vendor-style')
  {{-- Vendor Css files --}}
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
@endsection

@section('page-style')
  {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}">
@endsection
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <form class="form" id="doc-type-create-form" action="@if(isset($locale)){{url($locale.'/doc-types')}} @else {{url('en/doc-types')}} @endif"
            method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <br>
                <div class="row">
                    <div class="col-md-6 form-group pl-3">
                        <label class="control-label" for="doc_type_name">{{__('language.Name')}}</label><span class="text-danger"> *</span>
                        <input type="text" name="doc_type_name" id="doc_type_name" placeholder="{{__('language.Enter Name Here')}}"
                               class="form-control">
                    </div>
                    <div class="col-md-6 form-group pr-3">
                        <label class="control-label">{{__('language.Status')}}</label>
                        <select name="status" class="form-control">
                            <option value="1">{{__('language.Active')}}</option>
                            <option value="0">{{__('language.InActive')}}</option>
                        </select>
                    </div>
                </div>
                <div class="col-12 d-flex flex-sm-row flex-column mt-2 mb-2">
                    <button  type="submit" class="btn btn-primary mb-1 mb-sm-0 ml-1 mr-0 mr-sm-1 waves-effect waves-float waves-light">{{__('language.Add')}} {{__('language.Document')}}  {{__('language.Type')}}</button>
                    <button type="button" onclick="window.location.href='@if(isset($locale)){{url($locale.'/doc-types')}} @else {{url('en/doc-types')}} @endif'" class="btn btn-outline-warning waves-effect">{{__('language.Cancel')}}</button>
                </div>
            </form>
		</div>
	</div>
</div>
@stop
@section('vendor-script')
  <!-- vendor files -->
  <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>
@endsection
@section('page-script')
  <!-- Page js files -->
<script src="{{ asset(mix('js/scripts/forms/validations/form-doc-type-validation.js')) }}"></script>
@endsection