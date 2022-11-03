
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">
        {include file="../../common/breadcumbs.tpl" title={$title} links=$links}
    </div>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        {if isset($haserrors)}
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                <strong>Error!</strong>
                <div class="media">
                    {foreach from=$haserrors key=k item=v}
                        <p class="media-heading">{$k} - {$v.1}</p>
                    {/foreach}
                </div>

            </div>
        {/if}
        {if isset($success)}
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert"></button>
                <strong>Success!</strong>{$success}
            </div>
        {/if}
        <form action="/merchant/profile/updatesetting" method="post" id="submit_form"  class="form-horizontal form-row-sepe">
            {CSRF::create('profile_setting')}

            <div class="col-md-12">
                <div class="alert alert-danger display-none">
                    <button class="close" data-dismiss="alert"></button>
                    You have some form errors. Please check below.
                </div>
                <div class="portlet light bordered">
                    <div class="portlet-body form">
                        {if $type==false}

                            <!-- 
                            <h4 class="form-section">Invoice bulk upload details</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Upload status</label>
                                        <div class="col-md-8">
                                            <label class="control-label">
                            {if $setting.invoice_bulk_upload_limit>0}
                                Enable
                            {else}
                                Disable
                            {/if}
                        </label>
                        <br>
                    </div>
                </div>
            </div>
        </div>
                            {if $setting.invoice_bulk_upload_limit>0}
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Upload limit</label>
                                            <div class="col-md-8">
                                                <label class="control-label">{$setting.invoice_bulk_upload_limit}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            {/if}

                            <h4 class="form-section">Customer bulk upload details</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Upload status</label>
                                        <div class="col-md-8">
                                            <label class="control-label">
                            {if $setting.customer_bulk_upload_limit>0}
                                Enable
                            {else}
                                Disable
                            {/if}
                        </label>
                        <br>
                    </div>
                </div>
            </div>
        </div>
                            {if $setting.customer_bulk_upload_limit>0}
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Upload limit</label>
                                            <div class="col-md-8">
                                                <label class="control-label">{$setting.customer_bulk_upload_limit}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            {/if}
                            -->
                            <!--
                            <h4 class="form-section">Promotion & Advertisement</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Show Advertisement</label>
                                        <div class="col-md-8">
                                            <input type="checkbox" id="issupplier" name="show_ad" value="1" class="make-switch" data-on-text="&nbsp;Enabled&nbsp;&nbsp;" 
                            {if $setting.show_ad==1}
                                checked
                            {/if} data-off-text="&nbsp;Disabled&nbsp;">

                     <br>
                     <br>
                 </div>
             </div>
         </div>
     </div>-->
                            <!--
     <div class="row">
         <div class="col-md-6">
             <div class="form-group">
                 <label class="control-label col-md-4">Promotion offers</label>
                 <div class="col-md-8">
                     <label class="control-label">
                            {if $setting.promotion_id>0}
                                Enable
                            {else}
                                Disable
                            {/if}
                        </label>
                        <br>
                    </div>
                </div>
            </div>
        </div>
                            -->

                            <h4 class="form-section">Customer management</h4>
                            <!-- Start Bulk upload details -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Auto Approve</label>
                                        <div class="col-md-8">
                                            <input type="checkbox" id="issupplier" name="auto_approve" value="1" class="make-switch" data-on-text="&nbsp;Enabled&nbsp;&nbsp;" 
                                                   {if $setting.auto_approve==1}
                                                       checked
                                                   {/if} data-off-text="&nbsp;Disabled&nbsp;">

                                            <br>
                                            <br>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Reminders</label>
                                        <div class="col-md-8">
                                            <input type="checkbox" name="is_reminder" value="1" class="make-switch" data-on-text="&nbsp;Enabled&nbsp;&nbsp;" 
                                                   {if $setting.is_reminder==1}
                                                       checked
                                                   {/if} data-off-text="&nbsp;Disabled&nbsp;">

                                            <br>
                                            <br>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--<div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Auto generate customer code</label>
                                        <div class="col-md-8">
                                            <input type="checkbox" id="idauto1" name="customer_auto_generate" onchange="setautogenerate(1);" value="1" class="make-switch" data-on-text="&nbsp;Enabled&nbsp;&nbsp;" 
                            {if $setting.customer_auto_generate==1}
                                checked
                            {/if} data-off-text="&nbsp;Disabled&nbsp;">

                     <br>
                     <br>
                 </div>
             </div>
         </div>
     </div>

     <div class="row">
         <div class="col-md-6">
             <div class="form-group">
                 <label class="control-label col-md-4">Customer code prefix</label>
                 <div class="col-md-3">
                     <input type="text" id="idprefix1" class="form-control" {if $setting.customer_auto_generate==0}readonly={/if} required value="{$setting.prefix}" name="prefix" maxlength="6">
                     <br>
                 </div>
             </div>
         </div>
     </div>-->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Password protection for bill pay & package</label>
                                        <div class="col-md-3">
                                            <input type="checkbox" name="password_validation" value="1" class="make-switch" data-on-text="&nbsp;On&nbsp;&nbsp;" 
                                                   {if $setting.password_validation==1}
                                                       checked
                                                   {/if} data-off-text="&nbsp;Off&nbsp;">
                                            <br>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--<div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Reset password Send via Email and/or SMS</label>
                                        <div class="col-md-3">
                                            <label><input type="checkbox" name="resetsms" value="1" {if $reset_password.sms==1} checked {/if}></span> SMS</label>
                                            <label><input type="checkbox" name="resetemail" value="1" {if $reset_password.email==1} checked {/if}></span> E-mail</label>
                                            <br>
                                        </div>
                                    </div>
                                </div>
                            </div>-->
                        {else}


                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Estimate sequence prefix
                                            <i class="popovers fa fa-info-circle support blue" data-container="body" data-trigger="hover" data-content="Prefix entered here will be used as prefix for all system generated estimate numbers. For ex. if you enter EST/18-19/ as a prefix then the following sequence will be generated EST/18-19/1, EST/18-19/2, EST/18-19/3, ...." data-original-title="" title=""></i>
                                        </label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control" required value="{$estimate_inv_number.prefix}" name="estimate_prefix" maxlength="20">
                                            <br>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <!--
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-4">Auto generate vendor code</label>
                        <div class="col-md-8">
                            <input type="checkbox" id="idauto2" name="vendor_code_auto_generate" onchange="setautogenerate(2);" value="1" class="make-switch" data-on-text="&nbsp;Enabled&nbsp;&nbsp;" 
                            {if $setting.vendor_code_auto_generate==1}
                                checked
                            {/if} data-off-text="&nbsp;Disabled&nbsp;">

                     <br>
                     <br>
                 </div>
             </div>
         </div>
     </div>

     <div class="row">
         <div class="col-md-6">
             <div class="form-group">
                 <label class="control-label col-md-4">Vendor code prefix</label>
                 <div class="col-md-3">
                     <input type="text" id="idprefix2" class="form-control" {if $setting.vendor_code_auto_generate==0}readonly{/if} required value="{$vendor_code_number.prefix}" name="vprefix" maxlength="20">
                     <input type="hidden" value="{$vendor_code_number.auto_invoice_id}" name="vseq_id">
                     <input type="hidden" name="vprefix_val" value="{$vendor_code_number.val}">
                     <br>
                 </div>
             </div>
         </div>
     </div>-->
                            <!-- Start Bulk upload details -->
                            <div class="row">
                                <div class="col-md-6">

                                    <div class="portlet col-md-12">
                                        <div class="portlet-body">
                                            <h4 class="pull-left">Invoice sequence numbers</h4>
                                            <a  onclick="autoGenerateInvoiceNumber();"  class="btn btn-sm green pull-right"> <i class="fa fa-plus"> </i> Add new </a><br>
                                            <div class="table-scrollable">

                                                <table class="table table-bordered table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th class="td-c" style="width: 50%;">
                                                                Prefix
                                                            </th>
                                                            <th class="td-c" style="width: 40%;">
                                                                Current Number
                                                            </th>
                                                            <th class="td-c" style="width: 10%;">
                                                                Actions
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="new_invoice">
                                                        {foreach from=$invoice_numbers item=v}
                                                            <tr>
                                                                <td class="td-c" style="width: 50%;">
                                                                    {$v.prefix}
                                                                </td>
                                                                <td class="td-c" style="width: 40%;">
                                                                    {$v.val}
                                                                </td>
                                                                <th class="td-c" style="width: 10%;">
                                                                    <a href="#basic" onclick="updateauto_number('{$v.prefix}', '{$v.val}', '{$v.auto_invoice_id}');" data-toggle="modal"  class="btn btn-xs green"><i class="fa fa-edit"></i></a>
                                                                    <a href="#delete" onclick="document.getElementById('deleteanchor').href = '/merchant/profile/delete/{$v.auto_invoice_id}'" data-toggle="modal" class="btn btn-xs red"><i class="fa fa-remove"></i> </a>
                                                            </tr>
                                                        {/foreach}
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        {/if}
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="pull-right">
                                        <a href="/merchant/profile/settings" class="btn btn-default">Cancel</a>
                                        <input type="hidden" name="type" value="{$type}">
                                        <input type="submit" class="btn blue" value="Update"/>
                                    </div>
                                </div>
                            </div>
                        </div>	
                    </div>
                    				
                </div>						
                <!-- End Bulk upload details -->
                


            </div>
        </form>	
    </div>
