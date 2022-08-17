@extends('layouts/contentLayoutMaster')
@section('title','Show Question')
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
                    <div class="col-12">
                        <div class="row">
                            <div class="col-md-6 row">
                                <h6>{{__('language.Field Type')}}:</h6>&nbsp;<h6 class="font-weight-normal">{{$question->field_type}}</h6>
                            </div>
                            <div class="col-md-6 row">
                                <h6>{{__('language.Question Placement')}}:</h6>&nbsp;<h6 class="font-weight-normal">{{$question->placement}}</h6>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mt-1">
                        <label class="control-label">{{__('language.Question')}}</label>
                        <textarea class="form-control" id="question" name="question" value="{{ old('question') }}" placeholder="{{__('language.Enter')}} {{__('language.Question')}} {{__('language.Here')}}" readonly>{{$question->question}}</textarea>
                    </div>

                    @if($question->field_type == 'multiple choice button')
                        <div class="mt-2 font-weight-bold"><h4>Options</h4></div>

                        <hr>

                        <div class="row">
                            @foreach($question['options'] as $key => $option)
                                <li class="col-md-6 mt-1">
                                    {{$option->option}}
                                </li>
                            @endforeach
                        </div>
                    @endif
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
<script src="{{ asset(mix('js/scripts/forms/validations/form-performance-question.js'))}}"></script>
<script src="{{ asset(mix('js/scripts/forms/pickers/form-pickers.js')) }}"></script>

@endsection
@stop