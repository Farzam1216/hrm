@extends('layouts.contentLayoutMaster')
@section('title','Create Email Template')
@section('heading')
@stop
@section('page-style')
{{-- Page Css files --}}
<link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('css/base/pages/app-user.css')) }}">
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
@endsection
@section('content')
    <!-----Show error messages---->
    @if (Session::has('error'))
        <div class="alert alert-warning" align="left">
            <a href="#" class="close" data-dismiss="alert">&times;</a>
            <strong>!</strong> {{Session::get('error')}}
        </div>
    @endif
    <!-------end: Show error messages----->
    

    <form id="email_type" action="@if(isset($locale)){{url($locale.'/emailtemplates')}} @else {{url('en/emailtemplates')}} @endif"
    method="post"  enctype="multipart/form-data">
        {{ csrf_field() }}
            <div class="card">
            <div class="container">
                <hr>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label class="control-label">{{__('Template Name')}}</label><span
                                    class="text-danger"> *</span>
                        <input type="text" name="template_name"  class="form-control " placeholder="e.g. {{__('language.Invitation to Phone Screening')}}" required>
        
                    </div>
                    <div class="form-group col-md-6">
                        <label class="control-label">{{__('Email Subject')}}</label><span
                                    class="text-danger"> *</span>
                        <input type="text" name="subject"  class="form-control " placeholder="{{__('language.Enter')}} {{__('language.Subject')}}...." required>
        
                    </div>
                    <!--/span-->
                </div>
        
        
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="control-label">{{__('File Upload')}}</label>
                        <input type="file" class="form-control" name="document[]" multiple/>
                    </div>
                    <!--/span-->
                </div>
        
                <div class="row">
                    <!--/span-->
                </div>
        
        
                <div class="row">
                    <div class="form-group col-md-8">
                        <label class="control-label">{{__('language.Message')}}</label>
                        <textarea  class="textarea_editor form-control summernote" id="textarea" name="message"
                        placeholder="{{__('language.Enter')}} {{__('language.text')}} ..."
                        required rows="10"></textarea>
                    </div>
                    <div class="form-group col-md-4">
                        <label class="control-label"><b>{{__('language.Email')}} {{__('language.Placeholders')}}
                            :</b></label>
                    <br>
                    <small>Placeholders will be replaced with actual data at the time of sending email.
                        e.g. @name will be replaced with the First Name of Candidate</small>
                    <br>
                    <div class="btn-group-vertical btn-block ">
                        <button type="button" class="btn btn-info btn-block" placeholder="@name"
                                onclick="appendPlaceholder(this)">First Name
                        </button>
                        <button type="button" class="btn btn-info btn-block" placeholder="@lastName"
                                onclick="appendPlaceholder(this)">Last Name
                        </button>
                        <button type="button" class="btn btn-info btn-block" placeholder="@jobTitle"
                                onclick="appendPlaceholder(this)">Job Title
                        </button>
                        <button type="button" class="btn btn-info btn-block" placeholder="@senderName"
                                onclick="appendPlaceholder(this)">Sender Name
                        </button>
                        <button type="button" class="btn btn-info btn-block"
                                placeholder="@senderDesignation" onclick="appendPlaceholder(this)">
                            Sender Designation
                        </button>
                    </div>
        
                    </div>
                </div>
                
                <hr>
        
        
                <div class="mt-1 mb-1">
                <button class="btn btn-primary waves-effect waves-float waves-light" type="submit" id="btnSubmit">
                    <i class="d-block d-sm-none" data-feather='check-circle'></i><span class="d-none d-sm-inline"> Save</span></button>
                <button class="btn btn-outline-warning ml-1 waves-effect waves-float waves-light" type="button" id="cancelBtn"
                onclick="window.location.href='@if(isset($locale)){{url($locale.'/email-templates')}} @else {{url('en/email-templates')}} @endif'">
                    <i class="d-block d-sm-none" data-feather='x-circle'></i><span class="d-none d-sm-inline"> Cancel</span>
                </button>
            </div>
            </div>
            </div>
        </form>
@section('vendor-script')
    <!-- vendor files -->
    <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/forms/validations/form-email-template.js'))}}"></script>
@endsection
    @section('page-script')
        <script src="{{ asset(mix('js/scripts/forms/validations/form-email-template.js'))}}"></script>
        <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
        <script>
            $('.summernote').summernote({
                height: 200,   //set editable area's height
                codemirror: { // codemirror options
                  theme: 'monokai'
                }
              });
        </script>
        <script>
            function appendPlaceholder(elem) {
                
                var id = $(elem).attr("placeholder");
                console.log(id);
                $("#textarea").summernote('insertText', id);
                
            }
        </script>
    @endsection
@stop