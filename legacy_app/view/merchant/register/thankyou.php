<!-- BEGIN CONTAINER -->

<!-- BEGIN CONTENT -->
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->

    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="col-md-1"></div>
    <div class="col-md-10">
        <br>
        <h3 class="page-title">Verification successful </h3>
        <br>
        <div class="note note-success">
            <h4 class="block">Thank you for completing your registration with Swipez.</h4>

            <p>
                <?php if ($this->status == 'activated') { ?>
                    You have already verified your email id with Swipez. You can now start using Swipez via your account <a href="<?php echo $this->server_name; ?>/merchant/register/dashboard">Dashboard</a> 
                <?php } else { ?>
                    Now you can collect payments and keep track of all your transactions with a single login.<br><a href="<?php echo $this->server_name; ?>/merchant/register/dashboard">Click here</a> to access your dashboard and start collecting payments from your consumer. If you have any queries, please write to us on <a href="<?php echo $this->server_name; ?>/helpdesk" class="iframe">contact us. </a>
                <?php } ?>
            </p>
        </div>
    </div>

    <div class="clearfix">
    </div>
    <!-- END PAGE CONTENT-->
</div>
<!-- END CONTENT -->
</div>
<!-- END CONTAINER -->