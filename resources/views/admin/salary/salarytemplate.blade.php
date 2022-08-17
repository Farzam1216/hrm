@extends('layouts.admin')
@section('title','Salary Templates')
@section('heading')
<!-- Content Header (Page header) -->
<div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0 text-dark">{{__('language.Salary Templates')}}</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">{{__('language.Salary')}}</a></li>
                <li class="breadcrumb-item active">{{__('language.Salary Templates')}}</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        
    <div class="row justify-content-end">
		
            <div class="col-2">
    <button type="button" class="btn btn-info  btn-rounded m-t-10 float-right" data-toggle="modal" data-target="#create">
        <i class="fas fa-plus"></i> {{__('language.Add Template')}}
    </button>
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
                        <!-- /.card-header -->
                        <div class="card-body">
                                @if($templates->count() > 0)
                          <table id="kt_table_1" class="table table-bordered table-striped table-checkable">
                            <thead>
                            <tr>
                                                                    <td>#</td>
                                    <th>{{__('language.Name')}}</th>
                                    <th>{{__('language.Basic Salary')}}</th>
                                    <th>{{__('language.Absent Deduction')}}</th>
                                    <th>{{__('language.Status')}}</th>
                                    <th>{{__('language.Actions')}}</th>
                                
                            </tr>
                            </thead>
                            <tbody>
                                    @foreach($templates as $key =>$template)
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td>{{$template->template_name}}</td>
                                            <td>{{$template->basic_salary}}</td>
                                            <td>@if($template->deduction==1)
                                                    <span class="btn btn-bold btn-sm btn-font-sm  btn-label-success">{{__('language.Enabled')}}</span>
                                                @else
                                                    <span class="btn btn-bold btn-sm btn-font-sm  btn-label-danger">{{__('language.Disabled')}}</span>
                                                @endif
                                            </td>
                                            <td>@if($template->status==1)
                                                    <span class="btn btn-bold btn-sm btn-font-sm  btn-label-success">{{__('language.Active')}}</span>
                                                @else
                                                    <span class="btn btn-bold btn-sm btn-font-sm  btn-label-danger">{{__('language.InActive')}}</span>
                                                @endif
                                            </td>
                                            <td class="text-nowrap">
                                                                    <span class="dropdown">
                                    <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown"
                                       aria-expanded="true">
                                      <i class="fas fa-ellipsis-h"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a href="" class="dropdown-item" data-toggle="modal"
                                           data-target="#edit{{ $template->id }}"><i
                                                    class="fas fa-edit"></i> {{__('language.Edit Template Details')}}</a>
                                        <a href="" class="dropdown-item" data-toggle="modal"
                                           data-target="#confirm-delete{{ $template->id }}"><i class="fas fa-trash"></i> {{__('language.Delete Template')}}</a>
                                        <a class="dropdown-item" href="@if(isset($locale)){{url($locale.'/manage-salary-template',$template->id)}} @else {{url('en/manage-salary-template',$template->id)}} @endif"><i
                                                    class="fas fa-eye"></i> {{__('language.View Template Details')}}</a>
                                    </div>
                                </span>
                                                <div class="modal fade" id="confirm-delete{{ $template->id }}" tabindex="-1"
                                                     role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form action="@if(isset($locale)){{url($locale.'/salary-template/delete',$template->id)}} @else {{url('en/salary-template/delete',$template->id)}} @endif"
                                                                  method="post">
                                                                {{ csrf_field() }}
                                                                <div class="modal-header">
                                                                    {{__('language.Are you sure you want to delete this template?')}}
                                                                </div>
                                                                <div class="modal-header">
                                                                    <h4>{{ $template->template_name }}</h4>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-default"
                                                                            data-dismiss="modal">{{__('language.Cancel')}}
                                                                    </button>
                                                                    <button type="submit" class="btn btn-danger btn-ok">{{__('language.Delete')}}
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <div class="modal fade" id="edit{{ $template->id }}" tabindex="-1" role="dialog"
                                                 aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form action="@if(isset($locale)){{url($locale.'/salary-template/update',$template->id)}} @else {{url('en/salary-template/update',$template->id)}} @endif"
                                                              method="post">
                                                            {{ csrf_field() }}
                                                            <div class="modal-header">
                                                                {{__('language.Update')}} {{__('language.Salary')}} {{__('Template')}}
                                                            </div>
                                                            </br>
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label class="control-label"> {{__('language.Name')}}</label>
                                                                    <input type="text" name="name"
                                                                           value="{{old('name',$template->template_name)}}"
                                                                           placeholder="{{__('language.Enter Name Here')}}" class="form-control">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="control-label">{{__('language.Basic Salary')}}</label>
                                                                    <input type="number" name="salary"
                                                                           value="{{old('salary',$template->basic_salary)}}"
                                                                           placeholder="{{__('language.Enter Amount Here')}}" class="form-control">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="control-label">{{__('language.Leave Deduction')}}</label>
                                                                    <select name="deduction" class="form-control">
                                                                        <option value="1"
                                                                                @if($template->deduction==1)Selected @endif>
                                                                            {{__('language.Enable')}}
                                                                        </option>
                                                                        <option value="0"
                                                                                @if($template->deduction==0)Selected @endif>
                                                                             {{__('language.Disable')}}
                                                                        </option>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="control-label">{{__('language.Status')}}</label>
                                                                    <select name="status" class="form-control">
                                                                        <option value="1"
                                                                                @if($template->status==1)Selected @endif>{{__('language.Active')}}
                                                                        </option>
                                                                        <option value="0"
                                                                                @if($template->status==0)Selected @endif>
                                                                            {{__('language.InActive')}}
                                                                        </option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-default"
                                                                        data-dismiss="modal">{{__('language.Cancel')}}
                                                                </button>
                                                                <button type="submit" class="btn btn-success btn-ok">{{__('language.Update')}}
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
                <form action="@if(isset($locale)){{url($locale.'/salary-template/add')}} @else {{url('en/salary-template/add')}} @endif" method="post">
                    {{ csrf_field() }}
                    <div class="modal-header">
                        {{__('language.Add')}} {{__('language.Template')}}
                    </div>
                    </br>
                    <div class="col-md-12 modal-body">
                        <div class="form-group">
                            <label class="control-label">{{__('language.Name')}}</label>
                            <input type="text" name="name" placeholder="{{__('language.Enter Name Here')}}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{__('language.Basic Salary')}}</label>
                            <input type="number" name="salary" placeholder="{{__('language.Enter Amount Here')}}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{__('language.Leave Deduction')}}</label>
                            <select name="status" class="form-control">
                                <option value="1">
                                    {{__('language.Enable')}}
                                </option>
                                <option value="0">
                                    {{__('language.Disable')}}
                                </option>
                            </select>
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