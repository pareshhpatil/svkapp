<style>
    .pagination {
        margin-top: 10px !important;
    }
</style>
<div class="page-content">
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
                        <p class="media-heading">{$v.0} - {$v.1}</p>
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
        <form action="" method="post" id="submit_form" class="form-horizontal form-row-sepe">
            {CSRF::create('directpay_link')}
            <div class="col-md-12">
                <div class="alert alert-danger display-none">
                    <button class="close" data-dismiss="alert"></button>
                    You have some form errors. Please check below.
                </div>
                <div class="portlet light bordered">
                    <div class="portlet-body form">
                        <h3 class="page-title">
                            Get a payment link
                        </h3>
                        <h5>Direct pay option allows your customer to make payments via a simple link. All you need to
                            do is send the below link to your customer. Your customers can enter a amount and make a
                            payment to you via this link. Payments received via the Direct Pay option are visible under
                            Website transactions section in the Transactions menu.
                        </h5>
                        {if $display_url!=''}
                            <div class="row">
                                <div class="col-md-6">
                                    <h4 class="form-section">Direct pay link</h4>
                                    <div class="col-md-8 no-padding">
                                        <input type="text" class="form-control" readonly value="{$directpaylink}">
                                        <br>
                                        <div style="font-size: 0px;">
                                            <abc1>{$directpaylink}</abc1>
                                        </div>
                                        <a href="javascript:;" class="btn btn-sm blue bs_growl_show"
                                            data-clipboard-action="copy" data-clipboard-target="abc1"><i
                                                class="fa fa-clipboard"></i> Copy to clipboard</a>
                                        <a class="btn green input-sm" target="_BLANk"
                                            href="https://api.whatsapp.com/send?text={$directpaylink}">
                                            <i class="fa fa-whatsapp"></i> Share on Whatsapp</a>
                                    </div>
                                    <!-- Start Bulk upload details -->
                                </div>
                                <div class="col-md-6">
                                    <h4 class="form-section">Short link</h4>
                                    <div class="col-md-8 no-padding">
                                        <input type="text" readonly id="shortlink" class="form-control"
                                            value="{$shortlink}">
                                        <br>

                                        <div id="shortlinkdiv" style="font-size: 0px;">
                                            <abc2>{$shortlink}</abc2>
                                        </div>
                                        <a href="javascript:;" id="c_link" {if $shortlink!=""}style="display: none;" {/if}
                                            class="btn blue btn-sm green" onclick="getshortlink();"><i
                                                class="fa fa-link"></i> Create short link</a>
                                        <a href="javascript:;" id="cp_link" {if $shortlink==""}style="display: none;" {/if}
                                            class="btn btn-sm blue bs_growl_show" data-clipboard-action="copy"
                                            data-clipboard-target="abc2"><i class="fa fa-clipboard"></i> Copy short link</a>
                                        {if $shortlink!=""}<a class="btn green input-sm" target="_BLANk"
                                                href="https://api.whatsapp.com/send?text={$shortlink}">
                                            <i class="fa fa-whatsapp"></i> Share on Whatsapp</a>{/if}

                                    </div>
                                </div>
                            </div>

                        {else}
                            <div class="row">
                                <div class="col-md-6">
                                    <h4 class="form-section">Set Display URL</h4>
                                    <div class="col-md-5 no-padding">
                                        <input type="text" required="" class="form-control" maxlength="20"
                                            name="display_url">
                                        <br>
                                        <button type="submit" name="submit" class="btn blue">Save</button>
                                    </div>
                                    <!-- Start Bulk upload details -->
                                </div>
                            </div>
                        {/if}
                    </div>
                </div>
                <!-- End Bulk upload details -->



            </div>
        </form>
        {if $display_url!=''}
            <div class="col-md-12">
                <div class="portlet light bordered">
                    <div class="portlet-body">
                        <h3 class="page-title">
                            Payment link with fixed amount
                            <a href="#basic" data-toggle="modal" class="margin-top-10 btn btn-xs green pull-right"> Add new
                                <i class="fa fa-plus"></i></a>
                        </h3>
                        <h5>Direct pay option allows your customer to make payments via a simple link. To create a link with
                            a fixed amount click 'Add new' button and fill in the form. Your customers can make payments via
                            this link. Payments received via the Direct Pay option are visible under Website transactions
                            section in the Transactions menu.
                        </h5>

                        <table class="table table-striped  table-hover" id="table-no-export">
                            <thead>
                                <tr>
                                    <th class="td-c">
                                        ID
                                    </th>
                                    <th class="td-c">
                                        Name
                                    </th>
                                    <th class="td-c">
                                        Email
                                    </th>
                                    <th class="td-c">
                                        Mobile
                                    </th>
                                    <th class="td-c">
                                        Country
                                    </th>
                                    <th class="td-c">
                                        Amount
                                    </th>
                                    <th class="td-c">
                                        Currency
                                    </th>
                                    <th class="td-c">
                                        Purpose
                                    </th>
                                    <th class="td-c">
                                        Short Link
                                    </th>
                                    <th class="td-c">

                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <form action="" method="">
                                    {foreach from=$list item=v}
                                        <tr>
                                            <td class="td-c">
                                                {$v.id}
                                            </td>
                                            <td class="td-c">
                                                {$v.name}
                                            </td>
                                            <td class="td-c">
                                                {$v.email}
                                            </td>
                                            <td class="td-c">
                                                {$v.mobile}
                                            </td>
                                            <td class="td-c">
                                                {$v.country}
                                            </td>
                                            <td class="td-c">
                                                {$v.amount}
                                            </td>
                                            <td class="td-c">
                                                {$v.currency}
                                            </td>
                                            <td class="td-c">
                                                {$v.narrative}
                                            </td>
                                            <td class="td-c">
                                                <div style="font-size: 0px;">
                                                    <abc{$v.id+2}>{$v.short_link}</abc{$v.id+2}>
                                                </div>
                                                <a class="bs_growl_show" title="Copy Link" data-clipboard-action="copy"
                                                    data-clipboard-target="abc{$v.id+2}"> {$v.short_link}</a>
                                            </td>
                                            <!-- <td class="td-c">
                                                <a class="btn btn-xs green" target="_BLANk" href="https://api.whatsapp.com/send?text={$v.short_link}{if $v.mobile!=''}&phone=91{$v.mobile}{/if}">
                                                    <i class="fa fa-whatsapp"></i> Share</a>
                                                <a href="#delete" onclick="document.getElementById('deleteanchor').href = '/merchant/directpaylink/delete/{$v.encrypted_id}'" data-toggle="modal" class="btn btn-xs red"><i class="fa fa-times"></i> Delete </a>
                                            </td> -->
                                            <td class="td-c">
                                                <div class="btn-group dropup">
                                                    <button class="btn btn-xs btn-link dropdown-toggle" type="button"
                                                        data-toggle="dropdown">
                                                        &nbsp;&nbsp;<i class="fa fa-ellipsis-v"></i>&nbsp;&nbsp;
                                                    </button>
                                                    <ul class="dropdown-menu" role="menu">
                                                        <li><a target="_BLANk"
                                                                href="https://api.whatsapp.com/send?text={$v.short_link}{if $v.mobile!=''}&phone=91{$v.mobile}{/if}"
                                                                title="Whatsaap share"><i class="fa fa-whatsapp"></i> Share</a>
                                                        </li>
                                                        <li>
                                                            <a title="Delete" href="#delete"
                                                                onclick="document.getElementById('deleteanchor').href = '/merchant/directpaylink/delete/{$v.encrypted_id}'"
                                                                data-toggle="modal"><i class="fa fa-remove"></i> Delete</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>

                                    {/foreach}
                                </form>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        {/if}

        {if !empty($formlist)}
            <div class="col-md-12">
                <h3 class="page-title">
                    Form builder links
                </h3>
                <div class="portlet light bordered">
                    <div class="portlet-body">

                        <table class="table table-striped  table-hover" id="table-no-export">
                            <thead>
                                <tr>
                                    <th class="td-c">
                                        ID
                                    </th>
                                    <th class="td-c">
                                        Name
                                    </th>
                                    <th class="td-c">
                                        Link
                                    </th>
                                    <th class="td-c">

                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <form action="" method="">
                                    {foreach from=$formlist item=v}
                                        <tr>
                                            <td class="td-c">
                                                {$v.id}
                                            </td>
                                            <td class="td-c">
                                                {$v.name}
                                            </td>
                                            <td class="td-c">
                                                <div style="font-size: 0px;">
                                                    <abcd{$v.id+2}>{$server_name}/patron/form/submit/{$v.encrypted_id}
                                                    </abcd{$v.id+2}>
                                                </div>
                                                {$server_name}/patron/form/submit/{$v.encrypted_id}
                                            </td>

                                            <td class="td-c">
                                                <a class=" btn btn-xs green bs_growl_show" title="Copy Link"
                                                    data-clipboard-action="copy" data-clipboard-target="abcd{$v.id+2}"><i
                                                        class="fa fa-clipboard"></i> Copy</a>
                                            </td>
                                        </tr>

                                    {/foreach}
                                </form>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        {/if}
    </div>
