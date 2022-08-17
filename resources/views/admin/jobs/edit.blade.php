@extends('layouts.contentLayoutMaster')
@section('title','Edit Job')

@section('vendor-style')
{{-- Page Css files --}}
<link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap4.min.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/buttons.bootstrap4.min.css')) }}">
@endsection

@section('page-style')
{{-- Page Css files --}}
<link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('css/base/pages/app-user.css')) }}">
@endsection
@section('content')
@if (Session::has('error'))
<div class="alert alert-warning" align="left">
	<a href="#" class="close" data-dismiss="alert">&times;</a>
	<strong>!</strong> {{Session::get('error')}}
</div>
@endif

	<form id="jobs" action="@if(isset($locale)){{url($locale.'/job/'.$job->id)}} @else {{url('en/job'.$job->id)}} @endif" method="post" enctype="multipart/form-data">
		<input name="_method" type="hidden" value="PUT">
		{{ csrf_field() }}
			<div class="card">
			<div class="container mt-2">
				
				<div class="row">
					<div class="form-group col-md-6">
						<label class="control-label">{{__('language.Job Title')}}</label><span
						class="text-danger"> *</span>
						<input type="text" name="title" value="{{$job->title}}" class="form-control " placeholder="{{__('language.Enter')}} {{__('language.Title')}}" required>
		
					</div>
					<div class="form-group col-md-6">
						<label class="control-label">{{__('language.Designation')}}</label><span
						class="text-danger"> *</span>
						<select class="form-control " name="designation_id" required>
							<option value="">{{__('language.Select')}} {{__('language.Designation')}}</option>
							@foreach($designations as $designation)
								<option value="{{$designation->id}}" @if($designation->id == $job->designation_id )selected @endif>{{$designation->designation_name}}</option>
							@endforeach
						</select>
					</div>
					<!--/span-->
				</div>
		
				<div class="row">
					<div class="form-group col-md-6">
						<label class="control-label">{{__('language.Job Status')}}</label><span
						class="text-danger"> *</span>
						<select class="form-control " name="job_status" required>
							<option @if($job->status=="draft") selected @endif value="draft">{{__('language.Draft')}}</option>
							<option @if($job->status=="open") selected @endif value="open">{{__('language.Open')}}</option>
							<option @if($job->status=="hold") selected @endif value="hold">{{__('language.On Hold')}}</option>
							<option @if($job->status=="filled") selected @endif value="filled">{{__('language.Filled')}}</option>
							<option @if($job->status=="canceled") selected @endif value="canceled">{{__('language.Canceled')}}</option>
						</select>
					</div>
					<div class="form-group col-md-6">
						<label class="control-label">{{__('language.Minimum Experience')}}</label><span
						class="text-danger"> *</span>
						<select class="form-control " name="minimum_experience" required>
							<option @if($job->minimum_experience=="entryLevel") selected @endif value="entryLevel" >{{__('language.Entry-Level')}}</option>
							<option @if($job->minimum_experience=="midLevel") selected @endif value="midLevel" >{{__('language.Mid-Level')}}</option>
							<option @if($job->minimum_experience=="experienced") selected @endif value="experienced" >{{__('language.Experienced')}}</option>
							<option @if($job->minimum_experience=="manager") selected @endif value="manager" >{{ __('language.Manager') }}/{{ __('language.Supervisor') }}</option>
							<option @if($job->minimum_experience=="seniorManager") selected @endif value="seniorManager">{{ __('language.Senior Manager') }}/{{ __('language.Supervisor') }}</option>
							<option @if($job->minimum_experience=="executive") selected @endif value="executive" >{{__('language.Executive')}}</option>
							<option @if($job->minimum_experience=="seniorExecutive") selected @endif value="seniorExecutive">{{__('language.Senior Executive')}}</option>
															
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
								<option value="{{$department->id}}" @if($department->id == $job->department_id )selected @endif>{{$department->department_name}}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group col-md-6">
						<label class="control-label">{{__('language.Hiring Lead')}}</label><span
						class="text-danger"> *</span>
						<select class="form-control " name="hiring_lead_id" required>
							<option value="">{{__('language.Select')}} {{__('Hiring Lead')}}</option></option>
								@foreach($employees as $employee)
									<option value="{{$employee->id}}" @if($employee->id== $job->hiring_lead_id) selected @endif>{{$employee->firstname}} {{$employee->lastname}}</option>
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
								<option value="{{$eStatus->id}}" @if($eStatus->id== $job->employment_status_id) selected @endif>{{$eStatus->employment_status}}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group col-md-6">
						<label class="control-label">{{__('language.Location')}}</label><span
						class="text-danger"> *</span>
						<select class="form-control " data-placeholder="Choose a Category" tabindex="1" name="location_id">
							@foreach($locations as $location)
								<option value="{{$location->id}}" @if($location->id== $job->location_id) selected @endif>{{$location->name}} ({{$location->city}})</option>
							@endforeach
						</select>
					</div>
					<!--/span-->
				</div>
		
		
				<div class="row">
					<div class="form-group col-md-12">
						<label class="control-label">{{__('language.Description')}}</label>
						<textarea class="textarea_editor form-control" id="textarea" name="description" rows="5" placeholder="{{__('language.Enter')}} {{__('language.text')}} ..."
						style="width: 100%;  font-size: 14px; line-height: 18px; border: 1px solid #dddddd;"> {{$job->description}}</textarea>
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
						@foreach($allquestions as $question)
						@if($question->type_id == 1)
							<div class="p-1 flex-fill">
								<div class="custom-control custom-checkbox custom-control-inline" >
									<input type="hidden" value={{$question->id}} name="optionalQuestions[{{$question->id}}][id]">
									<input class="custom-control-input" name="optionalQuestions[{{$question->id}}][question]" type="checkbox" id="optionalField{{$question->id}}" @foreach ($question->jobques as $jobque )
									@if($question->id == $jobque->que_id && $job->id == $jobque->job_id )
									checked
									@endif
									@endforeach>
									<label for="optionalField{{$question->id}}" class="custom-control-label">{{$question->question}}</label>									
								</div>
								<div class="custom-control custom-switch custom-control-inline custom-switch-off-danger  custom-switch-on-success" style="display: inline-block">

									<input type="checkbox"  name="optionalQuestions[{{$question->id}}][status]" class="custom-control-input"  id="optionalFieldRequired{{$question->id}}" @foreach ($question->jobques as $jobque )
									@if($question->id == $jobque->que_id && $job->id == $jobque->job_id )
									@if($jobque->status==1)
									checked
									@endif
									@endif
									@endforeach / >
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
		
		<div class="row">
			<div class="col-md-11 offset-md-1" style="" id="optionalQuestions">
				<!---Custome question--->										
		
			</div>
			
		</div>
		<div class="mt-1 mb-1">
					<button class="btn btn-primary waves-effect waves-float waves-light" type="submit" id="btnSubmit">
						<i class="d-block d-sm-none" data-feather='check-circle'></i><span class="d-none d-sm-inline"> Update</span></button>
					<button class="btn btn-outline-warning ml-1 waves-effect waves-float waves-light" type="button" id="cancelBtn"
					onclick="window.location.href='@if(isset($locale)){{url($locale.'/job')}} @else {{url('en/job')}} @endif'">
						<i class="d-block d-sm-none" data-feather='x-circle'></i><span class="d-none d-sm-inline"> Cancel</span>
					</button>
				</div>
					</div>
				</div>
				
				
			</div>
			</div>
		</form>
		@section('vendor-script')
    <!-- vendor files -->
    		<script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
		@endsection
		@section('page-script')
		<script src="{{ asset(mix('js/scripts/forms/validations/form-jobs.js'))}}"></script>
		<script>
			var count=0;
			/*------Check all checkbox status on form submit----------*/
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
			});
			
			/*----All Questions with Job Questions----*/
			var allQues = {!!  $allquestions !!};
			
			
			/*--------Specific Job ID-------*/
			var id= {!!  $job->id!!}
			//console.log(JSON.stringify(obj));
			var jobQues;
			c='';
				 v='';
				 
			$.each( allQues, function( key, value) {
				//console.log(JSON.stringify(value));
				if(value['type_id'] != 1){
					
				var jobQues =  value['jobques'];	
				$.each( jobQues, function( key, data) {
			
			//console.log(data);
					//console.log(id + "  " + data['job_id']);
				if(value['id'] == data['que_id'] && id ==  data['job_id'])
				{
					if(data['status'] == 1 )
				{ 
			c="checked"; } 
				else  { 
				c=""; }
				console.log("what is happening");
				
			
				$('#optionalQuestions').append('<div class="input-group input-group-sm custom mb-2"><div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success"><input type="checkbox"  name="customQuestions['+ count +'][status]" '+ c +' class="custom-control-input" id="customSwitch'+count+'"><label class="custom-control-label" for="customSwitch'+count+'"></label></div><input class="form-control" placeholder="{{__('language.Enter')}} {{__('language.Question')}}"  type="text" value="'+value['question'] +'"  name="customQuestions['+count+'][question]"/><input type="text" hidden name="customQuestions['+count+'][id]" value="'+value['id']+'"><button class="remove_field btn btn-danger btn-sm ml-2"><i class="fas fa-trash"></i></button></div>'); //add input field
			 count++;
			
				}
			});
			
				
			
				}
				
			});
					$(document).ready(function() {		
				
					$('#add').click(function(e){ //click event on add more questions for the job
						e.preventDefault();	
						count++;
							$('#optionalQuestions').append('<div class="input-group input-group-sm custom mb-2"><div class="custom-control custom-switch custom-switch-off-danger  custom-switch-on-success"><input type="checkbox"  value="off" name="customQuestions['+ count + '][status]" class="custom-control-input" id="customSwitch'+ count +'"><label class="custom-control-label" for="customSwitch'+ count +'"></label></div><input class="form-control" placeholder="{{__('language.Enter')}} {{__('language.Question')}}"  type="text"  name="customQuestions['+ count + '][question]"/><button class="remove_field btn btn-danger btn-sm ml-2"><i class="fas fa-trash"></i></button></div>'); //add input field
							
					});  
					$('#optionalQuestions').on("click",".remove_field", function(e){ //user click on remove text links
						e.preventDefault(); 
						
						$(this).parents('.custom').remove();
					})
			
			
				});
			
			</script>
			
			<!----
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
						$('#optionalQuestions').append('<div class="input-group input-group-sm custom mb-2"><div class="custom-control custom-switch custom-switch-off-danger mr-n2 mt-1 custom-switch-on-success"><input type="checkbox" name="customQuestions['+ count + '][status]" class="custom-control-input" id="customSwitch'+ count +'"><label class="custom-control-label" for="customSwitch'+ count +'"></label></div><input class="form-control" placeholder="{{__('language.Enter')}} {{__('language.Question')}}"  type="text"  name="customQuestions['+ count + '][question]"/><button class="remove_field btn btn-danger btn-sm ml-2"><i class="fas fa-trash"></i></button></div>'); //add input field
					count++;
				});  
				$('#optionalQuestions').on("click",".remove_field", function(e){ //user click on remove text links
					e.preventDefault(); 
					
					$(this).parents('.custom').remove();
				})
			
			});
			</script>*/
			@endsection

@stop