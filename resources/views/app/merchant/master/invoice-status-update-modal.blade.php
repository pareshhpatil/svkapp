<style>
    .panel-wrap {
        position: fixed;
        top: 0;
        bottom: 0;
        right: 0;
        left: 50% !important;
        /* width: 80em; */
        transform: translateX(100%);
        transition: .3s ease-out;
        z-index: 100;
    }

    .panel {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        background: #fff;
        color: #394242;
        overflow-y: scroll;
        overflow-x: hidden;
        padding: 1em;
        box-shadow: 0 5px 15px rgb(0 0 0 / 50%);
        margin-bottom: 0;
    }

    .remove {
        padding: 4px 3px;
        cursor: pointer;
        float: left;
        position: relative;
        top: 0px;
        color: #fff;
        right: 25px;
        z-index: 99999;
    }

    .remove:hover {
        color: #FFF;
    }

    .remove i {
        font-size: 19px !important;
    }

    .subscription-info i {
        font-size: 22px !important;
    }

    .cust-head {
        text-align: left !important;
    }

    .subscription-info h3 {
        text-align: center;
        color: #000;
        margin-bottom: 2px !important;
    }

    .subscription-info h2 {
        font-weight: 600;
        margin-bottom: 0 !important;
        margin-top: 5px !important;
        text-align: center;
    }

    .td-head {
        font-size: 19px;
    }

    @media (max-width: 767px) {
        .cust-head {
            text-align: center !important;
        }

        .panel-wrap {
            /* width: 23em; */
            top: 0;
            bottom: 0;
            right: 0;
            left: 0;
            position: fixed;
        }
    }

    @media (min-width: 768px) and (max-width: 991px) {
        .panel-wrap {
            /* width: 47em; */
            position: fixed;
            right: 0;
        }
    }

    @media (min-width: 992px) and (max-width: 1199px) {
        .panel-wrap {
            /* width: 47em; */
            position: fixed;
            right: 0;
        }
    }

    .right-value {
        text-align: right;
    }
</style>
<div class="panel-wrap" id="updatepanelWrapIdInvoiceStatus">


    
    <div class="panel">
        <div class='cnt223'>
            <h3 class="modal-title">Update status

            <a class="close " data-toggle="modal"  onclick="return closeSidePanelInvoiceStatus();">
                    <button type="button" class="close" aria-hidden="true"></button></a>
            </h3>
            <hr>
            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <form class="form-horizontal form-row-sepe" id="invoice-status" name="invoice-status-form" action="/merchant/invoice-status/save" method="post">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden"  id="config_key" name="config_key" >
                        <div class="form-body">
                            <!-- Start profile details -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Status <span class="required">*
                                            </span></label>
                                        <div class="col-md-5">
                                            <input type="text" required="true" maxlength="15" name="status" id="config_value" class="form-control" placeholder="Enter invoice status">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="pull-right">
                                        <a href="#" onclick="return closeSidePanelInvoiceStatus();" class="btn default">Cancel</a>
                                        <input type="submit" value="Update Status" onclick="return updatebillcode();" class="btn blue" />
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>