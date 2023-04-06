<html lang="en">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->
    <head>
        <meta charset="utf-8"/>
        <title>Swipez | QR Code</title>
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
        <link href="/assets/admin/layout/css/custom.css" rel="stylesheet" type="text/css"/>
        <link rel="shortcut icon" href="/images/briq.ico"/>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/webrtc-adapter/3.3.3/adapter.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.1.10/vue.min.js"></script>
        <script type="text/javascript" src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
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
                    <!-- <h3 class="page-title">&nbsp</h3>-->
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
                                            <form action="/m/<?php echo $this->url;?>/qrcodereceipt/bookingid" method="post">
                                                <div class="form-body">
                                                    <div class="form-group">
                                                        <div class="col-md-12">
                                                            <div class="col-md-12 no-padding" style="text-align: left;"><label class="control-label" >Booking ID<span class="required">* </span></label></div>
                                                            <div class="col-xs-8 no-padding">
                                                                <input type="text" pattern="[\sa-zA-Z0-9]{10,10}" title="Enter 10 char Booking ID" required maxlength="10" value="T000" name="booking_id" class="form-control">
                                                                <div class="help-block"></div>
                                                            </div>
                                                            <div class="col-xs-4">
                                                                <button type="submit" class="btn btn-primary pull-left"><i class="fa fa-check"></i> Next</button>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                            <br>
                                            <br>
                                            <div class="form-body">
                                                <div class="form-group">
                                                    <div class="col-md-12">
                                                        <input type="hidden" id="url" value="<?php echo $this->url;?>"> 
                                                        <a href="/merchant/dashboard"  class="btn btn-default green-haze btn-sm"><< Dashboard</a>
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
            <script type="text/javascript" src="/assets/admin/pages/scripts/qrcodev1.js?v=3"></script>
    </body>
    <!-- END BODY -->
</html>