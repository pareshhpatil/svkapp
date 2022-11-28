<style>
    .panel-wrap {
        position: fixed;
        top: 0;
        bottom: 0;
        right: 0;
        left: 25%;
        /* width: 80em; */
        transform: translateX(100%);
        transition: .3s ease-out;
        z-index: 11;
    }

    .panel-wrap .panel {
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
        float: right;
        display: inline-block;
        padding: 2px 5px;
        /* background:#ccc; */
        cursor: pointer;
    }

    @media (max-width: 767px) {
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
</style>
<script>
    function showLedger() {
        document.getElementById("panelLedger").style.boxShadow = "0 0 0 9999px rgba(0,0,0,0.5)";
        document.getElementById("panelLedger").style.transform = "translateX(0%)";
    }

    function hideLedger() {
        document.getElementById("panelLedger").style.boxShadow = "none";
        document.getElementById("panelLedger").style.transform = "translateX(100%)";
    }
</script>
<div class="panel-wrap" id="panelLedger">
    <div class="panel">
        <header class="cd-panel__header">
            <h3 class="page-title">Create Product/Service
                {{-- <a href="javascript:;" class="btn btn-sm red" id="close"> <i class="fa fa-times"> </i></a> --}}
                <a href="javascript:;" class="remove" data-original-title="Close" title="" onclick="return hideLedger();">
                    <i class="fa fa-times"> </i>
                </a>
            </h3>
            <hr>
        </header>
        <div id="product_error" class="alert alert-danger" style="display: none;">
        </div>
        <div class="portlet light bordered">
            <div class="portlet-body form">
                <div id="subscription_view_ajax" "=""><div class=" portlet light bordered">
                    <div class="portlet-body form">
                        <div class="subscription-info">
                            <div class="row">
                                <div class="col-md-8">
                                    <h2 class="cust-head">Rakesh Takke (5619)</h2>
                                </div>
                                <div class="col-md-2">
                                    <h2><i class="fa fa-inr"></i> 0</h2>
                                    <p class="text-center">COLLECTED</p>
                                </div>
                                <div class="col-md-2">
                                    <h2><i class="fa fa-inr"></i> 200</h2>
                                    <p class="text-center">DUE</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <h3>
                                        Monthly
                                    </h3>
                                    <p class="text-center">MODE</p>
                                </div>
                                <div class="col-md-2">
                                    <h3>07 Sep 2021</h3>
                                    <p class="text-center">START DATE</p>
                                </div>
                                <div class="col-md-2">
                                    <h3> 07 Oct 2021
                                    </h3>
                                    <p class="text-center">END DATE</p>
                                </div>
                                <div class="col-md-2">
                                    <h3> 2
                                    </h3>
                                    <p class="text-center">OCCURRENCES</p>
                                </div>
                                <div class="col-md-2">
                                    <h3>1</h3>
                                    <p class="text-center">SENT</p>
                                </div>
                                <div class="col-md-2">
                                    <h3> 1
                                    </h3>
                                    <p class="text-center">PENDING</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="portlet light bordered">
                    <h3 class="page-title">Invoices created</h3>
                    <div class="portlet-body">
                        <div id="table-subscription_wrapper" class="dataTables_wrapper no-footer">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="btn-group tabletools-dropdown-on-portlet"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <div class="dataTables_length" id="table-subscription_length"><label>Show <select name="table-subscription_length" aria-controls="table-subscription" class="form-control input-xsmall input-inline">
                                                <option value="5">5</option>
                                                <option value="10">10</option>
                                                <option value="15">15</option>
                                                <option value="20">20</option>
                                                <option value="-1">All</option>
                                            </select> entries</label></div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div id="table-subscription_filter" class="dataTables_filter"><label>Search:<input type="search" class="form-control input-small input-inline" placeholder="" aria-controls="table-subscription"></label></div>
                                </div>
                            </div>
                            <div class="table-scrollable">
                                <table class="table table-striped  table-hover dataTable no-footer" id="table-subscription" role="grid" aria-describedby="table-subscription_info">
                                    <thead>
                                        <tr role="row">
                                            <th class="td-c sorting_desc" tabindex="0" aria-controls="table-subscription" rowspan="1" colspan="1" aria-sort="descending" aria-label="
                        Sent on
                    : activate to sort column ascending" style="width: 178px;">
                                                Sent on
                                            </th>
                                            <th class="td-c sorting" tabindex="0" aria-controls="table-subscription" rowspan="1" colspan="1" aria-label="
                        Due date
                    : activate to sort column ascending" style="width: 198px;">
                                                Due date
                                            </th>
                                            <th class="td-c sorting" tabindex="0" aria-controls="table-subscription" rowspan="1" colspan="1" aria-label="
                        Amount
                    : activate to sort column ascending" style="width: 182px;">
                                                Amount
                                            </th>
                                            <th class="td-c sorting" tabindex="0" aria-controls="table-subscription" rowspan="1" colspan="1" aria-label="
                        Status
                    : activate to sort column ascending" style="width: 173px;">
                                                Status
                                            </th>
                                            <th class="td-c sorting" tabindex="0" aria-controls="table-subscription" rowspan="1" colspan="1" aria-label="
                        Actions
                    : activate to sort column ascending" style="width: 178px;">
                                                Actions
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>



                                        <tr role="row" class="odd">
                                            <td class="td-c sorting_1">
                                                07 Sep 21
                                            </td>
                                            <td class="td-c">
                                                08 Sep 21
                                            </td>

                                            <td class="td-c">
                                                200.00
                                            </td>
                                            <td class="td-c">
                                                <span class="badge badge-pill status overdue">OVERDUE</span>
                                            </td>
                                            <td class="td-c">
                                                <div class="btn-group dropup" style="position: absolute;">
                                                    <button class="btn btn-xs btn-link dropdown-toggle" type="button" data-toggle="dropdown">
                                                        &nbsp;&nbsp;<i class="fa fa-ellipsis-v"></i>&nbsp;&nbsp;
                                                    </button>
                                                    <ul class="dropdown-menu pull-right" role="menu">
                                                        <li>
                                                            <a target="_BLANK" href="/merchant/paymentrequest/view/PBExksracvU5_wV9WhKpNxzIhGr--9A3lVc">
                                                                <i class="fa fa-table"></i> View invoice </a>
                                                        </li>
                                                        <li><a target="_BLANK" href="/merchant/paymentrequest/view/PBExksracvU5_wV9WhKpNxzIhGr--9A3lVc#respond" title="Settle request"><i class="fa fa-inr"></i> Settle</a>
                                                        </li>
                                                        <li><a href="/merchant/invoice/update/PBExksracvU5_wV9WhKpNxzIhGr--9A3lVc" title="Update request"><i class="fa fa-edit"></i> Edit</a>
                                                        </li>
                                                        <!--<li>
                                            <a href="/merchant/comments/view/PBExksracvU5_wV9WhKpNxzIhGr--9A3lVc" title="Comments" class="iframe"><i class="fa fa-comment"></i>Comments </a>
                                        </li>-->
                                                        <li>
                                                            <a title="Delete Invoice" href="#basic" onclick="document.getElementById('deleteanchor').href = '/merchant/paymentrequest/delete/PBExksracvU5_wV9WhKpNxzIhGr--9A3lVc'" data-toggle="modal"><i class="fa fa-remove"></i>Delete</a>
                                                        </li>
                                                        <li>
                                                            <div style="font-size: 0px;">
                                                                <abcd1>http://swipez.prod/patron/paymentrequest/view/PBExksracvU5_wV9WhKpNxzIhGr--9A3lVc</abcd1>
                                                            </div>
                                                            <a class="btn bs_growl_show" data-clipboard-action="copy" data-clipboard-target="abcd1"><i class="fa fa-clipboard"></i> Copy invoice Link</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="row">
                                <div class="col-md-5 col-sm-12">
                                    <div class="dataTables_info" id="table-subscription_info" role="status" aria-live="polite">Showing 1 to 1 of 1 entries</div>
                                </div>
                                <div class="col-md-7 col-sm-12">
                                    <div class="dataTables_paginate paging_simple_numbers" id="table-subscription_paginate">
                                        <ul class="pagination">
                                            <li class="paginate_button previous disabled" aria-controls="table-subscription" tabindex="0" id="table-subscription_previous"><a href="#"><i class="fa fa-angle-left"></i></a></li>
                                            <li class="paginate_button active" aria-controls="table-subscription" tabindex="0"><a href="#">1</a></li>
                                            <li class="paginate_button next disabled" aria-controls="table-subscription" tabindex="0" id="table-subscription_next"><a href="#"><i class="fa fa-angle-right"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>