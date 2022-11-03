<style>
    .pagination {
        margin-top: 10px !important;
    }
</style>
<div class="page-content">
    <div class="page-bar">
        {include file="../../common/breadcumbs.tpl" title={$title} links=$links}
    </div>

    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PAYMENT TRANSACTION TABLE -->

            <div class="portlet ">
                <div class="portlet-body">
                    <table class="table table-striped table-hover" id="example">
                        <thead>
                            <tr>
                                <th>
                                    Subscription #
                                </th>
                                <th class="td-c">
                                    {$customer_default_column.customer_name|default:'Customer name'}
                                </th>
                                <th class="td-c">
                                    Type
                                </th>
                                <th class="td-c">
                                    Start date
                                </th>
                                <th class="td-c">
                                    Summary
                                </th>
                                <th class="td-c">
                                    Mode
                                </th>
                                <th class="td-c">
                                    End Mode
                                </th>
                                <th class="td-c">
                                    Created date
                                </th>
                                <th class="td-c">
                                    Next bill date
                                </th>
                                <th>
                                    
                                </th>
                            </tr>
                        </thead>
                        
                        <tbody class="text-center">
                            
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- END PAYMENT TRANSACTION TABLE -->
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>
<!-- /.modal -->

<div class="modal fade delete_modal" id="basic" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Delete Subscription</h4>
            </div>
            <div class="modal-body">
                Are you sure you would not like to use this Subscription in the future?
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
    <div id="close_tab" hidden>
        <a href="javascript:;" class="remove" data-original-title="Close" title="" onclick="return closeSidePanel();">
            <i class="fa fa-times" aria-hidden="true"> </i>
        </a>
    </div>
    <div class="panel">
        <div id="subscription_view_ajax"">
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