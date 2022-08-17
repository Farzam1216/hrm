@extends('layouts.contentLayoutMaster')
@section('title','Add Document')
@section('vendor-style')
  {{-- Vendor Css files --}}
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/file-uploaders/dropzone.min.css')) }}">
@endsection

@section('page-style')
  {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-file-uploader.css')) }}">
@endsection
@section('content')
<div class="row">
	<div class="col-lg-12">
		<div class="card card-outline-info">
			<form id="document-form" action="@if(isset($locale)){{url($locale.'/documents')}} @else {{url('en/documents')}} @endif" method="post" enctype="multipart/form-data">
				{{ csrf_field() }}
				<br>
				<div class="form-body">
					<div class="row">
						<div class="col-md-6 form-group pl-3">
							<label class="control-label">{{__('language.Document')}} {{__('language.Name')}}<span class="text-danger">*</span></label>
							<input type="text" name="document_name" class="form-control" placeholder="{{__('language.Enter')}} {{__('language.Document')}} {{__('language.Name')}}">
						</div>
						<div class="col-md-6 form-group pr-3">
							<label class="control-label">{{__('language.Status')}}</label>
							<select class="form-control" name="status">
								<option value="1">{{__('language.Active')}}</option>
								<option value="0">{{__('language.InActive')}}</option>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6 form-group pl-3">
							<label class="control-label">{{__('language.New')}} {{__('language.File')}}<span class="text-danger">*</span><span class="small"><i> {{__('language.You Can Attatch only One File at a time.')}} </i></span></label>
							<div class="custom-file">
								<input type="file" class="custom-file-input" name="document" id="customFile"/>
								<label class="custom-file-label" for="customFile">Choose file</label>
							</div> 
						</div>
					</div>
				</div>
				<div class="col-12 d-flex flex-sm-row flex-column mt-2 mb-2">
					<button type="submit" class="btn btn-primary mb-1 mb-sm-0 ml-1 mr-0 mr-sm-1 waves-effect waves-float waves-light">{{__('language.Upload')}}</button>
					<button type="button" onclick="window.location.href='@if(isset($locale)){{url($locale.'/documents')}} @else {{url('en/documents')}} @endif'" class="btn btn-outline-warning waves-effect">{{__('language.Cancel')}}</button>
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
  <script src="{{ asset(mix('vendors/js/extensions/dropzone.min.js')) }}"></script>
@endsection
@section('page-script')
  <!-- Page js files -->
<script src="{{ asset(mix('js/scripts/forms/validations/form-documents-validation.js')) }}"></script>
<script src="{{ asset(mix('js/scripts/forms/form-file-uploader.js')) }}"></script>
@endsection