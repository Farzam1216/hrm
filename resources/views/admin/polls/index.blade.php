@extends('layouts/contentLayoutMaster')
@section('title','Polls')
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
@php
$permission = false;
if(Auth::user()->isAdmin() || $permissions['poll']){
$permission = true;
}
@endphp

<div class="row">
    <div class="col-lg-12">
        <div class="card">

            @if($permission== true)
            <div class="card-header border-bottom pt-1 pb-1">
                <div class="head-label">
                    <h6 class="mb-0"></h6>
                </div>
                <div class="dt-action-buttons text-right">
                    <div class="dt-buttons flex-wrap d-inline-flex">
                        <button type="button" class="btn create-new btn-primary mr-1" onclick="window.location.href='@if(isset($locale)){{url($locale.'/polls/create')}} @else {{url('en/polls/create')}} @endif'">
                            <i data-feather="plus"></i> {{__('language.Add')}} {{__('language.Polls')}}
                        </button>
                    </div>
                </div>
            </div>
            @endif
            <div class="card-datatable table-responsive pt-0" style="padding: 15px;">
                <table class="dt-simple-header table dt-responsive">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th> {{__('language.Title')}}</th>
                            @if($permission== true)
                            <th> {{__('language.Poll')}} {{__('language.Start Date')}}</th>
                            <th> {{__('language.Poll')}} {{__('language.End Date')}}</th>
                            @endif
                            <th> {{__('language.Actions')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($polls->count() > 0)
                        @foreach($polls as $key => $poll)
                        <tr>
                            <td>{{$key+1}}</td>
                            <td>{{$poll->title}}</td>
                            @if($permission== true)
                            <td>{{$poll->poll_start_date}}</td>
                            <td>{{$poll->poll_end_date}}</td>
                            @endif

                            <td>

                                @if($poll->attempted==true)
                                <a type="button" class="btn btn-sm btn-clean btn-icon btn-icon-md waves-effect waves-light" data-placement="top" title="" data-toggle="tooltip" data-original-title="Attempted">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#8e9394" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-up-right">
                                        <line x1="7" y1="17" x2="17" y2="7"></line>
                                        <polyline points="7 7 17 7 17 17"></polyline>
                                    </svg>
                                </a>

                                @else
                                <a type="button" class="btn btn-sm btn-clean btn-icon btn-icon-md waves-effect waves-light" data-placement="top" title="" data-toggle="tooltip" href="@if(isset($locale)){{route('polls.take.create',[$locale,$poll->id])}} @else {{route('polls.take.create',['en',$poll->id])}} @endif" data-original-title="Attempt Poll">
                                    <i data-feather="arrow-up-right"></i>
                                </a>
                                @endif
                                @if($permission== true)

                                <a type="button" class="btn btn-sm btn-clean btn-icon btn-icon-md waves-effect waves-light" data-placement="top" title="" data-toggle="tooltip" href="@if(isset($locale)){{route('polls.questions.index',[$locale,$poll->id])}} @else {{route('polls.questions.index',['en',$poll->id])}} @endif" data-original-title="Show Questions">
                                    <i data-feather="eye"></i>
                                </a>


                                <a class="btn btn-sm btn-clean btn-icon btn-icon-md" href="@if(isset($locale)){{url($locale.'/polls/'.$poll->id.'/edit')}} @else {{url('en/polls/'.$poll->id.'/edit')}} @endif">
                                    <i data-feather="edit-2"></i></a>
                                <a class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="modal" data-target="#confirm-delete{{ $poll->id }}" data-original-title="Close"> <i data-feather="trash-2"></i> </a>
                                <div class="modal fade" id="confirm-delete{{ $poll->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="@if(isset($locale)){{url($locale.'/polls',$poll->id)}} @else {{url('en/polls',$poll->id)}} @endif" method="post">
                                                <input name="_method" type="hidden" value="DELETE">
                                                {{ csrf_field() }}
                                                <div class="modal-header">
                                                    <b>{{__('language.Are you sure you want to delete this Poll?')}}</b>
                                                </div>
                                                <div class="modal-body">
                                                    <h4>{{ $poll->title }}</h4>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-warning waves-effect" data-dismiss="modal">{{__('language.Cancel')}}</button>
                                                    <button type="submit" class="btn btn-danger waves-effect waves-float waves-light btn-ok">{{__('language.Delete')}}</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endif
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