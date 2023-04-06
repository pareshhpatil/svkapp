<!DOCTYPE html>

<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->

<head>
    <meta charset="utf-8" />
    <title>GST filing information lookup</title>
    <meta name="description"
        content="View GST filing information of any GST number at a click of a button. Understand if your vendor company is filing their GST returns on time.">
    <meta name="author" content="Swipez">
    <style>
    </style>


    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    <!-- BEGIN GLOBAL MANDATORY STYLES -->

    <link href="/assets/global/plugins/font-awesome/css/font-awesome.min.css?version=4.7" rel="stylesheet"
        type="text/css" />
    <link href="/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/admin/layout/css/swipezapp.min.css?version=1649071087" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css" />

    <script src="/assets/admin/layout/scripts/plan.js?version=1643116259" type="text/javascript"></script>
    <!-- BEGIN PAGE LEVEL STYLES -->
    <link rel="stylesheet" type="text/css"
        href="/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" />
    <link href="/assets/admin/pages/css/portfolio.css" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL STYLES -->
    <!-- BEGIN THEME STYLES -->
    <!-- END THEME STYLES -->
    <link rel="shortcut icon" href="images/briq.ico" />
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <link href="/assets/admin/layout/css/custom.css?version=1649071087" rel="stylesheet" type="text/css" />
    <link href="/assets/admin/layout/css/movingintotailwind.css?version=1649071087" rel="stylesheet" type="text/css" />

    <link rel="shortcut icon" href="/images/briq.ico" />


</head>

