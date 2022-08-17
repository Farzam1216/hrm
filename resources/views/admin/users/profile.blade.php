@extends('layouts/contentLayoutMaster')

@section('title', 'User Account')


@section('content')
<!-- page users view start -->
<section class="page-users-view">
    <div class="row">
        <!-- account start -->
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-title"></div>
                    <div class="row ">
                        <div class="col-12 text-center">
                            <img class="round" src="{{asset(Auth::user()->picture)}}" onerror="this.src ='{{asset('asset/media/users/default3.png')}}';" alt="avatar" height="100" width="100">
                        </div>
                    </div>
                    <br>
                    <div class="row text-center">
                        <div class="col-sm-6 col-12">
                            <table>
                                <tr>
                                    <td class="font-weight-bold">Name</td>
                                    <td>{{$employee->firstname}} {{$employee->lastname}}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Phone No.</td>
                                    <td>@if($employee->contact_no == null) N/A @else {{$employee->contact_no}} @endif</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-sm-6 col-12">
                            <table class="">
                                <tr>
                                    <td class="font-weight-bold">Official Email</td>
                                    <td>{{$employee->official_email}}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Personal Email</td>
                                    <td>{{$employee->personal_email}}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <br>
                    <div class="row">
                        <div class="col-12">
                            <a href="@if(isset($locale)){{url($locale.'/personal-profile/edit')}} @else {{url('en/personal-profile/edit')}} @endif" class="btn btn-primary mr-1"><i data-feather="edit"></i> Edit</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- account end -->
    </div>
</section>
<!-- page users view end -->
@endsection