
<div class="page-content">
    <div class="row">
        <div class="col-md-1"></div>
        <div class=" col-md-9" style="text-align: -webkit-center;text-align: -moz-center;">
            {if $is_success=='True'}
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-block alert-success fade in">
                            <button type="button" class="close" data-dismiss="alert"></button>
                            <h4 class="alert-heading">Event Created / Modified</h4>
                            <p>
                                Event has been saved. You can share link to your patrons.
                            </p>
                        </div>
                    </div>

                </div>
            {/if}

            {if isset($haserrors)}
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert"></button>
                    <strong>Error!</strong>
                    <div class="media">
                        {foreach from=$haserrors item=v}

                            <p class="media-heading">{$v.0} - {$v.1}.</p>
                        {/foreach}
                    </div>

                </div>
            {/if}
            
            {if $is_valid=='NO'}
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert"></button>
                    <strong>Invalid event!</strong>
                    <div class="media">
                        <p class="media-heading">{$invalid_message}</p>
                    </div>
                </div>
            {/if}

            <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->

            <!-- /.modal -->
            <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
            <!-- BEGIN PAGE CONTENT-->
            <div  style="max-width: 900px;text-align: left;">
                <div class="row">
                    <div class="col-md-12 fileinput fileinput-new banner-main max900" data-provides="fileinput">
                        <div class="fileinput fileinput-new" data-provides="fileinput">
                            <div class="fileinput-preview thumbnail banner-container" style="min-width: 600px;" data-trigger="fileinput"> 
                                <img class="img-responsive" src="{if $info.banner_path!=''}/uploads/images/logos/{$info.banner_path}{else}holder.js/100%x100%{/if}"></div>
                        </div>

                    </div>
                    <div class="row no-margin" style="position: absolute;">
                        {if $info.image_path!=''}<div class="col-md-12 no-padding fileinput fileinput-new logo-main" data-provides="fileinput">
                                <div class="fileinput-new thumbnail" >
                                    <img class="img-responsive templatelogo"  src="/uploads/images/logos/{$info.image_path}" alt=""/>
                                </div>
                            </div>{/if}

                        </div>
                        <div class="col-md-12 no-padding fileinput fileinput-new eventtitle max900" data-provides="fileinput">
                            <div class="fileinput-new thumbnail col-md-11 title">
                               <div class="caption font-blue">
                                    <a href="{$merchant_page}" target="BLANK"><span class="caption-subject bold"> <h2>{$info.event_name}</h2></span></a>
                                <p>Presented by : {$info.company_name}</p>
                            </div>
                            </div>
                        </div>

                    </div>


                    <div class="row" style="margin-top: 330px;">
                        <h3></h3><br><br>
                        <div class="col-xs-1"></div>
                        <div class="col-xs-10 invoice-payment">
                            <form action="/merchant/event/seatbooking/{$url}" method="post">


                                {if {$info.event_from_date}!={$info.event_to_date}}
                                    <div class="row ">
                                        <div class="col-md-3"><strong>Date</strong></div>
                                        <div class="col-md-9 "><i class="fa fa-calendar"></i>&nbsp;&nbsp;  {{$info.event_from_date}|date_format:"%A, %B %e, %Y"} to {{$info.event_to_date}|date_format:"%A, %B %e, %Y"}</div>
                                    </div>
                                {else}
                                    <div class="row">
                                        <div class="col-md-3"><strong>Date</strong></div>
                                        <div class="col-md-9"><i class="fa fa-calendar"></i>&nbsp;&nbsp; {{$info.event_from_date}|date_format:"%A, %B %e, %Y"}</div>
                                    </div>
                                {/if}

                                {if $info.is_flexible==1}
                                    <div class="row">
                                        <div class="col-md-3"><strong>Min amount</strong></div>
                                        <div class="col-md-9"><i class="fa fa-inr fa-large"></i> &nbsp;&nbsp;{$info.min_price}</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3"><strong>Max amount</strong></div>
                                        <div class="col-md-9"><i class="fa fa-inr fa-large"></i> &nbsp;&nbsp;{$info.max_price}</div>
                                    </div>
                                {else}
                                    <div class="row">
                                        <div class="col-md-3"><strong>Amount</strong></div>
                                        <div class="col-md-9"><i class="fa fa-inr fa-large"></i> &nbsp;&nbsp;{$info.price}</div>
                                        <input type="hidden" id="price" value="{$info.price}"/>
                                    </div>
                                {/if}
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
                                <div class="row">

                                    <div class="col-xs-3"><strong>Post link:</strong></div>
                                    <div class="col-xs-9"><a href="{$link}" >{$link}</a></div>
                                </div>
                                    <input type="hidden" name="seat" value="1">
                                <div class="row">
                                    <br>
                                    <div class="col-md-3"></div>
                                    <div class="col-md-9">
                                        {if $is_valid=='YES'}
                                            <input type="submit" class="btn blue hidden-print margin-bottom-5" value="Pay offline" />
                                        {else}
                                            <p class="btn btn-xs red">Respond option not available</p>
                                        {/if}</div>
                                </div>

                            </form>
                        </div>


                    </div>

                </div>
                <!-- END PAGE CONTENT-->
            </div>
            <div class="col-md-1"></div>
        </div>
    </div>
</div>




