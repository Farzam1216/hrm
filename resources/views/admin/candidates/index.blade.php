@extends('layouts.contentLayoutMaster')
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
@section('title','Candidates')
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
                                        <table class="dt-simple-header table dataTable dtr-column">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>#</th>					
                                                    <th>{{__('language.Avatar')}}</th>
                                                    <th>{{__('language.City')}}</th>
                                                    <th>{{__('language.Job Status')}}</th>
                                                    <th>{{__('language.Date')}}</th>
                                                    <th>{{__('language.Applied For')}}</th>
                                                    <th>{{__('language.Actions')}}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($candidates as $key => $applicant)
                                                <tr class="text-nowrap">
                                                    <td>{{$key+1}}</td>
                                                    <td><div class="d-flex justify-content-left align-items-center"><div class="avatar  mr-1"><img src="{{asset($applicant->avatar)}}" alt="Avatar" width="32" height="32"></div><div class="d-flex flex-column"><span class="emp_name text-truncate font-weight-bold">{{$applicant->name}}</span><small class="emp_post text-truncate text-muted">{{$applicant->job->title}}</small></div></div></td>
                                                    <td>{{$applicant->city}}</td>
                                                    <td>{{$applicant->job_status}}</td>
                                                    <td>{{$applicant->created_at->format('d-M-Y')}}</td>
                                                    <td>{{$applicant->job->title}}</td>
                                                    <td nowrap="" class="text-center">
                                                        <a href="@if(isset($locale)){{url($locale.'/candidate',$applicant->id)}} @else {{url('en/candidate',$applicant->id)}} @endif"
                                                            class="btn btn-md btn-clean btn-icon btn-icon-md" data-toggle="tooltip"
                                                            data-placement="top" title="" data-original-title="Show Application">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </td>
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
