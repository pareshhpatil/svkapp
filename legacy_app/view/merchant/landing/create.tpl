<div class="row">
    <div class="col-md-1"></div>
    <div class="col-md-10 fileinput fileinput-new banner-main" data-provides="fileinput">
        <div class="fileinput fileinput-new" data-provides="fileinput">
            <div class="fileinput-preview thumbnail banner-container" data-trigger="fileinput" > 
                <img class="img-responsive"  src="{if $details.banner!=""}/uploads/images/landing/{$details.banner}{else}/assets/admin/layout/img/banner.jpg{/if}"></div>
        </div>

    </div>

</div>                                
<div class="row">
    <div class="portlet-body form">

        <div class="form-body">
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-10">
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-12">
                                <a href="{$details.merchant_website}" target="BLANK" ><h3 class="page-title" style="margin-left: 0px !important;">{$details.company_name} </h3> </a>                                                         
                                <span class="help-block">
                                </span>
                            </div>
                        </div>
                    </div>
                    {if $direct_pay==1}
                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <a href="/m/{$url}/directpay" class="btn blue" >Pay Now</a>                                                         
                                    <span class="help-block">
                                    </span>
                                </div>
                            </div>
                        </div>
                    {/if}
                    <div class="form-group">
                        {$details.overview}
                    </div>
                    <hr/>
                    <p>&copy; {$current_year} OPUS Net Pvt. Handmade in Pune. <a target="_BLANK" href="https://www.swipez.in"><img src="/assets/admin/layout/img/logo.png" class="img-responsive pull-right powerbyimg" alt=""/><span class="powerbytxt">powered by</span></a></p>
                    <br>
                </div>
            </div>
        </div>
    </div>
</div>	
</div>	


</div>
</div>
</form>
</div>

</div>	
<!-- END PAGE CONTENT-->
</div>
</div>