@extends('layouts/contentLayoutMaster')
@section('title','Work Schedules')
@section('vendor-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/buttons.bootstrap4.min.css')) }}">
@endsection

@section('page-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('css/base/pages/app-user.css')) }}">
    <style>
        table.dataTable thead th {
            white-space: nowrap
        }

    </style>
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
                            <button type="button" class="btn create-new btn-primary mr-1"
                                    onclick="window.location.href='@if(isset($locale)){{url($locale.'/work-schedule/create')}} @else {{url('en/work-schedule/create')}} @endif'">
                                <i data-feather="plus"></i> {{__('language.Add')}} {{__('language.Work Schedules')}}
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-datatable table-responsive pt-0" style="padding: 15px;">
                    <table class="dt-simple-header table table-sm">
                        <thead>
                        <tr>
                            <th> {{__('language.Title')}}</th>
                            <th> {{__('language.Start Time')}}</th>
                            <th> {{__('language.Flex Time In')}}</th>
                            <th> {{__('language.Break Time')}}</th>
                            <th> {{__('language.Back Time')}}</th>
                            <th> {{__('language.End Time')}}</th>
                            <th> {{__('language.Hours')}}</th>
                            <th> {{__('language.Actions')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if($workScedules->count() > 0)
                            @foreach($workScedules as $key => $workScedule)
                                <tr>
                                    <td>{{$workScedule->title}}</td>
                                    <td>{{$workScedule->schedule_start_time}}</td>
                                    <td>{{$workScedule->flex_time_in}}</td>
                                    <td>{{$workScedule->schedule_break_time}}</td>
                                    <td>{{$workScedule->schedule_back_time}}</td>
                                    <td>{{$workScedule->schedule_end_time}}</td>
                                    <td>{{$workScedule->schedule_hours}}</td>
                                    <td class="text-nowrap">
                                        <a type="button"
                                           class="btn btn-sm btn-clean btn-icon btn-icon-md waves-effect waves-light"
                                           data-placement="top" title="" data-toggle="tooltip"
                                           href="@if(isset($locale)){{url($locale.'/assign/work-schedule/'.$workScedule->id.'/create')}} @else {{url('en/assign/work-schedule/'.$workScedule->id.'/create')}} @endif"
                                           data-original-title="Assign Work Schedule To Employees">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                 stroke-linecap="round" stroke-linejoin="round"
                                                 class="feather feather-link-2">
                                                <path d="M15 7h3a5 5 0 0 1 5 5 5 5 0 0 1-5 5h-3m-6 0H6a5 5 0 0 1-5-5 5 5 0 0 1 5-5h3"></path>
                                                <line x1="8" y1="12" x2="16" y2="12"></line>
                                            </svg>
                                        </a>
                                        <a class="btn btn-sm btn-clean btn-icon btn-icon-md"
                                           href="@if(isset($locale)){{url($locale.'/work-schedule/'.$workScedule->id.'/edit')}} @else {{url('en/work-schedule/'.$workScedule->id.'/edit')}} @endif">
                                            <i data-feather="edit-2"></i></a>
                                        <a class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="modal"
                                           data-target="#confirm-delete{{ $workScedule->id }}"
                                           data-original-title="Close"> <i data-feather="trash-2"></i> </a>
                                        <div class="modal fade" id="confirm-delete{{ $workScedule->id }}" tabindex="-1"
                                             role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="@if(isset($locale)){{url($locale.'/work-schedule',$workScedule->id)}} @else {{url('en/work-schedule',$workScedule->id)}} @endif"
                                                          method="post">
                                                        <input name="_method" type="hidden" value="DELETE">
                                                        {{ csrf_field() }}
                                                        <div class="modal-header">
                                                            <b>{{__('language.Are you sure you want to delete this Work Schedule?')}}</b>
                                                        </div>
                                                        <div class="modal-header">
                                                            <h4>{{ $workScedule->title }}</h4>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button"
                                                                    class="btn btn-outline-warning waves-effect"
                                                                    data-dismiss="modal">{{__('language.Cancel')}}</button>
                                                            <button type="submit"
                                                                    class="btn btn-danger waves-effect waves-float waves-light btn-ok">{{__('language.Delete')}}</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                    <!--end: Datatable -->
                    <!--end: Datatable -->
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        ​

    </div>
    </div> ​
@stop
@section('vendor-script')
    {{-- Vendor js files --}}
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