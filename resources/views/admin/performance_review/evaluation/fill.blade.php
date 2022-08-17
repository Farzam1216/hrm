@extends('layouts/contentLayoutMaster')
@section('title','Fill Evaluation')
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
                @if($questions == '[]')
                    <button type="reset" onclick="window.location.href='@if(isset($locale)){{route('evaluations.index', [$locale])}} @else {{route('evaluations.index', ['en'])}} @endif'" class="btn btn-primary waves-effect waves-float waves-light">
                        <i data-feather="chevron-left"></i><span class="d-none d-lg-inline d-md-inline d-sm-inline">{{__('language.Back')}}</span>
                    </button>
                @endif
                <div class="text-center h4">Performance Evaluation Form</div>

                <hr>

                <form id="evaluation-form" action="@if(isset($locale)) {{ route('evaluations.store', [$locale, $employee_id]) }} @else {{ route('evaluations.store', ['en']) }} @endif" method="post">
                    @csrf
                    <input type="text" name="employee_id" value={{$employee_id}} hidden>
                    @php $i = 1; @endphp

                    @if($employee->assignedForm)
                        @foreach($questions as $question)
                            @foreach($employee->assignedForm['assignedQuestions'] as $assignedQuestion)
                                @if($question->id == $assignedQuestion->question_id)
                                    <div class="pt-2">
                                        <div class="form-group">
                                            <h5>{{__('language.Question')}} {{$i++}}: {{$question->question}}</h5>
                                        </div>
                                        <div class="row"> 
                                            @if($question->field_type == 'small text field')
                                                <div class="form-group col-6">
                                                    <label class="control-label">Answer:</label>
                                                    <input type="text" class="form-control" name="answer[{{$question->id}}]" id="answer{{$question->id}}" value="{{ old('answer') }}" oninput="check({!! $question->id !!})">
                                                    <span id="answer-error{{$question->id}}" class="error hidden">Answer is required</span>
                                                </div>
                                            @endif

                                            @if($question->field_type == 'long text field')
                                                <div class="form-group col-12">
                                                    <label class="control-label">Answer:</label>
                                                    <textarea class="form-control" name="answer[{{$question->id}}]" id="answer{{$question->id}}" value="{{ old('answer') }}" oninput="check({!! $question->id !!})"></textarea>
                                                    <span id="answer-error{{$question->id}}" class="error hidden">Answer is required</span>
                                                </div>
                                            @endif
                                        </div>

                                        @if($question->field_type == 'multiple choice button')
                                            <label class="control-label">Answer:</label>
                                            <input type="text" focus_name="focus" id="focus{{$question->id}}" style="position:absolute; z-index: -1;">
                                            <div class="row col-12">
                                                @foreach($question['options'] as $option)
                                                    @if($option->question_id == $question->id)
                                                        <div class="form-group col-md-6 mb-0" style="padding-bottom: 5px;">
                                                            <input type="radio" name="answer[{{$question->id}}]" id="answer[{{$question->id}}][{{$option->id}}]" value="{{$option->id}}" question_id="{{$question->id}}">
                                                            <label for="answer[{{$question->id}}][{{$option->id}}]">{{$option->option}}</label>
                                                        </div>
                                                    @endif
                                                @endforeach
                                                <div class="form-group col-12">
                                                    <span id="option-error{{$question->id}}" class="error hidden">Please select an option</span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <hr class="mt-0">
                                @endif
                            @endforeach
                        @endforeach
                    @endif

                    @if($questions != '[]')
                        <div class="mt-2">
                            <button type="button" class="btn btn-primary mr-1" onclick='validate({!! $employee !!}, {!! $questions !!})'>
                                <span class=" d-lg-none d-md-none d-sm-none"><i data-feather="check-circle"></i></span>
                                <span class="d-none d-lg-inline d-md-inline d-sm-inline">{{__('language.Submit')}}</span>
                            </button>
                            <button type="reset" onclick="window.location.href='@if(isset($locale)){{route('evaluations.index', [$locale])}} @else {{route('evaluations.index', ['en'])}} @endif'" class="btn btn-outline-warning waves-effect">
                                <span class=" d-lg-none d-md-none d-sm-none"><i data-feather="x-circle"></i></span>
                                <span class="d-none d-lg-inline d-md-inline d-sm-inline">{{__('language.Cancel')}}</span>
                            </button>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>

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
<script src="{{ asset(mix('js/scripts/forms/pickers/form-pickers.js')) }}"></script>
<script src="{{ asset('js/scripts/form-performance-evaluation-fill.js') }}"></script>
@endsection
@stop