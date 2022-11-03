
<div class="page-content">
    <br>
    <!-- BEGIN PAGE HEADER-->
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row no-margin" style="min-height: 600px;">
        {if {$isGuest} =='1'}
            <div class="col-md-3"></div>
            <div class="col-md-6">
            {else}
                <div class="col-md-2"></div>
                <div class="col-md-8">
                {/if}
                
                <div class="portlet light bordered">
                    <div class="portlet-body form">
                        <form action="#" class="form-horizontal form-row-sepe">
                            <h3 class="font-blue-madison">Thank you</h3>

                            <p>
                                Your form has been submited successful.
                            </p>

                            {if {$isGuest} =='1'}              
                                <hr/>
                                <p>Track your payments by registering on  <a href="/patron/register">Swipez - Register.</a> <img src="/assets/admin/layout/img/logo.png" class="img-responsive pull-right powerbyimg" alt=""/><span class="powerbytxt">powered by</span></p>
                                <hr/>
                            {/if}
                    </div>
                </div>
            </div>	
            <!-- END PAGE CONTENT-->

        </div>
    </div>
</div>
<!-- END CONTENT -->
</div>



