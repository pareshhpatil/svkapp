@extends('app.master')

@section('content')

<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">
        <span class="page-title" style="float: left;">{{$title}}</span>
        {{ Breadcrumbs::render() }}
    </div>
    <!-- END PAGE HEADER-->
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
    <!-- BEGIN PAGE CONTENT-->

    <div class="row">
        @include('layouts.alerts')
        <div class="col-md-12">
            <div class="portlet ">
                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover dataTable no-footer" id="table-no-export">
                        <thead>
                            <tr>
                                <th class="td-c">
                                    #
                                </th>
                                <th class="td-c">
                                    GSTIN
                                </th>
                                <th class="td-c">
                                    Invoice number
                                </th>
                                <th class="td-c">
                                    Invoice date
                                </th>
                                <th class="td-c">
                                    Client name
                                </th>
                                <th class="td-c">
                                    Client GST
                                </th>

                                <th class="td-c">
                                    Source
                                </th>
                                <th class="td-c">
                                    Create date
                                </th>
                                <th class="td-c">
                                    Status
                                </th>

                                <th class="td-c">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <form action="" method="">
                                @php
                                $i=1;
                                @endphp
                                @foreach($list as $v)

                                <tr>
                                    <td class="td-c">
                                        {{$i++}}
                                    </td>
                                    <td class="td-c">
                                        {{$v->merchant_gst}}
                                    </td>
                                    <td class="td-c">
                                        {{$v->invoice_number}}
                                    </td>
                                    <td class="td-c">
                                        <x-localize :date="$v->invoice_date" type="date" />
                                      
                                    </td>
                                    <td class="td-c">
                                        {{$v->client_name}}
                                    </td>
                                    <td class="td-c">
                                        {{$v->client_gst}}
                                    </td>
                                    <td class="td-c">
                                        {{$v->source}}
                                    </td>

                                    <td class="td-c">
                                        <x-localize :date="$v->created_date" type="date" />
                                      
                                    </td>
                                    <td class="td-c">
                                        @if($v->status==1)
                                        Success
                                        @elseif($v->status==2)
                                        Failed
                                        @elseif($v->status==3)
                                        Cancelled
                                        @endif
                                    </td>
                                    <td class="td-c">
                                        <div class="btn-group dropup">
                                            <button class="btn btn-xs btn-link dropdown-toggle" type="button" data-toggle="dropdown">
                                                &nbsp;&nbsp;<i class="fa fa-ellipsis-v"></i>&nbsp;&nbsp;
                                            </button>
                                            <ul class="dropdown-menu" role="menu">
                                                @if($v->status==2)
                                                <li>
                                                    <a href="/merchant/einvoice/recreate/{{$v->encrypted_id}}"><i class="fa fa-plus"></i> Re-create </a>
                                                    <a class="iframe" href="/merchant/einvoice/errors/{{$v->encrypted_id}}"><i class="fa fa-table"></i> Errors </a>
                                                    <a href="#delete" onclick="document.getElementById('deleteanchor2').href = '/merchant/einvoice/delete/{{$v->encrypted_id}}'" data-toggle="modal"><i class="fa fa-times"></i> Delete</a>
                                                </li>
                                                @elseif($v->status==1)
                                                <li>
                                                    <a class="iframe" href="/merchant/einvoice/view/{{$v->encrypted_id}}"><i class="fa fa-table"></i> View </a>
                                                    <a href="/merchant/einvoice/view/{{$v->encrypted_id}}/pdf"><i class="fa fa-download"></i> Download PDF </a>
                                                    @if($v->cancel_active==1)
                                                    <a href="#basic" onclick="document.getElementById('deleteanchor').href = '/merchant/einvoice/cancel/{{$v->encrypted_id}}'" data-toggle="modal"><i class="fa fa-times"></i> Cancel</a>
                                                    @else
                                                    <a href="#cancelexpired" data-toggle="modal"><i class="fa fa-times"></i> Cancel</a>
                                                    @endif
                                                </li>
                                                @else
                                                <li>
                                                    <a href="#delete" onclick="document.getElementById('deleteanchor2').href = '/merchant/einvoice/delete/{{$v->encrypted_id}}'" data-toggle="modal"><i class="fa fa-times"></i> Delete</a>
                                                </li>
                                                @endif
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
                <h4 class="modal-title">Cancel e-Invoice</h4>
            </div>
            <div class="modal-body">
                Are you sure you would you like to cancel this e-Invoice?
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
<div class="modal fade" id="cancelexpired" tabindex="-1" role="cancelexpired" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Cancel e-Invoice</h4>
            </div>
            <div class="modal-body">
                Only invoices created within the last 24 hours can be cancelled
            </div>
            <div class="modal-footer">
                <button type="button" class="btn default" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="delete" tabindex="-1" role="delete" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Delete entry</h4>
            </div>
            <div class="modal-body">
                Are you sure you would you like to delete this record?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn default" data-dismiss="modal">Close</button>
                <a href="" id="deleteanchor2" class="btn delete">Confirm</a>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

@endsection