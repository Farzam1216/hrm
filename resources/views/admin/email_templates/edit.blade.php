@extends('layouts.contentLayoutMaster')
@section('title','Edit Email Template')
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



    <form  action="@if(isset($locale)){{url($locale.'/emailtemplates/'.$emailTemplate->id)}} @else {{url('en/emailtemplates/'.$emailTemplate->id)}} @endif"
        method="post"  enctype="multipart/form-data">
        <input name="_method" type="hidden" value="PUT">
        {{ csrf_field() }}
            <div class="card">
            <div class="container">
                <hr>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label class="control-label">{{__('Template Name')}}</label><span
                                    class="text-danger"> *</span>
                        <input type="text" name="template_name" value="{{$emailTemplate->template_name}}"  class="form-control " placeholder="e.g. {{__('language.Invitation to Phone Screening')}}" required>
        
                    </div>
                    <div class="form-group col-md-6">
                        <label class="control-label">{{__('Email Subject')}}</label><span
                                    class="text-danger"> *</span>
                        <input type="text" name="subject"  value="{{$emailTemplate->subject}}"  class="form-control " placeholder="{{__('language.Enter')}} {{__('language.Subject')}}...." required>
        
                    </div>
                    <!--/span-->
                </div>
        
        
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="control-label">{{__('File Upload')}}</label>
                        <input type="file" class="form-control" name="document[]" multiple/>
                        @if($emailTemplateAttachment !=null)
                            @foreach($emailTemplateAttachment as $emailAttachment)
                                <div class="form-group d-inline-block">
                                    <div class="custom-file">
                                        <input type="text" disabled=""
                                                value="{{$emailAttachment['document_file_name']}}">
                                        <a href="{{asset('storage/email_documents/'.$emailAttachment['document_name'])}}" target="_blank"
                                            class="btn btn-default btn-sm"><i
                                                    class="fas fa-cloud-download-alt"></i></a>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <!--/span-->
                </div>
        
        
                <div class="row">
                    <div class="form-group col-md-8">
                        <label class="control-label">{{__('language.Message')}}</label>
                        <textarea class="textarea_editor form-control summernote" id="textarea" name="message"
                        placeholder="{{__('language.Enter')}} {{__('language.text')}} ..."
                        required rows="10">{{$emailTemplate->message}}</textarea>
                    </div>
                    <div class="form-group col-md-3">
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
                    <i class="d-block d-sm-none" data-feather='check-circle'></i><span class="d-none d-sm-inline"> Update</span></button>
                <button class="btn btn-outline-warning ml-1 waves-effect waves-float waves-light" type="button" id="cancelBtn"
                onclick="window.location.href='@if(isset($locale)){{url($locale.'/email-templates')}} @else {{url('en/email-templates')}} @endif'">
                    <i class="d-block d-sm-none" data-feather='x-circle'></i><span class="d-none d-sm-inline"> Cancel</span>
                </button>
            </div>
            </div>
            </div>
        </form>


        @section('page-script')
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
            $('.remove_field').on('click', function () {
                var task_id = $(this).attr('id');
                $.ajax({
                    type: 'get',
                    url: '/en/delete/task_template/document',
                    data: {
                        'id': task_id
                    },
                    success: function (taskDocumentId) {
                        var id = taskDocumentId;
                        $('#' + id).parent().remove();
                        $('#taskDocumentDeleted').modal('toggle');
                    }
                });
            });
        </script>

            <script>
                function appendPlaceholder(elem) {
                    //var htmlStr = "<p>#"+id+"</p>";
                    var id = $(elem).attr("placeholder");
                    console.log(id);
                    $("#textarea").summernote('insertText', id);
                    // use 'insertText' instead of 'code' to append the id instead
                }
            </script>

            @endsection

@stop