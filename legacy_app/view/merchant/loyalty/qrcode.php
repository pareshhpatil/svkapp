<html lang="en">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->
    <head>
        <meta charset="utf-8"/>
        <title>Swipez | QR Code</title>
        <link rel="canonical" href="http://www.swipez.prod/"/>
        <meta name="description" content="Swipez Scan QR code">
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
        <lin href="/assets/admin/layout/css/swipezapp.min.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/admin/layout/css/custom.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/admin/layout/css/movingintotailwind.css?version=1630416533" rel="stylesheet" type="text/css" />
        <link rel="shortcut icon" href="/favicon.ico"/>
        <script type="text/javascript" src="/assets/admin/pages/scripts/scanqr/adapter.min.js"></script>
        <script type="text/javascript" src="/assets/admin/pages/scripts/scanqr/vue.min.js"></script>
        <script type="text/javascript" src="/assets/admin/pages/scripts/scanqr/instascan.min.js"></script>
    </head>

    <body class="page-full-width" style="background-color: #ffffff;">
        <!-- BEGIN HEADER -->
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
                    <div class="page-bar">
                        <span class="page-title" style="float: left;">Scan QR code</span>
                        <i class="fa fa-bar"></i>
                        <ul class="page-breadcrumb">    
                            <li class="ms-hover">
                                <a href="/merchant/dashboard"><i class="fa fa-home"></i></a>
                                <i class="fa fa-angle-right"></i>
                            </li>
                            <li class="ms-hover">
                                Loyalty
                                <i class="fa fa-angle-right"></i>
                            </li>
                            <li class="ms-hover">
                                Scan QR code
                            </li>
                        </ul>
                    </div>
                    <!-- END PAGE HEADER-->
                    <!-- BEGIN PAGE CONTENT-->
                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-8">
                            <!-- BEGIN PROFILE SIDEBAR -->
                            <div class="profile-sidebar">
                                <!-- PORTLET MAIN -->
                                <div class="portlet light profile-sidebar-portlet">
                                    <!-- SIDEBAR USERPIC -->
                                    <div id="app">

                                        <div class="preview-container">
                                            <video style="width: 100%;max-height: 300px;" id="preview"></video>
                                            <section class="cameras">
                                                <div class="profile-usermenu"v-if="cameras.length === 0">
                                                    <ul class="nav">
                                                        <li  class="empty">
                                                            No cameras found
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="profile-usermenu" v-if="cameras.length > 1">
                                                    <ul class="nav" v-for="(camera,index) in cameras">
                                                        <li style="border: 1px solid lightgrey;position: relative;display: block;padding: 10px 15px;" v-if="camera.id == activeCameraId" :title="formatName(camera.name)"  class="">
                                                             Camera {{ index + 1 }}
                                                        </li>
                                                        <li style="border: 1px solid lightgrey;" v-if="camera.id != activeCameraId" :title="formatName(camera.name)" >
                                                            <a @click.stop="selectCamera(camera)">
                                                                Camera {{ index +1 }} </a>
                                                        </li>

                                                    </ul>
                                                </div>
                                            </section>
                                        </div>
                                        <div class="profile-userbuttons">
                                            <br>
                                            <br>
                                            <br>
                                            <br>
                                            
                                            <br>
                                            <br>
                                            <div class="form-body">
                                                <div class="form-group">
                                                    <div class="col-md-12">
                                                        <input type="hidden" id="url" value="<?php echo $this->url;?>"> 
                                                        <a href="/merchant/dashboard"  class="btn btn-blue btn-sm"><< Dashboard</a>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <!-- END SIDEBAR BUTTONS -->
                                        <!-- SIDEBAR MENU -->


                                        <!-- END MENU -->
                                    </div>
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
            
            <!-- END JAVASCRIPTS -->
            <script type="text/javascript" src="/assets/admin/pages/scripts/scanqr/qrcodev2.js"></script>
    </body>
    <!-- END BODY -->
</html>