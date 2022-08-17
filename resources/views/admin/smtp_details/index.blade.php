@extends('layouts/contentLayoutMaster')
@section('title','SMTP Details')

@section('vendor-style')
    {{-- Vendor Css files --}}
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
                        <button type="button" class="btn create-new btn-primary" onclick="window.location.href='@if(isset($locale)){{route('smtp-details.create', [$locale])}} @else {{route('smtp-details.create', ['en'])}} @endif'">
                            <i data-feather="plus"></i> {{__('language.Add')}} {{__('language.Smtp Details')}}
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="card-datatable table-responsive">
                    <table class="dt-simple-header table table-sm dataTable dtr-column">
                        <thead class="head-light">
                            <tr class="text-nowrap">
                                <th>#</th>
                                <th> {{__('language.Name')}}</th>
                                <th> {{__('language.Mail Address')}}</th>
                                <th> {{__('language.Driver')}}</th>
                                <th> {{__('language.Host')}}</th>
                                <th> {{__('language.Port')}}</th>
                                <th> {{__('language.Username')}}</th>
                                <th> {{__('language.Status')}}</th>
                                <th> {{__('language.Actions')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($smtp_details as $key => $smtp)
                                <tr class="text-nowrap">
                                    <td>{{$key+1}}</td>
                                    <td>
                                        @if($smtp->name)
                                            {{$smtp->name}}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if($smtp->mail_address)
                                            {{$smtp->mail_address}}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{$smtp->driver}}</td>
                                    <td>{{$smtp->host}}</td>
                                    <td>{{$smtp->port}}</td>
                                    <td>{{substr($smtp->username, 0, 10) . '...'}}</td>
                                    <td>
                                        @if($smtp->status == 'active')
                                            <div class="custom-control custom-switch custom-switch-success">
                                                <input type="checkbox" class="custom-control-input checkbox" id="status{{$smtp->id}}" smtp_id="{{$smtp->id}}" checked />
                                                <label class="custom-control-label" for="status{{$smtp->id}}">
                                                    <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                    <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                </label>
                                            </div>
                                        @else
                                            <div class="custom-control custom-switch custom-switch-success">
                                                <input type="checkbox" class="custom-control-input checkbox" id="status{{$smtp->id}}" smtp_id="{{$smtp->id}}" />
                                                <label class="custom-control-label" for="status{{$smtp->id}}">
                                                    <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                    <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                </label>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="text-nowrap">
                                        <a class="btn btn-sm btn-clean btn-icon btn-icon-md pl-0" href="@if(isset($locale)){{route('smtp-details.show', [$locale, $smtp->id])}} @else {{route('smtp-details.show', ['en', $smtp->id])}} @endif" data-toggle="tooltip" data-original-title="Show">
                                            <i data-feather="eye"></i>
                                        </a>

                                        <a class="btn btn-sm btn-clean btn-icon btn-icon-md pl-0" href="@if(isset($locale)){{route('smtp-details.edit', [$locale, $smtp->id])}} @else {{route('smtp-details.edit', ['en', $smtp->id])}} @endif" data-toggle="tooltip" data-original-title="Edit">
                                            <i data-feather="edit-2"></i>
                                        </a>

                                        <a class="btn btn-sm btn-clean btn-icon btn-icon-md pl-0" data-toggle="modal" data-target="#confirm-delete{{ $smtp->id }}" data-original-title="Close"> <i data-toggle="tooltip" data-original-title="Delete" data-feather="trash-2"></i> </a>
                                    </td>
                                </tr>
                                <div class="modal fade" id="confirm-delete{{ $smtp->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="@if(isset($locale)){{route('smtp-details.destroy', [$locale, $smtp->id])}} @else {{route('smtp-details.destroy', ['en', $smtp->id])}} @endif" method="post">
                                                <input name="_method" type="hidden" value="DELETE">
                                                {{ csrf_field() }}
                                                <div class="modal-header">
                                                    <h4 class="modal-title" id="myModalLabel1">Delete Smtp Details</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body mt-1">
                                                    <h5>Are you sure you want to delete this SMTP Details?</h5>
                                                    <br>
                                                    {{$smtp->name}}
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
    <script>
        $(".checkbox").on('change', function() {
            console.log()
            if ($(this).prop('checked') == true) {
                var data = {
                    smtp_id: $(this).attr('smtp_id'),
                    status: "active"
                };
            }

            if ($(this).prop('checked') == false) {
                var data = {
                    smtp_id: $(this).attr('smtp_id'),
                    status: "inactive"
                };
            }

            $.ajax({
                url: '/en/smtp-details/'+data.smtp_id+'/status/'+data.status,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'PUT',
                data: data,
                dataType: 'JSON',
                success: function(data) {
                    if (data) {
                        window.location.href = "/en/smtp-details";
                    }
                }
            });
        });
    </script>
@endsection