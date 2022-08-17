@extends('layouts/contentLayoutMaster')
@section('title', 'Inbox')
@section('vendor-style')
  <!-- vendor css files -->
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/editors/quill/katex.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/editors/quill/monokai-sublime.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/editors/quill/quill.snow.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/toastr.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/dragula.min.css')) }}">
@endsection
@section('page-style')
  <!-- Page css files -->
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-quill-editor.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/extensions/ext-component-toastr.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('css/base/pages/app-todo.css')) }}">
@endsection
@section('content')
<div class="container">
  <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title mb-2">Inbox Notifications</h4>
          </div>
          <div class="card-body">
            <!-- Sidebar header start -->
            <div class="chat-fixed-search">
              <div class="d-flex align-items-center w-100">
                <div class="sidebar-profile-toggle">
                </div>
                <div class="input-group input-group-merge ml-1 w-100">
                  <div class="input-group-prepend">
                    <span class="input-group-text round"><i data-feather="search" class="text-muted"></i></span>
                  </div>
                  <input type="text" class="form-control round" id="todo-search" placeholder="Search inbox notifications..." aria-label="Search..." aria-describedby="chat-search" />
                  <div class="input-group-append">
                    <span class="input-group-text round">
                      <div class="dropdown">
                        <a
                          href="javascript:void(0);"
                          class="dropdown-toggle hide-arrow "
                          id="todoActions"
                          data-toggle="dropdown"
                          aria-haspopup="true"
                          aria-expanded="false"
                        >
                          <i data-feather="more-vertical" class="font-medium-2 text-body"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="todoActions">
                          <a class="dropdown-item" id="all" href="javascript:void(0)">All</a>
                          <a class="dropdown-item" id="read" href="javascript:void(0)">Read</a>
                          <a class="dropdown-item" id="unread" href="javascript:void(0)">UnRead</a>
                        </div>
                      </div>
                    </span>
                  </div>
                </div>
              </div>
            </div><br><br>
            <!-- Sidebar header end -->
            <ul id="getData" class="timeline ">
              @foreach(Auth::user()->notifications as $notification)
                <li  class="timeline-item todo-item">
                    @if(isset($notification->data['description']))
                      <a data-toggle="modal" id="readNotification"  getID="{{ $notification['id'] }}" getReadStatus="{{ $notification->read_at }}" data-target="#new-task-modal{{ $notification->id }}">
                    @else
                      <a href="/en{{$notification->data['url']}}" id="readNotification"  getID="{{ $notification['id'] }}" getReadStatus="{{ $notification->read_at }}">
                    @endif    
                    @if($notification->read_at == null)
                      <span class="timeline-point timeline-point-indicator"></span>
                    @else
                      <span class="timeline-point timeline-point-dark timeline-point-indicator"></span>
                    @endif
                    <div class="timeline-event">
                      <div class="d-flex justify-content-between flex-sm-row flex-column mb-sm-0 mb-1">
                        <h6>{{$notification->data['title']}}</h6>
                        @if($notification->read_at == null)
                          <span id="getdate" class="timeline-event-time badge badge-pill badge-light-primary">{{$notification->created_at->diffForHumans()}}</span>
                        @else
                          <span id="getdate" class="timeline-event-time">{{$notification->created_at->diffForHumans()}}</span>
                        @endif
                      </div>
                      <p>{{$notification->data['message']}}</p>
                    </div>
                  </a><hr> 
                </li>
                  <!-- Right Sidebar starts -->
                  @if(isset($notification->data['description']))
                  <div class="modal modal-slide-in sidebar-todo-modal fade" id="new-task-modal{{ $notification->id }}">
                    <div class="modal-dialog sidebar-lg">
                      <div class="modal-content p-0">
                        <form id="form-modal-todo" class="todo-modal needs-validation" novalidate onsubmit="return false">
                          <div class="modal-header align-items-center mb-1">
                            <h5 class="modal-title">Notification Description</h5>
                            <div class="todo-item-action d-flex align-items-center justify-content-between ml-auto">
                              <span class="todo-item-favorite cursor-pointer mr-75"
                                ><i data-feather="x" class="font-medium-2"  data-dismiss="modal"
                                aria-label="Close"></i
                              ></span>
                              </button>
                            </div>
                          </div>
                          <div class="modal-body flex-grow-1 pb-sm-0 pb-3">
                            <div class="action-tags">
                              <div class="form-group">
                                <label for="todoTitleAdd" class="form-label">Title</label>
                                <input
                                  type="text"
                                  id=""
                                  name="todoTitleAdd"
                                  class="new-todo-item-title form-control"
                                  placeholder="Title"
                                  value="{{$notification->data['message']}}"
                                  disabled
                                />
                              </div>
                              <div class="form-group">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" disabled name="" id="" cols="10" rows="10">{{$notification->data['description']}}</textarea>
                              </div>
                              <div class="form-group">
                                <div class="d-flex flex-wrap">
                                  <a class="btn btn-primary mr-1" href="/en{{$notification->data['url']}}" >Go To Page</a>
                                  <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Close</button>
                                </div>
                              </div>
                            </div>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                  @endif
                  <!-- Right Sidebar ends -->
              @endforeach
            </ul>
          </div>
        </div>
      </div> 
    </div>
  </div>
