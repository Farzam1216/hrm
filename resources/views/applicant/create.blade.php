<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<!-- Tell the browser to be responsive to screen width -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="author" content="">
	<!-- Favicon icon -->
	<title>HRM|Glowlogix</title>
	<!-- Bootstrap Core CSS -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
		integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
		integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
	</script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
		integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
	</script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
		integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
	</script>
	<!-- Custom CSS -->
	<link href="{{ asset('asset/css/style.css') }}" rel="stylesheet">
	<!-- Theme style -->
	<link rel="stylesheet" href="{{asset('css/app.css')}}">
	<link rel="shortcut icon" type="image/x-icon" href="{{asset('asset/media/logos/favicon.ico')}}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/perfect-scrollbar/perfect-scrollbar.min.css')) }}"></link>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600" rel="stylesheet">
{{--    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>--}}
    <script src="{{ asset(mix('vendors/js/jquery/jquery.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/fontawesome/fontawesome.js')) }}"></script>

    {{-- Include core + vendor Styles --}}
    @include('panels/styles')
</head>
<style>
	body {
		overflow: hidden auto !important;
		position: absolute !important;

	}

	@media(max-width:767px) {
		.login-box {
			width: 370px !important;
		}
	}

	.login-box {

		width: 700px;
	}
</style>
</head>

