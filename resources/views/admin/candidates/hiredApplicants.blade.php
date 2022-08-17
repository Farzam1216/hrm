@extends('layouts.contentLayoutMaster')
@section('title','Hired Candidates')
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

<div class="">
	<div class="card card-primary card-outline card-tabs">
		<hr class="ml-1 mr-1">
		{{--  <div class=" p-0 pl-1 border-bottom-0">
			<ul class="nav nav-tabs" id="custom-tabs-two-tab" role="tablist">
				<li class="nav-item col-lg-2 col-md-2 col-sm-12">
					<a class="nav-link active" id="custom-tabs-two-home-tab" data-toggle="pill" href="#custom-tabs-two-home" role="tab" aria-controls="custom-tabs-two-home" aria-selected="true">ALL</a>
				</li>
			</ul>
		</div>  --}}
		<div class="ml-1 mr-1">
			<div class="tab-content" id="custom-tabs-two-tabContent">
				<div class="tab-pane fade show active" id="custom-tabs-two-home" role="tabpanel" aria-labelledby="custom-tabs-two-home-tab">
					<div class="row">
						<div class="col-lg-12">
							<div class="">
								<div class="card-datatable table-responsive">
									@if($candidates->count() > 0)
									<table class="dt-simple-header table dataTable dtr-column">
										<thead class="thead-light">
											<tr class="text-center">
												<th>#</th>					
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
											@foreach($candidates as $key => $applicant)
											<tr class="text-center">
												<td>{{$key+1}}</td>
												<td class="text-center"><img src="{{asset($applicant->avatar)}}" alt="user" width="40"
													class="img-circle"/></td>
												<td><a>{{$applicant->name}}</a></td>
												<td>{{$applicant->city}}</td>
												<td>{{$applicant->job_status}}</td>
												<td>{{$applicant->job->title}}</td>
												<td><a target="_blank" href="{{asset($applicant->cv)}}"
													class="btn btn-md btn-clean btn-icon btn-icon-lg" data-toggle="tooltip"
													data-placement="top" title="" data-original-title="Click To Open CV"
													style="font-size: 20px">
													<i class="fas fa-file-pdf"></i>
												</a>
											    </td>
												<td nowrap="">
													<a href="@if(isset($locale)){{url($locale.'/candidate/retire',$applicant->id)}} @else {{url('en/candidate/retire',$applicant->id)}} @endif"
														class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="tooltip"
														data-placement="top" title="" data-original-title="Restore Application">
														<i class="fas fa-history"></i>
													</a>
													<a href="@if(isset($locale)){{url($locale.'/candidate/delete',$applicant->id)}} @else {{url('en/candidate/delete',$applicant->id)}} @endif"
														class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="tooltip"
														data-placement="top" title="" data-original-title="Delet Application">
														<i class="fas fa-trash"></i>
													</a>
												</td>
											</tr>
											@endforeach
										</tbody>
									</table>
									@else
									<p class="text-center"> {{__('language.No New Applicant Found')}}</p>
									@endif
									<!--end: Datatable -->
								</div>
							</div>
							<!--end::Portlet-->
						</div>
					</div>
				</div>
				
			</div>
		</div>
		<!-- /.card -->
	</div>
</div>
@section('vendor-script')
    {{-- vendor files --}}
    <script src="{{ asset(mix('vendors/js/tables/datatable/jquery.dataTables.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.bootstrap4.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.responsive.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/responsive.bootstrap4.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.checkboxes.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.buttons.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/buttons.bootstrap4.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/jszip.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/pdfmake.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/vfs_fonts.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/buttons.html5.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/buttons.print.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.rowGroup.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>

    @endsection
    @section('page-script')
    {{-- Page js files --}}
    <script src="{{ asset(mix('js/scripts/tables/table-datatables-basic.js')) }}"></script>
@endsection
@stop