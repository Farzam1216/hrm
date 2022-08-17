@extends('layouts/contentLayoutMaster')
@section('title',ucfirst($task_type).' Category')
@section('page-style')
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="modal-content">
                <form @if($task_type == "onboarding")
                      action="@if(isset($locale)){{url($locale.'/onboarding-categories')}}
                @else {{url('en/onboarding-categories')}} @endif"
                      @elseif($task_type == "offboarding")
                      action="@if(isset($locale)){{url($locale.'/offboarding-categories')}}
                      @else {{url('en/offboarding-categories')}} @endif"
                      @endif id="task-category" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <br>
                    <div class="row">
                        <div class="col-md-6 form-group pl-3">

                            <label class="control-label">{{__('language.Task')}} {{__('language.Category')}}</label><span class="text-danger"> *</span>
                            <input  type="text" name="task_category_name" placeholder="{{__('language.Enter')}} {{__('language.Task')}} {{__('language.Category')}} {{__('language.Here')}}" class="form-control">

                        </div>
                    </div>
                    <div class="col-12 d-flex flex-sm-row flex-column mt-2 mb-2">
                        <button  type="submit" class="btn btn-primary mb-1 mb-sm-0 ml-1 mr-0 mr-sm-1 waves-effect waves-float waves-light" >{{__('language.Add')}} {{__('language.Category')}}  </button>
                        @if($task_type == "onboarding")
                            <button type="button"
                                    onclick="window.location.href='@if(isset($locale)){{url($locale.'/onboarding-categories')}} @else {{url('en/onboarding-categories')}} @endif'"
                                    class="btn btn-outline-warning waves-effect">{{__('language.Cancel')}}</button>
                        @else
                            <button type="button"
                                    onclick="window.location.href='@if(isset($locale)){{url($locale.'/offboarding-categories')}} @else {{url('en/offboarding-categories')}} @endif'"
                                    class="btn btn-outline-warning waves-effect">{{__('language.Cancel')}}</button>
                        @endif
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
    <script src="{{ asset(mix('js/scripts/forms/validations/form-task-category.js'))}}"></script>
@endsection
