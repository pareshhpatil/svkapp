@extends('app.master')

@section('content')
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">Payout Nodal&nbsp;</h3>
    <!-- END PAGE HEADER-->


    <!-- BEGIN SEARCH CONTENT-->

    <!-- BEGIN PAGE CONTENT-->
    <div class="row">

        <div class="col-md-12">
            @include('layouts.alerts')
            <div class="col-md-6">
                <!-- BEGIN PAYMENT TRANSACTION TABLE -->

                <div class="portlet ">
                    <div class="portlet-title">
                        <div class="caption">
                            Available Balance
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="form-group">
                                        <label class="control-label col-md-4"><b>Nodal Balance</b></label>
                                        <label class="control-label col-md-4"><i class="fa fa-inr"></i> {{$balance}}</label>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <br>
                                    <div class="row">
                                        <div class="col-md-offset-5 col-md-6">
                                            <a href="#basic" data-toggle="modal"  class="btn blue">Withdraw</a>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <!-- END PAYMENT TRANSACTION TABLE -->

                <div class="portlet ">
                    <div class="portlet-title">
                        <div class="caption">
                            Withdrawals
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>
                                                Date
                                            </th>
                                            <th>
                                                Amount
                                            </th>
                                            <th class="td-c">
                                                Narrative
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($list as $v)
                                        <tr>
                                            <td>
                                                <x-localize :date="$v->created_date" type="date" />
                                              
                                            </td>
                                            <td>
                                                {{$v->amount}}
                                            </td>
                                            <td>
                                                {{$v->narrative}}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- END PAYMENT TRANSACTION TABLE -->
            </div>
            @if(!empty($nodal_account))
            <div class="col-md-6">
                <!-- BEGIN PAYMENT TRANSACTION TABLE -->

                <div class="portlet ">
                    <div class="portlet-title">
                        <div class="caption">
                            Nodal Account details
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="form-group">
                                        <label class="control-label col-md-4"><b>Account Number</b></label>
                                        <label class="control-label col-md-4">{{$nodal_account->account_number}}</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label class="control-label col-md-4"><b>IFSC code</b></label>
                                        <label class="control-label col-md-4">{{$nodal_account->ifsc}}</label>
                                    </div>
                                </div>
                                <br>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- END PAYMENT TRANSACTION TABLE -->
            </div>
            @endif
            
        </div>
        <!-- END PAGE CONTENT-->
    </div>
</div>
</div>
<!-- END CONTENT -->
<div class="modal fade" id="basic" tabindex="-1" role="basic" aria-hidden="true">
    <form action="/merchant/payout/withdraw" method="post" id="confirm_covering_frm" class="form-horizontal form-row-sepe">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Withdraw amount</h4>
                </div>
                <div class="modal-body">

                    <div class="form-body">
                        <!-- Start profile details -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-md-4">Amount<span class="required">*
                                        </span></label>
                                    <div class="col-md-6">
                                        <input type="number" step="0.01" max="{{$balance}}" required  name="amount" class="form-control" >
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4">Narrative <span class="required">
                                        </span></label>
                                    <div class="col-md-6">
                                        <input type="text" maxlength="60"  name="narrative" class="form-control">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>					
                    <!-- End profile details -->
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="submit" class="btn blue">
                    <button type="button" class="btn default" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
    </form>
</div>
<!-- /.modal-dialog -->
</div>
@endsection