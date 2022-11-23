
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">{$title}&nbsp;
    </h3>
    <!-- END PAGE HEADER-->

    <!-- BEGIN SEARCH CONTENT-->
    <!-- BEGIN SEARCH CONTENT-->

    <!-- BEGIN PAGE CONTENT-->
    <div class="row">

        {if isset($haserrors)}
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert"></button>
                <strong>Error!</strong>  {$haserrors}
            </div> 
        {/if}
        {if isset($success)}
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert"></button>
                <strong>Success!</strong>  {$success}
            </div> 
        {/if}
        <div class="col-md-12">
            {if $link==''}
                <div class="row">

                    <div class="col-md-12">
                        <!-- BEGIN PORTLET-->
                        <div class="portlet">

                            <div class="portlet-body mb-2">

                                <form class="form-inline" action="" method="post" role="form">
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
                                    <input type="submit" class="btn blue" value="Search" />
                                </form>

                            </div>
                        </div>

                        <!-- END PORTLET-->
                    </div>
                </div>
            {/if}
            {if $gst_connection_failed==1}
                {if $showotp==1}
                    <div class="row">
                        <div class="col-md-8">
                            <div class="alert alert-success">
                                <strong>Success!</strong> OTP has been sent
                            </div>
                        </div>
                    </div>
                {else}
                    <div class="row">
                        <div class="col-md-8">
                            <div class="alert alert-danger">
                                <strong>Error!</strong> GST Connection lost Please connect with OTP
                            </div>
                        </div>
                    </div>
                {/if}
                <div class="row">
                    <div class="col-md-8">
                        <form action="/merchant/gst/gstdraft{if $link!=''}/{$link}{/if}" method="post">
                            {CSRF::create('gst_draft')}
                            <input type="hidden" name="fp" value="{$month}">
                            <input type="hidden" name="gstin" value="{$gstin}">
                            <input type="hidden" name="month" value="{$month}{$gstin}">
                            {if $showotp==1}
                                <div class="form-group">
                                    <label class="control-label col-md-1">OTP <span class="required">*
                                        </span></label>
                                    <div class="col-md-4">
                                        <input type="text" name="otp_text" class="form-control">
                                    </div>
                                </div>

                                <input type="submit" name="submit_otp" value="Submit OTP" class="btn green"/>
                            {/if}
                            <input type="submit" name="otp" value="Request OTP" class="btn blue"/>
                        </form>

                    </div>
                </div>
            {else}
                {if !empty($summary)}
                    {if $summary.totalNilAmount!='R'}
                        <div class="row">
                            <div class="col-md-12">
                                <!-- BEGIN PAYMENT TRANSACTION TABLE -->
                                <div class="portlet">
                                    <h4>Draft Summary</h4>
                                    <div class="portlet-body">
                                        <h5><b>B2B Sec Summary</b></h5>
                                        <table class="table table-bordered">
                                            <tr>
                                                <th>Invoice Count</th>
                                                <th>Total Taxable Value</th>
                                                <th>Total Taxable Amount</th>
                                                <th>Total IGST Amount</th>
                                                <th>Total CGST Amount</th>
                                                <th>Total SGST Amount</th>
                                                <th>Total CESS Amount</th>
                                            </tr>
                                            <tr>
                                                <td class="td-c">{$summary.b2bSecSummary.invoiceCount}</td>
                                                <td class="td-c">{$summary.b2bSecSummary.totalTaxableValue|string_format:"%.2f"}</td>
                                                <td class="td-c">{$summary.b2bSecSummary.totalTaxableAmt|string_format:"%.2f"}</td>
                                                <td class="td-c">{$summary.b2bSecSummary.totalIGSTAmount|string_format:"%.2f"}</td>
                                                <td class="td-c">{$summary.b2bSecSummary.totalCGSTAmount|string_format:"%.2f"}</td>
                                                <td class="td-c">{$summary.b2bSecSummary.totalSGSTAmount|string_format:"%.2f"}</td>
                                                <td class="td-c">{$summary.b2bSecSummary.totalCESSAmount|string_format:"%.2f"}</td>
                                            </tr>
                                        </table>
                                        <h5><b>GSTN Wise Summary</b></h5>
                                        <table class="table table-bordered">
                                            <tr>
                                                <th>GST Number</th>
                                                <th>Name</th>
                                                <th>Invoice Count</th>
                                                <th>Total Invoice Value</th>
                                                <th>Total Taxable</th>
                                                <th>CGST Amount</th>
                                                <th>SGST Amount</th>
                                                <th>IGST Amount</th>
                                            </tr>
                                            {foreach from=$summary.b2bSecSummary.ctinwiseSummaryList item=v}
                                                <tr>
                                                    <td class="td-c">{$v.ctin}</td>
                                                    <td class="td-c">{$v.cname}</td>
                                                    <td class="td-c">{$v.invoiceCount}</td>
                                                    <td class="td-c">{$v.totalInvValue|string_format:"%.2f"}</td>
                                                    <td class="td-c">{$v.totalTaxableValue|string_format:"%.2f"}</td>
                                                    <td class="td-c">{$v.totalCGSTAmount|string_format:"%.2f"}</td>
                                                    <td class="td-c">{$v.totalSGSTAmount|string_format:"%.2f"}</td>
                                                    <td class="td-c">{$v.totalIGSTAmount|string_format:"%.2f"}</td>
                                                </tr>
                                            {/foreach}
                                        </table>
                                        <h5><b>B2CS Sec Summary</b></h5>
                                        <table class="table table-bordered">
                                            <tr>
                                                <th>Invoice Count</th>
                                                <th>Total Taxable Value</th>
                                                <th>Total Taxable Amount</th>
                                                <th>Total IGST Amount</th>
                                                <th>Total CGST Amount</th>
                                                <th>Total SGST Amount</th>
                                                <th>Total CESS Amount</th>
                                            </tr>
                                            <tr>
                                                <td class="td-c">{$summary.b2csSecSummary.invoiceCount}</td>
                                                <td class="td-c">{$summary.b2csSecSummary.totalTaxableValue|string_format:"%.2f"}</td>
                                                <td class="td-c">{$summary.b2csSecSummary.totalTaxableAmt|string_format:"%.2f"}</td>
                                                <td class="td-c">{$summary.b2csSecSummary.totalIGSTAmount|string_format:"%.2f"}</td>
                                                <td class="td-c">{$summary.b2csSecSummary.totalCGSTAmount|string_format:"%.2f"}</td>
                                                <td class="td-c">{$summary.b2csSecSummary.totalSGSTAmount|string_format:"%.2f"}</td>
                                                <td class="td-c">{$summary.b2csSecSummary.totalCESSAmount|string_format:"%.2f"}</td>
                                            </tr>
                                        </table>
                                        <h5><b>HSN Sec Summary</b></h5>
                                        <table class="table table-bordered">
                                            <tr>
                                                <th>Invoice Count</th>
                                                <th>Total Invoice Value</th>
                                                <th>Total Taxable Value</th>
                                                <th>Total Taxable Amount</th>
                                                <th>Total IGST Amount</th>
                                                <th>Total CGST Amount</th>
                                                <th>Total SGST Amount</th>
                                                <th>Total CESS Amount</th>
                                            </tr>
                                            <tr>
                                                <td class="td-c">{$summary.hsnSecSummary.invoiceCount}</td>
                                                <td class="td-c">{$summary.hsnSecSummary.totalInvValue|string_format:"%.2f"}</td>
                                                <td class="td-c">{$summary.hsnSecSummary.totalTaxableValue|string_format:"%.2f"}</td>
                                                <td class="td-c">{$summary.hsnSecSummary.totalTaxableAmt|string_format:"%.2f"}</td>
                                                <td class="td-c">{$summary.hsnSecSummary.totalIGSTAmount|string_format:"%.2f"}</td>
                                                <td class="td-c">{$summary.hsnSecSummary.totalCGSTAmount|string_format:"%.2f"}</td>
                                                <td class="td-c">{$summary.hsnSecSummary.totalSGSTAmount|string_format:"%.2f"}</td>
                                                <td class="td-c">{$summary.hsnSecSummary.totalCESSAmount|string_format:"%.2f"}</td>
                                            </tr>
                                        </table>
                                        <h5><b>NIL Sec Summary</b></h5>
                                        <table class="table table-bordered">
                                            <tr>
                                                <th>Total Nil Amount</th>
                                                <th>Total Non Gst Ngsup Amount</th>
                                                <th>Total Exempted Ngsup Amount</th>
                                                <th>Total Supplies Composition</th>
                                            </tr>
                                            <tr>
                                                <td class="td-c">{$summary.nilSecSummary.totalNilAmount|string_format:"%.2f"}</td>
                                                <td class="td-c">{$summary.nilSecSummary.totalNonGstNgsupAmount|string_format:"%.2f"}</td>
                                                <td class="td-c">{$summary.nilSecSummary.totalExemptedNgsupAmount|string_format:"%.2f"}</td>
                                                <td class="td-c">{$summary.nilSecSummary.totalSuppliesComposition|string_format:"%.2f"}</td>
                                            </tr>
                                        </table>
                                        <h5><b>Document Details</b></h5>
                                        <table class="table table-bordered">
                                            <tr>
                                                <th>Total Docs</th>
                                                <th>Cancelled Docs</th>
                                                <th>Net Issued Docs</th>
                                            </tr>
                                            <tr>
                                                <td class="td-c">{$summary.docIssuedSecSummary.totnum}</td>
                                                <td class="td-c">{$summary.docIssuedSecSummary.cancel}</td>
                                                <td class="td-c">{$summary.docIssuedSecSummary.netIssue}</td>
                                            </tr>
                                        </table>

                                        <div class="col-md-offset-5 col-md-6">
                                            <form action="/merchant/gst/gstsubmitsave" id="gst_submit" method="post">
                                                <input type="hidden" name="fp" value="{$month}{$year}">
                                                <input type="hidden" name="gstin" value="{$gstin}">
                                                <input type="hidden" name="upload_id" value="{$link}">
                                                <input type="hidden" name="frm_type" value="GSTR1">
                                                <input type="hidden" name="month" value="{$month}{$gstin}">
                                                <a href="#submit" data-toggle="modal" class="btn blue"> Save to GSTN</a>
                                                <a href="#basic" data-toggle="modal" class="btn red" value="Delete draft" >Delete draft</a>
                                            </form>

                                            <form action="{if $link!=''}/merchant/gst/gstdraft/{$link}/delete{/if}" id="draft_delete" method="post">
                                                <input type="hidden" name="month" value="{$month}">
                                                <input type="hidden" name="year" value="{$year}">
                                                <input type="hidden" name="gstin" value="{$gstin}">
                                                <input type="hidden" value="1" name="delete" />
                                            </form>
                                        </div>
                                        <br>
                                        <br>
                                    </div>
                                </div>

                                <!-- END PAYMENT TRANSACTION TABLE -->
                            </div>
                        </div>
                    {else}
                        {if isset($success)}
                        {else}
                            <div class="row">
                                <form action="{if $link!=''}/merchant/gst/gstdraft/{$link}/create{/if}" method="post">
                                    <div class="col-md-12">
                                        <div class="alert alert-info">
                                            <strong>Draft Empty!</strong> Draft not exist for this period. Please create draft 
                                            <br>
                                            <br>
                                            <input type="hidden" name="month" value="{$month}">
                                            <input type="hidden" name="year" value="{$year}">
                                            <input type="hidden" name="gstin" value="{$gstin}">
                                            <input type="submit" name="create" class="btn btn-sm green" value="Create draft" />
                                        </div>
                                    </div>
                                </form>
                            </div>
                        {/if}
                    {/if}
                {else}
                    {if isset($gstin)}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <strong>Data Empty!</strong> No invoices found. Please prepare GSTR1 invoices 
                                    <br>
                                    <br>
                                    <a href="/merchant/gst/gstr1upload" class="btn btn-sm blue" value="Create draft" >Prepare GSTR1</a>
                                </div>
                            </div>
                        </div>
                    {/if}
                {/if}
            {/if}
        </div>
    </div>

    <!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->

<div class="modal fade" id="basic" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Delete Draft</h4>
            </div>
            <div class="modal-body">
                You are about to delete draft for {$datearray.{$month|substr:0:2}}-{$month|substr:2} & {$gstin}. Are you sure you would like to proceed?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn default" data-dismiss="modal">Close</button>
                <a onclick="submitfrm('draft_delete');" id="deleteanchor" class="btn delete">Confirm</a>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="submit" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Save Draft</h4>
            </div>
            <div class="modal-body">
                You are about to submit invoices for R1 for {$datearray.{$month|substr:0:2}}-{$month|substr:2} & {$gstin}. Are you sure you would like to proceed?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn default" data-dismiss="modal">Close</button>
                <a onclick="submitfrm('gst_submit');" class="btn blue">Confirm</a>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script>
    function submitfrm(id)
    {
        document.getElementById(id).submit();
    }
</script>