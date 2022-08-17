@extends('layouts.contentLayoutMaster')
@section('title','Canned Questions')

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
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header border-bottom pb-1 pt-1">
                <div class="head-label">
                    <h6 class="mb-0"></h6>
                </div>
                <div class="dt-action-buttons text-right dt-buttons flex-wrap d-inline-flex">
                    <a href="@if(isset($locale)){{url($locale.'/questions/create')}} @else {{url('en/questions/create')}} @endif"
                       class="btn create-new btn-primary mr-1 waves-effect waves-float waves-light">
                        <i data-feather='plus'></i>
                        {{__('Add Canned Questions')}}
                    </a>
                </div>
            </div> <!--end card-header-->
            <div class="card-datatable table-responsive pt-0" style="padding: 15px;">
                <table class="dt-simple-header table dataTable dtr-column">
                    <thead class="head-light">
                        <tr>
                            <th>#</th>
                            <th> {{__('language.Question')}}</th>
                            <th> {{__('language.Type')}}</th>
                            <th> {{__('language.Actions')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ques as $key => $question)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{ $question->question}}</td>
                                <td>{{ $question->fieldType}}</td>
                                <td class="text-nowrap">
                                    <a class="text-dark" data-toggle="tooltip" data-placement="top" title=""
                                    href="@if(isset($locale)){{url($locale.'/questions/'.$question->id.'/edit')}}
                                    @else {{url('en/questions/'.$question->id  .'/edit')}} @endif">
                                     <i data-feather="edit-2" class="mr-40"> </i></a>
                                    
                                    <a class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="modal"
                                        data-target="#confirm-delete{{  $question->id }}"
                                        data-original-title="Close">
                                        <i data-feather="trash-2"></i>
                                    </a>
                                </td>
                            </tr>

                            <div class="modal fade" id="confirm-delete{{ $question->id }}" tabindex="-1"
                                role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                               <div class="modal-dialog">
                                   <div class="modal-content">
                                       <div class="modal-header">
                                            <h5>Delete Canned Question</h5>
                                           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                               <span aria-hidden="true">&times;</span>
                                           </button>
                                       </div>
                                       <form action="@if(isset($locale)){{url($locale.'/questions/'.$question->id)}} @else {{url('/questions/'.$question->id)}} @endif"
                                             method="post">
                                           <input name="_method" type="hidden" value="DELETE">
                                           {{ csrf_field() }}
                                           <div class="modal-body">
                                               <h5>Are you sure you want to delete "{{$question->question}}" canned question? </h5>
                                           </div>
                                           <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-warning waves-effect" data-dismiss="modal">{{__('language.Cancel')}}</button>
                                                <button  type="submit" class="btn btn-danger waves-effect waves-float waves-light btn-ok">{{__('language.Delete')}}</button>
                                           </div>
                                       </form>
                                   </div>
                               </div>
                           </div>
                        @endforeach
                    </tbody>
                </table>
            </div> <!--end card-datatable-->
        </div> <!--end card-->
    </div> <!--end col-lg-12-->
</div> <!--end row-->

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
    <script src="{{ asset(mix('js/scripts/tables/table-datatables-basic.js')) }}"></script>
@endsection
@stop