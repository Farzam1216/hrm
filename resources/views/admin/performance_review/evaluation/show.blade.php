@extends('layouts/contentLayoutMaster')
@section('title','Show Evaluation')
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
                @if(Auth::user()->isAdmin() || isset($permissions['performance'][Auth::id()]['performance review']))
                    <button type="reset" onclick="window.location.href='@if(isset($locale)){{route('evaluations.employee-evaluations', [$locale, $employee_id])}} @else {{route('evaluations.employee-evaluations', ['en', $employee_id])}} @endif'" class="btn btn-primary waves-effect waves-float waves-light">
                        <i data-feather="chevron-left"></i><span class="d-none d-lg-inline d-md-inline d-sm-inline">{{__('language.Back')}}</span>
                    </button>
                @else
                    <button type="reset" onclick="window.location.href='@if(isset($locale)){{route('evaluations.index', [$locale])}} @else {{route('evaluations.index', ['en'])}} @endif'" class="btn btn-primary waves-effect waves-float waves-light">
                        <i data-feather="chevron-left"></i><span class="d-none d-lg-inline d-md-inline d-sm-inline">{{__('language.Back')}}</span>
                    </button>
                @endif

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

                @if(isset($quest->decision_authority->id))
                    @if(Auth::user()->isAdmin() || isset($permissions['performance'][Auth::id()]['performance review']))
                        <div class="badge d-block badge-primary mt-2">
                            <h5 class="text-light m-0">Questionnaire Decision</h5>
                        </div>

                        @foreach($questionnaire as $quest)
                            <div class="row justify-content-between col-12 mt-2">
                                <h6>Approving Authority: {{$quest->decision_authority->firstname}} {{$quest->decision_authority->lastname}}</h6>
                                <div class="row">
                                    <h6>Status:</h6>&nbsp;
                                    @if($quest->status == '1')
                                        <h6 class="badge badge-light-success">Approved</h6>
                                    @elseif($quest->status == '0')
                                        <h6 class="badge badge-light-danger">Rejected</h6>
                                    @else
                                        <h6 class="badge badge-light-secondary">Pending</h6>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-12">
                                    <label class="control-label">{{__('language.Comment')}}</label>
                                    <textarea class="form-control" readonly>{{ $quest->comment }}</textarea>
                                </div>
                            </div>
                        @endforeach
                    @endif
                @endif
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
@endsection
@stop