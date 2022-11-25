@extends('app.master')

@section('content')
<script src="/assets/admin/layout/scripts/invoiceformat.js?version=16650658059" type="text/javascript"></script>
<div class="page-content">
    <div class="page-bar">
        <span class="page-title" style="float: left;">{{$title}}</span>
        {{ Breadcrumbs::render('home.billtransaction') }}
        <a onclick="showbilltransaction();" class="btn blue pull-right"> Add bill transaction </a>
    </div>
    <!-- BEGIN SEARCH CONTENT-->
    <div class="row">
        @include('layouts.alerts')
        <div class="col-md-12">
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
                                   Date
                                </th>
                                 <th class="td-c">
                                    Code
                                </th> 
                                 <th class="td-c">
                                    Cost type
                                </th> 
                                 <th class="td-c">
                                    Rate
                                </th> 
                                 <th class="td-c">
                                    Unit
                                </th> 
                                 <th class="td-c">
                                    Amount
                                </th> 
                                <th class="td-c">
                                   Description
                                </th>
                                <th class="td-c">
                                   Status
                                </th>
                                <th class="td-c">
                                    
                                </th>
                            </tr>
                        </thead>
                        <tbody id="table-bill-code">
                        <form action="" method="">
                          
                            @foreach($list as $v)
                            <tr>
                                <td class="td-c">
                                    {{$v->id}}
                                </td>
                                <td class="td-c">
                                {{date("d M Y", strtotime($v->date))}}
                                </td>
                                 <td class="td-c">
                                    {{$v->cost_code}}
                                </td> 
                                 <td class="td-c">
                                    {{$v->cost_type}}
                                </td> 
                                 <td class="td-c">
                                 {{number_format($v->rate,2)}}
                                </td> 
                                 <td class="td-c">
                                    {{$v->unit}}
                                </td> 
                                 <td class="td-c">
                                    {{number_format($v->amount,2)}}
                                </td> 
                                <td class="td-c">
                                    {{$v->description}}
                                </td>
                                <td class="td-c">
                                    @if($v->status==0)Not billed @else Billed @endif
                                </td>
                                
                                <td class="td-c">
                                @if($v->status==0)
                                    <div class="btn-group dropup">
                                        <button class="btn btn-xs btn-link dropdown-toggle" type="button" data-toggle="dropdown">
                                            &nbsp;&nbsp;<i class="fa fa-ellipsis-v"></i>&nbsp;&nbsp;
                                        </button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a onclick="showupdatebilltransaction('{{$v->id}}','{{$v->cost_code}}','{{date("d M Y", strtotime($v->date))}}','{{$v->rate}}','{{$v->unit}}','{{$v->amount}}','{{$v->cost_type}}','{{$v->description}}');"><i class="fa fa-edit"></i> Update</a>
                                            </li>
                                            <li>
                                                <a href="#basic" onclick="document.getElementById('deleteanchor').href = '/merchant/billedtransaction/delete/{{$v->id}}'"  data-toggle="modal" ><i class="fa fa-times"></i> Delete</a>  
                                            </li>
                                        </ul>
                                    </div>
                                    @endif
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
                <h4 class="modal-title">Delete billed transaction</h4>
            </div>
            <div class="modal-body">
                Are you sure you would not like to use this transaction in the future?
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
@include('app.merchant.contract.add-bill-code-modal')
@include('app.merchant.master.billedtransaction.update-billed-transaction-modal')
@endsection
