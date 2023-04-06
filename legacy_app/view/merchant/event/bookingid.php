<html lang="en">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->
    <head>
        <meta charset="utf-8"/>
        <title>Company - Home</title>
        <meta name="description" content="">
        <meta name="author" content="Swipez">
        
        
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8">
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
        <link href="/assets/global/plugins/font-awesome/css/font-awesome.min.css?version=4.7" rel="stylesheet" type="text/css"/>
        <link href="/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/admin/layout/css/layout.css" rel="stylesheet" type="text/css"/>
        <link id="style_color" href="/assets/admin/layout/css/themes/blue.css<?php echo $this->fileTime('css', 'layout'); ?>" rel="stylesheet" type="text/css"/>
        <link href="/assets/admin/layout/css/custom.css" rel="stylesheet" type="text/css"/>
        <link rel="shortcut icon" href="/images/briq.ico"/>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/webrtc-adapter/3.3.3/adapter.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.1.10/vue.min.js"></script>
        <script type="text/javascript" src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
    </head>

    <body class="page-header-fixed page-quick-sidebar-over-content page-full-width">
        <!-- BEGIN HEADER -->
        <div class="page-header navbar navbar-fixed-top">
            <!-- BEGIN HEADER INNER -->
            <div class="page-header-inner">
                <!-- BEGIN LOGO -->
                <div class="page-logo">
                    <a href="">
                        <img src="/assets/admin/layout/img/logo.png?v=2"  alt="Swipez Online Payment" title="Swipez Online Payment Solutions"  style="max-height: 50px;" class="logo-default"/>
                    </a>
                </div>

            </div>
            <!-- END HEADER INNER -->
        </div>
        <!-- END HEADER -->
        <div class="clearfix">
        </div>
        <!-- BEGIN CONTAINER -->
        <div class="page-container">
            <!-- BEGIN SIDEBAR -->

            <!-- END SIDEBAR -->
            <!-- BEGIN CONTENT -->
            <div class="page-content-wrapper">
                <!-- BEGIN CONTENT -->
                <div class="page-content">
                    <br>
                    <!-- BEGIN PAGE HEADER-->
                    <!-- <h3 class="page-title">&nbsp</h3>-->
                    <!-- END PAGE HEADER-->
                    <!-- BEGIN PAGE CONTENT-->
                    <div class="row">
                        <div class="col-md-4"></div>
                        <div class="col-md-4">
                            <!-- BEGIN PROFILE SIDEBAR -->
                            <div class="profile-sidebar">
                                <!-- PORTLET MAIN -->
                                <div class="portlet light profile-sidebar-portlet">
                                    <!-- SIDEBAR USERPIC -->

                                    <div class="preview-container">
                                        <form action="/m/<?php echo $this->url; ?>/qrcodereceipt/bookingid" method="post">
                                            <div class="form-body">
                                                <div class="form-group">
                                                    <div class="col-md-12">
                                                        <div class="col-md-12" style="text-align: left;"><label class="control-label" >Booking ID<span class="required">* </span></label></div>
                                                        <div class="col-md-12">
                                                            <input type="text" style="width: 300px;" required maxlength="10" minlength="10" value="T000" name="booking_id" class="form-control">
                                                            <div class="help-block"></div>
                                                        </div>
                                                        <div class="col-md-4">

                                                            <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Next</button>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="profile-userbuttons">
                                        <br>
                                        <br>
                                        <a href="/merchant/dashboard"  class="btn btn-default green-haze btn-sm">Dashboard</a>
                                        <a href="/m/<?php echo $this->url; ?>/qrcode"  class="btn btn-circle btn-danger btn-sm">Scan QR Code</a>
                                    </div>
                                    <!-- END SIDEBAR BUTTONS -->
                                    <!-- SIDEBAR MENU -->


                                    <!-- END MENU -->
                                    <!-- END PORTLET MAIN -->
                                    <!-- PORTLET MAIN -->
                                </div>
                                <!-- END PORTLET MAIN -->
                            </div>
                            <!-- END BEGIN PROFILE SIDEBAR -->
                            <!-- BEGIN PROFILE CONTENT -->

                            <!-- END PROFILE CONTENT -->
                        </div>

                    </div>	
                    <!-- END PAGE CONTENT-->
                </div>
            </div>



            <!-- BEGIN FOOTER -->
            <div class="page-footer">
                <div class="page-footer-inner">
                    &copy; 2018 OPUS Net Pvt. Handmade in Pune.
                </div>
                <div class="scroll-to-top hidden-xs">
                    <i class="icon-arrow-up"></i>
                </div>
            </div>
            <!-- END JAVASCRIPTS -->
            <script type="text/javascript" src="/assets/admin/pages/scripts/qrcode.js"></script>
    </body>
    <!-- END BODY -->
</html>