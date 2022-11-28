</div>
</div>
<!-- END CONTENT -->
</div>
<!-- /.modal -->
<div class="modal fade" id="coupon" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/merchant/coupon/save" id="couponForm" method="post" class="form-horizontal form-row-sepe">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Add new coupon</h4>
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
                                                    <label class="control-label col-md-4">Coupon code <span
                                                            class="required">*
                                                        </span></label>
                                                    <div class="col-md-4">
                                                        <input type="text" required name="coupon_code" id="coupon_code"
                                                            class="form-control" value="{$post.coupon_code}">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Coupon description <span
                                                            class="required">*
                                                        </span></label>
                                                    <div class="col-md-6">
                                                        <textarea required name="descreption"
                                                            class="form-control">{$post.descreption}</textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Start date <span
                                                            class="required">*
                                                        </span></label>
                                                    <div class="col-md-4">
                                                        <input class="form-control form-control-inline date-picker"
                                                            type="text" required value="{$post.start_date}"
                                                            name="start_date" autocomplete="off"
                                                            data-date-format="dd M yyyy" placeholder="Start date" />
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">End date <span
                                                            class="required">*
                                                        </span></label>
                                                    <div class="col-md-4">
                                                        <input class="form-control form-control-inline date-picker"
                                                            type="text" required value="{$post.end_date}"
                                                            name="end_date" autocomplete="off"
                                                            data-date-format="dd M yyyy" placeholder="End date" />
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Number of coupons<span
                                                            class="required">*
                                                        </span></label>
                                                    <div class="col-md-4">
                                                        <input type="number" min="0" value="0" required name="limit"
                                                            class="form-control" value="{$post.limit}">
                                                    </div>Keep 0 for unlimited
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Coupon offer type</label>
                                                    <div class="col-md-4">
                                                        <select name="is_fixed" class="form-control"
                                                            onchange="isFixed(this.value);"
                                                            data-placeholder="Select...">
                                                            <option value="1">Fixed amount</option>
                                                            <option value="2">Percentage</option>

                                                        </select>
                                                        <span class="help-block">
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="form-group" id="per_div" style="display: none;">
                                                    <label class="control-label col-md-4">Percentage <span
                                                            class="required">*
                                                        </span></label>
                                                    <div class="col-md-4">
                                                        <input type="number" min="0" required id="per" name="percent"
                                                            class="form-control" value="{$post.percent}">
                                                    </div>%
                                                </div>
                                                <div class="form-group" id="fixed_div">
                                                    <label class="control-label col-md-4">Fixed amount <span
                                                            class="required">*
                                                        </span></label>
                                                    <div class="col-md-4">
                                                        <input type="number" min="0" required id="fix"
                                                            name="fixed_amount" class="form-control"
                                                            value="{$post.fixed_amount}">
                                                    </div>
                                                </div>

                                            </div>


                                        </div>
                                    </div>
                                    <!-- End profile details -->




                                </div>
                            </div>
                        </div>


                    </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" onclick="saveCoupon();" id="btnSubmit" class="btn blue" value="Save" />
                    <button type="button" class="btn default" id="closebutton" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade bs-modal-lg in" id="custom" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <form action="" method="post" id="customerForm" class="form-horizontal">
        {CSRF::create('invoice_customer')}
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" id="closebutton1" data-dismiss="modal"
                        aria-hidden="true"></button>
                    <h4 class="modal-title">New Customer</h4>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div id="warning" style="display:none;" class="portlet ">
                            <div class="portlet-title">
                                <div class="caption">
                                    Duplicate customer entry?
                                </div>

                            </div>
                            <div class="portlet-body">
                                <div class="alert alert-block alert-warning fade in">

                                    <h4 class="alert-heading">Warning!</h4>
                                    <p id="ex_message">
                                        This Email ID, Mobile Number already exists in your customer database. You could
                                        either replace this record or create a new entry with same values.
                                        Alternatively you can edit the records entered from the Customer create screen
                                        below.
                                    </p>
                                    <br>
                                    <br>
                                    <p class="pull-right" style="margin-top: -25px;">
                                        <button type="submit" id="ex_delete" onclick="return deleteCustomer();"
                                            class="btn red btn-sm">
                                            Disable Existing & Add New </button>
                                        <button type="submit" id="ex_add" onclick="return saveCustomer();"
                                            class="btn green btn-sm">
                                            Save As New </button>
                                        <a onclick="return confirmreplace();" class="btn blue btn-sm">
                                            Replace existing customer data</a>
                                        <a href="#basic" id="confirmm" data-toggle="modal">
                                        </a>
                                        <a onclick="document.getElementById('closebutton1').click();"
                                            class="btn default btn-sm"> Cancel </a>
                                    </p>
                                </div>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="td-c">
                                                Customer code
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
                                                Replace?
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="allcusta">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="portlet-body form">

                            <div class="form-body">
                                <!-- Start profile details -->
                                <div class="alert alert-danger display-none">
                                    <button class="close" data-dismiss="alert"></button>
                                    You have some form errors. Please check below.
                                </div>
                                <div class="alert alert-danger" style="display: none;" id="errorshow">
                                    <button class="close"
                                        onclick="document.getElementById('errorshow').style.display = 'none';"></button>
                                    <p id="error_display">Please correct the below errors to complete registration.</p>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Customer code<span class="required">*
                                                </span></label>
                                            <div class="col-md-7">
                                                <input type="text" id="customer_code" name="customer_code"
                                                    {if $merchant_setting.customer_auto_generate==0} required 
                                                    {else}
                                                    readonly value="Auto generate" {/if}class="form-control">
                                                <input type="hidden" id="customer_code" name="auto_generate"
                                                    value="{$merchant_setting.customer_auto_generate}">
                                            </div>
                                            <div class="col-md-3">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-4">Customer name<span class="required">*
                                                </span></label>
                                            <div class="col-md-7">
                                                <input type="text" name="customer_name" required value=""
                                                    class="form-control">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-4">Email<span class="required">
                                                </span></label>
                                            <div class="col-md-7">
                                                <div class="input-group">
                                                    <input type="email" name="email" id="defaultemail" value=""
                                                        class="form-control">
                                                    <span class="input-group-btn">
                                                        <label> <input id="def_email" onchange="defaultcustomeremail();"
                                                                type="checkbox">Default</label>>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Mobile<span class="required">
                                                </span></label>
                                            <div class="col-md-7">
                                                <div class="input-group">
                                                    <input type="text" {$validate.mobile} id="defaultmobile"
                                                        name="mobile" value="{$post.mobile}" class="form-control">
                                                    <span class="input-group-btn">
                                                        <label> <input id="def_mobile"
                                                                onchange="defaultcustomermobile();"
                                                                type="checkbox">Default</label>>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Address<span class="required">
                                                </span></label>
                                            <div class="col-md-7">
                                                <textarea value="" name="address" class="form-control"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">City<span class="required">
                                                </span></label>
                                            <div class="col-md-7">
                                                <input type="text" name="city" value="" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">State<span class="required">
                                                    *</span></label>
                                            <div class="col-md-7">
                                                <select name="state" style="width: 100%" required
                                                    class="form-control select2mepop" data-placeholder="Select...">
                                                    <option value="">Select State</option>
                                                    {foreach from=$state_code item=v}
                                                        <option {if $merchant_state==$v.config_value} selected {/if}
                                                            value="{$v.config_value}">{$v.config_value}</option>
                                                    {/foreach}
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Zipcode<span class="required">
                                                </span></label>
                                            <div class="col-md-7">
                                                <input type="text" name="zipcode" value="" class="form-control">
                                            </div>
                                        </div>
                                    </div>


                                    <div class="loading" id="loader" style="display: none;">Loading&#8230;</div>
                                </div>
                                <!-- End profile details -->
                            </div>

                            <h4 class="modal-title">Custom Fields </h4>

                            <div class="row">
                                <div class="col-md-6">
                                    {foreach from=$column item=v}
                                        {if $v.position=='L'}
                                            <div class="form-group">
                                                <input name="column_id[]" type="hidden" value="{$v.column_id}">
                                                <label class="control-label col-md-4">{$v.column_name}<span
                                                        class="required"></span></label>
                                                <div class="col-md-7">
                                                    {if $v.column_datatype=='textarea'}
                                                        <textarea maxlength="500" class="form-control"
                                                            name="column_value[]"></textarea>
                                                    {elseif $v.column_datatype=='date'}
                                                        <input class="form-control form-control-inline date-picker"
                                                            autocomplete="off" data-date-format="dd M yyyy" name="column_value[]"
                                                        type="text" /> {elseif $v.column_datatype=='number'}
                                                        <input type="number" maxlength="100" class="form-control" value=""
                                                            name="column_value[]">
                                                    {else}
                                                        <input maxlength="100" type="text" class="form-control"
                                                            name="column_value[]">
                                                    {/if}
                                                </div>
                                            </div>
                                        {/if}
                                    {/foreach}
                                </div>


                                <div class="col-md-6">
                                    {foreach from=$column item=v}
                                        {if $v.position=='R'}
                                            <div class="form-group">
                                                <input name="column_id[]" type="hidden" value="{$v.column_id}">
                                                <label class="control-label col-md-4">{$v.column_name}<span
                                                        class="required"></span></label>
                                                <div class="col-md-7">
                                                    {if $v.column_datatype=='textarea'}
                                                        <textarea maxlength="500" class="form-control"
                                                            name="column_value[]"></textarea>
                                                    {elseif $v.column_datatype=='date'}
                                                        <input class="form-control form-control-inline date-picker"
                                                            autocomplete="off" data-date-format="dd M yyyy" name="column_value[]"
                                                        type="text" /> {elseif $v.column_datatype=='number'}
                                                        <input type="number" maxlength="100" class="form-control" value=""
                                                            name="column_value[]">
                                                    {else}
                                                        <input maxlength="100" type="text" class="form-control"
                                                            name="column_value[]">
                                                    {/if}
                                                </div>
                                            </div>
                                        {/if}
                                    {/foreach}
                                </div>
                            </div>


                        </div>


                    </div>
                </div>

                <div class="modal-footer">

                    <input type="hidden" id="template_id" name="template_id" value="{$info.template_id}" />
                    <input type="hidden" id="customer_id_" name="customer_id" value="" />
                    <button type="submit" onclick="return display_warning();" class="btn blue">Save</button>
                    <button type="button" class="btn default" id="closebutton" data-dismiss="modal">Close</button>
                </div>
            </div>
    </form>
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
        width: 80em;
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

    #close {
        float: right;
        display: inline-block;
        padding: 2px 5px;
        background: #ccc;
        cursor: pointer;
    }

    @media (max-width: 767px) {
        .panel-wrap {
            width: 23em;
            position: absolute;
        }
    }

    @media (min-width: 768px) and (max-width: 991px) {
        .panel-wrap {
            width: 47em;
            position: fixed;
            right: 0;
        }
    }

    @media (min-width: 992px) and (max-width: 1199px) {
        .panel-wrap {
            width: 47em;
            position: fixed;
            right: 0;
        }
    }
