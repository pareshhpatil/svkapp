<script>
    function hidenotification(str) {
        var divv = 'div' + str;

        var elem = document.getElementById(divv);
        elem.parentElement.removeChild(elem);
        //document.getElementById(divv).remove();

        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else { // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {

        }
        xmlhttp.open("GET", "/patron/dashboard/update/" + str, true);
        xmlhttp.send();

    }
</script>
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">Patron dashboard</h3>

    <?php
    $int = 0;
    while (isset($this->notification[$int]['type'])) {
        if ($this->notification[$int]['type'] == 1) {
            ?>
            <div id="div<?php echo $this->notification[$int]['type']; ?>">
                <div class="alert alert-success"><strong></strong> <a href="<?php echo $this->notification[$int]['link']; ?>" onclick="hidenotification(<?php echo $this->notification[$int]['type']; ?>)"><?php echo $this->notification[$int]['message']; ?></a> 
                    <div class="checker" style="float: right;"><span> <input name="input" style="float: right;" type="checkbox" onchange="hidenotification(this.value)" value="<?php echo $this->notification[$int]['type']; ?>" class="pull-right" /></span></div>
                </div>
            </div>
        <?php } else if ($this->notification[$int]['type'] == 3) {
            ?>
            <div class="alert alert-info"><strong></strong> <a href="<?php echo $this->notification[$int]['link']; ?>" ><?php echo $this->notification[$int]['message']; ?></a> 
            </div>
            <?php
        } $int++;
    }
    ?>

    <!-- END PAGE HEADER-->


    <div class="row">
        <br>
        <div class="col-md-12">


            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <div class="dashboard-stat blue-hoki">
                    <div class="visual">
                        <i class="fa fa-credit-card"></i>
                    </div>
                    <div class="details">
                        <div class="number">
                        </div>
                        <div class="desc">
                            My requests
                        </div>
                    </div>
                    <a class="more" href="/patron/paymentrequest/viewlist">
                        View more <i class="fa  fa-arrow-circle-o-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <div class="dashboard-stat blue-madison">
                    <div class="visual">
                        <i class="fa fa-pie-chart"></i>
                    </div>
                    <div class="details">
                        <div class="number">

                        </div>
                        <div class="desc">
                            My transactions
                        </div>
                    </div>
                    <a class="more" href="/patron/transaction/viewlist">
                        View more <i class="fa  fa-arrow-circle-o-right"></i>
                    </a>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <div class="dashboard-stat blue-hoki">
                    <div class="visual">
                        <i class="fa fa-comments"></i>
                    </div>
                    <div class="details">
                        <div class="number">

                        </div>
                        <div class="desc">
                            Suggest a merchant
                        </div>
                    </div>
                    <a class="more" href="/patron/profile/suggest">
                        View more <i class="fa  fa-arrow-circle-o-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- END PAGE CONTENT-->


</div>
</div>
<!-- END CONTENT -->
<!-- /.modal -->

<!-- /.modal-dialog -->
</div>
<!-- /.modal -->