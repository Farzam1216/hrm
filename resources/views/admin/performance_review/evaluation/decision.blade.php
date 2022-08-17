@extends('layouts/contentLayoutMaster')
@section('title','Evaluation Decision')
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
                <button type="reset" onclick="window.location.href='@if(isset($locale)){{route('evaluations.employee-evaluations', [$locale, $employee_id])}} @else {{route('evaluations.employee-evaluations', ['en', $employee_id])}} @endif'" class="btn btn-primary waves-effect waves-float waves-light">
                    <i data-feather="chevron-left"></i><span class="d-none d-lg-inline d-md-inline d-sm-inline">{{__('language.Back')}}</span>
                </button>

                <div class="text-center h4 pt-1">Performance Evaluation Form</div>

                <hr>
                @php $i = 1; @endphp
                @foreach($questionnaire as $quest)
                    @foreach($quest['answers'] as $answer)
                        <div class="mt-2">
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
                                @foreach($questions as $question)
                                    @if($question->id == $answer->question_id)
                                        <div class="form-group">
                                            <h5>{{__('language.Question')}} {{$i++}}: {{$question->question}}</h5>
                                        </div>
                                        @php $questionData = $question; @endphp
                                    @endif
                                @endforeach
                            @endif

                            @if($questionData->field_type == 'small text field')
                                <div class="row col-12">
                                    <h5>Answer:</h5>&nbsp;
                                    <h5 class="font-weight-normal">
                                        {{$answer->answer}}
                                    </h5>
                                </div>
                            @endif

                            @if($questionData->field_type == 'long text field')
                                <div class="row col-12">
                                    <h5>Answer:</h5>&nbsp;
                                    <h5 class="font-weight-normal">
                                        {{$answer->answer}}
                                    </h5>
                                </div>
                            @endif

                            @if(isset($questionData->question_id))
                                @php $questionId = $questionData->question_id; @endphp
                            @else
                                @php $questionId = $questionData->id; @endphp
                            @endif

                            @if($questionData->field_type == 'multiple choice button')
                                <h5>Answer:</h5>
                                <div class="row">
                                    @php $count = '0'; $prev_created_at = ''; @endphp
                                    @if($optionsHistory)
                                        @foreach($optionsHistory as $optionHistory)
                                            @if($prev_created_at == '')
                                                @php $prev_created_at = $optionHistory->created_at->format('Y-m-d G:i'); @endphp
                                            @endif
                                            @if($optionHistory->created_at->format('Y-m-d G:i') == $prev_created_at)
                                                <li class="col-6" style="padding-bottom: 5px;">
                                                    <label class="h6 font-weight-normal">{{$optionHistory->q_option}}</label>

                                                    @if($answer->option_id == $optionHistory->option_id)
                                                        <i class="text-success" data-feather='check'></i>
                                                    @endif
                                                </li>
                                                @php $count++; @endphp
                                            @endif
                                        @endforeach
                                    @endif

                                    @if(!$optionsHistory || $count == '0')
                                        @foreach($questions as $question)
                                            @foreach($question['options'] as $option)
                                                @if($option->question_id == $questionId)
                                                    <li class="col-6" style="padding-bottom: 5px;">
                                                        <label class="h6 font-weight-normal">{{$option->option}}</label>

                                                        @if($answer->option_id == $option->id)
                                                            <i class="text-success" data-feather='check'></i>
                                                        @endif
                                                    </li>
                                                @endif
                                            @endforeach
                                        @endforeach
                                    @endif
                                </div>
                            @endif
                        </div>
                        <hr>
                    @endforeach
                @endforeach

                <div class="badge d-block badge-primary mt-2">
                    <h5 class="text-light m-0">Decision Form</h5>
                </div>
                <form id="evaluation-decision-form" class="mt-1" action="@if(isset($locale)){{route('evaluations.submitDecision', [$locale, $employee_id, $questionnaire_id])}} @else {{route('evaluations.submitDecision', ['en', $employee_id, $questionnaire_id])}} @endif" method="post">
                    {{ csrf_field() }}
                    <input type="text" name="employee_id" value="{{$employee_id}}" hidden>
                    <input type="text" name="questionnaire_id" value="{{$questionnaire_id}}" hidden>
                    <input type="text" name="employee_can_view" id="employee_can_view" hidden>
                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label class="control-label">{{__('language.Decision')}}</label><span class="text-danger"> *</span>
                                <select class="form-control" type="text" id="decision" name="decision">
                                    <option value="">Select Decision</option>
                                    <option value="1">Approved</option>
                                    <option value="0">Rejected</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label class="control-label">{{__('language.Comment')}}</label>
                                <textarea class="form-control" id="comment" name="comment" value="{{ old('comment') }}" placeholder="{{__('language.Enter')}} {{__('language.Comment')}} {{__('language.Here')}}"></textarea>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div>
                        <button type="button" class="btn btn-primary mr-1" onclick="validate();" data-toggle="tooltip" data-original-title="Submit">
                            <span class=" d-lg-none d-md-none d-sm-none"><i data-feather="check-circle"></i></span>
                            <span class="d-none d-lg-inline d-md-inline d-sm-inline">{{__('language.Submit')}}</span>
                        </button>
                        <button type="reset" onclick="window.location.href='@if(isset($locale)){{route('evaluations.employee-evaluations', [$locale, $employee_id, $questionnaire_id])}} @else {{route('evaluations.employee-evaluations', ['en', $employee_id, $questionnaire_id])}} @endif'" class="btn btn-outline-warning waves-effect">
                            <span class=" d-lg-none d-md-none d-sm-none"><i data-feather="x-circle"></i></span>
                            <span class="d-none d-lg-inline d-md-inline d-sm-inline">{{__('language.Cancel')}}</span>
                        </button>
                    </div>
                </form>

                <div class="modal fade" id="view-confirmation" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="myModalLabel1">View Permission</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body mt-1">
                                <h5>Do you want to allow relevant employee to view this questionnaire?</h5>
                            </div>
                            <div class="modal-footer">
                                <button type="button" onclick="decision('0');" class="btn btn-outline-warning waves-effect">{{__('language.No')}}</button>
                                <button type="button" onclick="decision('1');"  class="btn btn-primary waves-effect waves-float waves-light">{{__('language.Yes')}}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function validate()
    {
        if($('#evaluation-decision-form').valid() == true) {
            $("#view-confirmation").modal('show');
        }
    }
    function decision(value)
    {
        $("#employee_can_view").val(value);
        $("#evaluation-decision-form").submit();
    }
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
<script src="{{ asset(mix('js/scripts/forms/validations/form-performance-evaluation-decision.js'))}}"></script>
<script src="{{ asset(mix('js/scripts/forms/pickers/form-pickers.js')) }}"></script>
@endsection
@stop