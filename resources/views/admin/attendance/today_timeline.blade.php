@extends('layouts.admin')
@section('title','Today"s Attendance')
@section('heading')
<!-- Content Header (Page header) -->
<div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0 text-dark"> {{__("language.Today")}} {{__('language.Attendance')}}</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">{{__('language.Attendance')}}</a></li>
                <li class="breadcrumb-item active"> {{__("language.Today")}} {{__('language.Attendance')}}</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->
@stop
@section('content')





                <div class="row">
                        <div class="col-md-3 col-sm-6 col-12">
                          <div class="info-box">
                            <span class="info-box-icon bg-success "><i class="fas fa-user-tie"></i></span>
              
                            <div class="info-box-content">
                              <span class="info-box-text">{{__('language.Present')}}</span>
                              <span class="info-box-number">{{$present}}</span>
                            </div>
                            <!-- /.info-box-content -->
                          </div>
                          <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
                        <div class="col-md-3 col-sm-6 col-12">
                          <div class="info-box">
                            <span class="info-box-icon bg-danger"><i class="fas fa-bolt"></i></span>
              
                            <div class="info-box-content">
                              <span class="info-box-text">{{__('language.Absent')}}</span>
                              <span class="info-box-number">{{$absent}}</span>
                            </div>
                            <!-- /.info-box-content -->
                          </div>
                          <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
                        <div class="col-md-3 col-sm-6 col-12">
                          <div class="info-box">
                            <span class="info-box-icon bg-info"><i class="fas fa-pencil-ruler"></i></span>
              
                            <div class="info-box-content">
                              <span class="info-box-text">{{__('language.Leaves')}}</span>
                              <span class="info-box-number">{{$leavesCount}}</span>
                            </div>
                            <!-- /.info-box-content -->
                          </div>
                          <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
                        <div class="col-md-3 col-sm-6 col-12">
                          <div class="info-box">
                            <span class="info-box-icon bg-warning "><i class="fas fa-user-clock"></i></span>
              
                            <div class="info-box-content">
                              <span class="info-box-text">{{__('language.Delays')}}</span>
                              <span class="info-box-number">{{$delays}}</span>
                            </div>
                            <!-- /.info-box-content -->
                          </div>
                          <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->
     
    <div class="row">
        <div class="col-lg-12">

                <div class="card">
                                             <!-- /.card-header -->
                        <div class="card-body table-responsive">
                                @if(count($employees) > 0)
                                <h3 class=""><span class="float-right input-sm"><input id="selectDate" value="{{$today}}" class="form-control"
                                    type="date" name="date"></span></h3>
                          <table id="kt_table_1" class="table table-bordered table-striped table-checkable">
                            <thead>
                            <tr>
                                    <th>{{__('language.Name')}}</th>
                                    <th>{{__('language.Designation')}}</th>
                                    <th>{{__('language.Branch')}}</th>
                                    <th>{{__('language.Time')}} {{__('language.in')}}</th>
                                    <th>{{__('language.Time')}} {{__('language.Out')}}</th>
                                    <th>{{__('language.Total')}} {{__('language.Time')}}</th>
                                    <th>{{__('language.Delay')}}</th>
                                    <th>{{__('language.Actions')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                                    @foreach($employees as $employee)
                                    <tr>
                                        <td>{{$employee['firstname']}} {{$employee['lastname']}}</td>
                                        <td>{{$employee['designation']}}</td>
                                        <td>{{isset($employee['location']) ? $employee['location']['name'] : ''}}</td>
                                        <td>
                                            @if(
                                           isset($employee['attendanceSummary'][0]) &&
                                           $employee['attendanceSummary'][0]['first_timestamp_in'] != ''
                                                )
                                                {{isset($employee['attendanceSummary'][0]) ? Carbon\Carbon::parse($employee['attendanceSummary'][0]['first_timestamp_in'])->format('h:i a') : ''}}</td>
                                        @endif
                                        @foreach($employeeLeave as $key=>$leave)
                                            @if( $employee->id==$key)
                                                <p class="text-white badge badge-warning  font-weight-bold">{{__('language.On')}} {{__('language.Leave')}}</p>
                                            @endif
                                        @endforeach
                                        @if(!isset($employee['attendanceSummary'][0]) && !in_array($employee->id,$employeeLeave))
                                            <p class="text-white badge badge-danger  font-weight-bold">{{__('language.Absent')}}</p>
                                        @endif
                                        <td>
                                            @if(
                                                isset($employee['attendanceSummary'][0]) &&
                                                $employee['attendanceSummary'][0]['last_timestamp_out'] != ''
                                            )
                                                {{Carbon\Carbon::parse($employee['attendanceSummary'][0]['last_timestamp_out'])->format('h:i a')}}
                                            @else
                                            @endif
                                        </td>
        
                                        <td>
                                            @if(
                                               isset($employee['attendanceSummary'][0]) &&
                                               $employee['attendanceSummary'][0]['last_timestamp_out'] != ''
                                           )
                                                {{isset($employee['attendanceSummary'][0]) ? gmdate('H:i', floor(number_format(($employee['attendanceSummary'][0]['total_time'] / 60), 2, '.', '') * 3600))  : ''}}
                                            @endif
                                        </td>
                                        <td>{{isset($employee['attendanceSummary'][0]) ? $employee['attendanceSummary'][0]['is_delay'] : ''}}</td>
                                        <td class="text-nowrap">
                                            <a class="btn btn-sm btn-clean btn-icon btn-icon-md"
                                               href="@if(isset($locale)){{url($locale.'/attendance/create-break/'.$employee['id'].'/'.$today)}} @else {{url('en/attendance/create-break/'.$employee['id'].'/'.$today)}} @endif"
                                               data-toggle="tooltip" data-placement="top" title="" data-original-title="Add Attendance"> <i class="fas fa-plus"></i></a>
                                            <a class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="modal"
                                               data-target="#popup{{ $employee['id'] }}" data-original-title="Edit"> <i
                                                        class="la la-edit"></i></a>
                                            {{--///Dialog Box/// --}}
                                            <div class="modal fade" id="popup{{ $employee['id'] }}" tabindex="-1" role="dialog"
                                                 aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form action="{{route('attendance.storeAttendanceSummaryToday')}}"
                                                              method='POST'>
                                                            {{ csrf_field() }}
                                                            <div class="modal-header" style="margin-right: 20px;">
                                                                {{__('language.Adding attendance for Employee')}}:
                                                            </div>
                                                            <div class="modal-header">
                                                                <h4>{{$employee['firstname']}} {{$employee['lastname']}}</h4>
                                                            </div>
        
                                                            <div class="modal-body">
                                                                <div class="container-fluid">
                                                                    <div class="col-md-14">
                                                                        <label for="date">{{__("language.Today")}} {{__('language.Date')}}</label><br>
                                                                        <div class="input-group date1">
                                                                            <input type="hidden" name="employee_id"
                                                                                   value="{{$employee['id']}}"/>
                                                                            <input type="date" id="selectCurrentDate"
                                                                                   class="form-control" name="date"
                                                                                   value="{{isset($employee['attendanceSummary'][0]) ? $employee['attendanceSummary'][0]['date']: $today}}"/>
                                                                            <span class="input-group-addon">
                                                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                                                </span>
                                                                        </div>
                                                                    </div>
                                                                    <br>
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <label for="time_in">{{__('language.Time')}} {{__('language.In')}}</label>
                                                                            <div class="input-group">
                                                                                <input type="datetime-local"
                                                                                       class="form-control" name="time_in"
                                                                                       value="{{isset($employee['attendanceSummary'][0]) ? date('Y-m-d\TH:i',strtotime($employee['attendanceSummary'][0]['first_timestamp_in'])) : ''}}"/>
                                                                                <span class="input-group-addon">
                                                                                    <i class="fas fa-clock-o"
                                                                                       style="font-size:16px"></i>
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-12">
                                                                            <br>
                                                                            <label for="time_out">{{__('language.Time')}} {{__('language.Out')}}</label>
                                                                            <div class="input-group">
                                                                                <input type="datetime-local"
                                                                                       class="form-control" name="time_out"
                                                                                       value="{{isset($employee['attendanceSummary'][0]) && $employee['attendanceSummary'][0]['last_timestamp_out']!=""  ? date('Y-m-d\TH:i',strtotime($employee['attendanceSummary'][0]['last_timestamp_out'])) : ''}}"/>
                                                                                <span class="input-group-addon">
                                                                                    <i class="fas fa-clock-o"
                                                                                       style="font-size:16px"></i>
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-default"
                                                                        data-dismiss="modal">{{__('language.Cancel')}}
                                                                </button>
                                                                <button type="submit" class="btn btn-success create-btn"
                                                                        id="add-btn">{{__('language.Present')}}
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            {{--///End Dialog Box///--}}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                          </table>
                          @else
                          <tr> {{__('language.No Employee Found')}}</tr>
                  @endif
                      <!--end: Datatable -->
                        </div>
                        <!-- /.card-body -->
                      </div>
                      <!-- /.card -->           
        </div>
    </div>
    @push('scripts')
        <script type="text/javascript">
            $("input.zoho").click(function (event) {
                if ($(this).is(":checked")) {
                    $("#div_" + event.target.id).show();
                } else {
                    $("#div_" + event.target.id).hide();
                }
            });
        </script>

        <script type="text/javascript">
            $("input.zoho").click(function (event) {
                if ($(this).is(":checked")) {
                    $("#div_" + event.target.id).show();
                } else {
                    $("#div_" + event.target.id).hide();
                }
            });
        </script>
        <script>
            $(function () {
                $(document).ready(function () {
                    $('#myTable').DataTable({
                        stateSave: true,
                    });
                });
            });
            $(document).ready(function () {
                $("#selectDate").change(function (e) {
                    var url = "{{url($locale.'/attendance/today-timeline')}}/" + $(this).val();

                    if (url) {
                        window.location = url;
                    }
                    return false;
                });
            });
            $(document).ready(function () {
                $("#selectCurrentDate").change(function (e) {
                    var url = "{{url($locale.'/attendance/today-timeline')}}/" + $(this).val();

                    if (url) {
                        window.location = url;
                    }
                    return false;
                });
            });
            setInterval('window.location.reload()', 1200000);
        </script>
        <script src="{{asset('asset/plugins/moment/moment.js')}}"></script>
    @endpush
@stop