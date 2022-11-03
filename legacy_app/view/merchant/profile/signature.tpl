<script src= 
        "https://files.codepedia.info/files/uploads/iScripts/html2canvas.js">
</script> 
{foreach from=$fonts item=v}
    <link href="{$v.description}" rel="stylesheet">
{/foreach}
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    {if $iframe!=null}
       <div class="col-md-12">
        <div class="page-bar">
            <span class="page-title">Digital Signature</span>
        </div>
    {else}
        <div class="page-bar">
            {include file="../../common/breadcumbs.tpl" title={$title} links=$links}
        </div>
    {/if}
        <!-- END PAGE HEADER-->
        <!-- BEGIN PAGE CONTENT-->
        <div class="row">
            {if isset($error)}
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                    <strong>Error!</strong>{$error}
                </div>
            {/if}
            {if isset($success)}
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert"></button>
                    <strong>Success!</strong>{$success}
                </div>
            {/if}
            <form action="" method="post" id="submit_form"  class="form-horizontal form-row-sepe"  enctype="multipart/form-data">
                {CSRF::create('profile_accesskey')}
                <div class="col-md-12">
                    <div class="alert alert-danger display-none">
                        <button class="close" data-dismiss="alert"></button>
                        You have some form errors. Please check below.
                    </div>
                    <!-- End Bulk upload details -->
                    <div class="portlet light bordered">
                        <div class="portlet-body form">
                            <!-- End Bank details -->
                            <!-- Start Bulk upload details -->

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="portlet-body">
                                        <ul class="nav nav-tabs">
                                            <li {if $type=='font'}class="active"{/if}>
                                                <a href="#tab_1_1" data-toggle="tab">
                                                    Choose </a>
                                            </li>
                                            <li {if $type=='file'}class="active"{/if}>
                                                <a href="#tab_1_2" data-toggle="tab">
                                                    Upload </a>
                                            </li>

                                        </ul>
                                        <div class="tab-content">
                                            <div class="tab-pane fade {if $type=='font'}active in{/if}" id="tab_1_1">
                                                <div class="portlet ">
                                                    <div class="portlet-body">

                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <div class="col-md-6">
                                                                        Enter your name
                                                                        <input type="text" name="name" required onchange="changeFontText(this.value, 1);" value="{$name}" class="form-control">
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                        Font size
                                                                        <input type="number" name="font_size" onchange="changeFontText(this.value, 2);"  step="1" max="100" min="10"   value="{$font_size}" class="form-control">
                                                                    </div>
                                                                    <div class="col-md-3 w-auto">
                                                                        Alignment on invoice
                                                                        <select name="align" class="form-control">
                                                                            <option value="left">Left</option>
                                                                            <option {if $align=='right'} selected="" {/if} value="right">Right</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <table class="table">
                                                                    {foreach from=$fonts item=v}
                                                                        <tr>
                                                                            <td style="vertical-align: middle;"><input required {if $font_value==$v.config_key} checked {/if} type="radio" onchange="showImg(this.value);" id="font{$v.config_key}" value="{$v.config_key}" name="font_name" class="icheck"></td>
                                                                            <td >
                                                                                <label id="font_id{$v.config_key}" for="font{$v.config_key}"  class="font-text" style="font-family: '{$v.config_value}',cursive;font-size: {$font_size}px;font-weight: 500;color: black;padding:10px;padding-right: 15px;padding-left: 15px;">{$name}
                                                                                </label>
                                                                            </td>
                                                                        </tr>
                                                                    {/foreach}
                                                                </table>
                                                                <div id="testimg"></div> 
                                                                <div id="previewImage"></div> 
                                                            </div>
                                                        </div>
                                                        <div class="form-actions">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="pull-right">
                                                                        <input type="hidden" name="font_image" id="font_img" />
                                                                        <input type="submit" name="font" value="Submit"  class="btn blue"/>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="tab-pane fade {if $type=='file'}active in{/if}" id="tab_1_2">
                                                <div class="portlet ">

                                                    <div class="portlet-body">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <div class="col-md-3 w-auto">
                                                                        Alignment on invoice
                                                                        <select name="img_align" class="form-control">
                                                                            <option value="left">Left</option>
                                                                            <option {if $align=='right'} selected="" {/if} value="right">Right</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                {if $signature_file==true}
                                                                    <div class="form-group">
                                                                        <div class="col-md-6">
                                                                            <img src="{$signature_file}" style="max-height: 100px;">
                                                                        </div>
                                                                    </div>
                                                                {/if}
                                                                <div class="form-group">
                                                                    <div class="col-md-6">
                                                                        Upload your signature
                                                                        <input type="file" required id="file" onchange="validatefilesize(500000, 'file')" name="signature_file" accept="image/*" >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-actions">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="pull-right">
                                                                        <input type="submit" name="file" value="Submit"  class="btn blue"/>
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
                            </div>


                        </div>						
                    </div>	
                </div>
            </form>	
        </div>
        {if $iframe!=null}
            </div>
        {/if}
</div>	
<!-- END PAGE CONTENT-->




