@extends('layouts/contentLayoutMaster')
@section('title','Jobs')
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
        <div class="ml-1 mr-1">
            <div class="tab-content" id="custom-tabs-two-tabContent">
                <div class="tab-pane fade show active" id="custom-tabs-two-home" role="tabpanel" aria-labelledby="custom-tabs-two-home-tab">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="">
								<div class="card-header border-bottom pb-1 pt-1">
									<div class="head-label">
										<h6 class="mb-0"></h6>
									</div>
									<div class="dt-action-buttons text-right dt-buttons flex-wrap d-inline-flex">
										<a href="@if(isset($locale)){{url($locale.'/job/create')}} @else {{url('en/job/create')}} @endif"
										   class="btn create-new btn-primary mr-1 waves-effect waves-float waves-light">
											<i data-feather='plus'></i>
											{{__('language.Add')}} {{__('language.New')}} {{__('language.Job')}}
										</a>
									</div>
								</div>
                                <div class="card-datatable table-responsive">
									
                                    <table class="dt-simple-header table dataTable dtr-column">
                                        <thead class="thead-light">
                                            <tr class="text-nowrap">
                                                <th>#</th>					
												<th> {{__('language.Title')}}</th>
												<th> {{__('language.Job Status')}}</th>
												<th> {{__('language.Location')}}</th>
												<th> {{__('language.Actions')}} </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($jobs as $key => $job)
                                            <tr>
												<td>{{$key+1}}</td>
												<td>{{$job->title}}</td>
												<td>{{ucfirst($job->status)}}</td><td>{{isset($job->location_id) ? $job->Locations->name.'('.$job->Locations->city.')': ''}}</td>
							
												<td class="text-nowrap">
														<a class="btn btn-sm btn-clean btn-icon btn-icon-md" href="@if(isset($locale)){{url($locale.'/job/'.$job->id.'/edit')}} @else {{url('en/job/'.$job->id.'/edit')}} @endif" title="Edit"> <i data-feather="edit-2"></i></a>									
												<a class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="modal"
														data-target="#confirm-delete{{ $job->id }}"
														data-original-title="Delete"> <i data-feather="trash-2"></i> </a>
												</td>
												<div class="modal fade" id="confirm-delete{{ $job->id }}"
													tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
													aria-hidden="true">
												   <div class="modal-dialog">
													   <div class="modal-content">
														   <form action="@if(isset($locale)){{url($locale.'/job',$job->id)}} @else {{url('en/job',$job->id)}} @endif"
																 method="post">
																 @method('DELETE')
																 @csrf
															   <div class="modal-header">
																   {{__('Are you sure you want to delete this Job and all of its Applicants?')}}
															   </div>
															   <div class="modal-header">
																   <h4>{{ $job->title }}</h4>
															   </div>
															   <div class="modal-footer">
																   <button type="button" class="btn btn-default"
																		   data-dismiss="modal">{{__('language.Cancel')}}</button>
																   <button type="submit"
																		   class="btn btn-danger btn-ok">{{__('language.Delete')}}</button>
															   </div>
														   </form>
													   </div>
												   </div>
											   </div>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
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
