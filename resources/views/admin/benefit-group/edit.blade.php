@extends('layouts/contentLayoutMaster')
@section('title','Edit Benefit Group')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="container">
                        <form role="form"
                              action=@if(isset($locale)){{url($locale.'/benefitgroup/'.$benefitGroup->id)}} @else {{url('en/benefitgroup/'.$benefitGroup->id)}} @endif
                                      method="post" enctype="multipart/form-data" id="submit_form">
                            @method('PUT')
                            {{ csrf_field() }}
                            <div class="row setup-content" id="step-2">
                                <div class="form-group col-md-6">
                                    <label for="">Benefit Group Name</label><span class="text-danger"> *</span>
                                    <input class="form-group" id="" name="Id" type="hidden"
                                           value="{{$benefitGroup->id}}">
                                    <input type="text" class="form-control" id="name" name="name"
                                           value="{{$benefitGroup->name}}" required>
                                </div>
                                <div class="col-md-12">
                                    <p>Which employees would you like to include in this Benefit Group?</p>
                                    <div class="add_employees_hidden">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="card border border-secondary">
                                                <div class="card-header">
                                                    <button type="button"
                                                            class="btn btn-primary btn-rounded float-right ml-1 mt-1"
                                                            id="add-employee">Add
                                                    </button>
                                                </div>
                                                <div class="card-body">
                                                    <ul class="list-group" id="left-list">
                                                        @foreach($employeesNotInBenefitGroup as $employee)
                                                            <li value="{{$employee->id}}" class="list-group-item">
                                                                {{$employee->firstname}}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <div class="card border border-secondary">
                                                <div class="card-header">
                                                    <button type="button"
                                                            class="btn btn-primary btn-rounded float-right ml-1 mt-1"
                                                            id="remove-employee">Remove
                                                    </button>
                                                </div>
                                                <div class="card-body" id="right-list">
                                                    <ul class="list-group">
                                                        @foreach($employeesInBenefitGroup as $BenefitGroup)
                                                            <li value="{{$BenefitGroup->employees->id}}"
                                                                class="list-group-item">{{$BenefitGroup->employees->firstname}}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <button onClick="employeedata()" type="submit" class="btn btn-primary"
                                            id="btnSave">{{__('language.Update')}} {{__('language.Benefit')}} {{__('language.Group')}}</button>
                                    {{--                                            <button class="btn btn-primary nextBtn btn-lg pull-right" type="submit" id="btnSubmit" disabled>Update</button>--}}
                                    <a type="button" href="@if(isset($locale)){{url($locale.'/benefitgroup')}}
                                    @else {{url('en/benefitgroup')}} @endif"
                                       class="btn btn-outline-warning">{{__('language.Cancel')}}</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!--end::Portlet-->
            </div>
        </div>

        @section('page-script')
            {{-- Page js files --}}
            <script src="{{ asset(mix('js/scripts/forms/pickers/form-pickers.js')) }}"></script>
            <script src="{{ asset(mix('js/scripts/forms/form-wizard.js')) }}"></script>
            <script src="{{ asset(mix('js/scripts/benefit/benefit-group/edit.js')) }}"></script>


@endsection

@stop