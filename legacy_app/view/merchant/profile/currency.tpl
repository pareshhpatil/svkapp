<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">
        {include file="../../common/breadcumbs.tpl" title={$title} links=$links}
        {if $bank_detail.international_payment==1}
            <a href="#currency" data-toggle="modal" class="btn blue pull-right"> Add currency </a>
        {else}
            <a href="#stripe" data-toggle="modal" class="btn blue pull-right"> Add currency </a>
        {/if}

    </div>
    <!-- END PAGE HEADER-->

    <!-- BEGIN SEARCH CONTENT-->
    <!-- BEGIN SEARCH CONTENT-->

    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">
            {if $success!=''}
                <div class="alert alert-block alert-success fade in"
                    style="margin-left: 0px !important; margin-right: 0px !important;">
                    <button type="button" class="close" data-dismiss="alert"></button>
                    <p>{$success}</p>
                </div>
            {/if}
            <!-- BEGIN PAYMENT TRANSACTION TABLE -->
        </div>
        <div class="col-md-12">
            <div class="portlet ">
                <div class="portlet-body">
                    <table class="table table-striped  table-hover" id="table-no-export">
                        <thead>
                            <tr>
                                <th class="td-c">
                                    Code
                                </th>
                                <th class="td-c">
                                    Name
                                </th>
                                <th class="td-c">

                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            {foreach from=$merchant_currency item=v}
                                <tr>
                                    <td class="td-c">
                                        {$v.code}
                                    </td>
                                    <td class="td-c">
                                        {$v.name}
                                    </td>
                                    <td class="td-c">
                                        <a href="#basic"
                                            onclick="document.getElementById('deleteanchor').href = '/merchant/profile/delete/{$v.code}/currency'"
                                            data-toggle="modal" class="btn btn-xs red"><i class="fa fa-times"></i> Delete
                                        </a>
                                    </td>
                                </tr>

                            {/foreach}
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


<div class="modal fade" id="basic" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Delete currency</h4>
            </div>
            <div class="modal-body">
                Are you sure you would not like to use this currency in the future?
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
<div class="modal fade" id="stripe" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="StripeModalLabel">
                    Enable international currency
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>


            <div class="modal-body">
                To enable international currency integrate Stripe into your Swipez account we will redirect you to the
                Stripe website to complete your
                onboarding.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <a type="button" href="/merchant/profile/complete/international" class="btn btn-primary">Integrate</a>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="currency" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/merchant/profile/addcurrency" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="StripeModalLabel">
                        Add new currency
                    </h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group" id="mappvalue">
                                <label for="inputPassword12" class="col-md-4 control-label" id="valname">Currency <span
                                        class="required">
                                        *</span></label>
                                <div class="col-md-6" id="mappval">
                                    <select required  class="form-control select2me" name="currency"
                                        id="mapping_value" data-placeholder="Select">
                                        <option value="">Select currency</option>
                                        {foreach from=$currency_list item=v}
                                            {if !in_array($v.code,$currency)}
                                            <option value="{$v.code}">{$v.name} ({$v.code})</option>
                                            {/if}
                                        {/foreach}
                                    </select>
                                    <span class="help-block"></span><span class="help-block"></span>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>