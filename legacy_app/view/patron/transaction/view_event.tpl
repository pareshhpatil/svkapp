
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
                
                <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->

                <!-- /.modal -->
                <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
                <!-- BEGIN PAGE CONTENT-->
                <div  style="text-align: left;box-shadow: 1px 10px 10px #888888;">
                    <div class="row">
                        {if $info.banner_path!=''}
                            <div class="col-md-12 fileinput fileinput-new" style="text-align: center;" data-provides="fileinput">
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="fileinput-preview thumbnail banner-container" style="min-width: 600px;min-height: 300px;" data-trigger="fileinput"> 
                                        <img class="img-responsive" style="width: 100%;" src="{if $info.banner_path!=''}/uploads/images/logos/{$info.banner_path}{else}holder.js/100%x100%{/if}">
                                    </div>
                                </div>
                            </div>

                        {/if}


                    </div>
                    <div class="row">
                    <div class="col-md-12 no-padding fileinput fileinput-new " data-provides="fileinput">
                        <div class="fileinput-new thumbnail col-md-12 title">
                            <div class="caption font-blue" style="    background-color: #2a94a5; margin-left: 10px;margin-right: 10px;">
                                <span class="caption-subject bold" style="color: #FFFFFF;" > <h2 style="font-weight: 500;">{$info.event_name}</h2></span>
                                <p style="color: #FFFFFF;">Presented by <a href="{$merchant_page}" style="color: #FFFFFF;" target="BLANK">{$info.company_name}</a></p>
                            </div>
                        </div>
                    </div>
                </div>


                        <div class="row" style="">

                    <div class="col-xs-1"></div>
                    <div class="col-xs-10 invoice-payment">
                        <form action="/merchant/event/seatbooking/{$url}" method="post">


                            <div class="row">
                                <div class="col-md-3"><strong>Venue</strong></div>
                                <div class="col-md-9"><i class="fa fa-map-marker"></i>&nbsp;&nbsp;&nbsp;  {$info.venue}</div>
                            </div>

                            {if $info.description!=''}
                                <div class="row">
                                    <div class="col-md-3"><strong>Description</strong></div>
                                    <div class="col-xs-9">{$info.description}</div>
                                </div>
                            {/if}

                            {foreach from=$header item=v}
                                {if $v.value!='' && $v.column_position!=1 &&  $v.column_position!=2}
                                    <div class="row">
                                        <div class="col-md-3"><strong>{$v.column_name}:</strong></div>
                                        <div class="col-md-9">{$v.value}</div>
                                    </div>
                                {/if}
                            {/foreach}
                            
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th class="col-md-6">
                                            Package name

                                        </th>

                                        <th class="hidden-480 col-md-2">
                                            Price
                                        </th>
                                       
                                    </tr>
                                </thead>
                                <tbody>
                                    {$int =1}
                                    {$total =0}
                                    {foreach from=$package item=v}
                                        <tr> <td >
                                                {$v.package_name}
                                                <p class="small">{$v.package_description}</p>
                                            </td>
                                            <td class="hidden-480 col-md-2">
                                                {if {$v.is_flexible==1}}
                                                    Min - {$v.min_price} Max - {$v.max_price}
                                                    <input type="hidden" name="is_flexible[]" value="1">
                                                {else}
                                                    <input type="text" readonly="" class="form-control displayonly" value="{$v.price}" id="unitprice{$int}">
                                                {/if}

                                            </td>
                                            
                                        </tr>
                                        {$total = $total + $v.price}
                                        {$int = $int + 1}
                                    {/foreach}

                                    
                                </tbody>
                            </table>


                           

                        </form>
                        <br>
                        <br>
                    </div>
                            

                </div>

                    </div>
                    <!-- END PAGE CONTENT-->
                </div>
                <div class="col-md-1"></div>
            </div>
        </div>
    </div>
    </div>
    
    