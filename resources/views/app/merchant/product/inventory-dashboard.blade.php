@extends('app.master')

@section('content')
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">  
    <span class="page-title" style="float: left;">{{$title}}</span>
    {{ Breadcrumbs::render() }}
    <div class="btn-group pull-right">
        <button id="btnGroupVerticalDrop7" type="button" class="btn btn-md green dropdown-toggle " data-toggle="dropdown" aria-expanded="true">
            More options <i class="fa fa-angle-down"></i>
        </button>
        <ul class="dropdown-menu" role="menu" aria-labelledby="btnGroupVerticalDrop7">
            <li>
                <a href="/merchant/product/createnew">Create product/service</a>
            </li>
            <li>
                <a href="/merchant/product/index">View inventory listing</a>
            </li>
            <li>
                <a href="/merchant/report/productsalesreport">Product wise sales report</a>
            </li>
        </ul>
    </div>
    </div>
    <!-- END PAGE HEADER-->

    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        @include('layouts.alerts')
        <form class="form-inline" method="post" role="form" id="inventory_dashboard_frm">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="col-md-12">
                @if($enable_inventory!=1)
                    <div class="row sameheight-container">
                        <div class="col-md-12">
                            <div class="portlet light bordered">
                                <div class="portlet-body">
                                    <div class="alert alert-info mt-1">
                                        <div class="">
                                            @if($enable_inventory==0)
                                                <p><strong>Your account's inventory hasn't been enabled yet. Do you want to enable it right now to create and update a single ledger for all of your products and sales automatically?
                                                    Get comprehensive, real-time reports of all sales & invoices, products' sales, and purchase history.</strong></p>
                                                <p style="padding-bottom:20px"><a onclick="enableService('{{$service_id}}')" class="btn blue pull-left" data-cy="enable_inventory">Enable inventory</a></p>
                                            @elseif ($enable_inventory==2)
                                                <p><strong>Service activation request has been sent. Our support team will get in touch with you shortly.</strong></p>
                                                <p style="padding-bottom:20px"><button disabled="" class="btn pull-left mb-1">In Review</button></p>
                                            @endif
                                        </div>
                                    </div>
                                    <h5></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @if($enable_inventory==1)
                    <div class="row sameheight-container">
                        <div class="col col-lg-6 col-md-6 col-sm-12 col-xs-12 mt-1">
                            <div class="card stats" data-exclude="xs">
                                <div class="card-block map-card-block px-2">
                                    <div class="row row-sm stats-container">
                                        <div class="col-12">
                                            <div class="stat-icon">
                                                <i class="fa fa-shopping-cart fab"></i>
                                            </div>
                                            <div class="stat">
                                                <div class="value"> {{$get_dashboard_statistics[0]->items_in_stock}} </div>
                                                <div class="name"> Items in stock </div>
                                            </div>
                                            <div class="progress stat-progress">
                                                <div class="progress-bar" style="width: 100%;"></div>
                                            </div>
                                        </div>
                                        <div class="col-12 mt-4">
                                            <div class="stat-icon">
                                                <i class="fa fa-shopping-cart fab"></i>
                                            </div>
                                            <div class="stat mt-4">
                                                <div class="value"> {{number_format($get_dashboard_statistics[0]->total_stock,0)}} </div>
                                                <div class="name"> Total stock available </div>
                                            </div>
                                            <div class="progress stat-progress">
                                                <div class="progress-bar" style="width: 100%;"></div>
                                            </div>
                                        </div>
                                        <div class="col-12 mt-4">
                                            <div class="stat-icon">
                                                <i class="fa fa-inr fab"></i>
                                            </div>
                                            <div class="stat mt-4">
                                                <div class="value"> ₹ {{number_format($get_dashboard_statistics[0]->total_stock_value,0)}} </div>
                                                <div class="name"> Total Stock value
                                                </div>
                                            </div>
                                            <div class="progress stat-progress">
                                                <div class="progress-bar" style="width: 100%;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col col-lg-6 col-md-6 col-sm-12 col-xs-12 mt-1">
                            <div class="card stats" data-exclude="xs">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-4 pull-right mr-0 mt-1">
                                                <select class="form-control pull-right" onchange="setFilter(this.value,'pie')" data-placeholder="Filter by" name="pie_chart_filter">
                                                    <option value="last_7_days" @if($pie_chart_filter=='last_7_days') selected @endif>Last 7 days</option>
                                                    <option value="this_month" @if($pie_chart_filter=='this_month') selected @endif>This month</option>
                                                    <option value="last_6_months" @if($pie_chart_filter=='last_6_months') selected @endif>Last 6 months</option>
                                                    <option value="current_year" @if($pie_chart_filter=='current_year') selected @endif>Current Year</option>
                                                    <option value="last_year" @if($pie_chart_filter=='last_year') selected @endif>Last Year</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="chart-container">
                                        <div class="chart has-fixed-height" id="pie_basic"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @if(!empty($time_wise_sales_report))
                    <div class="row sameheight-container">
                        <div class="col col-lg-6 col-md-6 col-sm-12 col-xs-12 mt-1">
                            <div class="card stats" data-exclude="xs">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-4 pull-right mr-0 mt-1">
                                                <select class="form-control pull-right" onchange="setFilter(this.value,'bar')" data-placeholder="Filter by" name="bar_chart_filter">
                                                    <option value="last_7_days" @if($bar_chart_filter=='last_7_days') selected @endif>Last 7 days</option>
                                                    <option value="this_month" @if($bar_chart_filter=='this_month') selected @endif>This month</option>
                                                    <option value="last_6_months" @if($bar_chart_filter=='last_6_months') selected @endif>Last 6 months</option>
                                                    <option value="current_year" @if($bar_chart_filter=='current_year') selected @endif>Current Year</option>
                                                    <option value="last_year" @if($bar_chart_filter=='last_year') selected @endif>Last Year</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="chart-container">
                                        <div class="chart has-fixed-height" id="bars_basic"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <!-- END PAYMENT TRANSACTION TABLE -->
            </div>
        </form>
    </div>
    <!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->
