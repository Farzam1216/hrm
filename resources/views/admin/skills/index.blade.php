@extends('layouts.admin')
@section('title','Skills')
@section('heading')
<!-- Content Header (Page header) -->
<div class="content-header">
		<div class="container-fluid">
		  <div class="row mb-2">
			<div class="col-sm-6">
			  <h1 class="m-0 text-dark"> {{__('language.Skills')}}</h1>
			</div><!-- /.col -->
			<div class="col-sm-6">
			  <ol class="breadcrumb float-sm-right">
				<li class="breadcrumb-item"><a href="#">{{__('language.Settings')}}</a></li>
				<li class="breadcrumb-item active"> {{__('language.Skills')}}</li>
			  </ol>
			</div><!-- /.col -->
		  </div><!-- /.row -->
		</div><!-- /.container-fluid -->
	</div><!-- /.content-header -->
    <div class="row justify-content-end">
		
            <div class="col-2">
    <button type="button" class="btn btn-info btn-rounded m-t-10 float-right" data-toggle="modal" data-target="#create"><i class="fas fa-plus"></i> {{__('language.Add')}} {{__('language.Skill')}}</button>

            </div>
    </div>
    @stop
@section('content')
    <div class="row">
        <div class="col-lg-12">
                <div class="card">
                        
                        <div class="card-body">
                                @if($skills->count() > 0)
                          <table id="kt_table_1" class="table table-bordered table-striped table-checkable">
                            <thead>
                            <tr>
									<th>#</th>
                                <th> {{__('language.Skill')}} {{__('language.Name')}}</th>
                                <th>{{__('language.Description')}}</th>
                                <th> {{__('language.Status')}}</th>
                                <th> {{__('language.Actions')}}</th>
                            </tr>
                            </thead>
                            <tbody>
									@foreach($skills as $key=>$skill)
                                    <tr>
                                            <td>{{$key+1}}</td>
                                            <td>{{$skill->skill_name}}</td>
                                            <td>{{$skill->description}}</td>
                                            <td>@if($skill->status==1)
                                                    <span class="btn btn-bold btn-sm btn-font-sm  btn-label-success">{{__('language.Active')}}</span>
                                                @else
                                                    <span class="btn btn-bold btn-sm btn-font-sm  btn-label-danger">{{__('language.InActive')}}</span>
                                                @endif
                                            </td>
                                            <td class="text-nowrap">
                                                <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown"
                                                   aria-expanded="true">
                                                    <i class="fas fa-ellipsis-h"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a href="" class="dropdown-item" data-toggle="modal" data-target="#edit{{ $skill->id }}"   data-original-title="Edit"><i
                                                                class="fas fa-edit"></i> {{__('language.Edit')}} {{__('language.Skill')}} {{__('language.Details')}}</a>
                                                    <a href="" class="dropdown-item" data-toggle="modal" data-target="#confirm-delete{{ $skill->id }}"><i class="fas fa-trash"></i> {{__('language.Delete')}} {{__('language.Skill')}}</a>
                                                    <a href="" class="dropdown-item" data-toggle="modal" data-target="#add{{$skill->id}}"><i
                                                                class="fas fa-plus"></i> {{__('language.Add')}} {{__('language.Member')}} {{__('language.To')}}  {{__('language.Skill')}}</a>
                                                    <a class="dropdown-item" href="@if(isset($locale)){{url($locale.'/assign-skill/edit',$skill->id)}} @else {{url('en/assign-skill/edit',$skill->id)}} @endif"><i
                                                                class="fas fa-eye"></i> {{__('language.View')}} {{__('language.Skill')}} {{__('language.Members')}}</a>
                                                </div>
                                                <div class="modal fade" id="confirm-delete{{ $skill->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form action="@if(isset($locale)){{url($locale.'/skill/delete',$skill->id)}} @else {{url('en/skill/delete',$skill->id)}} @endif" method="post">
                                                                {{ csrf_field() }}
                                                                <div class="modal-header">
                                                                   {{__('language.Are you sure you want to delete this Skill?')}}
                                                                </div>
                                                                <div class="modal-header">
                                                                    <h4>{{ $skill->skill_name}}</h4>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">{{__('language.Cancel')}}</button>
                                                                    <button  type="submit" class="btn btn-danger btn-ok">{{__('language.Delete')}}</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal fade" id="add{{$skill->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form action="@if(isset($locale)){{url($locale.'/assign-skill')}} @else {{url('en/assign-skill')}} @endif" method="post">
                                                                {{ csrf_field() }}
                                                                <div class="modal-header">
                                                                    {{__('language.Assign')}} {{__('language.Skill')}} " {{$skill->skill_name}} " {{__('language.To')}}
                                                                </div>
                                                                <br>
                                                                <input type="text" name="skill_id" value="{{$skill->id}}" hidden>
                                                                <div class="col-md-8">
                                                                    <div class="form-group">
                                                                        <label class="control-label">{{__('language.Employee')}}</label>
                                                                        <select  name="employee_id"  class="form-control">
                                                                            @foreach($employees as $employee)
                                                                                <option value="{{$employee->id}}">{{$employee->firstname}} {{$employee->lastname}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">{{__('language.Cancel')}}</button>
                                                                    <button  type="submit" class="btn btn-success btn-ok">{{__('language.Assign')}}</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <div class="modal fade" id="edit{{ $skill->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form action="@if(isset($locale)){{url($locale.'/skill/update',$skill->id)}} @else {{url('en/skill/update',$skill->id)}} @endif" method="post">
                                                            {{ csrf_field() }}
                                                            <div class="modal-header">
                                                                {{__('language.Update')}} {{__('language.Skill')}}
                                                            </div>
                                                            </br>
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label class="control-label">{{__('language.Skill')}} {{__('language.Name')}}</label>
                                                                    <input  type="text" name="skill_name" value="{{old('skill_name',$skill->skill_name)}}" placeholder="{{__('language.Enter Skill Name Here')}}" class="form-control">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="control-label">{{__('language.Status')}}</label>
                                                                    <select  name="status"  class="form-control">
                                                                        <option value="1" @if($skill->status == 1) selected @endif>{{__('language.Active')}}</option>
                                                                        <option value="0" @if($skill->status == 0) selected @endif>{{__('language.InActive')}}</option>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="control-label">{{__('language.Description')}}</label>
                                                                    <textarea  type="text" name="description" value="{{$skill->description}}" class="form-control">{{$skill->description}}</textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">{{__('language.Cancel')}}</button>
                                                                <button  type="submit" class="btn btn-success btn-ok">{{__('language.Update')}} {{__('language.Skill')}}</button>
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
                        <p class="text-center"> {{__('language.No Skill Found')}}</p>
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
                <form action="@if(isset($locale)){{url($locale.'/skill/create')}} @else {{url('en/skill/create')}} @endif" method="post">
                    {{ csrf_field() }}
                    <div class="modal-header">
                        {{__('language.Create')}} {{__('language.Skill')}}
                    </div>
                    <br>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label">{{__('language.Skill')}} {{__('language.Name')}}</label>
                            <input  type="text" name="skill_name" placeholder="{{__('language.Enter Skill Name Here')}}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{__('language.Status')}}</label>
                            <select  name="status"  class="form-control">
                                <option value="1">{{__('language.Active')}}</option>
                                <option value="0">{{__('language.InActive')}}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{__('language.Description')}}</label>
                            <textarea  type="text" name="description" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{__('language.Cancel')}}</button>
                        <button  type="submit" class="btn btn-info btn-ok">{{__('language.Add')}} {{__('language.Skill')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop