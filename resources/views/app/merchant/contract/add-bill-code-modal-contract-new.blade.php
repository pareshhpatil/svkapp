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
<div class="panel-wrap" id="panelWrapIdBillCode">



    <div class="panel">
        <div class='cnt223'>
            <h3 class="modal-title">Add bill code

                <a class="close " data-toggle="modal" @click="return closeBillCodePanel();">
                    <button type="button" class="close" aria-hidden="true"></button></a>
            </h3>
            <hr>
            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <form class="form-horizontal form-row-sepe" id="billcodeform" name="billcodeform" action="/merchant/billcode/create" method="post">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="project_id" value="{{$project_id}}"  id="_project_id" >
                        <input type="hidden"  id="comefrom" >
                        <div class="form-body">
                            <!-- Start profile details -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Bill Code <span class="required">*
                                            </span></label>
                                        <div class="col-md-5">
                                            <input type="text" required="true" maxlength="45" name="bill_code" id="new_bill_code" class="form-control" placeholder="Enter bill code">
                                            <div class="text-danger" id="new_bill_code_message"></div>
                                        </div>
                                    </div>
                                    <!-- <div class="form-group">
                                <label class="control-label col-md-3">Bill Title <span class="required">*
                                    </span></label>
                                <div class="col-md-5">
                                    <input type="text" required="true" maxlength="100" name="bill_title" class="form-control" placeholder="Enter bill title">
                                </div>
                            </div> -->
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Bill Description <span class="required">*
                                            </span></label>
                                        <div class="col-md-5">
                                            <input type="text" required="true" maxlength="100" name="bill_description" id="new_bill_description" class="form-control" placeholder="Enter bill description">
                                            <div class="text-danger" id="new_bill_description_message"></div>
                                            <input type="hidden" id="selectedBillCodeId" value=""/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="pull-right">
                                        <a href="#" @click="return closeBillCodePanel();" class="btn default">Cancel</a>
                                        <input type="button" value="Add bill code" @click="return addNewBillCode('{{ csrf_token() }}');" class="btn blue" />
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