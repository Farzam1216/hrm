@extends('layouts/contentLayoutMaster')
@section('title','Access Level')
@section('content')
<div class="card">
    <h4 class="pl-2 mt-1">Add Employees to {{$role->name}} </h4>
    <hr>
    <form id="add_employee" action=@if(isset($locale)){{url($locale.'/access-levels/addemployee/'.$role->id)}} @else {{url('en/access-levels/addemployee/'.$role->id)}} @endif method="post" enctype="multipart/form-data">
        @method('PUT')
        {{ csrf_field() }}
        <div>
            <div class="row ml-2 mr-2">
                <div class="form-group col-md-5 shadow rounded">
                    <div class="card">
                        <div class="card-header">
                            <button type="button" class="btn btn-primary mt-1 ml-1 mr-0 mr-sm-1 waves-effect waves-float waves-light" id="add-employee">Add</button>
                        </div>
                        <div class="add_employees_hidden">
                        </div>
                        <div class="card-body mt-1 pt-0" style="overflow: auto; max-height: 200px;">
                            <ul class="list-group" id="left-list">
                                @foreach($availableEmployees as $employee)
                                <li value="{{$employee->id}}" class="list-group-item">
                                    {{$employee->firstname}} {{$employee->lastname}}
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="form-group col-md-5 shadow rounded" style="margin-left: auto;">
                    <div class="card">
                        <div class="card-header">
                            <button type="button" class="btn btn-primary mt-1 ml-1 mr-0 mr-sm-1 waves-effect waves-float waves-light" id="remove-employee">Remove</button>
                        </div>
                        <div class="card-body mt-1" style="overflow: auto; max-height: 200px;">
                            <ul class="list-group" id="right-list">
{{--                                @foreach($selectedEmployees as $employee)--}}
{{--                                    <li value="{{$employee->id}}" class="list-group-item">--}}
{{--                                        {{$employee->firstname}} {{$employee->lastname}}--}}
{{--                                    </li>--}}
{{--                                @endforeach--}}
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ml-2 mr-2 mt-1 mb-2" style="overflow:auto;">
                <button class="btn btn-primary waves-effect waves-float waves-light" onClick="employeedata()" type="button" id="btnSubmit">
                    <i class="d-block d-sm-none" data-feather='check-circle'></i><span class="d-none d-sm-inline"> Save</span>
                </button>
                <button class="btn btn-outline-warning ml-1 waves-effect waves-float waves-light" type="button" id="cancelBtn" onclick="window.location.href='@if(isset($locale)){{url($locale.'/access-level')}} @else {{url('en/access-level')}} @endif'">
                    <i class="d-block d-sm-none" data-feather='x-circle'></i><span class="d-none d-sm-inline"> Cancel</span>
                </button>
            </div>
        </div>
    </form>
</div>
<script>
    //toggle active class in list
    $(document).on('click', '.list-group-item', function() {
        $(this).toggleClass("active");
    });

    //Move active employee from left to right card and remove active class
    $(document).on('click', '#add-employee', function() {
        $('#right-list').append($('#left-list .active ').removeClass('active'));
    });
    //Move active employee from right to left card and remove active class
    $(document).on('click', '#remove-employee', function() {
        $('#left-list').append($('#right-list .active').removeClass('active'));
    });

    function employeedata() {
        var employees = $('#right-list li').map(function() {
            return {
                employee_id: $(this).attr('value'),
            }
        }).get();
        $('.add_employees_hidden').empty();
        $.each(employees, function(indexInArray, valueOfElement) {
            $('.add_employees_hidden').append(
                '<input class="form-group" value="' + valueOfElement.employee_id + '" name="employees_Id[]" type="hidden">'
            );
        });
        $("#add_employee").submit();
    }
    $('#remove-employee').click(function() {
        $('.add_employees_hidden').empty();
    });
    $('#add-employee').click(function() {
        $('.add_employees_hidden').empty();
    });
</script>
@stop