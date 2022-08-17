@extends('layouts.admin')
@section('title','Languages')
@section('heading')
<!-- Content Header (Page header) -->
<div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0 text-dark">{{__('language.Languages')}}</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#"> {{__('language.Settings')}}</a></li>
                <li class="breadcrumb-item active">{{__('language.Languages')}}</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        
      <div class="row justify-content-end">
		
            <div class="col-2">
    <button type="button" class="btn btn-info btn-rounded btn-block btn-md m-t-10 float-right" data-toggle="modal" data-target="#create"><i class="fas fa-plus"></i> {{__('language.Add')}} {{__('language.Language')}}</button>
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
                                @if($languages->count() > 0)
                          <table id="kt_table_1" class="table table-bordered table-striped table-checkable">
                            <thead>
                            <tr>
                                    <th>#</th>
                                <th>{{__('language.Languages')}} </th>
                                <th>{{__('language.Short Name')}}</th>
                                <th>{{__('language.Status')}}</th>
                                <th>{{__('language.Actions')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                                    @foreach($languages as $key=>$language)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{$language->name}}</td>
                                <td>{{$language->short_name}}</td>
                                <td>@if($language->status==1)
                                            <span class="btn btn-bold btn-sm btn-font-sm  btn-label-success">{{__('language.Active')}}</span>
                                        @else
                                            <span class="btn btn-bold btn-sm btn-font-sm  btn-label-danger">{{__('language.InActive')}}</span>
                                    @endif
                                </td>
                                <td class="text-nowrap">
                                    <a class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="modal" data-target="#edit{{ $language->id }}"   data-original-title="Edit"> <i class="fas fa-edit"></i></a>
                                    <a class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="modal" data-target="#confirm-delete{{ $language->id }}"  data-original-title="Close"> <i class="fas fa-trash"></i> </a>
                                    <div class="modal fade" id="confirm-delete{{ $language->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="@if(isset($locale)){{url($locale.'/language/delete',$language->id)}} @else {{url('en/language/delete',$language->id)}} @endif" method="post">
                                                    {{ csrf_field() }}
                                                    <div class="modal-header">
                                                        {{__('language.Are you sure you want to delete this Language?')}}
                                                    </div>
                                                    <div class="modal-header">
                                                        <h4>{{ $language->name}}</h4>
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
                                <div class="modal fade" id="edit{{ $language->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="@if(isset($locale)){{url($locale.'/language/update',$language->id)}} @else {{url('en/language/update',$language->id)}} @endif" method="post">
                                                {{ csrf_field() }}
                                                <div class="modal-header">
                                                    {{__('language.Update')}} {{__('language.Language')}}
                                                </div>
                                                </br>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="control-label">{{__('language.Language')}} {{__('language.Name')}}</label>
                                                        <input  type="text" name="name" value="{{old('name',$language->name)}}" placeholder="{{__('language.Enter')}} {{__('language.Language')}} {{__('language.Name')}} {{__('language.Here')}}" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="control-label">{{__('language.Short Name')}}</label>
                                                        <input  type="text" name="short_name" value="{{old('name',$language->short_name)}}" placeholder="{{__('language.Enter')}} {{__('language.Language')}} {{__('language.Short Name')}} {{__('language.Here')}}" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="control-label">{{__('language.Status')}}</label>
                                                        <select name="status" class="form-control">
                                                            <option value="1" @if($language->status==1) selected @endif>{{__('language.Active')}}</option>
                                                            <option value="0" @if($language->status==0) selected @endif>{{__('language.InActive')}}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">{{__('language.Cancel')}}</button>
                                                    <button  type="submit" class="btn btn-success btn-ok">{{__('language.Update')}} {{__('language.Language')}}</button>
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
                          <p class="text-center"> {{__('language.No Language Found')}}</p>
                  @endif
                      <!--end: Datatable -->
                        </div>
                        <!-- /.card-body -->
            
        </div>
    </div>

    <div class="modal fade" id="create" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="@if(isset($locale)){{url($locale.'/language/create')}} @else {{url('en/language/create')}} @endif" method="post">
                    {{ csrf_field() }}
                    <div class="modal-header">
                        {{__('language.Create')}} {{__('language.Language')}}
                    </div>
                </br>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label">{{__('language.Language')}} {{__('language.Name')}}</label>
                            <input  type="text" name="name" placeholder="{{__('language.Enter')}} {{__('language.Language')}} {{__('language.Name')}} {{__('language.Here')}}" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label">{{__('language.Language')}} {{__('language.Short Name')}}</label>
                            <input  type="text" name="short_name" placeholder="{{__('language.Enter')}} {{__('language.Language')}} {{__('language.Short Name')}} {{__('language.Here')}}" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label">{{__('language.Status')}}</label>
                            <select name="status" class="form-control">
                                <option value="1">{{__('language.Active')}}</option>
                                <option value="0">{{__('language.InActive')}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{__('language.Cancel')}}</button>
                        <button  type="submit" class="btn btn-info btn-ok">{{__('language.Add')}} {{__('language.Language')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- <div class="modal fade" id="delete_all" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="@if(isset($locale)){{url($locale.'/designation/delete_all')}} @else {{url('en/designation/delete_all')}} @endif" method="post">
                    {{ csrf_field() }}
                    <div class="modal-header">
                        {{__('language.Are You Sure You Want To Delete All Languages?')}}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{__('language.Cancel')}}</button>
                        <button  type="submit" class="btn btn-info btn-ok">{{__('language.Yes')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div> -->


@stop