</div>
@endsection
@section('vendor-script')
<!-- vendor js files -->
  <script src="{{ asset(mix('vendors/js/editors/quill/katex.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/editors/quill/highlight.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/editors/quill/quill.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/extensions/dragula.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/extensions/toastr.min.js')) }}"></script>
@endsection
@section('page-script')
  <!-- Page js files -->
  <!-- <script src="{{ asset(mix('js/scripts/pages/app-inbox-notification.js')) }}"></script> -->
  <script>
    $("#readNotification").on('click', function(){
      var id = $(this).attr('getID');
      var status = $(this).attr('getReadStatus');
      var _token = '{{ csrf_token() }}';
      $.ajax({
        url: '{!! URL('/en/inbox') !!}',
        type: 'post',
        dataType: 'json',
        data: {'notificationID': id,'readStatus':status , '_token':_token},
        success: function (data) {
          console.log(data);
        }, error: function (error) {
          console.log(error);
        }
      });
    });
  </script>
  <script>
    $("#unread").on('click', function(){
      var status = "unread";
         $.ajax({
            url: 'inbox/'+status+'/edit',
            type: 'get',
            dataType: 'json',
            success: function (response) {
              const object = response[1];
              $('#getData').empty();
              $.map(response[0], function(val, key) {
                var length = object.length;
                $('#getData').append('<li class="timeline-item todo-item">@if('+val.data.description+ !== null')<a href="/en'+val.data.url+'" id="readNotification"  getID="'+val.id+'" getReadStatus="'+val.read_at+'">@else<a data-toggle="modal" id="readNotification"  getID=" '+val.id+' " getReadStatus="'+val.read_at+'" data-target="#new-task-modal'+val.id+'">@endif <span class="timeline-point timeline-point-indicator"></span><div class="timeline-event"><div class="d-flex justify-content-between flex-sm-row flex-column mb-sm-0 mb-1"><h6>'+val.data.title+'</h6><span class="timeline-event-time badge badge-pill badge-light-primary">'+object[key]+'</span></div><p>'+val.data.message+'</p></div></a><hr></li> @if('+val.data.description+') <div class="modal modal-slide-in sidebar-todo-modal fade" id="new-task-modal'+val.id+'"> <div class="modal-dialog sidebar-lg"> <div class="modal-content p-0"> <form id="form-modal-todo" class="todo-modal needs-validation" novalidate onsubmit="return false"><div class="modal-header align-items-center mb-1"><h5 class="modal-title">Notification Description</h5><div class="todo-item-action d-flex align-items-center justify-content-between ml-auto"><span class="todo-item-favorite cursor-pointer mr-75"><i data-feather="x" class="font-medium-2"  data-dismiss="modal" aria-label="Close"></i></span> </button> </div></div> <div class="modal-body flex-grow-1 pb-sm-0 pb-3"><div class="action-tags"><div class="form-group"><label for="todoTitleAdd" class="form-label">Title</label><input id="" name="todoTitleAdd" class="new-todo-item-title form-control" placeholder="Title" value="'+val.data.message+'" disabled type="text"/> </div><div class="form-group"><label class="form-label">Description</label><textarea class="form-control" disabled name="" id="" cols="10" rows="10">'+val.data.description+'</textarea></div><div class="form-group"><div class="d-flex flex-wrap"><a class="btn btn-primary mr-1" href="/en'+val.data.url+'" >Go To Page</a> <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Close</button></div></div></div></div></form></div> </div> </div> @endif');
              });
            }, error: function (error) {
              console.log(error);
            }
        });
    })
  </script>
  <script>
    $(document).on('click','#read',function(){
      var status = "read";
         $.ajax({
            url: 'inbox/'+status+'/edit',
            type: 'get',
            dataType: 'json',
            success: function (response) {
              const object = response[1];
              $('#getData').empty();
              $.map(response[0], function(val, key) {
                var length = object.length;
                $('#getData').append('<li class="timeline-item todo-item">@if('+val.data.description+ !== null')<a href="/en'+val.data.url+'" id="readNotification"  getID="'+val.id+'" getReadStatus="'+val.read_at+'">@else<a data-toggle="modal" id="readNotification"  getID=" '+val.id+' " getReadStatus="'+val.read_at+'" data-target="#new-task-modal'+val.id+'">@endif <span class="timeline-point timeline-point-dark timeline-point-indicator"></span><div class="timeline-event"><div class="d-flex justify-content-between flex-sm-row flex-column mb-sm-0 mb-1"><h6>'+val.data.title+'</h6><span class="timeline-event-time">'+object[key]+'</span></div><p>'+val.data.message+'</p></div></a><hr></li> @if('+val.data.description+') <div class="modal modal-slide-in sidebar-todo-modal fade" id="new-task-modal'+val.id+'"> <div class="modal-dialog sidebar-lg"> <div class="modal-content p-0"> <form id="form-modal-todo" class="todo-modal needs-validation" novalidate onsubmit="return false"><div class="modal-header align-items-center mb-1"><h5 class="modal-title">Notification Description</h5><div class="todo-item-action d-flex align-items-center justify-content-between ml-auto"><span class="todo-item-favorite cursor-pointer mr-75"><i data-feather="x" class="font-medium-2"  data-dismiss="modal" aria-label="Close"></i></span> </button> </div></div> <div class="modal-body flex-grow-1 pb-sm-0 pb-3"><div class="action-tags"><div class="form-group"><label for="todoTitleAdd" class="form-label">Title</label><input id="" name="todoTitleAdd" class="new-todo-item-title form-control" placeholder="Title" value="'+val.data.message+'" disabled type="text"/> </div><div class="form-group"><label class="form-label">Description</label><textarea class="form-control" disabled name="" id="" cols="10" rows="10">'+val.data.description+'</textarea></div><div class="form-group"><div class="d-flex flex-wrap"><a class="btn btn-primary mr-1" href="/en'+val.data.url+'" >Go To Page</a> <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Close</button></div></div></div></div></form></div> </div> </div> @endif');
              });
            }, error: function (error) {
              console.log(error);
            }
        });
    })
  </script>
  <script>
    $(document).on('click','#all',function(){
      var status = "all";
         $.ajax({
            url: 'inbox/'+status+'/edit',
            type: 'get',
            dataType: 'json',
            success: function (response) {
              const object = response[1];
              $('#getData').empty();
              $.map(response[0], function(val, key) {
                var length = object.length;
                $('#getData').append('<li class="timeline-item todo-item">@if('+val.data.description+ !== null')<a href="/en'+val.data.url+'" id="readNotification"  getID="'+val.id+'" getReadStatus="'+val.read_at+'">@else<a data-toggle="modal" id="readNotification"  getID=" '+val.id+' " getReadStatus="'+val.read_at+'" data-target="#new-task-modal'+val.id+'">@endif <span class="timeline-point timeline-point-info timeline-point-indicator"></span><div class="timeline-event"><div class="d-flex justify-content-between flex-sm-row flex-column mb-sm-0 mb-1"><h6>'+val.data.title+'</h6><span class="timeline-event-time badge badge-pill badge-light-info">'+object[key]+'</span></div><p>'+val.data.message+'</p></div></a><hr></li> @if('+val.data.description+') <div class="modal modal-slide-in sidebar-todo-modal fade" id="new-task-modal'+val.id+'"> <div class="modal-dialog sidebar-lg"> <div class="modal-content p-0"> <form id="form-modal-todo" class="todo-modal needs-validation" novalidate onsubmit="return false"><div class="modal-header align-items-center mb-1"><h5 class="modal-title">Notification Description</h5><div class="todo-item-action d-flex align-items-center justify-content-between ml-auto"><span class="todo-item-favorite cursor-pointer mr-75"><i data-feather="x" class="font-medium-2"  data-dismiss="modal" aria-label="Close"></i></span> </button> </div></div> <div class="modal-body flex-grow-1 pb-sm-0 pb-3"><div class="action-tags"><div class="form-group"><label for="todoTitleAdd" class="form-label">Title</label><input id="" name="todoTitleAdd" class="new-todo-item-title form-control" placeholder="Title" value="'+val.data.message+'" disabled type="text"/> </div><div class="form-group"><label class="form-label">Description</label><textarea class="form-control" disabled name="" id="" cols="10" rows="10">'+val.data.description+'</textarea></div><div class="form-group"><div class="d-flex flex-wrap"><a class="btn btn-primary mr-1" href="/en'+val.data.url+'" >Go To Page</a> <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Close</button></div></div></div></div></form></div> </div> </div> @endif');
              });
            }, error: function (error) {
              console.log(error);
            }
        });
    })
  </script>
@endsection
