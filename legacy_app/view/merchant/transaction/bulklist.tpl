
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">{$title}&nbsp;
    </h3>
    <!-- END PAGE HEADER-->

    <!-- BEGIN SEARCH CONTENT-->
    <!-- BEGIN SEARCH CONTENT-->

    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">
            {if $success!=''}
                <div class="alert alert-block alert-success fade in">
                    <button type="button" class="close" data-dismiss="alert"></button>
                    <p>{$success}</p>
                </div>
            {/if}
            <!-- BEGIN PAYMENT TRANSACTION TABLE -->

            <div class="portlet ">
                <div class="portlet-body">
                    <form id="myForm" action="" method="post">
                        <table class="table table-striped  table-hover" id="table-no-export">
                            <thead>
                                <tr>
                                    <th class="td-c">
                                        ID
                                    </th>
                                    <th class="td-c">
                                        Invoice ID
                                    </th>
                                    <th class="td-c">
                                        Date
                                    </th>
                                    <th class="td-c">
                                        Mode
                                    </th>

                                    <th class="td-c">
                                        
                                    </th>
                                </tr>
                            </thead>
                            <tbody>

                                {foreach from=$list item=v}
                                    <tr>
                                        <td class="td-c">
                                            {$v.id}
                                        </td>
                                        <td class="td-c">
                                            <a target="_BLANK" href="/merchant/paymentrequest/view/{$v.encrypted_req_id}">{$v.payment_request_id}</a>
                                        </td>
                                        <td class="td-c">
                                            {$v.settlement_date}
                                        </td>
                                        <td class="td-c">
                                            {if $v.offline_response_type==1}
                                                NEFT/RTGS
                                            {else if $v.offline_response_type==2}
                                                Cheque
                                            {else if $v.offline_response_type==3}
                                                Cash
                                            {else if $v.offline_response_type==5}
                                                Online Payment
                                            {/if}
                                        </td>
                                        <td class="td-c">

                                            <a  id="{$v.id}" title="Update transaction" onclick="updateRespond(this.id);" class="btn btn-xs green"><i class="fa fa-edit"></i> Edit </a>
                                            <a href="#basic" onclick="document.getElementById('deleteanchor').href = '/merchant/transaction/delete/{$v.encrypted_id}/staging'" data-toggle="modal" class="btn btn-xs red"><i class="fa fa-times"></i> Delete </a>

                                        </td>
                                    </tr>
                                {/foreach}


                            </tbody>
                        </table>
                        <input type="hidden" name="paymentresponse_id" id="paymentresponse_id" />
                    </form>
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
                <h4 class="modal-title">Delete Transaction</h4>
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

{if $is_update==1}
    <!-- /.modal -->
    <div class="modal fade bs-modal-lg" id="respond" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" id="closeguest" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">

                    <div class="portlet ">
                        <div class="portlet-body">
                            <div class="row">
                                <div class="col-md-10">
                                    <form class="form-horizontal" action="/merchant/transaction/updaterespond/staging" method="POST"  id="profile_update" >
                                        <div class="form-body">
                                            <div class="alert alert-danger display-hide">
                                                <button class="close" data-close="alert"></button>
                                                You have some form errors. Please check below.
                                            </div>


                                            <div class="form-group">
                                                <label for="inputPassword12" class="col-md-5 control-label">Transaction type <span class="required">*
                                                    </span></label>
                                                <div class="col-md-5">
                                                    <select class="form-control input-sm select2me"  name="response_type" onchange="responseType(this.value);" data-placeholder="Select type">
                                                        <option value="1" {if $res_type==1} selected {/if}>NEFT/RTGS</option>
                                                        <option value="2" {if $res_type==2} selected {/if}>Cheque</option>
                                                        <option value="3" {if $res_type==3} selected {/if}>Cash</option>
                                                        <option value="5" {if $res_type==5} selected {/if}>Online Payment</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group" {if $res_type==2 || $res_type==3} style="display: none;" {/if} id="bank_transaction_no">
                                                <label for="inputPassword12" class="col-md-5 control-label">Bank ref no</label>
                                                <div class="col-md-5">
                                                    <input class="form-control form-control-inline input-sm" id="bank_transaction_no_" name="bank_transaction_no" type="text" value="{$res_details.bank_transaction_no}" placeholder="Bank ref number"/>
                                                </div>
                                            </div>
                                            <div id="cheque_no" {if $res_type!=2} style="display: none;" {/if}>
                                                <div class="form-group" >
                                                    <label for="inputPassword12" class="col-md-5 control-label">Cheque no</label>
                                                    <div class="col-md-5">
                                                        <input class="form-control form-control-inline input-sm" id="cheque_no_" name="cheque_no" {$validate.number}  type="text" value="{$res_details.cheque_no}" placeholder="Cheque no"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" {if $res_type!=3} style="display: none;" {/if} id="cash_paid_to">
                                                <label for="inputPassword12" class="col-md-5 control-label">Cash paid to</label>
                                                <div class="col-md-5">
                                                    <input class="form-control form-control-inline input-sm" id="cash_paid_to_" name="cash_paid_to" {$validate.name}  type="text" value="{$res_details.cash_paid_to}" placeholder="Cash paid to"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputPassword12" class="col-md-5 control-label">Date <span class="required">*
                                                    </span></label>
                                                <div class="col-md-5">
                                                    <input class="form-control form-control-inline input-sm date-picker" onkeypress="return false;"  autocomplete="off" data-date-format="dd M yyyy"  required name="date" type="text" value="{{$res_details.settlement_date}|date_format:"%d %b %Y"}" placeholder="Date"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputPassword12" class="col-md-5 control-label">Bank name</label>
                                                <div class="col-md-5">
                                                    <input class="form-control form-control-inline input-sm" name="bank_name" type="text" value="{$res_details.bank_name}" placeholder="Bank name"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputPassword12" class="col-md-5 control-label">Amount <span class="required">*
                                                    </span></label>
                                                <div class="col-md-5">
                                                    <input class="form-control form-control-inline input-sm" name="amount" required {$validate.amount} type="text" value="{$res_details.amount}" placeholder="Amount"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-8"></div>
                                                <div class="col-md-3">
                                                    <input type="hidden" name="offline_response_id" value="{$res_details.id}" />
                                                    <input type="hidden" name="payment_request_id" value="{$res_details.payment_request_id}" />
                                                    <input type="submit" class="btn blue" value="Update">
                                                </div>
                                            </div>
                                        </div>


                                    </form>		
                                </div>


                            </div>


                        </div>
                    </div>

                </div>

            </div>

        </div>
        <!-- /.modal-content -->
    </div>
    <a  data-toggle="modal" id="respondupdate" href="#respond"></a>
    <script>
        document.getElementById('respondupdate').click();
        responseType({$res_type});
    </script>
    <!-- /.modal -->
{/if}