@extends('app.master')

@section('content')
<div class="page-content">
    <div class="page-bar">  
        <span class="page-title" style="float: left;">{{$title}}</span>
        {{ Breadcrumbs::render() }}
    </div>
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PORTLET-->
            @include('layouts.alerts')
            <div class="portlet">

                <div class="portlet-body" >

                    <form class="form-inline" role="form" action="" method="post">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-group">
                            <input class="form-control form-control-inline rpt-date" type="text" required  value='<x-localize :date="$from_date" type="date" /> - <x-localize :date="$to_date" type="date" />' id="daterange" name="date_range"  autocomplete="off" data-date-format="{{ Session::get('default_date_format')}}"  />
                        </div>
                        
                        <input type="submit" class="btn  blue" value="Search">
                    </form>

                </div>
            </div>

            <!-- END PORTLET-->
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            @include('layouts.alerts')
            <!-- BEGIN PAYMENT TRANSACTION TABLE -->
            <div class="portlet ">
               
                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover" id="table-no-export">
                        <thead>
                            <tr>
                                <th class="td-c">
                                    # ID
                                </th>
                                <th class="td-c">
                                    Date
                                </th>
                                <th class="td-c">
                                    Customer name
                                </th>
                                <th class="td-c">
                                    Email ID
                                </th>
                                <th class="td-c">
                                    Mobile
                                </th>
                                <th class="td-c">
                                    Amount
                                </th>
                                <th class="td-c">
                                    Status
                                </th>
                                
                            </tr>
                        </thead>
                        <tbody>
                        <form action="" method="">
                            @foreach($list as $v)
                            <tr>
                                <td class="td-c">
                                    {{$v->transaction_id}}
                                </td>
                                <td class="td-c">
                                    <x-localize :date="$v->created_date" type="datetime" />
                      
                                </td>
                                
                                <td class="td-c">
                                    {{$v->customer_name}}
                                </td>
                                <td class="td-c">
                                    {{$v->email_id}}
                                </td>
                                <td class="td-c">
                                    {{$v->mobile}}
                                </td>
                                <td class="td-c">
                                    {{ Helpers::moneyFormatIndia($v->amount) }}
                                </td>
                                <td class="td-c">
                                    {{$v->status}}
                                </td>
                                
                            </tr>
                            @endforeach
                        </form>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- END PAYMENT TRANSACTION TABLE -->
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->



@endsection