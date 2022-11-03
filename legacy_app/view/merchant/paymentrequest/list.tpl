<style>
    .pagination{
        matgin-top:10px !important;
    }
</style>
<div class="page-content">
    <div class="page-bar">
        {include file="../../common/breadcumbs.tpl" title={$title} links=$links}
    </div>

    <!-- END PAGE HEADER-->

    <!-- BEGIN SEARCH CONTENT-->
    {if $is_filter=='True'}
        <div class="row">
            {if isset($success)}
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert"></button>
                    <strong>Success!</strong>{$success}
                </div> 
            {/if}
            {if isset($error)}
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert"></button>
                    <strong>Error!</strong>{$error}
                </div> 
            {/if}

                {if isset($haserrors)}
                    <div class="alert alert-danger">
                        <button type="button" class="close" data-dismiss="alert"></button>
                        <strong>Error!</strong>
                        <div class="media">
                            {foreach from=$haserrors item=v}

                                <p class="media-heading">{$v.0} - {$v.1}.</p>
                            {/foreach}
                        </div>

                    </div>
                {/if}
                <div class="col-md-12">



                    <!-- BEGIN PORTLET-->

                    <div class="portlet">

                        <div class="portlet-body" >

                            <form class="form-inline" role="form" action="" method="post">
                                <div class="form-group">
                                    <label class="help-block" id="rptby">Sent date</label>
                                </div>
                                <div class="form-group">
                                    <input class="form-control form-control-inline  rpt-date" id="daterange"  autocomplete="off" data-date-format="{$session_date_format}"  name="date_range" type="text" value="{$from_date} - {$to_date}" placeholder="From date"/>
                                </div>

                                <div class="form-group">
                                    <select class="form-control " name="invoice_type" data-placeholder="Request type">
                                        <option value="">Request type</option>
                                        <option {if $invoice_type==1} selected="" {/if} value="1">Invoice</option>
                                        <option {if $invoice_type==2} selected="" {/if} value="2">Estimate</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <select class="form-control " name="invoice_status" data-placeholder="Invoice status">
                                        <option selected="" value="0">Unpaid</option>
                                        <option {if $invoice_status==1} selected="" {/if} value="1">Paid</option>
                                        <option {if $invoice_status==''} selected="" {/if} value="">All</option>
                                    </select>
                                </div>
                                <input type="submit" class="btn blue" value="Search">
                            </form>
                        </div>
                    </div>

                    <!-- END PORTLET-->
                </div>
            </div>
        {/if}
        <!-- BEGIN SEARCH CONTENT-->

        <!-- BEGIN PAGE CONTENT-->
        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN PAYMENT TRANSACTION TABLE -->

                <div class="portlet ">
                    <div class="portlet-body">
                        <table class="table table-striped  table-hover" id="example">
                            <thead>
                                <tr>
                                    <th>
                                        Request #
                                    </th>
                                    <th>
                                        {$customer_default_column.customer_name|default:'Customer name'}
                                    </th>
                                    <th>
                                        Type
                                    </th>
                                      <th>
                                        Project name
                                    </th>
                                    <th>
                                        Amount
                                    </th>
                                    <th>
                                        cost
                                    </th>
                                    <th>
                                        Due date
                                    </th>
                                    <th>
                                        Sent date
                                    </th>
                                    <th>
                                        Status
                                    </th>
                                    <th>

                                    </th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th colspan="10" style=""></th>
                                </tr>
                            </tfoot>
                            <tbody class="text-center request-list">
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
<!-- /.modal -->

<!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<div class="modal fade" id="basic" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Delete Invoice</h4>
            </div>
            <div class="modal-body">
                Are you sure you would not like to use this Invoice in the future?
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
</style>
<div class="panel-wrap" id="panelWrapId">
    
    <div class="panel">
    <h3 class="page-title">Revision history
           <a class="close " data-toggle="modal"  onclick="return closeRevisionSidePanel();">
                    <button type="button" class="close" aria-hidden="true"></button></a>
            </h3>
            <hr>
        <div id="subscription_view_ajax">
        </div>
    </div>
</div>
<script src=" /assets/global/plugins/jquery.min.js" type="text/javascript">
            </script>
<script>
    var show_summary = '{$link}';
    if (show_summary != '') {
        callSidePanel('{$link}');
    }
</script>