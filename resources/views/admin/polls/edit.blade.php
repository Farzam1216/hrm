@extends('layouts/contentLayoutMaster')
@section('title','Polls')
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
            <div class="card-header">
                <h4 class="card-title">{{__('language.Edit Poll')}}</h4>
            </div>
            <div class="card-body">
                <form action="@if(isset($locale)){{url($locale.'/polls/'.$poll->id)}} @else {{url('en/polls/'.$poll->id)}} @endif" id="polls-form" method="post">
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" value="PUT">
                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label class="control-label">{{__('language.Title')}}</label><span class="text-danger"> *</span>
                                <input type="text" required id="title" name="title" placeholder="{{__('language.Enter')}} {{__('language.Title')}} {{__('language.Here')}}" value="{{old('title',$poll->title)}}" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="start_end_date">{{__('language.Date Range')}}</label><span class="text-danger"> *</span>
                                <input type="text" required name="start_end_date" id="start_end_date" value="{{old('start_date',$poll->poll_start_date)}} to {{old('end_date',$poll->poll_end_date)}}" class="form-control flatpickr-range" placeholder="YYYY-MM-DD to YYYY-MM-DD" />
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">{{__('language.Description')}}</label>
                                <textarea class="form-control" name="description" cols="30" rows="3" placeholder="{{__('language.Enter')}} {{__('language.Description')}} {{__('language.Here')}}">{{old('description',$poll->poll_description)}}</textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <br>
                            <button type="submit" class="btn btn-primary mr-1">{{__('language.Update')}} {{__('language.Poll')}} </button>
                            <button type="button" onclick="window.location.href='@if(isset($locale)){{url($locale.'/polls/')}} @else {{url('en/polls')}} @endif'" class="btn btn-outline-warning waves-effect">{{__('language.Cancel')}}</button>
                        </div>
                    </div>
                </form>
            </div>

            </tr>
            </tbody>
            </table>
            <!--end: Datatable -->
        </div>
        <!--end::card body-->
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
<script src="{{ asset(mix('js/scripts/forms/validations/form-poll.js'))}}"></script>
<script src="{{ asset(mix('js/scripts/forms/pickers/form-pickers.js')) }}"></script>
@endsection
@stop