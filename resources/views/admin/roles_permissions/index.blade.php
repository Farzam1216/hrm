@extends('layouts.admin')
@section('title','Roles Permission')
@section('heading')
<!-- Content Header (Page header) -->
<div class="content-header">
		<div class="container-fluid">
		  <div class="row mb-2">
			<div class="col-sm-6">
			  <h1 class="m-0 text-dark">Roles and Permissions</h1>
			</div><!-- /.col -->
			<div class="col-sm-6">
			  <ol class="breadcrumb float-sm-right">
				<li class="breadcrumb-item"><a href="#">{{__('language.Roles Permission')}}</a></li>
				<li class="breadcrumb-item active">{{__('language.Roles')}}</li>
			  </ol>
			</div><!-- /.col -->
		  </div><!-- /.row -->
		</div><!-- /.container-fluid -->
	  </div>
	  <!-- /.content-header -->
	  <div class="row justify-content-end">
		
            <div class="col-2">
	<button type="button"  onclick="window.location.href='@if(isset($locale)){{url($locale.'/rolespermissions/create')}} @else {{url('en/rolespermissions/create')}} @endif'" class="btn btn-info btn-rounded btn-block m-t-10 float-right"><span class="fas fa-plus" ></span>{{__('language.Add')}} {{__('language.Roles')}}</button>
			</div>
	  </div>
	@stop
@section('content')
	<div class="row">
		<div class="col-lg-12">

				<div class="card">
                
                        <div class="card-body">
                                @if($roles->count() > 0)
                          <table id="kt_table_1" class="table table-bordered table-striped table-checkable">
                            <thead>
                            <tr>
                                    <th>{{__('language.Roles')}}</th>
								<th>{{__('language.Action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
									@foreach($roles as $role)
										<tr>
			
											<td>{{$role->name}}</td>
											<td class="text-nowrap">
												<a data-toggle="kt-tooltip" data-placement="top" title="" data-original-title="Edit Role" class="btn btn-sm btn-clean btn-icon btn-icon-md" href="@if(isset($locale)){{url($locale.'/rolespermissions/edit',$role->id)}} @else {{url('en/rolespermissions/edit',$role->id)}} @endif" > <i class="fas fa-edit"></i></a>
												<a class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="modal" data-target="#confirm-delete{{ $role->id }}"> <i class="fas fa-trash"></i> </a>
												{{--//Model//--}}
												<div class="modal fade" id="confirm-delete{{ $role->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
													<div class="modal-dialog">
														<div class="modal-content">
															<form action="@if(isset($locale)){{url($locale.'/rolespermissions/delete',$role->id)}} @else {{url('en/rolespermissions/delete',$role->id)}} @endif" method="post">
																{{ csrf_field() }}
																<div class="modal-header">
																	{{__('language.Are you sure you want to delete this Role')}} {{$role->name}}?
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
										</tr>
									@endforeach
									@else
									<tr>
											
											<p class="text-center"> {{__('language.No Permission Found')}}</p>
									
									</tr>
									@endif
										
									</tbody>
                          </table>
                          
                      <!--end: Datatable -->
                        </div>
                        <!-- /.card-body -->
                      </div>
                      <!-- /.card -->			
		</div>
	</div>

@stop