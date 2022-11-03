
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <!-- END PAGE HEADER-->
    <div class="row no-margin">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <br>
            <br>
            <div class="alert alert-block alert-danger fade in">
                {if $is_bookingid==1}
                    <h4 class="alert-heading">Invalid Booking ID</h4>
                    <p>
                        Sorry entered booking id is not valid Please try again.
                    </p>
                {else}
                    <h4 class="alert-heading">Invalid QR Code</h4>
                    <p>
                        Sorry the code you scanned is not valid Please try again.
                    </p>
                {/if}

                <p>
                    <a class="btn blue input-sm" href="/m/{$url}/qrcode">
                         Scan QR Again </a>
                </p>
            </div>
        </div>
        <div class="col-md-1"></div>
    </div>
    <!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->

