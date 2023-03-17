<div class="page-content  ">
    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">
        {include file="../../common/breadcumbs.tpl" title={$title} links=$links}
    </div>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="portlet light" style="min-height: 500px;">
        <div class="row ">
            <div class="col-md-1"></div>

            <div class="col-md-10">
                <div class="col-md-4">
                    <h4 class="dc-h"><b>Billing & Invoicing</b></h4>
                    <p>
                        <a href="/merchant/template/newtemplate" class="dc-link"> Create invoice format</a>
                    </p>
                    <p>
                        <a href="/merchant/template/viewlist" class="dc-link"> Invoice formats</a>
                    </p>
                    <p>
                        <a href="/merchant/profile/setting/sequence" class="dc-link"> Invoice sequence</a>
                    </p>
                    <p>
                        <a href="/merchant/coveringnote/viewlist" class="dc-link"> Covering notes</a>
                    </p>
                    <p>
                        <a href="/merchant/profile/digitalsignature" class="dc-link"> Digital signature</a>
                    </p>
                    <p>
                        <a href="/merchant/profile/gstprofile" class="dc-link"> Billing profile</a>
                    </p>
                    <p>
                        <a href="/merchant/profile/producttaxation" class="dc-link"> Product Taxation</a>
                    </p>
                    {if $multi_currency==true}
                    <p>
                        <a href="/merchant/profile/currency" class="dc-link" > Currency</a>
                    </p>
                    {/if}
                </div>
                <div class="col-md-4">
                    <h4 class="dc-h"><b>Data Configuration</b></h4>
                    <p>
                        <a href="/merchant/imports" class="dc-link"> Imports</a>
                    </p>
                    <p>
                        <a href="/merchant/product/dashboard" class="dc-link"> Inventory</a>
                    </p>
                    <p>
                        <a href="/merchant/project/list" class="dc-link"> Project</a>
                    </p>
                    <p>
                        <a href="/merchant/tax/viewlist" class="dc-link"> Tax</a>
                    </p>
                    <p>
                        <a href="/merchant/plan/viewlist" class="dc-link"> Plans</a>
                    </p>
                    <p>
                        <a href="/merchant/coupon/viewlist" class="dc-link"> Coupons</a>
                    </p>
                    <p>
                        <a href="/merchant/supplier/viewlist" class="dc-link"> Suppliers</a>
                    </p>
                    <p>
                        <a href="/merchant/unit-type/index" class="dc-link"> Unit types</a>
                    </p>
                    <p>
                        <a href="/merchant/product-category/index" class="dc-link"> Product category</a>
                    </p>
                    <p>
                        <a href="/merchant/product-attribute/index" class="dc-link"> Product variations</a>
                    </p>
                    <p>
                        <a href="/merchant/gst/exporttally" class="dc-link"> Tally export</a>
                    </p>

                    {if $non_brand_food_franchise}
                        <p>
                            <a href="/merchant/master/delivery-partner/list" class="dc-link"> Delivery partners</a>
                        </p>
                    {/if}
                    <p>
                        <a href="/merchant/cost-types/index" class="dc-link"> Cost Type</a>
                    </p>
                    <p>
                        <a href="/merchant/configure-invoice-statuses" class="dc-link"> Configure invoice statues</a>
                    </p>
                    {* <p>
                        <a href="/merchant/hsn-sac-code/index" class="dc-link" > HSN/SAC codes</a>
                    </p> *}
                </div>
                <div class="col-md-4">
                    <h4 class="dc-h"><b>Contact settings</b></h4>

                    <p>
                        <a href="/merchant/customer/structure" class="dc-link"> Customer structure</a>
                    </p>
                    <p>
                        <a href="/merchant/profile/setting" class="dc-link"> Customer settings</a>
                    </p>

                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-1"></div>

            <div class="col-md-10">
                <div class="col-md-4">
                    <h4 class="dc-h"><b>Company settings</b></h4>
                    <p>
                        <a href="/merchant/profile/update" class="dc-link"> Update profile</a>
                    </p>
                    <p>
                        <a href="/site/company-profile/home" class="dc-link"> Company Website</a>
                        <!-- <a href="/merchant/companyprofile" class="dc-link" > Company profile</a> -->
                    </p>
                    <p>
                        <a href="/merchant/profile/packagedetails" class="dc-link"> Package details</a>
                    </p>
                    {* <p>
                        <a href="/merchant/profile/accesskey" class="dc-link"> API keys</a>
                    </p> *}
                    <p>
                        <a href="/merchant/user/create-token" class="dc-link"> API token</a>
                    </p>
                    <p>
                        <a href="/merchant/integrations" class="dc-link"> Integrations</a>
                    </p>


                </div>
                <div class="col-md-4">
                    <h4 class="dc-h"><b>Manage Users</b></h4>
{*                    <p>*}
{*                        <a href="/merchant/roles" class="dc-link"> Roles</a>*}
{*                    </p>*}
                    <p> 
                        <a href="/merchant/subusers" class="dc-link"> Team Members </a>
                    </p>


                </div>
                <div class="col-md-4">
                    <h4 class="dc-h"><b>Personal preferences</b></h4>
                    <p>
                        <a href="/profile/preferences" class="dc-link"> Notifications</a>
                    </p>
                    <p>
                        <a href="/profile/reset" class="dc-link"> Password reset</a>
                    </p>
                      <p>
                        <a href="/merchant/regionSettings" class="dc-link"> Region settings</a>
                    </p>
                </div>



            </div>
        </div>


    </div>
</div>
</div>
<!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->
</div>