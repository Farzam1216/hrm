@extends('layouts/contentLayoutMaster')
@section('title','Add Employment Status')
@section('page-style')
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <form action="@if(isset($locale)){{url($locale.'/employment-status')}} @else {{url('en/employment-status')}} @endif"
                      id="employment-status" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <br>
                    <div class="row">
                        <div class="col-md-6 form-group pl-3">
                            <label class="control-label">{{__('language.Employment')}} {{__('language.Status')}}</label><span
                                    class="text-danger"> *</span>
                            <input type="text" name="employment_status"
                                   placeholder="{{__('language.Enter')}} {{__('language.Employment')}} {{__('language.Status')}} {{__('language.Here')}}"
                                   class="form-control">

                        </div>
                        <div class="col-md-6 form-group pr-3">
                            <label class="control-label">{{__('language.Status')}}</label><span
                            class="text-danger"> *</span>
                            <select name="status" class="form-control ">
                                <option value="1">{{__('language.Active')}}</option>
                                <option value="0">{{__('language.InActive')}}</option>
                            </select>

                        </div>
                        

                    </div>

                    <div class="row">
                        <div class="col-md-12 form-group pl-3 pr-3">
                            <label class="control-label">{{__('language.Employment')}} {{__('language.Description')}}</label>
                            <textarea class="form-control" rows="5" cols="5" name="description"  placeholder="{{__('language.Enter')}} {{__('language.Employment')}} {{__('language.Description')}} {{__('language.Here')}}"></textarea>

                        </div>

                    </div>

                    <div class="col-12 d-flex flex-sm-row flex-column mt-2 mb-2">
                        <button type="submit"
                                class="btn btn-primary mb-1 mb-sm-0 ml-1 mr-0 mr-sm-1 waves-effect waves-float waves-light">{{__('language.Add')}} {{__('language.Employment')}} {{__('language.Status')}}  </button>
                        <button type="button"
                                onclick="window.location.href='@if(isset($locale)){{url($locale.'/employment-status')}} @else {{url('en/employment-status')}} @endif'"
                                class="btn btn-outline-warning waves-effect">{{__('language.Cancel')}}</button>
                    </div>

                </form>
            </div>
        </div>
        <!--end::card body-->
    </div>
    </div>
@stop
@section('vendor-script')
    <!-- vendor files -->
    <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
@endsection
@section('page-script')
    {{-- Page js files --}}
    <script src="{{ asset(mix('js/scripts/forms/validations/form-employment-status.js'))}}"></script>
@endsection
