@extends('layouts.admin')
@section('title','Personal Leaves')
@section('heading')
<!-- Content Header (Page header) -->
<div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0 text-dark"> {{__('language.My')}} {{__('language.Leaves')}}</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#"> {{__('language.Attendance')}}</a></li>
                <li class="breadcrumb-item active"> {{__('language.My')}} {{__('language.Leaves')}}</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
       
      <div class="row justify-content-end">
		
            <div class="col-2">
    <button type="button" onclick="window.location.href='@if(isset($locale)){{url($locale.'/leave/create')}} @else {{url('en/leave/create')}} @endif'"
            class="btn btn-info btn-rounded btn-block m-t-10 float-right"><i class="fas fa-plus"></i> &nbsp; {{__('language.Apply')}} {{__('language.For')}} {{__('language.Leave')}}
    </button>
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
                                @if(count($leaves) > 0)
                                        
                          <table id="kt_table_1" class="table table-bordered table-striped table-checkable">
                            <thead>
                            <tr>
                                    <th>{{__('language.Leave')}} {{__('language.Type')}}</th>
                                    <th>{{__('language.Date')}} {{__('language.From')}}</th>
                                    <th>{{__('language.Date')}} {{__('language.To')}}</th>
                                    <th>{{__('language.Subject')}}</th>
                                    <th>{{__('language.Status')}}</th>
                                        <th>{{__('language.Actions')}}</th>
                                    
                            </tr>
                            </thead>
                            <tbody>
                                    @foreach($leaves as $leave)
                            <tr class="small">
                                
                                            <td>{{$leave->leaveType->name}}</td>
                                            <td>{{Carbon\Carbon::parse($leave->datefrom)->format('Y-m-d')}}</td>
                                            <td>{{Carbon\Carbon::parse($leave->dateto)->format('Y-m-d')}}</td>
                                            <td>{{$leave->subject}}</td>
                                            <td>
                                                @if($leave->status == 'Pending')
                                                    <span class="btn btn-bold btn-sm btn-font-sm  btn-label-warning">
                                                    {{$leave->status}}
                                                </span>
                                                @elseif($leave->status == 'Approved')
                                                    <span class="btn btn-bold btn-sm btn-font-sm  btn-label-success"> {{$leave->status}}</span>
                                                @elseif($leave->status == 'Declined')
                                                    <span class="btn btn-bold btn-sm btn-font-sm  btn-label-danger"> {{$leave->status}}</span>
                                                @endif
                                            </td>
                                            <td>
                                                 <span class="dropdown">
                                        <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown"
                                           aria-expanded="true">
                                          <i class="fas fa-ellipsis-h"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                             @if(
                                                    (strtolower($leave->status) == 'pending' || $leave->status == '')
                                                )
                                                <form action="@if(isset($locale)){{url($locale.'/leave/delete',$leave->id)}} @else {{url('en/leave/delete',$leave->id)}} @endif" method="post">
                                                        {{ csrf_field() }}
                                                        <button class="dropdown-item" type="submit"><i class="fas fa-trash"></i> {{__('language.Delete')}} {{__('language.Leave')}}</button>
                                                </form>
                                                <a class="dropdown-item" href="@if(isset($locale)){{url($locale.'/leave/edit',$leave->id)}} @else {{url('en/leave/edit',$leave->id)}} @endif"><i
                                                            class="fas fa-edit"></i> {{__('language.Update')}} {{__('language.Leave')}} {{__('language.Details')}}</a>
                                            @endif
                                            
                                            @if(
                                                    (strtolower($leave->status) == 'approved')
                                                )
                                            <a class="dropdown-item" href="@if(isset($locale)){{url($locale.'/leave/show',$leave->id)}} @else {{url('en/leave/show',$leave->id)}} @endif"><i
                                                        class="fas fa-eye"></i> {{__('language.Leave')}} {{__('language.Details')}}</a>

                                                        @endif
                                                        
                                        </div>
                                    </span>
                                            </td>
                                        </tr>
                                    @endforeach
                            </tbody>
                          </table>
                          @else
                        <p style="text-align: center;">{{__('language.No Leave Found')}}.</p>
                @endif

                      <!--end: Datatable -->
                        </div>
                        <!-- /.card-body -->
                      </div>

            
        </div>
    </div>

    @push('scripts')
        <script type="text/javascript">
            $(".update_status").on('change', function (event) {
                if ($(this).val() !== '') {
                    location.href = "{{url('/')}}/leave/updateStatus/" + $(this).attr('id') + '/' + $(this).val();
                }
            });
        </script>
    @endpush
@stop