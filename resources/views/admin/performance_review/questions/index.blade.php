@extends('layouts/contentLayoutMaster')
@section('title','Questions')
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
                <div class="dt-action-buttons text-right">
                    <div class="dt-buttons flex-wrap d-inline-flex">
                        <button type="button" class="btn create-new btn-primary mr-1" onclick="window.location.href='@if(isset($locale)){{route('questions.create', [$locale])}} @else {{route('questions.create', ['en'])}} @endif'">
                            <i data-feather="plus"></i> {{__('language.Add')}} {{__('language.Question')}}
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-datatable table-responsive pt-0" style="padding: 15px;">
                <table class="dt-simple-header table dt-responsive">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th> {{__('language.Question')}}</th>
                            <th> {{__('language.Field Type')}}</th>
                            <th> {{__('language.Question Placement')}}</th>
                            <th> {{__('language.Actions')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($questions as $key => $question)
                        <tr>
                            <td>{{$key+1}}</td>
                            <td>{{substr($question->question, 0, 40) . '...'}}</td>
                            <td>{{$question->field_type}}</td>
                            <td>{{$question->placement}}</td>
                            <td>
                                <a type="button" class="btn btn-sm btn-clean btn-icon btn-icon-md waves-effect waves-light" data-placement="top" title="" data-toggle="tooltip" 
                                href="@if(isset($locale)){{route('questions.show', [$locale, $question->id])}} @else {{route('.questions.show', ['en', $question->id])}} @endif" data-original-title="Show Questions">
                                    <i data-feather="eye"></i>
                                </a>

                                <a class="btn btn-sm btn-clean btn-icon btn-icon-md" href="@if(isset($locale)){{route('questions.edit', [$locale, $question->id])}} @else {{route('questions.edit', ['en', $question->id])}} @endif">
                                    <i data-feather="edit-2"></i></a>
                                <a class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="modal" data-target="#confirm-delete{{ $question->id }}" data-original-title="Close"> <i data-feather="trash-2"></i> </a>
                            </td>
                        </tr>
                        <div class="modal fade" id="confirm-delete{{ $question->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="@if(isset($locale)){{route('questions.destroy', [$locale, $question->id])}} @else {{route('questions.destroy', ['en', $question->id])}} @endif" method="post">
                                        <input name="_method" type="hidden" value="DELETE">
                                        {{ csrf_field() }}
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="myModalLabel1">Delete Question</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body mt-1">
                                            <h5>Are you sure you want to delete this Question?</h5>
                                            <br>
                                            {{$question->question}}
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-warning waves-effect" data-dismiss="modal">{{__('language.Cancel')}}</button>
                                            <button type="submit" class="btn btn-danger waves-effect waves-float waves-light btn-ok">{{__('language.Delete')}}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
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