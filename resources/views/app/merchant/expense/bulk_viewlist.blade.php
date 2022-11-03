@extends('app.master')

@section('content')
<div class="page-content">
    <h3 class="page-title">Expense list&nbsp;
    </h3>
    <!-- BEGIN SEARCH CONTENT-->
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PORTLET-->

            

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
                                    Expense id
                                </th>
                                <th class="td-c">
                                    Expense no
                                </th>
                                <th class="td-c">
                                    Vendor name
                                </th>
                                <th class="td-c">
                                    Category
                                </th>
                                <th class="td-c">
                                    Department
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
                                    Payment status
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
                                    {{$v->expense_no}}
                                </td>
                                <td class="td-c">
                                    {{$v->vendor_name}}
                                </td>
                                <td class="td-c">
                                    {{$v->category}}
                                </td>
                                <td class="td-c">
                                    {{$v->department}}
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
                                <td class="td-c">
                                    @if($v->payment_status==1)
                                    Paid
                                    @elseif($v->payment_status==2)
                                    Unpaid
                                    @elseif($v->payment_status==3)
                                    Refunded
                                    @elseif($v->payment_status==4)
                                    Cancelled
                                    @else
                                    Submitted
                                    @endif
                                </td>

                                <td class="td-c">

                                    <div class="btn-group dropup">
                                        <button class="btn btn-xs btn-link dropdown-toggle" type="button" data-toggle="dropdown">
                                            &nbsp;&nbsp;<i class="fa fa-ellipsis-v"></i>&nbsp;&nbsp;
                                        </button>
                                        <ul class="dropdown-menut" role="menu">
                                            <li>
                                                <a href="/merchant/expense/bulkview/{{$v->encrypted_id}}" ><i class="fa fa-table"></i> View</a>
                                            </li>
                                            <li>
                                                <a href="/merchant/expense/bulkupdate/{{$v->encrypted_id}}" ><i class="fa fa-edit"></i> Update</a>
                                            </li>
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


@endsection