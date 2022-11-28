
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">
        {include file="../../common/breadcumbs.tpl" title={$title} links=$links}
    </div>
    <p>{if isset($se.description)}{$se.description}{else}Run your own customized loyalty program. Allow your customers to earn points on every purchase and redeem them while making payments to you.{/if}</p>
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
        <form action="/merchant/loyalty/updatesetting" method="post" id="submit_form"  class="form-horizontal form-row-sepe">
            <div class="col-md-12">
                <div class="alert alert-danger display-none">
                    <button class="close" data-dismiss="alert"></button>
                    You have some form errors. Please check below.
                </div>
                <div class="portlet light bordered">
                    <div class="portlet-body form">
                        <!-- End Bank details -->
                        <h4 class="form-section">Loyalty Points Settings</h4>
                        <!-- Start Bulk upload details -->
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label class="control-label col-md-4">Points nomenclature</label>
                                    <div class="col-md-3">
                                        <input type="text"  class="form-control"  required value="{if isset($se.points_nomenclature)}{$se.points_nomenclature}{else}Points{/if}" name="nomenclature">
                                        <br>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label class="control-label col-md-4">Expiry days</label>
                                    <div class="col-md-3">
                                        <input type="text"  class="form-control"  required value="{if isset($se.expiry)}{$se.expiry}{else}30{/if}" name="expiry">
                                        <br>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h4 class="form-section">Earning Mechanism</h4>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label class="control-label col-md-4">Buy Worth</label>
                                    <div class="col-md-3">
                                        <input type="text"  class="form-control"  required value="{if isset($se.earning_logic_rs)}{$se.earning_logic_rs}{else}100{/if}" name="earning_logic_rs">
                                        <br>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label class="control-label col-md-4">Earn Points</label>
                                    <div class="col-md-3">
                                        <input type="text"  class="form-control"  required value="{if isset($se.earning_logic_points)}{$se.earning_logic_points}{else}1.00{/if}" name="earning_logic_points">
                                        <br>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h4 class="form-section">Redemption Mechanism</h4>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label class="control-label col-md-4">Redeem Points</label>
                                    <div class="col-md-3">
                                        <input type="text"  class="form-control"  required value="{if isset($se.redeeming_logic_points)}{$se.redeeming_logic_points}{else}100.00{/if}" name="redeem_logic_points">
                                        <br>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label class="control-label col-md-4">Avail Rupees</label>
                                    <div class="col-md-3">
                                        <input type="text"  class="form-control"  required value="{if isset($se.redeeming_logic_rs)}{$se.redeeming_logic_rs}{else}1.00{/if}" name="redeem_logic_rs">
                                        <br>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label class="control-label col-md-4">Redemption threshold</label>
                                    <div class="col-md-3">
                                        <input type="text"  class="form-control"  required value="{if isset($se.redemption_threshold)}{$se.redemption_threshold}{else}100.00{/if}" name="threshold">
                                        <br>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="pull-right">
                                        <input type="hidden" name="id" value="{if isset($se.merchant_id)}{$se.merchant_id}{else}{/if}" name="id">
                                        <input type="submit" class="btn blue" value="Submit"/>
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

    function setautogenerate()
    {

        if ($('#idauto').is(':checked')) {
            $("#idprefix").prop('readonly', false);
        } else
        {
            $("#idprefix").prop('readonly', true);
        }

    }

    function updateauto_number(subscript, val, id)
    {
        document.getElementById('autoinvoice_id').value = id;
        document.getElementById('subscript').value = subscript;
        document.getElementById('autonumber').value = val;
    }
</script>