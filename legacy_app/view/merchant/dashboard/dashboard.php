<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script src="//cdn.headwayapp.co/widget.js"></script>
<style>
    #notification-dropdown {
        list-style: none;
        padding-left: 0;
    }
    .notification-icon {
        margin-right: 10px;
        padding-top: 9px !important;
        padding-bottom: 9px !important;
    }
</style>
<script>
    // @see https://docs.headwayapp.co/widget for more configuration options.
    // var HW_UnseenCount = 0;
    // var config = {
    //     selector: ".badgeCont",
    //     trigger: ".toggleWidget",
    //     account: "JrXKbx",
    //     translations: {
    //         title: "New Features",
    //         readMore: "Read more",
    //         labels: {
    //             "new": "Feature",
    //             "improvement": "Improvement",
    //             "fix": "Fixes"
    //         },
    //         footer: "Read more 👉"
    //     },
    //     callbacks: {
    //         onWidgetReady: function(widget) {
    //             console.log("Widget is here!");
    //             console.log("unseen entries count: " + widget.getUnseenCount());
    //             HW_UnseenCount = widget.getUnseenCount();
    //         },
    //         onShowWidget: function() {
    //             console.log("Someone opened the widget!");
    //         },
    //         onShowDetails: function(changelog) {
    //             console.log(changelog.position); // position in the widget
    //             console.log(changelog.id); // unique id
    //             console.log(changelog.title); // title
    //             console.log(changelog.category); // category, lowercased
    //         },
    //         onReadMore: function(changelog) {
    //             console.log(changelog); // same changelog object as in onShowDetails callback
    //         },
    //         onHideWidget: function() {
    //             console.log("Who turned off the light?");
    //         }
    //     }
    // };
    // Headway.init(config);
    // console.log("unseen entries count: " + HW_UnseenCount);
</script>
<link href="/assets/admin/layout/css/timeline.css" rel="stylesheet" type="text/css" />
<?php $menu = $this->lang['title']; ?>

