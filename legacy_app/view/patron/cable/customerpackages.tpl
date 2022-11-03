<section class="pageTitleSection">
    <div class="container">
        <h1>Current Packages</h1>
    </div>
</section>
<section class="ptb40">
    <div class="container">
        {if isset($success)}
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert"></button>
                <strong>Bingo! </strong>{$success}
            </div> 
        {/if}
        <div class="row pakageCount sticky-top" style="background-color: #2c5294; color:#ffffff; padding: 5px;">
            <div class="col-md-4 col-xs-3"><h5 id="pkg_selected" class="pull-left"> {count($packages)} Package</h5></div>
            <div class="col-md-4 col-xs-3"><h5 id="channl_selected">{count($channels)} Channels</h5></div>
            <div class="col-md-4 col-xs-6"><h5 id="total_cost">Total Cost (Including GST) : <span class="total">{$grand_total|string_format:"%.2f"}</span> </h5></div>
        </div>

        <div class="row">

            <div class="col-sm-12">
                <a href="/cable/selectpackage/{$link}" class="btnBlue hvr-bounce-to-right pull-right">Add</a>
            </div>
        </div>
        <div class="row mt30">
            <div class="col-sm-12 packageListBox mt30">
                <form id="pkg" action="/cable/updatepackage" method="post">
                    <div id="myRadioGroup" class="radioTabsRow">
                        <div id="Cars2" class="desc">
                            {foreach from=$packages item=v}
                                <div class="panel-group panelGroup" id="accordion{$v.package_id}" role="tablist" aria-multiselectable="true">
                                    <div class="panel panel-default">
                                        <div class="panel-heading" role="tab" id="headingOne{$v.package_id}">
                                            <div class="row">
                                                <div class="col-sm-12">

                                                    <div class="row channelList">
                                                        <div class="col-sm-5"> 
                                                            <h4 class="panel-title">
                                                                <a role="button" data-toggle="collapse" data-parent="#accordion{$v.package_id}" href="#collapseOne{$v.package_id}" aria-expanded="true"
                                                                   aria-controls="collapseOne{$v.package_id}">
                                                                    {$v.package_name}                                                                                               
                                                                </a>
                                                            </h4>
                                                        </div> 
                                                        <div class="col-sm-2 panel-title span-padd" style="display: block;padding: 15px;">
                                                            <span class="price-setup">{if $v.package_cost>0}<i class="fa fa-rupee-sign"></i> {$v.package_cost} {/if}</span>
                                                        </div>
                                                        <div class="col-sm-3 panel-title" style="display: block;padding: 15px;">
                                                            <span class="price-setup"> {count($v.channels)} Channels</span>
                                                        </div>

                                                        <div class="col-sm-1 channalName">
                                                            {if $v.default!=1}
                                                                <input type="checkbox" onchange="packageTotal();" class="checkBox" checked="" name="exist_id[]" id="{$v.exist_id}" value="{$v.exist_id}"  />
                                                                <input name="cost[]" id="cost{$v.exist_id}" value="{$v.package_cost}" type="hidden" />
                                                            {/if}
                                                            <input type="hidden" name="group_type" value="{$v.group_type}" id="group{$v.exist_id}">
                                                            <input type="hidden" name="" value="{$v.channels|count}" id="channelcount{$v.exist_id}">
                                                        </div>

                                                        <div class="col-sm-1 arrowMobile">
                                                            <h4 class="panel-title">
                                                                <a role="button" data-toggle="collapse" data-parent="#accordion{$v.package_id}" href="#collapseOne{$v.package_id}" aria-expanded="true"
                                                                   aria-controls="collapseOne{$v.package_id}">
                                                                    <i class="more-less fa fa-chevron-right"></i>
                                                                </a>
                                                            </h4>
                                                        </div>
                                                    </div>


                                                </div>
                                            </div>
                                        </div>
                                        <div id="collapseOne{$v.package_id}" class="panel-collapse collapse pakg" role="tabpanel" aria-labelledby="headingOne{$v.package_id}">
                                            <div class="panel-body">
                                                <ul class="channelLogoList channelListSelect">
                                                    {foreach from=$v.channels item=cc}
                                                        <li>
                                                                <div class="channelLogoBox">
                                                                    <img src="/uploads/images/cablechannel/{$cc.logo}">
                                                                </div>
                                                                <div class="channalName" title="{$cc.channel_name}">{if {$cc.channel_name|count_characters:true}>20}{$cc.channel_name|substr:0:18}...{else}{$cc.channel_name}{/if}</div>
                                                        </li>
                                                    {/foreach}
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <!--panel panel-default-->
                                </div>
                            {/foreach}
                            <!--panel-group panelGroup-->


                            <!--panel-group panelGroup-->

                            {if !empty($channels)}
                                <div class="panel-group panelGroup" id="accordionc3" role="tablist" aria-multiselectable="true">
                                    <div class="panel panel-default">
                                        <div class="panel-heading" role="tab" id="headingOnec3">

                                            <div class="row channelList">
                                                <div class="col-sm-5"> 
                                                    <h4 class="panel-title">
                                                        <a role="button" data-toggle="collapse" data-parent="#accordionc3" href="#collapseOnec3" aria-expanded="true"
                                                           aria-controls="collapseOnec3">
                                                            Channels                                                                                               
                                                        </a>
                                                    </h4>
                                                </div> 
                                                <div class="col-sm-2 panel-title span-padd" style="display: block;padding: 15px;">
                                                    <span class="price-setup"><i class="fa fa-rupee-sign"></i> {$channels_cost|string_format:"%.2f"} </span>
                                                </div>
                                                <div class="col-sm-4 panel-title" style="display: block;padding: 15px;">
                                                    <span class="price-setup"> {count($channels)} Channels</span>
                                                </div>

                                                <div class="col-sm-1 arrowMobile">
                                                    <h4 class="panel-title">
                                                        <a role="button" data-toggle="collapse" data-parent="#accordionc3" href="#collapseOnec3" aria-expanded="true"
                                                           aria-controls="collapseOnec3">
                                                            <i class="more-less fa fa-chevron-right"></i>
                                                        </a>
                                                    </h4>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="collapseOnec3" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOnec3">
                                            <div class="panel-body">
                                                <ul class="channelLogoList channelListSelect">
                                                    {foreach from=$channels item=cc}
                                                        <li id="accordiona{$v.package_id}">
                                                            <div class="row">
                                                                <div class="col-sm-5">
                                                                    <div class="channelLogoBox">
                                                                        <img src="/uploads/images/cablechannel/{$cc.logo}">
                                                                    </div>
                                                                    <div class="channalName">{$cc.channel_name}</div>
                                                                </div>
                                                                <div class="col-sm-3 channalName">
                                                                    <span class="price-setup"><i class="fa fa-rupee-sign"></i> {$cc.cost}</span>
                                                                </div>
                                                                <div class="col-sm-3 channalName">
                                                                    <span class="subInfoDays">{$cc.language}</span>
                                                                </div>
                                                                <div class="col-sm-1 channalName">
                                                                    <input type="checkbox" class="checkBox" onchange="packageTotal();" checked="" name="exist_id[]" id="{$cc.exist_id}" value="{$cc.exist_id}" />
                                                                    <input name="cost[]" id="cost{$cc.exist_id}" value="{$cc.cost}" type="hidden" />
                                                                    <input type="hidden" value="3" id="group{$cc.exist_id}">
                                                                    <input type="hidden" name="" value="1" id="channelcount{$cc.exist_id}">

                                                                </div>
                                                            </div>
                                                        </li>
                                                    {/foreach}

                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <!--panel panel-default-->
                                </div>
                            {/if}
                            <!--panel-group panelGroup-->
                            <div class="col-12 btnGroup sticky-bottom">
                                <a href="/cable/settopbox" class="btnBlue hvr-bounce-to-right">Cancel</a>
                                <input type="hidden" name="service_id" value="{$link}">
                                <a href="#basic"  data-toggle="modal" class="btnBlue hvr-bounce-to-right">Confirm</a>
                            </div>
                        </div>

                    </div>

                    <div class="modal fade" id="basic" tabindex="-1" role="basic" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                    <h4 class="modal-title">Confirm Package</h4>
                                </div>
                                <div class="modal-body">
                                    Total Amount Of Package is Rs. <span id="confirm_amt">{$grand_total|string_format:"%.2f"}</span>. Your Set Top Box will be activated in next 24 Hrs. Do you want to confirm?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btnBlue" data-dismiss="modal">No</button>
                                    <button type="submit" value="" id="deleteanchor" class="btn btnBlue">Yes</button>
                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                </form>
                
            </div>
                                <hr/>
                <p style="width: 100%;"> <img src="/assets/admin/layout/img/logo.png" class="img-responsive pull-right powerbyimg" alt=""/><span class="powerbytxt">powered by</span></p>
            <!--col-12 packageListBox-->
        </div>
        <!--row-->

    </div>
    <!--container-->
</section>

<script>
            var cable_set='{$cable_setting}';
        </script>