</div>
<!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->
</div>

<div class="modal fade" id="delete" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Delete Request</h4>
            </div>
            <div class="modal-body">
                Are you sure you would not like to use this request in the future?
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
<div class="modal fade" id="basic" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-lg modal-content">
            <form action="/merchant/directpaylink/save" id="couponForm" method="post"
                class="form-horizontal form-row-sepe">
                {CSRF::create('directpay_save')}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Direct pay request</h4>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="portlet">
                                <div class="portlet-body form">
                                    <!--<h3 class="form-section">Profile details</h3>-->

                                    <div class="form-body">
                                        <!-- Start profile details -->
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="alert alert-danger display-none">
                                                    <button class="close" data-dismiss="alert"></button>

                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Customer code <span
                                                            class="required">
                                                        </span></label>
                                                    <div class="col-md-4">
                                                        <input type="text" maxlength="45" name="customer_code"
                                                            class="form-control">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Name <span class="required">
                                                        </span></label>
                                                    <div class="col-md-4">
                                                        <input type="text" {$validate.name} name="name"
                                                            class="form-control">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Email <span class="required">
                                                        </span></label>
                                                    <div class="col-md-4">
                                                        <input type="email" name="email" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Country <span class="required">
                                                        </span></label>
                                                    <div class="col-md-4">
                                                        <select name="country" class="form-control select2me"
                                                        data-placeholder="Select..." onchange="showStateDiv(this.value);">
                                                        <option value="">Select Country</option>
                                                        {foreach from=$country_code item=v}
                                                            <option {if $v.config_value=="India"} selected {/if} value="{$v.config_value}">{$v.config_value}</option>
                                                        {/foreach}
                                                    </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Mobile <span class="required">
                                                        </span></label>
                                                    <div class="col-md-4">
                                                        {* <input type="text" maxlength="10" {$validate.mobile}
                                                            name="mobile" class="form-control"> *}
                                                        <div class="input-group">
                                                            <span class="input-group-addon" id="country_code_txt">+91</span>
                                                            <input type="text" id="defaultmobile" required pattern="([0-9]{ldelim}10{rdelim})" title="Enter your valid mobile number" maxlength="10" name="mobile" class="form-control" aria-describedby="defaultmobile-error">
                                                        </div>
                                                        <span id="defaultmobile-error" class="help-block help-block-error"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Amount <span
                                                            class="required">
                                                        </span></label>
                                                    <div class="col-md-4">
                                                        <input type="number" step="0.01"  name="amount"
                                                            class="form-control">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Currency <span
                                                            class="required">*
                                                        </span></label>
                                                    <div class="col-md-4">
                                                        <select required class="form-control" name="currency">
                                                            <option value="">Select..</option>
                                                            {foreach from=$currency_list item=v}
                                                                <option value="{$v}">{$v}</option>
                                                            {/foreach}
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Purpose of payment <span
                                                            class="required">
                                                        </span></label>
                                                    <div class="col-md-6">
                                                        <textarea name="purpose"
                                                            class="form-control">{$post.descreption}</textarea>
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
                <div class="modal-footer">
                    <button type="button" class="btn default" id="closebutton" data-dismiss="modal">Close</button>
                    <input type="submit" class="btn blue" value="Save" />
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<script type="text/javascript">
function showStateDiv(country_name) {
    if(country_name!='') {
        if(country_name=='India') {
            $("#country_code_txt").text('+91');
            $("#defaultmobile").attr('pattern',"([0-9]{ldelim}10{rdelim})");
            $("#defaultmobile").attr('maxlength', "10");
        } else {
            $("#defaultmobile").attr('pattern', "([0-9]{ldelim}7,10{rdelim})");
            $("#defaultmobile").attr('maxlength', "10");
            $.ajax({
                type: 'POST',
                url: '/ajax/getCountryCode',
                data: {
                    "country_name": country_name,
                },
                success: function (data)
                {
                    obj = JSON.parse(data);
                    if (obj.status == 1) {
                        $("#country_code_txt").text('+' + obj.country_code);
                    } else {
                        $("#country_code_txt").text('');
                    }
                }
            });
        }
    }
}
</script>
