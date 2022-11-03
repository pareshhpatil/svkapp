@extends('app.master')

@section('content')
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">Transfer list&nbsp;</h3>
    <!-- END PAGE HEADER-->

    <!-- BEGIN SEARCH CONTENT-->
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PORTLET-->

            <div class="portlet">

                <div class="portlet-body" >

                    <form class="form-inline" role="form" action="" method="post">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-group">
                            <input class="form-control form-control-inline rpt-date" type="text" required  value='<x-localize :date="$from_date" type="date" /> - <x-localize :date="$to_date" type="date" />' id="daterange" name="date_range"  autocomplete="off" data-date-format="{{ Session::get('default_date_format')}}"  />
                        </div>
                        <input type="submit" class="btn blue" value="Search">
                    </form>

                </div>
            </div>

            <!-- END PORTLET-->
        </div>
    </div>

    <!-- BEGIN SEARCH CONTENT-->

    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PAYMENT TRANSACTION TABLE -->

            <div class="portlet ">

                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover" id="table-no-export">
                        <thead>
                            <tr>
                                <th>
                                    Transaction #
                                </th>
                                <th>
                                    Date
                                </th>
                                <th class="td-c">
                                    Name
                                </th>
                                <th class="td-c">
                                    Bank account
                                </th>
                                <th class="td-c">
                                    IFSC
                                </th>
                                <th class="td-c">
                                    Amount
                                </th>
                                <th class="td-c">
                                    UTR 
                                </th>
                                <th class="td-c">
                                    Narrative 
                                </th>
                                <th class="td-c">
                                    Status 
                                </th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach($list as $v)
                            <tr>
                                <td>
                                    {{$v->transfer_id}}
                                </td>
                                <td>
                              
                                    <x-localize :date="$v->created_date" type="date" />
                                </td>
                                <td>
                                    {{$v->name}}
                                </td>
                                <td>
                                    {{$v->bank_account_no}}
                                </td>
                                <td>
                                    {{$v->ifsc_code}}
                                </td>
                                <td>
                                    {{ Helpers::moneyFormatIndia($v->amount) }}
                                </td>

                                <td>
                                    {{$v->utr_number}}
                                </td>
                                <td>
                                    {{$v->narrative}}
                                </td>
                                <td>
                                    @if($v->status==1)
                                    Success
                                    @else
                                    Pending
                                    @endif
                                </td>

                            </tr>
                            @endforeach

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