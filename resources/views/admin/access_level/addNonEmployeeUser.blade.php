@extends('layouts/contentLayoutMaster')
@section('title','Access Level')
@section('page-style')
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
@endsection
@section('content')
<form action="@if(isset($locale)){{url($locale.'/access-levels/store-non-employee')}} @else {{url('en/access-levels/store-non-employee')}} @endif"
      id = "add-non-employee-form" method="post" enctype="multipart/form-data">
    {{ csrf_field() }}
    <div class="card">
        <div class="container">
            <h6 class="mt-1"> What exactly is a non-employee user?</h6>
            <p>Users allow people outside your company (like contractors or auditors, for example) to access your account. They won't show up in the directory and they won't have an employee profile.</p><hr>
            <div class="row">
                <div class="form-group col-md-6">
                    <label class="control-label">{{__('language.First Name')}}</label>
                    <input type="text" name="firstname" value="{{ old('firstname') }}" class="form-control " placeholder="{{__('language.Enter')}} {{__('language.First Name')}}" required>

                </div>
                <div class="form-group col-md-6">
                    <label class="control-label">{{__('language.Last Name')}}</label>

                    <input type="text" name="lastname" value="{{ old('lastname') }}" class="form-control " placeholder="{{__('language.Enter')}} {{__('language.Last Name')}}" required>

                </div>
                <!--/span-->
            </div>

            <div class="row">
                <div class="form-group col-md-6">
                    <label class="control-label">{{__('language.Email')}}</label>
                    <input type="email" name="official_email" value="{{ old('official_email') }}" class="form-control" placeholder=" e.g. example@gmail.com" required>
                </div>
                <div class="form-group col-md-6">
                    <label class="control-label">{{__('language.Access Level')}}</label>
                    <select class="form-control select2" name="employeerole">
                        <option value="1">Full Admin</option>
                        <option value="noaccess">No Access</option>
                        @if($managerRoles->isNotEmpty())
                        <optgroup label = "Manager Roles">
                            @foreach($managerRoles as $key => $role)
                                <option value="{{$role->id}}">{{$role->name}}</option>
                            @endforeach
                        </optgroup>
                        @endif
                        @if($customRoles->isNotEmpty())
                            <optgroup label = "Manager Roles">
                                @foreach($customRoles as $key => $role)
                                    <option value="{{$role->id}}">{{$role->name}}</option>
                                @endforeach
                            </optgroup>
                        @endif
                    </select>
                </div>
            </div>
            <div class="mt-1 mb-1">
            <button class="btn btn-primary waves-effect waves-float waves-light" type="submit" id="btnSubmit">
                <i class="d-block d-sm-none" data-feather='check-circle'></i><span class="d-none d-sm-inline"> Save</span></button>
            <button class="btn btn-outline-warning ml-1 waves-effect waves-float waves-light" type="button" id="cancelBtn"
            onclick="window.location.href='@if(isset($locale)){{url($locale.'/access-level')}} @else {{url('en/access-level')}} @endif'">
                <i class="d-block d-sm-none" data-feather='x-circle'></i><span class="d-none d-sm-inline"> Cancel</span>
            </button>
        </div>
        </div>
    </div>
</form>
@section('vendor-script')
    <!-- vendor files -->
    <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
@endsection
@section('page-script')
    {{-- Page js files --}}
    <script src="{{ asset(mix('js/scripts/forms/validations/form-ACL-add-non-employee-validation.js'))}}"></script>

@endsection
@stop