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

                <div class="portlet-body" >

                    <form class="form-inline" role="form" action="" method="post">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-group">
                            <input class="form-control form-control-inline rpt-date" type="text" required  value='<x-localize :date="$from_date" type="date" /> - <x-localize :date="$to_date" type="date" />'  id="daterange" name="date_range"  autocomplete="off" data-date-format="{{ Session::get('default_date_format')}}"  />
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
            <div class="portlet">
                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover" id="table-no-export">
                        <thead>
                            <tr>
                                <th class="td-c">
                                    ID
                                </th>
                                <th class="td-c">
                                    Bill no
                                </th>
                                <th class="td-c">
                                    Bill date
                                </th>
                                <th class="td-c">
                                    Vendor name
                                </th>
                                <th class="td-c">
                                    Email
                                </th>
                                <th class="td-c">
                                    Mobile
                                </th>
                                <th class="td-c">
                                    State
                                </th>


                                <th class="td-c">
                                    Amount
                                </th>
                                <th class="td-c">
                                    Date
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
                                    {{$v->expense_id}}
                                </td>
                                <td class="td-c">
                                    {{$v->invoice_no}}
                                </td>
                                <td class="td-c">
                                    <x-localize :date="$v->bill_date" type="date" />
                                </td>
                                <td class="td-c">
                                    @if($v->file_path!='')
                                    <a href="{{$v->file_path}}" target="_BLANK">{{$v->vendor_name}}</a>
                                    @else 
                                    {{$v->vendor_name}}
                                    @endif

                                </td>
                                <td class="td-c">
                                    {{$v->email_id}}
                                </td>
                                <td class="td-c">
                                    {{$v->mobile}}
                                </td>
                                <td class="td-c">
                                    {{$v->state}}
                                </td>


                                <td class="td-c">
                                    {{ Helpers::moneyFormatIndia($v->total_amount) }}
                                </td>
                                <td class="td-c">
                                
                                    <x-localize :date="$v->created_date" type="datetime" />
                                </td>


                                <td class="td-c">

                                    <div class="hidden-xs btn-group dropup" style="position: absolute;">
                                        <button class="btn btn-xs btn-link dropdown-toggle" type="button" data-toggle="dropdown">
                                            &nbsp;&nbsp;<i class="fa fa-ellipsis-v"></i>&nbsp;&nbsp;
                                        </button>
                                        <ul class="dropdown-menu pull-right" role="menu">
                                            <li>
                                                <a href="/merchant/expense/approve/{{$v->encrypted_id}}" ><i class="fa fa-check"></i> Approve</a>
                                            </li>
                                            @if($v->file_path!='')
                                            <li>
                                                <a href="{{$v->file_path}}" target="_BLANK"><i class="fa fa-file"></i> View invoice</a>
                                            </li>
                                            @endif
                                            <li>
                                                <a href="#basic" onclick="document.getElementById('deleteanchor').href = '/merchant/expense/bulkexpense/delete/{{$v->encrypted_id}}'" data-toggle="modal"  ><i class="fa fa-times"></i> Delete</a>
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
                <h4 class="modal-title">Delete expense</h4>
            </div>
            <div class="modal-body">
                Are you sure you would not like to use this expense in the future?
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

<div class="modal fade" id="payment" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <form action="/merchant/expense/updatepayment" method="post">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Update payment status</h4>
                </div>
                <div class="modal-body">

                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label col-md-4">Payment status<span class="required">
                                    </span></label>
                                <div class="col-md-6">
                                    <select onchange="paymentStatus(this.value);" id="payment_status" class="form-control" name="payment_status">
                                        <option value="0">Select..</option>
                                        <option value="1">Paid</option>
                                        <option value="2">Unpaid</option>
                                        <option value="3">Refunded</option>
                                        <option value="4">Cancelled</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="paymentmode" style="display: none;">
                        <div class="row" style="margin-bottom: 10px;">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="inputPassword12" class="col-md-4 control-label">Payment mode<span class="required">*
                                        </span></label>
                                    <div class="col-md-6">
                                        <select class="form-control " id="payment_mode"  name="payment_mode" onchange="responseType(this.value);" data-placeholder="Select type">
                                            <option value="0">Select..</option>
                                            <option value="1">NEFT/RTGS</option>
                                            <option value="2">Cheque</option>
                                            <option value="3">Cash</option>
                                            <option value="5">Online Payment</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="bank_transaction_no" style="margin-bottom: 10px;">
                            <div class="col-md-12">
                                <div class="form-group" >
                                    <label for="inputPassword12" class="col-md-4 control-label">Bank ref no</label>
                                    <div class="col-md-6">
                                        <input class="form-control form-control-inline " id="fbank_transaction_no" name="bank_transaction_no" type="text" value="" placeholder="Bank ref number"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="cheque_no" style="display: none;">
                            <div class="row" style="margin-bottom: 10px;">
                                <div class="col-md-12">
                                    <div class="form-group" >
                                        <label for="inputPassword12" class="col-md-4 control-label">Cheque no</label>
                                        <div class="col-md-6">
                                            <input class="form-control form-control-inline " id="fcheque_no" name="cheque_no"  type="text" value="" placeholder="Cheque no"/>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row" id="cash_paid_to" style="margin-bottom: 10px;">
                            <div class="col-md-12">
                                <div class="form-group" style="display: none;">
                                    <label for="inputPassword12" class="col-md-4 control-label">Cash paid to</label>
                                    <div class="col-md-6">
                                        <input class="form-control form-control-inline " id="fcash_paid_to" name="cash_paid_to" type="text" value="" placeholder="Cash paid to"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-bottom: 10px;">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="inputPassword12" class="col-md-4 control-label">Date <span class="required">*
                                        </span></label>
                                    <div class="col-md-6">
                                        <input class="form-control form-control-inline  date-picker" id="date" onkeypress="return false;"  autocomplete="off" data-date-format="dd M yyyy"  name="date" type="text" value="" placeholder="Date"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="bank_name" style="margin-bottom: 10px;">
                            <div class="col-md-12">
                                <div class="form-group" >
                                    <label for="inputPassword12" class="col-md-4 control-label">Bank name</label>
                                    <div class="col-md-6">
                                        <input class="form-control form-control-inline " id="fbank_name" name="bank_name" type="text" placeholder="Bank name"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-bottom: 10px;">
                            <div class="col-md-12">
                                <div class="form-group" >
                                    <label for="inputPassword12" class="col-md-4 control-label">Amount <span class="required">*
                                        </span></label>
                                    <div class="col-md-6">
                                        <input class="form-control form-control-inline " id="amount" name="amount" type="number" step="0.01" placeholder="Amount"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="vendor_id"  id="vendor_id">
                    <input type="hidden" name="expense_id"  id="expense_id">
                    <input type="hidden" name="transfer_id"  id="transfer_id">
                    <input type="hidden" name="narrative"  value="">
                    <button type="button" class="btn default" data-dismiss="modal">Close</button>
                    <input type="submit" class="btn blue" value="Save">
                </div>
            </div>
        </form>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<script>
hide_first_col=true;
</script>
@endsection