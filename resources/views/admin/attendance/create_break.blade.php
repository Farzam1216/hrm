@extends('layouts.admin')
@section('title','Add Attendance')
@section('heading')
<!-- Content Header (Page header) -->
<div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0 text-dark"> {{__('language.Add')}} {{__('language.Attendance')}}</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">{{__('language.Attendance')}}</a></li>
                <li class="breadcrumb-item active"> {{__('language.Add')}} {{__('language.Attendance')}}</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
       
      <div class="row justify-content-end">
		
            <div class="col-12">
        <button type="button" onclick="window.location.href='@if(isset($locale)){{url($locale.'/leave/admin-create')}} @else {{url('en/leave/admin-create')}} @endif'"
                class="btn btn-info btn-rounded m-t-10 float-right"><i class="fa fa-plus"></i> {{__('language.Add')}} {{__('language.Employee')}} {{__('language.Leave')}}
        </button>
            </div>
      </div>
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
@stop
@section('content')
    <div class="row">
        <div class="col-lg-12 card">
            <div class="card-body card-outline-info">
                <div style="margin-top: 10px;margin-right: 10px">
                    <button type="button" class="btn  btn-info float-right"
                            onclick="window.location.href='@if(isset($locale)){{url($locale.'/attendance/timeline')}} @else {{url('en/attendance/timeline')}} @endif'">{{__('language.Back')}}
                    </button>
                    @if(isset($attendance_summary))
                        <a style="margin-left: 10px" data-toggle="modal"
                           data-target="#confirm-delete{{ $attendance_summary->id }}"
                           class="btn btn-danger float-left text-white">{{__('language.Delete')}}</a>
                    @endif
                </div>
                @if(isset($attendance_summary))
                    <div class="modal fade" id="confirm-delete{{$attendance_summary->id}}" tabindex="-1" role="dialog"
                         aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="@if(isset($locale)){{url($locale.'/attendance/delete',$attendance_summary->id)}} @else {{url('en/attendance/delete',$attendance_summary->id)}} @endif" method="post">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="att_date" value="{{$attendance_summary->date}}">
                                    <input type="hidden" name="att_employee_id"
                                           value="{{$attendance_summary->employee_id}}">
                                    <div class="modal-header">
                                        {{__('language.Are you sure you want to delete Attendance Of?')}}
                                    </div>
                                    <div class="modal-header">
                                        <h4> @foreach($employees as $emp)
                                                @if($emp_id == $emp->id) {{$emp->firstname}} {{$emp->lastname}} @endif
                                            @endforeach</h4>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">{{__('language.Cancel')}}
                                        </button>
                                        <button type="submit" class="btn btn-danger btn-ok">{{__('language.Delete')}}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="card-body">
                    <form class="form-horizontal" action="{{route('attendance.storeAttendanceSummaryToday')}}"
                          method='POST'>
                        {{csrf_field()}}
                        <div class="form-body">
                            <h3 class="box-title">{{__('language.Create')}} {{__('language.CheckIn')}}/{{__('language.CheckOut')}}</h3>
                            <hr class="m-t-0 m-b-40">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="control-label text-right col-md-3">{{__('language.Select')}} {{__('language.Name')}} {{__('language.Here')}}</label>
                                        <div class="col-md-9">
                                            <select class="form-control custom-select" name="employee_id">
                                                <option value="0">{{__('language.Select')}} {{__('language.Employee')}}</option>
                                                @foreach($employees as $emp)
                                                    <option value="{{$emp->id}}"
                                                            @if($emp_id == $emp->id) selected @endif >{{$emp->firstname}} {{$emp->lastname}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="control-label text-right col-md-3">{{__('language.Select')}} {{__('language.Date')}}</label>
                                        <div class="col-md-9">
                                            <input type="date" class="form-control date" name="date"
                                                   value="{{$current_date}}">
                                            <span class="input-group-addon">
            <span class="glyphicon glyphicon-calendar"></span>
        </span>
                                        </div>
                                    </div>
                                </div>
                                <!--/span-->
                            </div>
                            <!--/row-->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="control-label text-right col-md-3">{{__('language.Time')}} {{__('language.In')}}</label>
                                        <div class="col-md-9">
                                            <input type="datetime-local" class="form-control" name="time_in"
                                                   value="{{isset($attendance_summary['first_timestamp_in']) ? date('Y-m-d\TH:i',strtotime($attendance_summary['first_timestamp_in'])): ''}}">
                                        </div>
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-6">
                                    <div class="form-group row ">
                                        <label class="control-label text-right col-md-3">{{__('language.Time')}} {{__('language.Out')}}</label>
                                        <div class="col-md-9">
                                            <input type="datetime-local" class="form-control" name="time_out"
                                                   value="{{isset($attendance_summary['last_timestamp_out']) && $attendance_summary['last_timestamp_out']!=""  ? date('Y-m-d\TH:i',strtotime($attendance_summary['last_timestamp_out'])): '' }}">
                                        </div>
                                    </div>
                                </div>
                                <!--/span-->
                            </div>
                        </div>
                        <div class="form-actions">
                            <hr>
                            <div class="col-md-12">
                                <div class="row">

                                    <div class="col-md-offset-3 col-md-12">
                                        <button type="submit" class="btn btn-info float-right">{{__('language.Create')}}</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6"></div>
                        </div>
                    </form>
                    <br>
                    <h3 class="box-title">{{__('language.Details')}}</h3>
                    <hr>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group row">
                                <label class="control-label text-right col-md-6">{{__('language.Is')}} {{__('language.Delay')}}? :</label>
                                <div class="col-md-4 ">
                                    @if(isset($attendance_summary->is_delay)) {{$attendance_summary->is_delay}} @endif
                                </div>
                            </div>
                        </div>

                        <!--/span-->
                        <div class="col-md-4">
                            <div class="form-group row ">
                                <label class="control-label text-right col-md-6">{{__('language.Total')}} {{__('language.Hours')}}:</label>
                                <div class="col-md-3 ">
                                    @if(isset($attendance_summary->total_time))
                                        {{gmdate('H:i', floor(number_format(($attendance_summary->total_time/60), 2, '.', '') * 3600))}}
                                    @endif
                                </div>
                            </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-4">
                            <div class="form-group row ">
                                <label class="control-label text-right col-md-6">{{__('language.Total')}} {{__('language.Checks')}}:</label>
                                <div class="col-md-4 ">
                                    @if($attendances->count()) {{$attendances->count()}} @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <h3 class="box-title">{{__('language.Breaks')}}<a class="btn btn-info text-white float-right" data-toggle="modal"
                                                   data-target="#popup" data-original-title="Edit"> <i
                                    class="fas fa-plus text-white"></i> {{__('language.Add')}} {{__('language.Break')}}</a>
                    </h3>
                    <hr>
                    {{--///Dialog Box/// --}}
                    <div class="modal fade" id="popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                         aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="@if(isset($locale)){{url($locale.'/attendance/store-break')}} @else {{url('en/attendance/store-break')}} @endif" method='POST'>
                                    {{ csrf_field() }}
                                    <div class="modal-header" style="margin-right: 20px;">
                                        {{__('language.Add')}} {{__('language.Break')}}
                                    </div>
                                    <div class="modal-body">
                                        <div class="container-fluid">
                                            <select class="form-control custom-select" name="employee_id" hidden>
                                                <option value="0">{{__('language.Select')}} {{__('language.Employee')}}</option>
                                                @foreach($employees as $emp)
                                                    <option value="{{$emp->id}}"
                                                            @if($emp_id == $emp->id) selected @endif >{{$emp->firstname}} {{$emp->lastname}}</option>
                                                @endforeach
                                            </select>
                                            <div class="form-group">
                                                <label for="date">{{__('language.Date')}}</label><br>
                                                <div class="input-group date1">
                                                    <input type="date" class="form-control date" name="date"
                                                           value="{{$current_date}}">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                    <label for="time_in">{{__('language.Break')}} {{__('language.Start')}}</label>
                                                    <div class="input-group timepicker">
                                                        <input type="datetime-local" class="form-control"
                                                               name="break_start" value="{{$current_time}}">
                                                    </div>
                                            </div>
                                            <div class="form-group">
                                            <label for="time_out">{{__('language.Break')}} {{__('language.End')}}</label>
                                                    <div class="input-group timepicker">
                                                        <input type="datetime-local" class="form-control"
                                                               name="break_end" value="">
                                                    </div>
                                                </div>
                                            <div class="form-group">
                                                    <label for="time_out">{{__('language.Comment')}}</label>
                                                    <div class="input-group timepicker">
                                                        <input type="text" class="form-control" name="comment" value="">
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">{{__('language.Cancel')}}
                                        </button>
                                        <button type="submit" class="btn btn-success create-btn" id="add-btn">{{__('language.Add')}}
                                            {{__('language.Break')}}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-push-12">
                        <div class="table">
                            <table id="demo-foo-addrow" class="table m-t-30 table-hover contact-list" data-paging="true"
                                   data-paging-size="7">
                                <thead>
                                @if($attendances->count() > 0)
                                    <tr>
                                        <th>{{__('language.Break')}} {{__('language.Start')}}</th>
                                        <th>{{__('language.Break')}} {{__('language.End')}}</th>
                                        <th>{{__('language.Comment')}}</th>
                                        <th>{{__('language.Actions')}}</th>
                                    </tr>
                                </thead>

                                <tbody>
                                @foreach($attendances as $att)
                                    <tr>
                                        <td>
                                            @if($att->timestamp_break_start != '')
                                                {{ Carbon\Carbon::parse($att->timestamp_break_start)->format('h:i a')}}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($att->timestamp_break_end != '')
                                                {{ Carbon\Carbon::parse($att->timestamp_break_end)->format('h:i a') }}
                                            @endif
                                        </td>
                                        <td>{{$att->comment}}</td>
                                        <td class="text-nowrap">
                                            <a class="btn btn-info btn-sm" data-toggle="modal"
                                               data-target="#edit{{ $att->id }}"> <i
                                                        class="fas fa-pencil-alt text-white "></i></a>
                                            <div class="modal fade" id="edit{{ $att->id }}" tabindex="-1" role="dialog"
                                                 aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form action="@if(isset($locale)){{url($locale.'/attendance/update-break?id='.$att->id)}} @else {{url('en/attendance/update-break?id='.$att->id)}} @endif"
                                                              method="post">
                                                            {{ csrf_field() }}
                                                            <div class="modal-header">
                                                                {{__('language.Edit')}} {{__('language.Break')}}
                                                            </div>
                                                            <div class="modal-body">

                                                                <div>
                                                                    <label for="date">{{__('language.Date')}}</label></br>
                                                                    <div class="input-group">
                                                                        <input type="date" class="form-control"
                                                                               name="date" value="{{$att->date}}"/>
                                                                    </div>
                                                                </div>

                                                                <label for="time">{{__('language.Break')}} {{__('language.Start')}}</label>
                                                                <div class="input-group">
                                                                    <input type="datetime-local" class="form-control"
                                                                           name="break_start"
                                                                           value="{{date('Y-m-d\TH:i',strtotime($att->timestamp_break_start))}}"/>
                                                                </div>

                                                                <label for="time">{{__('language.Break')}} {{__('language.End')}}</label>
                                                                <div class="input-group">
                                                                    <input type="datetime-local" class="form-control"
                                                                           name="break_end"
                                                                           @if($att->timestamp_break_end!=null) value="{{date('Y-m-d\TH:i',strtotime($att->timestamp_break_end))}}" @endif />
                                                                </div>
                                                                <label for="time">{{__('language.Comment')}}</label>
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control"
                                                                           name="comment" value="{{$att->comment}}">
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-default"
                                                                        data-dismiss="modal">{{__('language.Cancel')}}
                                                                </button>
                                                                <button type="submit" class="btn btn-success btn-ok">
                                                                    {{__('language.Update')}}
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            {{--//Edit--}}
                                            <a class="btn btn-danger btn-sm" data-toggle="modal"
                                               data-target="#confirm-delete{{ $att->id }}"> <i
                                                        class="fas fa-window-close text-white"></i></a>

                                            <div class="modal fade" id="confirm-delete{{ $att->id }}" tabindex="-1"
                                                 role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form action="{{ route('attendance.deleteBreakChecktime' , ['id' => $att->id] )}}"
                                                              method="post">
                                                            {{ csrf_field() }}
                                                            <div class="modal-header">
                                                                {{__('language.Are you sure you want to delete this Break check')}}
                                                            </div>
                                                            <div class="modal-content">
                                                                <strong>{{ $att->in_out }}
                                                                    on {{Carbon\Carbon::parse($att->timestamp_break_start)->format('Y-m-d h:i a')}}
                                                                    ?</strong>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-default"
                                                                        data-dismiss="modal">{{__('language.Cancel')}}
                                                                </button>
                                                                <button type="submit" class="btn btn-danger btn-ok">
                                                                    {{__('language.Delete')}}
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach @else
                                    <tr>
                                        <p>
                                            {{__('language.Time Not Added yet')}}
                                        </p>
                                    </tr>
                                @endif

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script type="text/javascript">
            $(document).ready(function () {
                $('#delay').hide();

                $('.date').on("change", function (e) {
                            @if($emp_id)
                    var url = '/{!! $locale !!}/attendance/create-break/{{$emp_id}}/' + $(this).val();
                            @else
                    var url = '/{!! $locale !!}/attendance/create-break/0/' + $(this).val();
                    @endif
                    if (url) {
                        window.location = url;
                    }
                    return false;
                });

                $(".custom-select").on('change', function (e) {
                    var url = '/{!! $locale !!}/attendance/create-break/' + $(this).val() + '/{{$current_date}}';

                    if (url) {
                        window.location = url;
                    }
                    return false;
                });
            });
        </script>
        <script type="text/javascript">
            $(function () {
                $('#datetimepicker2').datetimepicker({
                    locale: 'ru'
                });
            });
        </script>
    @endpush
@stop
