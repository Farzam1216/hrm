@extends('layouts.admin')
@section('title','Apply Leave')
@section('heading')
<!-- Content Header (Page header) -->
<div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2 ">
            <div class="col-sm-6">
              <h1 class="m-0 text-dark">  {{__('language.Apply')}} {{__('language.Leave')}}</a></h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#"> {{__('language.Attendance')}}</a></li>
				<li class="breadcrumb-item "> {{__('language.My')}} {{__('language.Leaves')}}</li>
				<li class="breadcrumb-item active">  {{__('language.Apply')}} {{__('language.Leave')}}</a></li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->
@stop
@section('content')
	<div class="row">
		<div class="col-lg-12">
			<div class="card card-outline-info">
				<div style="margin:10px 10px">
					</h4>
				</div>
				<div class="card-body">
					<form class="form-horizontal" action="@if(isset($locale)){{url($locale.'/leave/store')}} @else {{url('en/leave/store')}} @endif" method="post">
						{{csrf_field()}}
						<div class="form-body">
							<h3 class="box-title">{{__('language.Create')}} {{__('language.Leave')}}</h3>
							<hr class="m-t-0 m-b-40">
							<!--/row-->
							<div class="row">
								<div class="col-md-6">
									<div class="form-group row">
										<label class="control-label text-right col-md-3">{{__('language.Leave')}} {{__('language.Type')}}</label>
										<div class="col-md-9">
											<select class="form-control custom-select" name="leave_type">
												@foreach($leave_types as $leave_type)
													<option @if(old('leave_type') == $leave_type->id)selected @endif value="{{$leave_type->id}}">{{$leave_type->name}} ({{$leave_type->amount}})</option>
												@endforeach
											</select>
										</div>
									</div>
								</div>
								<!--/span-->
								<div class="col-md-6">
									<div class="form-group row">
										<label class="control-label text-right col-md-3">{{__('language.Line Manager')}}</label>
										<div class="col-md-9">
											<input type="hidden" name="line_manager" value="{{isset($line_manager->id) ? $line_manager->id : ''}}">
											<input type="text" class="form-control" value="{{isset($line_manager->id) ? $line_manager->firstname.'  '. $line_manager->lastname : ''}}" disabled>
										</div>
									</div>
								</div>

								<!--/span-->
							</div>
							<!--/row-->
							<div class="row">
								<div class="col-md-6">
									<div class="form-group row">
										<label class="control-label text-right col-md-3">{{__('language.From')}} {{__('language.Date')}}</label>
										<div class="col-md-9">
											<input type="date" class="form-control"   name="datefrom" value="{{old('datefrom')}}"  min="{{Carbon\Carbon::now()->format('Y-m-d')}}">
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group row">
										<label class="control-label text-right col-md-3">{{__('language.To')}} {{__('language.Date')}}</label>
										<div class="col-md-9">
											<input type="date" class="form-control" placeholder="dd/mm/yyyy" name="dateto" value="{{old('dateto')}}" min="{{Carbon\Carbon::now()->format('Y-m-d')}}">
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group row">
										<label class="control-label text-right col-md-3">{{__('language.Back up')}}/ {{__('language.Point of Contact')}}:</label>
										<div class="col-md-9">
											<select class="form-control custom-select" name="point_of_contact">
												@foreach($employees as $employee)
													@if(Auth::user()->id != $employee->id)
													<option  @if(old('employee_id') == $employee->id) selected @endif value={{$employee->id}}>{{$employee->firstname}} {{$employee->lastname}}</option>
													@endif
												@endforeach
											</select>
										</div>
									</div>
								</div>
								<!--/span-->
								<div class="col-md-6">
									<div class="form-group row">
										<label class="control-label text-right col-md-3">CC {{__('language.To')}}</label>
										<div class="col-md-9">
											<input type="email"  multiple="multiple" class="form-control" name="cc_to" id="cc_to" value="{{old('cc_to')}}">
										</div>
									</div>
								</div>
								<!--/span-->
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group row">
										<label class="control-label text-right col-md-3">{{__('language.Subject')}}</label>
										<div class="col-md-9">
											<input type="text" class="form-control" placeholder="{{__('language.Enter Subject Here')}}" name="subject" value="{{old('subject')}}">
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group row">
										<label class="control-label text-right col-md-3">{{__('language.Description')}}</label>
										<div class="col-md-9">
											<textarea type="text" class="form-control" rows="3" name="description" placeholder="{{__('language.Enter Description Here')}}">{{old('description')}}</textarea>
										</div>
									</div>
								</div>
							</div>
						</div>
						<hr>
						<div class="form-actions">
							<div class="row">
								<div class="col-md-6">
									<div class="row">
										<div class="col-md-offset-3 col-md-9">
											<button type="submit" class="btn btn-success">{{__('language.Apply')}}</button>
											<a href="@if(isset($locale)){{url($locale.'/my-leaves')}} @else {{url('en/my-leaves')}} @endif" class="btn btn-inverse">{{__('language.Cancel')}}</a>
										</div>
									</div>
								</div>
								<div class="col-md-6"> </div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
        $(document).ready(function() {
            $(function () {
                $('#datefrom').datetimepicker({
                    format: "YYYY-MM-DD"
                });
                $('#dateto').datetimepicker({
                    format: "YYYY-MM-DD"
                });
            });
        });
	</script>
	@stop
