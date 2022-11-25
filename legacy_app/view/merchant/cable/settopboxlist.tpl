
<div class="page-content">
    <div class="page-bar">
        {include file="../../common/breadcumbs.tpl" title={$title} links=$links}
    </div>

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

            <div id="exp_success" style="display: none;" class="alert alert-block alert-success fade in">
                <button type="button" class="close" data-dismiss="alert"></button>
                <p>Expiry date has been updated</p>
            </div>

            <div class="portlet">
                
                <div class="portlet-body">

                    <form class="form-inline" action="" method="post" role="form">
                        <span  id="plan_invoice_create">
                            <div class="form-group">
                                <select class="form-control input-sm" data-placeholder="Status" name="status">
                                    <option value="">Select status</option>
                                    <option value="0" {if {$status== '0'}} selected {/if}>Pending</option>
                                    <option value="1" {if {$status== '1'}} selected {/if}>Approved</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <select class="form-control input-sm" data-placeholder="Expiry Status" name="expiry_status">
                                    <option value="">Select expiry status</option>
                                    <option value="1" {if {$expiry_status== '1'}} selected {/if}>Expired</option>
                                    <option value="2" {if {$expiry_status== '2'}} selected {/if}>Not Expired</option>
                                </select>
                            </div>
                        </span>
                        

                        <input type="submit" class="btn btn-sm blue-madison" value="Search" />
                    </form>

                </div>
            </div>
            <!-- BEGIN PAYMENT TRANSACTION TABLE -->

            <div class="portlet ">
                <div class="portlet-body">
                    <table id="example" class="display" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th >
                                    ID
                                </th>
                                <th >
                                    Modified Date
                                </th>
                                <th >
                                    Customer name
                                </th>
                                <th >
                                    Customer Code
                                </th>
                                <th >
                                    Set Top Box Number
                                </th>

                                
                                <th >
                                    Cost
                                </th>
                                <th >
                                    Expiry date
                                </th>
                                <th >
                                    Status
                                </th>
                                <th >
                                    
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        
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

<div class="modal fade" id="expiry" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Set Expiry Date</h4>
            </div>
            <div class="modal-body">
                <form action="post" id="exp_frm">
                    <input class="form-control form-control-inline date-picker" id="exp_date" type="text" name="expiry_date"  autocomplete="off" data-date-format="dd M yyyy"  placeholder="Expiry date"/>
                    <input type="hidden" name="service_id" id="stb_id">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="closebtn" class="btn default" data-dismiss="modal">Close</button>
                <a class="btn blue" onclick="updateExpiry();">Submit</a>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script>
    function setExpiryDetail(id, date)
    {
        document.getElementById('exp_date').value = date;
        document.getElementById('stb_id').value = id;
    }
</script>