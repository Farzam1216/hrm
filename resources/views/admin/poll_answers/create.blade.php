@extends('layouts/contentLayoutMaster')
@section('title','Polls')
@section('heading')
@section('vendor-style')
{{-- Page Css files --}}
<link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap4.min.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/buttons.bootstrap4.min.css')) }}">
@endsection

@section('page-style')
{{-- Page Css files --}}
<link rel="stylesheet" href="{{ asset(mix('css/base/pages/app-user.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">

@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header border-bottom pt-1 pb-1">
                <h4 class="card-title">{{$poll->title}}</h4>
                <!-- @if(!empty($poll->poll_description))
                <div>
                    <p>{{$poll->poll_description}}</p>
                </div>
                @endif -->
            </div>

            <form action="@if(isset($locale)){{route('polls.take.store',[$locale,$poll->id])}} @else {{route('polls.take.store',['en',$poll->id])}} @endif" id="answers-form" method="post">
                {{ csrf_field() }}
                <div class="card-body">
                    @foreach($questions as $key => $question)
                    <div class="content-header pb-2 mt-1 ml-2">
                        <h5 class="mb-0">{{$key+1}} - {{$question->title}}</h5>
                    </div>

                    <div class="row mr-3 ml-3  border-bottom">
                        <div class="col-md-6 col-12">
                            @foreach($question['options'] as $optionKey => $option)
                            <div class="form-check pb-2">
                                <input class="form-check-input" id="answer-{{$optionKey}}-{{$key}}" required type="radio" name="answer[{{$key}}]" value="{{$question->id}}-{{$option->id}}" />
                                <label class="form-check-label" for="answer-{{$optionKey}}-{{$key}}">{{$option->option_name}}&nbsp;</label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                    <div class="col-12">
                        <br>
                        <button type="submit" class="btn btn-primary mr-1">{{__('language.Submit')}} {{__('language.Poll')}} </button>
                        <button type="reset" onclick="window.location.href='@if(isset($locale)){{url($locale.'/polls/')}} @else {{url('en/polls')}} @endif'" class="btn btn-outline-warning waves-effect">{{__('language.Cancel')}}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@stop
@section('vendor-script')
{{-- Vendor js files --}}
<script src="{{ asset(mix('vendors/js/tables/datatable/jquery.dataTables.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/datatables.bootstrap4.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.responsive.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/responsive.bootstrap4.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/datatables.checkboxes.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/datatables.buttons.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/buttons.bootstrap4.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/jszip.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/vfs_fonts.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/buttons.html5.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/buttons.print.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.rowGroup.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>

@endsection
@section('page-script')
{{-- Page js files --}}
<script src="{{ asset(mix('js/scripts/forms/validations/form-answers.js'))}}"></script>
<script src="{{ asset(mix('js/scripts/tables/table-datatables-basic.js')) }}"></script>
@endsection