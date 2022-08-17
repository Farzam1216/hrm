@extends('layouts/contentLayoutMaster')
@section('title','Time Off Type')
@section('heading')
@section('page-style')
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
@endsection

@stop
@section('content')
    <section id="multiple-column-form">
        <div class="row">
            <div class="col-12">
                <div class="card ">
                    <div class="card-header">
                        <h4 class="card-title pt-1 pl-2">Create Time Off Type</h4>
                    </div>
                    <div class="card-body pt-1">
                        <form class="form" method="post" action="@if(isset($locale)){{url($locale.'/time-off-type')}} @else {{url('en/time-off-type')}} @endif" id="time-off-type">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name-column">Time Off Type</label><span class="text-danger">*</span>
                                        <input
                                                type="text"
                                                id="first-name-column"
                                                class="form-control"
                                                placeholder="{{__('language.Enter')}} {{__('language.Time Off')}} {{__('language.Type')}}"
                                                name="timeOffType"
                                        />
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary mr-1">{{__('language.Add')}} {{__('language.Time Off')}} {{__('language.Type')}}</button>
                                    <button type="button" onclick="window.location.href='@if(isset($locale)){{url($locale.'/time-off-type')}} @else {{url('en/time-off-type')}} @endif'" class="btn btn-outline-warning">Cancel</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

@stop
@section('vendor-script')
    <!-- vendor files -->
    <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
@endsection
@section('page-script')
    {{-- Page js files --}}
    <script src="{{ asset(mix('js/scripts/forms/validations/form-time-off-type.js'))}}"></script>

@endsection
