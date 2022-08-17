@extends('layouts/contentLayoutMaster')
@section('title', 'Categories')

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
<!-- users list start -->
<section class="app-user-list">
    <!-- list section start -->
    <div class="card">
        <div class="card-header border-bottom pb-1 pt-1">
            <div class="head-label">
                <h6 class="mb-0"></h6>
            </div>
            <div class="dt-action-buttons text-right dt-buttons flex-wrap d-inline-flex mr-1">
                <a @if($task_type == "onboarding")
                   href="@if(isset($locale)){{url($locale.'/onboarding-categories/create')}}
                    @else {{url('en/onboarding-categories/create')}} @endif"
                    @elseif($task_type == "offboarding")
                        href="@if(isset($locale)){{url($locale.'/offboarding-categories/create')}}
                @else {{url('en/offboarding-categories/create')}} @endif"
                @endif
                        class="btn btn-brand btn-primary btn-elevate btn-icon-sm">
                    <i data-feather='plus'></i>
                    {{__('language.Add')}} {{__('language.Category')}}
                </a>
            </div>
        </div> <!--end card-header-->
        <div class="card-datatable table-responsive pt-0 p-1">
            <table class="dt-simple-header table">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th> {{__('language.Category Name')}}</th>
                        <th> {{__('language.Actions')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($taskCategories as $key => $taskCategory)
                        <tr>
                            <td>{{$key+1}}</td>
                            <td>{{$taskCategory->task_category_name}}</td>
                            <td>
                                <a class="text-dark" data-toggle="tooltip" data-placement="top" title="edit" @if($task_type == "onboarding")
                                href="@if(isset($locale)){{url($locale.'/onboarding-categories/'.$taskCategory->id.'/edit')}}
                                @else {{url('en/onboarding-categories/'.$taskCategory->id.'/edit')}} @endif"
                                   @elseif($task_type == "offboarding")
                                   href="@if(isset($locale)){{url($locale.'/offboarding-categories/'.$taskCategory->id.'/edit')}}
                                   @else {{url('en/offboarding-categories/'.$taskCategory->id.'/edit')}} @endif"
                                        @endif>
                                    <i data-feather="edit-2" class="mr-40"> </i></a>
                                <a class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="modal" data-target="#confirm-delete{{ $taskCategory->id }}"> <i data-feather="trash-2"></i> </a>
                            </td>
                        </tr>

                        <div class="modal fade" id="confirm-delete{{ $taskCategory->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5>Delete @if($task_type == 'onboarding') Onboarding @elseif($task_type == 'offboarding') Offboarding @endif Task</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="@if($task_type == 'onboarding')  @if(isset($locale)){{url($locale.'/onboarding-categories/'.$taskCategory->id)}} @else {{url('en/onboarding-categories/'.$taskCategory->id)}} @endif @else @if(isset($locale)){{url($locale.'/offboarding-categories/'.$taskCategory->id)}} @else {{url('en/offboarding-categories/'.$taskCategory->id)}} @endif @endif" method="post">
                                        <input name="_method" type="hidden" value="DELETE">
                                        {{ csrf_field() }}
                                        <div class="modal-body">
                                            <h5>Are you sure you want to delete "{{$taskCategory->task_category_name}}" @if($task_type == 'onboarding') onboarding @elseif($task_type == 'offboarding') offboarding @endif task category?</h5>
                                            <br>
                                            @if($taskCategory->tasks->count())
                                            <h5><small>Note: This category is being used in {{$taskCategory->tasks->count()}} @if($task_type == 'onboarding') onboarding @else offboarding @endif task(s). By deleteing it, you're agreeing to remove their categories?</small></h5>
                                            @endif
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-warning waves-effect" data-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-danger waves-effect waves-float waves-light">{{__('language.Delete')}}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- Modal to add new user starts-->
        <div class="modal modal-slide-in new-user-modal fade" id="modals-slide-in">
            <div class="modal-dialog">
                <form class="add-new-user modal-content pt-0">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                    <div class="modal-header mb-1">
                        <h5 class="modal-title" id="exampleModalLabel">New User</h5>
                    </div>
                    <div class="modal-body flex-grow-1">
                        <div class="form-group">
                            <label class="form-label" for="basic-icon-default-fullname">Full Name</label>
                            <input
                                    type="text"
                                    class="form-control dt-full-name"
                                    id="basic-icon-default-fullname"
                                    placeholder="John Doe"
                                    name="user-fullname"
                                    aria-label="John Doe"
                                    aria-describedby="basic-icon-default-fullname2"
                            />
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="basic-icon-default-uname">Username</label>
                            <input
                                    type="text"
                                    id="basic-icon-default-uname"
                                    class="form-control dt-uname"
                                    placeholder="Web Developer"
                                    aria-label="jdoe1"
                                    aria-describedby="basic-icon-default-uname2"
                                    name="user-name"
                            />
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="basic-icon-default-email">Email</label>
                            <input
                                    type="text"
                                    id="basic-icon-default-email"
                                    class="form-control dt-email"
                                    placeholder="john.doe@example.com"
                                    aria-label="john.doe@example.com"
                                    aria-describedby="basic-icon-default-email2"
                                    name="user-email"
                            />
                            <small class="form-text text-muted"> You can use letters, numbers & periods </small>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="user-role">User Role</label>
                            <select id="user-role" class="form-control">
                                <option value="subscriber">Subscriber</option>
                                <option value="editor">Editor</option>
                                <option value="maintainer">Maintainer</option>
                                <option value="author">Author</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div class="form-group mb-2">
                            <label class="form-label" for="user-plan">Select Plan</label>
                            <select id="user-plan" class="form-control">
                                <option value="basic">Basic</option>
                                <option value="enterprise">Enterprise</option>
                                <option value="company">Company</option>
                                <option value="team">Team</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary mr-1 data-submit">Submit</button>
                        <button type="reset" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- Modal to add new user Ends-->
    </div>
    <!-- list section end -->
</section>
<!-- users list ends -->
@endsection

@section('vendor-script')
    {{-- Vendor js files --}}
    <script src="{{ asset(mix('vendors/js/tables/datatable/jquery.dataTables.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.bootstrap4.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.responsive.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/responsive.bootstrap4.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.buttons.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/buttons.bootstrap4.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
@endsection

@section('page-script')
    {{-- Page js files --}}
    <script src="{{ asset(mix('js/scripts/tables/table-datatables-basic.js')) }}"></script>
    {{-- <script src="{{ asset(mix('js/scripts/pages/app-user-list.js')) }}"></script> --}}
@endsection
