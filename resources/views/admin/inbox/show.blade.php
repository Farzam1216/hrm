@extends('layouts.admin')
@section('title','Inbox')
@section('heading')
<style>
  .comment-form {
    width: 960px;
    margin: 0 auto;
  }

  ​ .action_btn {
    display: inline-block;
    margin: 0 auto;
  }

  ​
</style>
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">{{__('language.Inbox')}}</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">{{__('language.Settings')}}</a></li>
          <li class="breadcrumb-item active">{{__('language.Inbox')}}</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div><!-- /.content-header -->
@stop
@section('content')

<div class="row">
  <div class="col-md-3">

    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Folders</h3>

        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
          </button>
        </div>
      </div>
      <div class="card-body p-0">
        <ul class="nav nav-pills flex-column">
          <li class="nav-item active">
            <a href="@if(isset($locale)){{route('inbox.index',['lang'=>$locale])}} @else {{route('inbox.index',['lang'=>'en'])}} @endif" class="nav-link">
              <i class="fas fa-inbox"></i> Inbox
              <span class="badge bg-primary float-right">{{$notificationCount}}</span>
            </a>
          </li>
          <li class="nav-item active">
            <a href="@if(isset($locale)){{route('inbox.completed',['lang'=>$locale])}} @else {{route('inbox.completed',['lang'=>'en'])}} @endif" class="nav-link">
              <i class="fas fa-check"></i> Completed
              <span class="badge bg-primary float-right"></span>
              <span class="badge bg-primary float-right">{{$notificationCompleted}}</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="@if(isset($locale)){{route('inbox.index.trash',['lang'=>$locale,'trash'=>'true'])}} @else {{route('inbox.index.trash',['lang'=>'en','trash'=>'true'])}} @endif" class="nav-link">
              <i class="far fa-trash-alt"></i> Trash
              <span class="badge bg-primary float-right">{{ $employeeTrashedCount }}</span>
            </a>
          </li>
        </ul>
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
  </div>
  <!-- /.col -->
  <div class="col-md-9">
    <div class="card card-primary card-outline">
      <div class="card-header">
        <h3 class="card-title">@if(isset($notification->data['subject'])) {{ $notification->data['subject']}} @endif </h3>
        <h3 class="card-title" style="float: right">@if(array_key_exists('status',$notification->data)) Status:{{ $notification->data['status']}} @endif</h3>
      </div>
      <!-- /.card-header -->
      <div class="card-body p-0">
        <div class="mailbox-read-info">
          <h5>@if(isset($notification->data['body'])){{$notification->data['body']}} @endif
            <span class="mailbox-read-time float-right">@if(isset($notification->created_at)){{$notification->created_at}} @endif</span></h5>
        </div>
        <!-- /.mailbox-read-info -->
        <div class="mailbox-controls with-border text-center">
          <div class="btn-group">
            <button type="button" onclick="
                        if(confirm('Are you sure, You Want to delete this?'))
                            {
                              event.preventDefault();
                              document.getElementById('delete-form-{{ $notification->id }}').submit();
                            }
                            else{
                              event.preventDefault();
                            }" class="btn btn-default btn-sm" data-toggle="tooltip" data-container="body" title="Delete">
              <i class="far fa-trash-alt"></i></button>
            ​
            <form id="delete-form-{{ $notification->id }}" method="post"
              action="@if(isset($locale)){{route('inbox.destroy',['lang'=>$locale,'inbox'=> $notification->id ])}} @else {{route('inbox.destroy',['lang'=>'en','inbox' => $notification->id ])}} @endif"
              style="display: none">
              {{ csrf_field() }}
              {{ method_field('DELETE') }}
            </form>

            </td>
          </div>
          @if ($notification->deleted_at != null)
          <div class="btn-group">
            <button type="button" onclick="
                        if(confirm('Are you sure, You Want to restore this?'))
                            {
                              event.preventDefault();
                              document.getElementById('restore-form-{{ $notification->id }}').submit();
                            }
                            else{
                              event.preventDefault();
                            }" class="btn btn-default btn-sm" data-toggle="tooltip" data-container="body" title="Restore">
              <i class="fas fa-undo"></i></button>
            <form id="restore-form-{{ $notification->id }}" method="get"
              action="@if(isset($locale)){{route('inbox.restore',['lang'=>$locale,'inbox'=> $notification->id ])}} @else {{route('inbox.restore',['lang'=>'en','inbox' => $notification->id ])}} @endif"
              style="display: none">
            </form>

            </td>
          </div>
          @endif
        </div>
        <!-- /.mailbox-controls -->
        <div class="mailbox-read-message">
          <p><b>Hello {{ Auth::user()->firstname}}</b>,</p>
          @if (isset($notification->data['previous_information']) && isset($notification->data['requested_information']))
          <table class="table table-borderless">
            <thead>
              <tr>
                <th scope="col">Previous Information</th>
                <th scope="col">Requested Information</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>
                  <ul class="list-unstyled">
                    @foreach ($notification->data['previous_information'] as $key => $previousInformation)
                    <li><b>{{ucwords($key)}}</b>
                      <ul>
                        <li>{{$previousInformation}}</li>
                      </ul>
                    </li>
                    @endforeach
                  </ul>
                </td>
                <td>
                  <ul class="list-unstyled">
                    @foreach ($notification->data['requested_information'] as $key => $requestedInformation)
                    <li><b>{{ucwords($key)}}</b>
                      <ul>
                        <li>{{$requestedInformation}}</li>
                      </ul>
                    </li>
                    @endforeach
                  </ul>
                </td>
              </tr>
            </tbody>
          </table>
          @else
          <p>@if(isset($notification->data['body_information'])) {{$notification->data['body_information']}} @endif</p>
          @endif
          @if(isset($notification->data['comment'])) <label>Comment:</label>
          <p>{{$notification->data['comment']}}</p>
          @endif
        </div>
      </div>
      @if(!$notification->is_completed && isset($notification->data['ApproveURL']))
      <div class="contianer">
        <form id="statusForm" action="@if(isset($notification->data['ApproveURL'])){{ $notification->data['ApproveURL']}}@endif" method="post">
          @csrf
          <textarea name="comment" class="form-control" id="" cols="30" rows="2"></textarea>
          <input type="file"><br>
          <input type="hidden" name="notificationId" value="{{$notification->id}}">
          <span class="mailbox-read-time float-right"><button type="submit" id="" class="btn btn-primary action_btn">Approve</button></span>
          <span class="mailbox-read-time float-right"><button type="submit" id="denyBtn" class="btn btn-primary action_btn">Disapprove</button></span> </form>
      </div>
      @endif
      <!-- /.card-footer -->
    </div>
    <!-- /.card -->
  </div>
  <!-- /.col -->
</div>
<script>
  $("#denyBtn").click(function () { 
    $("#statusForm").prop("action","@if(isset($notification->data['DisapproveURL'])){{ $notification->data['DisapproveURL']}}@endif");
  });
</script>
@endsection