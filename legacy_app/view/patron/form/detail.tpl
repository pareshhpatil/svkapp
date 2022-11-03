
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">&nbsp;</h3>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row no-margin">
        <div class="col-md-2"></div>
        <div class="col-md-8" style="text-align: -webkit-center;text-align: -moz-center;">
            {if $success_msg==1}
                <div class="alert alert-success ml-0 mr-0" style="text-align: left;margin-right: 0px !important;">
                    <button type="button" class="close" data-dismiss="alert"></button>
                    {if isset($thankyoupage.successMessage)}
                        {$thankyoupage.successMessage}
                    {else}
                        <strong></strong>Your form has been submited successful.
                    {/if}
                </div>
            {/if}

            {if isset($thankyoupage)}
                {foreach from=$thankyoupage.design item=v}
                    {if $v.type=='image'}
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12" style="text-align: {$v.align}">
                                    <img src="{$v.src}" class="{$v.className}" style="{$v.style}">
                                </div>
                            </div>
                        </div>
                    {else if $v.type=='h3'}
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <h3 style="color: #275770;"><b>{$v.label}</b></h3>
                                </div>
                            </div>
                        </div>
                    {else if $v.type=='paragraph'}
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <p  class="{$v.className}" style="{$v.style}">
                                        {$v.value}
                                    </p>
                                </div>
                            </div>
                        </div>
                    {else if $v.type=='buttongroup'}
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12" style="text-align: {$v.align}">
                                    {foreach from=$v.buttons item=b}
                                        <a href="{$b.link}" class="{$b.className}" >
                                            {if isset($b.faicon)}
                                                <i class="fa fa-{$b.faicon}"></i>
                                            {/if}
                                            {$b.text}</a>
                                        {/foreach}

                                </div>
                            </div>
                        </div>
                    {/if}
                {/foreach}
                <br><br>
            {/if}


            {if $thankyoupage.hideFormDetail!=1}
                <h3 class="page-title">Form detail</h3>
                {if isset($errors)}
                    <div class="alert alert-danger alert-dismissable" style="max-width: 900px;text-align: left;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                        <strong>Error!</strong>
                        <div class="media">
                            {foreach from=$errors key=k item=v}
                                <p class="media-heading">{$k} - {$v.1}</p>
                            {/foreach}
                        </div>

                    </div>
                {/if}
                <div class="portlet light bordered" style="max-width: 900px;text-align: left;">
                    <div class="portlet-body form">
                        <!--<h3 class="form-section">Profile details</h3>-->
                        <form action="" method="post" id="submit_form" class="form-horizontal form-row-sepe">
                            <div class="form-body">
                                <!-- Start profile details -->
                                <div class="row">
                                    <div class="col-md-12">

                                        <div class="row">
                                            <div class="col-md-12">
                                                {foreach from=$detail item=v}
                                                    {if $v.value!='' && $v.label!=='Payment status'}
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">{$v.label} :<span class="required">
                                                                    </span></label>
                                                                <label class="control-label col-md-6" style="text-align: left;" >{$v.value} <span class="required">
                                                                    </span></label>
                                                            </div>
                                                        </div>
                                                    {/if}
                                                {/foreach}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>					
                    <!-- End profile details -->
                </div>
            {/if}
        </div>
    </div>	
    <!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->
</div>