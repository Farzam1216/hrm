@extends('layouts/contentLayoutMaster')
@section('title','Edit Division')
@section('heading')
<!-- Content Header (Page header) -->
<div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0 text-dark">{{__('language.Edit')}} {{__('language.Team')}}</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#"> {{__('language.Employee')}} Mgmt </a></li>
                <li class="breadcrumb-item">{{__('language.Division')}}</li>
                <li class="breadcrumb-item active">{{__('language.Edit')}} {{__('language.Division')}}</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
       
      <div class="row justify-content-end">
		
            <div class="col-12">
    <button type="button" class="btn btn-info btn-rounded m-t-10 float-right"
            onclick="window.location.href='@if(isset($locale)){{url($locale.'/division')}} @else {{url('en/division')}} @endif'">{{__('language.Back')}}
    </button>
            </div>
      </div>
    </div><!-- /.container-fluid -->
  </div>
@stop
@section('content')
    <div class="row">
        <div class="col-lg-12">
                <div class="card">
                        <div class="card-header">
                          <h3 class="card-title">{{__('language.Members')}} {{__('language.Of')}} {{__('language.Division')}} "{{$division_name->name}}"</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                                
                          <table id="kt_table_1" class="table table-bordered table-striped">
                            <thead>
                                    @if(count($division_members) > 0)
                            <tr>
                                    <th>#</th>
                                    <th>{{__('language.Employee')}} {{__('language.Name')}}</th>
                                    <th>{{__('language.Action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                                    @foreach($division_members as $key=> $division_member)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td> {{isset($division_member->id) ? $division_member->employee->firstname . $division_member->employee->lastname : ''}}</td>
                                        <td class="text-nowrap">
                                            <a class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="modal"
                                               data-target="#confirm-delete{{ $division_member->id }}" data-original-title="Close">
                                                <i class="fas fa-trash"></i> </a>
                                            <div class="modal fade" id="confirm-delete{{ $division_member->id }}" tabindex="-1"
                                                 role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form action="@if(isset($locale)){{url($locale.'/division-member/delete',$division_member->id)}} @else {{url('en/division-member/delete',$division_member->id)}} @endif"
                                                              method="post">
                                                            {{ csrf_field() }}
                                                            <div class="modal-header">
                                                                {{__('language.Are you sure you want to delete this Employee')}} {{__('language.From')}} {{__('language.Division')}}?
                                                            </div>
                                                            <div class="modal-header">
                                                                <h4>{{isset($division_member->id) ? $division_member->employee->firstname : ''}}</h4>
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
                                    </tr>
                                @endforeach @else
                                    <tr> {{__('language.No Division Member Found')}}</tr>
                                @endif
                            </tbody>
                          </table>
                        </div>
                        <!-- /.card-body -->
                      </div>
           
        </div>
    </div>

@stop