@extends('layouts.admin')
@section('title','Organization Hierarchy Edit')
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
                <li class="breadcrumb-item"><a href="#"> {{__('language.People Management')}}</a></li>
				<li class="breadcrumb-item">{{__('language.Organization')}} {{__('language.Hierarchy')}}</li>
				<li class="breadcrumb-item active">{{__('language.Update')}}</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

@stop
@section('content')
	<div class="row">
		<div class="col-lg-8" style="margin-left:200px" >
			<div class="card card-outline-info">
				<h6 class="card-subtitle"><button type="button" style="margin-right: 10px; margin-top:15px;" class="btn btn-info  m-t-10 float-right" onclick="window.location.href='@if(isset($locale)){{url($locale.'/organization-hierarchy')}} @else {{url('en/organization-hierarchy')}} @endif'">Back</button></h6>

				<div class="card-body">
					<form  action="@if(isset($locale)){{url($locale.'/organization-hierarchy',$organization_hierarchy->id)}} @else {{url('en/organization-hierarchy',$organization_hierarchy->id)}} @endif" method="post">
						<input name="_method" type="hidden" value="PUT">
						{{csrf_field()}}
						<div class="form-body">
							<div class="col-md-10">
								<div class="form-group">
									<label class="control-label">{{__('language.Name')}}</label>
									<select class="form-control custom-select" name="employee_id">
										@foreach ($employees as $employee)
											<option value="{{$employee->id}}" @if($employee->id == $organization_hierarchy->employee_id) selected @endif>{{$employee->firstname}} {{$employee->lastname}}</option>
										@endforeach
									</select>
								</div>
							</div>

								<div class="col-md-10">
									<div class="form-group">
										<label class="control-label">{{__('language.Parent')}} {{__('language.Employee')}}</label>
										<select class="form-control custom-select" name="parent_id">
											@foreach ($employees as $employee)
												<option value="{{$employee->id}}" @if($employee->id == $organization_hierarchy->parent_id) selected @endif>{{$employee->firstname}} {{$employee->lastname}}</option>
											@endforeach
										</select>
									</div>
								</div>
						</div>

						<div class="form-actions">
							<hr>
							&nbsp;&nbsp;&nbsp;<button type="submit" class="btn btn-success">{{__('language.Update')}}</button>
							<button type="button" onclick="window.location.href='@if(isset($locale)){{url($locale.'/organization-hierarchy')}} @else {{url('en/organization-hierarchy')}} @endif'" class="btn btn-inverse">{{__('language.Cancel')}}</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
@stop







{{--<div class="panel panel-default">--}}
	{{--<div class="panel-heading text-center">--}}
		{{--<b> Edit Organize Employee</b>--}}
		{{--<span style="float: right;">--}}
            {{--<a href="{{route('organization_hierarchy.index')}}" class="btn btn-info btn-xs" align="right">--}}
                {{--<span class="glyphicon"></span> Back--}}
            {{--</a>--}}
        {{--</span>--}}
	{{--</div>--}}
	{{--<div class="panel-body">--}}

		{{--<form action="{{route('organization_hierarchy.update',['id' => $organization_hierarchy->id])}}" method="post">--}}
			{{--<input name="_method" type="hidden" value="PUT">--}}
			{{--{{csrf_field()}}--}}
			{{--<div class="form-group">--}}
				{{--<label for="name">Name</label>--}}
				{{--<select name="employee_id" class="form-control">--}}
					{{--@foreach ($employees as $employee)--}}
					{{--<option value="{{$employee->id}}" @if($employee->id == $organization_hierarchy->employee_id) selected @endif>{{$employee->firstname}} {{$employee->lastname}}</option>--}}
					{{--@endforeach--}}
				{{--</select>--}}
			{{--</div>--}}
			{{--<div class="form-group">--}}
				{{--<label for="name">Line Manager</label>--}}
				{{--<select name="line_manager_id" class="form-control">--}}
					{{--@foreach ($employees as $employee)--}}
					{{--<option value="{{$employee->id}}" @if($employee->id == $organization_hierarchy->line_manager_id) selected @endif>{{$employee->firstname}} {{$employee->lastname}}</option>--}}
					{{--@endforeach--}}
				{{--</select>--}}
			{{--</div>--}}
			{{--<div class="form-group">--}}
				{{--<label for="name">Parent Employee</label>--}}
				{{--<select name="parent_id" class="form-control">--}}
					{{--@foreach ($employees as $employee)--}}
					{{--<option value="{{$employee->id}}" @if($employee->id == $organization_hierarchy->parent_id) selected @endif>{{$employee->firstname}} {{$employee->lastname}}</option>--}}
					{{--@endforeach--}}
				{{--</select>--}}
			{{--</div>--}}
			{{--<div class="form-group">--}}
				{{--<a href="{{route('organization_hierarchy.index')}}" class="btn btn-default">Cancel</a>--}}
	            {{--<button class="btn btn-success center-block" style="display: inline; float: left; margin-right: 5px;" type="submit">Update</button>--}}
			{{--</div>--}}
		{{--</form>--}}
	{{--</div>--}}
{{--</div>--}}
{{--<script type="text/javascript">--}}
{{--$(document).ready(function () {--}}
    {{--$(function () {--}}
	    {{--$("#check_all").click(function(){--}}
		    {{--$('input:checkbox').not(this).prop('checked', this.checked);--}}
		{{--});--}}
		{{--$(".check_all_sub").click(function(){--}}
		    {{--$('div.'+ this.id +' input:checkbox').prop('checked', this.checked);--}}
		{{--});--}}
    {{--});--}}
{{--});--}}
{{--</script>--}}
{{--@stop--}}
