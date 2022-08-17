@extends('layouts.admin')
@section('title','Employee Leaves')
@section('heading')
<!-- Content Header (Page header) -->
<div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0 text-dark">{{__('language.Employee')}} {{__('language.Leaves')}}</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">{{__('language.Attendance')}}</a></li>
                <li class="breadcrumb-item active">{{__('language.Employee')}} {{__('language.Leaves')}}</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
       
      <div class="row justify-content-end">
		
            <div class="col-4">
        <button type="button"  onclick="window.location.href='@if(isset($locale)){{url($locale.'/leave/admin-create')}} @else {{url('en/leave/admin-create')}} @endif'" class="btn btn-info btn-rounded m-t-10 float-right"><i class="fas fa-plus"></i> {{__('language.Add')}} {{__('language.Employee')}} {{__('language.Leaves')}}</button>

            </div>
      </div>
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
@stop
@section('content')
    <div class="row">
        <div class="col-lg-12">
                <div class="card">
                 
                        
                        <div class="card-body">
                                @if(count($employees) > 0)
                                <div class="dropdown dropdown-inline">
                                        <div class="float-right">
                                            <select class="form-control" id="filter">
                                                <option>{{__('language.All')}}</option>
                                                <option @if($id=='Approved') selected @endif>{{__('language.Approved')}}</option>
                                                <option @if($id=='Declined') selected @endif >{{__('language.Declined')}}</option>
                                                <option @if($id=='Pending') selected @endif >{{__('language.Pending')}}</option>
                                            </select>
                                        </div>
                                    </div> 
                          <table id="kt_table_1" class="table table-bordered table-striped table-checkable">
                            <thead>
                            <tr>
                                    <th>{{__('language.Employee')}}</th>
                                <th>{{__('language.Leave')}} {{__('language.Type')}}</th>
                                <th>{{__('language.Date')}} {{__('language.From')}}</th>
                                <th>{{__('language.Date')}} {{__('language.To')}}</th>
                                <th>{{__('language.Subject')}}</th>
                                <th>{{__('language.Actions')}}</th>
                                <th>{{__('language.Status')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                                    @foreach($employees as $employee)
                                    @if (empty($employee->id))
                                        @continue
                                    @endif
                            <tr class="small">
                                            <td>{{$employee->firstname}} {{$employee->lastname}}</td>
                                            <td>{{$employee->leaveType->name}}</td>
                                            <td>{{Carbon\Carbon::parse($employee->datefrom)->format('d-m-Y')}}</td>
                                            <td>{{Carbon\Carbon::parse($employee->dateto)->format('d-m-Y')}}</td>
                                            <td>{{$employee->leave_subject}}</td>
                                            <td>
                                                @if($employee->leave_status == '' || strtolower($employee->leave_status) == 'pending')
                                                    @if(Auth::user()->id == 1 || (Auth::user()->id != $employee->id))
                                                        <select class="update_status form-control" id="{{$employee->leave_id}}"
                                                                style="width:160px;">
                                                            <option value="">{{__('language.Update')}} {{__('language.Status')}}</option>
                                                            <option value="Approved">{{__('language.Approved')}}</option>
                                                            <option value="Declined">{{__('language.Declined')}}</option>
                                                        </select>
                                                    @endif
                                                @endif
            
                                                @if($employee->leave_status == 'Pending')
                                                    <span class="btn btn-bold btn-sm btn-font-sm  btn-label-warning">
                                                    {{$leave->status}}
                                                </span>
                                                @elseif($employee->leave_status == 'Approved')
                                                    <span class="btn btn-bold btn-sm btn-font-sm  btn-label-success"> {{$employee->leave_status}}</span>
                                                @elseif($employee->leave_status == 'Declined')
                                                    <span class="btn btn-bold btn-sm btn-font-sm  btn-label-danger"> {{$employee->leave_status}}</span>
                                                @endif
                                            </td>
                                            <td>
                                                 <span class="dropdown">
                                        <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown"
                                           aria-expanded="true">
                                          <i class="fas fa-ellipsis-h"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                           <a class="dropdown-item" href="@if(isset($locale)){{url($locale.'/leave/show',$employee->leave_id)}} @else {{url('en/leave/show',$employee->leave_id)}} @endif">
                                               <i class="fas fa-eye"></i> {{__('language.View')}} {{__('language.Details')}}
                                           </a>
                                            <a class="dropdown-item" href="#" data-toggle="modal"
                                               data-target="#confirm-delete{{$employee->leave_id }}"><i class="fas fa-trash"></i> {{__('language.Delete')}} {{__('language.Leave')}}</a>
                                        </div>
                                    </span>
                                                @if(
                                                    ($employee->leave_status == 'Pending' && $employee->leave_status == '')
                                                )
                                                @endif
                                                <div class="modal fade" id="confirm-delete{{$employee->leave_id }}" tabindex="-1"
                                                     role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form action="@if(isset($locale)){{url($locale.'/leave/delete',$employee->leave_id)}} @else {{url('en/leave/delete',$employee->leave_id)}} @endif"
                                                                  method="post">
                                                                {{ csrf_field() }}
                                                                <div class="modal-header">
                                                                   {{__('language.Are you sure you want to delete this Leave?')}}
                                                                </div>
                                                                <div class="modal-header">
                                                                    <h4>{{$employee->firstname}} {{$employee->lastname}}</h4>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-default"
                                                                            data-dismiss="modal">{{__('language.Cancel')}}
                                                                    </button>
                                                                    <button type="submit" class="btn btn-danger btn-ok">{{__('language.Delete')}}
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                            </tbody>
                          </table>
                          @else
                          <tr> {{__('language.No Employee Found')}}</tr>
                  @endif
                      <!--end: Datatable -->
                        </div>
                        <!-- /.card-body -->
                      </div>
                      <!-- /.card -->


           
        </div>
    </div>



    @push('scripts')
        <script type="text/javascript">
        $(document).ready(function () {
            $("#filter").change(function(e){
                    var url = "{{url($locale.'/employee-leaves')}}/" + $(this).val();

                if (url) {
                    window.location = url;
                }
                return false;
            });
        });
    </script>
        <script type="text/javascript">
            $(".update_status").on('change', function (event) {
                if ($(this).val() !== '') {
                    location.href = "{{url('/')}}/{!! $locale !!}/leave/updateStatus/" + $(this).attr('id') + '/' + $(this).val();
                }
            });
        </script>
        <script>
            $(document).ready(function() {
                $('#myTable').DataTable({
                    stateSave: true,
                    info: false,
                    ordering:false
                });
            });
        </script>
    @endpush
@stop

