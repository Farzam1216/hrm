@extends('layouts.admin')
@section('title','Manage Salary')
@section('heading')
<!-- Content Header (Page header) -->
<div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0 text-dark">{{__('language.Manage Salary')}}</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">{{__('language.Salary')}}</a></li>
                <li class="breadcrumb-item active">{{__('language.Manage Salary')}}</li>
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

                <div class="card">
                        <!-- /.card-header -->
                        <div class="card-body">
                                @if($employees->count() > 0)
                          <table id="kt_table_1" class="table table-bordered table-striped table-checkable">
                            <thead>
                            <tr>
                                    <td>#</td>
                                <th>{{__('language.Name')}}</th>
                                <th>{{__('language.Template')}}</th>
                                <th>{{__('language.Actions')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                                    @foreach($employees as $key =>$employee)
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td>{{$employee->firstname}} {{$employee->lastname}}</td>
                                            <td>@if(isset($employee->salaryTemplate))
                                                    {{$employee->salaryTemplate->template_name}}
                                                    @if($employee->salaryTemplate->deduction==1)
                                                        (Absent Deduction Enabled)
                                                        @else
                                                        (Absent Dduction Disabled)
                                                    @endif
                                                @else
                                                    {{__('language.Please')}} {{__('language.Assign')}} {{__('language.Template')}} {{__('language.For')}} {{__('language.Salary')}}
                                                @endif
                                            </td>
                                            <td class="text-nowrap">
                                                <a class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="modal"
                                                   data-target="#edit{{ $employee->id }}"><i class="fas fa-edit"></i></a>
                                            </td>
                                            <div class="modal fade" id="edit{{ $employee->id }}" tabindex="-1" role="dialog"
                                                 aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form action="{{route('assign_template')}}"
                                                              method="post">
                                                            {{ csrf_field() }}
                                                            <div class="modal-header">
                                                                {{__('language.Assign')}} {{__('language.Salary')}} {{__('language.Template')}}
                                                            </div>
                                                            <div class="modal-header">
                                                                <h5>{{$employee->firstname}} {{$employee->lastname}}</h5>
                                                            </div>
                                                            </br>
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label class="control-label">{{__('language.Template')}}</label>
                                                                    <select name="template" class="form-control">
                                                                        @foreach($templates as $template)
                                                                            <option value="">{{__('language.Choose')}} {{__('language.Template')}}</option>
                                                                            <option value="{{$template->id}}"
                                                                                    @if($employee->salary_template==$template->id)Selected @endif>{{$template->template_name}}
                                                                            </option>
                                                                        @endforeach
                                                                        <input type="hidden" value="{{$employee->id}}"
                                                                               name="employee_id">
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-default"
                                                                        data-dismiss="modal">{{__('language.Cancel')}}
                                                                </button>
                                                                <button type="submit" class="btn btn-success btn-ok">{{__('language.Assign')}}
                                                                    {{__('language.Template')}}
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </tr>
                                    @endforeach
                                    </tbody>
                          </table>
                          @else
                        <p class="text-center"> {{__('language.No')}} {{__('language.Salary')}} {{__('language.Template')}} {{__('language.Found')}}</p>
                @endif
                      <!--end: Datatable -->
                        </div>
                        <!-- /.card-body -->
                      </div>
                      <!-- /.card -->
        </div>
    </div>

    <div class="modal fade" id="create" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{route('salary_template.add')}}" method="post">
                    {{ csrf_field() }}
                    <div class="modal-header">
                        {{__('language.Add')}} {{__('language.Template')}}
                    </div>
                    </br>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label">{{__('language.Name')}}</label>
                            <input type="text" name="name" placeholder="{{__('language.Enter Name Here')}}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{__('language.Basic Salary')}}</label>
                            <input type="number" name="salary" placeholder="{{__('language.Enter Amount Here')}}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{__('language.Status')}}</label>
                            <select name="status" class="form-control">
                                <option value="1">{{__('language.Active')}}</option>
                                <option value="0">{{__('language.UnActive')}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{__('language.Cancel')}}</button>
                        <button type="submit" class="btn btn-info btn-ok">{{__('language.Add')}} {{__('language.Salary')}} {{__('language.Template')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


@stop