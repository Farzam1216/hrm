@extends('layouts/contentLayoutMaster')
@section('title','Edit Question')
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
                @foreach($questionWithOptions as $question)
                    <form id="question-form" action="@if(isset($locale)){{route('questions.update', [$locale, $question->id])}} @else {{route('questions.update', ['en', $question->id])}} @endif" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="question_id" value="{{$question->id}}">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{__('language.Field Type')}}</label><span class="text-danger"> *</span>
                                    <select class="form-control" type="text" id="field_type" name="field_type" autofocus>
                                        <option value="">Select Option</option>
                                        <option value="small text field" @if($question->field_type == "small text field") selected @endif>Small Text Field</option>
                                        <option value="long text field" @if($question->field_type == "long text field") selected @endif>Long Text Field</option>
                                        <option value="multiple choice button" @if($question->field_type == "multiple choice button") selected @endif>Multiple Choice Button</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{__('language.Question Placement')}}</label><span class="text-danger"> *</span>
                                    <input type="text" class="form-control" id="placement" name="placement" value="{{$question->placement}}" placeholder="Enter number from 0-9">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="control-label">{{__('language.Question')}}</label><span class="text-danger"> *</span>
                                    <textarea class="form-control" id="question" name="question" value="{{ old('question') }}" placeholder="{{__('language.Enter')}} {{__('language.Question')}} {{__('language.Here')}}">{{ old('question', $question->question) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="mt-1 col-12" id="add-button" style="display:none;">
                            <div class="row justify-content-between">
                                <div class="font-weight-bold" style="padding-top: 3px;"><h4>Options</h4></div>
                                <div class="text-right">
                                    <button type="button" id="add-option" class="btn btn-sm btn-primary waves-effect waves-light waves-float" data-toggle="tooltip" data-original-title="Add Option">
                                        <span class=" d-lg-none d-md-none d-sm-none"><i data-feather="plus"></i></span>
                                        <span class="d-none d-lg-inline d-md-inline d-sm-inline">Add Option</span>
                                    </button>
                                </div>
                            </div>
                            <hr class="row mt-0">
                        </div>

                        <div class="row" id="options">
                            @foreach($question['options'] as $option)
                                <div class="form-group col-md-6">
                                    <label class="control-label">Option</label>
                                    <a href="#" class="text-danger pl-1 remove_field">
                                        <i data-feather="trash-2"></i>
                                    </a>
                                    <input type="text" class="form-control" name="options[]" value="{{ old('options', $option->option) }}" />
                                </div>
                            @endforeach
                        </div>

                        <hr>

                        <div>
                            <button type="submit" class="btn btn-primary mr-1">
                                <span class=" d-lg-none d-md-none d-sm-none"><i data-feather="check-circle"></i></span>
                                <span class="d-none d-lg-inline d-md-inline d-sm-inline">{{__('language.Update')}}</span>
                            </button>
                            <button type="reset" onclick="window.location.href='@if(isset($locale)){{route('questions.index', [$locale])}} @else {{route('questions.index', ['en'])}} @endif'" class="btn btn-outline-warning waves-effect">
                                <span class=" d-lg-none d-md-none d-sm-none"><i data-feather="x-circle"></i></span>
                                <span class="d-none d-lg-inline d-md-inline d-sm-inline">{{__('language.Cancel')}}</span>
                            </button>
                        </div>
                    </form>
                @endforeach
            </div>
        </div>
    </div>
</div>
<script>
    //on type option change
    $(document).ready(function(){
        if ($("#field_type").val() == 'multiple choice button') {
            document.getElementById('add-button').style.display = "";
        }
    });
    $("#field_type").on('change', function(){
        field_type = this.value;

        if (field_type == 'multiple choice button') {
            document.getElementById('add-button').style.display = "";
            $("#options").append('<div class="form-group col-md-6"><label class="control-label">Option</label><a href="#" class="text-danger pl-1 remove_field"><i data-feather="trash-2"></i></a><input type="text" class="form-control" name="options[]" /></div>');

            feather.replace()
        } else {
            document.getElementById('add-button').style.display = "none";
            document.getElementById("options").innerHTML = "";
        }
    });

    // on add option button click
    $("#add-option").on('click', function(){
        $("#options").append('<div class="form-group col-md-6"><label class="control-label">Option</label><a href="#" class="text-danger pl-1 remove_field"><i data-feather="trash-2"></i></a><input type="text" class="form-control" name="options[]" /></div>');

        feather.replace()
    });

    // on remove button click
    $("#options").on("click",".remove_field", function(e)
    {
        e.preventDefault();

        // Deleting Fields
        $(this).parent('div').remove();
    });
</script>
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
<script src="{{ asset(mix('js/scripts/forms/validations/form-performance-question.js'))}}"></script>
<script src="{{ asset(mix('js/scripts/forms/pickers/form-pickers.js')) }}"></script>

@endsection
@stop