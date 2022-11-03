<section class="pageTitleSection">
    <div class="container">
        <h1>Choose Packages</h1>
    </div>
</section>
<!--pageTitleSection-->
<section class="ptb40">
    <div class="container">
        <form action="/cable/packagesaved" method="post">
            <input type="hidden" name="settopbox_id" value="{$settopbox_id}">
            <input type="hidden" name="channel_selected" id="ch_sel" value="{$ch_selected}">
            <input type="hidden" name="package_selected" id="pkg_sel" value="{$pkg_selected}">
            <div class="row pakageCount sticky-top" style="background-color: #2c5294; color:#ffffff; padding: 5px;">
                <div class="col-md-4 col-xs-3"><h5 id="pkg_selected" class="pull-left"> {count($checked_package)} Package Selected</h5></div>
                <div class="col-md-4 col-xs-3"><h5 id="channl_selected">{count($checked_channel)} Channels</h5></div>
                <div class="col-md-4 col-xs-6"><h5 id="total_cost" class="pull-right">Total Cost (Including GST) : <span class="total">{$total_cost|string_format:"%.2f"}</span></h5></div>
            </div>
            <!--col-12-->

            <div class="row">

                <div class="col-sm-12 packageListBox mt30">
                    <div class="alert alert-info">
                        <strong>Info! </strong>To select an add on pack, click on broadcaster bouquet
                    </div> 

                    <div id="myRadioGroup" class="radioTabsRow">

                        {$inttt=0}
                        {foreach from=$packages key=k item=v}
                            <div class="radioTabs" style="width: auto;">
                                <input type="radio" id="{$k}f-option" name="cars" {if $inttt==0}checked="checked" {/if}value="s{$k}">
                                <label for="{$k}f-option">{$v.name}</label>								
                                <div class="check">{if $inttt==0}<div class="inside"></div>{/if} </div>
                            </div>
                            {$inttt=$inttt+1}
                        {/foreach}

                        <div class="radioTabs" style="width: auto;">
                            <input type="radio" id="s-option" name="cars" value="3">
                            <label for="s-option">Genre</label>								
                            <div class="check"><div class="inside"></div></div>
                        </div>

                        <div class="radioTabs" style="width: auto;">
                            <input type="radio" id="t-option" name="cars" value="4" >
                            <label for="t-option">Search</label>
                            <div class="check"><div class="inside"></div></div>
                        </div>



                        {$inttt=0}
                        {foreach from=$packages key=k item=v}
                            <div id="Carss{$k}" class="desc" {if $inttt>0}style="display: none;"{/if}>
                                <h3>{$v.name} {if $v.min>0}<span style="font-size: 15px; color: maroon;">(Minimum {$v.min} package)</span>{/if}</h3> 
                                {foreach from=$v.packages key=kp item=pk}
                                    <div class="panel-group panelGroup" id="accordion{$kp}" role="tablist" aria-multiselectable="true">
                                        <div class="panel panel-default">
                                            <div class="panel-heading" role="tab" id="headingOne{$kp}">
                                                <div class="row channelList">
                                                    <div class="col-sm-5"> 
                                                        <h4 class="panel-title">
                                                            <a role="button" data-toggle="collapse" data-parent="#accordion{$kp}" href="#collapseOne{$kp}" aria-expanded="true"
                                                               aria-controls="collapseOne{$kp}">
                                                                {$pk.package_name} {if $pk.sub_package_name!=''}
                                                                    <span style="font-size: 12px;"> (Including {$pk.sub_package_name})</span>
                                                                {/if}
                                                            </a>
                                                        </h4>
                                                    </div> 
                                                    <div class="col-sm-2 panel-title span-padd" style="display: block;padding: 15px;">
                                                        <span class="price-setup"><i class="fa fa-rupee-sign"></i> {$pk.package_cost} </span>
                                                    </div>
                                                    <div class="col-sm-3 panel-title" style="display: block;padding: 15px;">
                                                        <span class="subInfoDays"></span>
                                                    </div>
                                                    <div class="col-sm-1 panel-title" style="display: block;padding: 10px;">
                                                        <input {if $pk.exist==1} checked="" {/if} id="pkg_id{$pk.package_id}" name="package_id[]" onchange="GetSelected('pkg',{$pk.package_id});" value="{$pk.package_id}" class="checkBox" {if $v.max==1}type="radio"  {else} type="checkbox" {/if} />
                                                        <input name="package_group[]" value="{$pk.package_group}" type="hidden" />
                                                        <input name="pkg_total_cost[]" id="pkgcost{$kp}" value="{$pk.total_cost}" type="hidden" />
                                                        <input name="group_type[]" id="grptype{$kp}" value="{$v.group_type}" type="hidden" />
                                                        <input name="" id="countchannel{$kp}" value="{$pk.channels|count}" type="hidden" />
                                                        <input name="package_max[]" value="{$v.max}" type="hidden" />
                                                        <input name="package_min[]" value="{$pk.min}" type="hidden" />
                                                    </div>
                                                    <div class="col-sm-1 arrowMobile">
                                                        <h4 class="panel-title">
                                                            <a role="button" data-toggle="collapse" data-parent="#accordion{$kp}" href="#collapseOne{$kp}" aria-expanded="true"
                                                               aria-controls="collapseOne{$kp}">
                                                                <i class="more-less fa fa-chevron-right"></i>
                                                            </a>
                                                        </h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="collapseOne{$kp}" class="panel-collapse collapse pakg" role="tabpane{$kp}" aria-labelledby="headingOne{$kp}">
                                                <div class="panel-body">
                                                    <ul class="channelLogoList channelListSelect">
                                                        {foreach from=$pk.channels key=cp item=pc}
                                                            <li>
                                                                <div class="channelLogoBox">
                                                                    <img src="/uploads/images/cablechannel/{$pc.logo}">
                                                                </div>
                                                                <div class="channalName" title="{$pc.channel_name}">{if {$pc.channel_name|count_characters:true}>20}{$pc.channel_name|substr:0:18}...{else}{$pc.channel_name}{/if}</div>
                                                            </li>
                                                        {/foreach}

                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <!--panel panel-default-->
                                    </div>
                                {/foreach}
                            </div>
                            {$inttt=$inttt+1}
                        {/foreach}

                        <div id="Cars3" class="desc" style="display: none;">
                            {$int=100}
                            {foreach from=$genre key=k item=v}
                                {$int=$int+1}
                                <div class="panel-group panelGroup" id="MakeMyHD" role="tablist" aria-multiselectable="true">
                                    <div class="panel panel-default">
                                        <div class="panel-heading" role="tab" id="headingOne{$int}">
                                            <h4 class="panel-title">
                                                <a role="button" data-toggle="collapse" data-parent="#MakeMyHD" href="#MakeMyHD{$int}" aria-expanded="true"
                                                   aria-controls="MakeMyHD{$int}">
                                                    <i class="more-less fa fa-chevron-right"></i>
                                                    {$v}
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="MakeMyHD{$int}"  class="panel-collapse collapse" role="tabpane{$int}" aria-labelledby="MakeMyHD{$int}">
                                            <div class="panel-body" style="max-height: 500px;overflow: auto;" >
                                                <ul class="channelLogoList channelListSelect">
                                                    {foreach from=$genre_pkg.$v item=vc}
                                                        {if $vc.exist!=1}
                                                            <li>
                                                                <div class="row">
                                                                    <div class="col-sm-5">
                                                                        <div class="channelLogoBox">
                                                                            <img src="/uploads/images/cablechannel/{$vc.logo}">
                                                                        </div>
                                                                        <div class="channalName">{$vc.channel_name}</div>
                                                                    </div>
                                                                    <div class="col-sm-2 channalName">
                                                                        <span class="subInfoDays">{$vc.language}</span>
                                                                    </div>
                                                                    <div class="col-sm-3 channalName">
                                                                        <span class="price-setup"><i class="fa fa-rupee-sign"></i> {$vc.cost}</span>
                                                                    </div>

                                                                    <div class="col-sm-1 channalName">
                                                                        {if $vc.exist!=1}
                                                                            <input {if $vc.checked==1} checked="" {/if}name="channel_id[]" onchange="GetSelected('genre',{$vc.channel_id});" id="genre{$vc.channel_id}" value="{$vc.channel_id}" class="checkBox" type="checkbox" />
                                                                            <input name="ch_total_cost[]" id="chtotalcost{$vc.channel_id}" value="{$vc.total_cost}" type="hidden" />
                                                                        {else}
                                                                        {/if}

                                                                    </div>
                                                                </div>
                                                            </li>
                                                        {/if}
                                                    {/foreach}
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <!--panel panel-default-->
                                </div>
                            {/foreach}
                            <!--panel-group panelGroup-->
                        </div>
                        <div id="Cars4" class="desc" style="display: none;">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="searchBoxTop searchWrap">
                                    <input type="text" id="searchval" onkeyup="filterChannel();" placeholder="Search Channels">
                                    <a onclick="filterChannel();" class="btnBlue hvr-bounce-to-right hidden-xs"><i class="fa fa-search"></i> Search</a>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12 mt30" >
                                <div class="panel-group panelGroup" id="MakeMyHDSearch" role="tablist" aria-multiselectable="true">
                                    <div class="panel panel-default" style="max-height: 500px;overflow: auto;">
                                        <div id="MakeMyHDSearch2" class="panel-collapse" role="tabpanel" aria-labelledby="MakeMyHDSearch2">
                                            <div class="panel-body">
                                                <ul class="channelLogoList channelListSelect" id="myUL">
                                                    {foreach from=$channels item=vc}
                                                        {if $vc.exist!=1}
                                                            <li>
                                                                <div class="row">
                                                                    <div class="col-sm-5">
                                                                        <div class="channelLogoBox">
                                                                            <img src="/uploads/images/cablechannel/{$vc.logo}">
                                                                        </div>
                                                                        <div class="channalName"><span>{$vc.channel_name}</span></div>
                                                                    </div>
                                                                    <div class="col-sm-2 channalName">
                                                                        <span class="price-setup"><i class="fa fa-rupee-sign"></i> {$vc.cost}</span>
                                                                    </div>
                                                                    <div class="col-sm-3 channalName">
                                                                        <span class="subInfoDays">{$vc.language}</span>
                                                                    </div>
                                                                    <div class="col-sm-1 channalName">
                                                                        {if $vc.exist!=1}
                                                                            <input {if $vc.checked==1} checked="" {/if} name="channel_id[]" onchange="GetSelected('channel',{$vc.channel_id});" id="single{$vc.channel_id}"  value="{$vc.channel_id}" class="checkBox" type="checkbox" />
                                                                            <input name="ch_total_cost[]" id="chtotalcost{$vc.channel_id}" value="{$vc.total_cost}" type="hidden" />
                                                                        {else}
                                                                        {/if}
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        {/if}
                                                    {/foreach}
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <!--panel panel-default-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--col-12 packageListBox-->
            </div>
            <div class="col-12 btnGroup sticky-bottom">
                <a href="/cable/customerpackages/{$link}" class="btnBlue hvr-bounce-to-right">Cancel</a>
                <button type="submit" class="btnBlue hvr-bounce-to-right">Save</button>
            </div>
            <hr/>
            <p style="width: 100%;"> <img src="/assets/admin/layout/img/logo.png" class="img-responsive pull-right powerbyimg" alt=""/><span class="powerbytxt">powered by</span></p>
            <!--row-->
            <input type="hidden" id="cable_setting" value="{$cable_setting}" class="displayonly">
            
        </form>
    </div>
    <!--container-->
</section>

<script>
    var cable_set = '{$cable_setting}';
    var channel_json = '{$ch_array}';
    channel_array = JSON.parse(channel_json);
    var pkg_json = '{$pkg_array}';
    package_array = JSON.parse(pkg_json);
</script>