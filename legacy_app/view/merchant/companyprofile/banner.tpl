
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">Company profile&nbsp;
        {if $details.display_url!=''}
            <a href="/m/{$details.display_url}" class="btn blue pull-right mb-1" target="_BLANK"> View company profile</a>
        {/if}
    </h3>

    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row no-margin">
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
        {if isset($success)}
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert"></button>
                <strong></strong> {$success}
            </div> 
        {/if}
        <div class="col-md-12">

            <form enctype="multipart/form-data" action="{$post_url}" method="post" id="submit_form" class="form-horizontal form-row-sepe">
                {CSRF::create('companyprofile')}
                <div class="portlet light bordered">
                    <div class="portlet-body form">
                        <form action="#" id="template_create" >
                            <div class="form-body">
                                <div class="alert alert-danger display-none">
                                    <button class="close" data-dismiss="alert"></button>
                                    You have some form errors. Please check below.
                                </div>
                                <!-- image upload -->
                                <div class="row">
                                    <div class="col-md-12 fileinput fileinput-new banner-main"  data-provides="fileinput">
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div class="fileinput-preview thumbnail banner-container" style="min-width: 600px;" data-trigger="fileinput">
                                                {if $details.banner!=""}
                                                <img src="{if strpos($details.banner, '/')}/{$details.banner}{else}/uploads/images/landing/{$details.banner}{/if}" style="object-fit:fill"></div>
                                                {/if}
                                                <!-- <img src="{if $details.banner!=""}/uploads/images/landing/{$details.banner}{else}/assets/admin/layout/img/banner.jpg{/if}"></div> -->
                                            <div>
                                                <span class="btn default btn-file">
                                                    <span class="fileinput-new">
                                                        Upload banner </span>
                                                    <span class="fileinput-exists">
                                                        Change </span>
                                                    <input {if ($details.publishable==0)} disabled {/if} type="file" name="banner" accept="image/*">
                                                </span>
                                                <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput">
                                                    Remove </a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 fileinput fileinput-new logo-main" style="position: absolute;" data-provides="fileinput">
                                        <div class="fileinput-new thumbnail">
                                            {if $details.logo!=""}
                                            <img class="img-responsive" style="max-width: 300px;max-height:150px;" src="{if strpos($details.logo, '/')}/landingpage/default-logo.png{else}/uploads/images/landing/{$details.logo}{/if}" alt=""/>
                                            {/if}
                                            <!-- <img class="img-responsive" style="max-width: 300px;max-height:150px;" src="{if $details.logo!=""}/uploads/images/landing/{$details.logo}{else}/assets/admin/layout/img/nologo.gif{/if}" alt=""/> -->
                                        </div>

                                        <div  class="fileinput-preview fileinput-exists thumbnail logo-select">
                                        </div>
                                        <div>
                                            <span class="btn btn-sm default btn-file">
                                                <span class="fileinput-new">
                                                    Select logo </span>
                                                <span class="fileinput-exists">
                                                    Change </span>
                                                <input {if ($details.publishable==0)} disabled {/if} type="file" name="logo" accept="image/*">
                                            </span>
                                            <a href="javascript:;" class="btn-sm btn default fileinput-exists" data-dismiss="fileinput">
                                                Remove </a>
                                        </div>
                                    </div>
                                </div>







                                <div class="row " style="margin-top: 350px;">
                                    <ul class="mix-filter pull-right">
                                        <a href="/merchant/companyprofile"> <li class="filter {if $title=='Home'}active{/if}">
                                                Home
                                            </li></a>
                                        <a href="/merchant/companyprofile/policies"><li class="filter {if $title=='Policies'}active{/if}" >
                                                Policies
                                            </li></a>
                                        <a href="/merchant/companyprofile/aboutus"><li class="filter {if $title=='About us'}active{/if}" >
                                                About us
                                            </li></a>
                                        <a href="/merchant/companyprofile/contactus"><li class="filter {if $title=='Contact us'}active{/if}">
                                                Contact us
                                            </li></a>

                                    </ul>
                                </div>