<body class="page-header-fixed page-quick-sidebar-over-content page-full-width">
    <!-- BEGIN HEADER -->
    <div class="page-header navbar navbar-fixed-top">
        <!-- BEGIN HEADER INNER -->
        <div class="page-header-inner">
            <!-- BEGIN LOGO -->
            <div class="page-logo">
                <a target="_BLANK" href="">
                    <img src="/assets/admin/layout/img/logo.png?v=2" style="max-height: 50px;" alt="Swipez"
                        class="logo-default" />
                </a>
            </div>
            <!-- END LOGO -->
            <!-- BEGIN HORIZANTAL MENU -->
            <!-- DOC: Apply "hor-menu-light" class after the "hor-menu" class below to have a horizontal menu with white background -->
            <!-- DOC: This is desktop version of the horizontal menu. The mobile version is defined(duplicated) sidebar menu below. So the horizontal menu has 2 seperate versions -->
            <div class="hor-menu hor-menu-light hidden-sm hidden-xs pull-right">
                <ul class="nav navbar-nav">
                    <!-- DOC: Remove data-hover="dropdown" and data-close-others="true" attributes below to disable the horizontal opening on mouse hover -->

                    <li class="classic-menu-dropdown active">
                        <a href="#">
                            <h4>GST Information</h4>
                            <span class="selected"></span>
                        </a>
                    </li>

                </ul>
            </div>
            <!-- END HORIZANTAL MENU -->
            <!-- BEGIN HEADER SEARCH BOX -->
            <!-- DOC: Apply "search-form-expanded" right after the "search-form" class to have half expanded search box -->

            <!-- END HEADER SEARCH BOX -->
            <!-- BEGIN RESPONSIVE MENU TOGGLER -->
            <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse"
                data-target=".navbar-collapse">
            </a>
            <!-- END RESPONSIVE MENU TOGGLER -->
            <!-- BEGIN TOP NAVIGATION MENU -->

            <!-- END TOP NAVIGATION MENU -->
        </div>
        <!-- END HEADER INNER -->
    </div>
    <!-- END HEADER -->
    <div class="clearfix">
    </div>
    <!-- BEGIN CONTAINER -->
    <div class="page-container">
        <!-- BEGIN SIDEBAR -->
        <div class="page-sidebar-wrapper">
            <!-- BEGIN HORIZONTAL RESPONSIVE MENU -->
            <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
            <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
            <div class="page-sidebar navbar-collapse collapse">

            </div>
            <!-- END HORIZONTAL RESPONSIVE MENU -->
        </div>
        <!-- END SIDEBAR -->
        <!-- BEGIN CONTENT -->
        <div class="page-content-wrapper">
            <!-- BEGIN CONTENT -->
            <div class="page-content">
                <!-- BEGIN PAGE HEADER-->

                <!-- END PAGE HEADER-->

                <!-- BEGIN SEARCH CONTENT-->
                <!-- BEGIN SEARCH CONTENT-->

                <!-- BEGIN PAGE CONTENT-->
                <div class="row">
                    <div class="col-md-2"></div>
                    <div class="col-md-8">
                        {if isset($error)}
                            <div class="alert alert-danger alert-dismissable" style="">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                                <strong>Error!</strong> {$error}
                            </div>
                        {/if}

                        <div class="alert alert-danger" style="display: none;" id="errorshow">
                            <button class="close"
                                onclick="document.getElementById('errorshow').style.display = 'none';"></button>
                            <p id="error_display">You have some form errors. Please check below.</p>
                        </div>

                        <div class="formWpr">
                            <div class="row">
                                <div class="col-md-2"></div>
                                <div class="col-md-8">
                                    <div class="">

                                        <div class="portlet">

                                            <div class="portlet-body mb-2 pb-2">
                                                <h3 style="margin-top: 10px;;">GST filing information lookup</h3>
                                                <p>View GST filing information of any GST number at a click of a button.
                                                    Understand if your vendor company is filing their GST returns on
                                                    time.
                                                </p>

                                                <form class="form-inline" action="" method="post" role="form">
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group" style="width: 100%;">
                                                                <input placeholder="Enter GSTIN" style="width: 100%;font-size: 17px;" type="text" required
                                                                    name="gst_number" {$validate.gst_number}
                                                                    class="form-control " value="{$gst_number}">
                                                            </div>
                                                        </div>
                                                        <input type="hidden" id="captcha1" name="recaptcha_response">
                                                        <div class="col-md-4">
                                                            <input type="submit" class="btn blue pull-right"
                                                                value="Get details" />
                                                        </div>
                                                         </div>
                                                </form>

                                            </div>
                                        </div>



                                    </div>
                                </div>
                            </div>
                            <!--  formWpr end  -->
                            </section>
                            <section class="">
                                <!-- Content Header (Page header) -->
                                <div class="col-md-12">
                                    <!--
                        {$id=0}
                        {if isset($return_list.{$id}.dof)}
                            <div class="alert alert-block alert-info">
                                <table class="table" style="margin-bottom: 0;">
                                    <tr>
                                        <td style="border-top: none;">Last filing: {$return_list.{$id}.rtntype}</td>
                            {$date=$return_list.{$id}.ret_prd}
                    <td style="border-top: none;">Filed for: {$datearray.{$date|substr:0:2}}-{$date|substr:2}</td>
                    <td style="border-top: none;">Date of filing: {$return_list.{$id}.dof|date_format:"%d-%b-%Y"}</td>
                </tr>
            </table>
        </div>   
                        {/if}
                        -->
                                    {if isset($filereturn_success)}
                                        <div class="alert alert-block alert-success ml-0 mr-0">
                                            <p style="margin-bottom: 0;">{$filereturn_success}</p>
                                        </div>
                                    {/if}
                                    {if isset($filereturn_error)}
                                        <div class="alert alert-block alert-danger ml-0 mr-0">
                                            <p style="margin-bottom: 0;">{$filereturn_error}</p>
                                        </div>
                                    {/if}
                                    {if !empty($det)}
                                        <div class="form-section">GSTN Info</div>
                                        <div class="portlet">

                                            <div class="portlet-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <p></p>
                                                        <div class="col-md-6">
                                                            <p><b>Name of business :</b>
                                                                {if $det.tradename!=''}{$det.tradename}{else}{$det.name}{/if}
                                                            </p>
                                                            <p><b>Location :</b> {$det.pradr.loc}</p>
                                                            <p><b>Tax payer type :</b> {$det.type}</p>
                                                            <p><b>Status :</b> {$det.status}</p>
                                                            <p><b>Tax return status :</b> {$det.status}</p>
                                                            <p><b>Principle place of business :</b> {$det.pradr.bno},
                                                                {$det.pradr.st}, {$det.pradr.loc}, {$det.pradr.stcd}</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p><b>Constitution of business :</b> {$det.constitution}</p>
                                                            <p><b>Nature of business :</b> {$det.nature}</p>
                                                            <p><b>State jurisdiction :</b> {$det.state}</p>
                                                            <p><b>Center jurisdiction :</b> {$det.center}</p>
                                                            <p><b>Date of registration :</b>
                                                                {{$det.registrationDate|substr:0:10}|date_format:"%d-%b-%Y"}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    {/if}
                                    {if !empty($return_list)}
                                        <div class="form-section">GSTR3B filing status</div>
                                        <div class="portlet">
                                            <div class="portlet-body">
                                                <div class="row no-margin">
                                                    <div class="col-md-12" style="overflow: auto;max-height: 400px;">
                                                        <div class="">
                                                            <table class="table table-bordered table-no-export_class">
                                                                <thead>
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th>Date of filing</th>
                                                                        <th>Return period</th>
                                                                        <th>Return type</th>
                                                                        <th>Status</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    {foreach from=$return_list key=k item=v}
                                                                        {if $v.rtntype=='GSTR3B'}
                                                                            <tr>
                                                                                <td class="td-c">{$v.dof|date_format:"%Y-%m-%d"}
                                                                                </td>
                                                                                <td class="td-c">{$v.dof}</td>
                                                                                <td class="td-c">
                                                                                    {$datearray.{$v.ret_prd|substr:0:2}}-{$v.ret_prd|substr:2}
                                                                                </td>
                                                                                <td class="td-c">{$v.rtntype}</td>
                                                                                <td class="td-c">{$v.status}</td>
                                                                            </tr>
                                                                        {/if}
                                                                    {/foreach}
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    {/if}
                                    <br>
                                    {if !empty($return_list)}
                                        <div class="form-section">GSTR1 filing status</div>
                                        <div class="portlet">
                                            <div class="portlet-body">
                                                <div class="row no-margin">
                                                    <div class="col-md-12" style="overflow: auto;max-height: 400px;">
                                                        <div class="">
                                                            <table class="table table-bordered table-no-export_class">
                                                                <thead>
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th>Date of filing</th>
                                                                        <th>Return period</th>
                                                                        <th>Return type</th>
                                                                        <th>Status</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    {foreach from=$return_list key=k item=v}
                                                                        {if $v.rtntype=='GSTR1'}
                                                                            <tr>
                                                                                <td class="td-c">{$v.dof|date_format:"%Y-%m-%d"}
                                                                                </td>
                                                                                <td class="td-c">{$v.dof}</td>
                                                                                <td class="td-c">
                                                                                    {$datearray.{$v.ret_prd|substr:0:2}}-{$v.ret_prd|substr:2}
                                                                                </td>
                                                                                <td class="td-c">{$v.rtntype}</td>
                                                                                <td class="td-c">{$v.status}</td>
                                                                            </tr>
                                                                        {/if}
                                                                    {/foreach}
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    {/if}
                                </div>
                            </section>
                            </section>
                            <!-- /.content -->
                            </form>
                        </div>
                    </div>
                </div>
</div>