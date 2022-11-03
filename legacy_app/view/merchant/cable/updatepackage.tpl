
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title"> Set Top Box Details - {$settopbox_name} </h3>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <form action="/merchant/cable/packagesaved" method="post">
            <input type="hidden" name="channel_selected" id="ch_sel" value="{$ch_selected}">
            <input type="hidden" name="package_selected" id="pkg_sel" value="{$pkg_selected}">
            <div class="col-md-12">
                {if isset($error)}
                    <div class="alert alert-danger">
                        <div class="media">
                            <strong>Error!</strong> {$error}
                        </div>
                    </div>
                {/if}
                <div class="col-sm-12 packageListBox mt30" style="min-height: 500px;">
                    <div class="row">
                        <div class="col-md-12">
                            <input type="hidden" name="settopbox_id" value="{$settopbox_id}">
                            <div class="row pakageCount sticky-top" style="background-color: #2c5294; color:#ffffff; padding: 5px;">
                                <div class="col-md-4 col-xs-3"><h4 id="pkg_selected" class="pull-left"> {count($checked_package)} Package Selected</h4></div>
                                <div class="col-md-4 col-xs-3"><h4 id="channl_selected">{count($checked_channel)} Channels Selected</h4></div>
                                <div class="col-md-4 col-xs-6"><h4 id="total_cost" class="pull-right">Total Cost (Including GST) : <span class="total">{$total_cost|string_format:"%.2f"}</span></h4></div>
                            </div>
                            <br>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <ul class="nav nav-pills">
                            {$inttt=0}
                            {foreach from=$packages key=k item=v}
                                <li class="{if $inttt==0}active {/if}">
                                    <a href="#tab_2_{$inttt+1}" data-toggle="tab" aria-expanded="true">
                                        {$v.name} </a>
                                </li>
                                {$inttt=$inttt+1}
                            {/foreach}

                            <li class="">
                                <a href="#tab_2_{$inttt+1}" data-toggle="tab" aria-expanded="false">
                                    Alacarte </a>
                            </li>

                        </ul>
                        <div class="tab-content">

                            {$inttt=0}
                            {foreach from=$packages key=k item=v}
                                <div class="tab-pane fade {if $inttt==0}active in{/if}" id="tab_2_{$inttt+1}"  style="max-height: 400px;overflow-x: hidden;">
                                    <table class="table table-striped table-bordered table-hover" {if $inttt==1}id="table-no-export2"{/if}>
                                        <thead>
                                            <tr>
                                                <th >
                                                    Name
                                                </th>
                                                <th  class="td-c">
                                                    Price
                                                </th>

                                                <th  class="td-c">
                                                    
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {foreach from=$v.packages key=kp item=pk}
                                                <tr>
                                                    <td class=""><a href="/merchant/cable/packagechannels/{$pk.package_id}" class="iframe">{$pk.package_name} {if $pk.sub_package_name!=''}
                                                            <span style="font-size: 12px;"> (Including {$pk.sub_package_name})</span>
                                                            {/if}</a></td>
                                                        <td class="td-c">{$pk.package_cost}</td>
                                                        <td class="td-c">
                                                            {if $pk.exist==1} <span style="color: transparent;">a</span> {else}<span style="color: transparent;">b</span> {/if}
                                                            <input {if $pk.exist==1} checked="" {/if} id="pkg_id{$pk.package_id}" name="package_id[]" onchange="GetSelected('pkg',{$pk.package_id});" value="{$pk.package_id}"  {if $v.max==1}type="radio" class="icheck"  {else} class="checkBox" type="checkbox" {/if} />
                                                            <input name="package_group[]" value="{$pk.package_group}" type="hidden" />
                                                            <input name="pkg_total_cost[]" id="pkgcost{$kp}" value="{$pk.total_cost}" type="hidden" />
                                                            <input name="group_type[]" id="grptype{$kp}" value="{$v.group_type}" type="hidden" />
                                                            <input name="" id="countchannel{$kp}" value="{$pk.channels|count}" type="hidden" />
                                                            <input name="package_max[]" value="{$v.max}" type="hidden" />
                                                            <input name="package_min[]" value="{$pk.min}" type="hidden" /></td>
                                                    </tr>
                                                    {/foreach}
                                                    </tbody>
                                                </table>

                                            </div>
                                            {$inttt=$inttt+1}
                                            {/foreach}


                                                <div class="tab-pane fade" id="tab_2_{$inttt+1}" style="max-height: 400px;overflow-x: hidden;">
                                                    <table class="table table-striped table-bordered table-hover" id="table-no-export">
                                                        <thead>
                                                            <tr>
                                                                <th >
                                                                    Name
                                                                </th>
                                                                <th  class="td-c">
                                                                    Language
                                                                </th>
                                                                <th  class="td-c">
                                                                    Price
                                                                </th>

                                                                <th  class="td-c">
                                                                    
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            {foreach from=$channels item=vc}
                                                                {if $vc.exist!=1}
                                                                    <tr>
                                                                        <td class="">{$vc.channel_name}</td>
                                                                        <td class="td-c">{$vc.language}</td>
                                                                        <td class="td-c">{$vc.cost}</td>

                                                                        <td class="td-c">
                                                                            {if $vc.exist!=1}
                                                                                {if $vc.checked==1} <span style="color: transparent;">a</span> {else}<span style="color: transparent;">b</span> {/if}
                                                                                <input {if $vc.checked==1} checked="" {/if} name="channel_id[]" onchange="GetSelected('channela',{$vc.channel_id});" id="single{$vc.channel_id}"  value="{$vc.channel_id}" class="checkBox" type="checkbox" />
                                                                                <input name="ch_total_cost[]" id="chtotalcost{$vc.channel_id}" value="{$vc.total_cost}" type="hidden" />
                                                                            {else}
                                                                            {/if}
                                                                        </td>
                                                                    </tr>
                                                                {/if}
                                                            {/foreach}
                                                        </tbody>
                                                    </table>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" id="cable_setting" value="{$cable_setting}" class="displayonly">
                                    <div class="form-actions">
                                        <div class="row">
                                            <div class="pull-right" style="margin-right: 30px;">

                                                <input type="submit" value="Save" class="btn blue"/>
                                                <a href="/merchant/cable/settopboxlist" class="btn btn-link">Cancel</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </form>
                            <!-- END CONTENT -->
                        </div>
                    </div>
                </div>

                <script>
                    var cable_set = '{$cable_setting}';
                    var channel_json = '{$ch_array}';
                    channel_array = JSON.parse(channel_json);
                    var pkg_json = '{$pkg_array}';
                    package_array = JSON.parse(pkg_json);
                </script>