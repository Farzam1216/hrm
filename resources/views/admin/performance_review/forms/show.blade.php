@extends('layouts/contentLayoutMaster')
@section('title','Show Form')
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
            <div class="card-header pt-1 pb-1">
                <button type="button" class="btn create-new btn-primary mr-1" onclick="window.location.href='@if(isset($locale)){{route('forms.index', [$locale])}} @else {{route('forms.index', ['en'])}} @endif'">
                    <i data-feather="chevron-left"></i> {{__('language.Back')}}
                </button>
            </div>
            <hr class="mt-0 mb-0 mr-2 ml-2">
            <div class="card-body">
                @foreach($performanceForm as $form)
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">{{__('language.Name')}}</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ $form->name }}" placeholder="Enter Questionnaire Form Name" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <h4 class="col-12 font-weight-bold">Questions</h4>
                    </div>
                    <hr class="mt-0">
                    <div class="row">
                        @php $i = 1; @endphp
                        @foreach($questions as $question)
                            @foreach($form['assignedQuestions'] as $assignedQuestion)
                                @if($assignedQuestion->question_id == $question->id)
                                    <div class="col-md-6 pt-1">
                                        <div>Q-{{$i++}}: {{$question->question}}</div>
                                    </div>
                                @endif  
                            @endforeach
                        @endforeach
                    </div>
                @endforeach
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