@extends('layouts/contentLayoutMaster')
@section('title','Holidays')
@section('vendor-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/buttons.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/pickadate/pickadate.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
@endsection

@section('page-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-pickadate.css')) }}">
@endsection
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body pl-0 pr-0">
                <div class="row col-12 justify-content-between m-0">
                    <div>
                        <input type="text" id="date_filter" class="form-control flatpickr-basic" data-msg-required="Holiday date is required." placeholder="YYYY-MM-DD" />
                    </div>
                    @if(Auth::user()->isAdmin() || isset($permissions['holidays']['all']) && in_array('manage company holidays', $permissions['holidays']['all']))
                        <div class="">
                            <button type="button" class="btn create-new btn-primary" onclick="window.location.href='@if(isset($locale)) {{route('holidays.create', [$locale])}} @else {{route('holidays.create', ['en'])}} @endif'">
                                <i data-feather="plus"></i> {{__('language.Add')}} {{__('language.Holiday')}}
                            </button>
                        </div>
                    @endif
                </div>
                <hr class="mb-0 mr-1 ml-1">
            </div>
            <div class="card-datatable table-responsive pt-0" style="padding: 15px;">
                <table id="holidays_table" class="dt-simple-header table">
                    <thead>
                        <tr>
                            <th> {{__('language.Name')}}</th>
                            <th> {{__('language.Date')}}</th>
                            <th data-toggle="tooltip" data-original-title="Pay rate for working on this holiday"> {{__('language.Pay Rate')}}</th>

                            @if(Auth::user()->isAdmin() || isset($permissions['holidays']['all']) && in_array('manage company holidays', $permissions['holidays']['all']))
                                <th> {{__('language.Actions')}}</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @if(Auth::user()->isAdmin() || isset($permissions['holidays']['all']) && in_array('manage company holidays', $permissions['holidays']['all']))
                            @php $employee_check = 'authorized' @endphp
                            @foreach($holidays as $key => $holiday)
                                <tr>
                                    <td>{{$holiday->name}}</td>
                                    <td>{{$holiday->date}}</td>
                                    <td>
                                        @if($holiday->pay_rate)
                                            {{$holiday->pay_rate}} x
                                        @else
                                            -
                                        @endif
                                    </td>

                                    <td class="text-nowrap">
                                        <a class="btn btn-sm btn-clean btn-icon btn-icon-md" href="@if(isset($locale)) {{route('holidays.edit', [$locale,$holiday->id])}} @else {{route('holidays.edit', ['en',$holiday->id])}} @endif"data-toggle="tooltip" data-original-title="Edit Holiday">
                                            <i data-feather="edit-2"></i>
                                        </a>
                                        <a class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="modal" data-target="#confirm-delete{{ $holiday->id }}" data-original-title="Delete Holiday">
                                            <i data-feather="trash-2"></i>
                                        </a>
                                    </td>  
                                </tr>

                                <div class="modal fade" id="confirm-delete{{ $holiday->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="@if(isset($locale)){{route('holidays.destroy', [$locale, $holiday->id])}} @else {{route('holidays.destroy', ['en', $holiday->id])}} @endif"
                                                  method="post">
                                                <input name="_method" type="hidden" value="DELETE">
                                                {{ csrf_field() }}
                                                <div class="modal-header">
                                                    <h4 class="modal-title" id="myModalLabel1">Delete Holiday</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div> 
                                                <div class="modal-body mt-1">
                                                    <b>{{__('language.Are you sure you want to delete this Holiday?')}}</b>
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
                        @else
                            @php $employee_check = 'unauthorized' @endphp
                            @foreach($holidays as $key => $holiday)
                                @foreach($employee['employeeHolidays'] as $employee_holiday)
                                    @if($holiday->id == $employee_holiday->holiday_id)
                                        <tr>
                                            <td>{{$holiday->name}}</td>
                                            <td>{{$holiday->date}}</td>
                                            <td>
                                                @if($holiday->pay_rate)
                                                    {{$holiday->pay_rate}} x
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    $("#date_filter").on('change', function(){
        $.ajax({
            url: "holidays/filter", 
            data: {'date':this.value}, 
            success: function(data){
                var dataSet = [];
                var employee_check = '{!! $employee_check !!}'
                $.each(data, function(index, val)
                {
                    url = window.location.href+'/'+val.id+'/edit';
                    
                    if (employee_check == 'authorized') {
                        dataSet[index] = [val.name, val.date, val.pay_rate+' x', '<a class="btn btn-sm btn-clean btn-icon btn-icon-md" href="'+url+'" data-toggle="tooltip" data-original-title="Edit Holiday"><i data-feather="edit-2"></i></a><a class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="modal" data-target="#confirm-delete'+val.id+'" data-original-title="Delete Holiday"><i data-feather="trash-2"></i></a>'];
                    }

                    if (employee_check == 'unauthorized') {
                        dataSet[index] = [val.name, val.date, val.pay_rate+' x'];
                    }
                });

                $('#holidays_table').DataTable().destroy();
                $('#holidays_table').DataTable( {
                    data: dataSet
                });
                feather.replace();
            }
        });
    });
</script>
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
    <script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.date.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.time.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/pickadate/legacy.js')) }}"></script>
@endsection
@section('page-script')
    {{-- Page js files --}}
    <script src="{{ asset(mix('js/scripts/tables/table-datatables-basic.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/forms/pickers/form-pickers.js')) }}"></script>
@endsection