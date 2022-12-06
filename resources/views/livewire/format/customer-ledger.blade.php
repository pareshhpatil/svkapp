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
    

    function hideLedger() {
        $('.page-sidebar-wrapper').css('pointer-events','auto');
        document.getElementById("panelLedger").style.boxShadow = "none";
        document.getElementById("panelLedger").style.transform = "translateX(100%)";
    }
</script>
<div class="panel-wrap" id="panelLedger">
    <div class="panel">
        <header class="cd-panel__header">
            <h3 class="page-title">Customer Ledger
            <a class="close " data-toggle="modal" onclick="return closeSidePanelLedger();">
                    <button type="button" class="close" aria-hidden="true"></button></a>
            </h3>
            <hr>
        </header>
        <div id="product_error" class="alert alert-danger" style="display: none;">
        </div>
        <div class="portlet light ">
            <div class="portlet-body form">
                <div id="subscription_view_ajax" "=""><div class=" portlet light bordered">
                    <div class="portlet-body form">
                        <div class="subscription-info">
                            <div class="row">
                                <div class="col-md-8">
                                    <h2 class="cust-head" id="lname"></h2>
                                    <p class="text-left" id="lcustomer_code"></p>
                                </div>
                                <div class="col-md-4 text-right">
                                    <h2 class=""><i class="fa fa-inr "></i> <span id="lbalance">0.00</span></h2>
                                    <p class="">Balance</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="portlet light bordered">
                    <div class="portlet-body">

                        <table class="table table-striped  table-hover" id="table-no-export">
                            <thead>
                                <tr>
                                    <th class="td-c">
                                        Date
                                    </th>
                                    <th class="td-c">
                                        Description
                                    </th>
                                    <th class="td-c">
                                        Status
                                    </th>
                                    <th class="td-c">
                                        Amount
                                    </th>
                                    <th class="td-c">
                                        Reference No
                                    </th>

                                </tr>
                            </thead>
                            <tbody id="ledger">

                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>