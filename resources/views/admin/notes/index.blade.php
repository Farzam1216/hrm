@extends('layouts.admin')
@section('title','Employee Assets')
@section('heading')
<!-- Content Header (Page header) -->
<div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0 text-dark">{{__('language.Employee')}} {{__('language.Notes')}}</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#"> {{__('language.Settings')}}</a></li>
                <li class="breadcrumb-item ">{{__('language.Employee')}}</li>
                <li class="breadcrumb-item active">{{__('language.Notes')}}</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        
      <div class="row justify-content-end">
		
            <div class="col-12">
                    <button type="button" class="btn btn-info btn-rounded m-t-10 float-right" data-toggle="modal" data-target="#create"><i class="fa fa-plus"></i> {{__('language.Add')}} {{__('language.Notes')}}</button>
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

                    @if($notes->count() > 0)
                        @foreach($notes as $key=>$note)
                            <div class="media">
                                <div class="media-body">
                                    <h4 class="media-heading">{{$note->username}}</h4>
                                    <p>{{$note->note}}</p>
                                    @if(Auth::user()->firstname == $note->username)
                                        <a class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="modal" data-target="#edit{{$note->id}}"   data-original-title="Edit"> <i class="la la-edit"></i></a>
                                        <a class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="modal" data-target="#confirm-delete{{$note->id}}"  data-original-title="Close"> <i class="la la-trash"></i> </a>
                                    @endif
                                </div>
                                <div class="modal fade" id="confirm-delete{{ $note->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="@if(isset($locale)){{url($locale.'/notes/delete',$note->id)}} @else {{url('en/notes/delete',$note->id)}} @endif" method="post">
                                                {{ csrf_field() }}
                                                <div class="modal-header">
                                                    {{__('language.Are you sure you want to delete this note?')}}
                                                </div>
                                                <div class="modal-header">
                                                    <p>{{ $note->note}} </p>
                                                    <input type="hidden" name="employee_id" value="{{$employee_id}}">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">{{__('language.Cancel')}}</button>
                                                    <button  type="submit" class="btn btn-danger btn-ok">{{__('language.Delete')}}</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal fade" id="edit{{ $note->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="@if(isset($locale)){{url($locale.'/notes/update',$note->id)}} @else {{url('en/notes/update',$note->id)}} @endif" method="post">
                                                {{ csrf_field() }}
                                                <div class="modal-header">
                                                    {{__('language.Update')}} {{__('language.Note')}}
                                                </div>
                                                </br>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="control-label">{{__('language.Note')}} </label>
                                                        <input  type="text" name="note" value="{{old('name',$note->note)}}" placeholder="" class="form-control">
                                                        <input type="hidden" name="employee_id" value="{{$employee_id}}">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">{{__('language.Cancel')}}</button>
                                                    <button  type="submit" class="btn btn-success btn-ok">{{__('language.Update')}} {{__('language.Note')}}</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                        @endforeach
                    @else
                        <p class="text-center"> {{__('language.No Notes Found')}}</p>
                @endif

                <!--end: Datatable -->
                </div>
            </div>

            <!--end::Portlet-->
        </div>
    </div>

    <div class="modal fade" id="create" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="@if(isset($locale)){{url($locale.'/notes/create')}} @else {{url('en/notes/create')}} @endif" method="post">
                    {{ csrf_field() }}
                    <div class="modal-header">
                        {{__('language.Add')}} {{__('language.Note')}}
                    </div>
                    </br>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label"> {{__('language.Note')}}</label>
                            <input  type="text" name="note" placeholder="" class="form-control">
                            <input type="hidden" name="employee_id" value="{{$employee_id}}">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{__('language.Cancel')}}</button>
                        <button  type="submit" class="btn btn-info btn-ok">{{__('language.Add')}} {{__('language.Note')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



@stop