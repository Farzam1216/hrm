@extends('layouts/contentLayoutMaster')
@section('title','Attendance Approval')
@section('vendor-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/buttons.bootstrap4.min.css')) }}">
@endsection

@section('page-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('css/base/pages/app-user.css')) }}">
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">

                <div class="card-header border-bottom pt-1 pb-1">
                    <div class="head-label">
                        <h6 class="mb-0"></h6>
                    </div>
                </div>
                <div class="card-datatable table-responsive pt-0" style="padding: 15px;">
                    <table class="dt-simple-header table dt-responsive">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th> {{__('language.Employee')}} {{__('language.Name')}}</th>
                            <th> {{__('language.Month')}}</th>
                            <th> {{__('language.Status')}}</th>
                            <th> {{__('language.Approver')}} {{__('language.Name')}}</th>
                            <th> {{__('language.Comments')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if($approvals->count() > 0)
                            @foreach($approvals as $key => $approval)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{$approval->employee->firstname}} {{$approval->employee->lastname}}</td>
                                    <td>
                                        {{$approval->month}}
                                    </td>
                                    <td>
                                        @if($approval->status == 'approve')
                                            <h6 class="text-success">Approved</h6>
                                        @else
                                            <h6 class="text-danger">Rejected</h6>
                                        @endif
                                    </td>
                                    <td>
                                        {{$approval->approver->firstname}} {{$approval->approver->lastname}}
                                    </td>

                                    <td>

                                        <a class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="modal"
                                           data-target="#new-task-modal{{$approval->id}}" data-original-title="Close">
                                            <i data-feather="eye"></i> </a>

                                    <!-- Right Sidebar starts -->
                                        <div class="modal modal-slide-in sidebar-todo-modal fade"
                                             id="new-task-modal{{$approval->id}}">
                                            <div class="modal-dialog sidebar-lg">
                                                <div class="modal-content p-0">
                                                    <form id="form-modal-todo" class="todo-modal needs-validation"
                                                          novalidate onsubmit="return false">
                                                        <button
                                                                type="button"
                                                                class="close font-large-1 font-weight-normal py-0"
                                                                data-dismiss="modal"
                                                                aria-label="Close"
                                                        >
                                                            ×
                                                        </button>
                                                        <div class="modal-header align-items-center mb-1">
                                                            <h5 class="modal-title">Approval Comments</h5>

                                                            <div class="todo-item-action d-flex align-items-center justify-content-between ml-auto">


                                                            </div>
                                                        </div>
                                                        <div class="modal-body flex-grow-1 pb-sm-0 pb-3">
                                                            <div class="action-tags">
                                                                @foreach($approval->comments as $comment)
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <div class="form-group">
                                                                                <label for="exampleFormControlTextarea1">Comment</label>
                                                                                <textarea
                                                                                        class="form-control"
                                                                                        id="exampleFormControlTextarea1"
                                                                                        rows="3"
                                                                                >{{$comment->comment}}</textarea>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>

                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Right Sidebar ends -->
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