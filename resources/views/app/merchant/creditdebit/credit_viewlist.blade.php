@extends('app.master')

@section('content')
<div class="page-content">
    <div class="page-bar">
        <span class="page-title" style="float: left;">{{$title}}</span>
        {{ Breadcrumbs::render() }}
    </div>
    <!-- BEGIN SEARCH CONTENT-->
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PORTLET-->

            <div class="portlet">

                <div class="portlet-body">

                    <form class="form-inline" role="form" action="" method="post">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-group">
                            <input class="form-control form-control-inline rpt-date" type="text" required value='<x-localize :date="$from_date" type="date" /> - <x-localize :date="$to_date" type="date" />' id="daterange" name="date_range" autocomplete="off" data-date-format="{{ Session::get('default_date_format')}}" />
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
                                    ID
                                </th>
                                <th class="td-c">
                                    Credit note no
                                </th>
                                <th class="td-c">
                                    Date
                                </th>
                                <th class="td-c">
                                    {{$customer_default_column['customer_name']??'Customer name'}}
                                </th>
                                <th class="td-c">
                                    {{$customer_default_column['customer_code']??'Customer code'}}
                                </th>
                                <th class="td-c">
                                    State
                                </th>
                                <th class="td-c">
                                    GST no.
                                </th>
                                <th class="td-c">
                                    Bill date
                                </th>
                                <th class="td-c">
                                    Bill no
                                </th>
                                <th class="td-c">
                                    Amount
                                </th>

                                <th class="td-c">

                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <form action="" method="">
                                @foreach($list as $v)
                                <tr>
                                    <td class="td-c">
                                        {{$v->id}}
                                    </td>

                                    <td class="td-c">
                                        {{$v->credit_debit_no}}
                                    </td>
                                    <td class="td-c">
                                      
                                        <x-localize :date="$v->date" type="date" />
                                    </td>
                                    <td class="td-c">
                                        {{$v->first_name}} {{$v->last_name}}
                                    </td>
                                    <td class="td-c">
                                        {{$v->customer_code}}
                                    </td>
                                    <td class="td-c">
                                        {{$v->state}}
                                    </td>
                                    <td class="td-c">
                                        {{$v->gst_number}}
                                    </td>
                                    <td class="td-c">
                                      
                                        <x-localize :date="$v->bill_date" type="date" />
                                    </td>
                                    <td class="td-c">
                                        {{$v->invoice_no}}
                                    </td>
                                    <td class="td-c">
                                        {{ Helpers::moneyFormatIndia($v->total_amount) }}
                                    </td>
                                    <td>
                                        <div class="btn-group dropup">
                                            <button class="btn btn-xs btn-link dropdown-toggle" type="button" data-toggle="dropdown">
                                                &nbsp;&nbsp;<i class="fa fa-ellipsis-v"></i>&nbsp;&nbsp;
                                            </button>
                                            <ul class="dropdown-menu pull-right" role="menu">
                                                <li>
                                                    <a href="/merchant/creditnote/view/{{$v->encrypted_id}}"><i class="fa fa-table"></i> View</a>
                                                </li>
                                                <li>
                                                    <a href="/merchant/creditnote/update/{{$v->encrypted_id}}"><i class="fa fa-edit"></i> Update</a>
                                                </li>
                                                @if($v->file_path!='')
                                                <li>
                                                    <a href="{{$v->file_path}}" target="_BLANK"><i class="fa fa-file"></i> View attachment</a>
                                                </li>
                                                @endif
                                                <li>
                                                    <a href="#basic" onclick="document.getElementById('deleteanchor').href = '/merchant/expense/creditnote/delete/{{$v->encrypted_id}}'" data-toggle="modal"><i class="fa fa-times"></i> Delete</a>
                                                </li>

                                            </ul>
                                        </div>
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


<div class="modal fade" id="basic" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Delete credit note</h4>
            </div>
            <div class="modal-body">
                Are you sure you would not like to use this credit note in the future?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn default" data-dismiss="modal">Close</button>
                <a href="" id="deleteanchor" class="btn delete">Confirm</a>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


@endsection