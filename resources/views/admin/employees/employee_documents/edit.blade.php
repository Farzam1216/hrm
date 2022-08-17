@extends('layouts/contentLayoutMaster')
@section('title','Edit Employee Document')
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
			<div class="card-body">
                <form id="employee-document-form" action="@if(isset($locale)){{url($locale.'/employees/'.$employee_id.'/docs/'.$doc_types->id)}} @else {{url('en/employees/'.$employee_id.'/docs',$doc_types->id)}} @endif"
                    method="post" enctype="multipart/form-data">
                        <input name="_method" type="hidden" value="PUT">
                    {{ csrf_field() }}
                    <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">{{__('language.Name')}}</label>
                            <input type="text" name="name"
                                    value="{{old('name',$doc_types->doc_name)}}"
                                    placeholder="{{__('language.Enter Name Here')}}" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">{{__('language.File')}}</label>
                            <div class="custom-file">
                                    <input class="custom-file-input" type="file" name="file"
                                    placeholder="{{__('language.Attach File Here')}}" class="form-control">
                                    <label class="custom-file-label" for="customFile">Choose file</label>
                                    </div>
                            <a href="{{url($doc_types->doc_file)}}"
                                    download> {{__('language.Click To Download Previous File')}}: &nbsp; <i class="fa fa-file-pdf"></i></a>
                        </div>
                        <input name="employee_id" value="{{$employee_id}}" hidden>
                        <input type="text" name="previous_file"
                                value="{{$doc_types->doc_file}}" hidden>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">{{__('language.Document')}} {{__('language.Type')}}</label>
                            <select name="doc_type" class="form-control">
                                @foreach($all_types as $doctype1)
                                    <option value="{{$doctype1->id}}"
                                            @if($doc_types->doc_type==$doctype1->id) Selected @endif>
                                        {{$doctype1->doc_type_name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">{{__('language.Status')}}</label>
                            <select name="status" class="form-control">
                                <option value="1" @if($doc_types->status==1)Selected @endif>
                                    {{__('language.Active')}}
                                </option>
                                <option value="0" @if($doc_types->status==0)Selected @endif>
                                    {{__('language.InActive')}}
                                </option>
                            </select>
                        </div>
                    </div>
                    </div>
                    <div class="col-12 d-flex flex-sm-row flex-column mt-2">
                        <button  type="submit" class="btn btn-primary mb-1 mb-sm-0 mr-0 mr-sm-1 waves-effect waves-float waves-light">{{__('language.Update')}} {{__('language.Document')}}</button>
                        <button type="button" onclick="window.location.href='@if(isset($locale)){{url($locale.'/employees/'.$employee_id.'/docs')}} @else {{url('en/employees/'.$employee_id.'/docs')}} @endif'" class="btn btn-inverse">{{__('language.Cancel')}}</button>
                    </div>
                </form>
			</div>
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
<script src="{{ asset(mix('js/scripts/forms/validations/form-employee-docs-validation.js')) }}"></script>
<script src="{{ asset(mix('js/scripts/forms/form-file-uploader.js')) }}"></script>
@endsection