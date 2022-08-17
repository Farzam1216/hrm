@extends('layouts.contentLayoutMaster')
@section('title','Edit Documents')
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
            <form id="document-form" action="@if(isset($locale)){{url($locale.'/documents',$document->id)}} @else {{url('en/documents',$document->id)}} @endif" method="post" enctype="multipart/form-data">
                <input name="_method" type="hidden" value="PUT">
                {{csrf_field()}}
                <br>
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-6 form-group pl-3">
                            <label class="control-label">{{__('language.Name')}}<span class="text-danger">*</span></label>
                            <input type="text" value="{{$document->name}}"  name="document_name" class="form-control" placeholder="{{__('language.Enter')}} {{__('language.Title')}}">
                        </div>
                        <div class="col-md-6 form-group pr-3 has-success">
                            <label class="control-label">{{__('language.Status')}}</label>
                            {{ csrf_field() }}
                            <select name="upload_status" class="form-control custom-select">
                                <option @if($document->status == 1) selected @endif value="1">{{__('language.Active')}}</option>
                                <option @if($document->status == 0)  selected   @endif value="0">{{__('language.InActive')}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 pl-3">
                            <div class="form-group">
                                <label class="control-label">{{__('language.New')}} {{__('language.File')}}<span class="text-danger">*</span></label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="doc" id="customFile" />
                                    <label class="custom-file-label" for="customFile">Choose file</label>
                                </div> 
                            </div>
                            <div>
                                <a class="btn btn-sm btn-primary" href="{{url($document->url)}}" download><i data-feather='download'></i> {{__('language.Click To Download Previous File')}}</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 d-flex flex-sm-row flex-column mt-2 mb-2">
                    <button type="submit" class="btn btn-primary mt-1 mb-1 mb-sm-0 ml-1 mr-0 mr-sm-1 waves-effect waves-float waves-light">{{__('language.Update')}} {{__('language.Document')}} </button>
                    <button type="button" onclick="window.location.href='@if(isset($locale)){{url($locale.'/documents')}} @else {{url('en/documents')}} @endif'" class="btn btn-outline-warning waves-effect mt-1">{{__('language.Cancel')}}</button>
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