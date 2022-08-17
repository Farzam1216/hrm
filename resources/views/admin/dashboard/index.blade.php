@extends('layouts/contentLayoutMaster')
@section('title','Dashboard')
@section('heading')
@section('vendor-style')
    {{-- vendor css files --}}
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/charts/apexcharts.css')) }}">
@endsection
@section('page-style')
    {{-- Page css files --}}
    <link rel="stylesheet" href="{{ asset(mix('css/base/pages/dashboard-ecommerce.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/charts/chart-apex.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/extensions/ext-component-toastr.css')) }}">
@endsection
<!-- Content Header (Page header) -->
<div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0 text-dark">{{__('language.Dashboard')}}</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item active"><a href="#">{{__('language.Dashboard')}}</a></li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
@stop
@section('content')

            <div class="row">
                <div class="col-md-12">
                    <!-- Info boxes -->
                    <div class="card-body bg-white statistics-body rounded-lg shadow-sm">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="media">
                                    <div class="avatar bg-light-primary mr-2">
                                        <div class="avatar-content">
                                            <i data-feather="trending-up" class="avatar-icon"></i>
                                        </div>
                                    </div>
                                    <div class="media-body my-auto">
                                        <h4 class="font-weight-bolder mb-0">0%</h4>
                                        <p class="card-text font-small-3 mb-0">{{__('language.Average Attendance')}}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                                <div class="media">
                                    <div class="avatar bg-light-info mr-2">
                                        <div class="avatar-content">
                                            <i data-feather="user" class="avatar-icon"></i>
                                        </div>
                                    </div>
                                    <div class="media-body my-auto">
                                        <h4 class="font-weight-bolder mb-0">{{count($totalemployees)}}</h4>
                                        <p class="card-text font-small-3 mb-0">{{__('language.Employees')}}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-sm-0">
                                <div class="media">
                                    <div class="avatar bg-light-danger mr-2">
                                        <div class="avatar-content">
                                            <i data-feather="box" class="avatar-icon"></i>
                                        </div>
                                    </div>
                                    <div class="media-body my-auto">
                                        <h4 class="font-weight-bolder mb-0">{{$applicants}}</h4>
                                        <p class="card-text font-small-3 mb-0">{{__('language.Applicants')}}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-sm-6 col-12">
                                <div class="media">
                                    <div class="avatar bg-light-success mr-2">
                                        <div class="avatar-content">
                                            <i data-feather="dollar-sign" class="avatar-icon"></i>
                                        </div>
                                    </div>
                                    <div class="media-body my-auto">
                                        <h4 class="font-weight-bolder mb-0">0</h4>
                                        <p class="card-text font-small-3 mb-0">{{__('language.Payroll Proc')}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
{{--                    <!-- /.row -->--}}
                </div>
            </div>
{{--            -----------------------------------------Section 2--------------------------------------------------------------}}

            <div class="row mt-1">
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body pb-50">
                                <h6>Average Attendance</h6>
                                <h2 class="font-weight-bolder mb-1"></h2>
                                <div id="statistics-order-chart"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card card-tiny-line-stats">
                            <div class="card-body pb-50">
                                <h6>Employee Designations</h6>
                                <h2 class="font-weight-bolder mb-1"></h2>
                                <div id="statistics-profit-chart"></div>
                            </div>
                        </div>
                    </div>
                <div class="card earnings-card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <h6>Gender Ratio</h6>
                                <ul class="chart-legend clearfix">
                                    <li><i class="far fa-circle text-danger"></i> Female</li>
                                    <li><i class="far fa-circle text-primary"></i> Male</li></ul>
                            </div>
                            <div class="col-6">
                                <div id="earnings-chart"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card card-browser-states mt-0">
                        <div class="card-body">
{{--                            <h4 class="card-title mb-1">{{__('language.New Employees')}}</h4>--}}
                            <h6>{{__('language.New Employees')}} &nbsp;  <span class="badge badge-danger mb-1">{{count($employee)}} {{__('language.New Members')}}</span>
                            </h6>
{{--                            <div class="browser-states">--}}
                               <ul>
                                @foreach($employee as $employees)
                                    <li>
                                    <div class="media">
                                        <img
                                                src="{{asset($employees->picture)}}"
                                                class="rounded mr-1"
                                                height="30"
                                                alt="User Image"
                                                onerror="this.src='{{asset('asset/media/error/default.png')}}';"
                                        />
                                        {{--                                    <img src="{{asset($employees->picture)}}" onerror="this.src='{{asset('asset/media/error/default.png')}}';" alt="User Image">--}}

                                        <h6 class="mb-0 pt-1">{{$employees->firstname}} {{ Carbon\Carbon::parse($employees->joining_date)->subMonth()->diffForHumans()}}</h6>
{{--                                        <div class="mb-0 pt-1 text-body-heading">{{ Carbon\Carbon::parse($employees->joining_date)->subMonth()->diffForHumans()}}</div>--}}

                                    </div>


                                    <br>
                                    </li>
                                @endforeach
                               </ul>

{{--                            </div>--}}

                        </div>
                    </div>
                </div>



            </div>
            {{--            -----------------------------------------end Section 2--------------------------------------------------------------}}

            {{--            <div class="row">--}}
{{--                <div class="col-md-12">--}}
{{--                    <div class="card">--}}
{{--                        <div class="card-header">--}}
{{--                            <h5 class="card-title">Average Attendance Report</h5>--}}

{{--                            <div class="card-tools">--}}
{{--                                <button type="button" class="btn btn-tool" data-card-widget="collapse">--}}
{{--                                    <i class="fas fa-minus"></i>--}}
{{--                                </button>--}}
{{--                                <button type="button" class="btn btn-tool" data-card-widget="remove">--}}
{{--                                    <i class="fas fa-times"></i>--}}
{{--                                </button>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <!-- /.card-header -->--}}
{{--                        <div class="card-body">--}}
{{--                            <div class="row">--}}
{{--                                <div class="col-md-12">--}}
{{--                                    <p class="text-center">--}}
{{--                                        <strong>Average Attendance</strong>--}}
{{--                                    </p>--}}

{{--                                    <div class="chart">--}}
{{--                                        <!-- Sales Chart Canvas -->--}}
{{--                                        <canvas id="salesChart" height="180" style="height: 180px;" width="100%"></canvas>--}}
{{--                                    </div>--}}
{{--                                    <!-- /.chart-responsive -->--}}
{{--                                </div>--}}
{{--                                <!-- /.col -->--}}
{{--                                <!-- /.col -->--}}
{{--                            </div>--}}
{{--                            <!-- /.row -->--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <!-- /.card -->--}}
{{--                </div>--}}
{{--                <div class="col-md-12">--}}
{{--                    <div class="card">--}}
{{--                        <div class="card-header">--}}
{{--                            <h5 class="card-title">Employee Designations Report</h5>--}}

{{--                            <div class="card-tools">--}}
{{--                                <button type="button" class="btn btn-tool" data-card-widget="collapse">--}}
{{--                                    <i class="fas fa-minus"></i>--}}
{{--                                </button>--}}
{{--                                <button type="button" class="btn btn-tool" data-card-widget="remove">--}}
{{--                                    <i class="fas fa-times"></i>--}}
{{--                                </button>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <!-- /.card-header -->--}}
{{--                        <div class="card-body">--}}
{{--                            <div class="row">--}}
{{--                                <div class="col-md-12">--}}
{{--                                    <p class="text-center">--}}
{{--                                        <strong>Employee Designations</strong>--}}
{{--                                    </p>--}}

{{--                                    <div class="chart">--}}
{{--                                        <!-- Sales Chart Canvas -->--}}
{{--                                        <canvas id="salesChart2" height="180" style="height: 180px;" width="100%"></canvas>--}}
{{--                                    </div>--}}
{{--                                    <!-- /.chart-responsive -->--}}
{{--                                </div>--}}
{{--                                <!-- /.col -->--}}
{{--                                <!-- /.col -->--}}
{{--                            </div>--}}
{{--                            <!-- /.row -->--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <!-- /.card -->--}}
{{--                </div>--}}
{{--                <!-- /.col -->--}}
{{--            </div>--}}
            <!-- /.row -->

            <!-- Main row -->

            <!-- /.row -->


    @push('scripts')
        <script>
            var salesChartCanvas = $('#salesChart').get(0).getContext('2d');
            var salesChartCanvas2 = $('#salesChart2').get(0).getContext('2d');

            var salesChartData = {
                labels  : {!! $chartMonths !!},
                datasets: [
                    {
                        label               : 'Digital Goods',
                        backgroundColor     : 'rgba(60,141,188,0.9)',
                        borderColor         : 'rgba(60,141,188,0.8)',
                        pointRadius          : false,
                        pointColor          : '#3b8bba',
                        pointStrokeColor    : 'rgba(60,141,188,1)',
                        pointHighlightFill  : '#fff',
                        pointHighlightStroke: 'rgba(60,141,188,1)',
                        data                :  {!! $averageAttendance !!}
                    },
                ]
            }
            var salesChartData2 = {
                labels  :[{!! $DesignationName !!}],
                datasets: [
                    {
                        label               : 'Digital Goods',
                        backgroundColor     : 'rgb(220,53,69,0.9)',
                        borderColor         : 'rgba(166, 41, 41,0.8)',
                        pointRadius          : false,
                        pointColor          : '#3b8bba',
                        pointStrokeColor    : 'rgba(60,141,188,1)',
                        pointHighlightFill  : '#fff',
                        pointHighlightStroke: 'rgba(60,141,188,1)',
                        data                :  [{!! $designationSeries !!}]
                    },
                ]
            }

            var salesChartOptions = {
                maintainAspectRatio : false,
                responsive : true,
                legend: {
                    display: false
                },
                scales: {
                    xAxes: [{
                        gridLines : {
                            display : false,
                        }
                    }],
                    yAxes: [{
                        gridLines : {
                            display : false,
                        }
                    }]
                }
            }
            var salesChartOptions2 = {
                maintainAspectRatio : false,
                responsive : true,
                legend: {
                    display: false
                },
                scales: {
                    xAxes: [{
                        gridLines : {
                            display : false,
                        }
                    }],
                    yAxes: [{
                        gridLines : {
                            display : false,
                        }
                    }]
                }
            }

            // This will get the first returned node in the jQuery collection.
            var salesChart = new Chart(salesChartCanvas, {
                    type: 'line',
                    data: salesChartData,
                    options: salesChartOptions
                }
            )
            var salesChart2 = new Chart(salesChartCanvas2, {
                    type: 'line',
                    data: salesChartData2,
                    options: salesChartOptions2
                }
            )


            //-------------
            //- PIE CHART -
            //-------------
            // Get context with jQuery - using jQuery's .get() method.
            var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
            var pieData        = {
                labels: ['Male','Female'
                ],
                datasets: [
                    {
                        data: [{!! $male !!},{!! $female !!}],
                        backgroundColor : ['#3c8dbc','#f56954' ],
                    }
                ]
            }
            var pieOptions     = {
                legend: {
                    display: false
                }
            }
            //Create pie or douhnut chart
            // You can switch between pie and douhnut using the method below.
            var pieChart = new Chart(pieChartCanvas, {
                type: 'doughnut',
                data: pieData,
                options: pieOptions
            })

            //-----------------
            //- END PIE CHART -
            //-----------------

        </script>
    @endpush
@endsection
@section('vendor-script')
    {{-- vendor files --}}
    <script src="{{ asset(mix('vendors/js/charts/apexcharts.min.js')) }}"></script>
@endsection
@section('page-script')
    {{-- Page js files --}}
    <script src="{{ asset(mix('js/scripts/pages/dashboard-ecommerce.js')) }}"></script>
@endsection
