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
                        <h4 class="card-title">Create Employee Dependent</h4>
                    </div>
                    <div class="card-body">
                        <form action="@if(isset($locale)){{url($locale.'/employees/'.$emp_id.'/dependents')}}@else{{url('en/employees/'.$emp_id.'/dependents')}}@endif"
                              method="post" id="dependents-form">
                            {{ csrf_field() }}
                        <div class="row">
                            <div class="col-xl-4 col-md-6 col-12 ">
                                <div class="form-group">
                                    <label for="firstName">{{__('language.First Name')}}<span
                                                class="text-danger"> *</span></label>
                                    <input type="hidden" name="first_name" value="">
                                    @if(Auth::user()->isAdmin() || isset($permissions['dependents'][$emp_id]['employeedependent first_name']) )
                                        <input type="text" name="first_name" class="form-control" id="firstName"
                                               placeholder="{{__('language.First Name')}}"
                                               @if(Auth::user()->isNotAdmin() && isset($permissions['dependents'][$emp_id]['employeedependent first_name']) && $permissions['dependents'][$emp_id]['employeedependent first_name'] == "view") disabled @endif>
                                    @endif
                                </div>
                            </div>@if(Auth::user()->isAdmin() || isset($permissions['dependents'][$emp_id]['employeedependent middle_name']) )
                            <div class="col-xl-4 col-md-6 col-12 ">
                                <div class="form-group">
                                    <label for="middleName">{{__('language.Middle Name')}}</label>
                                    <input type="hidden" name="middle_name" value="">

                                        <input type="text" name="middle_name" class="form-control"
                                               placeholder="{{__('language.Middle Name')}}" id="middleName"
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
                                        <input type="text" name="last_name" class="form-control" id="lastName"
                                               placeholder="{{__('language.Last Name')}}"
                                               @if(Auth::user()->isNotAdmin() && isset($permissions['dependents'][$emp_id]['employeedependent last_name']) && $permissions['dependents'][$emp_id]['employeedependent last_name'] == "view") disabled @endif>
                                    @endif
                                </div>
                            </div>
                            <input type="hidden" name="birth_date" value="">
                            @if(Auth::user()->isAdmin() || isset($permissions['dependents'][$emp_id]['employeedependent date_of_birth']) )
                                <div class="col-xl-4 col-md-6 col-12 ">
                                    <div class="form-group">
                                        <label for="date_of_birth">{{__('language.Date Of Birth')}}</label>
                                        <input type="date" id="date_of_birth" name="date_of_birth" class="form-control"
                                               placeholder="{{__('language.Date Of Birth')}}"
                                               @if(Auth::user()->isNotAdmin() && isset($permissions['dependents'][$emp_id]['employeedependent date_of_birth']) && $permissions['dependents'][$emp_id]['employeedependent date_of_birth'] == "view") disabled @endif>
                                    </div>
                                </div>
                            @endif
                            <input type="hidden" name="snn_number" value="">
                            @if(Auth::user()->isAdmin() || isset($permissions['dependents'][$emp_id]['employeedependent SSN']) )
                                <div class="col-xl-4 col-md-6 col-12 ">
                                    <div class="form-group">
                                        <label for="firstName">SNN</label>
                                        <input type="number" name="SSN"
                                               placeholder="{{__('language.Enter')}} {{__('language.SNN Number')}}  {{__('language.here')}}"
                                               class="form-control"
                                               @if(Auth::user()->isNotAdmin() && isset($permissions['dependents'][$emp_id]['employeedependent SSN']) && $permissions['dependents'][$emp_id]['employeedependent SSN'] == "view") disabled @endif>
                                    </div>
                                </div>
                            @endif

                            <input type="hidden" name="sin_number" value="">
                            @if(Auth::user()->isAdmin() || isset($permissions['dependents'][$emp_id]['employeedependent SIN']) )
                                <div class="col-xl-4 col-md-6 col-12 ">
                                    <div class="form-group">
                                        <label for="sin_number">SIN</label>
                                        <input type="number" name="SIN"
                                               placeholder="{{__('language.Enter')}} {{__('language.SIN Number')}}  {{__('language.here')}}"
                                               class="form-control" id="sin_number"
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
                                        <select class="form-control" name="gender"
                                                @if(Auth::user()->isNotAdmin() && isset($permissions['dependents'][$emp_id]['employeedependent gender']) && $permissions['dependents'][$emp_id]['employeedependent gender'] == "view")
                                                disabled="true" @endif id="gender">
                                            <option value="">Select Gender</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
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
                                        <select class="form-control" name="relationship"
                                                @if(Auth::user()->isNotAdmin() && isset($permissions['dependents'][$emp_id]['employeedependent relationship']) && $permissions['dependents'][$emp_id]['employeedependent relationship'] == "view")
                                                disabled="true" @endif id="relationship">
                                            <option value="">Select Relationship</option>
                                            <option value="Child">Child</option>
                                            <option value="Family">Family</option>
                                            <option value="Spouse">Spouse</option>
                                        </select>
                                    </div>
                                </div>
                            @endif

                            <div class="col-md-12 col-12 pb-1">
                                <div class="demo-inline-spacing">
                                    <input type="hidden" name="is_us_citizen" value="0">
                                    @if(Auth::user()->isAdmin() || isset($permissions['dependents'][$emp_id]['employeedependent is_us_citizen']) )
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" name="is_us_citizen"
                                                   @if(Auth::user()->isNotAdmin() && isset($permissions['dependents'][$emp_id]['employeedependent is_us_citizen']) && $permissions['dependents'][$emp_id]['employeedependent is_us_citizen'] == "view") disabled
                                                   @endif value="1" class="custom-control-input" id="is_us_citizen"/>
                                            <label class="custom-control-label"
                                                   for="is_us_citizen">{{__('language.Is US Citizen')}}</label>
                                        </div>
                                    @endif
                                    <input type="hidden" name="is_student" value="0">
                                    @if(Auth::user()->isAdmin() || isset($permissions['dependents'][$emp_id]['employeedependent is_student']) )
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" name="is_student"
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
                                               class="form-control" id="street1"
                                               @if(Auth::user()->isNotAdmin() && isset($permissions['dependents'][$emp_id]['employeedependent street1']) && $permissions['dependents'][$emp_id]['employeedependent street1'] == "view")  disabled @endif>
                                    </div>
                                </div>
                            @endif
                            @if(Auth::user()->isAdmin() || isset($permissions['dependents'][$emp_id]['employeedependent street2']) )
                                <div class="col-md-6 col-12 ">
                                    <div class="form-group">
                                        <label for="street2">{{__('language.Enter')}} {{__('language.Street 2')}}</label>
                                        <input type="text" name="street2"
                                               placeholder="{{__('language.Enter')}} {{__('language.Street 1')}}"
                                               class="form-control" id="street2"
                                               @if(Auth::user()->isNotAdmin() && isset($permissions['dependents'][$emp_id]['employeedependent street2']) && $permissions['dependents'][$emp_id]['employeedependent street2'] == "view")  disabled @endif>
                                    </div>
                                </div>
                            @endif

                            @if(Auth::user()->isAdmin() || isset($permissions['dependents'][$emp_id]['employeedependent city']) )
                                <div class="col-xl-4 col-md-6 col-12 ">
                                    <div class="form-group">
                                        <label for="city">{{__('language.Enter')}} {{__('language.City')}}</label>
                                        <input type="text" name="city" class="form-control" id="city"
                                               placeholder="{{__('language.Enter')}} {{__('language.City')}}"
                                               @if(Auth::user()->isNotAdmin() && isset($permissions['dependents'][$emp_id]['employeedependent city']) && $permissions['dependents'][$emp_id]['employeedependent city'] == "view")  disabled @endif>
                                    </div>
                                </div>
                            @endif
                            @if(Auth::user()->isAdmin() || isset($permissions['dependents'][$emp_id]['employeedependent state']) )
                                <div class="col-xl-4 col-md-6 col-12 ">
                                    <div class="form-group">
                                        <label for="state">{{__('language.Enter')}} {{__('language.State')}}</label>
                                        <input type="text" name="state" class="form-control" id="state"
                                               placeholder="{{__('language.Enter')}} {{__('language.state')}}"
                                               @if(Auth::user()->isNotAdmin() && isset($permissions['dependents'][$emp_id]['employeedependent state']) && $permissions['dependents'][$emp_id]['employeedependent state'] == "view")  disabled @endif>
                                    </div>
                                </div>
                            @endif
                            @if(Auth::user()->isAdmin() || isset($permissions['dependents'][$emp_id]['employeedependent zip']) )
                                <div class="col-xl-4 col-md-6 col-12 ">
                                    <div class="form-group">
                                        <label for="zip">{{__('language.Enter')}} {{__('language.Zip')}}</label>
                                        <input type="number" name="zip" class="form-control" id="zip"
                                               placeholder="{{__('language.Enter')}} {{__('language.Zip')}}"
                                               @if(Auth::user()->isNotAdmin() && isset($permissions['dependents'][$emp_id]['employeedependent zip']) && $permissions['dependents'][$emp_id]['employeedependent zip'] == "view")  disabled @endif>
                                    </div>
                                </div>
                            @endif

                            @if(Auth::user()->isAdmin() || isset($permissions['dependents'][$emp_id]['employeedependent country']) )
                                <div class="col-md-6 col-12 ">
                                    <div class="form-group">
                                        <label for="country">{{__('language.Country')}}</label>
                                        <select class="form-control" name="country"
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
                            <input type="hidden" name="home_phone" value="">
                            @if(Auth::user()->isAdmin() || isset($permissions['dependents'][$emp_id]['employeedependent home_phone']) )
                                <div class="col-md-6 col-12 ">
                                    <div class="form-group">
                                        <label for="home_phone">{{__('language.Home Number')}}</label>
                                        <input type="number" name="home_number"
                                               placeholder="{{__('language.Enter')}} {{__('language.Home Number')}}  "
                                               class="form-control" id="home_phone"
                                               @if(Auth::user()->isNotAdmin() && isset($permissions['dependents'][$emp_id]['employeedependent home_phone']) && $permissions['dependents'][$emp_id]['employeedependent home_phone'] == "view")  disabled @endif>
                                    </div>
                                </div>
                            @endif
                            <br>
                            <div class="col-12">
                                    <button type="submit"
                                            class="btn btn-primary mr-1">{{__('language.Add')}} </button>
                                    <a href="@if(isset($locale)){{url($locale.'/employees/'.Auth::id().'/dependents')}} @else {{url('en/employees/'.Auth::id().'/dependents')}} @endif" class="btn btn-outline-warning mr-1 "
                                            >{{__('language.Cancel')}} </a>
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
