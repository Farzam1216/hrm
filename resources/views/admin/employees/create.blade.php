@extends('layouts/contentLayoutMaster')
@section('title','Create Employee')
@section('heading')
<!-- Content Header (Page header) -->
@section('vendor-style')
  {{-- Vendor Css files --}}
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
@endsection

@section('page-style')
  {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}">
@endsection

@stop
@section('content')
@if (Session::has('error'))
<div class="alert alert-warning" align="left">
    <a href="#" class="close" data-dismiss="alert">&times;</a>
    <strong>!</strong> {{Session::get('error')}}
</div>
@endif
<div class="row">
    <div class="col-lg-12">
        <div class="card card-outline-info">
            <div class="card-body">
                <form action="@if(isset($locale)){{url($locale.'/employee/store')}} @else {{url('en/employee/store')}} @endif" 
                id="employee-create" method="post" class="form-horizontal" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="form-body">
                        <!-- <center>
                        <input type="image" 
                        src="{{asset('asset/media/error/default.png')}}" 
                        class="user-avatar users-avatar-shadow rounded mr-2 my-25 cursor-pointer" 
                        id="wizardPicturePreview" title="" width="90" height="90"
                        onclick="document.getElementById('picture').click();"
                        />
                        <input type="file" name="picture" id="picture" hidden>
                        </center> -->

                        <!-- image settings -->
                        <div class="media mb-2">
                            <img type="image" 
                            src="{{asset('asset/media/error/default.png')}}" 
                            class="user-avatar users-avatar-shadow rounded mr-2 my-25 cursor-pointer" 
                            id="wizardPicturePreview" title="" width="90" height="90"
                            />
                            <div class="media-body mt-50">
                                <div class="col-12 d-flex mt-1 px-0">
                                    <label class="btn btn-primary mr-75 mb-0" for="picture">
                                    <span class="d-none d-sm-block">Change</span>
                                    <input
                                        class="form-control"
                                        type="file"
                                        id="picture"
                                        name="picture"
                                        hidden
                                        accept="image/png, image/jpeg, image/jpg"
                                    />
                                    <span class="d-block d-sm-none">
                                        <i class="mr-0" data-feather="edit"></i>
                                    </span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <h4 class="mb-1 mt-2"><span class="d-flex align-items-center"><i class="font-medium-3 mr-25" data-feather="user"></i>
                        {{__('language.Employee')}} {{__('language.Information')}}</span></h4>
                        <hr class="m-t-0 m-b-40">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label text-right">{{__('language.Employee')}} #</label>
                                    <input type="text" name="employee_no" value="{{ old('employee_no') }}" 
                                    class="form-control" placeholder="{{__('language.Enter')}} {{__('language.Employee')}} {{__('language.Number')}}">
                                </div>
                            </div>
                            <div class="col-md-6">
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label text-right">{{__('language.First Name')}} </label><span class="text-danger"> *</span>
                                    <input type="text" name="firstname" value="{{ old('firstname') }}" 
                                    class="form-control" placeholder="{{__('language.Enter')}} {{__('language.First Name')}}">
                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label text-right">{{__('language.Last Name')}}</label><span class="text-danger"> *</span>
                                    <input type="text" name="lastname" value="{{ old('lastname') }}" 
                                    class="form-control " placeholder="{{__('language.Enter')}} {{__('language.Last Name')}}">
                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label text-right">{{__('language.Official')}} {{__('language.Email')}}</label><span class="text-danger"> *</span>
                                    <input type="email" name="official_email" value="{{ old('official_email') }}" 
                                    class="form-control " placeholder="e.g. example@glowlogix.com">
                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label text-right">{{__('language.Personal')}} {{__('language.Email')}}</label>
                                    <input type="email" name="personal_email" value="{{ old('personal_email') }}" 
                                    class="form-control " placeholder=" e.g. example@gmail.com">
                                </div>
                            </div>
                            <!--/span-->
                        </div>
                        <!--/row-->
                        <div class="row">
                            <!--/span-->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label text-right">{{__('language.Designation')}}</label>
                                    <select class="form-control custom-select" name="designation">
                                        @foreach($designations as $designation)
                                        <option value="{{$designation->id}}">{{$designation->designation_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label text-right">{{__('language.Employment Status')}}</label>
                                    <select class="form-control custom-select" name="employment_status">
                                        @if($employment_statuses->count() > 0)
                                        @foreach($employment_statuses as $employment_status)
                                        <option value="{{$employment_status->id}}">{{$employment_status->employment_status}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label text-right">{{__('language.Department')}}</label>
                                    <select class="form-control custom-select" data-placeholder="Choose a Category" tabindex="1" name="department_id">
                                        <option value="">{{__('language.Select')}} {{__('language.Department')}}</option>
                                        @if($departments->count() > 0)
                                        @foreach($departments as $department)
                                        <option value="{{$department->id}}">{{$department->department_name}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <!--/span-->
                            <!-- <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label text-right">{{__('language.Type')}}</label>
                                    <select class="form-control custom-select" name="type">
                                        <option value="office" @if(old('type')=="office" ) selected @endif>Work from Office
                                        </option>
                                        <option value="remote" @if(old("type")=="remote" ) selected @endif>Work Remotely
                                        </option>
                                    </select>
                                </div>
                            </div> -->
                        </div>
                        <!--/row-->
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label text-right">{{__('language.Branch')}}</label>
                                    <select class="form-control custom-select" data-placeholder="Choose a Category" tabindex="1" name="location_id">
                                        @foreach($locations as $location)
                                        <option value="{{$location->id}}" @if(old("location_id")=="remote" ) selected @endif>{{$location->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label text-right">{{__('language.Gender')}}</label>
                                    <select class="form-control custom-select" data-placeholder="Choose a Category" tabindex="1" name="gender">
                                        <option value="">{{__('language.Select')}} {{__('language.Gender')}}</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                            </div>
                             <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label text-right">{{__('language.Marital Status')}}</label>
                                    <select class="form-control custom-select" data-placeholder="Choose a Category" tabindex="1" name="marital_status">
                                        <option value="">{{__('language.Select')}} {{__('language.Marital Status')}}</option>
                                        <option value="Single">{{__('language.Single')}}</option>
                                        <option value="Married">{{__('language.Married')}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label text-right">{{__('language.Work Schedule')}}</label>
                                    <select class="form-control custom-select"
                                            data-placeholder="Choose a Work Schedule"
                                            name="workscheduleid">
                                        <option value="">
                                            Select Work Schedule
                                        </option>
                                        @foreach($workSchedules as $workSchedule)
                                            <option value="{{$workSchedule->id}}">
                                                {{$workSchedule->title}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <!--/span-->
                        </div>
                    </div>
                    <br>
                    {{--///Contact Info///--}}
                    <div class="form-body">
                        <h4 class="mb-1 mt-2"><i class="font-medium-3 mr-25" data-feather='smartphone'></i>{{__('language.Contact')}} {{__('language.Information')}}</h4>
                        <hr class="m-t-0 m-b-40">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label text-right">{{__('language.Phone')}}</label>
                                    <input type="text" class="form-control" placeholder=" e.g. 03000000000" 
                                    name="contact_no" value="{{ old('contact_no') }}">
                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label text-right">NIN</label>
                                    <input type="text" class="form-control " 
                                    placeholder="e.g. 000-00-0000" name="nin" value="{{ old('nin') }}">
                                </div>
                            </div>
                        </div>
                        <!--/row-->
                        <div class="row">
                            <!--/span-->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label text-right">{{__('language.Emergency Contact Relationship')}}</label>
                                    <select class="form-control custom-select" name="emergency_contact_relationship">
                                        <option value="father" @if(old("emergency_contact_relationship")=="father" ) selected @endif>
                                            Father
                                        </option>
                                        <option value="brother" @if(old('emergency_contact_relationship')=="brother" ) selected @endif>
                                            Brother
                                        </option>
                                        <option value="mother" @if(old('emergency_contact_relationship')=="mother" ) selected @endif>
                                            Mother
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label text-right">{{__('language.Emergency Phone')}}#</label>
                                    <input type="text" class="form-control " 
                                    placeholder="{{__('language.Enter')}} {{__('language.Emergency Phone')}}#" name="emergency_contact"
                                    value="{{ old('emergency_contact') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label text-right">{{__('language.City')}}</label>
                                     <input type="text" class="form-control " 
                                     placeholder="{{__('language.Enter')}} {{__('language.City')}}" name="city" value="{{ old('city') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label text-right" for="date_of_birth">{{__('language.Date of Birth')}}</label>
                                    <input type="date" class="form-control " name="date_of_birth" 
                                    id="date_of_birth" placeholder="YYYY-MM-DD">
                                </div>
                            </div>
                        </div>
                        <!--/row-->

                        <div class="row">
                            
                            <!--/span-->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label text-right">{{__('language.Permanent Address')}}</label>
                                    <textarea rows="2" class="form-control " 
                                    placeholder="{{__('language.Enter')}} {{__('language.Permanent Address')}}" 
                                    name="permanent_address" value="{{ old('permanent_address') }}"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label text-right">{{__('language.Current Address')}}</label>
                                    <textarea rows="2" class="form-control" placeholder="{{__('language.Enter')}} {{__('language.Current Address')}}" 
                                    name="current_address" value="{{ old('current_address') }}"></textarea>
                                </div>
                            </div>
                        <!--/span-->
                        </div>
                    </div>
                    {{--///Official Info///--}}
                    <div class="form-body">
                        <h4 class="mb-1 mt-2"><i class="mr-1 mr-sm-50" data-feather='hexagon'></i>{{__('language.Official')}} {{__('language.Information')}}</h4>
                        <hr class="m-t-0 m-b-40">
                        <div class="row">
{{--                            <div class="col-md-6">--}}
{{--                                <div class="form-group">--}}
{{--                                    <label class="control-label text-right">{{__('language.Basic Salary')}} <span class="text-danger">*</span></label>--}}
{{--                                    <input type="number" class="form-control" name="basic_salary" placeholder="e.g. 50000" />--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="col-md-6">--}}
{{--                                <div class="form-group">--}}
{{--                                    <label class="control-label text-right">{{__('language.Home Allowance')}} <span class="text-danger">*</span></label>--}}
{{--                                    <input type="number" class="form-control" name="home_allowance" placeholder="e.g. 10000" />--}}
{{--                                </div>--}}
{{--                            </div>--}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label text-right" for="joining_date">{{__('language.Joining Date')}}</label>
                                    <input type="date" id="joining_date" name="joining_date" class="form-control" placeholder="{{__('language.Enter Joining Date')}}" value="{{old('joining_date')}}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="control-label text-right">{{__('language.Reports To')}}</label>
                                <select class="form-control select2" name="manager_id">
                                    <option value="">Select</option>
                                    @foreach($employees as $employee)
                                    <option value="{{$employee->id}}">{{$employee->firstname}} {{$employee->lastname}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label text-right">{{__('language.Self-service access')}}</label>
                                    <label class="control-label d-inline col-md-3 radio-inline">{{__('language.On')}}
                                        <input type="radio" name="Self-service-access" class="access" />
                                    </label>
                                    <label class="control-label d-inline col-md-3 radio-inline">{{__('language.Off')}}
                                        <input type="radio" name="Self-service-access" class="no-access" checked />
                                    </label>
                                    <div class="Off">
                                        <p>Turn this ON to allow basic employee access to do things like request time off and access the company directory.</p>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="col-md-6">
                            
                            <div class="On">
                                <select class="form-control select2" name="employeerole">
                                    <option value="">Select</option>
                                    @foreach($roles as $role)
                                    <option value="{{$role->id}}">{{$role->name}}</option>
                                    @endforeach
                                </select>
                                <p>To enable access, this employee needs to be active and have a valid work or home email.</p>
                            </div>
                        </div>
                    </div>
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-offset-3 col-md-9">
                                    <button type="submit" class="btn btn-primary">{{__('language.Add')}} {{__('language.Employee')}}</button>
                                    <button type="button" onclick="window.location.href='@if(isset($locale)){{url($locale.'/employees')}} @else {{url('en/employees')}} @endif'"
                                        class="btn btn-inverse">{{__('language.Cancel')}}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</div>

@stop

@section('vendor-script')

  <!-- vendor files -->
  <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>
@endsection
@section('page-script')
  <!-- Page js files -->
<script src="{{ asset(mix('js/scripts/forms/form-validation.js')) }}"></script>
<script type="text/javascript">

    $(document).ready(function () {
            var pass_flag = 0;

            $("#submit_update").click(function () {
                pass_flag = 1;
            });

            // console.log(pass_flag); here
            $("#employee_form").submit(function (event) {
                $('#confirm').modal('show');
                if (pass_flag != 1) {
                    event.preventDefault();
                }
            });

            var teams = $('#asana_teams');
            var count = 0;
            var orgId = '{{config('values.asanaWorkspaceId')}}';
            var token = '{{config('values.asanaToken')}}';

            $('.asana').bind('click', function () {
                if ($(this).is(':checked')) {

                    $.ajax({
                        url: "https://app.asana.com/api/1.0/organizations/" + orgId + "/teams",
                        type: 'GET',
                        cache: false,
                        dataType: 'json',
                        beforeSend: function (xhr) {
                            xhr.setRequestHeader('Authorization', 'Bearer ' + token);
                        },
                        success: function (res) {
                            count++;
                            if (count == 1) {
                                teams.append("<h4 class='head'>Teams in Asana</h4>");
                                res.data.forEach(function (item, index) {
                                    teams.append("<div class='row'><lable class='teams'><input name='teams[]' value='" + item.id + "' style='position:unset;opacity:5' type='checkbox' id='" + item.name + "' >" + item.name + "</lable></div>"
                                    );
                                });
                            }
                            teams.show();
                        },
                        error: function (err) {
                            console.log(err);
                        }
                    })
                } else {
                    teams.hide();
                }
            })
        });
        $(function () {

            $(document).ready(function () {
                $(function () {
                    $("#check_all").on('click', function () {
                        $('input:checkbox').not(this).prop('checked', this.checked);
                    });
                    $(".check_all_sub").click(function () {
                        $('div.' + this.id + ' input:checkbox').prop('checked', this.checked);
                    });
                });
            });
        });
</script>
<script>
    $(document).ready(function () {
            // Prepare the preview for profile picture
        $("#picture").change(function () {
                    readURL(this);
                });
        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        $('#wizardPicturePreview').attr('src', e.target.result).fadeIn('slow');
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            }

        $(".form-control").keypress(function (e) {
            if (e.which == 13) {
                e.preventDefault();
                return false;
            }
        });

        $('#asana_teams input[type="checkbox"]').each(function () {
            var $checkbox = $(this);
            $checkbox.checkbox();
        });
        $(".On").hide();
        $(".no-access").click(function(){
     $(".On").hide();
    $(".Off").show();
  });
  $(".access").click(function(){
    $(".Off").hide();
    $(".On").show();
  });
  

</script>
@endsection