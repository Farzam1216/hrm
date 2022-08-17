@extends('layouts.contentLayoutMaster')
@section('title','Create Job')
@section('content')
<form action="@if(isset($locale)){{url($locale.'/job')}} @else {{url('en/job')}} @endif"  method="post" id="jobs" enctype="multipart/form-data">
{{ csrf_field() }}
	<div class="card">
	<div class="container mt-2">	
		<div class="row">
			<div class="form-group col-md-6">
				<label class="control-label">{{__('language.Job Title')}}</label><span
				class="text-danger"> *</span>
				<input type="text" name="title" value="{{ old('jobtitle') }}" class="form-control " placeholder="{{__('language.Enter')}} {{__('language.Title')}}" required>
			</div>
			<div class="form-group col-md-6">
				<label class="control-label">{{__('language.Designation')}}</label><span
				class="text-danger"> *</span>
				<select class="form-control" name="designation_id" required>
					<option value="">{{__('language.Select')}} {{__('language.Designation')}}</option>
					@foreach($designations as $designation)
						<option value="{{$designation->id}}">{{$designation->designation_name}}</option>
					@endforeach
				</select>
			</div>
		</div>
		<div class="row">
			<div class="form-group col-md-6">
				<label class="control-label">{{__('language.Job Status')}}</label><span
				class="text-danger"> *</span>
				<select class="form-control " name="job_status" required>
					<option value="">{{__('language.Select')}} {{__('language.Job')}} {{__('language.Status')}}</option>
					<option value="draft">{{__('language.Draft')}}</option>
					<option value="open">{{__('language.Open')}}</option>
					<option value="hold">{{__('language.On Hold')}}</option>
					<option value="filled">{{__('language.Filled')}}</option>
					<option value="canceled">{{__('language.Canceled')}}</option>
				</select>
			</div>
			<div class="form-group col-md-6">
				<label class="control-label">{{__('language.Minimum Experience')}}</label><span
				class="text-danger"> *</span>
				<select class="form-control " name="minimum_experience" required>
					<option value="">{{__('language.Select')}} {{__('language.Experience')}} {{__('language.Level')}}</option>												
					<option  value="entryLevel" >{{__('language.Entry-Level')}}</option>
					<option value="midLevel" >{{__('language.Mid-Level')}}</option>
					<option value="experienced" >{{__('language.Experienced')}}</option>
					<option value="manager" >{{ __('language.Manager') }}/{{ __('language.Supervisor') }}</option>
					<option value="seniorManager">{{ __('language.Senior Manager') }}/{{ __('language.Supervisor') }}</option>
					<option value="executive" >{{__('language.Executive')}}</option>
					<option value="seniorExecutive">{{__('language.Senior Executive')}}</option>
				
				</select>
			</div>
			<!--/span-->
		</div>
		<div class="row">
			<div class="form-group col-md-6">
				<label class="control-label">{{__('language.Department')}}</label><span
				class="text-danger"> *</span>
				<select class="form-control " name="department_id" required>
					<option value="">{{__('language.Select')}} {{__('language.Department')}}</option>
					@foreach($departments as $department)
						<option value="{{$department->id}}">{{$department->department_name}}</option>
					@endforeach
				</select>
			</div>
			<div class="form-group col-md-6">
				<label class="control-label">{{__('language.Hiring Lead')}}</label><span
				class="text-danger"> *</span>
				<select class="form-control " name="hiring_lead_id" required>
					<option value="">{{__('language.Select')}} {{__('Hiring Lead')}}</option></option>
						@foreach($employees as $employee)
							<option value="{{$employee->id}}" @if(old("employee_id") == "remote") selected @endif>{{$employee->firstname}} {{$employee->lastname}}</option>
						@endforeach
				</select>
			</div>
			<!--/span-->
		</div>
		<div class="row">
			<div class="form-group col-md-6">
				<label class="control-label">{{__('language.Employment Status')}}</label><span
				class="text-danger"> *</span>
				<select class="form-control " name="employment_status_id" required>
					<option value="">{{__('language.Select')}} {{__('language.Employment')}} {{__('language.Status')}}</option>	
					@foreach($employmentStatus as $eStatus)
						<option value="{{$eStatus->id}}">{{$eStatus->employment_status}}</option>
					@endforeach
				</select>
			</div>
			<div class="form-group col-md-6">
				<label class="control-label">{{__('language.Location')}}</label><span
				class="text-danger"> *</span>
				<select class="form-control " name="location_id" required>
					<option value="">{{__('language.Select')}} {{__('language.Job')}} {{__('language.Location')}}</option>
					@foreach($locations as $location)
						<option value="{{$location->id}}" @if(old("location_id") == "remote") selected @endif>{{$location->name}} ({{$location->city}})</option>
					@endforeach
				</select>
			</div>
			<!--/span-->
		</div>
		<div class="row">
			<div class="form-group col-md-12">
				<label class="control-label">{{__('language.Description')}}</label>
				<textarea
                  class="form-control"
                  id="exampleFormControlTextarea1"
                  rows="3"
                  id="textarea" name="description" placeholder="{{__('language.Enter')}} {{__('language.text')}} ..."
                ></textarea>
				
			</div>
		</div>
		<hr>
		<div class="row">			
			<div class="form-group col-md-12">
				<label class="control-label bold">{{__('Application Questions:')}}</label>
				<small class="float-left input-group input-group-sm">
					<span class="m-1"> Questions with </span> 
				<div class="custom-control custom-switch  mt-1 custom-switch-on-success">
					<input type="checkbox" checked class="custom-control-input">
					<label class="custom-control-label"></label>
				</div>
				<span class="my-1 ml-1">are Required.</span>
			</small>
			<div class="row">
				<!-----Optional Questions Div------>
				<div class=" col-md-12 mt-2 mr-2" style="">
					<!---Optional question--->
					<!-----row 1---->
					<div class="row ml-1 d-flex flex-row">
						@foreach($questions as $question)
						@if($question->type_id == 1)
							<div class="p-1 flex-fill">
								<div class="custom-control custom-checkbox custom-control-inline" >
									<input type="hidden" value={{$question->id}} name="optionalQuestions[{{$question->id}}][id]">
									<input class="custom-control-input" name="optionalQuestions[{{$question->id}}][question]" type="checkbox" id="optionalField{{$question->id}}" >
									<label for="optionalField{{$question->id}}" class="custom-control-label">{{$question->question}}</label>									
								</div>
								<div class="custom-control custom-switch custom-control-inline custom-switch-off-danger  custom-switch-on-success" style="display: inline-block">

									<input type="checkbox"  name="optionalQuestions[{{$question->id}}][status]" class="custom-control-input"  id="optionalFieldRequired{{$question->id}}" />
									<label class="custom-control-label" for="optionalFieldRequired{{$question->id}}"></label>
								
								</div>
							</div>												
						@endif
						@endforeach
					</div>	
				</div>
			</div>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="form-group col-md-12">
				<label class="control-label bold">{{__('Custom Questions:')}}</label>
				<a href="#" class="ml-2" id="add"><small> + Add Custom Questions </small></a>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-md-11 offset-md-1" style="" id="optionalQuestions">
				<!---Custome question--->										
			</div>
		</div>
		<div class="mt-1 mb-1">
		<button class="btn btn-primary waves-effect waves-float waves-light" type="submit" id="btnSubmit">
			<i class="d-block d-sm-none" data-feather='check-circle'></i><span class="d-none d-sm-inline"> Save</span></button>
		<button class="btn btn-outline-warning ml-1 waves-effect waves-float waves-light" type="button" id="cancelBtn"
		onclick="window.location.href='@if(isset($locale)){{url($locale.'/job')}} @else {{url('en/job')}} @endif'">
			<i class="d-block d-sm-none" data-feather='x-circle'></i><span class="d-none d-sm-inline"> Cancel</span>
		</button>
	</div>
	</div>
	</div>
