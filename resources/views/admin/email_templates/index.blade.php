@extends('layouts.contentLayoutMaster')
@section('title','Email Templates')

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
                    <a href="@if(isset($locale)){{url($locale.'/email-templates/create')}} @else {{url('en/email-templates/create')}} @endif"
                       class="btn create-new btn-primary mr-1 waves-effect waves-float waves-light">
                        <i data-feather='plus'></i>
                        {{__('Add Email Template')}}
                    </a>
                </div>
            </div> <!--end card-header-->
            <div class="card-datatable table-responsive pt-0" style="padding: 15px;">
                <table class="dt-simple-header table dataTable dtr-column">
                    <thead class="head-light">
                        <tr>
                            <th>#</th>
                            <th> {{__('language.Name')}}</th>
                            <th> {{__('language.Subject')}}</th>
                            <th> {{__('language.Actions')}}</th>
                            <th tooltip="Only one template can be set as Welcome Email Template"> {{__('language.Welcome Email Template')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($emailTemplates as $key => $emailTemplate)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{$emailTemplate->template_name}}</td>
                                <td>
                                    {{$emailTemplate->subject}}
                                </td>
                                <td class="text-nowrap">
                                    <a class="btn btn-sm btn-clean btn-icon btn-icon-md" href="@if(isset($locale)){{url($locale.'/emailtemplates/'.$emailTemplate->id.'/edit')}} @else {{url('en/emailtemplates/'.$emailTemplate->id.'/edit')}} @endif" title="Edit"><i data-feather="edit-2"></i></a>
                                    <a class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="modal" data-target="#confirm-delete{{ $emailTemplate->id }}"  data-original-title="Close"><i data-feather="trash-2"></i> </a>
                                </td>
                                <td>
                                    <div class="custom-control custom-switch mr-n2 mt-1"><input type="checkbox" templateId="{{$emailTemplate->id}}"  @if($emailTemplate->welcome_email == 1) checked @endif class="custom-control-input welcomeEmailSwitch" onchange="setEmailTemplate(this)"  id="welcomeEmailSwitch{{$emailTemplate->id}}"><label class="custom-control-label" for="welcomeEmailSwitch{{$emailTemplate->id}}"></label></div>
                                </td>
                            </tr>

                            <div class="modal fade" id="confirm-delete{{ $emailTemplate->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="@if(isset($locale)){{url($locale.'/email-templates',$emailTemplate->id)}} @else {{url('en/email-templates',$emailTemplate->id)}} @endif" method="post">
                                            @method('DELETE')
                                            {{ csrf_field() }}
                                            <div class="modal-header">
                                                <h5>Delete Email Template</h5>
                                            </div>
                                            <div class="modal-body">
                                                <h5>Are you sure you want to delete "{{ $emailTemplate->template_name }}" Email Template?</h5>
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
@section('page-script')
    {{-- Page js files --}}
    <script>
        function setEmailTemplate($this)
        {
            if ($this.checked) {
                var attachments = $("#template_change option:selected").attr('emailAttachments');
                $('.welcomeEmailSwitch').each(function(index, value){
                    if(value.checked)
                    {
                        if(value != $this){
                            var id='#'+value.id;
                            $(id).prop('checked', false);
                        }

                    }

                });
                var id=$($this).attr('templateId');
                console.log($this.id+"Wow"+ id);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "get",
                    url: "/setEmail",
                    data: {
                        'id': id,
                        'welcome_email': 1,
                    },
                    cache: false,
                    success: function (data) {
                        $($this).prop('checked',true
                        );
                    }
                });
            }
        }
    </script>
@endsection
@stop
