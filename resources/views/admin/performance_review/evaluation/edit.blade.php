@extends('layouts/contentLayoutMaster')
@section('title','Edit Evaluation')
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
                    <button type="reset" onclick="window.location.href='@if(isset($locale)){{route('evaluations.employee-evaluations', [$locale, $employee_id])}} @else {{route('evaluations.employee-evaluations', ['en', $employee_id])}} @endif'" class="btn btn-primary waves-effect waves-float waves-light">
                        <i data-feather="chevron-left"></i><span class="d-none d-lg-inline d-md-inline d-sm-inline">{{__('language.Back')}}</span>
                    </button>
                @endif
                <div class="text-center h4">Performance Evaluation Form</div>

                <hr>

                <form id="questionnaire-form" action="@if(isset($locale)) {{ route('evaluations.update', [$locale, $employee_id, $questionnaire_id]) }} @else {{ route('evaluations.update', ['en', $employee_id, $questionnaire_id]) }} @endif" method="post">
                    @csrf
                    <input type="hidden" name="_method" value="PUT">
                    <input type="text" name="questionnaire_id" value="{{$questionnaire_id}}" hidden>
                    <input type="text" name="employee_id" value="{{$employee_id}}" hidden>
                    @php $i = 1; @endphp
                    @foreach($questionnaire as $key => $quest)
                        @foreach($quest['answers'] as $answer)
                            <input type="text" name="question_id[]" value="{{$answer->question_id}}" hidden>
                            <div class="pt-2">
                                @php $count = '0'; $questionData = ''; @endphp
                                @if($questionsHistory)
                                    @foreach($questionsHistory as $questionHistory)
                                        @if($count == '0' && $questionHistory->question_id == $answer->question_id)
                                                <div class="form-group">
                                                    <h5>{{__('language.Question')}} {{$i++}}: {{$questionHistory->question}}</h5>
                                                </div>
                                            @php $count++; $questionData = $questionHistory; @endphp
                                        @endif
                                    @endforeach
                                @endif

                                @if(!$questionsHistory || $count == '0')
                                    @foreach($questions as $key => $question)
                                        @if($question->id == $answer->question_id)
                                            <div class="form-group">
                                                <h5>{{__('language.Question')}} {{$i++}}: {{$question->question}}</h5>
                                            </div>
                                            @php $questionData = $question; @endphp
                                        @endif
                                    @endforeach
                                @endif

                                @if(isset($questionData->question_id))
                                    @php $questionId = $questionData->question_id; @endphp
                                @else
                                    @php $questionId = $questionData->id; @endphp
                                @endif

                                @if($questionData->field_type == 'small text field')
                                    <div class="row">
                                        <div class="form-group col-6">
                                            <label class="control-label">Answer:</label>
                                            <input type="text" class="form-control" name="answer[{{$questionId}}]" id="answer{{$questionId}}" oninput="checkInput({!! $questionId !!});" value="{{ old('answer', $answer->answer) }}">
                                            <span id="answer-error{{$questionId}}" question_id="{{$questionId}}" class="error hidden error-message">Answer is required</span>
                                        </div>
                                    </div>
                                @endif

                                @if($questionData->field_type == 'long text field')
                                    <div class="row">
                                        <div class="form-group col-12">
                                            <label class="control-label">Answer:</label>
                                                <textarea class="form-control" name="answer[{{$questionId}}]" id="answer{{$questionId}}" oninput="checkInput({!! $questionId !!});" >{{old('answer', $answer->answer)}}</textarea>
                                            <span id="answer-error{{$questionId}}" question_id="{{$questionId}}" class="error hidden error-message">Answer is required</span>
                                        </div>
                                    </div>
                                @endif

                                @if($questionData->field_type == 'multiple choice button')
                                    <label class="control-label">Answer:</label>
                                    <input type="text" @if(isset($questionData->question_id)) id="answer{{$questionData->question_id}}" @else id="answer{{$questionData->id}}" @endif  style="position:absolute; z-index: -1;">
                                    <div class="row col-12">
                                        @php $count = '0'; $prev_created_at = ''; @endphp
                                        @if($optionsHistory)
                                            @foreach($optionsHistory as $key => $optionHistory)
                                                @if($prev_created_at == '')
                                                    @php $prev_created_at = $optionHistory->created_at->format('Y-m-d G:i'); @endphp
                                                @endif
                                                @if($optionHistory->created_at->format('Y-m-d G:i') == $prev_created_at)
                                                    <div class="form-group col-md-6 mb-0" style="padding-bottom: 5px;">
                                                        <input type="radio" name="answer[{{$questionId}}]" id="answer[{{$questionId}}][{{$optionHistory->option_id}}]" value="{{$optionHistory->option_id}}" question_id="{{$questionId}}" @if($answer->option_id == $optionHistory->option_id) checked @endif>
                                                        <label for="answer[{{$questionId}}][{{$optionHistory->option_id}}]">{{$optionHistory->q_option}}</label>
                                                    </div>
                                                    @php $count++; @endphp
                                                @endif
                                            @endforeach
                                        @endif

                                        @if(!$optionsHistory || $count == '0')
                                            @foreach($questions as $question)
                                                @foreach($question['options'] as $option)
                                                    @if($option->question_id == $questionId)
                                                        <div class="form-group col-md-6 mb-0" style="padding-bottom: 5px;">
                                                            <input type="radio" name="answer[{{$questionId}}]" id="answer[{{$questionId}}][{{$option->id}}]" value="{{$option->id}}" question_id="{{$questionId}}"@if($answer->option_id == $option->id) checked @endif>
                                                            <label for="answer[{{$questionId}}][{{$option->id}}]">{{$option->option}}</label>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endforeach
                                        @endif

                                        <div class="form-group col-12">
                                            <span id="answer-error{{$questionId}}" question_id="{{$questionId}}" class="error hidden error-message">Please select an option</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <hr class="mt-0">
                        @endforeach
                    @endforeach

                    <div class="mt-2">
                        <button type="button" class="btn btn-primary mr-1" onclick="validate();">
                            <span class=" d-lg-none d-md-none d-sm-none"><i data-feather="check-circle"></i></span>
                            <span class="d-none d-lg-inline d-md-inline d-sm-inline">{{__('language.Update')}}</span>
                        </button>
                        <button type="reset" onclick="window.location.href='@if(isset($locale)){{route('evaluations.employee-evaluations', [$locale, $employee_id])}} @else {{route('.evaluation.employee-evaluations', ['en', $employee_id])}} @endif'" class="btn btn-outline-warning waves-effect">
                            <span class=" d-lg-none d-md-none d-sm-none"><i data-feather="x-circle"></i></span>
                            <span class="d-none d-lg-inline d-md-inline d-sm-inline">{{__('language.Cancel')}}</span>
                        </button>
                    </div>
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

<script>
function checkInput(question_id)
{
    if($("#answer"+question_id).val() == ''){
        $("#answer-error"+question_id).removeClass("hidden");
        $("#answer"+question_id).addClass("error");
    }
    if($("#answer"+question_id).val() != '') {
        $("#answer-error"+question_id).addClass("hidden");
        $("#answer"+question_id).removeClass("error");
    }
}

function validate()
{
    check = true;
    count = 0;
    $.each($(".error-message"), function(index, error) {
        if ($(error).hasClass('hidden') == false) {
            check = false;

            if (count == 0) {
                $("#answer"+$(error).attr('question_id')).focus();
                count++;
            }
        }
    });

    if (check == true && count == 0) {
        $("#questionnaire-form").submit();
    }
}

$("input[type='radio']").on('click', function(){
    $("#answer-error"+this.getAttribute('question_id')).addClass("hidden");
});
</script>
@endsection
@stop