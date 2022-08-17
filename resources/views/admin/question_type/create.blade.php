@extends('layouts/contentLayoutMaster')
@section('title','Question Type')
@section('page-style')
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <form action="@if(isset($locale)){{url($locale.'/question-types')}} @else {{url('en/question-types')}} @endif"
                      id="question-types" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <br>
                    <div class="row">
                        <div class="col-md-6 form-group pl-3">
                            <label class="control-label"> {{__('language.Questions')}} {{__('language.Type')}}</label><span
                                    class="text-danger" required> *</span>
                            <input type="text" name="question_type"
                                   placeholder="{{__('language.Enter')}} {{__('language.Questions')}} {{__('language.Type')}} {{__('language.Here')}}"
                                   class="form-control" required>

                        </div>
                    </div>
                    <div class="col-12 d-flex flex-sm-row flex-column mt-2 mb-2">
                        <button type="submit"
                                class="btn btn-primary mb-1 mb-sm-0 ml-1 mr-0 mr-sm-1 waves-effect waves-float waves-light">{{__('Add Question Type')}}</button>
                        <button type="button"
                                onclick="window.location.href='@if(isset($locale)){{url($locale.'/question-types')}} @else {{url('en/question-types')}} @endif'"
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
    <script src="{{ asset(mix('js/scripts/forms/validations/form-question-types.js'))}}"></script>
@endsection