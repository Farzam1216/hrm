@extends('layouts/contentLayoutMaster')
@section('title','Assign Work Schedule')
@section('vendor-style')
    {{-- Vendor Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/pickadate/pickadate.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
@endsection

@section('page-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-pickadate.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}">
@endsection
@section('content')
    <!-- Bootstrap Select start -->
    <section class="bootstrap-select">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Assign Work Schedule</h4>
                    </div>
                    <div class="card-body">

                        <form class="form"
                              action="@if(isset($locale)){{url($locale.'/assign/work-schedule/'.$workSchedule->id)}} @else {{url('en/assign/work-schedule/'.$workSchedule->id)}} @endif"
                              method="post" onsubmit="return myForm();" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="customSelectMulti">Select Employees</label>
                                        <select class="custom-select" id="customSelectMulti" multiple>
                                            @foreach($employees as $employee)
                                            <option value="{{$employee->id}}" @if(in_array($employee->id,$assignedEmployeeIDByWorkSchedule)) selected="selected" @endif>{{$employee->getFullNameAttribute()}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <input type="text" id="selected-employees" name="selected_employees" hidden>
                                </div>
                                <div class="col-12"><br>
                                    <button type="submit" class="btn btn-primary mr-1">Submit</button>
                                    <button type="reset"
                                            onclick="window.location.href='@if(isset($locale)){{url($locale.'/work-schedule')}} @else {{url('en/work-schedule')}} @endif'"
                                            class="btn btn-outline-warning waves-effect">Cancel
                                    </button>
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
    <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.date.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.time.js')) }}"></script>
@endsection
@section('page-script')
    <!-- Page js files -->
    <script src="{{ asset(mix('js/scripts/forms/validations/form-departments-validation.js')) }}"></script>
    <script>
        function myForm(){
            var options = document.getElementById('customSelectMulti').selectedOptions;
            var values = Array.from(options).map(({ value }) => value);
            console.log(values)
            $('#selected-employees').val(values);

        }


    </script>
@endsection