@extends('layouts.contentLayoutMaster')
@section('title','Candidate Details')
@section('vendor-style')
  <!-- Vendor css files -->
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/editors/quill/katex.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/editors/quill/monokai-sublime.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/editors/quill/quill.snow.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/editors/quill/quill.bubble.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/editors/quill/katex.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/editors/quill/monokai-sublime.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/editors/quill/quill.snow.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/editors/quill/quill.bubble.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/toastr.min.css')) }}">
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Inconsolata&family=Roboto+Slab&family=Slabo+27px&family=Sofia&family=Ubuntu+Mono&display=swap" rel="stylesheet">
@endsection
@section('page-style')
{{-- Page Css files --}}
<link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('css/base/pages/app-user.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-quill-editor.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('css/base/plugins/extensions/ext-component-toastr.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('css/base/pages/app-email.css')) }}">
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">

<style>
    .checkedStar{
        color: orange;
    }
    .setHover:hover{
        color:orange;
    }
</style>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-4">
            <!-- Profile Image -->
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <img class="profile-user-img img-fluid img-circle" width="150px;" src="{{asset($candidate->avatar)}}"
                             alt="User profile picture">
                    </div>
                    <br>
                    <h4 class=" text-center">{{$candidate->name}} {{$candidate->fname}}</h4>
                    <p class="text-muted text-center">{{__('language.Applied')}} {{__('language.on')}}
                        {{ $candidate->created_at->format('M d, Y') }}
                        ({{$difference}})</p>
                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Rating</b>
                            <!-- Rating Stars Box -->
                            <div class='rating-stars text-center float-right'>
                                <ul id='stars' style="
                                display: flex;
                                list-style-type: none;">
                                    <li class='star @if($currentRating) @if($currentRating->status >=1) selected @endif @endif'
                                        title='Poor' data-value='1'>
                                        <i class='fa fa-star fa-fw setHover'></i>
                                    </li>
                                    <li class='star @if($currentRating)  @if($currentRating->status >=2) selected @endif @endif'
                                        title='Fair' data-value='2'>
                                        <i class='fa fa-star fa-fw setHover'></i>
                                    </li>
                                    <li class='star @if($currentRating) @if($currentRating->status >=3) selected @endif @endif'
                                        title='Good' data-value='3'>
                                        <i class='fa fa-star fa-fw setHover'></i>
                                    </li>
                                    <li class='star @if($currentRating) @if($currentRating->status >=4) selected @endif @endif'
                                        title='Excellent' data-value='4'>
                                        <i class='fa fa-star fa-fw setHover'></i>
                                    </li>
                                    <li class='star @if($currentRating) @if($currentRating->status >=5) selected @endif @endif'
                                        title='WOW!!!' data-value='5'>
                                        <i class='fa fa-star fa-fw setHover'></i>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <b>Email</b> <a class="float-right" href="#">{{$candidate->email}}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Employment Status</b> <a class="float-right">{{$candidate->job_status}}</a>
                        </li>
                    </ul>
                    <select class="form-control custom-select dropdown " id="status" tabindex="1" name="status">
                        <div class="dropdown-menu">
                            <option class="dropdown-item" value="new">{{__('language.New')}}</option>
                            <optgroup label="Active Statuses">
                                <option class="dropdown-item" text-selection="Reviewed" @if($currentStatus)
                                @if($currentStatus->status == 'reviewed') selected @endif
                                        @endif
                                        value="reviewed">Reviewed
                                </option>
                                <option class="dropdown-item" text-selection="Schedule Phone Screen" @if($currentStatus)
                                @if($currentStatus->status == 'schedulephone') selected @endif
                                        @endif
                                        value="schedulephone">Schedule Phone Screen
                                </option>
                                <option class="dropdown-item" @if($currentStatus) @if($currentStatus->status == 'phone')
                                selected @endif
                                        @endif
                                        value="phone">Phone Screened
                                </option>
                                <option class="dropdown-item" @if($currentStatus) @if($currentStatus->status ==
                                'scheduleinterview') selected @endif
                                @endif
                                value="scheduleinterview">Schedule Interview
                                </option>
                                <option class="dropdown-item"
                                        @if($currentStatus) @if($currentStatus->status == 'interview')
                                        selected @endif
                                        @endif
                                        value="interview">Interviewed
                                </option>
                                <option class="dropdown-item" @if($currentStatus) @if($currentStatus->status == 'hold')
                                selected @endif
                                        @endif
                                        value="hold">Put on Hold
                                </option>
                                <option class="dropdown-item"
                                        @if($currentStatus) @if($currentStatus->status == 'reference')
                                        selected @endif
                                        @endif
                                        value="reference">Checking References
                                </option>
                            </optgroup>
                            <optgroup label="Not Hired Because..">
                                <option class="dropdown-item"
                                        @if($currentStatus) @if($currentStatus->status == 'notfit')
                                        selected @endif
                                        @endif
                                        value="notfit">Not a Fit
                                </option>
                                <option class="dropdown-item" @if($currentStatus) @if($currentStatus->status ==
                                'declinedoffer') selected @endif
                                @endif
                                value="declinedoffer">Declined Offer
                                </option>
                                <option class="dropdown-item" @if($currentStatus) @if($currentStatus->status ==
                                'notqualified') selected @endif
                                @endif
                                value="notqualified">Not Qualified
                                </option>
                                <option class="dropdown-item" @if($currentStatus) @if($currentStatus->status ==
                                'overqualified') selected @endif
                                @endif
                                value="overqualified">Over Qualified
                                </option>
                                <option class="dropdown-item" @if($currentStatus) @if($currentStatus->status ==
                                'hiredelsewhere') selected @endif
                                @endif
                                value="hiredelsewhere">Hired Elsewhere
                                </option>
                            </optgroup>
                            <option class=" dropdown-item" @if($currentStatus) @if($currentStatus->status == 'hire')
                            selected @endif
                                    @endif
                                    value="hire">
                                <i class="fas fa-check-square"></i>Hire
                            </option>
                    </select>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
            <!-- About Me Box -->
            <div class="card card-primary">
                <div class="card-header">
                    <h4 class="card-title">About</h4>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <strong><i class="fas fa-book mr-1"></i> Education</strong>
                    <p class="text-muted">
                    </p>
                    <hr>
                    <strong><i class="fas fa-map-marker-alt mr-1"></i> Location</strong>
                    <p class="text-muted"></p>
                    <hr>
                    <strong><i class="fas fa-pencil-alt mr-1"></i> Skills</strong>
                    <p class="text-muted">
                    </p>
                    <hr>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link active" href="#info" data-toggle="tab">Candidate
                                info</a></li>
                        <li class="nav-item"><a class="nav-link" href="#notes" data-toggle="tab">Notes</a></li>
                        <li class="nav-item"><a class="nav-link" href="#email" data-toggle="tab">Email</a></li>
                    </ul>
                </div><!-- /.card-header -->
                <div class="card-body">
                    <div class="tab-content">
                        <div class="active tab-pane" id="info">

                            <ul class="nav nav-tabs" id="infoHeader" role="tablist">

                            </ul>
                            <div class="tab-content" id="infoDetails">

                            </div>

                            <h5 style="padding-top: 2%; color: grey">
                                <i>{{__('language.Application Questions for this Job')}}</i></h5>
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group" id="answers">
                                        {{--	<label class="control-label">{{$answer->que_desc}}</label>
                                        <h3 class="form-control" type="text">{{$answer->answer}}</h3>--}}
                                    </div>
                                </div>

                            </div>

                        </div>
                        <!-- /.tab-pane -->
                        
                        
                            <div class="tab-pane" id="notes">
                            <ul class="timeline" id="timeLine">
                                @foreach($status as $state)
                                @if($candidate->id == $state->candidate_id)
                                    @if($state->set_by == null && $state->change_in == null)
                                <li class="timeline-item">
                                    <span class="timeline-point">
                                        <i data-feather="file-text"></i>
                                      </span>
                                  <div class="timeline-event">
                                    <div class="d-flex justify-content-between flex-sm-row flex-column mb-sm-0 mb-1">
                                      <h6>Applied For {{$candidate->job->title}}</h6>
                                      {{--  <span class="timeline-event-time">12 min ago</span>  --}}
                                    </div>
                                    <p> {{date("d-M-Y",strtotime($state->created_at)) }}</p>
                                  </div>
                                </li>
                                @else
                                @if($state->change_in == 'Status')
                                    <li class="timeline-item">
                                        <span class="timeline-point">
                                            <i class="fas fa-comment-alt"></i>
                                          </span>
                                    <div class="timeline-event">
                                      <div class="d-flex justify-content-between flex-sm-row flex-column mb-sm-0 mb-1">
                                        <h6>{{$state->change_in}} changed to {{$statusArray[$state->status]}} by
                                            @foreach($employee as $e)
                                                @if($e->id == $state->set_by)
                                                    {{$e->firstname}}
                                                @endif
                                            @endforeach</h6>
                                        {{--  <span class="timeline-event-time">12 min ago</span>  --}}
                                      </div>
                                      <p> {{date("d-M-Y",strtotime($state->created_at)) }}</p>
                                    </div>
                                  </li>
                                @else 
                                <li class="timeline-item">
                                    <span class="timeline-point">
                                        <i class="fa fa-star"></i>
                                    </span>
                                    <div class="timeline-event">
                                      <div class="d-flex justify-content-between flex-sm-row flex-column mb-sm-0 mb-1">
                                        
                                        <h6 class="timeline-header">{{$state->change_in}} changed to
                                            <div class="rating-stars text-center inline-block"
                                                 style="display:inline-block;">
                                                <ul id="displayHistory" style="display: flex ; list-style-type: none; margin-left:-36px;" class="ratingHistory">
                                                    @for($i=1; $i<=5; $i++)
                                                        <li
                                                                class="star @if($state->status >=$i) checkedStar @endif "
                                                                data-value="{{$i}}"><i
                                                                    class="timeline-rating fa fa-star fa-fw"></i>
                                                        </li>
                                                    @endfor
                                                </ul>
                                            </div>
                                            by
                                            @foreach($employee as $e)
                                                @if($e->id == $state->set_by)
                                                    {{$e->firstname}}
                                                @endif
                                            @endforeach
                                        </h6>
                                        
                                        {{--  <span class="timeline-event-time">12 min ago</span>  --}}
                                      </div>
                                      <p> {{date("d-M-Y",strtotime($state->created_at)) }}</p>
                                    </div>
                                  </li> 
                                @endif
                            @endif
                            @endif
                            @endforeach
                              </ul>

                            <!-- The timeline -->
                           
                        </div>
                        <!-- /.tab-pane -->

                        <div class="tab-pane" id="email">
                            <button type="button" class="btn btn-outline-primary px-5" data-toggle="modal"
                                    data-target="#send-email">{{__('language.New')}} {{__('language.Email')}}</button>
                            <hr>
                            <div class="row">
                                <!-- /.col -->
                                <div class="col-md-12">    
                                    @if($emails != null)
                                        @foreach($emails as $email)
                                            <div class="card card-outline card-warning collapsed-card" id="testing">
                                                <div class="my-3 ml-2 row">
                                                    <div class="col-md-10 col-sm-10">
                                                        <p class="" style="line-height: 10%"><span class="text-muted">From: </span>
                                                            {{$email['email_from']}}
                                                        </p>
                                                        <p class=""><span
                                                                    class="text-muted">To: </span> {{$email['email_to']}}</p>
                                                        <p class="" style="line-height: 10%"><span class="text-muted">Subject:
                                                </span>{{$email['subject']}}
                                                        </p>
                                                        <p class="" id="showMessage"
                                                           style="text-overflow:ellipsis; overflow: hidden; white-space: nowrap ">
                                                            {{strip_tags(str_replace(array('@name', '@lastName', '@jobTitle', '@senderName','@senderDesignation'),
                                                                                   array($candidate->name, $candidate->fname, $candidate->job->title, Auth::user()->firstname, Auth::user()->designation->designation_name ) ,$email['message']))}}
                                                        </p>
                                                    </div>

                                                    <div class="card-tools  text-right col-md-2 col-sm-2 " style="">
                                                        <button type="button" class="btn btn-tool pr-3 text-lg" id="msgBtn"
                                                                data-card-widget="collapse"><i id="messageBtn"
                                                                                               class="fas fa-plus"></i>
                                                        </button>
                                                    </div>
                                                    <!-- /.card-tools -->
                                                    <!-- /.card-header -->
                                                <div class="card-body hidden" id="emailMessage">
                                                    {!! str_replace(array('@name', '@lastName', '@jobTitle',
                                                    '@senderName','@senderDesignation'),
                                                    array($candidate->name, $candidate->fname, $candidate->job->title,
                                                    Auth::user()->firstname, Auth::user()->designation->designation_name ) ,$email['message']) !!}
                                                    @if($email['candidateEmailAttachments'])
                                                        @foreach($email['candidateEmailAttachments'] as $key => $attachments)               
                                                            <div class="form-group d-inline-block">
                                                                <div class="custom-file">
                                                                    <input type="text" disabled=""
                                                                           value="{{$attachments['document_file_name']}}">
                                                                    <a href="{{asset('storage/email_documents/'.$attachments['document_name'])}}" target="_blank"
                                                                       class="btn btn-default btn-sm"><i
                                                                                class="fas fa-cloud-download-alt"></i></a>
                                                                </div>
                                                            </div>
                                                        @endforeach    
                                                    @endif
                                                </div>
                                                <!-- /.card-body -->
                                                </div>
                                                
                                            </div>
                                    @endforeach
                                @endif
                                <!-- /.card -->
                                </div>
                                <!-- /.col -->
                            </div>
                            <div id="send-email" class="modal modal-slide-in update-item-sidebar fade">
                                <div class="modal-dialog sidebar-lg">
                                  <div class="modal-content p-0">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                                    <div class="modal-header mb-1">
                                      <h5 class="modal-title">New Email</h5>
                                    </div>
                                    <div class="modal-body flex-grow-1">
                                      
                                      <div class="tab-content mt-2">
                                        <div class="tab-pane tab-pane-update fade show active" id="tab-update" role="tabpanel">
                                          <form id="candidate-email" action=" @if(isset($locale)){{url($locale.'/candidate/send-email',$candidate->id)}} @else {{url('en/candidate/send-email',$candidate->id)}} @endif"
                                            method="post" class="update-item-form">
                                            {{ csrf_field() }}
                                                <input hidden name="candidateName" value="{{$candidate->name}}">
                                                <input hidden name="candidateLastName" value="{{$candidate->fname}}">
                                                <input hidden name="email" value="{{$candidate->email}}">
                                                <input hidden name="job_id" value="{{$candidate->job_id}}">
                                                <input hidden name="email_sender"
                                                       value="{{Auth::user()->official_email}}">
                                                <input hidden name="email_sender_name"
                                                       value="{{Auth::user()->firstname}} {{Auth::user()->lastname}}">
                                                <input hidden name="senderJob" value="{{Auth::user()->designation}}">
                                            <div class="form-group">
                                                <label class="form-label" for="label">Select a Template</label><span
                                                class="text-danger"> *</span>
                                                <select name="email_template" id="template_change" data-placeholder="Select a Template" class="select2  form-control">
                                                    <option  data-color="badge-light-primary" value="">{{__('language.Select')}}
                                                        {{__('language.Template')}}</option>
                                                    @foreach($allTemplates as $template)
                                                        <option data-color="badge-light-primary" value="{{$template->id}}"
                                                                emailSubject="{{$template->subject}}"
                                                                emailMessage="{{$template->message}}"
                                                                emailMailable="{{$template->mailable}}"
                                                                emailAttachments="{{$template->emailAttachments}}">
                                                            {{$template->template_name}}</option>
                                                    @endforeach
                                                   
                                                </select>
                                              </div>
                                            <div class="form-group">
                                              <label class="form-label" for="title">Email Subject</label><span
                                              class="text-danger"> *</span>
                                              <input type="text" id="email_subject" name="subject" class="form-control" placeholder="{{__('language.Enter')}} {{__('language.Subject')}}...." required/>
                                            </div>
                                            <div class="form-group">
                                              <label class="form-label">Message</label><span
                                              class="text-danger"> *</span>
                                              <div id="full-wrapper" >
                                                <div id="full-container">
                                                    <textarea name="message" id="textarea" placeholder="Message" row="10" col="5" class="form-control summernote"></textarea>
                                                </div>
                                                    </div>
                                                  </div>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                              <div class="d-flex flex-wrap">
                                                <button class="btn btn-primary mr-1" type="submit" >Send Email</button>
                                                <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Cancel</button>
                                              </div>
                                            </div>
                                          </form>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>

                            
                            


                        </div>
                        <!-- /.tab-pane -->
                    </div>
                    <!-- /.tab-content -->
                </div><!-- /.card-body -->
            </div>
            <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

    
      @section('vendor-script')
  <!-- Vendor js files -->
  <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/editors/quill/katex.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/editors/quill/highlight.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/editors/quill/quill.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>>
