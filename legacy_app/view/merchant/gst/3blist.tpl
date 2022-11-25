
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">
        {include file="../../common/breadcumbs.tpl" title={$title} links=$links}
    </div>
    <!-- END PAGE HEADER-->

    <!-- BEGIN SEARCH CONTENT-->
    <!-- BEGIN SEARCH CONTENT-->

    <!-- BEGIN PAGE CONTENT-->

    <div class="row no-margin">


        {if isset($success)}
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert"></button>
                <strong>Success!</strong>{$success}
            </div> 
        {else}

            <!-- BEGIN PORTLET-->

            <div class="portlet">
                <div class="portlet-body">

                    <form class="form-inline" action="/merchant/gst/gst3b" method="post" role="form">
                        {CSRF::create('gst_3b_summary')}
                        <div class="form-group">
                            <select required="" class="form-control" name="gstin">
                                <option value="">Select GSTIN</option>
                                {foreach from=$gst_list key=k item=v}
                                    <option {if $gstin==$v.gstin} selected{/if} value="{$v.gstin}">{$v.company_name} - {$v.gstin}</option>
                                {/foreach}
                            </select>
                        </div>
                        <div class="form-group">
                            <select required="" name="month" class="form-control" >
                                <option>Select Month</option>
                                {foreach from=$months key=k item=v}
                                    <option {if $k==$month} selected {$monthsel=$v}{/if} value="{$k}">{$v}</option>
                                {/foreach}
                            </select>
                        </div>
                        <div class="form-group">
                            <select required="" name="year" class="form-control" >
                                <option>Select Year</option>
                                {foreach from=$years item=v}
                                    <option {if $v==$year} selected {/if} value="{$v}">{$v}</option>
                                {/foreach}
                            </select>
                        </div>
                        <input type="submit" class="btn blue" value="Generate" />
                        {if !empty($summary)}
                            <input type="submit" name="download" class="btn green" value="Download File" />
                        {/if}
                    </form>

                </div>
            </div>
        {/if}

    </div>
    {if !empty($summary)}
        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN PAYMENT TRANSACTION TABLE -->

                <div class="portlet ">
                    <div class="portlet-body">
                        <table class="table table-striped  table-hover">
                            <tbody>
                            <form action="" method="">
                                <tr>
                                    <td>
                                        <b> Return period</b>

                                    </td>
                                    <td>
                                        {$datearray.{$fp|substr:0:2}}-{$fp|substr:2}
                                    </td>
                                    <td colspan="7">

                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <b>Return Type</b>
                                    </td>
                                    <td>
                                        GSTR3B
                                    </td>
                                    <td colspan="7">

                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <b> GSTIN of Taxpayer</b>
                                    </td>
                                    <td>
                                        <b> Item Type</b>
                                    </td>
                                    <td>
                                        <b> Item Details</b>
                                    </td>
                                    <td>
                                        <b> Place of Supply</b>
                                    </td>
                                    <td>
                                        <b> Total Taxable value</b>
                                    </td>
                                    <td>
                                        <b> IGST</b>
                                    </td>
                                    <td>
                                        <b>  CGST</b>
                                    </td>
                                    <td>
                                        <b> SGST</b>
                                    </td>
                                    <td>
                                        <b> CESS</b>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        {$summary.gstin}
                                    </td>
                                    <td>
                                        3.1a
                                    </td>
                                    <td>
                                        (a) Outward taxable supplies (other than zero rated; nil rated and exempted)
                                    </td>
                                    <td>

                                    </td>
                                    <td>
                                        {$summary.taxable}
                                    </td>
                                    <td>
                                        {$summary.igst}
                                    </td>
                                    <td>
                                        {$summary.cgst}
                                    </td>
                                    <td>
                                        {$summary.sgst}
                                    </td>
                                    <td>

                                    </td>
                                </tr>
                                {foreach from=$grouping item=v}
                                    <tr>
                                        <td>
                                            {$v.gstin}
                                        </td>
                                        <td>
                                            3.2a

                                        </td>
                                        <td>
                                            Supplies made to Unregistered Persons (Inter-State)
                                        </td>
                                        <td>
                                            {$v.pos}
                                        </td>
                                        <td>
                                            {$v.taxable}
                                        </td>
                                        <td>
                                            {$v.igst}
                                        </td>
                                        <td>
                                            {$v.cgst}
                                        </td>
                                        <td>
                                            {$v.sgst}
                                        </td>
                                        <td>
                                        </td>
                                    </tr>
                                {/foreach}

                                {if $purchaseSummary.igst!=null || $purchaseSummary.sgst!=null}
                                    <tr>
                                        <td>
                                            {$v.gstin}
                                        </td>
                                        <td>
                                            4.5a	
                                        </td>
                                        <td>
                                            All other ITC
                                        </td>
                                        <td>
                                        </td>
                                        <td>
                                        </td>
                                        <td>
                                            {$purchaseSummary.igst}
                                        </td>
                                        <td>
                                            {$purchaseSummary.cgst}
                                        </td>
                                        <td>
                                            {$purchaseSummary.sgst}
                                        </td>
                                        <td>
                                        </td>
                                    </tr>
                                {/if}
                            </form>
                            </tbody>
                        </table>

                    </div>
                </div>

                <!-- END PAYMENT TRANSACTION TABLE -->
            </div>
        </div>
    {/if}
    <!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->



