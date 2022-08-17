@extends('layouts.admin')
@section('title','TimeLine')
@section('heading')
<!-- Content Header (Page header) -->
<div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0 text-dark">{{__('language.TimeLine')}}</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">{{__('language.Attendance')}}</a></li>
                <li class="breadcrumb-item active">{{__('language.TimeLine')}}</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
       
      <div class="row justify-content-end">
		
            <div class="col-12">
    <button type="button"  onclick="window.location.href='@if(isset($locale)){{url($locale.'/attendance/create-break')}} @else {{url('en/attendance/create-break')}} @endif'" class="btn btn-info btn-rounded m-t-10 float-right"><span class="fas fa-plus" ></span> {{__('language.Add')}} {{__('language.Attendance')}}</button>
            </div>
      </div>
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
    @stop
@section('content')
<div class="row">
    <div class="col-lg-12">
        

            <div class="card">
                    <div class="card-header row">
                            <div class="col-md-6">
                          <h3 class="card-title col-sm-12 p-3"><span style="float: left;"><input id="myTextBox" value="{{ Carbon\Carbon::now()->toDateString()}}" class="form-control" type="date" name="date"></span></h3>
                            </div>
                            
                               
                          <div class="dropdown p-1  col-md-6 ">
                                <span style="float: right;  ">
                                        <select class="form-control" id="selectOffice">
                                            <option value="0" @if($branch_id == 0) selected @endif>{{__('language.All')}} {{__('language.Offices')}}</option>
                                            @foreach($office_locations as $office_location)
                                                <option value="{{$office_location->id}}" @if($branch_id == $office_location->id) selected @endif>{{$office_location->name}}</option>
                                            @endforeach
                                        </select>
                                    </span>
                           
                           
                                </div>
                               
                        </div>
                        
                    <!-- /.card-header -->
<div class="card-body">
        <div id="calendar">
            </div>
</div>

<!-- /.card-body -->
</div>
<!-- /.card --> 

        
    </div>
</div>
@push('scripts')
<link href="{{asset('asset/plugins/fullcalendar-3.9.0/fullcalendar.min.css')}}" rel='stylesheet'/>
<link href="{{asset('asset/plugins/fullcalendar-3.9.0/fullcalendar.print.css')}}" rel='stylesheet' media='print'/>
<link href="{{asset('asset/plugins/fullcalendar-3.9.0/scheduler.min.css')}}" rel='stylesheet'/>
<script src="{{asset('asset/plugins/fullcalendar-3.9.0/2.22.2-moment.min.js')}}"></script>
<script src="{{asset('asset/plugins/fullcalendar-3.9.0/fullcalendar.min.js')}}"></script>
<script src="{{asset('asset/plugins/fullcalendar-3.9.0/scheduler.min.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#calendar').fullCalendar({
            themeSystem: 'bootstrap4',
            defaultView: 'timelineMonth',
            weekends: 'Boolean',
            schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
            displayEventTime: false,
            dow: [ 1, 2, 3, 4, 5 ],
            header: {
                left: 'today prev,next',
                center: 'title',
                right: 'timelineDay,timelineWeek,timelineMonth,timelineYear'
            },
            contentHeight:500,
            firstDay: 1,
            slotWidth : 100,
            resourceAreaWidth:300,
            resourceColumns: [
                {
                    labelText: 'Employees',
                    field: 'firstname',
                },
                {
                    field: 'lastname',
                },
            ],
                    eventClick:function(event, jsEvent, view) {
                if (event.title.search('Birthday') !== -1) {
                    // window.location = "{{route('employees')}}/"+event.resourceId + "/" + event.date;
                    // console.log('found');
                }
                if (event.title.search('present') !== -1) {
                    window.location = "/{!! $locale !!}/attendance/create-break/"+event.resourceId + "/" + event.date;
                }
                if (event.title.search('leave') !== -1) {
                    window.location = "{{route('leaves')}}/show/"+event.resourceId;
                }
            },
            resources:{!! $employees !!},
            events:{!! $events !!}
        });
        $("#selectOffice").change(function(e){
            var url = "/{!! $locale !!}/attendance/timeline/" + $(this).val();

            if (url) {
                window.location = url;
            }
            return false;
        });
    });
    $("#myTextBox").on("change paste keyup", function() {
        $('#calendar').fullCalendar('gotoDate', $(this).val());
    });
</script>
@endpush
@stop