@endsection
    @section('page-script')
    {{-- Page js files --}}
    <script src="{{ asset(mix('js/scripts/forms/form-quill-editor.js')) }}"></script>
    <script type="text/javascript" src="https://unpkg.com/pdfobject@2.2.6/pdfobject.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
    <script src="{{ asset(mix('js/scripts/forms/validations/form-candidate-email.js'))}}"></script>
        
   
       <script>
        var count = 0;
        $(document).ready(function () {
            count++;
            /* 1. Visualizing things on Hover - See next part for action on click */
            $('#stars li').on('mouseover', function () {
                var onStar = parseInt($(this).data('value'), 10); // The star currently mouse on
                // Now highlight all the stars that's not after the current hovered star
                $(this).parent().children('li.star').each(function (e) {
                    if (e < onStar) {
                        $(this).addClass('hover');
                    } else {
                        $(this).removeClass('hover');
                    }
                });
            }).on('mouseout', function () {
                $(this).parent().children('li.star').each(function (e) {
                    $(this).removeClass('hover');
                });
            });
            /* 2. Action to perform on click */
            $('#stars li').on('click', function () {
                 let current_datetime = new Date()
                 let formatted_date = current_datetime.getDate() + "-" + (current_datetime.toLocaleString('default', { month: 'short' })) + "-" + current_datetime.getFullYear()

                var onStar = parseInt($(this).data('value'), 10); // The star currently selected
                var stars = $(this).parent().children('li.star');
                for (i = 0; i < stars.length; i++) {
                    $(stars[i]).removeClass('selected');
                }

                for (i = 0; i < onStar; i++) {
                    $(stars[i]).addClass('selected');
                }
                var ratingValue = parseInt($('#stars li.selected').last().data('value'), 10);
                var candidate ={!! $candidate->id !!};
                var employee = {!!Auth::user()->id!!};
                jQuery.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "get",
                    url: "/updatestatus",
                    data: {
                        'status': ratingValue,
                        'candidate_id': candidate,
                        'change_in': "Rating",
                        'set_by': employee,
                    },
                    cache: false,
                    success: function (data) {
                        var employeename;
                        var name = {!! $employee!!}
                        $.each(name, function (key, value) {

                            if (value['id'] == employee) {
                                employeename = value['firstname'];

                            }
                        });
                        
                       
                    $('#timeLine').prepend('<li class="timeline-item"><span class="timeline-point"><i class="fa fa-star"></i></span> <div class="timeline-event"><div class="d-flex justify-content-between flex-sm-row flex-column mb-sm-0 mb-1"><h6 class="timeline-header"> ' + data.change_in + ' changed to  <div class="rating-stars text-center inline-block" style="display:inline-block;"> <ul  style="display: flex ; list-style-type:none; margin-left:-36px;" id="addRating" class="addRating' + count + '"></ul> </div> by ' + employeename + '</h6></div><p> ' + formatted_date + ' </p></div></li>');
                    
                            for (i = 1; i <= 5; i++) {
                                if (data.status >= i) {
                                    c = '';

                                } else {
                                    c = 'selected';
                                }

                                $('.addRating' + count).append('<li class="star checkedStar' + c + ' " data-value="' + i + '"><i class="timeline-rating fa fa-star fa-fw"></i></li>');
                            }
                        count++;
                    }
                });
            });
        });
    </script>

    <script>
        var ans ={!! json_encode($candidateAnswers) !!};
        var count = 0;
        if (ans) {
            $.each(ans, function (key, value) {
                if (value['field'] == 'file') {
                    if (count == 0) {
                        a = 'active';
                    } else {
                        a = '';
                    }
                    var url = "value";
                    $('#infoHeader').append('<li class="nav-item"><a class="nav-link ' + a + '" id="file' + key + '" data-toggle="pill" href="#file' + key + '" role="tab" aria-controls="file' + key + '" aria-selected="true">' + value['que'] + '</a></li>');
                    $('#infoDetails').append('<div class="tab-pane fade show ' + a + '" id="file' + key + '" role="tabpanel" aria-labelledby="file' + key + '"><div class="pdf" id="filePDF' + key + '" style="height: 40rem; border: 1rem solid rgba(0,0,0,.1);"></div></div>');
                    count++;

                    if (value['answer']) {

                        var id = 'filePDF' + key;
                        //var id = document.querySelector('.pdf').id;
                        var embed = "#" + id;

                        url = value['answer'];
                        $(document).ready(function () {
                            var obj = "{!! asset('/') !!}" + url;
                            PDFObject.embed(obj, embed);
                        });
                    }
                } else {
                    if (value['que']) {
                        $('#answers').append('<label class="control-label">' + value['que'] + '</label><h3 class="form-control" type="text" >' + value['answer'] + '</h3>')
                    } else {

                        	$('#answers').append('<h3>No Related Questions.</h3>');
                    }
                }
            });
        }
    </script>

    <script>
        $('.summernote').summernote({
            height: 200,   //set editable area's height
            codemirror: { // codemirror options
              theme: 'monokai'
            }
          });
    </script>

    <script>
        jQuery(document).ready(function ($) {
            $("#template_change").on('change', function () {
                $('#attachments').empty();
                $("#attachmentsLabel").hide();
                var subject = $("#template_change option:selected").attr('emailSubject');
                var message = $("#template_change option:selected").attr('emailMessage');
                var mailable = $("#template_change option:selected").attr('emailMailable');
                var attachments = $("#template_change option:selected").attr('emailAttachments');
                var name = "{{$candidate->name}}";
                var lastname = "{{$candidate->fname}}";
                var jobTitle = "{{$candidate->job->title}}";
                var senderName = "{{Auth::user()->firstname}}";
                var senderDesignation = "{{Auth::user()->designation->designation_name}}";
                message = message.replace('@name', name);
                message = message.replace('@lastName', lastname);
                message = message.replace('@jobTitle', jobTitle);
                message = message.replace('@senderName', senderName);
                message = message.replace('@senderDesignation', senderDesignation);
                $("#email_subject").val(subject);
                $("#textarea").summernote('code', message);
                if (attachments) {
                    console.log(attachments+" hawww");

                    $.each(JSON.parse(attachments), function (key, value) {
                        $("#attachmentsLabel").show();
                        doc = value['document_name'];
                        var obj = "{!! asset('storage/email_documents//') !!}" + doc;

                        $('#attachments').append('<div class="form-group d-inline-block"><div class="custom-file"><input type="text" disabled="" value="' + value['document_file_name'] + '"><a href="' + obj + '" target="_blank" class="btn btn-default btn-sm"><i class="fas fa-cloud-download-alt"></i></a></div></div>');

                    });
                }
            });
        });
    </script>
    {{--		Reset textarea on modal close.--}}
    <script>
        $(document).ready(function () {
            $('.modal').on('hidden.bs.modal', function () {
                $(this).find('form')[0].reset();
                $('#textarea').summernote('reset');
                $("#attachmentsLabel").hide();
                $("#attachments").empty();

            });
        });
    </script>

    <script>
        $('#msgBtn').on('click', function () {
            btn = $(this);
            //alert( JSON.stringify(btn));
            if ($('#messageBtn').hasClass('fa-minus')) {
                document.getElementById("messageBtn").classList.add('fa-plus');
                document.getElementById("messageBtn").classList.remove('fa-minus');
                $('#showMessage').fadeIn();
                $('#emailMessage').fadeOut();
                

            } else if ($('#messageBtn').hasClass('fa-plus')) {
                document.getElementById("emailMessage").classList.remove('hidden');
                document.getElementById("messageBtn").classList.add('fa-minus');
                document.getElementById("messageBtn").classList.remove('fa-plus');
               
                $('#emailMessage').fadeIn();
                $('#showMessage').fadeOut();
            }
        }
    );
    </script>
    <script>
        $(document).ready(function () {
            $('#textID').summernote({
                height: 150
            });
        });
    </script>

    <script>
        $(document).ready(function () {
            $('.hire').on('click', function () {
                $(this).removeClass("buttonClassA");
                $(this).addClass("buttonClassB");

            });
        });
    </script>


    <script type="text/javascript">
        $(document).ready(function () {
            statusArray = {
                'reviewed': 'Reviewed',
                'schedulephone': 'Schedule Phone Screen',
                'phone': 'Phone Screened',
                'scheduleinterview': 'Schedule Interview',
                'interview': 'Interviewed',
                'hold': 'Put on Hold',
                'reference': 'Checking References',
                'notfit': 'Not a Fit',
                'declinedoffer': 'Declined Offer',
                'notqualified': 'Not Qualified',
                'overqualified': 'Over Qualified',
                'hiredelsewhere': 'Hired Elsewhere',
                'hire': 'Hire'
            };

            $('#status').on('change', function () {
                var value = $(this).val();
                var candidate =
                        {!! $candidate->id !!}
                var employee =
                        {!!Auth::user()->id!!}
                var count = 0;
                //	var name={!!Auth::user()->firstname!!}

                console.log(employee);
                let current_datetime = new Date()
              
                let formatted_date = current_datetime.getDate() + "-" + (current_datetime.toLocaleString('default', { month: 'short' })) + "-" + current_datetime.getFullYear()


                jQuery.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "get",
                    url: "/updatestatus",
                    data: {
                        'status': value,
                        'candidate_id': candidate,
                        'change_in': "Status",
                        'set_by': employee,
                    },
                    cache: false,
                    success: function (data) {
                        var employeename;
                        var name = {!! $employee!!}
                        $.each(name, function (key, value) {

                            if (value['id'] == employee) {
                                employeename = value['firstname'];

                            }
                        });
                        $status = statusArray[data.status];
                        
                        $('#timeLine').prepend('<li class="timeline-item"><span class="timeline-point"><i class="fas fa-comment-alt"></i></span><div class="timeline-event"><div class="d-flex justify-content-between flex-sm-row flex-column mb-sm-0 mb-1"><h6>' + data.change_in + ' changed to ' + $status +  ' by ' + employeename + '</h6></div><p>'+ formatted_date +' </p></div></li>');
                                
                        count++;
                    }
                });

            });
        });

    </script>
    @endsection

@stop