</div>	
<!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->
</div>
<div class="modal fade" id="basic" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Update auto invoice number</h4>
            </div>
            <div class="modal-body">

                <form action="/merchant/profile/updateautonumber" method="post" id="submit_form" class="form-horizontal form-row-sepe">
                    <div class="form-body">
                        <!-- Start profile details -->
                        <div class="row">
                            <div class="form-group">
                                <label class="control-label col-md-5">Prefix<span class="required">
                                    </span></label>
                                <div class="col-md-4">
                                    <input type="text" name="subscript" id="subscript" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-5">Current number<span class="required">
                                    </span></label>
                                <div class="col-md-4">
                                    <input type="number"  name="last_number" id="autonumber" class="form-control">
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn default" data-dismiss="modal">Close</button>
                        <input type="hidden" id="autoinvoice_id" name="auto_invoice_id">
                        <input type="submit" class="btn blue">
                    </div>
                </form>
            </div>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="delete" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Delete invoice number</h4>
            </div>
            <div class="modal-body">
                Are you sure you would not like to use this number in the future?
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
<script>

    function setautogenerate(id)
    {

        if ($('#idauto' + id).is(':checked')) {
            $("#idprefix" + id).prop('readonly', false);
        } else
        {
            $("#idprefix" + id).prop('readonly', true);
        }

    }

    function updateauto_number(subscript, val, id)
    {
        document.getElementById('autoinvoice_id').value = id;
        document.getElementById('subscript').value = subscript;
        document.getElementById('autonumber').value = val;
    }
</script>