<!-- confirm modal for inventory service enable -->
<div class="modal fade" id="confirm" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Activate Service</h4>
            </div>
            <div class="modal-body">
                Are you sure you would like to enable this service?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn default" data-dismiss="modal" data-cy="close_activate_service_modal">Close</button>
                <button id="enableServiceOk" class="btn blue" data-cy="confirm_activate_Service_modal">Confirm</button>
            </div>
        </div>
    </div>
</div>

<!-- Service Activation message -->
<div class="modal fade" id="serviceActivated" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Activate Service</h4>
            </div>
            <div class="modal-body">
                <p id="serviceActivatedMsg"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn default" data-dismiss="modal" data-cy="close_service_activated_modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('footer')
<script src="https://cdnjs.cloudflare.com/ajax/libs/echarts/5.3.2/echarts.min.js"></script>
<script type="text/javascript">
    function setFilter(date_filter,chart_type){
        document.getElementById('loader').style.display = 'block';
        $("#inventory_dashboard_frm").submit();
    }
    
    var pie_basic_element = document.getElementById('pie_basic');
    if (pie_basic_element) {
        var pie_basic = echarts.init(pie_basic_element);
        pie_basic.setOption({
            color: [
                '#07a2a4','#9a7fd1','#588dd5','#f5994e','#c05050',
                '#59678c','#c9ab00','#7eb00a','#6f5553','#c14089',
                '#2ec7c9','#b6a2de','#5ab1ef','#ffb980','#d87a80',
                '#8d98b3','#e5cf0d','#97b552','#95706d','#dc69aa'
            ],          
            
            textStyle: {
                fontFamily: 'Roboto, Arial, Verdana, sans-serif',
                fontSize: 13
            },
    
            title: {
                text: 'Stock Status',
                left: 'center',
                textStyle: {
                    fontSize: 20,
                    fontWeight: 500
                },
                subtextStyle: {
                    fontSize: 12
                }
            },
    
            tooltip: {
                trigger: 'item',
                backgroundColor: 'rgba(0,0,0,0.75)',
                padding: [10, 15],
                textStyle: {
                    fontSize: 13,
                    fontFamily: 'Roboto, sans-serif'
                },
                formatter: "{a} <br/>{b}: {c} ({d}%)"
            },
    
            legend: {
                orient: 'vertical',
                bottom: '40%',
                right: '5%',                   
                data: ['In stock', 'Out of stock','Low stock'],
                itemHeight: 10,
                itemWidth: 10
            },
    
            series: [{
                name: 'Stock status',
                type: 'pie',
                radius: '70%',
                center: ['50%', '50%'],
                itemStyle: {
                    normal: {
                        borderWidth: 1,
                        borderColor: '#fff'
                    }
                },
                data: [
                    {value: {{$stock_status['in_stock']}}, name: 'In stock'},
                    {value: {{$stock_status['out_of_stock']}}, name: 'Out of stock'},
                    {value: {{$stock_status['low_stock']}}, name: 'Low stock'}
                ]
            }]
        });
    }
    

    
    //start load bar chart
    var bars_basic_element = document.getElementById('bars_basic');
    if (bars_basic_element) {
        var bars_basic = echarts.init(bars_basic_element);
        bars_basic.setOption({
            color: ['#3398DB'],
            title: {
                text: 'Time period wise total sales',
                left: 'center',
                textStyle: {
                    fontSize: 20,
                    fontWeight: 500
                },
                subtextStyle: {
                    fontSize: 12
                }
            },
            tooltip: {
                trigger: 'axis',
                axisPointer: {            
                    type: 'shadow'
                }
            },
            grid: {
                left: '3%',
                right: '4%',
                bottom: '3%',
                containLabel: true
            },
            xAxis: [
                {
                    type: 'category',
                    data: [
                        @foreach($time_wise_sales_report as $kd=>$val)
                            "{{$val['axis']}}",
                        @endforeach
                    ],
                    axisTick: {
                        alignWithLabel: true
                    }
                }
            ],
            yAxis: [
                {
                    type: 'value'
                }
            ],
            series: [
                {
                    name: 'Total sales',
                    type: 'bar',
                    barWidth: '20%',
                    data: [
                        @foreach($time_wise_sales_report as $val)
                            {{$val['value']}},
                        @endforeach
                    ]
                }
            ]
        });
    }
    
    </script>
    @endsection