</style>
<div class="panel-wrap" id="panelWrapId">
    <div class="panel">
        <header class="cd-panel__header">
            <h3 class="page-title">Create Product/Service
                <a href="javascript:;" class="btn btn-sm red" id="close"> <i class="fa fa-times"> </i></a>
            </h3>
            <hr>
        </header>
        <div id="product_error" class="alert alert-danger" style="display: none;">
        </div>
        <div class="portlet light bordered">
            <div class="portlet-body form">
                <form action="/merchant/product/store" method="post" class="form-horizontal form-row-sepe"
                    enctype="multipart/form-data" id="product_create">
                    <input type="hidden" name="_token" value="NUyTAXqe4ZGJFSYEz1eX42SvIM8FbuBAFI1NTq2r">
                    <h4 class="form-section">Product Information</h4>
                    <div class="form-body">
                        <!-- Start profile details -->
                        <div class="row">
                            <div id="print-error-msg" class="alert alert-danger" style="display:none">
                                <button class="close" data-dismiss="alert"></button>
                                <ul></ul>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-5">Type <span class="required">*</span></label>
                                    <div class="col-md-7">
                                        <div class="md-radio-inline">
                                            <div class="md-radio">
                                                <input type="radio" id="radio1" checked="" value="Goods" name="type"
                                                    class="md-radiobtn" onclick="setInputFields(this);">
                                                <label for="radio1">
                                                    <span></span>
                                                    <span class="check"></span>
                                                    <span class="box"></span>
                                                    Product </label>
                                            </div>
                                            <div class="md-radio">
                                                <input type="radio" id="radio2" value="Service" name="type"
                                                    class="md-radiobtn" onclick="setInputFields(this);">
                                                <label for="radio2">
                                                    <span></span>
                                                    <span class="check"></span>
                                                    <span class="box"></span>
                                                    Service </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-5">Product/Service Name <span
                                            class="required">*</span></label>
                                    <div class="col-md-7">
                                        <input type="text" required="" name="product_name" {$validate.name}=""
                                            class="form-control" value="" id="product_name">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-5">HSN/SAC Code </label>
                                    <div class="col-md-7">
                                        <input type="text" maxlength="20" name="sac_code" class="form-control" value="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-5">Unit Type </label>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <select id="unit_type" data-placeholder="Select Unit Type"
                                                name="unit_type_id" class="form-control select2me">
                                                <option value="0">Select Unit Type</option>
                                            </select>
                                            <span class="input-group-btn">
                                                <a data-toggle="modal" href="#createUnitType"
                                                    class="btn btn-icon-only green"><i class="fa fa-plus"> </i></a>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-5">Description </label>
                                    <div class="col-md-7">
                                        <textarea name="description" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-5">SKU </label>
                                    <div class="col-md-7">
                                        <input type="text" name="sku" class="form-control" placeholder="SKU" value="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-5">GST applicable </label>
                                    <div class="col-md-7">
                                        <select id="tax" data-placeholder="Select GST" name="gst_percent"
                                            class="form-control">
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-5">Select Category </label>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <select id="category" data-placeholder="Select Category" name="category_id"
                                                class="form-control select2me ">
                                                <option value="">Select Category</option>
                                                <option value="1">Prduct Category</option>
                                                <option value="2">Pro sub category</option>
                                                <option value="3">Prduct Category3</option>
                                                <option value="4">Pro sub category3</option>
                                            </select>
                                            <span class="input-group-btn">
                                                <a data-toggle="modal" href="#master" class="btn btn-icon-only green"><i
                                                        class="fa fa-plus"> </i></a>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-5">Select Vendor </label>
                                    <div class="col-md-7">
                                        <select id="vendor" data-placeholder="Select Vendor" name="vendor_id"
                                            class="form-control">
                                            <option value="">Select Vendor</option>
                                            <option value="2">Paresh Patil</option>

                                        </select>
                                    </div>
                                </div>
                                <div class="form-group" id="product_img_div">
                                    <label class="control-label col-md-5">Product Image </label>
                                    <div class="col-md-7">
                                        <input type="hidden" name="product_image" class="form-control" id="uppy_file">
                                        <div id="drag-drop-area" name="image"></div>
                                        <span id="error" class="text-danger"><span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="form-section mb-2">Purchase Information</h4>
                                <div class="form-group">
                                    <label class="control-label col-md-5">Cost Price </label>
                                    <div class="col-md-7">
                                        <input type="number" name="purchase_cost" class="form-control" value="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-5">Description </label>
                                    <div class="col-md-7">
                                        <textarea name="purchase_info" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h4 class="form-section mb-2">Sale Information</h4>
                                <div class="form-group">
                                    <label class="control-label col-md-5">Sale Price <span class="required">*
                                        </span></label>
                                    <div class="col-md-7">
                                        <input type="number" name="price" required="" class="form-control" value=""
                                            id="sale_price">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-5">Description </label>
                                    <div class="col-md-7">
                                        <textarea name="sale_info" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group" id="stock_keep_div">
                                    <label class="control-label col-md-5">Add Stock Keeping information</label>
                                    <div class="col-md-7">
                                        <input type="hidden" name="has_stock_keeping" value="0" id="has_stock_keeping">
                                        <div class="bootstrap-switch bootstrap-switch-wrapper bootstrap-switch-off bootstrap-switch-id-has_inventory bootstrap-switch-animate"
                                            style="width: 106.039px;">
                                            <div class="bootstrap-switch-container"
                                                style="width: 156px; margin-left: -52px;"><span
                                                    class="bootstrap-switch-handle-on bootstrap-switch-primary"
                                                    style="width: 52px;">&nbsp;On&nbsp;&nbsp;</span><span
                                                    class="bootstrap-switch-label"
                                                    style="width: 52px;">&nbsp;</span><span
                                                    class="bootstrap-switch-handle-off bootstrap-switch-default"
                                                    style="width: 52px;">&nbsp;Off&nbsp;</span><input type="checkbox"
                                                    name="is_stock_keeping" id="has_inventory"
                                                    onchange="stockDivEnable(this.checked)" value="1"
                                                    class="make-switch" data-on-text="&nbsp;On&nbsp;&nbsp;"
                                                    data-off-text="&nbsp;Off&nbsp;">
                                            </div>
                                        </div>
                                        <br>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="stock_details" style="display:none">
                            <h4 class="form-section mb-2">Stock Keeping Units</h4>
                            <div class="row">
                                <div class="col-md-6">

                                    <div class="form-group">
                                        <label class="control-label col-md-5">Available Stock <span class="required">*
                                            </span></label>
                                        <div class="col-md-7">
                                            <input type="number" name="available_stock" class="form-control"
                                                id="available_stock" value="">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-5">Minimum Stock </label>
                                        <div class="col-md-7">
                                            <input type="number" name="minimum_stock" class="form-control" value="">
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-offset-10">
                                <input type="hidden" name="redirect" value="/merchant/expense/create" id="ajax">
                                <input type="submit" value="Save" class="btn blue" onclick="return saveProduct();">
                                <a href="/merchant/expense/create" class="btn btn-link">Cancel</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://releases.transloadit.com/uppy/v1.28.1/uppy.min.js"></script>
















