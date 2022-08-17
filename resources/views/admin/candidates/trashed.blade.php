@extends('layouts.contentLayoutMaster')
@section('title','Trashed Candidates')
@section('heading')
<!-- Content Header (Page header) -->
<div class="content-header">
		<div class="container-fluid">
		  <div class="row mb-2">
			<div class="col-sm-6">
			  <h1 class="m-0 text-dark">{{__('language.Trashed')}} {{__('language.Candidates')}}</h1>
			</div><!-- /.col -->
			<div class="col-sm-6">
			  <ol class="breadcrumb float-sm-right">
				<li class="breadcrumb-item"><a href="#">{{__('language.Hiring')}}</a></li>
				<li class="breadcrumb-item active"><a href="@if(isset($locale)){{url($locale.'/candidates')}} @else {{url('en/candidates')}} @endif">{{__('language.Candidates')}}</a></li>
				<li class="breadcrumb-item active">{{__('language.Trashed')}} {{__('language.Candidates')}}</li>
			  </ol>
			</div><!-- /.col -->
		  </div><!-- /.row -->
		
	  <div class="row justify-content-end">
		
            <div class="col-2">
		<button type="button" class="btn btn-info btn-md btn-rounded m-t-10 float-right" onclick="window.location.href='@if(isset($locale)){{url($locale.'/candidates/hired')}} @else {{url('en/candidates/hired')}} @endif'">{{__('language.Hired')}}</button>
			</div>
	  </div>
	</div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
		@stop


@section('content')
<!-------row------>
<div class="row">
	<!-------col-------->
	<div class="col-lg-12">
		<!-------card-------->
			<div class="card">
                        <!-------card body------->
					<div class="card-body">
						 <!----display candiadtes if present in system-->
							@if($candidates->count() > 0)
					  <table id="kt_table_1" class="table table-bordered table-striped table-checkable">
						<thead>
						<tr>
								<th>{{__('language.Avatar')}}</th>
						<th>{{__('language.Name')}}</th>
						<th>{{__('language.City')}}</th>
						<th>{{__('language.Job Status')}}</th>
						<th>{{__('language.Applied For')}}</th>
						<th>{{__('language.CV')}}</th>
						<th>{{__('language.Actions')}}</th>
						</tr>
						</thead>
						<tbody>
								@foreach($candidates as $applicant)
						<tr>
							<td><img src="{{asset($applicant->avatar)}}" alt="user" width="40" class="img-circle" /></td>
							<td>
								<a>{{$applicant->name}}</a>
							</td>
							<td>{{$applicant->city}}</td>
							<td>{{$applicant->job_status}}</td>
							<td>{{$applicant->job->title}}</td>
							<td>
								<a target="_blank" href="{{asset($applicant->cv)}}" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="tooltip" data-placement="top" title="" data-original-title="Click To Open CV" style="font-size: 20px">
									<i class="fas fa-file-pdf"></i>
								</a>
							</td>
							<td nowrap="">
								<a href="@if(isset($locale)){{url($locale.'/candidate/restore',$applicant->id)}} @else {{url('en/candidate/restore',$applicant->id)}} @endif" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="tooltip" data-placement="top" title="" data-original-title="Restore Application">
									<i class="fas fa-history"></i>
								</a>
								<a href="@if(isset($locale)){{url($locale.'/candidate/kill',$applicant->id)}} @else {{url('en/candidate/kill',$applicant->id)}} @endif" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete Application">
									<i class="fas fa-trash"></i>
								</a>
							</td>
						</tr>
					@endforeach
						</tbody>
					  </table>
					  @else
					<p class="text-center" >{{__('language.No New Candidate Found')}}</p>
			@endif
				  <!--end: Datatable -->
					</div>
					<!-- /.card-body --> 
	</div>
	<!-------/.card---->	
	</div>
	<!--------/.col----->
</div>
<!---------/.row------->

@stop