@extends('layouts/contentLayoutMaster')
@section('title','Employee Dependent')
@section('vendor-style')
    <!-- vendor css files -->
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/pickadate/pickadate.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
@endsection

@section('page-style')
    <!-- Page css files -->
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-pickadate.css')) }}">
@endsection
@section('content')
    <section id="basic-input">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Edit Employee Dependent</h4>
                    </div>
                    <div class="card-body">
                        <form action="@if(isset($locale)){{url($locale.'/employees/'.$emp_id.'/dependents/'.$employeeDependent->id)}}@else{{url('en/employees/'.$emp_id.'/dependents/'.$employeeDependent->id)}}@endif" method="post">
                            <input type="hidden" id="dependents-form" name="_method" value="PUT">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-xl-4 col-md-6 col-12 ">
                                    <div class="form-group">
                                        <label for="firstName">{{__('language.First Name')}}<span
                                                    class="text-danger"> *</span></label>
                                        <input type="hidden" name="first_name" value="">
                                        @if(Auth::user()->isAdmin() || isset($permissions['dependents'][$emp_id]['employeedependent first_name']) )
                                            <input type="text" name="first_name" class="form-control" id="firstName"
                                                   placeholder="{{__('language.First Name')}}" value="{{$employeeDependent->first_name}}" @if(in_array('first_name',$disabledFields)) disabled @endif
                                                   @if(Auth::user()->isNotAdmin() && isset($permissions['dependents'][$emp_id]['employeedependent first_name']) && $permissions['dependents'][$emp_id]['employeedependent first_name'] == "view") disabled @endif>
                                        @endif
                                    </div>
                                </div>@if(Auth::user()->isAdmin() || isset($permissions['dependents'][$emp_id]['employeedependent middle_name']) )
                                    <div class="col-xl-4 col-md-6 col-12 ">
                                        <div class="form-group">
                                            <label for="middleName">{{__('language.Middle Name')}}</label>
                                            <input type="hidden" name="middle_name" value="">

                                            <input type="text" name="middle_name" class="form-control"
                                                   placeholder="{{__('language.Middle Name')}}" id="middleName" value="@if(isset($employeeDependent->middle_name)){{$employeeDependent->middle_name}}@endif"  @if(in_array('middle_name',$disabledFields)) disabled @endif
                                                   @if(Auth::user()->isNotAdmin() && isset($permissions['dependents'][$emp_id]['employeedependent middle_name']) && $permissions['dependents'][$emp_id]['employeedependent middle_name'] == "view") disabled @endif>

                                        </div>
                                    </div>
                                @endif
                                <div class="col-xl-4 col-md-6 col-12 ">
                                    <div class="form-group">
                                        <label for="lastName">{{__('language.Last Name')}}<span
                                                    class="text-danger"> *</span></label>
                                        <input type="hidden" name="last_name" value="">
                                        @if(Auth::user()->isAdmin() || isset($permissions['dependents'][$emp_id]['employeedependent last_name']) )
                                            <input type="text" name="last_name" class="form-control" id="lastName" value="{{$employeeDependent->last_name}}" @if(in_array('last_name',$disabledFields)) disabled @endif
                                                   placeholder="{{__('language.Last Name')}}"
                                                   @if(Auth::user()->isNotAdmin() && isset($permissions['dependents'][$emp_id]['employeedependent last_name']) && $permissions['dependents'][$emp_id]['employeedependent last_name'] == "view") disabled @endif>
                                        @endif
                                    </div>
                                </div>
                                <input type="hidden" name="date_of_birth" value="{{$employeeDependent->date_of_birth}}">
                                @if(Auth::user()->isAdmin() || isset($permissions['dependents'][$emp_id]['employeedependent date_of_birth']) )
                                    <div class="col-xl-4 col-md-6 col-12 ">
                                        <div class="form-group">
                                            <label for="date_of_birth">{{__('language.Date Of Birth')}}</label>
                                            <input  type="date"  id="date_of_birth" name="date_of_birth" value="{{$employeeDependent->date_of_birth}}" class="form-control" @if(in_array('date_of_birth',$disabledFields)) disabled @endif
                                            @if(Auth::user()->isNotAdmin() && isset($permissions['dependents'][$emp_id]['employeedependent date_of_birth']) && $permissions['dependents'][$emp_id]['employeedependent date_of_birth'] == "view") disabled @endif>
                                        </div>
                                    </div>
                                @endif
                                <input type="hidden" name="SSN" value="{{$employeeDependent->SSN}}">
                                @if(Auth::user()->isAdmin() || isset($permissions['dependents'][$emp_id]['employeedependent SSN']) )
                                    <div class="col-xl-4 col-md-6 col-12 ">
                                        <div class="form-group">
                                            <label for="firstName">SNN</label>
                                            <input type="number" name="SSN"
                                                   placeholder="{{__('language.Enter')}} {{__('language.SNN Number')}}  {{__('language.here')}}"
                                                   class="form-control" value="{{$employeeDependent->SSN}}" @if(in_array('SNN',$disabledFields)) disabled @endif
                                                   @if(Auth::user()->isNotAdmin() && isset($permissions['dependents'][$emp_id]['employeedependent SSN']) && $permissions['dependents'][$emp_id]['employeedependent SSN'] == "view") disabled @endif>
                                        </div>
                                    </div>
                                @endif

                                <input type="hidden" name="SIN" value="{{$employeeDependent->SIN}}">
                                @if(Auth::user()->isAdmin() || isset($permissions['dependents'][$emp_id]['employeedependent SIN']) )
                                    <div class="col-xl-4 col-md-6 col-12 ">
                                        <div class="form-group">
                                            <label for="sin_number">SIN</label>
                                            <input type="number" name="SIN"
                                                   placeholder="{{__('language.Enter')}} {{__('language.SIN Number')}}  {{__('language.here')}}"
                                                   class="form-control" id="sin_number" value="{{$employeeDependent->SIN}}" @if(in_array('SIN',$disabledFields)) disabled @endif
                                                   @if(Auth::user()->isNotAdmin() && isset($permissions['dependents'][$emp_id]['employeedependent SIN']) && $permissions['dependents'][$emp_id]['employeedependent SIN'] == "view") disabled @endif>

                                        </div>
                                    </div>
                                @endif

                                <input type="hidden" name="gender" value="">
                                @if(Auth::user()->isAdmin() || isset($permissions['dependents'][$emp_id]['employeedependent gender']) )
                                    <div class="col-md-6 col-12 ">
                                        <div class="form-group">
                                            <label for="gender">{{__('language.Gender')}}<span
                                                        class="text-danger"> *</span></label>
                                            <select class="form-control" name="gender" @if(in_array('gender',$disabledFields)) disabled @endif
                                                    @if(Auth::user()->isNotAdmin() && isset($permissions['dependents'][$emp_id]['employeedependent gender']) && $permissions['dependents'][$emp_id]['employeedependent gender'] == "view")
                                                    disabled="true" @endif id="gender">
                                                <option value="">Select Gender</option>
                                                <option value="Male" @if($employeeDependent->gender == 'Male') selected @endif>Male</option>
                                                <option value="Female" @if($employeeDependent->gender == 'Female') selected @endif>Female</option>
                                            </select>
                                        </div>
                                    </div>
                                @endif

                                <input type="hidden" name="relationship" value="">
                                @if(Auth::user()->isAdmin() || isset($permissions['dependents'][$emp_id]['employeedependent relationship']) )
                                    <div class="col-md-6 col-12 ">
                                        <div class="form-group">
                                            <label for="relationship">{{__('language.Relationship')}}<span
                                                        class="text-danger"> *</span></label>
                                            <select class="form-control" name="relationship" @if(in_array('relationship',$disabledFields)) disabled @endif
                                                    @if(Auth::user()->isNotAdmin() && isset($permissions['dependents'][$emp_id]['employeedependent relationship']) && $permissions['dependents'][$emp_id]['employeedependent relationship'] == "view")
                                                    disabled="true" @endif id="relationship">
                                                <option value="">Select Relationship</option>
                                                <option value="Child" @if($employeeDependent->relationship == 'Child') selected @endif >Child</option>
                                                <option value="Family" @if($employeeDependent->relationship == 'Family') selected @endif>Family</option>
                                                <option value="Spouse" @if($employeeDependent->relationship == 'Spouse') selected @endif>Spouse</option>
                                            </select>
                                        </div>
                                    </div>
                                @endif

                                <div class="col-md-12 col-12 pb-1">
                                    <div class="demo-inline-spacing">
                                        <input type="hidden" name="is_us_citizen" value="0">
                                        @if(Auth::user()->isAdmin() || isset($permissions['dependents'][$emp_id]['employeedependent is_us_citizen']) )
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" name="is_us_citizen"  @if($employeeDependent->is_us_citizen == 1) Checked @endif @if(in_array('is_us_citizen',$disabledFields)) disabled @endif
                                                       @if(Auth::user()->isNotAdmin() && isset($permissions['dependents'][$emp_id]['employeedependent is_us_citizen']) && $permissions['dependents'][$emp_id]['employeedependent is_us_citizen'] == "view") disabled
                                                       @endif value="1" class="custom-control-input" id="is_us_citizen"/>
                                                <label class="custom-control-label"
                                                       for="is_us_citizen">{{__('language.Is US Citizen')}}</label>
                                            </div>
                                        @endif
                                        <input type="hidden" name="is_student" value="0">
                                        @if(Auth::user()->isAdmin() || isset($permissions['dependents'][$emp_id]['employeedependent is_student']) )
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" name="is_student" @if($employeeDependent->is_student == 1) Checked @endif @if(in_array('is_student',$disabledFields)) disabled @endif
                                                @if(Auth::user()->isNotAdmin() && isset($permissions['dependents'][$emp_id]['employeedependent is_student']) && $permissions['dependents'][$emp_id]['employeedependent is_student'] == "view") disabled
                                                       @endif value="1" class="custom-control-input" id="is_student"/>
                                                <label class="custom-control-label"
                                                       for="is_student">{{__('language.Full Time Student')}}</label>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <input type="hidden" name="street1" value="">
                                <input type="hidden" name="street2" value="">
                                <input type="hidden" name="city" value="">
                                <input type="hidden" name="state" value="">
                                <input type="hidden" name="zip" value="">
                                <input type="hidden" name="country" value="">

                                @if(Auth::user()->isAdmin() || isset($permissions['dependents'][$emp_id]['employeedependent street1']) )
                                    <div class="col-md-6 col-12 ">
                                        <div class="form-group">
                                            <label for="street1">{{__('language.Enter')}} {{__('language.Street 1')}}</label>
                                            <input type="text" name="street1"
                                                   placeholder="{{__('language.Enter')}} {{__('language.Street 1')}}"
                                                   class="form-control" id="street1" value="{{$employeeDependent->street1}}" @if(in_array('street1',$disabledFields)) disabled @endif
                                                   @if(Auth::user()->isNotAdmin() && isset($permissions['dependents'][$emp_id]['employeedependent street1']) && $permissions['dependents'][$emp_id]['employeedependent street1'] == "view")  disabled @endif>
                                        </div>
                                    </div>
                                @endif
                                @if(Auth::user()->isAdmin() || isset($permissions['dependents'][$emp_id]['employeedependent street2']) )
                                    <div class="col-md-6 col-12 ">
                                        <div class="form-group">
                                            <label for="street2">{{__('language.Enter')}} {{__('language.Street 2')}}</label>
                                            <input type="text" name="street2"
                                                   placeholder="{{__('language.Enter')}} {{__('language.Street 2')}}"
                                                   class="form-control" id="street2" value="{{$employeeDependent->street2}}" @if(in_array('street2',$disabledFields)) disabled @endif
                                                   @if(Auth::user()->isNotAdmin() && isset($permissions['dependents'][$emp_id]['employeedependent street2']) && $permissions['dependents'][$emp_id]['employeedependent street2'] == "view")  disabled @endif>
                                        </div>
                                    </div>
                                @endif

                                @if(Auth::user()->isAdmin() || isset($permissions['dependents'][$emp_id]['employeedependent city']) )
                                    <div class="col-xl-4 col-md-6 col-12 ">
                                        <div class="form-group">
                                            <label for="city">{{__('language.Enter')}} {{__('language.City')}}</label>
                                            <input type="text" name="city" class="form-control" id="city" value="{{$employeeDependent->city}}" @if(in_array('city',$disabledFields)) disabled @endif
                                                   placeholder="{{__('language.Enter')}} {{__('language.City')}}"
                                                   @if(Auth::user()->isNotAdmin() && isset($permissions['dependents'][$emp_id]['employeedependent city']) && $permissions['dependents'][$emp_id]['employeedependent city'] == "view")  disabled @endif>
                                        </div>
                                    </div>
                                @endif
                                @if(Auth::user()->isAdmin() || isset($permissions['dependents'][$emp_id]['employeedependent state']) )
                                    <div class="col-xl-4 col-md-6 col-12 ">
                                        <div class="form-group">
                                            <label for="state">{{__('language.Enter')}} {{__('language.State')}}</label>
                                            <input type="text" name="state" class="form-control" id="state" value="{{$employeeDependent->state}}" @if(in_array('state',$disabledFields)) disabled @endif
                                                   placeholder="{{__('language.Enter')}} {{__('language.state')}}"
                                                   @if(Auth::user()->isNotAdmin() && isset($permissions['dependents'][$emp_id]['employeedependent state']) && $permissions['dependents'][$emp_id]['employeedependent state'] == "view")  disabled @endif>
                                        </div>
                                    </div>
                                @endif
                                @if(Auth::user()->isAdmin() || isset($permissions['dependents'][$emp_id]['employeedependent zip']) )
                                    <div class="col-xl-4 col-md-6 col-12 ">
                                        <div class="form-group">
                                            <label for="zip">{{__('language.Enter')}} {{__('language.Zip')}}</label>
                                            <input type="number" name="zip" class="form-control" id="zip" value="{{$employeeDependent->zip}}" @if(in_array('zip',$disabledFields)) disabled @endif
                                                   placeholder="{{__('language.Enter')}} {{__('language.Zip')}}"
                                                   @if(Auth::user()->isNotAdmin() && isset($permissions['dependents'][$emp_id]['employeedependent zip']) && $permissions['dependents'][$emp_id]['employeedependent zip'] == "view")  disabled @endif>
                                        </div>
                                    </div>
                                @endif

                                @if(Auth::user()->isAdmin() || isset($permissions['dependents'][$emp_id]['employeedependent country']) )
                                    <div class="col-md-6 col-12 ">
                                        <div class="form-group">
                                            <label for="country">{{__('language.Country')}}</label>
                                            <select class="form-control" name="country" value="{{$employeeDependent->country}}" @if(in_array('country',$disabledFields)) disabled @endif
                                                    @if(Auth::user()->isNotAdmin() && isset($permissions['dependents'][$emp_id]['employeedependent country']) && $permissions['dependents'][$emp_id]['employeedependent country'] == "view")
                                                    disabled="true" @endif id="country">
                                                @foreach($countries as $country)--}}
                                                <option value="{{$country->name}}">{{$country->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif
                                <div class="col-md-6 col-12 "></div>
                                <input type="hidden" name="home_phone" value="{{$employeeDependent->home_phone}}">
                                @if(Auth::user()->isAdmin() || isset($permissions['dependents'][$emp_id]['employeedependent home_phone']) )
                                    <div class="col-md-6 col-12 ">
                                        <div class="form-group">
                                            <label for="home_phone">{{__('language.Home Number')}}</label>
                                            <input type="number" name="home_phone"
                                                   placeholder="{{__('language.Enter')}} {{__('language.Home Number')}}  "
                                                   class="form-control" id="home_phone" value="{{$employeeDependent->home_phone}}" @if(in_array('home_phone',$disabledFields)) disabled @endif
                                                   @if(Auth::user()->isNotAdmin() && isset($permissions['dependents'][$emp_id]['employeedependent home_phone']) && $permissions['dependents'][$emp_id]['employeedependent home_phone'] == "view")  disabled @endif>
                                        </div>
                                    </div>
                                @endif
                                <br>
                                <div class="col-12">
                                    <div class="float-left">
                                        <button type="submit"
                                                class="btn btn-primary  mr-sm-1 waves-effect waves-float waves-light">{{__('language.Update')}} </button>
                                        <a href="@if(isset($locale)){{url($locale.'/employees/'.$emp_id.'/dependents')}} @else {{url('en/employees/'.$emp_id.'/dependents')}} @endif" class="btn btn-outline-warning waves-effect"
                                                data-dismiss="modal">{{__('language.Cancel')}}</a>
                                    </div>
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
    <script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>
@endsection
@section('page-script')
    {{-- Page js files --}}
    <script src="{{ asset(mix('js/scripts/forms/validations/form-dependents-validation.js'))}}"></script>
    <script>
        if ($('#date_of_birth').length) {
            $('#date_of_birth').flatpickr({
                onReady: function (selectedDates, dateStr, instance) {
                    if (instance.isMobile) {
                        $(instance.mobileInput).attr('step', null);
                    }
                }
            });
        }
    </script>
@endsection