</form>

{{--    --}}
@section('vendor-script')
    <!-- vendor files -->
    <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
@endsection
	@section('page-script')
	<script src="{{ asset(mix('js/scripts/forms/validations/form-jobs.js'))}}"></script>


<script>
		$("form").submit(function () {
   var this_master = $(this);
   this_master.find('input[type="checkbox"]').each( function () {
       var checkbox_this = $(this);
       if( checkbox_this.is(":checked") == true ) {
           checkbox_this.attr('value','Yes');
       } else {
           checkbox_this.attr('value','No');
       }
   })
})
		$(document).ready(function() {
			var count=0;
	
		$('#add').click(function(e){ //click event to add custom questions in view
			e.preventDefault();	
				$('#optionalQuestions').append('<div class="input-group input-group-sm custom mb-2"><div class="custom-control custom-switch custom-switch-off-danger  custom-switch-on-success"><input type="checkbox" name="customQuestions['+ count + '][status]" class="custom-control-input" id="customSwitch'+ count +'"><label class="custom-control-label" for="customSwitch'+ count +'"></label></div><input class="form-control" placeholder="{{__('language.Enter')}} {{__('language.Question')}}"  type="text"  name="customQuestions['+ count + '][question]"/><button class="remove_field btn btn-danger btn-sm ml-2"><i class="fas fa-trash"></i></button></div>'); //add input field
			count++;
		});  
		$('#optionalQuestions').on("click",".remove_field", function(e){ //user click on remove text links
			e.preventDefault(); 
			
			$(this).parents('.custom').remove();
		})

	});
	</script>
	@endsection
@stop
