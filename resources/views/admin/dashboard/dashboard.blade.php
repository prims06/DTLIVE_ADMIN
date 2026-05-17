@extends('admin.layout.page-app')
@section('page_title', __('label.dashboard'))
@section('tab_title', __('label.dashboard'))

@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">{{__('label.dashboard')}}</h1>

            <!-- First Counter -->
            <div class="row counter-row">
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card custom-card card-color-primary">
                        <div class="card-body">
                            <div class="card-icon-primary card-color-primary">
                                <i class="fa-solid fa-users fa-2x"></i>
                            </div>
                            <div class="text-right">
                                <h3>{{ No_Format($UserCount ?? 0) }}</h3>
                                <span>{{__('label.users')}}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card custom-card card-color-primary">
                        <div class="card-body">
                            <div class="card-icon-primary card-color-primary">
                                <i class="fa-solid fa-video fa-2x"></i>
                            </div>
                            <div class="text-right">
                                <h3>{{ No_Format($VideoCount ?? 0) }}</h3>
                                <span>{{__('label.movies')}}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card custom-card card-color-primary">
                        <div class="card-body">
                            <div class="card-icon-primary card-color-primary">
                                <i class="fa-solid fa-tv fa-2x"></i>
                            </div>
                            <div class="text-right">
                                <h3>{{ No_Format($TVShowCount ?? 0) }}</h3>
                                <span>{{__('label.tv_show')}}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card custom-card card-color-primary">
                        <div class="card-body">
                            <div class="card-icon-primary card-color-primary">
                                <i class="fa-solid fa-film fa-2x"></i>
                            </div>
                            <div class="text-right">
                                <h3>{{ No_Format($ChannelCount ?? 0) }}</h3>
                                <span>{{__('label.channel')}}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Second Counter -->
            <div class="row counter-row">
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card custom-card card-color-primary">
                        <div class="card-body">
                            <div class="card-icon-primary card-color-primary">
                                <i class="fa-solid fa-user-tie fa-2x"></i>
                            </div>
                            <div class="text-right">
                                <h3>{{ No_Format($CastCount ?? 0) }}</h3>
                                <span>{{__('label.cast')}}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card custom-card card-color-primary">
                        <div class="card-body">
                            <div class="card-icon-primary card-color-primary">
                                <i class="fa-solid fa-user-shield fa-2x"></i>
                            </div>
                            <div class="text-right">
                                <h3>{{ No_Format($ProducerCount ?? 0) }}</h3>
                                <span>{{__('label.producer')}}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card custom-card card-color-primary">
                        <div class="card-body">
                            <div class="card-icon-primary card-color-primary">
                                <i class="fa-solid fa-box-archive fa-2x"></i>
                            </div>
                            <div class="text-right">
                                <h3>{{ No_Format($PackageCount ?? 0) }}</h3>
                                <span>{{__('label.package')}}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card custom-card card-color-primary">
                        <div class="card-body">
                            <div class="card-icon-primary card-color-primary">
                                <i class="fa-solid fa-right-left fa-2x"></i>
                            </div>
                            <div class="text-right">
                                <h3>{{ No_Format($TotalWithdrawalCount ?? 0) }}</h3>
                                <span>{{__('label.total_withdrawal')}}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Third Counter -->
            <div class="row counter-row">
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card custom-card card-color-primary">
                        <div class="card-body">
                            <div class="card-icon-primary card-color-primary">
                                <i class="fa-solid fa-money-bill-1 fa-2x"></i>
                            </div>
                            <div class="text-right">
                                <h3>{{ No_Format($CurrentMounthCount ?? 0) }}</h3>
                                <span>{{__('label.monthly_package_earnings')}}({{Currency_Code()}})</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card custom-card card-color-primary">
                        <div class="card-body">
                            <div class="card-icon-primary card-color-primary">
                                <i class="fa-solid fa-money-bill fa-2x"></i>
                            </div>
                            <div class="text-right">
                                <h3>{{ No_Format($TransactionCount ?? 0) }}</h3>
                                <span>{{__('label.package_earnings')}} ({{Currency_Code()}})</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card custom-card card-color-primary">
                        <div class="card-body">
                            <div class="card-icon-primary card-color-primary">
                                <i class="fa-solid fa-money-bill-1-wave fa-2x"></i>
                            </div>
                            <div class="text-right">
                                <h3>{{ No_Format($CurrentMounthRentCount ?? 0) }}</h3>
                                <span>{{__('label.monthly_rent_earnings')}} ({{Currency_Code()}})</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card custom-card card-color-primary">
                        <div class="card-body">
                            <div class="card-icon-primary card-color-primary">
                                <i class="fa-solid fa-money-bill-wave fa-2x"></i>
                            </div>
                            <div class="text-right">
                                <h3>{{ No_Format($RentTransactionCount ?? 0) }}</h3>
                                <span>{{__('label.rent_earnings')}} ({{Currency_Code()}})</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Join User Statistice && Rent Earning Statistice -->
            <div class="row mb-4">
                <div class="col-12 col-xl-8 cart-bg">
                    <div class="box-title">
                        <h2 class="title"><i class="fa-solid fa-chart-column fa-lg mr-2"></i>{{__('label.join_users_statistice_current_year')}}</h2>
                        <a href="{{ route('admin.user.index') }}" class="btn btn-link">{{__('label.view_all')}}</a>
                    </div>
                    <div class="row mt-2 mb-2">
                        <div class="col-12 col-sm-12">
                            <Button id="year" class="btn btn-default">{{__('label.this_year')}}</Button>
                            <Button id="month" class="btn btn-default">{{__('label.this_month')}}</Button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-sm-12">
                            <div id="User_Chart"></div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-xl-4">
                    <div class="video-box" style="padding: 25px 25px 50px 25px;">
                        <div class="box-title mt-0">
                            <h2 class="title"><i class="fa-solid fa-chart-pie fa-lg mr-2"></i>{{__('label.rent_earning_current_year')}}</h2>
                            <a href="{{ route('admin.rent-transaction.index') }}" class="btn btn-link">{{__('label.view_all')}}</a>
                        </div>
                        <div class="col-12 col-sm-12 mt-4">
                            <div id="Rent_Earning"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Plan Earning Statistice && Best Category -->
            <div class="row mb-4">
                <div class="col-12 col-xl-8 cart-bg">
                    <div class="box-title">
                        <h2 class="title"><i class="fa-solid fa-chart-column fa-lg mr-2"></i>{{__('label.plan_earning_statistice_current_year')}}</h2>
                        <a href="{{ route('admin.transaction.index') }}" class="btn btn-link">{{__('label.view_all')}}</a>
                    </div>
                    <div class="row">
                        <div class="col-12 mt-4">
                            <div id="PackageChart"></div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-xl-4">
                    <div class="video-box" style="padding: 25px 25px 50px 25px;">
                        <div class="box-title mt-0">
                            <h2 class="title"><i class="fa-solid fa-chart-pie fa-lg mr-2"></i>{{__('label.most_used_categorise')}}</h2>
                            <a href="{{ route('admin.category.index') }}" class="btn btn-link">{{__('label.view_all')}}</a>
                        </div>
                        <div class="col-12 col-sm-12 mt-4">
                            <div id="Category_Chart"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Most View Video & TVShow && Best Channel -->
            <div class="row mb-1">
                <div class="col-12 col-xl-8 cart-bg">
                    <div class="box-title">
                        <h2 class="title"><i class="fa-solid fa-chart-bar fa-lg mr-2"></i>{{__('label.most_view_movies_tvshow')}}</h2>
                    </div>

                    <ul class="nav nav-pills custom-tabs" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="pills-video-view-tab" data-toggle="pill" href="#pills-video-view" role="tab" aria-controls="pills-video-view" aria-selected="true">{{__('label.movies')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-tvshow-view-tab" data-toggle="pill" href="#pills-tvshow-view" role="tab" aria-controls="pills-tvshow-view" aria-selected="false">{{__('label.tv_show')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-shorts-view-tab" data-toggle="pill" href="#pills-shorts-view" role="tab" aria-controls="pills-shorts-view" aria-selected="false">{{__('label.shorts')}}</a>
                        </li>
                    </ul>

                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-video-view" role="tabpanel" aria-labelledby="pills-video-view-tab">
                            <div class="summary-table-card" style="color: var(--black-color);">
                                @for ($i = 0; $i < count($top_video_view); $i++)
                                    <div class="border-card bg-white">
                                        <div class="row">
                                            <div class="col-1">
                                                {{$i + 1 .'.'}}
                                            </div>
                                            <div class="col-7">
                                                <span class="avatar-control" title="{{ $top_video_view[$i]['name']}}">
                                                    <img src="{{$top_video_view[$i]['thumbnail']}}" style='height:40px; width:40px' />
                                                    {{String_Cut($top_video_view[$i]['name'],45)}}
                                                </span>
                                            </div>
                                            <div class="col-2 d-flex justify-content-start" style="font-weight: 600; font-size: 18px">
                                                {{ $top_video_view[$i]['type']['name'] ?? '-' }}
                                            </div>
                                            <div class="col-2 d-flex justify-content-start">
                                                <i class="fa-solid fa-eye mr-3 fa-xl"></i>     
                                                <p class="m-0 p-0 counting" data-count="{{No_Format($top_video_view[$i]['total_view'] ?? 00)}}"> {{No_Format($top_video_view[$i]['total_view'] ?? 00)}}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endfor
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-tvshow-view" role="tabpanel" aria-labelledby="pills-tvshow-view-tab">
                            <div class="summary-table-card" style="color: var(--black-color);">
                                @for ($i = 0; $i < count($top_tvshow_view); $i++)
                                    <div class="border-card bg-white">
                                        <div class="row">
                                            <div class="col-1">
                                                {{$i + 1 .'.'}}
                                            </div>
                                            <div class="col-7">
                                                <span class="avatar-control" title="{{ $top_tvshow_view[$i]['name']}}">
                                                    <img src="{{ $top_tvshow_view[$i]['thumbnail'] }}" style='height:40px; width:40px' />
                                                    {{String_Cut($top_tvshow_view[$i]['name'],45)}}
                                                </span>
                                            </div>
                                            <div class="col-2 d-flex justify-content-start" style="font-weight: 600; font-size: 18px">
                                                {{ $top_tvshow_view[$i]['type']['name'] ?? '-' }}
                                            </div>
                                            <div class="col-2 d-flex justify-content-start">
                                                <i class="fa-solid fa-eye mr-3 fa-xl"></i>     
                                                <p class="m-0 p-0 counting" data-count="{{No_Format($top_tvshow_view[$i]['total_view'] ?? 00)}}"> {{No_Format($top_tvshow_view[$i]['total_view'] ?? 00)}}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endfor
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-shorts-view" role="tabpanel" aria-labelledby="pills-shorts-view-tab">
                            <div class="summary-table-card" style="color: var(--black-color);">
                                @for ($i = 0; $i < count($top_shorts_view); $i++)
                                    <div class="border-card bg-white">
                                        <div class="row">
                                            <div class="col-1">
                                                {{$i + 1 .'.'}}
                                            </div>
                                            <div class="col-7">
                                                <span class="avatar-control" title="{{ $top_shorts_view[$i]['name']}}">
                                                    <img src="{{ $top_shorts_view[$i]['thumbnail'] }}" style='height:40px; width:40px' />
                                                    {{String_Cut($top_shorts_view[$i]['name'],45)}}
                                                </span>
                                            </div>
                                            <div class="col-2 d-flex justify-content-start" style="font-weight: 600; font-size: 18px">
                                                {{ $top_shorts_view[$i]['type']['name'] ?? '-' }}
                                            </div>
                                            <div class="col-2 d-flex justify-content-start">
                                                <i class="fa-solid fa-eye mr-3 fa-xl"></i>     
                                                <p class="m-0 p-0 counting" data-count="{{No_Format($top_shorts_view[$i]['total_view'] ?? 00)}}"> {{No_Format($top_shorts_view[$i]['total_view'] ?? 00)}}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-xl-4">
                    <div class="category-box">
                        <div class="box-title mt-0">
                            <h2 class="title"><i class="fa-solid fa-table-cells-large fa-lg mr-2"></i>{{__('label.best_channel')}}</h2>
                            <a href="{{ route('admin.channel.index')}}" class="btn btn-link">{{__('label.view_all')}}</a>
                        </div>
                        <div class="pt-3 mt-0">
                            <div class="row pr-3">
                                @for ($i = 0; $i < count($best_channel); $i++)
                                    @if($i > 0 && (($i % 4) == 1 || ($i % 4) == 2))
                                        <div class="col-5 mb-2 pr-0">
                                            <img src="{{$best_channel[$i]['portrait_img']}}" class="category-image">
                                            <div class="centered">{{$best_channel[$i]['name']}}</div>
                                        </div>
                                        @else
                                        <div class="col-7 mb-2 pr-0">
                                            <img src="{{$best_channel[$i]['portrait_img']}}" class="category-image">
                                            <div class="centered">{{$best_channel[$i]['name']}}</div>
                                        </div>
                                    @endif
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pagescript')
    <!-- Chart -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>

        // User Chart
        let userYear = JSON.parse(`<?php echo $user_year ?>`);
        let userMonth = JSON.parse(`<?php echo $user_month ?>`);
        let month = [
            '{{__("label.jan")}}', '{{__("label.feb")}}', '{{__("label.mar")}}', '{{__("label.apr")}}',
            '{{__("label.may")}}', '{{__("label.jun")}}', '{{__("label.jul")}}', '{{__("label.aug")}}',
            '{{__("label.sep")}}', '{{__("label.oct")}}', '{{__("label.nov")}}', '{{__("label.dec")}}'
        ] ;
        let chartOptions = {
            chart: {
                type: 'line',
                height: 400,
                toolbar: {
                    show: false
                },
                zoom: {
                    enabled: false
                },
                selection: {
                    enabled: false
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth', // ✅ makes the line smooth (you can use 'straight' or 'stepline')
                width: 3
            },
            markers: {
                size: 5,
                colors: ['#BAFA34'],
                strokeColors: '#fff',
                strokeWidth: 2
            },
            colors: ['#BAFA34'],
            grid: {
                borderColor: '#9a9a9a',
                strokeDashArray: 4
            },
            tooltip: {
                theme: 'dark',
                style: {
                    fontSize: '14px'
                }
            },
            series: [],
            xaxis: {
                categories: [],
                labels: {
                    style: {
                        fontSize: '14px',
                        fontWeight: 'bold',
                        colors: '#FFFFFF'
                    }
                }
            },
            yaxis: {
                labels: {
                    style: {
                        fontSize: '14px',
                        fontWeight: 'bold',
                        colors: '#FFFFFF'
                    }
                }
            },
            legend: {
                position: 'bottom',
                fontSize: '16px',
                fontWeight: 'bold',
                labels: {
                    colors: '#FFFFFF',
                    useSeriesColors: false
                }
            }
        };

        let chart = new ApexCharts(document.querySelector("#User_Chart"), chartOptions);
        chart.render();

        function loadChartData(type) {
            if (type === 'year') {
                chart.updateOptions({
                    series: [{
                        name: "{{ __('label.users') }}",
                        data: userYear.sum
                    }],
                    xaxis: {
                        categories: month
                    }
                });
            } else {
                let daysInMonth = userMonth.sum.length;
                chart.updateOptions({
                    series: [{
                        name: "{{ __('label.users') }}",
                        data: userMonth.sum
                    }],
                    xaxis: {
                        categories: Array.from({ length: daysInMonth }, (_, i) => (i + 1).toString())
                    }
                });
            }
        }

        loadChartData('year');
        document.getElementById('year').addEventListener('click', function () {
            loadChartData('year');
        });
        document.getElementById('month').addEventListener('click', function () {
            loadChartData('month');
        });
        
        // Rent Earning Statistice
        var rent_cData = JSON.parse(`<?php echo $rent_earning; ?>`);
        var rentOptions = {
            chart: {
                type: 'pie',
                height: 400
            },
            series: rent_cData['sum'],
            labels: month,
            colors: [
                '#FF6384', '#4BC0C0', '#FFCD56', '#B04645',
                '#35B03B', '#36A2EB', '#E007F0', '#9966FF',
                '#FF9F40', '#E04714', '#A19135', '#E876D3'
            ],
            legend: {
                position: 'bottom',
                fontSize: '14px',
                labels: {
                    colors: '#fff'
                }
            },
            tooltip: {
                theme: 'dark',
                y: {
                    formatter: function (val) {
                        return val;
                    }
                }
            }
        };
        var rentChart = new ApexCharts(document.querySelector("#Rent_Earning"), rentOptions);
        rentChart.render();


        // Package Earning Chart
        var package_cData = JSON.parse(`<?php echo $package_data; ?>`);
        let seriesData = [];
        for (let i = 0; i < package_cData.label.length; i++) {
            seriesData.push({
                name: package_cData.label[i],
                data: package_cData.sum[i]
            });
        }
        var packageOptions = {
            chart: {
                type: 'bar',
                stacked: true,
                height: 430,
                toolbar: { show: false }
            },
            tooltip: {
                theme: 'dark',
                style: {
                    fontSize: '14px'
                }
            },
            series: seriesData, // will be filled dynamically
            xaxis: {
                categories: month,
                labels: {
                    style: {
                        fontSize: '14px',
                        fontWeight: 'bold',
                        colors: '#FFFFFF'
                    }
                }
            },
            yaxis: {
                labels: {
                    style: {
                        fontSize: '14px',
                        fontWeight: 'bold',
                        colors: '#FFFFFF'
                    }
                }
            },
            legend: { 
                position: 'bottom',
                labels: {
                    colors: '#fff'
                }
            },
            plotOptions: {
                bar: { horizontal: false }
            }
        };
        var packageChart = new ApexCharts(document.querySelector("#PackageChart"), packageOptions);
        packageChart.render();


        // Most Used Categories
        var category_cData = JSON.parse(`<?php echo $most_used_categorise; ?>`);
        var categoryOptions = {
            chart: {
                type: 'donut', // Changed from 'pie' to 'donut'
                height: 400
            },
            series: category_cData.sum, // total used counts
            labels: category_cData.labels, // category names
            colors: [
                '#8abd1bff',   // Base color
                '#6ba704ff',   // Darker shade
                '#3e6b00ff',   // Darker shade
                '#2b5000ff'    // Darkest shade
            ],
            legend: {
                position: 'bottom',
                fontSize: '14px',
                labels: {
                    colors: '#fff'
                }
            },
            tooltip: {
                theme: 'dark',
                y: {
                    formatter: function (val) {
                        return val;
                    }
                }
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '60%' // Adjust donut hole size (50-80% works well)
                    }
                }
            }
        };
        var categoryChart = new ApexCharts(document.querySelector("#Category_Chart"), categoryOptions);
        categoryChart.render();
    </script>
@endsection