<body class="login-register"
	style="background-image:url({{ asset('asset/media/misc/hiring.jpg') }});height: auto; width:100%;background-size:cover;">
	<!-- ============================================================== -->
	<!-- Preloader - style you can find in spinners.css -->
	<!-- ============================================================== -->
	{{--<div class="preloader">--}}
	{{--	<svg class="circular" viewBox="25 25 50 50">--}}
	{{--		<circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"></circle> </svg>--}}
	{{--</div>--}}

	<!-- ============================================================== -->
	<!-- Main wrapper - style you can find in pages.scss -->
	<!-- ============================================================== -->
	<section id="wrapper">
		<div class="container">
			<div class="row">
				<div class="col-sm-4 "></div>
				<div class="login-box card col-sm-4 mt-4">
					<div class=" card-body ">
	
						<!---Error and Success Messages--->
						@include('admin.includes.errors')
						@if(Session::has('success'))
						<div class="alert alert-success alert-dismissible fade show">
							<div class="alert-body">
								<strong>Success!</strong> {{Session::get('success')}}
							</div>
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							  <span aria-hidden="true">×</span>
							  </button>
						</div>
						@endif
						@if (Session::has('error'))
						<div class="alert alert-warning alert-dismissible fade show">
							<div class="alert-body">
								<strong>Success!</strong> {{Session::get('error')}}
							</div>
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							  <span aria-hidden="true">×</span>
							  </button>
						</div>
						@endif 
	
	
						<!----Application Form-->
						<h1> Apply for Job </h1>
						<form id="applicant-form" class="form form-horizontal" action="{{route('applicant.store')}}" method="post"
						enctype="multipart/form-data">
						{{csrf_field()}}
						<div class="row">
							<div class="form-group col-md-12">
								<label for="job_id">Select Position</label></label><span
								class="text-danger"> *</span>
								<select name="position" id="job_id" type="select" class="job_id form-control select"
									required>
									<option value="">Select Position</option>

									<!---Jobs with open status--->
									@foreach($jobs as $j)
									@if($j->status == "open")

									<option joblocation="{{$j->locations->name}}" jobquestions="{{$j->que}}"
										jobexperience="{{$j->minimum_experience}}"
										jobtype="{{$j->employmentStatus->employment_status}}"
										jobDesc="{{$j->description}}" value="{{$j->id}}" @if(old("job_id")==$j->id )
										selected
										@endif>{{$j->title}}&nbsp({{isset($j->designation) ? $j->designation->designation_name : ''}})
									</option>
									@endif
									@endforeach
									<!---/Jobs with open status--->
								</select>
								<div class="valid-feedback">
									Looks good!
								</div>
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-12" id="jobTypeDiv" style="display:none;">
								<label>Employment Type</label>
								<input type="hidden" id="type" name='type'>
								<div id='typediv'> </div>

							</div>
							<div class="show col-md-12" style="display: none" id="descriptionDiv">
								<label>Details:</label>
								<div id="description" role="alert" class="alert alert-secondary py-2"
									style="color:black;">
								</div>
							</div>

						</div>

						<div class="row">
							<div class="form-group col-md-6" id="locationDiv" style="display:none;">
								<label>Location</label>
								<input type="hidden" id="city" name='city'>
								<div id='citydiv'> </div>

							</div>
							<div class="form-group col-md-6" id="minimumExpDiv" style="display:none;">
								<label>Minimum Experience</label>
								<input type="hidden" id="experience" name='experience'>
								<div id='experiencediv'> </div>

							</div>
						</div>
						<hr>
						<div class="row">
							<div class="form-group col-md-6">
								<label for="name">Name</label></label><span
								class="text-danger"> *</span>
								<input type="text" name="name" placeholder="Enter name here" class="form-control"
									value="{{old('name')}}" required data-msg-required="Name is required">
							</div>
							<div class="form-group col-md-6">
								<label for="fname">Father Name</label></label><span
								class="text-danger"> *</span>
								<input type="text" name="fname" placeholder="Enter name here" class="form-control"
									value="{{old('fname')}}" required data-msg-required="Father Name is required">
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-12">
								<label for="email">Email</label></label><span
								class="text-danger"> *</span>
								<input type="text" name="email" placeholder="Enter email here" class="form-control"
									value="{{old('email')}}" required data-msg-required="Email is required">
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-6">
								<div>
									<label>Select your avatar: </label></label><span
									class="text-danger"> *</span>
									<input type="file" name="avatar" accept="image/*" required
										value="{{old('avatar')}}" required />
								</div>
							</div>
							{{--<div class="form-group col-md-6">
							<div class="cv">
								<label >Resume:</label><br>
								<input type="file" name="cv" required value="{{old('cv')}}"/>
						</div>
				</div>--}}
			</div>
			<hr>
			<div class="row">

				<div class="col-md-12" id="cannedQuestions">
					<!----Canned Questions will display here--->
				</div>

			</div>

			<div class="row">


				<div class="col-md-12" id="customQuestions">
					<!----Custom Questions will display here--->
				</div>
			</div>
			<div class="form-group">
				<label for="job_status">Job status</label>
				<select name="job_status" id="job_status" class="form-control">
					<option value="Employed" @if(old("job_Status")=="Employed" ) selected @endif>Currently working
					</option>
					<option value="Unemployed" @if(old("job_Status")=="Unemployed" ) selected @endif>Currently not
						working</option>
				</select>
			</div>
			<div class="form-group">
				<button class="btn btn-primary" id="submit" name="submit" type="submit" align="right"> Submit
					Application</button>
			</div>
			</form>
				<div class="col-sm-4"></div>
			</div>
			</div>
			</div>
			
		</div>
	</section>
</body>

<!----------------------------------JS------------------------------------------------>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/forms/validations/applcant-form.js'))}}"></script>

<script type="text/javascript">
	$(document).ready(function () {
		$(document).on('change', '.category', function () {
			var cat_id = $(this).val();
			var div = $(this).parent();
			$.ajax({
				type: 'get',
				url: '/findjob',
				data: {
					'id': cat_id
				},
				success: function (data) {
					var model = $('#job');
					model.empty();
					$.each(data, function (i, item) {
						model.append("<option value='" + item.id + "'>" + item.title + "</option>");
					});
				}
			});
		});
	});

             


<!--------JQuery to display Job information depending upon selected job----->

    jQuery(document).ready(function($) {		
//gb	var ques;
		count=0;
        $(".select").on('change', function() {
			$('#customQuestions').empty();
			$('#cannedQuestions').empty();
			var location=$( "#job_id option:selected" ).attr('joblocation');
			var experience=$( "#job_id option:selected" ).attr('jobexperience');
			var type=$( "#job_id option:selected" ).attr('jobtype');
			var ques=$( "#job_id option:selected" ).attr('value');
			var canned=$( "#job_id option:selected" ).attr('jobquestions');
			
			
			$(".show").show();
			$("#locationDiv").show();
			$("#citydiv").text(location);
			$("#city").val(location);
			$("#minimumExpDiv").show();
			$("#experiencediv").text(experience);
			$("#experience").val(experience);
			$("#jobTypeDiv").show();
			$("#typediv").text(type);
			$("#type").val(type);
			var description=$( "#job_id option:selected" ).attr('jobDesc');
			$("#description").html(description);
			r='';
			 v='';
			 qid='';

			<!-------Canned Questions-------->
			var jobs = {!!  $jobs !!};
					$.each( jobs, function( key, value) {
			//	console.log(JSON.stringify(value['que']));
				
				//console.log(JSON.stringify(value['que']) + " " + "question");
				if(value['id']== ques){
					$.each( value['que'], function( key, value) {
						var id=value['id'];
							$.each( value['jobquestions'], function( key, data) {
								console.log(value['fieldType']);
						if(key=="id")
						{
							qid=data;
							console.log(qid);

						}
							if(key=="status")
							{
								if(data==1)
							{
								r="required"
							}
							else{
								r="";
							}

							}});


console.log("Hello");
						if(value['type_id'] == 1){
							if(value['fieldType'] == "file")
							{
								$('#cannedQuestions').append('<div class="cv"><label>'+value['question']+'</label><br><input type="text" hidden name="question['+key+'][fieldType]" value="'+value['fieldType']+'"><input type="file" name="question['+key+'][answer]" '+r+' "/><input type="text" hidden name="question['+key+'][qid]" value="'+qid+'"></div><br>'); //add input field

					
								
							}
							else {
								$('#cannedQuestions').append('<div class="form-group mb-2"><label>'+value['question']+'</label><input type="text" hidden name="question['+key+'][fieldType]" value="'+value['fieldType']+'"><input type="text" hidden name="question['+key+'][qid]" value="'+qid+'"><br><input class="form-control" placeholder="Enter answer here." type="text" '+r+' name="question['+key+'][answer]"/></div>'); //add input field

							

							}
						}
				
				
				});
				}
});


			<!-------Custom Questions----->
			var jobs = {!!  $jobs !!};
			
			$.each( jobs, function( key, value) {
				
				//console.log(value['que']+  " " + " Bilkul Bahir");
				if(value['id']== ques){
					$.each( value['que'], function( key, value) {
						var id=value['id'];

						$.each( value['jobquestions'], function( key, data) {
							//console.log(JSON.stringify(data)+  " " + key + " andr");
							if(key=="id")
						{
							qid=data;
							console.log(qid);

						}
							if(key=="status")
							{
								if(data==1)
							{
								r="required"
							}
							else{
								r="";
							}

							}});		
	


						if(value['type_id'] != 1){
							$('#customQuestions').append('<div class="form-group mb-2"><label>'+value['question']+'</label><input type="text" hidden name="question['+key+'][qid]" value="'+qid+'"> <input type="text" hidden name="question['+key+'][fieldType]" value="'+value['fieldType']+'"><br><input class="form-control" placeholder="Enter answer here." '+r+'  type="text"  name="question['+key+'][answer]"/></div>'); //add input field

						}
				
				
				});
				}
});

	/*		var obj = {!!  $questions !!};
			c='';
			 v='';
	$.each( obj, function( key, value) {
	if(value['job_id']==ques){
	if(value['status'] == "Yes" || value['status']== 1 )
	{ v="Yes"; r="required"; } 
	else  {  v="No";
	r=""; }
	

	$('#customQuestions').append('<div class="form-group mb-2"><label>'+value['question']+'</label><input type="text" hidden name="question['+key+'][desc]" value="'+value['question']+'"><input type="text" hidden name="question['+key+'][qid]" value="'+value['id']+'"><br><input class="form-control" placeholder="Enter answer here." '+r+'  type="text"  name="question['+key+'][answer]"/></div>'); //add input field
 count++;}
});*/
        });

    });

	
</script>