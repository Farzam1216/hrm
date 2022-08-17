@extends('layouts/contentLayoutMaster')
@section('title','Edit Designation')
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
		<div class="card card-outline-info">
            <form id="designation-form" action="@if(isset($locale)){{url($locale.'/designations',$designations->id)}} @else {{url('en/designations',$designations->id)}} @endif" 
            method="post" enctype="multipart/form-data">
            <input name="_method" type="hidden" value="PUT">
                {{ csrf_field() }}
                <br>
                <div class="row">
                    <div class="col-md-6 pl-3">
                        <div class="form-group">
                            <label class="control-label" for="designation_name">{{__('language.Designation')}} {{__('language.Name')}}<span class="text-danger"> *</span></label>
                            <input  type="text" name="designation_name" id="designation_name" value="{{old('name',$designations->designation_name)}}" placeholder="{{__('language.Enter')}} {{__('language.Designation')}} {{__('language.Name')}} {{__('language.Here')}}" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6 pr-3">
                        <div class="form-group">
                            <label class="control-label">{{__('language.Status')}}</label>
                            <select name="status" class="form-control">
                                <option value="1" @if($designations->status==1) selected @endif>{{__('language.Active')}}</option>
                                <option value="0" @if($designations->status==0) selected @endif>{{__('language.InActive')}}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-12 d-flex flex-sm-row flex-column mt-2 mb-2">
                    <button  type="submit" class="btn btn-primary mb-1 mb-sm-0 mr-0 ml-1 mr-sm-1 waves-effect waves-float waves-light">{{__('language.Update')}} {{__('language.Designation')}}</button>
                    <button type="button" onclick="window.location.href='@if(isset($locale)){{url($locale.'/designations')}} @else {{url('en/designations')}} @endif'" class="btn btn-outline-warning waves-effect">{{__('language.Cancel')}}</button>
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
<script src="{{ asset(mix('js/scripts/forms/validations/form-designations-validation.js')) }}"></script>
@endsection