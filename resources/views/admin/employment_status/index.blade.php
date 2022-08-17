@extends('layouts.contentLayoutMaster')
@section('title','Employment Status')

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
										<a href="@if(isset($locale)){{url($locale.'/employment-status/create')}} @else {{url('en/employment-status/create')}} @endif"
										   class="btn create-new btn-primary mr-1 waves-effect waves-float waves-light">
											<i data-feather='plus'></i>
											{{__('language.Add')}} {{__('language.Employment')}} {{__('language.Status')}}
										</a>
									</div>
								</div>
                                <div class="card-datatable table-responsive">									
                                    <table class="dt-simple-header table dataTable dtr-column">
                                        <thead class="thead-light">
                                            <tr class="text-nowrap">
                                                <th>#</th>					
												<th> {{__('language.Name')}}</th>
                                                <th> {{__('language.Description')}}</th>
                                                <th> {{__('language.Status')}}</th>
                                                <th> {{__('language.Actions')}}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($employmentStatuses as $key => $employment_statuss)
                                                <tr>
                                                    <td>{{$key+1}}</td>
                                                    <td>{{$employment_statuss->employment_status}}</td>
                                                    <td>{{$employment_statuss->description}}</td>
                                                    <td>
                                                        @if($employment_statuss->status==1)
                                                        <span
                                                            class="btn btn-bold btn-sm btn-font-sm  btn-label-success">{{__('language.Active')}}</span>
                                                        @else
                                                        <span
                                                            class="btn btn-bold btn-sm btn-font-sm  btn-label-danger">{{__('language.InActive')}}</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-nowrap">
                                                        <a class="btn btn-sm btn-clean btn-icon btn-icon-md" href="@if(isset($locale)){{url($locale.'/employment-status/'.$employment_statuss->id.'/edit')}} @else {{url('en/employment-status/'.$employment_statuss->id.'/edit')}} @endif" title="Edit"> <i
                                                            data-feather="edit-2"></i></a>									
                                                        <a class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="modal"
                                                        data-target="#confirm-delete{{ $employment_statuss->id }}"
                                                        data-original-title="Delete"><i
                                                        data-feather="trash-2"></i></a>
                                                    </td>        
                                                </tr>

                                                <div class="modal fade" id="confirm-delete{{ $employment_statuss->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                    @if(($employment_statuss->employees->count()) > 0)
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
                                                                    <h5">{{__("language.You've got")}}{{" ".$employment_statuss->employees->count()." "}}{{__("language.employee(s) in this")}} {{__("language.Employment Status")}}</h5>
                                                                    <h5 class="text-wrap">{{__("language.Don't leave them hanging;")}} {{__("language.Move them before deleting this")}}
                                                                    {{__("language.Employment Status")}}.</h5>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-outline-warning waves-effect" data-dismiss="modal">{{__('language.Cancel')}}</button>
                                                                    <button  type="button" data-dismiss="modal" class="btn btn-primary btn-ok">{{__('language.I\'ll fix it')}}!</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <form action="@if(isset($locale)){{url($locale.'/employment-status',$employment_statuss->id)}} @else {{url('en/employment-status',$employment_statuss->id)}} @endif" method="post">
                                                                <input name="_method" type="hidden" value="DELETE">
                                                                    {{ csrf_field() }}
                                                                    <div class="modal-header">
                                                                        <h5>Delete Employment Status</h5>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <h5>Are you sure you want to delete "{{ $employment_statuss->employment_status}}" Employment Status?</h5>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-outline-warning waves-effect" data-dismiss="modal">{{__('language.Cancel')}}</button>
                                                                        <button  type="submit" class="btn btn-danger waves-effect waves-float waves-light">{{__('language.Delete')}}</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
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