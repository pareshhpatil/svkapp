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
<div class="panel-wrap" id="panelWrapIdBillCodePanel" x-show="billcodepanel">



    <div class="panel">
        <div class='cnt223'>
            <h3 class="modal-title">Add bill code
                <a class="close " data-toggle="modal" @click="billcodepanel=false;">
                    <button type="button" class="close" aria-hidden="true"></button></a>
            </h3>
            <hr>
            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <form class="form-horizontal form-row-sepe" id="billcodeform" name="billcodeform" action="/merchant/billcode/create" method="post">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="project_id" id="_project_id">
                        <input type="hidden" id="comefrom">
                        <div class="form-body">
                            <!-- Start profile details -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Bill Code <span class="required">*
                                            </span></label>
                                        <div class="col-md-5">
                                            <input type="text" required="true" maxlength="45" id="bill_code_name" name="bill_code" class="form-control" placeholder="Enter bill code">
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
                                            <input type="text" required="true" maxlength="100" id="bill_code_description"  name="bill_description" class="form-control" placeholder="Enter bill description">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="pull-right">
                                        <a href="#" @click="billcodepanel=false;" class="btn default">Cancel</a>
                                        <a href="#" value="Add bill code" @click="setBillCodes();" onclick="addbillcode();" class="btn blue" >Submit</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="portlet light bordered ">
                            <div class="portlet-body form">
                                <h3 class="form-section">Bill code list</h3>
                                <div class="table-scrollable">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th class="td-c">
                                                <b>Bill code</b>
                                                </th>
                                                <th class="td-c">
                                                    <b>Description</b>
                                                </th>
                                            </tr>
                                            <template x-for="(grp, gindex) in bill_codes" :key="gindex">
                                                <tr>
                                                    <th class="td-c" x-text="grp.code">

                                                    </th>
                                                    <th class="td-c" x-text="grp.title">

                                                    </th>
                                                </tr>
                                            </template>
                                            </thead>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>