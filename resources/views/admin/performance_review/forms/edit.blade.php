@extends('layouts/contentLayoutMaster')
@section('title','Edit Form')
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
                @foreach($performanceForm as $form)
                    <form id="form" action="@if(isset($locale)){{route('forms.update', [$locale, $form->id])}} @else {{route('forms.update', ['en', $form->id])}} @endif" method="post">
                        <input type="hidden" name="_method" value="PUT">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{__('language.Name')}}</label><span class="text-danger"> *</span>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ $form->name }}" placeholder="Enter Questionnaire Form Name">
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
                                @php $checked = false; @endphp
                                @foreach($form['assignedQuestions'] as $assignedQuestion)
                                    @if($assignedQuestion->question_id == $question->id)
                                        @php $checked = true; @endphp
                                    @endif  
                                @endforeach
                                <div class="col-md-6 pt-1">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input checkboxes" id="question{{$question->id}}" name="questions[]" value="{{$question->id}}" @if($checked == true) checked @endif>
                                        <label class="custom-control-label" for="question{{$question->id}}">Q-{{$i++}}: {{$question->question}}</label>
                                    </div>
                                </div>
                            @endforeach

                            <div class="col-12 pt-2 hidden" id="checkboxes-error">
                                <label class="error">Please select a checkbox</label>
                            </div>
                        </div>

                        <hr>

                        <div>
                            <button type="button" class="btn btn-primary mr-1" onclick="validate();">
                                <span class=" d-lg-none d-md-none d-sm-none"><i data-feather="check-circle"></i></span>
                                <span class="d-none d-lg-inline d-md-inline d-sm-inline">{{__('language.Update')}} {{__('language.Form')}}</span>
                            </button>
                            <button type="reset" onclick="window.location.href='@if(isset($locale)){{route('forms.index', [$locale])}} @else {{route('forms.index', ['en'])}} @endif'" class="btn btn-outline-warning waves-effect">
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
    $(".checkboxes").on('click', function(){
        if (this.checked == true) {
            $("#checkboxes-error").addClass('hidden');
        }
    });

    function validate()
    {

        var validateForm = $("#form").valid();
        var checkboxes = $(".checkboxes");
        var check = false;

        $.each(checkboxes, function(index, val) {
            if (val.checked == true) {
                check = true;
            }
        });

        if (check == false) {
            $("#checkboxes-error").removeClass('hidden');
        }
        
        if (check == true && validateForm == true) {
            $("#form").submit();
        }
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
<script src="{{ asset(mix('js/scripts/forms/validations/performance-form-validation.js'))}}"></script>
<script src="{{ asset(mix('js/scripts/forms/pickers/form-pickers.js')) }}"></script>
@endsection
@stop