<div class="page-content">

    <div id="dashboard">
        <!-- BEGIN PAGE HEADER -->
        <?php dd("bnm"); if ($this->is_patron == 1) { ?>
            <h3 class="page-title ml-0">Dashboard</h3>
        <?php } else { ?>
            <div class="page-bar">
                <span class="page-title" style="float: left;">Dashboard</span>
                <?php if(count($this->currency)>1){?>
                    <div class="dropdown hidden-xs pull-right" style="margin-top:9px; margin-left:10px;">
                        <a href="javascript:;" class="dropdown-toggle blank white default-font" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                            <?php
                            echo $this->report_currency;
                            ?> &nbsp;&nbsp;<i class="fa fa-angle-down"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <?php foreach ($this->currency as $currency) { ?>
                                <?php if ($this->report_currency != $currency) { ?>
                                    <li class="">
                                        <a href="/merchant/dashboard/reportdays/<?php echo $currency;?>/report_currency">
                                            <?php echo $currency;?>
                                        </a>
                                    </li>
                                <?php } ?>
                            <?php } ?>
                        </ul>
                    </div>
                <?php } ?>
                <div class="dropdown hidden-xs pull-right" style="margin-top:9px;">
                    <a href="javascript:;" class="dropdown-toggle blank white default-font" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                        <?php if ($this->report_days != 1 && $this->report_days != 'today') { ?>
                            Last <?php echo $this->report_days; ?> days
                        <?php } else if ($this->report_days == 'today') {
                            echo 'Today';
                        } else {
                            echo 'Current month';
                        } ?> &nbsp;&nbsp;<i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <?php if ($this->report_days != 'today') { ?>
                            <li class="">
                                <a href="/merchant/dashboard/reportdays/today">
                                    Today
                                </a>
                            </li>
                        <?php } ?>
                        <?php if ($this->report_days != 7) { ?>
                            <li class="">
                                <a href="/merchant/dashboard/reportdays/7">
                                    Last 7 days
                                </a>
                            </li>
                        <?php } ?>
                        <?php if ($this->report_days != 30) { ?>
                            <li class="">
                                <a href="/merchant/dashboard/reportdays/30">
                                    Last 30 days
                                </a>
                            </li>
                        <?php } ?>
                        <?php if ($this->report_days != 60) { ?>
                            <li class="">
                                <a href="/merchant/dashboard/reportdays/60">
                                    Last 60 days
                                </a>
                            </li>
                        <?php } ?>
                        <?php if ($this->report_days != 90) { ?>
                            <li class="">
                                <a href="/merchant/dashboard/reportdays/90">
                                    Last 90 days
                                </a>
                            </li>
                        <?php } ?>
                        <?php if ($this->report_days != 1) { ?>
                            <li class="">
                                <a href="/merchant/dashboard/reportdays/1">
                                    Current month
                                </a>
                            </li>
                        <?php } ?>

                    </ul>

                </div>


                <?php if ($this->has_partner == 0) { ?>
                    <ul class="nav navbar-nav pull-right">
                        <li class="dropdown hidden-xs">
                            <a href="javascript:;" class="dropdown-toggle notification-icon" data-toggle="dropdown" data-hover="" data-close-others="true" aria-expanded="false">
                                <i class="fa fa-bell-o"></i>
                                <span>Notifications </span>
                            </a>
                            <div class="dropdown-menu">
                                <ul id="notification-dropdown">

                                </ul>
                                <div style="text-align: center;padding: 10px 0;">
                                    <a href="/merchant/notifications" target="_blank">View More</a>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <!--                    <a style="margin-r
                    ight:23px; font-weight: 400;font-size: 14px;" href="javascript:;" class="btn btn-link btn-sm toggleWidget pull-right">-->
                    <!--                        <i class="fa fa-bullhorn"></i>-->
                    <!--                        New features <span class="badgeCont pull-right"></span>-->
                    <!--                    </a>-->
                <?php } ?>


            </div>
        <?php } ?>

        <?php if (isset($this->successMessage)) { ?>
            <div class="alert alert-success ml-0">
                <button type="button" class="close" data-dismiss="alert"></button>
                <strong>Success!</strong><?php echo $this->successMessage; ?>
            </div>
        <?php } ?>

        <?php
        $int = 0;
        while (isset($this->notification[$int]['type'])) {
            if ($this->notification[$int]['type'] == 1) {
                ?>

            <?php } else if ($this->notification[$int]['type'] == 3) {
                ?>
                <div class="alert alert-info ml-0"><strong></strong> <a href="<?php echo $this->notification[$int]['link']; ?>" class="btn btn-xs blue"><?php echo $this->notification[$int]['message']; ?></a>
                </div>
                <?php
            }
            $int++;
        }
        ?>

        <section class="section">
            <div class="row sameheight-container">
                <?php if ($this->is_legal == 1 || $this->has_invoice == 1 || $this->isCompletedCompayPage == 1) { ?>
                    <!-- this card will appear if and only if user's profile is not completed -->
                    <!-- this card will appear if and only if user's profile is not completed -->
                    <!-- <div class="col col-12 col-md-4 mt-1">
                        <div class="card stats" data-exclude="xs">
                            <div class="card-block">
                                <div class="row row-sm stats-container">
                                    <div class="col-12">
                                        <div class="stat">
                                            <div style="color:#859494" class="page-title"> GETTING STARTED </div>
                                        </div>
                                        <div class="progress stat-progress getting-started-progress" style="height:17px;position:relative;margin-top:-5px;margin-bottom:-10px">
                                            <div class="progress-bar" style="width: <?= $this->circular_percentage == 0 ? '1' : $this->circular_percentage; ?>%; "></div>
                                            <h6 style="position:absolute;right:45%;top:-7px; color:white"><?= $this->circular_percentage; ?>%</h6>
                                        </div> -->
                    <!--
                <div class="col-12 no-padding">
                    <span class="label label-sm label-default pull-right pull-top-1">Last 30 days</span>
                </div>-->
                    <!-- <div class="mt-2 progress stat-progress">
                    <div class="progress-bar" style="width: 100%;"></div>
                </div> -->
                    <!--  </div>-->
                    <!-- <div class="col-12 mt-4">
                                        <div class="progress-card">
                                            <div class="progress-circle p<?= $this->circular_percentage; ?> <?php if ($this->circular_percentage > 50) { ?> over50 <?php }  ?>">
                                                <span><?= $this->completion_percentage; ?>%</span>
                                                <div class="left-half-clipper">
                                                    <div class="first50-bar"></div>
                                                    <div class="value-bar"></div>
                                                </div>
                                            </div>
                                            <div class="profile-steps mt-1">
                                                <div class="progress-text" style="display:flex">
                                                    <i class="fa fa-angle-right fab mr-1 step-icon"> </i>
                                                    <?php if ($this->entity_type == 1) { ?> <del> <?php } ?> <a href="/merchant/profile/complete/company" style="<?php if ($this->entity_type == 1) { ?>color:#6F8181<?php } ?>"> Company information </a> <?php if ($this->entity_type == 1) { ?> </del> <?php } ?>
                                                </div>
                                                <div class="progress-text" style="display:flex">
                                                    <i class="fa fa-angle-right fab mr-1 step-icon"> </i>
                                                    <?php if ($this->reg_city == 1) { ?> <del> <?php } ?> <a href="/merchant/profile/complete/contact" style="<?php if ($this->reg_city == 1) { ?>color:#6F8181<?php } ?>"> Contact details </a> <?php if ($this->reg_city == 1) { ?> </del> <?php } ?>
                                                </div>
                                                <div class="progress-text" style="display:flex">
                                                    <i class="fa fa-angle-right fab mr-1 step-icon"> </i>
                                                    <?php if ($this->is_legal == 1) { ?> <del> <?php } ?> <a href="/merchant/profile/complete/document" style="<?php if ($this->is_legal == 1) { ?>color:#6F8181<?php } ?>"> Online payments KYC </a> <?php if ($this->is_legal == 1) { ?> </del> <?php } ?>
                                                </div>
                                                <div class="progress-text" style="display:flex">
                                                    <i class="fa fa-angle-right fab mr-1 step-icon"> </i>
                                                    <?php if ($this->bank_verified == 1) { ?> <del> <?php } ?> <a href="/merchant/profile/complete/bank" style="<?php if ($this->bank_verified == 1) { ?>color:#6F8181<?php } ?>"> Activate payments </a> <?php if ($this->bank_verified == 1) { ?> <del> <?php } ?>
                                                </div>


                                                <div class="progress-text" style="display:flex">
                                                    <i class="fa fa-angle-right fab mr-1 step-icon"> </i>
                                                    <?php if ($this->has_invoice > 0) { ?> <del> <?php } ?> <a href="/merchant/invoice/create" style="<?php if ($this->has_invoice > 0) { ?>color:#6F8181<?php } ?>"> Send invoice </a> <?php if ($this->has_invoice > 0) { ?> <del> <?php } ?>
                                                </div>
                                                <div class="progress-text" style="display:flex">
                                                    <i class="fa fa-angle-right fab mr-1 step-icon"> </i>
                                                    <?php if ($this->isCompletedCompayPage == 1) { ?> <del> <?php } ?> <a href="/m/<?php echo $this->display_url ?>" style="<?php if ($this->isCompletedCompayPage == 1) { ?>color:#6F8181<?php } ?>" target="_blank">View your website </a> <?php if ($this->isCompletedCompayPage == 1) { ?> <del> <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div> -->
                    <!-- </div>
                </div>
            </div>
        </div> -->
                <?php } ?>
                <!--  -->
                <div class="col col-12 col-md-6 mt-1">
                    <div class="card stats" data-exclude="xs">
                        <div class="card-block">
                            <div class="row row-sm stats-container">
                                <div class="col-12">
                                    <div class="stat-icon">
                                        <i class="fa fa-users fab"></i>
                                    </div>
                                    <div class="stat">
                                        <div class="value"> <?php echo $this->total_customer_display; ?> </div>
                                        <div class="name"> Total Customers </div>
                                    </div>
                                    <div class="progress stat-progress">
                                        <div class="progress-bar" style="width: 100%;"></div>
                                    </div>
                                </div>
                                <div class="col-12 mt-4">
                                    <div class="stat-icon">
                                        <i class="fa fa-line-chart fab"></i>
                                    </div>
                                    <div class="stat mt-4">
                                        <div class="value"> <?php echo $this->currency_icon; ?> <?php echo $this->total_invoice_sum; ?> </div>
                                        <div class="name"> Invoices Raised
                                        </div>
                                    </div>
                                    <!--
                                    <div class="col-12 no-padding">
                                        <span class="label label-sm label-default pull-right pull-top-1">Last 30 days</span>
                                    </div>-->
                                    <div class="progress stat-progress">
                                        <div class="progress-bar" style="width: 100%;"></div>
                                    </div>

                                </div>
                                <div class="col-12 mt-4">
                                    <div class="stat-icon">
                                        <i class="fa fa-thumbs-up fab"></i>
                                    </div>
                                    <div class="stat mt-4">
                                        <div class="value"> <?php echo $this->currency_icon; ?> <?php echo $this->total_paid_sum; ?> </div>
                                        <div class="name"> Paid Invoices </div>
                                    </div>
                                    <!--
                                    <div class="col-12 no-padding">
                                        <span class="label label-sm label-default pull-right pull-top-1">Last 30 days</span>
                                    </div>-->
                                    <div class="progress stat-progress">
                                        <div class="progress-bar" style="width: <?php echo $this->paid_per; ?>%;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col col-12 col-md-6 mt-1">
                    <div class="card stats" data-exclude="xs">
                        <div class="card-block">
                            <div class="row row-sm stats-container">
                                <div class="col-12">
                                    <div class="stat-icon">
                                        <i class="fa fa-thumbs-up fab"></i>
                                    </div>
                                    <div class="stat">
                                        <div class="value"> <?php echo $this->currency_icon; ?> <?php echo $this->transaction; ?> </div>
                                        <div class="name"> Funds collected online </div>
                                    </div>
                                    <!--
                                    <div class="col-12 no-padding">
                                        <span class="label label-sm label-default pull-right pull-top-1">Last 30 days</span>
                                    </div>-->
                                    <div class="progress stat-progress">
                                        <div class="progress-bar" style="width: 100%;"></div>
                                    </div>
                                </div>
                                <div class="col-12 mt-4">
                                    <div class="stat-icon">
                                        <i class="fa fa-check-square fab"></i>
                                    </div>
                                    <div class="stat mt-4">
                                        <div class="value"> <?php echo $this->currency_icon; ?> <?php echo $this->settlement; ?> </div>
                                        <div class="name"> Settlements received </div>
                                    </div>
                                    <!--
                                    <div class="col-12 no-padding">
                                        <span class="label label-sm label-default pull-right pull-top-1">Last 30 days</span>
                                    </div>-->
                                    <div class="progress stat-progress">
                                        <div class="progress-bar" style="width: <?php echo $this->settlement_per; ?>%;"></div>
                                    </div>
                                </div>
                                <div class="col-12 mt-4">
                                    <div class="stat-icon">
                                        <i class="fa fa-university fab"></i>
                                    </div>
                                    <div class="stat mt-4">
                                        <div class="value"> <?php echo $this->currency_icon; ?> <?php echo $this->pending_settlement; ?> </div>
                                        <div class="name"> Pending Settlements </div>
                                    </div>
                                    <!--
                                    <div class="col-12 no-padding">
                                        <span class="label label-sm label-default pull-right pull-top-1">Last 30 days</span>
                                    </div>-->
                                    <div class="progress stat-progress">
                                        <div class="progress-bar" style="width: <?php echo 100 - $this->settlement_per; ?>%;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        

        <?php if (!empty($this->merchant_notification)) { ?>
            <div class="row">
                <div class="col-lg-12">
                    <div class="portlet light bordered">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon-share font-dark hide"></i>
                                <span class="caption-subject font-dark bold uppercase"><?php echo $menu['notification']; ?></span>
                            </div>
                            <?php if ($this->has_partner == 0) { ?>
                                <div class="actions">
                                    <a href="javascript:;" class="btn blue btn-sm toggleWidget">
                                        <i class="fa fa-thumbs-up"></i>
                                        New features <span class="badgeCont pull-right"></span>
                                    </a>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="portlet-body">
                            <div class="scroller" data-always-visible="1" data-rail-visible="0">
                                <ul class="feeds">
                                    <?php foreach ($this->merchant_notification as $row) { ?>
                                        <li>
                                            <div class="col1">
                                                <div class="cont">
                                                    <div class="cont-col2">
                                                        <div class="desc"> <?php echo $row['message']; ?>
                                                            <?php if ($row['link'] != '') { ?>
                                                                <a href="<?php echo $row['link']; ?>" target="_BLANK">
                                                                    <span class="label label-sm label-warning "> <?php echo $row['link_text']; ?>
                                                                        <i class="fa fa-share"></i>
                                                                    </span>
                                                                </a>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col2">
                                                <div class="date"> <?php echo $row['time']; ?> </div>
                                            </div>
                                        </li>
                                    <?php } ?>

                                </ul>
                            </div>
                            <!--<div class="scroller-footer">
                                <div class="btn-arrow-link pull-right">
                                    <a href="/merchant/dashboard/notifications">See All Records</a>
                                    <i class="icon-arrow-right"></i>
                                </div>
                            </div>-->
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
        <!-- END PAGE HEADER-->
        <?php if ($this->chart_display == TRUE) { ?>
            <div class="row">
                <div class="col-md-6 col-sm-6">
                    <!-- BEGIN PORTLET-->
                    <div class="portlet light bordered">
                        <div class="portlet-title">
                            <div class="caption">
                                <a class="caption-subject font-green-sharp bold uppercase" href="/merchant/chart/paymentreceived"><?php echo $menu['payment_received']; ?></a>
                            </div>
                            <div class="pull-right"> <a href="/merchant/chart/paymentreceived"><i class="fa  fa-arrow-circle-o-right fa-lg"></i></a></div>
                        </div>
                        <div class="portlet-body">
                            <iframe style="width: 100%;height: 310px;" src="/merchant/chart/paymentreceived/1"></iframe>
                        </div>
                    </div>
                    <!-- END PORTLET-->
                </div>
                <div class="col-md-6 col-sm-6">
                    <!-- BEGIN PORTLET-->
                    <div class="portlet light bordered">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon-share font-red-sunglo hide"></i>
                                <a class="caption-subject font-red-sunglo bold uppercase" href="/merchant/chart/billingstatus"><?php echo $menu['billing_status']; ?></a>

                            </div>
                            <div class="pull-right"> <a href="/merchant/chart/billingstatus"><i class="fa  fa-arrow-circle-o-right fa-lg"></i></a></div>
                        </div>
                        <div class="portlet-body">
                            <iframe style="width: 100%;height: 310px;" src="/merchant/chart/billingstatus/1"></iframe>
                        </div>
                    </div>
                    <!-- END PORTLET-->
                </div>
            </div>
        <?php } ?>

        <div class="row">

            <?php if ($this->chart_display == TRUE) { ?>
                <div class="col-lg-4">
                    <div class="dashboard-stat blue-madison">
                        <div class="visual">
                            <i class="fa fa-pie-chart"></i>
                        </div>
                        <div class="details">
                            <div class="number">

                            </div>
                            <div class="desc">
                                Particulars break-up
                            </div>
                        </div>
                        <a class="more" href="/merchant/chart/particularsummary">
                            View more <i class="fa  fa-arrow-circle-o-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="dashboard-stat blue-hoki">
                        <div class="visual">
                            <i class="fa fa-area-chart"></i>
                        </div>
                        <div class="details">
                            <div class="number">

                            </div>
                            <div class="desc">
                                Tax break-up
                            </div>
                        </div>
                        <a class="more" href="/merchant/chart/taxsummary">
                            View more <i class="fa  fa-arrow-circle-o-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="dashboard-stat blue-steel">
                        <div class="visual">
                            <i class="fa fa-credit-card"></i>
                        </div>
                        <div class="details">
                            <div class="number">

                            </div>
                            <div class="desc">
                                Payment mode
                            </div>
                        </div>
                        <a class="more" href="/merchant/chart/paymentmode">
                            View more <i class="fa  fa-arrow-circle-o-right"></i>
                        </a>
                    </div>
                </div>

            <?php } ?>
        </div>
    </div>
</div>
</div>


</div>
</div>
<?php
if ($this->document_upload == true) {
    ?>
    <a data-toggle="modal" id="cashfree" href="#document"></a>
    <div class="modal fade" id="document" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="/merchant/profile/documentsave" id="documentForm" method="post" enctype="multipart/form-data" class="form-horizontal form-row-sepe">
                    <div class="modal-header">
                        <h4 class="modal-title">Upload your documents</h4>
                    </div>
                    <div class="">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="">
                                    <div class="portlet-body form">
                                        <!--<h3 class="form-section">Profile details</h3>-->

                                        <div class="form-body">
                                            <!-- Start profile details -->
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="alert alert-info">
                                                        <h4><b>0% online transaction fees</b></h4>
                                                        <p>Upload your documents and decrease your online transaction charges. Avail 0% charges on debit cards less than <?php echo $this->currency_icon; ?>2000 by simply uploading your business documentation.</p>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label col-md-5">Business type <span class="required">*
                                                            </span></label>
                                                        <div class="col-md-4">
                                                            <select name="biz_type" class="form-control" required onchange="BizType(this.value);" data-placeholder="Select...">
                                                                <option value="">Select business type</option>
                                                                <option <?php
                                                                if ($this->merchant_det['entity_type'] == 2) {
                                                                    echo 'selected';
                                                                }
                                                                ?> value="2">Pvt. Ltd.</option>
                                                                <option <?php
                                                                if ($this->merchant_det['entity_type'] == 4) {
                                                                    echo 'selected';
                                                                }
                                                                ?> value="4">Proprietorship</option>
                                                                <option <?php
                                                                if ($this->merchant_det['entity_type'] == 3) {
                                                                    echo 'selected';
                                                                }
                                                                ?> value="3">LLP (Partnership)</option>
                                                            </select>
                                                        </div>
                                                    </div>


                                                    <div id="gstav">
                                                        <div class="form-group">
                                                            <label class="control-label col-md-5">GST available <span class="required">*
                                                                </span>
                                                            </label>
                                                            <div class="col-md-3">
                                                                <input type="checkbox" <?php
                                                                if ($this->bank_detail['gst_available'] == 1) {
                                                                    echo 'checked';
                                                                }
                                                                ?> id="gst" onchange="gstavailable();" name="gst_available" value="1" class="make-switch" data-on-text="&nbsp;Yes&nbsp;&nbsp;" data-off-text="&nbsp;No&nbsp;">
                                                            </div>
                                                        </div>
                                                        <?php if ($this->bank_detail['gst_certificate'] != '') { ?>
                                                            <label class="control-label col-md-5">GST certificate
                                                            </label>
                                                            <div class="col-md-6">
                                                                <span class="help-block"><a class="btn btn-xs green" target="_BLANK" href="/uploads/documents/<?php echo $this->bank_detail['merchant_id'] . '/' . $this->bank_detail['gst_certificate']; ?>">View doc</a>
                                                                    <a onclick="updateDoc(2, 1);" class="btn btn-xs blue">Update</a>
                                                                </span>
                                                                <span id="update2" style="display: none;">
                                                                    <input type="file" accept="image/*,application/pdf" onchange="validatefilesize(1000000, 'a2');
                                                                            submitDoc();" id="a2" name="doc_gst_cer">
                                                                    <span class="help-block red">* Max file size 1 MB
                                                                        <a onclick="updateDoc(2, 0);" class="btn btn-xs red"><i class="fa fa-remove"></i></a>
                                                                    </span>
                                                                </span>
                                                            </div>
                                                        <?php } else { ?>
                                                            <div id="gstdiv" <?php
                                                            if ($this->bank_detail['gst_available'] == 0) {
                                                                echo 'style="display: none;"';
                                                            }
                                                            ?>>
                                                                <div class="form-group">
                                                                    <label class="control-label col-md-5">GST certificate <span class="required">*
                                                                        </span>
                                                                    </label>
                                                                    <div class="col-md-3">
                                                                        <input type="file" accept="image/*,application/pdf" onchange="validatefilesize(1000000, 'a2');
                                                                                submitDoc();" id="a2" name="doc_gst_cer">
                                                                        <span class="help-block red">* Max file size 1 MB</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                    <?php if ($this->bank_detail['cancelled_cheque'] != '') { ?>
                                                        <label class="control-label col-md-5">Cancelled cheque/ account statement
                                                        </label>
                                                        <div class="col-md-6">
                                                            <span class="help-block"><a class="btn btn-xs green" target="_BLANK" href="/uploads/documents/<?php echo $this->bank_detail['merchant_id'] . '/' . $this->bank_detail['cancelled_cheque']; ?>">View doc</a>
                                                                <a onclick="updateDoc(1, 1);" class="btn btn-xs blue">Update</a>
                                                            </span>
                                                            <span id="update1" style="display: none;">
                                                                <input type="file" accept="image/*,application/pdf" onchange="validatefilesize(1000000, 'a1');
                                                                        submitDoc();" id="a1" name="doc_cancelled_cheque">
                                                                <span class="help-block red">* Max file size 1 MB
                                                                    <a onclick="updateDoc(1, 0);" class="btn btn-xs red"><i class="fa fa-remove"></i></a>
                                                                </span>
                                                            </span>
                                                        </div>
                                                    <?php } else { ?>
                                                        <div id="ccheque" class="form-group">
                                                            <label class="control-label col-md-5">Cancelled cheque/ account statement <span class="required">*
                                                                </span>
                                                            </label>
                                                            <div class="col-md-6">
                                                                <input type="file" accept="image/*,application/pdf" onchange="validatefilesize(1000000, 'a1');
                                                                        submitDoc();" id="a1" name="doc_cancelled_cheque">
                                                                <span class="help-block red">* Max file size 1 MB</span>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                    <div id="pvt" style="display: none;">
                                                        <?php
                                                        if (!empty($this->bank_detail['address_proof'])) {
                                                            foreach ($this->bank_detail['address_proof'] as $key => $val) {
                                                                $int = $key + 68;
                                                                ?>
                                                                <div id="address1">
                                                                    <label class="control-label col-md-5">Directors address proof (Aadhaar/DL/Passport)
                                                                    </label>
                                                                    <div class="col-md-5">
                                                                        <span class="help-block"><a class="btn btn-xs green" target="_BLANK" href="/uploads/documents/<?php echo $this->bank_detail['merchant_id'] . '/' . $val; ?>">View doc</a>
                                                                            <a onclick="updateDoc(<?php echo $int; ?>, 1);" class="btn btn-xs blue">Update</a>
                                                                        </span>
                                                                        <span id="update<?php echo $int; ?>" style="display: none;">
                                                                            <input type="file" accept="image/*,application/pdf" onchange="validatefilesize(1000000, 'a3');
                                                                                    submitDoc();" id="a3" name="address_prrof[]">
                                                                            <span class="help-block red">* Max file size 1 MB
                                                                                <a onclick="updateDoc(<?php echo $int; ?>, 0);" class="btn btn-xs red"><i class="fa fa-remove"></i></a>
                                                                            </span>
                                                                        </span>
                                                                    </div>
                                                                    <div class="col-md-1">
                                                                        <a onclick="addMoreAddressProof(1, 'Directors');" class="btn btn-sm blue">Add More</a>
                                                                    </div>
                                                                </div>
                                                                <?php
                                                            }
                                                        } else {
                                                            ?>
                                                            <?php if ($int == 68) { ?>
                                                                <div id="address1">
                                                            <?php } ?>

                                                            <label class="control-label col-md-5">Directors address proof (Aadhaar/DL/Passport) <span class="required">*
                                                                    </span>
                                                            </label>
                                                            <div class="col-md-5">
                                                                <input type="file" accept="image/*,application/pdf" onchange="validatefilesize(1000000, 'a3');
                                                                            submitDoc();" id="a3" name="address_prrof[]">
                                                                <span class="help-block red">* Max file size 1 MB</span>
                                                            </div>
                                                            <?php if ($int == 68) { ?>
                                                                <div class="col-md-1">
                                                                    <a onclick="addMoreAddressProof(1, 'Directors');" class="btn btn-sm blue">Add More</a>
                                                                </div>
                                                                </div>
                                                            <?php } ?>

                                                        <?php } ?>
                                                        <?php if ($this->bank_detail['company_incorporation_certificate'] != '') { ?>
                                                            <label class="control-label col-md-5">Company incorporation certificate
                                                            </label>
                                                            <div class="col-md-6">
                                                                <span class="help-block"><a class="btn btn-xs green" target="_BLANK" href="/uploads/documents/<?php echo $this->bank_detail['merchant_id'] . '/' . $this->bank_detail['company_incorporation_certificate']; ?>">View doc</a>
                                                                    <a onclick="updateDoc(4, 1);" class="btn btn-xs blue">Update</a>
                                                                </span>
                                                                <span id="update4" style="display: none;">
                                                                    <input type="file" accept="image/*,application/pdf" onchange="validatefilesize(1000000, 'a4');
                                                                            submitDoc();" id="a4" name="company_certificate">
                                                                    <span class="help-block red">* Max file size 1 MB
                                                                        <a onclick="updateDoc(4, 0);" class="btn btn-xs red"><i class="fa fa-remove"></i></a>
                                                                    </span>
                                                                </span>
                                                            </div>
                                                        <?php } else { ?>
                                                            <div class="form-group">
                                                                <label class="control-label col-md-5">Company incorporation certificate<span class="required">*
                                                                    </span>
                                                                </label>
                                                                <div class="col-md-6">
                                                                    <input type="file" accept="image/*,application/pdf" onchange="validatefilesize(1000000, 'a4');
                                                                            submitDoc();" id="a4" name="company_certificate">
                                                                    <span class="help-block red">* Max file size 1 MB</span>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>

                                                    <div id="propriter" style="display: none;">


                                                        <?php if ($this->bank_detail['adhar_card'] != '') { ?>
                                                            <label class="control-label col-md-5">Proprietors address proof (Aadhaar/DL/Passport)
                                                            </label>
                                                            <div class="col-md-6">
                                                                <span class="help-block"><a class="btn btn-xs green" target="_BLANK" href="/uploads/documents/<?php echo $this->bank_detail['merchant_id'] . '/' . $this->bank_detail['adhar_card']; ?>">View doc</a>
                                                                    <a onclick="updateDoc(6, 1);" class="btn btn-xs blue">Update</a>
                                                                </span>
                                                                <span id="update6" style="display: none;">
                                                                    <input type="file" accept="image/*,application/pdf" onchange="validatefilesize(1000000, 'a6');
                                                                            submitDoc();" id="a6" name="doc_adhar_card">
                                                                    <span class="help-block red">* Max file size 1 MB
                                                                        <a onclick="updateDoc(6, 0);" class="btn btn-xs red"><i class="fa fa-remove"></i></a>
                                                                    </span>
                                                                </span>
                                                            </div>
                                                        <?php } else { ?>
                                                            <div class="form-group">
                                                                <label class="control-label col-md-5">Proprietors address proof (Aadhaar/DL/Passport)<span class="required">*
                                                                    </span>
                                                                </label>
                                                                <div class="col-md-6">
                                                                    <input type="file" accept="image/*,application/pdf" onchange="validatefilesize(1000000, 'a6');
                                                                            submitDoc();" id="a6" name="doc_adhar_card">
                                                                    <span class="help-block red">* Max file size 1 MB</span>
                                                                </div>
                                                            </div>
                                                        <?php } ?>


                                                        <?php if ($this->bank_detail['pan_card'] != '') { ?>
                                                            <label class="control-label col-md-5">Pan card
                                                            </label>
                                                            <div class="col-md-6">
                                                                <span class="help-block"><a class="btn btn-xs green" target="_BLANK" href="/uploads/documents/<?php echo $this->bank_detail['merchant_id'] . '/' . $this->bank_detail['pan_card']; ?>">View doc</a>
                                                                    <a onclick="updateDoc(6, 1);" class="btn btn-xs blue">Update</a>
                                                                </span>
                                                                <span id="update6" style="display: none;">
                                                                    <input type="file" accept="image/*,application/pdf" onchange="validatefilesize(1000000, 'a7');
                                                                            submitDoc();" id="a7" name="doc_pan_card">
                                                                    <span class="help-block red">* Max file size 1 MB
                                                                        <a onclick="updateDoc(6, 0);" class="btn btn-xs red"><i class="fa fa-remove"></i></a>
                                                                    </span>
                                                                </span>
                                                            </div>
                                                        <?php } else { ?>
                                                            <div class="form-group">
                                                                <label class="control-label col-md-5">Pan card<span class="required">*
                                                                    </span>
                                                                </label>
                                                                <div class="col-md-6">
                                                                    <input type="file" accept="image/*,application/pdf" onchange="validatefilesize(1000000, 'a7');
                                                                            submitDoc();" id="a7" name="doc_pan_card">
                                                                    <span class="help-block red">* Max file size 1 MB</span>
                                                                </div>
                                                            </div>
                                                        <?php } ?>


                                                    </div>

                                                    <div id="llp" style="display: none;">


                                                        <?php
                                                        if (!empty($this->bank_detail['address_proof'])) {
                                                            foreach ($this->bank_detail['address_proof'] as $key => $val) {
                                                                $int = $key + 28;
                                                                ?>
                                                                <?php if ($int == 28) { ?>
                                                                    <div id="address3">
                                                                <?php } ?>
                                                                <label class="control-label col-md-5">Partners address proof (Aadhaar/DL/Passport)
                                                                </label>
                                                                <div class="col-md-5">
                                                                        <span class="help-block"><a class="btn btn-xs green" target="_BLANK" href="/uploads/documents/<?php echo $this->bank_detail['merchant_id'] . '/' . $val; ?>">View doc</a>
                                                                            <a onclick="updateDoc(<?php echo $int; ?>, 1);" class="btn btn-xs blue">Update</a>
                                                                        </span>
                                                                    <span id="update<?php echo $int; ?>" style="display: none;">
                                                                            <input type="file" accept="image/*,application/pdf" onchange="validatefilesize(1000000, 'a<?php echo $int; ?>');
                                                                                    submitDoc();" id="a<?php echo $int; ?>" name="address_prrof[]">
                                                                            <span class="help-block red">* Max file size 1 MB
                                                                                <a onclick="updateDoc(<?php echo $int; ?>, 0);" class="btn btn-xs red"><i class="fa fa-remove"></i></a>
                                                                            </span>
                                                                        </span>
                                                                </div>
                                                                <?php if ($int == 28) { ?>
                                                                    <div class="col-md-1">
                                                                        <a onclick="addMoreAddressProof(3, 'Partners');" class="btn btn-sm blue">Add More</a>
                                                                    </div>
                                                                    </div>
                                                                <?php } ?>

                                                                <?php
                                                            }
                                                        } else {
                                                            ?>
                                                            <div id="address3">
                                                                <div class="form-group">
                                                                    <label class="control-label col-md-5">Partners address proof (Aadhaar/DL/Passport) <span class="required">*
                                                                        </span>
                                                                    </label>
                                                                    <div class="col-md-5">
                                                                        <input type="file" accept="image/*,application/pdf" onchange="validatefilesize(1000000, 'a9');
                                                                                submitDoc();" id="a9" name="address_prrof[]">
                                                                        <span class="help-block red">* Max file size 1 MB</span>
                                                                    </div>
                                                                    <div class="col-md-1">
                                                                        <a onclick="addMoreAddressProof(3, 'Partners');" class="btn btn-sm blue">Add More</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                        <?php
                                                        if (!empty($this->bank_detail['partner_pan_card'])) {
                                                            foreach ($this->bank_detail['partner_pan_card'] as $key => $val) {
                                                                $int = $key + 48;
                                                                ?>
                                                                <?php if ($int == 48) { ?>
                                                                    <div id="addpan">
                                                                <?php } ?>
                                                                <label class="control-label col-md-5">Partners pan card
                                                                </label>
                                                                <div class="col-md-5">
                                                                        <span class="help-block"><a class="btn btn-xs green" target="_BLANK" href="/uploads/documents/<?php echo $this->bank_detail['merchant_id'] . '/' . $val; ?>">View doc</a>
                                                                            <a onclick="updateDoc(<?php echo $int; ?>, 1);" class="btn btn-xs blue">Update</a>
                                                                        </span>
                                                                    <span id="update<?php echo $int; ?>" style="display: none;">
                                                                            <input type="file" accept="image/*,application/pdf" onchange="validatefilesize(1000000, 'a<?php echo $int; ?>');
                                                                                    submitDoc();" id="a<?php echo $int; ?>" name="partner_pan_card[]">
                                                                            <span class="help-block red">* Max file size 1 MB
                                                                                <a onclick="updateDoc(<?php echo $int; ?>, 0);" class="btn btn-xs red"><i class="fa fa-remove"></i></a>
                                                                            </span>
                                                                        </span>
                                                                </div>
                                                                <?php if ($int == 48) { ?>
                                                                    <div class="col-md-1">
                                                                        <a onclick="addMorePanCard();" class="btn btn-sm blue">Add More</a>
                                                                    </div>
                                                                    </div>
                                                                <?php } ?>
                                                                <?php
                                                            }
                                                        } else {
                                                            ?>
                                                            <div id="addpan">
                                                                <div id="partnerpancard" class="form-group">
                                                                    <label class="control-label col-md-5">Partners pan card<span class="required">*
                                                                        </span>
                                                                    </label>
                                                                    <div class="col-md-5">
                                                                        <input type="file" accept="image/*,application/pdf" onchange="validatefilesize(1000000, 'a10');
                                                                                submitDoc();" id="a10" name="partner_pan_card[]">
                                                                        <span class="help-block red">* Max file size 1 MB</span>
                                                                    </div>
                                                                    <div class="col-md-1">
                                                                        <a onclick="addMorePanCard();" class="btn btn-sm blue">Add More</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php } ?>



                                                        <?php if ($this->bank_detail['partnership_deed'] != '') { ?>
                                                            <label class="control-label col-md-5">Partnership deed
                                                            </label>
                                                            <div class="col-md-6">
                                                                <span class="help-block"><a class="btn btn-xs green" target="_BLANK" href="/uploads/documents/<?php echo $this->bank_detail['merchant_id'] . '/' . $this->bank_detail['partnership_deed']; ?>">View doc</a>
                                                                    <a onclick="updateDoc(56, 1);" class="btn btn-xs blue">Update</a>
                                                                </span>
                                                                <span id="update56" style="display: none;">
                                                                    <input type="file" accept="image/*,application/pdf" onchange="validatefilesize(1000000, 'a11');
                                                                            submitDoc();" id="a11" name="partnership_deed">
                                                                    <span class="help-block red">* Max file size 1 MB
                                                                        <a onclick="updateDoc(56, 0);" class="btn btn-xs red"><i class="fa fa-remove"></i></a>
                                                                    </span>
                                                                </span>
                                                            </div>
                                                        <?php } else { ?>
                                                            <div id="pancard" class="form-group">
                                                                <label class="control-label col-md-5">* Partnership deed<span class="required">*
                                                                    </span>
                                                                </label>
                                                                <div class="col-md-6">
                                                                    <input type="file" accept="image/*,application/pdf" onchange="validatefilesize(1000000, 'a11');
                                                                            submitDoc();" id="a11" name="partnership_deed">
                                                                    <span class="help-block red">* Max file size 1 MB</span>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                    <?php if ($this->bank_detail['business_reg_proof'] != '') { ?>
                                                        <label class="control-label col-md-5">Business registration proof (Shop act, IE certificate)
                                                        </label>
                                                        <div class="col-md-6">
                                                            <span class="help-block"><a class="btn btn-xs green" target="_BLANK" href="/uploads/documents/<?php echo $this->bank_detail['merchant_id'] . '/' . $this->bank_detail['business_reg_proof']; ?>">View doc</a>
                                                                <a onclick="updateDoc(5, 1);" class="btn btn-xs blue">Update</a>
                                                            </span>
                                                            <span id="update5" style="display: none;">
                                                                <input type="file" accept="image/*,application/pdf" onchange="validatefilesize(1000000, 'a5');
                                                                        submitDoc();" id="a5" name="biz_reg_proof">
                                                                <span class="help-block red">* Max file size 1 MB
                                                                    <a onclick="updateDoc(5, 0);" class="btn btn-xs red"><i class="fa fa-remove"></i></a>
                                                                </span>
                                                            </span>
                                                        </div>
                                                    <?php } else { ?>
                                                        <div id="biz_reg">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-5">Business registration proof (Shop act, IE certificate)<span id="reqspan" class="required">*</span>
                                                                </label>
                                                                <div class="col-md-3">
                                                                    <input type="file" accept="image/*,application/pdf" onchange="validatefilesize(1000000, 'a5');
                                                                            submitDoc();" id="a5" name="biz_reg_proof">
                                                                    <span class="help-block red">* Max file size 1 MB</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php } ?>

                                                    <div <?php
                                                    if ($this->document_complete == 1) {
                                                    } else {
                                                        echo 'style="display:none;"';
                                                    }
                                                    ?> id="confirmbox" class="form-group">
                                                        <label class="control-label col-md-3">&nbsp;
                                                        </label>
                                                        <div class="col-md-9">
                                                            <br>
                                                            <label> <input type="checkbox" id="confirm"> I confirm that the documents uploaded are genuine business proofs related to <?php echo $this->merchant_det['company_name']; ?> and can be used in case of queries and compliance.</label>
                                                        </div>
                                                    </div>
                                                </div>


                                            </div>
                                        </div>
                                        <!-- End profile details -->
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" value="" id="detail" name="detail">

                        <input type="submit" <?php
                        if ($this->document_complete == 1) {
                            echo 'class="btn green"';
                        } else {
                            echo 'class="btn default" disabled=""';
                        }
                        ?> id="submit_doc" onclick="validateConfirm(true);" name="submit_document" value="Submit documents for verification" />

                        <input type="submit" onclick="validateConfirm(false);" id="btnSubmit" class="btn blue" value="Save progress and submit later" />
                        <a href="/merchant/dashboard/remindmelater" class="btn yellow">Remind me later</a>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>


    <script>
        BizType(<?php echo $this->merchant_det['entity_type']; ?>);
        document.getElementById('cashfree').click();
        gstavailable();
    </script>
<?php }
?>

<?php if ($this->help_hero_popup == 1) { ?>
    <div data-controls-modal="helphero" data-backdrop="static" data-keyboard="false" class="modal fade" id="helphero" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog" style="width: 430px;">
            <div class="modal-content">
                <div class="portlet box" style="margin-bottom: 5px;">
                    <div class="portlet-title" style="background-color: #18aebf;">
                        <div class="caption whitecolor">
                            Get started with Swipez
                        </div>
                    </div>
                </div>
                <div class="modal-body ">
                    Take a quick tour of the Swipez billing product. Learn how to create and send an invoice.
                </div>
                <div class="modal-footer">
                    <a type="button" href="/merchant/dashboard/remindmelater/helphero" class="btn green">Dismiss</a>
                    <a href="" onclick="HelpHero.startTour('us116WJMOqS');" data-dismiss="modal" class="btn blue">Start now</a>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <a data-toggle="modal" id="helpheropopup" href="#helphero"></a>
    <script>
        document.getElementById('helpheropopup').click();
    </script>
<?php } ?>
<div data-controls-modal="cable" data-backdrop="static" data-keyboard="false" class="modal fade" id="cable" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="width: 730px;">
        <div class="modal-content">
            <div class="portlet box" style="margin-bottom: 5px;">
                <div class="portlet-title" style="background-color: #18aebf;">
                    <div class="caption whitecolor">
                        Get started with Swipez
                    </div>
                </div>
            </div>
            <div class="modal-body ">
                <iframe width="700" height="315" src="https://www.youtube.com/embed/12PzCrkWRuk" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
            <div class="modal-footer">
                <a type="button" href="/merchant/dashboard/remindmelater/cable" class="btn green">Watch later</a>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<?php if ($this->new_merchant == true && env('IS_FULLSTORY_ACTIVE') == 1) { ?>
    <script>
        window['_fs_debug'] = false;
        window['_fs_host'] = 'fullstory.com';
        window['_fs_script'] = 'edge.fullstory.com/s/fs.js';
        window['_fs_org'] = 'B8KTC';
        window['_fs_namespace'] = 'FS';
        (function(m, n, e, t, l, o, g, y) {
            if (e in m) {
                if (m.console && m.console.log) {
                    m.console.log('FullStory namespace conflict. Please set window["_fs_namespace"].');
                }
                return;
            }
            g = m[e] = function(a, b, s) {
                g.q ? g.q.push([a, b, s]) : g._api(a, b, s);
            };
            g.q = [];
            o = n.createElement(t);
            o.async = 1;
            o.crossOrigin = 'anonymous';
            o.src = 'https://' + _fs_script;
            y = n.getElementsByTagName(t)[0];
            y.parentNode.insertBefore(o, y);
            g.identify = function(i, v, s) {
                g(l, {
                    uid: i
                }, s);
                if (v) g(l, v, s)
            };
            g.setUserVars = function(v, s) {
                g(l, v, s)
            };
            g.event = function(i, v, s) {
                g('event', {
                    n: i,
                    p: v
                }, s)
            };
            g.anonymize = function() {
                g.identify(!!0)
            };
            g.shutdown = function() {
                g("rec", !1)
            };
            g.restart = function() {
                g("rec", !0)
            };
            g.log = function(a, b) {
                g("log", [a, b])
            };
            g.consent = function(a) {
                g("consent", !arguments.length || a)
            };
            g.identifyAccount = function(i, v) {
                o = 'account';
                v = v || {};
                v.acctId = i;
                g(o, v)
            };
            g.clearUserCookie = function() {};
            g.setVars = function(n, p) {
                g('setVars', [n, p]);
            };
            g._w = {};
            y = 'XMLHttpRequest';
            g._w[y] = m[y];
            y = 'fetch';
            g._w[y] = m[y];
            if (m[y]) m[y] = function() {
                return g._w[y].apply(this, arguments)
            };
            g._v = "1.3.0";
        })(window, document, window['_fs_namespace'], 'script', 'user');
    </script>
<?php } ?>

<script>
    $(function() {
        var start = moment().add(1, 'days');
        var end = moment();

        $('#daterange').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            showCustomRangeLabel: false,
            startDate: start,
            endDate: end,
            autoApply: true,
            opens: 'left',
            locale: {
                format: 'Do MMM YYYY'
            },
            minDate: new Date(new Date().getTime() + 86400000),
            maxDate: new Date(new Date().getTime() + 691200000),
            ranges: {
                'Tomorrow': [moment().add(1, 'days'), moment().add(1, 'days')],
                'Day After Tomorrow': [moment().add(2, 'days'), moment()]
            }
        }, function(start, end, label) {

        });
    });
</script>
<!-- Firebase Push Events -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.3.3/axios.min.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js"></script>
<script>
    // Your web app's Firebase configuration
    // For Firebase JS SDK v7.20.0 and later, measurementId is optional
    const firebaseConfig = {
        apiKey: "AIzaSyC-SjmTAA8a263sh83pqBwuDTj5l7UJQRg",
        authDomain: "push-notifications-ea922.firebaseapp.com",
        projectId: "push-notifications-ea922",
        storageBucket: "push-notifications-ea922.appspot.com",
        messagingSenderId: "361692257040",
        appId: "1:361692257040:web:fe2910d1d2a72d3054e395",
        measurementId: "G-Z94TJHFS72"
    };

    firebase.initializeApp(firebaseConfig);

    const messaging = firebase.messaging();

    function initFirebaseMessagingRegistration() {
        messaging.requestPermission ().then(function () {
            return messaging.getToken()
        }).then(function(token) {
            axios.post('/fcm-token', {
                _method:"POST",
                token
            }).then(({data}) => {
                console.log(data);
                fetchNewNotifications();
            }).catch(({response:{data}}) => {
                console.error(data)
            })

        }).catch(function (err) {
            console.log(`Token Error :: ${err}`);
        });
    }

    initFirebaseMessagingRegistration();

    messaging.onMessage(function({data:{body,title}}) {
        //alert(title);
        console.log(title, {body});
        //if receive notification fetch from db latest notifications for login user
        fetchNewNotifications();

        //new Notification(title, {body});
    });

    // $(function() {
    function fetchNewNotifications() {
        fetch(`/merchant/user/notifications`)
            .then((response) => response.json())
            .then((data) => {

                let notifications = data.data;

                let notificationDropdown = $("#notification-dropdown");

                notificationDropdown.empty();
                notifications.forEach((notification, i) => {
                    console.log(notification);
                    let html = `<li style="border-bottom: 1px solid #eee;padding: 10px 15px">
                                    <div style="width: 300px">
                                        ${notification.data.type === 'invoice' ? invoiceNotificationText(notification) : ''}
                                        ${notification.data.type === 'change-order' ? changeOrderNotificationText(notification): ''}
                                    </div>
                                </li>`;

                    notificationDropdown.append(html);
                })
            })
    }

    function invoiceNotificationText(notification) {
        return `<a href="/merchant/invoice/viewg703/${notification.data.payment_request_id}?notification_id=${notification.id}" target="_blank" style="color: #495555;">${notification.data.invoice_number} invoice pending for approval</a>`
    }

    function changeOrderNotificationText(notification) {
        return `<a href="/merchant/invoice/viewg703/${notification.data.order_id}" target="_blank" style="color: #495555;">${notification.data.order_number} order pending for approval</a>`
    }
    // })

</script>