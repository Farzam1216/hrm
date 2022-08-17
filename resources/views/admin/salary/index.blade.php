@extends('layouts.admin')
@section('title','Salary')
@section('heading')
<!-- Content Header (Page header) -->
<div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0 text-dark">{{__('language.Salary')}}</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">{{__('language.Payment')}}</a></li>
                <li class="breadcrumb-item active">{{__('language.Salary')}}</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->
    
@stop
@section('content')
    <div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <div class="float-left">
                        <input id="month" class="form-control" value="{{$month}}" type="month">
                    </div>
                </div>
                <div class="kt-portlet__head-toolbar">
                    <div class="kt-portlet__head-toolbar-wrapper">
                        <div class="dropdown dropdown-inline">
                            <button type="button" class="btn btn-brand btn-sm" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                <i class="la la-plus"></i> {{__('language.Tools')}}
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <ul class="kt-nav">
                                    <li class="kt-nav__section kt-nav__section--first">
                                        <span class="kt-nav__section-text">{{__('language.Export Tools')}}</span>
                                    </li>
                                    <li class="kt-nav__item">
                                        <a href="#" class="kt-nav__link" id="export_print">
                                            <i class="kt-nav__link-icon la la-print"></i>
                                            <span class="kt-nav__link-text">{{__('language.Print')}}</span>
                                        </a>
                                    </li>
                                    <li class="kt-nav__item">
                                        <a href="#" class="kt-nav__link" id="export_copy">
                                            <i class="kt-nav__link-icon la la-copy"></i>
                                            <span class="kt-nav__link-text">{{__('language.Copy')}}</span>
                                        </a>
                                    </li>
                                    <li class="kt-nav__item">
                                        <a href="#" class="kt-nav__link" id="export_excel">
                                            <i class="kt-nav__link-icon la la-file-excel-o"></i>
                                            <span class="kt-nav__link-text">{{__('language.Excel')}}</span>
                                        </a>
                                    </li>
                                    <li class="kt-nav__item">
                                        <a href="#" class="kt-nav__link" id="export_csv">
                                            <i class="kt-nav__link-icon la la-file-text-o"></i>
                                            <span class="kt-nav__link-text">{{__('language.CSV')}}</span>
                                        </a>
                                    </li>
{{--                                    <li class="kt-nav__item">--}}
{{--                                        <a href="#" class="kt-nav__link" id="export_pdf">--}}
{{--                                            <i class="kt-nav__link-icon la la-file-pdf-o"></i>--}}
{{--                                            <span class="kt-nav__link-text">{{__('language.PDF')}}</span>--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="kt-portlet__body">
                <!--begin: Datatable -->
                @if(count($employees) > 0)
                    <table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_3">
                        <thead>
                        <tr>
                            <th>{{__('language.Employee')}} {{__('language.Name')}}</th>
                            <th>{{__('language.Basic Salary')}}</th>
                            <th>{{__('language.Bonus')}}</th>
                            <th>{{__('language.Approved Leaves')}}</th>
                            <th>{{__('language.UnApproved Leaves')}}</th>
                            <th>{{__('language.Absent')}}</th>
                            <th>{{__('language.Present')}}</th>
                            <th>{{__('language.Net')}} {{__('language.Payable')}}</th>
                            <th> {{__('language.Actions')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($employees as $employee)
                            <tr>
                                <td>{{$employee->firstname}} {{$employee->lastname}}</td>
                                <td>
                                    @foreach($template as $tmp)
                                        @if($tmp->id==$employee->salary_template)
                                            {{$tmp->basic_salary}}
                                            @else
                                        @endif
                                    @endforeach
                                </td>
                                <td>{{$employee->bonus}}</td>
                                @foreach($ApprovedCount as $key=>$cnt)
                                    @if($key==$employee->id)
                                        <td>{{$cnt}}</td>
                                    @endif
                                @endforeach
                                @foreach($unApprovedCount as $key=>$cnt)
                                    @if($key==$employee->id)
                                        <td>{{$cnt}}</td>
                                    @endif
                                @endforeach
                                @foreach($AbsentCounts as $key=>$AbsentCount)
                                    @if($key==$employee->id)
                                        <td>{{$AbsentCount}}</td>
                                    @endif
                                @endforeach
                                @foreach($presents as $key=>$present)
                                    @if($key==$employee->id)
                                        <td>{{$present}}</td>
                                    @endif
                                @endforeach
                                @foreach($netPayables as $key=>$netPayable)
                                    @if($key==$employee->id)
                                        @if($netPayable < 1)
                                            <td>0</td>
                                        @else
                                            <td>{{$netPayable}}</td>
                                        @endif
                                    @endif
                                @endforeach
                                <td>
                                    <a class="btn btn-clean btn-sm" data-toggle="modal"
                                       data-target="#edit{{ $employee->id }}" data-original-title="Add Bonus"> <i
                                                class="la la-edit"></i></a>
                                </td>
                            </tr>
                            <div class="modal fade" id="edit{{ $employee->id }}" tabindex="-1" role="dialog"
                                 aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="@if(isset($locale)){{url($locale.'/salary/addBonus',$employee->id)}} @else {{url('en/salary/addBonus',$employee->id)}} @endif"
                                              method="post">
                                            {{ csrf_field() }}
                                            <div class="modal-header">
                                                {{__('language.Add Bonus For Employee :')}} {{$employee->firstname}}
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label">{{__('language.Bonus')}} {{__('language.Amount')}}</label>
                                                    <input type="number" name="bonus"
                                                           value="{{old('bonus',$employee->bonus)}}"
                                                           placeholder="{{__('language.Enter Amount Here')}}" class="form-control">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">
                                                    {{__('language.Cancel')}}
                                                </button>
                                                <button type="submit" class="btn btn-success btn-ok">{{__('language.Save')}}</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-center">{{__('language.No Salary Data Found')}}</p> @endif
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="{{asset('asset/js/demo12/pages/crud/datatables/extensions/buttons.js')}}"
                type="text/javascript"></script>
        <script>
            $(document).ready(function () {
                $("#month").change(function (e) {
                    var url = "{{url($locale.'/salary')}}/" + $(this).val();

                    if (url) {
                        window.location = url;
                    }
                    return false;
                });
            });
        </script>
    @endpush
@stop

