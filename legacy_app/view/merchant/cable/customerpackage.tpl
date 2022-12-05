
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title"> Set Top Box Details - {$settopbox_name} </h3>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        {if isset($success)}
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert"></button>
                    <strong>Success!</strong>  {$success}
                </div> 
            {/if}
        <div class="col-md-12">
            
            <div class="col-sm-12 packageListBox mt30">
                {if empty($packages)}
                    <br>
                    <h4 style="text-align: center;">No data found</h4>
                    <a href="/merchant/cable/settopboxlist"  class="btn default"><< Back to List</a>
                {else}
                    <div id="myRadioGroup" class="radioTabsRow">
                        <div id="Cars2" class="desc">
                            <!--panel-group panelGroup-->
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
                                                                    <b> {$v.package_name} </b>                                                                                              
                                                                </a>
                                                            </h4>
                                                        </div> 
                                                        <div class="col-sm-3 panel-title span-padd">
                                                            <span class="price-setup">{if $v.package_cost>0}<i class="fa fa-inr"></i> {$v.package_cost} {/if}</span>
                                                        </div>
                                                        <div class="col-sm-3 panel-title" >
                                                            <span class="price-setup"> {count($v.channels)} Channels</span>
                                                        </div>

                                                        <div class="col-sm-1">
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
                                                <div class="row">
                                                    {foreach from=$v.channels item=cc}
                                                        <div class="col-md-2">{$cc.channel_name}
                                                            <hr>
                                                        </div>

                                                    {/foreach}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" value="{$v.exist_id}" name="exist_id[]">
                                    <!--panel panel-default-->
                                </div>
                            {/foreach}
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
                                                            <b>Channels</b>                                                                                               
                                                        </a>
                                                    </h4>
                                                </div> 
                                                <div class="col-sm-3 panel-title span-padd">
                                                    <span class="price-setup"><i class="fa fa-inr"></i> {$channels_cost|string_format:"%.2f"} </span>
                                                </div>
                                                <div class="col-sm-3 panel-title">
                                                    <span class="price-setup"> {count($channels)} Channels</span>
                                                </div>

                                                <div class="col-sm-1">
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
                                                <div class="row">
                                                    {foreach from=$channels item=cc}
                                                        <div class="col-md-2">{$cc.channel_name} ({$cc.language})
                                                            <hr>
                                                        </div>
                                                    {/foreach}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--panel panel-default-->
                                </div>
                            {/if}
                            <!--panel-group panelGroup-->

                        </div>
                    </div>
                    {if $settopbox_status==0}
                        <form method="post" action="">
                            <input type="hidden" name="link" value="{$link}" />
                            <a href="/merchant/cable/settopboxlist"  class="btn default"><< Back to List</a>
                            <a href="/merchant/cable/selectpackage/{$link}"  class="btn green">Modify</a>
                            <input type="submit" value="Approve" class="btn blue"/>
                        </form>
                    {else}
                        <a href="/merchant/cable/settopboxlist"  class="btn default"><< Back to List</a>
                        <a href="/merchant/cable/selectpackage/{$link}"  class="btn green">Modify</a>
                    {/if}
                {/if}

                <h3>Package History</h3>

                <div class="row">
                    <table class="table table-striped  table-hover" id="table-no-export">
                        <thead>
                            <tr>
                                <th class="td-c">
                                    Name
                                </th>
                                <th class="td-c">
                                    Type
                                </th>
                                <th class="td-c">
                                    Cost
                                </th>
                                <th class="td-c">
                                    Added On
                                </th>
                                <th class="td-c">
                                    Removed on
                                </th>

                            </tr>
                        </thead>
                        <tbody>
                        <form action="" method="">
                            {foreach from=$history item=v}
                                {if {$v.add_date|date_format:"%d-%m-%Y"}!={$v.end_date|date_format:"%d-%m-%Y"}}
                                    <tr>
                                        <td class="td-c">
                                            {$v.name}
                                        </td>
                                        <td class="td-c">
                                            {$v.type}
                                        </td>
                                        <td class="td-c">
                                            {$v.cost}
                                        </td>
                                        <td class="td-c">
                                            {$v.add_date|date_format:"%d-%m-%Y"}
                                        </td>
                                        <td class="td-c">
                                            {$v.end_date|date_format:"%d-%m-%Y"}
                                        </td>
                                    </tr>
                                {/if}
                            {/foreach}
                            </tbody>
                    </table>

                </div>
            </div>

        </div>
        <!-- END CONTENT -->
    </div>
</div>
</div>