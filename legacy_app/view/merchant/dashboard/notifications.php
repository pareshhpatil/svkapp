
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">Merchant notifications</h3>


    <div class="row">
        <div class="col-md-12">

            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-share font-dark hide"></i>
                        <span class="caption-subject font-dark bold uppercase">Recent Activities</span>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="scroller" style="height: 400px;" data-always-visible="1" data-rail-visible="0">
                        <ul class="feeds">
                            <?php foreach ($this->merchant_notification as $row) { ?>
                                <li>
                                    <div class="col1">
                                        <div class="cont">
                                            <div class="cont-col1">
                                                <div class="label label-sm <?php echo $row['label_class']; ?>">
                                                    <i class="fa <?php echo $row['label_icon']; ?>"></i>
                                                </div>
                                            </div>
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
                    
                </div>
            </div>
        </div>
    </div>


</div>
</div>
