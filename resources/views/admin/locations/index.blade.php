@extends('layouts/contentLayoutMaster')
@section('title','Locations')

@section('vendor-style') 
	{{-- vendor css files --}}
	<link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')) }}">
	<link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap4.min.css')) }}">
	<link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/buttons.bootstrap4.min.css')) }}">
	<link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/rowGroup.bootstrap4.min.css')) }}">
	<link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
@endsection

@section('content')
<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header border-bottom pt-1 pb-1">
				<div class="head-label">
					<h6 class="mb-0"></h6>
				</div>
				<div class="dt-action-buttons text-right">
					<div class="dt-buttons flex-wrap d-inline-flex">
						<button type="button" class="btn create-new btn-primary mr-1" onclick="window.location.href='@if(isset($locale)){{url($locale.'/locations/create')}} @else {{url('en/locations/create')}} @endif'"><span data-feather="plus" ></span> {{__('language.Add')}} {{__('language.Location')}}</button>
					</div>
				</div>
			</div>
			<div class="card-datatable table-responsive pt-0" style="padding: 15px; margin-top: 10px;">
				<table id="kt_table_1" class="dt-simple-header table">
					<thead>
						<tr>
							<th> {{__('language.Name')}}</th>
							<th> {{__('language.Contact')}} {{__('language.No')}}</th>
							<th> {{__('language.Address')}}</th>
							<th> {{__('language.Actions')}}</th>
						</tr>
					</thead>
					<tbody>
						@foreach($locations as $location)
							<tr>
								<td>{{$location->name}}</td>
								<td>{{$location->phone_number}}</td>
								<td>{{$location->city}}, {{$location->state}} ({{$location->zip_code}}) {{$location->country}}</td>
								<td class="text-nowrap">
									<a data-toggle="kt-tooltip" data-placement="top" title="" data-original-title="Edit Location" class="btn btn-sm btn-clean btn-icon btn-icon-md" href="@if(isset($locale)){{url($locale.'/locations/'.$location->id.'/edit')}} @else {{url('/locations/'.$location->id.'/edit')}} @endif" > <i data-feather="edit-2"></i></a>

									<a class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="modal" data-target="#confirm-delete{{ $location->id }}"> <i data-feather="trash-2"></i> </a>
								</td>
							</tr>

							<div class="modal fade" id="confirm-delete{{ $location->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
								@if(($location->employee->count()) > 0)
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<h5>{{__("language.Can't Delete Yet")}}..</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
											</div>
											<div class="modal-body">
												<div class="d-block text-center">
													<i class="fas fa-exclamation-triangle fa-2x text-danger"></i>
												</div>
												<h5>{{__("language.You've got")}}{{" ".$location->employee->count()." "}}{{__("language.employee(s) in this")}} {{__("language.Location")}}</h5>
												<h5 class="text-wrap">{{__("language.Don't leave them hanging; move them before deleting this")}} {{__("language.Location")}}.</h5>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-default" data-dismiss="modal">{{__('language.Cancel')}}</button>
												<button  type="button" data-dismiss="modal" class="btn btn-primary btn-ok">{{__('language.I\'ll fix it')}}!</button>
											</div>
										</div>
									</div>
								@else
									<div class="modal-dialog">
										<div class="modal-content">
											<form action="@if(isset($locale)){{url($locale.'/locations/'.$location->id)}} @else {{url('/locations/'.$location->id)}} @endif" method="post">
												<input name="_method" type="hidden" value="DELETE">
												{{ csrf_field() }}
												<div class="modal-header">
													<h5>Delete Location</h5>
	                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                                                    <span aria-hidden="true">&times;</span>
	                                                </button>
												</div>
												<div class="modal-body">
													<h5>{{__('language.Are you sure you want to delete this Location')}}? </h5>
													<hr>
													<ul class="list-group list-group-flush">
														<li class="list-group-item">{{__('language.Name')}}: {{$location->name}}</li>
														<li class="list-group-item">{{__('language.Street 1')}}: {{$location->street_1}}</li>
														<li class="list-group-item">{{__('language.Street 2')}}: {{$location->street_2}}</li>
														<li class="list-group-item">{{__('language.State')}}: {{$location->state}}</li>
														<li class="list-group-item">{{__('language.Zip')}} {{__('language.Code')}}: {{$location->zip_code}}</li>
														<li class="list-group-item">{{__('language.Country')}}: {{$location->country}}</li>
													</ul>
												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-outline-warning waves-effect" data-dismiss="modal">{{__('language.Cancel')}}</button>
													<button  type="submit" class="btn btn-danger btn-ok">{{__('language.Delete')}}</button>
												</div>
											</form>
										</div>
									</div>
								@endif
							</div>
						@endforeach
					</tbody>
				</table><!--end: Datatable -->
			</div><!-- /.card -->			
		</div>
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