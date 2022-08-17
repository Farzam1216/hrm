@extends('layouts.admin')
@section('title','My Attendance')
@section('heading')
<!-- Content Header (Page header) -->
<div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0 text-dark">{{__('language.My')}} {{__('language.Attendance')}}</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">{{__('language.Attendance')}}</a></li>
                <li class="breadcrumb-item active">{{__('language.My')}} {{__('language.Attendance')}}</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->
@stop
@section('content')
<div class="row">
        <div class="col-md-4 col-sm-12 col-12">
          <div class="info-box">
            <span class="info-box-icon bg-success "><i class="fas fa-user-tie"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">{{__('language.Average Attendance')}}</span>
              <span class="info-box-number">{{$averageAttendance}}%</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-4 col-sm-12 col-12">
          <div class="info-box">
            <span class="info-box-icon bg-info"><i class="fas fa-bolt"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">{{__('language.Average')}} {{__('language.Arrival')}}</span>
              <span class="info-box-number">{{$averageArrival}}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-4 col-sm-12 col-12">
          <div class="info-box">
            <span class="info-box-icon bg-info"><i class="fas fa-pencil-ruler"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">{{__('language.Average')}} {{__('language.Hours')}}</span>
              <span class="info-box-number">{{$averageHours}} HRS</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        
      </div>
      <!-- /.row -->
      <div class="row"> <div class="col-md-12 col-sm-12 col-12">
            <div class="info-box">
              <span class="info-box-icon bg-warning "><i class="fas fa-user-clock"></i></span>
  
              <div class="info-box-content">
                <span class="info-box-text">{{__('language.Attendance')}} </span>
                <span class="info-box-number"><span class="">{{$present}}&nbsp; {{__('language.Present')}} </span> &nbsp; | &nbsp; 
 <span class="">{{$leaveCount}} &nbsp;{{__('language.Leaves')}} </span> &nbsp; | &nbsp; 
                 <span class="">{{$absent}}&nbsp; {{__('language.Absent')}}</span>
              </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
      </div>
   
    <div class="row">
        <div class="col-lg-12">

                <div class="card">
                        <div class="card-header row">
                                <div class="col-md-6">
                              <h3 class="card-title col-sm-12 p-3"><i class="fas fa-calender"></i>{{__('language.Calendar')}}</h3>
                                </div>
                                   
                              <div class="dropdown p-1  col-md-6 ">
                                    <span style="float: right;">
                                                    <select class="form-control" id="employee">
                                                <option value={{Auth::user()->id}} @if(Auth::user()->type=='remote')Selected @endif>{{__('language.Select')}} {{__('language.Employee')}}</option>
                                                @foreach($employees as $employee)
                                                            <option value="{{$employee->id}}"  @if($employeeId==$employee->id) Selected @endif>{{$employee->firstname}} {{$employee->lastname}}</option>
                                                        @endforeach
                                            </select>
                                            </span>
                               
                               
                                    </div>
                                   
                            </div>
                            
                        <!-- /.card-header -->
    <div class="card-body">
            <div id="kt_calendar" class="fc fc-unthemed fc-ltr">
                </div>
                <div id="calendarModal" class="modal fade">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                {{__('language.Send')}} {{__('language.Correction')}} {{__('language.Message')}}
                            </div>
                            <div class="modal-header">
                                <h4 id="modalTitle" class="modal-title"></h4>
                            </div>
                            </br>
                            <div>
                                <form action="@if(isset($locale)){{url($locale.'/attendance/correction-email')}} @else {{url('en/attendance/correction-email')}} @endif" method="post">
                                    {{csrf_field()}}
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label">{{__('language.To')}}</label>
                                            <input  type="email" name="email" value="hr@glowlogix.com" class="form-control" hidden>
                                            <input  type="email" value="hr@glowlogix.com" class="form-control" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">CC {{__('language.to')}} {{__('language.Parent')}} {{__('language.Employee')}}</label>

                                            <select class="form-control" name="line_manager_email" @foreach($linemanagers as $linemanager) @if($linemanager->line_manager_id == null) disabled @endif @endforeach>
                                                @foreach($linemanagers as $linemanager)
                                                    @if($linemanager->parent_id!=null)
                                                        <option value="{{$linemanager->lineManager->official_email}}">{{$linemanager->lineManager->official_email}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <input id="date" type="text" name="date" hidden>
                                        <div class="form-group">
                                            <label class="control-label">{{__('language.Message')}}</label>
                                            <textarea  name="message" class="form-control"></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-success" >{{__('language.Send')}}</button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">{{__('language.Close')}}</button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            

            <!--end::Portlet-->
          
    </div>
  
    <!-- /.card-body -->
    </div>
    <!-- /.card --> 
           
            </div>
        </div>

        @push('scripts')
        
            <script type="text/javascript">
                $('#kt_calendar').fullCalendar({
                    header: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'month,agendaWeek,agendaDay,listWeek'
                    },
                    businessHours: {
                        // days of week. an array of zero-based day of week integers (0=Sunday)
                        dow: [{{ $dow }}],
                    },
                    displayEventTime:false,
                    editable: false,
                    eventLimit: true, // allow "more" link when too many events
                    navLinks: true,
                    // defaultDate: moment(),
                    events: {!! $events !!},
                    firstDay: 1,
                    eventClick:function(event, jsEvent, view) {
                        if (event.title.search('Absent') !== -1){
                            $('#modalTitle').html(event.title);
                            $('#modalTitle').append(event.date);
                            $('#date').val(event.date);
                            $('#calendarModal').modal();
                        }
                    },
                    eventRender: function(event, element) {
                        if (element.hasClass('fc-day-grid-event')) {
                            element.data('content', event.description);
                            element.data('placement', 'top');
                        } else if (element.hasClass('fc-time-grid-event')) {
                            element.find('.fc-title').append('<div class="fc-description">' + event.description + '</div>');
                        } else if (element.find('.fc-list-item-title').lenght !== 0) {
                            element.find('.fc-list-item-title').append('<div class="fc-description">' + event.description + '</div>');
                        }
                    }
                });
                $("#employee").change(function(e){
                    var url = "/{!! $locale !!}/attendance/myAttendance/" + $(this).val();

                    if (url) {
                        window.location = url;
                    }
                    return false;
                });
            </script>
        @endpush
        @stop
