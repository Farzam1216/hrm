@extends('layouts.admin')
@section('title','Manage Salary Templates')
@section('heading')
<!-- Content Header (Page header) -->
<div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0 text-dark">{{__('language.Manage')}} {{__('language.Salary')}} {{__('language.Templates')}}</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">{{__('language.Salary')}}</a></li>
                <li class="breadcrumb-item active">{{__('language.Manage')}} {{__('language.Salary')}} {{__('language.Templates')}}</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->
   
    <div class="row justify-content-end">
		
            <div class="col-2">
    <a href="@if(isset($locale)){{url($locale.'/salary-templates')}} @else {{url('en/salary-templates')}} @endif" class="btn btn-info btn-rounded btn-block m-t-10 float-right" >{{__('language.Templates')}}</a>
            </div>
    </div>
    @stop
@section('content')
    <div class="row">
        <div class="col-lg-12">
                <div class="card">
                        
                        <div class="card-body">
                                <div class="dropdown dropdown-inline">
                                        <div class="float-right">
                                            <button type="button" class="btn btn-info btn-rounded m-t-10 float-right" data-toggle="modal" data-target="#create_allowance"><i class="fas fa-plus"></i> {{__('language.Add')}} {{__('language.Allowance')}}</button>
    
                                        </div>
                                </div>
                            
                                        @if($allowances->count() > 0)
                          <table id="kt_table_1" class="table table-bordered table-striped table-checkable">
                            <thead>
                            <tr> <td>#</td>
                                <th>{{__('language.Name')}}</th>
                                <th>{{__('language.Amount')}}</th>
                                <th>{{__('language.Type')}}</th>
                                <th>{{__('language.Actions')}}</th>
                                
                            </tr>
                            </thead>
                            <tbody>
                                    @foreach($allowances as $key =>$allowance)
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td>{{$allowance->allowance_name}}</td>
                                            <td>{{$allowance->amount}}</td>
                                            <td>@if($allowance->type==1)
                                                    <span class="btn btn-bold btn-sm btn-font-sm  btn-label-success">{{__('language.Fixed')}}</span>
                                                @else
                                                    <span class="btn btn-bold btn-sm btn-font-sm  btn-label-danger">{{__('language.Percentage')}}</span>
                                                @endif
                                            </td>
                                            <td class="text-nowrap">
                                                <a class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="modal" data-target="#edit_allowance_{{ $allowance->id }}"   data-original-title="Edit"> <i class="fas fa-edit"></i></a>
                                                <a class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="modal" data-target="#confirm-delete_allowance_{{ $allowance->id }}"  data-original-title="Close"> <i class="fas fa-trash"></i> </a>
                                                <div class="modal fade" id="confirm-delete_allowance_{{ $allowance->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form action="@if(isset($locale)){{url($locale.'/allowance/delete',$allowance->id)}} @else {{url('en/allowance/delete',$allowance->id)}} @endif" method="post">
                                                                {{ csrf_field() }}
                                                                <div class="modal-header">
                                                                    {{__('language.Are you sure you want to delete this Allowance?')}}
                                                                </div>
                                                                <div class="modal-header">
                                                                    <h4>{{ $allowance->allowance_name }}</h4>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">{{__('language.Cancel')}}</button>
                                                                    <button  type="submit" class="btn btn-danger btn-ok">{{__('language.Delete')}}</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <div class="modal fade" id="edit_allowance_{{ $allowance->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form action="@if(isset($locale)){{url($locale.'/allowance/update',$allowance->id)}} @else {{url('en/allowance/update',$allowance->id)}} @endif" method="post">
                                                            {{ csrf_field() }}
                                                            <div class="modal-header">
                                                                {{__('language.Update')}} {{__('language.Allowance')}} {{__('language.Template')}}
                                                            </div>
                                                            </br>
                                                            <div class="col-md-12 modal-body">
                                                                <div class="form-group">
                                                                    <label class="control-label">{{__('language.Name')}}</label>
                                                                    <input  type="text" name="name" value="{{old('name',$allowance->allowance_name)}}" placeholder="{{__('language.Enter Name Here')}}" class="form-control">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="control-label">{{__('language.Amount')}}</label>
                                                                    <input  type="number" name="amount" value="{{old('amount',$allowance->amount)}}" placeholder="{{__('language.Enter Amount Here')}}" class="form-control">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="control-label">{{__('language.Type')}}</label>
                                                                    <select  name="type"  class="form-control">
                                                                        <option value="1" @if($allowance->type==1)Selected @endif>{{__('language.Fixed')}}</option>
                                                                        <option value="0" @if($allowance->type==0)Selected @endif>{{__('language.Percentage')}}</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <input type="hidden" value="{{$template->id}}" name="template_id">
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">{{__('language.Cancel')}}</button>
                                                                <button  type="submit" class="btn btn-success btn-ok">{{__('language.Update')}} {{__('language.Allowance')}} </button>
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
                          <p class="text-center"> {{__('language.No')}} {{__('language.Allowance')}} {{__('language.Found')}}</p>
                  @endif
                      <!--end: Datatable -->
                        </div>
                        <!-- /.card-body -->
                      </div>
                      <!-- /.card -->
            
        </div>
    </div>

    <div class="modal fade" id="create_allowance" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="@if(isset($locale)){{url($locale.'/allowance/add')}} @else {{url('en/allowance/add')}} @endif" method="post">
                    {{ csrf_field() }}
                    <div class="modal-header">
                        {{__('language.Add')}} {{__('language.Allowance')}}
                    </div>
                    </br>
                    <div class="col-md-12 modal-body">
                        <div class="form-group">
                            <label class="control-label">{{__('language.Name')}}</label>
                            <input  type="text" name="name" placeholder="{{__('language.Enter Name Here')}}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{__('language.Amount')}}</label>
                            <input  type="number" name="amount" placeholder="{{__('language.Enter Amount Here')}}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{__('language.Type')}}</label>
                            <select  name="type"  class="form-control">
                                <option value="1">{{__('language.Fixed')}}</option>
                                <option value="0">{{__('language.Percentage')}}</option>
                            </select>
                        </div>
                        <input type="hidden" value="{{$template->id}}" name="template_id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{__('language.Cancel')}}</button>
                        <button  type="submit" class="btn btn-info btn-ok">{{__('language.Add')}} {{__('language.Allowance')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">

                <div class="card">
                        <div class="card-body">
                                <div class="dropdown dropdown-inline">
                                        <div class="float-right">
                                            <button type="button" class="btn btn-info btn-rounded m-t-10 float-right" data-toggle="modal" data-target="#create_deduction"><i class="fas fa-plus"></i> {{__('language.Add')}} {{__('language.Deduction')}}</button>
    
                                        </div>
                                    </div>
                                    @if($deductions->count() > 0)
                          <table id="kt_table_2" class="table table-bordered table-striped table-checkable">
                            <thead>
                            <tr>
                                    <td>#</td>
                                    <th>{{__('language.Name')}}</th>
                                    <th>{{__('language.Amount')}}</th>
                                    <th>{{__('language.Type')}}</th>
                                    <th>{{__('language.Actions')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                                    @foreach($deductions as $key =>$deduction)
                            <tr class="small">
                                    <td>{{$key+1}}</td>
                                    <td>{{$deduction->deduction_name}}</td>
                                    <td>{{$deduction->amount}}</td>
                                    <td>@if($deduction->type==1) <span class="btn btn-bold btn-sm btn-font-sm  btn-label-success">{{__('language.Fixed')}}</span>
                                        @else
                                            <span class="btn btn-bold btn-sm btn-font-sm  btn-label-danger">{{__('language.Percentage')}}</span>
                                        @endif
                                    </td>
                                    <td class="text-nowrap">
                                        <a class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="modal" data-target="#edit_deduction_{{ $deduction->id }}"   data-original-title="Edit"> <i class="fas fa-edit"></i></a>
                                        <a class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="modal" data-target="#confirm_delete_deduction_{{ $deduction->id }}"  data-original-title="Close"> <i class="fas fa-trash"></i> </a>
                                        <div class="modal fade" id="confirm_delete_deduction_{{ $deduction->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="@if(isset($locale)){{url($locale.'/deduction/delete',$deduction->id)}} @else {{url('en/deduction/delete',$deduction->id)}} @endif" method="post">
                                                        {{ csrf_field() }}
                                                        <div class="modal-header">
                                                            {{__('language.Are you sure you want to delete this Deduction?')}}
                                                        </div>
                                                        <div class="modal-header">
                                                            <h4>{{ $deduction->deduction_name }}</h4>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">{{__('language.Cancel')}}</button>
                                                            <button  type="submit" class="btn btn-danger btn-ok">{{__('language.Delete')}}</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <div class="modal fade" id="edit_deduction_{{ $deduction->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="@if(isset($locale)){{url($locale.'/deduction/update',$deduction->id)}} @else {{url('en/deduction/update',$deduction->id)}} @endif" method="post">
                                                    {{ csrf_field() }}
                                                    <div class="modal-header">
                                                        {{__('language.Update')}} {{__('language.Deduction')}}
                                                    </div>
                                                    </br>
                                                    <div class="col-md-12 modal-body">
                                                        <div class="form-group">
                                                            <label class="control-label">{{__('language.Name')}}</label>
                                                            <input  type="text" name="name" value="{{old('name',$deduction->allowance_name)}}" placeholder="{{__('language.Enter Name Here')}}" class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label">{{__('language.Amount')}}</label>
                                                            <input  type="number" name="amount" value="{{old('amount',$deduction->amount)}}" placeholder="{{__('language.Enter Amount Here')}}" class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label">{{__('language.Type')}}</label>
                                                            <select  name="type"  class="form-control">
                                                                <option value="1" @if($deduction->type==1)Selected @endif>{{__('language.Fixed')}}</option>
                                                                <option value="0" @if($deduction->type==0)Selected @endif>{{__('language.Percentage')}}</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" value="{{$template->id}}" name="template_id">
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">{{__('language.Cancel')}}</button>
                                                        <button  type="submit" class="btn btn-success btn-ok">{{__('language.Update')}} {{__('language.Deduction')}} </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </tr>
                            @endforeach
                            </tbody>
                          </table>

                      <!--end: Datatable -->
                      @else
                      <p class="text-center"> {{__('language.No')}} {{__('language.Deduction')}} {{__('language.Found')}}</p>
              @endif
                        </div>
                        <!-- /.card-body -->
                      </div>
                      <!-- /.card -->
        </div>
    </div>

    <div class="modal fade" id="create_deduction" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="@if(isset($locale)){{url($locale.'/deduction/add')}} @else {{url('en/deduction/add')}} @endif" method="post">
                    {{ csrf_field() }}
                    <div class="modal-header">
                        {{__('language.Add')}} {{__('language.Deduction')}}
                    </div>
                    </br>
                    <div class="col-md-12 modal-body">
                        <div class="form-group">
                            <label class="control-label">{{__('language.Name')}}</label>
                            <input  type="text" name="name" placeholder="{{__('language.Enter Name Here')}}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{__('language.Amount')}}</label>
                            <input  type="number" name="amount" placeholder="{{__('language.Enter Amount Here')}}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{__('language.Type')}}</label>
                            <select  name="type"  class="form-control">
                                <option value="1">{{__('language.Fixed')}}</option>
                                <option value="0">{{__('language.Percentage')}}</option>
                            </select>
                        </div>
                        <input type="hidden" value="{{$template->id}}" name="template_id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{__('language.Cancel')}}</button>
                        <button  type="submit" class="btn btn-info btn-ok">{{__('language.Add')}} {{__('language.Deduction')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@stop