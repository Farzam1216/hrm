@extends('layouts.admin')
@section('title','Leave Details')
@section('heading')
<!-- Content Header (Page header) -->
<div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0 text-dark">{{__('language.Leave')}} {{__('language.Details')}}</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">{{__('language.Attendance')}}</a></li>
                <li class="breadcrumb-item">{{__('language.Leaves')}}</li>
                <li class="breadcrumb-item active">{{__('language.Leave')}} {{__('language.Details')}}</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

@stop
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-outline-info">
                <div class="card-body">
                        <div class="form-body">
                            <h3 class="box-title">{{__('language.Details')}}</h3>
                            <hr class="m-t-0 m-b-40">
                            <!--/row-->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="control-label text-right col-md-3">{{__('language.Leave')}} {{__('language.Type')}}</label>
                                        <div class="col-md-9">
                                            @if(isset($leave->leaveType))
                                                {{$leave->leaveType->name}}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="control-label text-right col-md-3">{{__('language.Leave')}} {{__('language.Days')}}</label>
                                        <div class="col-md-9">
                                            {{$leave_days}}
                                        </div>
                                    </div>
                                </div>
                                <!--/span-->
                            </div>
                            <!--/row-->

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="control-label text-right col-md-3">{{__('language.From')}} {{__('language.Date')}}</label>
                                        <div class="col-md-9">
                                            {{Carbon\Carbon::parse($leave->datefrom)->format('Y-m-d')}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="control-label text-right col-md-3">{{__('language.To')}} {{__('language.Date')}}</label>
                                        <div class="col-md-9">
                                            {{Carbon\Carbon::parse($leave->dateto)->format('Y-m-d')}}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="control-label text-right col-md-3">{{__('language.Line Manager')}}</label>
                                        <div class="col-md-9">
                                            @if(isset($leave->lineManager))
                                                {{$leave->lineManager->firstname}}
                                                {{$leave->lineManager->lastname}}
                                            @endif
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="control-label text-right col-md-3">CC {{__('language.To')}}</label>
                                        <div class="col-md-9">
                                            {{$leave->cc_to}}
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
                                             @if(isset($leave->pointOfContact))
                                                {{$leave->pointOfContact->firstname}}
                                                {{$leave->pointOfContact->lastname}}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="control-label text-right col-md-3">{{__('language.Subject')}}</label>
                                        <div class="col-md-9">
                                            {{$leave->subject}}
                                        </div>
                                    </div>
                                </div>
                                <!--/span-->
                            </div>
                            <div class="row">

                              <!--/span-->
                              <div class="col-md-6">
                                  <div class="form-group row">
                                      <label class="control-label text-right col-md-3">{{__('language.Description')}}</label>
                                      <div class="col-md-9">
                                        {{$leave->description}}
                                      </div>
                                  </div>
                              </div>
                              <!--/span-->
                            </div>
                            <hr>
                        </div>
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