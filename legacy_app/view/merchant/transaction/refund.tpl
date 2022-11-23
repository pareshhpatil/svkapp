</a>
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <br>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row no-margin">
        <div class="col-md-12">

            <div class="portlet ">
                <div class="portlet-title">
                    <div class="caption">
                        Refund payment
                    </div>

                </div>
                <div class="portlet-body">
                    <div class="row">
                        <div class="col-md-10">
                            <form class="form-horizontal" action="/merchant/transaction/saverefund" method="POST" >
                                <div class="form-body">
                                    <div class="alert alert-danger display-hide">
                                        <button class="close" data-close="alert"></button>
                                        You have some form errors. Please check below.
                                    </div>
                                    <div class="form-group">
                                        <label for="inputPassword12" class="col-md-5 control-label">Amount <span class="required">*
                                            </span></label>
                                        <div class="col-md-5">
                                            <input class="form-control form-control-inline input-sm" name="amount" required {$validate.amount} type="text" value="{$info.amount}" placeholder="Amount"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputPassword12" class="col-md-5 control-label">Reason</label>
                                        <div class="col-md-5">
                                            <input class="form-control form-control-inline input-sm" name="reason" {$validate.name}  type="text" placeholder="Reason"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-8"></div>
                                        <div class="col-md-3">
                                            <input type="hidden" name="transaction_id" value="{$info.transaction_id}" />
                                            <input type="hidden" name="pg_id" value="{$info.pg_id}" />
                                            <input type="submit" class="btn blue" value="Submit">
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
    <!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->
</div>