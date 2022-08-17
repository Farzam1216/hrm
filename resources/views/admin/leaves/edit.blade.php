@extends('layouts.admin')
@section('title','Edit Leave')
@section('heading')
<!-- Content Header (Page header) -->
<div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0 text-dark">{{__('language.Edit')}}</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#"> {{__('language.Attendance')}}</a></li>
                <li class="breadcrumb-item">{{__('language.My')}} {{__('language.Leaves')}}</li>
                <li class="breadcrumb-item active">{{__('language.Edit')}}</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        
      <div class="row justify-content-end">
		
            <div class="col-12">
                    <button type="button" class="btn btn-info btn-rounded m-t-10 float-right" onclick="window.location.href='@if(isset($locale)){{url($locale.'/my-leaves')}} @else {{url('en/my-leaves')}} @endif'">{{__('language.Back')}}</button>
            </div>
      </div>
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
    

@stop
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-outline-info">
                <div class="card-body">
                    <form class="form-horizontal" action="@if(isset($locale)){{url($locale.'/leave/update',$leave->id)}} @else {{url('en/leave/update',$leave->id)}} @endif" method="post">
                        {{csrf_field()}}
                        <div class="form-body">
                            <h3 class="box-title">{{__('language.Update')}} {{__('language.Leave')}}</h3>
                            <hr class="m-t-0 m-b-40">
                            <!--/row-->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="control-label text-right col-md-3">{{__('language.Leave')}} {{__('language.Type')}}</label>
                                        <div class="col-md-9">
                                            <select class="form-control custom-select" name="leave_type">
                                                @foreach($leave_types as $leave_type)
                                                    <option @if($leave->leave_type == $leave_type->id)selected @endif value="{{$leave_type->id}}">{{$leave_type->name}} ({{$leave_type->amount}})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="control-label text-right col-md-3">{{__('language.Line Manager')}}</label>
                                        <div class="col-md-9">
                                            <input type="hidden" name="line_manager" value="{{isset($line_manager->id) ? $line_manager->id : ''}}">
                                            <input type="text" class="form-control" value="{{isset($line_manager->id) ? $line_manager->firstname. '' .$line_manager->lastname : ''}}" disabled>
                                        </div>
                                    </div>
                                </div>
                                <!--/span-->

                                <!--/span-->
                            </div>
                            <!--/row-->

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="control-label text-right col-md-3">{{__('language.From')}} {{__('language.Date')}}</label>
                                        <div class="col-md-9">
                                            <input type='date' class="form-control" name="datefrom" value="{{Carbon\Carbon::parse($leave->datefrom)->format('Y-m-d')}}" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="control-label text-right col-md-3">{{__('language.To')}} {{__('language.Date')}}</label>
                                        <div class="col-md-9">
                                            <input type='date' class="form-control" name="dateto" value="{{Carbon\Carbon::parse($leave->dateto)->format('Y-m-d')}}"/>
                                        </div>
                                    </div>
                                </div>

                                <!--/span-->
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="control-label text-right col-md-3">{{__('language.Back up')}}/ {{__('language.Point of Contact')}}:</label>
                                        <div class="col-md-9">
                                            <select class="form-control custom-select" name="point_of_contact">
                                                @foreach($employees as $employee)
                                                    @if(Auth::user()->id != $employee->id)
                                                    <option  @if($leave->employee_id == $employee->id) selected @endif value={{$employee->id}}>{{$employee->firstname}} {{$employee->lastname}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="control-label text-right col-md-3">CC {{__('language.To')}}</label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" name="cc_to" id="cc_to" value="{{$leave->cc_to}}">
                                        </div>
                                    </div>
                                </div>
                                <!--/span-->
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                  <div class="form-group row">
                                      <label class="control-label text-right col-md-3">{{__('language.Subject')}}</label>
                                      <div class="col-md-9">
                                          <input type="text" class="form-control" name="subject" value="{{$leave->subject}}">
                                      </div>
                                  </div>
                              </div>
                              <!--/span-->
                              <div class="col-md-6">
                                  <div class="form-group row">
                                      <label class="control-label text-right col-md-3">{{__('language.Description')}}</label>
                                      <div class="col-md-9">
                                          <input type="text" class="form-control" name="description" value="{{$leave->description}}">
                                      </div>
                                  </div>
                              </div>
                              <!--/span-->
                            </div>
                            <hr>
                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-offset-3 col-md-9">
                                                <button type="submit" class="btn btn-success">{{__('language.Update')}} {{__('language.Leave')}}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            $(function () {
                $('#datefrom').datetimepicker({
                    format: "YYYY-MM-DD"
                });
                $('#dateto').datetimepicker({
                    format: "YYYY-MM-DD"
                });
            });
        });
    </script>
@stop