<style>hr {
        height: 1px;
        background-color: grey;
    }</style>
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <br class="hidden-xs">
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row no-margin">
        <div class="col-md-3 hidden-xs">&nbsp;</div>
        <div class="col-md-6">
            {if isset($success)}
                <div class="alert alert-success nolr-margin">
                    <button type="button" class="close" data-dismiss="alert"></button>
                    <strong>Success!</strong>{$success}
                </div>
            {/if}
            <div class="portlet">
                <div class="portlet-body apps-shadow">
                    <div class="row">
                        <div class="col-md-7">
                            <span class="apps-company-name">{$company_name}</span>
                        </div>
                        <div class="col-md-5">
                            <div class="pull-right text-center ml-2">

                                <p class="apps-contact"> {$helpdesk_contact}</p>
                                <p class="apps-help">Helpdesk contact</p>

                                <p class="apps-contact">support@swipez.in</p>
                                <p class="apps-help mb-0">Helpdesk email</p>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <p class="apps-heading">Active Apps</p>
                </div>
            </div>
            <div class="portlet">
                <div class="portlet-body apps-shadow">
                    <div class="row">
                        {$int=0}
                        {foreach from=$services key=k item=v}
                            {if $v.status==1}
                                <div class="col-md-6">
                                    <a href="/merchant/dashboard/index/{$v.encrypted_id}">
                                        <h5 class="apps-heading">
                                            <i class="fa fa-check apps"></i>
                                            {$v.title}
                                        </h5>
                                    </a>
                                </div>
                            {/if}
                        {/foreach}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <p class="apps-heading">Available Apps</p>
                </div>
            </div>
            <div class="">
                <div class="">
                    {$int=0}
                    {foreach from=$services key=k item=v}
                        {if $v.status!=1}

                            <div class="row no-margin">
                                <div class="col-md-12 apps-shadow mb-2" style="background-color: #ffffff;">

                                    <div class="apps-box">
                                        <p class="apps-title"> {$v.title}</p>
                                        <div class="row no-margin">
                                            <div class="col-md-3 apps-left-box">
                                                <img class="img-responsive" src="{$v.icon}">
                                            </div>
                                            <div class="col-md-9 ">
                                                <div class="col-md-12 apps-description">
                                                    {$v.description}
                                                </div>
                                                {if $v.status==2}
                                                    <button disabled class="btn  pull-right mb-1">In Review</button>
                                                {else}
                                                    <a href="#confirm" onclick="document.getElementById('deleteanchor').href = '/merchant/profile/activate/{$v.encrypted_id}'"   data-toggle="modal"   class="btn blue pull-right mb-1">Activate Now</a>
                                                {/if}  
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>


                        {/if}
                    {/foreach}
                    {if $int==0}
                    </div>
                {/if}
            </div>
        </div>

    </div>

</div>	
<!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->
</div>

<div class="modal fade" id="basic" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Share details</h4>
            </div>
            <div class="modal-body" >
                <div class="row" id="text-data">
                    <div class="col-md-3">
                        <!-- Email -->
                        <a href="mailto:?Subject=Simple Share Buttons&amp;Body=I%20saw%20this%20and%20thought%20of%20you!%20 https://simplesharebuttons.com">
                            <img src="/assets/admin/layout/img/email.png" alt="Email" />
                        </a>
                    </div>
                    <div class="col-md-3">
                        <!-- Facebook -->
                        <a href="http://www.facebook.com/sharer.php?u=https://simplesharebuttons.com" target="_blank">
                            <img src="/assets/admin/layout/img/facebook.png" alt="Facebook" />
                        </a>
                    </div>
                    <div class="col-md-3">
                        <!-- Google+ -->
                        <a href="https://plus.google.com/share?url=https://simplesharebuttons.com" target="_blank">
                            <img src="/assets/admin/layout/img/google.png" alt="Google" />
                        </a>
                    </div>
                    <div class="col-md-3">
                        <!-- Whatsapp -->
                        <a href="https://api.whatsapp.com/send?text=hello&source=https://simplesharebuttons.com/images/somacro/yummly.png&data=https://simplesharebuttons.com/images/somacro/yummly.png">
                            <img style="max-height: 64px;" src="/assets/admin/layout/img/whatsapp.jpg" alt="Yummly" />
                        </a>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn default" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="confirm" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Activate Service</h4>
            </div>
            <div class="modal-body">
                Are you sure you would like to activate this service?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn default" data-dismiss="modal">Close</button>
                <a href="" id="deleteanchor" class="btn blue">Confirm</a>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script src="/assets/frontend/onepage2/scripts/layout.js" ></script>
{literal} 
    <script>
                                                        var element = document.getElementById('updates-div');
                                                        var positionInfo = element.getBoundingClientRect();
                                                        var height = positionInfo.height;
                                                        height = positionInfo.height + 82;
                                                        if (height > 500)
                                                        {
                                                            document.getElementById('services-div').style.maxHeight = height + "px";
                                                        }
    </script>
{/literal} 

