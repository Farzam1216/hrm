@extends('layouts.admin')
@section('title','Trashed Employee')
@section('heading')
<!-- Content Header (Page header) -->
<div class="content-header">
		<div class="container-fluid">
		  <div class="row mb-2">
			<div class="col-sm-6">
			  <h1 class="m-0 text-dark">{{__('language.Document')}}</h1>
			</div><!-- /.col -->
			<div class="col-sm-6">
			  <ol class="breadcrumb float-sm-right">
				<li class="breadcrumb-item"><a href="#">{{__('language.People Management ')}}</a></li>
				<li class="breadcrumb-item">{{__('language.Employee')}}</li>
				<li class="breadcrumb-item active">{{__('language.Trashed')}}</li>
			  </ol>
			</div>
</div>
</div> <!-- /.container-fluid --> 
</div><!-- /.content-header -->
	
@stop
@section('content')
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<h6 class="card-subtitle"></h6>
				<button type="button"  onclick="window.location.href='{{route('employees')}}'" class="btn btn-info btn-rounded m-t-10 float-right">Back</button>
				<div class="table">
					@if($employees->count() > 0)
					<table id="demo-foo-addrow" class="table  table-hover contact-list" data-paging="true" data-paging-size="7">
						<thead>
						<tr>
							<th> Firstname</th>
							<th> Lastname</th>
							<th> Role</th>
							<th> Organization Email</th>
							<th>Actions</th>
						</tr>
						</thead>
						<tbody>
						@foreach($employees as $employee)
							<tr>

								<td>{{$employee->firstname}}</td>
								<td>{{$employee->lastname}}</td>
								<td> {{$employee->role}}</td>
								<td>{{$employee->org_email}}</td>
								<td class="text-nowrap">
									<a class="btn btn-info btn-sm" href="{{route('employee.restore',['id'=>$employee->id])}}" data-toggle="tooltip" data-original-title="Restore"> <i class="text-white">Restore</i></a>
									<a class="btn btn-danger btn-sm" href="{{route ('employee.kill',['id' =>$employee->id])}}" data-toggle="tooltip" data-original-title="Close"> <i class="text-white">Delete</i> </a>
								</td>

							</tr>
						@endforeach
						</tbody>
					</table>
					@else
						<p class="text-center"> No job found.</p>
					@endif

				</div>
			</div>
		</div>
	</div>
</div>

@stop



{{--<div class="panel panel-default">--}}
	{{--<div class="panel-heading text-center">--}}
		{{--<b> Trash Employees </b>--}}
		{{--<span style="float: right;">--}}
            {{--<a href="{{route('employees')}}" class="btn btn-info btn-xs" align="right">--}}
                {{--<span class="glyphicon"></span> Back--}}
            {{--</a>--}}
        {{--</span>--}}
	{{--</div>--}}
	{{--<div class="panel-body">--}}
		{{--<table class="table">--}}
			{{--@if($employees->count() > 0) @foreach($employees as $employee)--}}

			{{--<thead>--}}
				{{--<th> Firstname</th>--}}
				{{--<th> Lastname</th>--}}
				{{--<th> Role</th>--}}
				{{--<th> Organization Email</th>--}}
				{{--<th>Actions</th>--}}
			{{--</thead>--}}
			{{--<tbody class="table-bordered table-hover table-striped">--}}
				{{--<tr>--}}
					{{--<td>{{$employee->firstname}}</td>--}}
					{{--<td>{{$employee->lastname}}</td>--}}
					{{--<td> {{$employee->role}}</td>--}}
					{{--<td>{{$employee->org_email}}</td>--}}
					{{--<td>--}}
						{{--<div class="btn-group">--}}
							{{--<button type="button" class="btn btn-primary">Actions</button>--}}
							{{--<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">--}}
								{{--<span class="caret"></span>--}}
							{{--</button>--}}
							{{--<ul class="dropdown-menu" role="menu">--}}
								{{--<li>--}}
									{{--<a href="{{route('employee.restore',['id'=>$employee->id])}}">Restore</a>--}}
								{{--</li>--}}
								{{--<li>--}}
									{{--<a href="{{route ('employee.kill',['id' =>$employee->id])}}">Delete</a>--}}
								{{--</li>--}}
							{{--</ul>--}}
						{{--</div>--}}
					{{--</td>--}}
				{{--</tr>--}}

			{{--</tbody>--}}
			{{--@endforeach @else--}}
			{{--<tr> No Employee found.</tr>--}}
			{{--@endif--}}

		{{--</table>--}}
	{{--</div>--}}
{{--</div>--}}

