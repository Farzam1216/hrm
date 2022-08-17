@extends('layouts/contentLayoutMaster')

@section('title', 'Edit User Account')

@section('vendor-style')
{{-- Page Css files --}}
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/pickadate/pickadate.css')) }}">
@endsection
@section('page-style')
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
@endsection

@section('content')
<!-- users edit start -->
<section class="users-edit">
  <div class="card">
    <div class="card-content">
      <div class="card-body">
        <div class="tab-content">
          <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">
            <form id="edit-profile-form" method="post" action="{{url($locale.'/personal-profile/update')}}" enctype="multipart/form-data">
              {{csrf_field()}}
              <input type="text" name="id" value={{$employee->id}} hidden>
              <!-- users edit media object start -->
              <div class="media mb-2">
                <img type="image" 
                src="{{asset($employee->picture)}}" onerror="this.src ='{{asset('asset/media/users/default3.png')}}';"
                class="user-avatar users-avatar-shadow rounded mr-2 my-25 cursor-pointer" 
                id="wizardPicturePreview" title="" width="90" height="90"
                />
                <div class="media-body mt-50">
                  <h4 class="media-heading">{{$employee->firstname}} {{$employee->lastname}}</h4>
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
              <!-- users edit media object ends -->
              <!-- users edit account form start -->
              <div class="row">
                <div class="col-12 col-sm-6">
                  <div class="form-group">
                    <label>First Name <span style="color: red;">*</span></label>
                    <input type="text" class="form-control required" placeholder="First Name" value="{{$employee->firstname}}" name="first_name" data-validation-required-message="This first name field is required">
                    <input type="file" name="image" id="user-picture" hidden>
                  </div>
                </div>
                <div class="col-12 col-sm-6">
                  <div class="form-group">
                    <label>Last Name <span style="color: red;">*</span></label>
                    <input type="text" class="form-control required" placeholder="Last Name" name="last_name" value="{{$employee->lastname}}" data-validation-required-message="This last name field is required">
                  </div>
                </div>
                <div class="col-12 col-sm-6">
                  <div class="form-group">
                    <label>E-mail <span style="color: red;">*</span></label>
                    <input type="email" class="form-control required" name="email" placeholder="Email" value="{{$employee->official_email}}" data-validation-required-message="This email field is required">
                  </div>
                </div>
              </div>


              <h3>Password Change</h3>
              <hr>
              <div class="row">
                <div class="col-12 col-sm-8">
                  <div class="form-group">
                    <label>Current Password</label>
                    <fieldset class="form-group position-relative">
                      <input class="form-control" id="oldPassword" type="password" name="current_password" placeholder="current password">
                      <div class="form-control-position">
                        <i class="feather icon-eye" onclick="togglePasswordVisibility(this, document.getElementById('oldPassword'))"></i>
                      </div>
                      <div class="EnterOldPasswordError text-danger" style="display:none;">
                        Old Password is required.
                      </div>
                    </fieldset>
                  </div>
                </div>
                <div class="col-12 col-sm-8">
                  <div class="form-group">
                    <label>New Password</label>
                    <fieldset class="form-group position-relative">
                      <input id="newPassword" type="password" name="new_password" class="form-control" placeholder="new password">
                      <div class="form-control-position">
                        <i class="feather icon-eye" onclick="togglePasswordVisibility(this, document.getElementById('newPassword'))"></i>
                      </div>
                    </fieldset>
                  </div>
                </div>
                <div class="col-12 col-sm-8">
                  <div class="form-group">
                    <label>Re-enter New password</label>
                    <fieldset class="form-group position-relative">
                      <input id="confirmPassword" type="password" name="new_password_confirmation" class="form-control" placeholder="confirm new password">
                      <div class="form-control-position">
                        <i class="feather icon-eye" onclick="togglePasswordVisibility(this, document.getElementById('confirmPassword'))"></i>
                      </div>
                    </fieldset>
                  </div>
                </div>
              </div>

              <hr>
              <div>
                <button type="submit" class="btn btn-primary waves-effect waves-float waves-light"><span class="d-lg-none d-md-none d-sm-inline"><i data-feather="check-circle"></i></span><span class="d-none d-lg-inline d-md-inline d-sm-none">Save
                  Changes</span></button>
                <button type="button" class="btn btn-outline-warning waves-effect" onclick="window.location.href='{{url('en/personal-profile')}}'"><span class="d-lg-none d-md-none d-sm-inline"><i data-feather="x-circle"></i></span><span class="d-none d-lg-inline d-md-inline d-sm-none">Cancel</span></button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <!-- users edit account form ends -->
    </div>
  </div>
</section>
<!-- users edit ends -->
@section('vendor-script')
    <!-- vendor files -->
    <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/forms/validations/edit-profile-form.js'))}}"></script>
@endsection

@section('page-script')
<script>
  function togglePasswordVisibility(toggleButton, passwordField) {
    if (passwordField.type == 'password') {
      passwordField.type = 'text';
      toggleButton.className = 'feather icon-eye-off';

    } else {
      passwordField.type = 'password';
      toggleButton.className = 'feather icon-eye';
    }
  }

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
</script>
@endsection
@stop