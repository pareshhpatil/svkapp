
<div class="page-content">
    <div class="row no-margin">

        {if {$isGuest} =='1'}
            <div class="col-md-2"></div>
            <div class="col-md-8" style="text-align: -webkit-center;text-align: -moz-center;">
            {else}
                <div class="col-md-1"></div>
                <div class="col-md-10" style="text-align: -webkit-center;text-align: -moz-center;">
                {/if}
                <br>
                {if $is_valid=='NO' && !isset($is_invoice)}
                    <div class="alert alert-danger">
                        <button type="button" class="close" data-dismiss="alert"></button>
                        <strong>Invalid event!</strong>
                        <div class="media">
                            <p class="media-heading">{$invalid_message}</p>
                        </div>
                    </div>
                {/if}

                {if isset($errortitle)}
                    <div class="alert alert-danger" style="text-align: left;">
                        <button type="button" class="close" data-dismiss="alert"></button>
                        <div class="media">
                            <strong>{$errortitle}</strong> - <p class="media-heading">{$errormessage}</p>
                        </div>
                    </div>
                {/if}

                <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->

                <!-- /.modal -->
                <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
                <!-- BEGIN PAGE CONTENT-->
                <div  style="text-align: left;box-shadow: 1px 10px 10px #888888;">
                    <div class="row">
                        {if $info.banner_path!=''}
                            <div class="col-md-12 fileinput fileinput-new" style="text-align: center;" data-provides="fileinput">
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="fileinput-preview thumbnail banner-container"  data-trigger="fileinput"> 
                                        <img class="img-responsive" style="width: 100%;" src="{if $info.banner_path!=''}/uploads/images/logos/{$info.banner_path}{else}holder.js/100%x100%{/if}">
                                    </div>
                                </div>
                            </div>

                        {/if}


                    </div>
                    <div class="row">
                        <div class="col-md-12 no-padding fileinput fileinput-new " style="width: 100%;" data-provides="fileinput">
                            <div class="fileinput-new thumbnail col-md-12 title">
                                <div class="caption font-blue" style="background-color: #F15F14;background: linear-gradient(#fb8f22, #ec4a0d);margin-left: 10px;margin-right: 10px;">
                                    <span class="caption-subject bold" style="color: #FFFFFF;" > <h2 style="font-weight: 500;">Khar Linking Road Store Launch Offer</h2></span>
                                    <p style="color: #FFFFFF;">Presented by <a href="http://mapledti.com" style="color: #FFFFFF;" target="BLANK">Maple</a></p>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row no-margin no-padding" style="">

                        <div class="col-md-1"></div>
                        <div class="col-md-10 invoice-payment">
                            <form id="event_form" action="/patron/event/prebookpay/{$url}" onsubmit="return seatcalculate();" method="post">


                                <div class="row">
                                    <div class="col-md-2"><strong>Address</strong></div>
                                    <div class="col-md-10"><i class="fa fa-map-marker"></i>&nbsp;&nbsp;&nbsp;Maple Digital Technology International Pvt. Ltd. Ground floor, seasons avenue, Khar Linking road, Mumbai 400052</div>
                                </div>
                                <span class="help-block"></span>

                                {if $info.description!=''}
                                    <div class="row">
                                        <div class="col-md-2"><strong>Description</strong></div>
                                        <div class="col-md-10">
                                            1. Customer can book a maximum of 4 products across MacBooks and iPhones<br>
                                            2. Pre book amount for iPhones and MacBooks are Rs. 5000/- and Rs. 10000/- respectively per device<br>
                                            3. iPhone must be activated in the store itself<br>
                                            4. Please produce payment confirmation receipt and Identity proof to collect the product at the store<br>
                                            5. The discounts are offered as part of an exchange offer only so please get the device you want to exchange to the store<br>
                                        </div>
                                    </div>
                                {/if}

                                {foreach from=$header item=v}
                                    {if $v.value!='' && $v.column_position!=1 &&  $v.column_position!=2}
                                        <div class="row">
                                            <div class="col-md-2"><strong>{$v.column_name}:</strong></div>
                                            <div class="col-md-10">{$v.value}</div>
                                        </div>
                                    {/if}
                                {/foreach}
                                <span class="help-block"></span>
                                <div class="row">

                                    <div class="col-md-2"><strong>Valid upto</strong></div>
                                    <div class="col-md-10"> 
                                        <input value="{$occurence.0.occurence_id}" name="start_date" type="hidden">
                                        <i class="fa fa-calendar"></i>&nbsp;&nbsp;&nbsp;14-Jun-2016 to 17-Jun-2016
                                        <input type="hidden" name="package_id[]">
                                        <input type="hidden" name="seat[]">
                                    </div>
                                </div>
                                <br>

                                <div class="portlet">
                                    <div class="portlet-title" onclick="document.getElementById('iphone').click();" style="cursor: pointer;">
                                        <div class="caption">
                                            iPhone
                                        </div>
                                        <div class="tools">

                                            <a href="javascript:;" class="collapse" id="iphone" data-original-title="" title="">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="portlet-body">
                                        <div class="row">
                                            <div class="col-md-3">

                                                <label class="control-label">Model </label>
                                                <select class="form-control" name="iphonemodel[]" onchange="manageSubcategory(this.value, 1);" id="iphone_model1" data-placeholder="Select...">
                                                    <option value="">Select Model</option>
                                                    {foreach from=$iphonemodel item=k}
                                                        <option value="{$k}">{$k}</option>
                                                    {/foreach}
                                                </select>
                                                <span class="help-block"></span>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="control-label">Storage </label>
                                                <div id="istorage_1">
                                                    <select class="form-control" name="iphonestorage[]" id="iphone_storage1" data-placeholder="Select...">
                                                        <option value="">Select Storage</option>
                                                        {foreach from=$iphonememory item=k}
                                                            <option value="{$k}">{$k}</option>
                                                        {/foreach}
                                                    </select>
                                                    <span class="help-block"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="control-label">Colour </label>
                                                <div id="icolour_1">
                                                    <select class="form-control" name="iphonecolour[]" id="iphone_colour1" data-placeholder="Select...">
                                                        <option value="">Select Colour</option>
                                                        {foreach from=$iphonecolor item=k}
                                                            <option value="{$k}">{$k}</option>
                                                        {/foreach}
                                                    </select>
                                                    <span class="help-block"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="control-label">Quantity </label>
                                                <select name="iphoneseat[]" class="form-control" id="iphone_qty1" onchange="return calculateprebook(this.id);" data-placeholder="Select...">
                                                    <option value="0">0</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                </select>
                                                <span class="help-block"></span>
                                            </div>
                                            <div class="col-md-1">
                                                <a href="javascript:;" >
                                                    <div class="btn blue btn-xs" id="iphone_btn" onclick="addButton('iphone').className = '';"><i class="fa fa-plus"></i></div>
                                                </a>
                                                <span class="help-block"></span>
                                            </div>
                                        </div>

                                        <div class="row"> 
                                            <div id="iphone_row" class="hidden"> 
                                                <div class="col-md-3">
                                                    <label class="control-label">Model </label>
                                                    <select class="form-control" name="iphonemodel[]" onchange="manageSubcategory(this.value, 2);" id="iphone_model2" data-placeholder="Select...">
                                                        <option value="">Select Model</option>
                                                        {foreach from=$iphonemodel item=k}
                                                            <option value="{$k}">{$k}</option>
                                                        {/foreach}
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="control-label">Storage </label>
                                                    <div id="istorage_2">
                                                        <select class="form-control" name="iphonestorage[]" id="iphone_storage2" data-placeholder="Select...">
                                                            <option value="">Select Storage</option>
                                                            {foreach from=$iphonememory item=k}
                                                                <option value="{$k}">{$k}</option>
                                                            {/foreach}
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="control-label">Colour </label>
                                                    <div id="icolour_2">
                                                        <select class="form-control" name="iphonecolour[]" id="iphone_colour2" data-placeholder="Select...">
                                                            <option value="">Select Colour</option>
                                                            {foreach from=$iphonecolor item=k}
                                                                <option value="{$k}">{$k}</option>
                                                            {/foreach}
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="control-label">Quantity </label>
                                                    <select name="iphoneseat[]" class="form-control" id="iphone_qty2" onchange="return calculateprebook(this.id);" data-placeholder="Select...">
                                                        <option value="0">0</option>
                                                        <option value="1">1</option>
                                                        <option value="2">2</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-1">
                                                    <div class="btn red btn-xs" onclick="closeButton('iphone', 'iphone_qty2');"><i class="fa fa-close"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Imac start-->
                                <div class="portlet">
                                    <div class="portlet-title" onclick="document.getElementById('imac').click();" style="cursor: pointer;">
                                        <div class="caption">
                                            iMac
                                        </div>
                                        <div class="tools">
                                            <a href="javascript:;" class="expand" id="imac" data-original-title="" title="">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="portlet-body" style="display: none;">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label class="control-label">Model </label>
                                                <select class="form-control" id="makbookpromodel" onchange="getSubCategory('pack01imac', this.value, 'imac_td1', 'imac_qty1');" data-placeholder="Select...">
                                                    <option value="">Select Model</option>
                                                    {foreach from=$gadget.imac item=v key=k}
                                                        <option value="{$v}">{$v}</option>
                                                    {/foreach}
                                                </select>
                                                <span class="help-block"></span>
                                            </div>
                                            <div class="col-md-5">
                                                <label class="control-label">Specification </label>
                                                <div id="imac_td1">
                                                    <select class="form-control" name="package_id[]" id="pack01" data-placeholder="Select...">
                                                        <option value="">Select Specification</option>
                                                    </select>
                                                    <span class="help-block"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="control-label">Quantity </label>
                                                <select name="seat[]" class="form-control" id="imac_qty1" onchange="return calculateprebook(this.id);" data-placeholder="Select...">
                                                    <option value="0">0</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                </select>
                                                <span class="help-block"></span>
                                            </div>
                                            <div class="col-md-1">
                                                <a href="javascript:;" >
                                                    <div class="btn blue btn-xs" id="imac_btn" onclick="addButton('imac').className = '';"><i class="fa fa-plus"></i></div>
                                                </a>
                                                <span class="help-block"></span>
                                            </div>
                                        </div>



                                        <div class="row"> 
                                            <div id="imac_row" class="hidden"> 
                                                <div class="col-md-4">

                                                    <label class="control-label">Model </label>
                                                    <select class="form-control" id="makbookpromodel" onchange="getSubCategory('pack02imac', this.value, 'imac_td2', 'imac_qty2');" data-placeholder="Select...">
                                                        <option value="">Select Model</option>
                                                        {foreach from=$gadget.imac item=v key=k}
                                                            <option value="{$v}">{$v}</option>
                                                        {/foreach}
                                                    </select>
                                                </div>
                                                <div class="col-md-5">
                                                    <label class="control-label">Specification </label>
                                                    <div id="imac_td2">
                                                        <select class="form-control" name="package_id[]" id="pack02" data-placeholder="Select...">
                                                            <option value="">Select Specification</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="control-label">Quantity </label>
                                                    <select name="seat[]" class="form-control" id="imac_qty2" onchange="return calculateprebook(this.id);" data-placeholder="Select...">
                                                        <option value="0">0</option>
                                                        <option value="1">1</option>
                                                        <option value="2">2</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-1">
                                                    <div class="btn red btn-xs" onclick="closeButton('imac', 'imac_qty2');"><i class="fa fa-close"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--Imac End-->                   



                                    <!-- mac book Start-->
                                    <div class="portlet">
                                        <div class="portlet-title" onclick="document.getElementById('macbook').click();" style="cursor: pointer;">
                                            <div class="caption">
                                                MacBook
                                            </div>
                                            <div class="tools">

                                                <a href="javascript:;" class="expand" id="macbook" data-original-title="" title="">
                                                </a>
                                            </div>
                                        </div>
                                        <div class="portlet-body" style="display: none;">
                                            <div class="row">
                                                <div class="col-md-4">

                                                    <label class="control-label">Model </label>
                                                    <select class="form-control" id="makbookpromodel" onchange="getSubCategory('pack03macbook', this.value, 'macbook_td1', 'macbook_qty1');" data-placeholder="Select...">
                                                        <option value="">Select Model</option>
                                                        {foreach from=$gadget.macbook item=v key=k}
                                                            <option value="{$v}">{$v}</option>
                                                        {/foreach}
                                                    </select>
                                                    <span class="help-block"></span>
                                                </div>
                                                <div class="col-md-5">
                                                    <label class="control-label">Colour </label>
                                                    <div id="macbook_td1">
                                                        <select class="form-control" name="package_id[]" id="pack03" data-placeholder="Select...">
                                                            <option value="">Select Colour</option>
                                                        </select>
                                                        <span class="help-block"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="control-label">Quantity </label>
                                                    <select name="seat[]" class="form-control" id="macbook_qty1" onchange="return calculateprebook(this.id);" data-placeholder="Select...">
                                                        <option value="0">0</option>
                                                        <option value="1">1</option>
                                                        <option value="2">2</option>
                                                    </select>
                                                    <span class="help-block"></span>
                                                </div>
                                                <div class="col-md-1">
                                                    <a href="javascript:;" >
                                                        <div class="btn blue btn-xs" id="macbook_btn" onclick="addButton('macbook').className = '';"><i class="fa fa-plus"></i></div>
                                                    </a>
                                                    <span class="help-block"></span>
                                                </div>
                                            </div>

                                            <div class="row"> 
                                                <div id="macbook_row" class="hidden"> 
                                                    <div class="col-md-4">

                                                        <label class="control-label">Model </label>
                                                        <select class="form-control" id="makbookpromodel" onchange="getSubCategory('pack04macbook', this.value, 'macbook_td2', 'macbook_qty2');" data-placeholder="Select...">
                                                            <option value="">Select Model</option>
                                                            {foreach from=$gadget.macbook item=v key=k}
                                                                <option value="{$v}">{$v}</option>
                                                            {/foreach}
                                                        </select>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <label class="control-label">Colour </label>
                                                        <div id="macbook_td2">
                                                            <select class="form-control" name="package_id[]" id="pack04" data-placeholder="Select...">
                                                                <option value="">Select Colour</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label class="control-label">Quantity </label>
                                                        <select name="seat[]" class="form-control"  id="macbook_qty2" onchange="return calculateprebook(this.id);" data-placeholder="Select...">
                                                            <option value="0">0</option>
                                                            <option value="1">1</option>
                                                            <option value="2">2</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <div class="btn red btn-xs" onclick="closeButton('macbook', 'macbook_qty2');"><i class="fa fa-close"></i></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <!--Mac book End--> 

                                    <!--Mac book pro start--> 
                                    <div class="portlet">
                                        <div class="portlet-title" onclick="document.getElementById('macbookpro').click();" style="cursor: pointer;">
                                            <div class="caption">
                                                MacBook Pro
                                            </div>
                                            <div class="tools">

                                                <a href="javascript:;" class="expand" id="macbookpro" data-original-title="" title="">
                                                </a>
                                            </div>
                                        </div>
                                        <div class="portlet-body" style="display: none;">
                                            <div class="row">
                                                <div class="col-md-4">

                                                    <label class="control-label">Model </label>
                                                    <select class="form-control" id="makbookpromodel" onchange="getSubCategory('pack05macbookpro', this.value, 'macbookpro_td1', 'macbookpro_qty1');" data-placeholder="Select...">
                                                        <option value="">Select Model</option>
                                                        {foreach from=$gadget.macbookpro item=v key=k}
                                                            <option value="{$v}">{$v}</option>
                                                        {/foreach}
                                                    </select>
                                                    <span class="help-block"></span>
                                                </div>
                                                <div class="col-md-5">
                                                    <label class="control-label">Specification </label>
                                                    <div id="macbookpro_td1">
                                                        <select class="form-control" name="package_id[]" id="pack05" data-placeholder="Select...">
                                                            <option value="">Select Specification</option>
                                                        </select>
                                                        <span class="help-block"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="control-label">Quantity </label>
                                                    <select name="seat[]" class="form-control" id="macbookpro_qty1" onchange="return calculateprebook(this.id);" data-placeholder="Select...">
                                                        <option value="0">0</option>
                                                        <option value="1">1</option>
                                                        <option value="2">2</option>
                                                    </select>
                                                    <span class="help-block"></span>
                                                </div>
                                                <div class="col-md-1">
                                                    <a href="javascript:;" >
                                                        <div class="btn blue btn-xs" id="macbookpro_btn" onclick="addButton('macbookpro').className = '';"><i class="fa fa-plus"></i></div>
                                                    </a>
                                                    <span class="help-block"></span>
                                                </div>
                                            </div>

                                            <div class="row"> 
                                                <div id="macbookpro_row" class="hidden"> 
                                                    <div class="col-md-4">

                                                        <label class="control-label">Model </label>
                                                        <select class="form-control" id="makbookpromodel" onchange="getSubCategory('pack06macbookpro', this.value, 'macbookpro_td2', 'macbookpro_qty2');" data-placeholder="Select...">
                                                            <option value="">Select Model</option>
                                                            {foreach from=$gadget.macbookpro item=v key=k}
                                                                <option value="{$v}">{$v}</option>
                                                            {/foreach}
                                                        </select>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <label class="control-label">Specification </label>
                                                        <div id="macbookpro_td2">
                                                            <select class="form-control" name="package_id[]" id="pack06" data-placeholder="Select...">
                                                                <option value="">Select Specification</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label class="control-label">Quantity </label>
                                                        <select name="seat[]" class="form-control" id="macbookpro_qty2" onchange="return calculateprebook(this.id);" data-placeholder="Select...">
                                                            <option value="0">0</option>
                                                            <option value="1">1</option>
                                                            <option value="2">2</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <div class="btn red btn-xs" onclick="closeButton('macbookpro', 'macbookpro_qty2');"><i class="fa fa-close"></i></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- macbook pro End-->


                                    <!-- mac pro Start-->
                                    <div class="portlet">
                                        <div class="portlet-title" onclick="document.getElementById('macpro').click();" style="cursor: pointer;">
                                            <div class="caption">
                                                Mac Pro
                                            </div>
                                            <div class="tools">

                                                <a href="javascript:;" class="expand" id="macpro" data-original-title="" title="">
                                                </a>
                                            </div>
                                        </div>
                                        <div class="portlet-body" style="display: none;">
                                            <div class="row">
                                                <div class="col-md-3">

                                                    <label class="control-label">Model </label>
                                                    <h4>Mac Pro</h4>
                                                    <span class="help-block"></span>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="control-label">Specification </label>
                                                    <div>
                                                        <select class="form-control" name="package_id[]" id="pack07" data-placeholder="Select...">
                                                            <option value="">Select Specification</option>
                                                            {foreach from=$gadget.macpro item=v}
                                                                <option value="{$v.id}">{$v.val}</option>
                                                            {/foreach}
                                                        </select>
                                                        <span class="help-block"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="control-label">Quantity </label>
                                                    <select name="seat[]" class="form-control" id="macpro_qty1" onchange="return calculateprebook(this.id);" data-placeholder="Select...">
                                                        <option value="0">0</option>
                                                        <option value="1">1</option>
                                                        <option value="2">2</option>
                                                    </select>
                                                    <span class="help-block"></span>
                                                </div>
                                                <div class="col-md-1">
                                                    <a href="javascript:;" >
                                                        <div class="btn blue btn-xs" id="macpro_btn" onclick="addButton('macpro').className = '';"><i class="fa fa-plus"></i></div>
                                                    </a>
                                                    <span class="help-block"></span>
                                                </div>
                                            </div>

                                            <div class="row"> 
                                                <div id="macpro_row" class="hidden"> 
                                                    <div class="col-md-3">

                                                        <label class="control-label">Model </label>
                                                        <h4>Mac Pro</h4>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="control-label">Specification </label>
                                                        <div >
                                                            <select class="form-control" name="package_id[]" id="pack08" data-placeholder="Select...">
                                                                <option value="">Select Specification</option>
                                                                {foreach from=$gadget.macpro item=v key=k}
                                                                    <option value="{$v.id}">{$v.val}</option>
                                                                {/foreach}
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label class="control-label">Quantity </label>
                                                        <select name="seat[]" class="form-control" id="macpro_qty2" onchange="return calculateprebook(this.id);" data-placeholder="Select...">
                                                            <option value="0">0</option>
                                                            <option value="1">1</option>
                                                            <option value="2">2</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <div class="btn red btn-xs" onclick="closeButton('macpro', 'macpro_qty2');"><i class="fa fa-close"></i></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--Mac pro End--> 



                                    <!-- mac book air Start-->
                                    <div class="portlet">
                                        <div class="portlet-title" onclick="document.getElementById('macbookair').click();" style="cursor: pointer;">
                                            <div class="caption">
                                                MacBook Air
                                            </div>
                                            <div class="tools">

                                                <a href="javascript:;" class="expand" id="macbookair" data-original-title="" title="">
                                                </a>
                                            </div>
                                        </div>
                                        <div class="portlet-body" style="display: none;">
                                            <div class="row">
                                                <div class="col-md-3">

                                                    <label class="control-label">Model </label>
                                                    <h4>MacBook Air</h4>
                                                    <span class="help-block"></span>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="control-label">Specification </label>
                                                    <div>
                                                        <select class="form-control" name="package_id[]" id="pack09" data-placeholder="Select...">
                                                            <option value="">Select Specification</option>
                                                            {foreach from=$gadget.macbookair item=v key=k}
                                                                <option value="{$v.id}">{$v.val}</option>
                                                            {/foreach}
                                                        </select>
                                                        <span class="help-block"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="control-label">Quantity </label>
                                                    <select name="seat[]" class="form-control" id="macbookair_qty1" onchange="return calculateprebook(this.id);" data-placeholder="Select...">
                                                        <option value="0">0</option>
                                                        <option value="1">1</option>
                                                        <option value="2">2</option>
                                                    </select>
                                                    <span class="help-block"></span>
                                                </div>
                                                <div class="col-md-1">
                                                    <a href="javascript:;" >
                                                        <div class="btn blue btn-xs" id="macbookair_btn" onclick="addButton('macbookair').className = '';"><i class="fa fa-plus"></i></div>
                                                    </a>
                                                    <span class="help-block"></span>
                                                </div>
                                            </div>

                                            <div class="row"> 
                                                <div id="macbookair_row" class="hidden"> 
                                                    <div class="col-md-3">

                                                        <label class="control-label">Model </label>
                                                        <<h4>MacBook Air</h4>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="control-label">Specification </label>
                                                        <div >
                                                            <select class="form-control" name="package_id[]" id="pack10" data-placeholder="Select...">
                                                                <option value="">Select Specification</option>
                                                                {foreach from=$gadget.macbookair item=v key=k}
                                                                    <option value="{$v.id}">{$v.val}</option>
                                                                {/foreach}
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label class="control-label">Quantity </label>
                                                        <select name="seat[]" class="form-control" id="macbookair_qty2" onchange="return calculateprebook(this.id);" data-placeholder="Select...">
                                                            <option value="0">0</option>
                                                            <option value="1">1</option>
                                                            <option value="2">2</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <div class="btn red btn-xs" onclick="closeButton('macbookair', 'macbookair_qty2');"><i class="fa fa-close"></i></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--Mac book air End--> 







                                    <table class="table table-striped table-hover">

                                        <tbody>

                                            <tr> <td class="col-md-7">
                                                    Grand total:
                                                </td>
                                                <td class="hidden-480 col-md-3">

                                                </td>
                                                <td class="hidden-480 col-md-2">
                                                    <input type="hidden" min="1"  name="grand_total"  id="grand_total" readonly="" value="" class="form-control col-md-2 displayonly" >
                                                    <label id="display_grand_total"></label>
                                                </td>

                                            </tr>
                                        </tbody>
                                    </table>
                                    {if $is_valid=='YES'}
                                        <div class="col-md-9">

                                        </div>
                                        <div class="col-md-3 ">
                                            {if {$merchant_type} != '1' &&  {$is_valid}=='YES'}
                                                <div class="row center" >
                                                    <input type="submit" class="btn blue hidden-print margin-bottom-5 col-md-12" value="Book now">
                                                {else}
                                                    <!-- <p class="btn btn-xs red">Event unavailable</p> -->
                                                {/if}
                                            </div>
                                        </div>
                                        <br>
                                    {else}
                                        <p class="btn btn-xs red pull-right">Book now option not available</p><br>
                                    {/if}
                                    </form>
                                    <br>
                                    <br>
                                    <br>
                                </div>


                        </div>
                        </div>

                    </div>
                    <br>
                    <!-- END PAGE CONTENT-->
                </div>
                <div class="col-md-1"></div>
            </div>
        </div>
    </div>
    <div class="modal fade bs-modal-lg" id="guestpay" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" id="closeguest" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <div class="portlet ">
                        <div class="portlet-title">
                            <div class="caption">
                                Guest payment
                            </div>

                        </div>
                        <div class="portlet-body">
                            <div class="row">

                                <div class="col-md-6">
                                    <h4>Book as guest</h4>
                                    <form class="form-horizontal" id="guestlogin" action="/patron/event/preebookpay/{$url}" method="post" role="form">
                                        {if $is_flexible==0}
                                            <div class="form-group">
                                                <label for="inputEmail1" class="col-md-3 control-label">Grand total:</label>
                                                <div class="col-md-7">
                                                    <b><h3 id="booking_amount"><i class="fa fa-inr fa-large"></i> {$info.price} /-</h3></b>
                                                </div>
                                            </div>
                                        {/if}
                                        <div class="form-group">
                                            <label for="inputEmail1" class="col-md-3 control-label"></label>
                                            <div class="col-md-7">
                                                <button type="button" class="btn blue" onclick="document.getElementById('event_form').submit();" id="onlinepay" >Book as guest</button>

                                            </div>
                                        </div>
                                    </form>		
                                </div>
                                <div class="col-md-6">
                                    <h4>Login with Swipez</h4>
                                    <form class="form-horizontal" id="guestlogin" action="/login/failed/{$url}|eonl" method="post" role="form">
                                        <div class="form-group">
                                            <label for="inputEmail1" class="col-md-4 control-label">Email</label>
                                            <div class="col-md-7">
                                                <input type="email" name="username" class="form-control input-sm" id="inputEmail1" placeholder="Email">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputPassword12" class="col-md-4 control-label">Password</label>
                                            <div class="col-md-7">
                                                <input type="password" name="password" AUTOCOMPLETE='OFF' class="form-control input-sm" id="inputPassword12" placeholder="Password">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-4"></div>
                                            <label for="inputPassword12" class="col-md-4 control-label"></label>
                                            <div class="col-md-3">
                                                <button type="submit" class="btn blue">Sign in</button>
                                            </div>
                                        </div>
                                    </form>		
                                </div>
                            </div>
                            <hr>
                            <h4>Benefits of swipez login</h4>
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="col-md-2"> <img src="/assets/admin/layout/img/single.jpg"></div><div class="col-md-10"><label>
                                            Single window for paying 
                                            multiple merchants
                                        </label></div>

                                    <div class="col-md-2"> <img src="/assets/admin/layout/img/instant.jpg"></div><div class="col-md-10"><label>
                                            Instant alerts, due date 
                                            reminders across merchants
                                        </label></div>
                                </div>
                                <div class="col-md-5">
                                    <div class="col-md-2"> <img src="/assets/admin/layout/img/access.jpg"></div><div class="col-md-10"><label>
                                            Access your payments across 
                                            devices from the cloud
                                        </label></div>

                                    <div class="col-md-2"> <img src="/assets/admin/layout/img/reward.jpg"></div><div class="col-md-10"><label>
                                            Reward programs and much
                                            more coming your way
                                        </label></div>
                                </div>
                                <div class="col-md-2">
                                    <br><br>
                                    <div class="form-group">
                                        <a href="/patron/register/index/{$url}" class="btn blue">Join now</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>
        <!-- /.modal-content -->
    </div>
    <form action="" id="occurence_form" method="post">
        <input type="hidden" value="" id="occurence_id" name="occurence_id" >
    </form>
    <a  href="#guestpay"  data-toggle="modal" id="guest"></a>                         