<div class="modal  fade" id="new_product" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Add new Product</h4>
            </div>
            <form action="/merchant/product/productsave" method="post" id="product_frm"
                class="form-horizontal form-row-sepe">
                {CSRF::create('product_save')}
                <div class="form-body">
                    <!-- Start profile details -->
                    <div class="row">
                        <div class="col-md-12">
                            <br>
                            <div id="product_error" class="alert alert-danger" style="display: none;">

                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Type <span class="required">*
                                    </span></label>
                                <div class="col-md-4">

                                    <div class="md-radio-inline">
                                        <div class="md-radio">
                                            <input type="radio" id="radio1" checked="" value="Goods" name="type"
                                                class="md-radiobtn">
                                            <label for="radio1">
                                                <span></span>
                                                <span class="check"></span>
                                                <span class="box"></span>
                                                Goods </label>
                                        </div>
                                        <div class="md-radio">
                                            <input type="radio" id="radio2" value="Service" name="type"
                                                class="md-radiobtn">
                                            <label for="radio2">
                                                <span></span>
                                                <span class="check"></span>
                                                <span class="box"></span>
                                                Service </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Product name <span class="required">*
                                    </span></label>
                                <div class="col-md-4">
                                    <input type="text" required name="product_name" {$validate.name}
                                        class="form-control" value="{$post.product_name}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-4">Price <span class="required">
                                    </span></label>
                                <div class="col-md-4">
                                    <input type="number" step="0.01" name="price" class="form-control"
                                        value="{$post.price}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">HSN/SAC code <span class="required">
                                    </span></label>
                                <div class="col-md-4">
                                    <input type="text" name="sac_code" maxlength="20" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Unit type <span class="required">
                                    </span></label>
                                <div class="col-md-4">
                                    <input type="text" maxlength="45" name="unit_type" class="form-control"
                                        value="{$post.unit_type}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-4">Description <span class="required">
                                    </span></label>
                                <div class="col-md-4">
                                    <textarea name="description" class="form-control">{$post.description}</textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-4">GST applicable <span class="required">
                                    </span></label>
                                <div class="col-md-4">
                                    <select id="tax" data-placeholder="Select GST" name="gst_percent"
                                        class="form-control">
                                        {foreach from=$gst_tax item=v}
                                            <option value="{$v}">{$v}%</option>
                                        {/foreach}
                                    </select>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
                <!-- End profile details -->



            </form>
            <div class="modal-footer">
                <button type="button" id="pclosebutton" class="btn default" data-dismiss="modal">Close</button>
                <input type="submit" onclick="return saveProduct();" value="Save" class="btn blue">
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal  fade" id="new_addtax" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Add new Tax</h4>
            </div>
            <form action="/merchant/tax/taxsave" method="post" id="tax_frm" class="form-horizontal form-row-sepe">
                {CSRF::create('tax_save')}
                <div class="form-body">
                    <!-- Start profile details -->
                    <div class="row">
                        <div class="col-md-12">
                            <br>
                            <div id="tax_error" class="alert alert-danger" style="display: none;">

                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-4">Tax name <span class="required">*
                                    </span></label>
                                <div class="col-md-4">
                                    <input type="text" required name="tax_name" maxlength="100" class="form-control"
                                        value="{$post.tax_name}">
                                </div>
                            </div>



                            <div class="form-group">
                                <label class="control-label col-md-4 ">Tax Type <span class="required">*
                                    </span></label>
                                <div class="col-md-4">
                                    <select name="tax_type" onchange="changeTaxtype(this.value);" required
                                        class="form-control" data-placeholder="Select...">
                                        <option value="">Select Type</option>
                                        {foreach from=$tax_type item=v}
                                            <option value="{$v.config_key}">{$v.config_value}</option>
                                        {/foreach}
                                    </select>
                                </div>
                            </div>
                            <div class="form-group" id="tpercent">
                                <label class="control-label col-md-4">Percentage <span class="required">
                                    </span></label>
                                <div class="col-md-4">
                                    <input type="number" step="0.01" name="percentage" class="form-control"
                                        value="{$post.percentage}">
                                </div>
                            </div>
                            <div class="form-group display-none" id="tamount">
                                <label class="control-label col-md-4">Amount <span class="required">
                                    </span></label>
                                <div class="col-md-4">
                                    <input type="number" step="0.01" name="tax_amount" class="form-control"
                                        value="{$post.tax_amount}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-4">Description <span class="required">
                                    </span></label>
                                <div class="col-md-4">
                                    <textarea name="description" class="form-control">{$post.description}</textarea>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
                <!-- End profile details -->



            </form>
            <div class="modal-footer">
                <button type="button" id="tclosebutton" class="btn default" data-dismiss="modal">Close</button>
                <input type="submit" onclick="return saveTax();" value="Save" class="btn blue">
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal  fade" id="new_covering" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Add new covering note</h4>
            </div>
            <form action="/merchant/coveringnote/save" method="post" id="covering_frm"
                class="form-horizontal form-row-sepe">
                {CSRF::create('coveringnote_save')}
                <div class="form-body">
                    <!-- Start profile details -->
                    <div class="row">
                        <div class="col-md-12">
                            <br>
                            <div id="covering_error" class="alert alert-danger" style="display: none;">

                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Template name <span class="required">*
                                    </span></label>
                                <div class="col-md-4">
                                    <input type="text" required name="template_name" {$validate.name}
                                        class="form-control" value="{$post.template_name}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">Mail body <span class="required">*
                                    </span></label>
                                <div class="col-md-7">
                                    <textarea required name="body" id="summernote" class="form-control description">
                                                <div style="text-align: center;"><span style="font-family:open sans,helvetica neue,helvetica,arial,sans-serif"><img data-file-id="890997" src="{$logo}"  style="max-height: 200px; margin: 0px;"></span></div><div><span style="font-family:open sans,helvetica neue,helvetica,arial,sans-serif">Dear %CUSTOMER_NAME%,<br><br>Please find attached our invoice for the services provided to your company.<br><br>It has been a pleasure serving you. We look forward to working with you again.<br><br>If you have any questions about your invoice, please contact us by replying to this email.<br><br>Thanking You,<br>With best regards,<br>%COMPANY_NAME%</span></div>
                                    </textarea>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Mail Subject <span class="required">*
                                    </span></label>
                                <div class="col-md-6">
                                    <input type="text" required maxlength="100" name="subject" class="form-control"
                                        value="Payment request from %COMPANY_NAME%">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Invoice label <span class="required">*
                                    </span></label>
                                <div class="col-md-4">
                                    <input type="text" required maxlength="20" name="invoice_label" class="form-control"
                                        value="View Invoice">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Attach PDF <span class="required">*
                                    </span></label>
                                <div class="col-md-4">
                                    <input type="checkbox" checked="" name="pdf_enable" value="1" class="make-switch"
                                        data-on-text="&nbsp;Enabled&nbsp;&nbsp;" data-off-text="&nbsp;Disabled&nbsp;">
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
                <!-- End profile details -->



            </form>
            <div class="modal-footer">
                <button type="button" id="cclosebutton" class="btn default" data-dismiss="modal">Close</button>
                <input type="submit" onclick="return saveCovering('');" value="Save" class="btn blue">
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal  fade" id="con_coveri" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Confirm and Send Invoice</h4>
            </div>
            <form action="/merchant/coveringnote/save" method="post" id="confirm_covering_frm"
                class="form-horizontal form-row-sepe">
                {CSRF::create('coveringnote_save')}
                <div class="form-body">
                    <!-- Start profile details -->
                    <div class="row">
                        <div class="col-md-12">
                            <br>
                            <div id="conf_covering_error" class="alert alert-danger" style="display: none;">
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">Mail body <span class="required">*
                                    </span></label>
                                <div class="col-md-7">
                                    <textarea required name="body" id="confsummernote" class="form-control description">
                                                <div style="text-align: center;"><span style="font-family:open sans,helvetica neue,helvetica,arial,sans-serif"><img data-file-id="890997" src="{$logo}"  style="max-height: 200px; margin: 0px;"></span></div><div><span style="font-family:open sans,helvetica neue,helvetica,arial,sans-serif">Dear %CUSTOMER_NAME%,<br><br>Please find attached our invoice for the services provided to your company.<br><br>It has been a pleasure serving you. We look forward to working with you again.<br><br>If you have any questions about your invoice, please contact us by replying to this email.<br><br>Thanking You<br><br>With best regards,<br>%COMPANY_NAME%</span></div>
                                    </textarea>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Mail Subject <span class="required">*
                                    </span></label>
                                <div class="col-md-6">
                                    <input type="text" id="conf_mail_sub" required maxlength="100" name="subject"
                                        class="form-control" value="Payment request from %COMPANY_NAME%">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Invoice label <span class="required">*
                                    </span></label>
                                <div class="col-md-4">
                                    <input type="text" id="conf_inv_label" required maxlength="20" name="invoice_label"
                                        class="form-control" value="View Invoice">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Attach PDF <span class="required">*
                                    </span></label>
                                <div class="col-md-4">
                                    <input type="checkbox" checked="" name="pdf_enable" value="1" class="make-switch"
                                        data-on-text="&nbsp;Enabled&nbsp;&nbsp;" data-off-text="&nbsp;Disabled&nbsp;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End profile details -->
                <input type="hidden" required id="conf_template_name" name="template_name" {$validate.name} value="">
                <input type="hidden" name="random_covering" value="1">
            </form>
            <div class="modal-footer">
                <button type="button" id="ccclosebutton" class="btn default" data-dismiss="modal">Close</button>

                <input type="submit" onclick="return confirmCovering('');" value="Confirm & Send" class="btn blue">
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal  fade" id="autocollect_plan" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Auto collect plan</h4>
            </div>
            <form action="/merchant/autocollect/plan/save" method="post" id="autocollect_frm"
                class="form-horizontal form-row-sepe">
                <div class="form-body">
                    <div id="autocollect_error" class="alert alert-danger" style="display: none;">

                    </div>
                    <br>
                    <!-- Start profile details -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-danger display-none">
                                <button class="close" data-dismiss="alert"></button>
                                You have some form errors. Please check below.
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-4">Plan Name <span class="required">*
                                    </span></label>
                                <div class="col-md-6">
                                    <input type="text" required id="name" maxlength="100" {{$validate->name}}
                                        name="name" class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-4">Amount<span class="required">*
                                    </span></label>
                                <div class="col-md-6">
                                    <input type="text" required maxlength="25" name="amount" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Expire after <span class="required">*
                                    </span></label>
                                <div class="col-md-4">
                                    <input type="number" min="1" required max="24" name="occurrence" value="12"
                                        class="form-control">
                                </div>
                                <label class="control-label col-md-2 align-left">
                                    Months
                                </label>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Description <span class="required">*
                                    </span></label>
                                <div class="col-md-6">
                                    <textarea type="text" id="address" required name="description" maxlength="500"
                                        class="form-control"></textarea>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
                <!-- End profile details -->
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-5 col-md-6">
                            <input type="hidden" name="type" value="PERIODIC">
                            <input type="hidden" name="interval_type" value="month">
                            <input type="hidden" name="intervals" value="1">
                        </div>
                    </div>
                </div>

            </form>
            <div class="modal-footer">
                <input type="submit" id="acsubbutton" onclick="return saveAutocollectPlan();" value="Save"
                    class="btn blue">
                <button type="button" id="acclosebutton" class="btn default" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>



<div class="modal fade" id="basic" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Replace customer</h4>
            </div>
            <div class="modal-body">
                Are you sure you want to replace <span id="totalchecked"></span> customer records with newly entered
                customer data?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn default" data-dismiss="modal">Close</button>
                <a href="" onclick="return updatemultiCustomer();" class="btn blue" data-dismiss="modal">Confirm</a>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>



<script>
    function isFixed(id) {
        if (id == 1) {
            $("#fixed_div").slideDown(200).fadeIn();
            $("#per_div").slideDown(200).fadeOut();
        } else {
            $("#per_div").slideDown(200).fadeIn();
            $("#fixed_div").slideDown(200).fadeOut();
        }
    }
</script>