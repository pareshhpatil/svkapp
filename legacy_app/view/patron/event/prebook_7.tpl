
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
                                <div class="caption font-blue" style="background-color: #F15F14;background: linear-gradient(#888888, #333);margin-left: 10px;margin-right: 10px;">
                                    <span class="caption-subject bold" style="color: #FFFFFF;" > <h4 style="font-weight: 500;">PRE-REGISTER YOUR IPHONE 7</h4></span>
                                    <p style="color: #FFFFFF;">Presented by <a href="http://mapledti.com" style="color: #FFFFFF;" target="BLANK">Maple</a></p>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row no-margin no-padding" style="">

                        <div class="col-md-12 invoice-payment">
                            <form id="event_form" action="/patron/event/prebook7pay/{$url}" onsubmit="return validate();" method="post">




                                {foreach from=$header item=v}
                                    {if $v.value!='' && $v.column_position!=1 &&  $v.column_position!=2}
                                        <div class="row">
                                            <div class="col-md-2"><strong>{$v.column_name}:</strong></div>
                                            <div class="col-md-10">{$v.value}</div>
                                        </div>
                                    {/if}
                                {/foreach}
                                <span class="help-block"></span>

                                <br>

                                <div class="portlet">

                                    <div class="portlet-body">
                                        <div class="row">
                                            <div class="col-md-3">

                                                <label class="control-label">Model </label>
                                                <select class="form-control" name="iphonemodel[]" onchange="clearModel(1);" id="iphone_model1" data-placeholder="Select...">
                                                    <option value="">Select Model</option>
                                                    {foreach from=$iphonemodel item=k}
                                                        <option value="{$k}">{$k}</option>
                                                    {/foreach}
                                                </select>
                                                <span class="help-block"></span>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="control-label">Colour </label>
                                                <div id="icolour_1">
                                                    <select class="form-control" name="iphonecolour[]" onchange="manageSubcategory(this.value, 1);" id="iphone_colour1" data-placeholder="Select...">
                                                        <option value="">Select Colour</option>
                                                        {foreach from=$iphonecolor item=k}
                                                            <option value="{$k}">{$k}</option>
                                                        {/foreach}
                                                    </select>
                                                    <span class="help-block"></span>
                                                </div>
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

                                            <div class="col-md-2">
                                                <label class="control-label">Quantity </label>
                                                <select name="iphoneseat[]" class="form-control" id="iphone_qty1" onchange="return calculateprebook(this.id);" data-placeholder="Select...">
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                    <option value="6">6</option>
                                                    <option value="7">7</option>
                                                    <option value="8">8</option>
                                                    <option value="9">9</option>
                                                    <option value="10">10</option>
                                                </select>
                                                <span class="help-block"></span>
                                            </div>
                                            <div class="col-md-1">
                                                <a href="javascript:;" >
                                                    <div class="btn blue btn-xs" id="iphone_btn" onclick="addButton('iphone').className = '';"><i class="fa fa-plus"></i></div>
                                                </a>
                                                <span class="help-block"></span>
                                            </div>
                                            <div class="row" style="display: none;" id="other_1">
                                                <div class="col-md-12" >
                                                    <br>
                                                    <label class="control-label pull-left red" style="color: #f3565d;margin-left: 10px;">* If Jet Black color is unavailable, which other color would you prefer?</label>
                                                    <div class="col-md-3" id="icolour_other2">
                                                        <select class="form-control" name="iphoneothercolour[]"  id="iphone_colour_other1" data-placeholder="Select...">
                                                            <option value="">Select..</option>
                                                            {foreach from=$iphoneothercolor item=k}
                                                                <option value="{$k}">{$k}</option>
                                                            {/foreach}
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row"> 
                                            <div id="iphone_row" class="hidden"> 
                                                <div class="col-md-3">
                                                    <label class="control-label">Model </label>
                                                    <select class="form-control" name="iphonemodel[]"  onchange="clearModel(2);" id="iphone_model2" data-placeholder="Select...">
                                                        <option value="">Select Model</option>
                                                        {foreach from=$iphonemodel item=k}
                                                            <option value="{$k}">{$k}</option>
                                                        {/foreach}
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="control-label">Colour </label>
                                                    <div id="icolour_2">
                                                        <select class="form-control" name="iphonecolour[]" onchange="manageSubcategory(this.value, 2);" id="iphone_colour2" data-placeholder="Select...">
                                                            <option value="">Select Colour</option>
                                                            {foreach from=$iphonecolor item=k}
                                                                <option value="{$k}">{$k}</option>
                                                            {/foreach}
                                                        </select>
                                                    </div>
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

                                                <div class="col-md-2">
                                                    <label class="control-label">Quantity </label>
                                                    <select name="iphoneseat[]" class="form-control" id="iphone_qty2" onchange="return calculateprebook(this.id);" data-placeholder="Select...">
                                                        <option value="1">1</option>
                                                        <option value="2">2</option>
                                                        <option value="3">3</option>
                                                        <option value="4">4</option>
                                                        <option value="5">5</option>
                                                        <option value="6">6</option>
                                                        <option value="7">7</option>
                                                        <option value="8">8</option>
                                                        <option value="9">9</option>
                                                        <option value="10">10</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-1">
                                                    <div class="btn red btn-xs" onclick="closeButton('iphone', 'iphone_qty2');"><i class="fa fa-close"></i></div>
                                                </div>

                                                <div class="row" style="display: none;" id="other_2">
                                                    <div class="col-md-12" >
                                                        <br>
                                                        <label class="control-label pull-left red" style="color: #f3565d;margin-left: 10px;">* If Jet Black color is unavailable, which other color would you prefer?</label>
                                                        <div class="col-md-3">
                                                            <select class="form-control" name="iphoneothercolour[]"  id="iphone_colour_other2" data-placeholder="Select...">
                                                                <option value="">Select..</option>
                                                                {foreach from=$iphoneothercolor item=k}
                                                                    <option value="{$k}">{$k}</option>
                                                                {/foreach}
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <!-- Imac start-->
                                <div class="portlet">

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
                                                    <input type="submit"  class="btn blue hidden-print margin-bottom-5 col-md-8 pull-right" value="Book now">
                                                    <input type="hidden" id="narrative" value="{$company_name}" name="company_name">
                                                    <input type="hidden" id="company_name" value="{$company_name}">
                                                    <input value="{$occurence.0.occurence_id}" name="start_date" type="hidden">
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

<form action="" id="occurence_form" method="post">
    <input type="hidden" value="" id="occurence_id" name="occurence_id" >
</form>
<a  href="#guestpay"  data-toggle="modal" id="guest"></a>                         



