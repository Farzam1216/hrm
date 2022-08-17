@extends('layouts/contentLayoutMaster')
@section('title','Import Employees')

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
                <form id="import-form" action="@if(isset($locale)) {{route('import.employee.show', [$locale, 'preview'])}} @else {{route('import.employee.show', ['en', 'preview'])}} @endif" method="post" enctype="multipart/form-data">
                    <input name="_method" type="hidden" value="GET">
                    {{csrf_field()}}
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class="control-label text-right">{{__('language.Excel File')}} </label><span class="text-danger"> *</span>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="file" name="file" accept=".xlsx">
                                <label class="custom-file-label" for="customFile">Choose file</label>
                            </div>
                        </div>
                    </div>
                    <a class="btn-sm pl-0" href="{{ asset('sheets/employee_sheet_template.xlsx') }}" download><i data-feather="download"></i> {{__('language.Click Here To Download Sample Sheet Template')}}</a>
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button type="submit" id="preview" class="btn btn-primary waves-effect waves-float waves-light" data-toggle="modal" data-target="#large"><span class="d-lg-none d-md-none d-sm-inline"><i data-feather="check-circle"></i></span><span class="d-none d-lg-inline d-md-inline d-sm-none"> Preview</span></button>
                                        <button type="button" onclick="window.location.href='@if(isset($locale)) {{url($locale.'/employees')}} @else {{url('en/employees')}} @endif'" class="btn btn-outline-warning waves-effect"><span class="d-lg-none d-md-none d-sm-inline"><i data-feather="x-circle"></i></span><span class="d-none d-lg-inline d-md-inline d-sm-none">Cancel</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
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
<script src="{{ asset(mix('js/scripts/forms/validations/form-import-validation.js')) }}"></script>
@endsection