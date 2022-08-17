@extends('layouts.admin')
@section('title','Organization Hierarchy Create')
@section('heading')
<!-- Content Header (Page header) -->
<div class="content-header">
		<div class="container-fluid">
		  <div class="row mb-2">
			<div class="col-sm-6">
			  <h1 class="m-0 text-dark">{{__('language.Create')}}</h1>
			</div><!-- /.col -->
			<div class="col-sm-6">
			  <ol class="breadcrumb float-sm-right">
				<li class="breadcrumb-item"><a href="#">{{__('language.People Management')}}</a></li>
				<li class="breadcrumb-item">{{__('language.Organization')}} {{__('language.Hierarchy')}}</li>
				<li class="breadcrumb-item active">{{__('language.Create')}}</li>
			  </ol>
			</div><!-- /.col -->
		  </div><!-- /.row -->
		</div><!-- /.container-fluid -->
	  </div>
	  <!-- /.content-header -->
	
@stop
@section('content')
	<div class="row">
		<div class="col-lg-12" >
			<div class="card card-outline-info">
				<h6 class="card-subtitle"><button type="button" style="margin-right: 10px; margin-top:15px;" class="btn btn-info  m-t-10 float-right" onclick="window.location.href='@if(isset($locale)){{url($locale.'/organization-hierarchy')}} @else {{url('en/organization-hierarchy')}} @endif'">{{__('language.Back')}}</button></h6>

				<div class="card-body">
					<form  action="@if(isset($locale)){{url($locale.'/organization-hierarchy')}} @else {{url('en/organization-hierarchy')}} @endif" method="post" enctype="multipart/form-data">
						{{csrf_field()}}
						<div class="form-body">
							<div class="col-md-8">
								<div class="form-group">
									<label class="control-label">{{__('language.Employee')}}({{__('language.Child')}})</label>
									<select class="form-control custom-select" name="employee_id">
										@foreach ($employees as $employee)
											<option value="{{$employee->id}}" @if($employee->id == old('employee_id')) selected @endif>{{$employee->firstname}} {{$employee->lastname}}</option>
										@endforeach
									</select>
								</div>
							</div>
							@if($OrganizationHierarchyCnt > 0)

							<div class="col-md-8">
								<div class="form-group">
									<label class="control-label">{{__('language.Parent')}} {{__('language.Employee')}}</label>
									<select class="form-control custom-select" name="parent_id">
										@foreach ($employees as $employee)
											<option value="{{$employee->id}}" @if($employee->id == old('employee_id')) selected @endif>{{$employee->firstname}} {{$employee->lastname}}</option>
										@endforeach
									</select>
								</div>
							</div>
							@endif
						</div>

						<div class="form-actions">
							<hr>
							&nbsp;&nbsp;&nbsp;<button type="submit" class="btn btn-success">{{__('language.Create')}}</button>
							<button type="button" onclick="window.location.href='@if(isset($locale)){{url($locale.'/organization-hierarchy')}} @else {{url('en/organization-hierarchy')}} @endif'" class="btn btn-inverse">{{__('language.Cancel')}}</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
@stop
