@extends('layouts.admin')
@section('title','Help')
@section('heading')
<!-- Content Header (Page header) -->
<div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0 text-dark"> {{__('language.Help')}}</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#"> {{__('language.Support')}}</a></li>
                <li class="breadcrumb-item active"> {{__('language.Help')}}</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->
   
@stop
@section('content')
    <div class="col-md-10 " >
        <div class="card card-body" style="margin-left:200px;">
            <div class="row">
                <div class="col-sm-12 col-xs-12">
                    <form action="@if(isset($locale)){{url($locale.'/contact-us')}} @else {{url('en/contact-us')}} @endif" method="post" id="contact">
                        {{csrf_field()}}
                        <div class="form-group ">
                            <div class="controls">
                            <input type="text" name="name" class="form-control"  placeholder="{{__('language.Enter Name Here')}}" required data-validation-required-message="This field is required">
                            </div>
                            </div>
                        <div class="form-group ">
                            <div class="controls">
                            <input type="email" name="email" class="form-control"  placeholder="{{__('language.Enter email Here')}}" required data-validation-required-message="This field is required">
                            </div>
                            </div>
                        <div class="form-group ">
                            <div class="controls">
                            <input type="number" name="number" class="form-control"  pattern="[0-9]{11}" placeholder="{{__('language.Enter Phone Number Here')}}" required data-validation-required-message="This field is required">
                            </div>
                            </div>
                        <div class="form-group ">
                            <div class="controls">
                            <select class="form-control custom-select" name="type" required data-validation-required-message="This field is required">
                                <option value="Feedback">{{__('language.Feedback')}}</option>
                                <option value="Others">{{__('language.Others')}}</option>
                            </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="controls">
                            <textarea name="message" class="form-control" rows="5" placeholder="{{__('language.Message')}}"  required data-validation-required-message="This field is required"></textarea>
                            </div>
                            </div>
                        <button type="submit" class="btn btn-success waves-effect waves-light m-r-10">{{__('language.Send')}}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
    <script>
            // Initialize form validation on the registration form.
            // It has the name attribute "registration"
            jQuery("form[id='contact']").validate({
                // Specify validation rules
                rules: {
                    // The key name on the left side is the name attribute
                    // of an input field. Validation rules are defined
                    // on the right side
                    name: "required",
                    email: "required",
                    number:"required",
                    type: "required",
                    message: "required",
                },
                // Specify validation error messages
                messages: {
                    name: "Please enter name",
                    email: "Please enter email",
                    number: "Please enter number",
                    type: "Please select type",
                    message: "Please enter message",
                },
                // Make sure the form is submitted to the destination defined
                // in the "action" attribute of the form when valid
                submitHandler: function(form) {
                    form.submit();
                }
        });
    </script>
@endsection
