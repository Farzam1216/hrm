@extends('layouts.admin')
@section('title','Leave Types')
@section('heading')
<!-- Content Header (Page header) -->
<div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0 text-dark">{{__('language.Leaves')}} {{__('language.Types')}}</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#"> {{__('language.Settings')}}</a></li>
                <li class="breadcrumb-item active">{{__('language.Leaves')}} {{__('language.Types')}}</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        
      <div class="row justify-content-end">
		
            <div class="col-12">
                    <button type="button" class="btn btn-info btn-rounded m-t-10 float-right" data-toggle="modal" data-target="#create"><i class="fas fa-plus"></i> {{__('language.Add')}} {{__('language.Leave')}} {{__('language.Type')}}</button>
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
                        
                        <div class="card-body">
                                @if($leave_types->count() > 0)
                          <table id="kt_table_1" class="table table-bordered table-striped table-checkable">
                            <thead>
                            <tr>
                                    <td>#</td>
                                    <th>{{__('language.Name')}}</th>
                                    <th>{{__('language.Count')}}</th>
                                    <th>{{__('language.Status')}}</th>
                                    <th>{{__('language.Actions')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                                    @foreach($leave_types as $key =>$leave_type)
                                    <tr>
                                            <td>{{$key+1}}</td>
                                            <td>{{$leave_type->name}}</td>
                                            <td>{{$leave_type->count}}</td>
                                            <td>@if($leave_type->status==1)
                                                    <span class="btn btn-bold btn-sm btn-font-sm  btn-label-success">{{__('language.Active')}}</span>
                                                @else
                                                    <span class="btn btn-bold btn-sm btn-font-sm  btn-label-danger">{{__('language.InActive')}}</span>
                                                @endif
                                            </td>
                                            <td class="text-nowrap">
                                                <a class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="modal" data-target="#edit{{ $leave_type->id }}"   data-original-title="Edit"> <i class="fas fa-edit"></i></a>
                                                <a class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="modal" data-target="#confirm-delete{{ $leave_type->id }}"  data-original-title="Close"> <i class="fas fa-trash"></i> </a>
                                                <div class="modal fade" id="confirm-delete{{ $leave_type->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form action="@if(isset($locale)){{url($locale.'/leave-type/delete',$leave_type->id)}} @else {{url('en/leave-type/delete',$leave_type->id)}} @endif" method="post">
                                                                {{ csrf_field() }}
                                                                <div class="modal-header">
                                                                    {{__('language.Are you sure you want to delete this Leave?')}}
                                                                </div>
                                                                <div class="modal-header">
                                                                    <h4>{{ $leave_type->name }}</h4>
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
                                            <div class="modal fade" id="edit{{ $leave_type->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form action="@if(isset($locale)){{url($locale.'/leave-type/update',$leave_type->id)}} @else {{url('en/leave-type/update',$leave_type->id)}} @endif" method="post">
                                                            {{ csrf_field() }}
                                                            <div class="modal-header">
                                                                {{__('language.Update')}} {{__('language.Leave')}} {{__('language.Type')}}
                                                            </div>
                                                        </br>
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label class="control-label">{{__('language.Name')}}</label>
                                                                    <input  type="text" name="name" value="{{old('name',$leave_type->name)}}" placeholder="{{__('language.Enter Name Here')}}" class="form-control">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="control-label">{{__('language.Count')}}</label>
                                                                    <input  type="number" name="amount" value="{{old('count',$leave_type->count)}}" placeholder="{{__('language.Enter Amount Here')}}" class="form-control">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="control-label">{{__('language.Status')}}</label>
                                                                    <select  name="status"  class="form-control">
                                                                        <option value="1" @if($leave_type->status==1)Selected @endif>{{__('language.Active')}}</option>
                                                                        <option value="0" @if($leave_type->status==0)Selected @endif>{{__('language.InActive')}}</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">{{__('language.Cancel')}}</button>
                                                                <button  type="submit" class="btn btn-success btn-ok">{{__('language.Update')}} {{__('language.Leave')}} {{__('language.Type')}}</button>
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
                        <p class="text-center"> {{__('language.No')}} {{__('language.Leave')}} {{__('language.Type')}} {{__('language.Found')}}</p>
                @endif

                      <!--end: Datatable -->
                        </div>
                        <!-- /.card-body -->
            
        </div>
    </div>



                </div>
      
    <div class="modal fade" id="create" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="@if(isset($locale)){{url($locale.'/leave-type/create')}} @else {{url('en/leave-type/create')}} @endif" method="post">
                    {{ csrf_field() }}
                    <div class="modal-header">
                        {{__('language.Create')}} {{__('language.Leave')}}
                    </div>
                    </br>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label">{{__('language.Name')}}</label>
                            <input  type="text" name="name" placeholder="{{__('language.Enter Name Here')}}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{__('language.Count')}}</label>
                            <input  type="number" name="amount" placeholder="{{__('language.Enter Amount Here')}}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{__('language.Status')}}</label>
                            <select  name="status"  class="form-control">
                                <option value="1">{{__('language.Active')}}</option>
                                <option value="0">{{__('language.InActive')}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{__('language.Cancel')}}</button>
                        <button  type="submit" class="btn btn-info btn-ok">{{__('language.Add')}} {{__('language.Leave')}} {{__('language.Type')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


@stop