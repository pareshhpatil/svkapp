
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">{$title}&nbsp;
    </h3>
    <!-- END PAGE HEADER-->

    <!-- BEGIN SEARCH CONTENT-->
    <!-- BEGIN SEARCH CONTENT-->

    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">
            {if isset($success)}
                <div class="alert alert-info">
                    <button type="button" class="close" data-dismiss="alert"></button>
                    <strong></strong>{$success}
                </div> 
            {/if}
            <!-- BEGIN PORTLET-->
            {if $link==''}
                <div class="portlet">

                    <div class="portlet-body">

                        <form class="form-inline" action="" method="post" role="form">
                            {CSRF::create('gst_submit_status')}
                            <div class="form-group">
                                <select required="" class="form-control" name="month">
                                    <option>Select Month GSTIN</option>
                                    {foreach from=$data item=v}
                                        <option {if $month=={$v.fp} && $gstin=={$v.gstin}} selected{/if} value="{$v.fp}{$v.gstin}">{$datearray.{$v.fp|substr:0:2}}-{$v.fp|substr:2} - {$v.gstin}</option>
                                    {/foreach}
                                </select>
                            </div>
                            <input type="submit" class="btn  blue" value="Get Status" />
                        </form>

                    </div>
                </div>
            {/if}

            <!-- END PORTLET-->
        </div>
    </div>
    {if isset($gstin)}
        {if $submit_enable==1}
            {$error=0}
            <div class="row">
                <div class="col-md-8">
                    {if $message!=''}
                        <div class="alert alert-success">
                            {$message}
                        </div> 
                    {/if}
                    {foreach from=$checklist item=v}
                        {if $v.checkListType=='Error'}
                            <div class="alert alert-danger">
                                <strong></strong>{$v.checkListDesc}
                            </div> 
                            {$error=1}
                        {else}
                            <div class="alert alert-warning" style="margin-left: 15px !important;    margin-right: 20px !important;">
                                <strong>Warning! </strong>{$v.checkListDesc}
                            </div> 
                        {/if}
                    {/foreach}


                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    {if $error!=1}
                        <form class="form-inline" action="" method="post" role="form">
                            {CSRF::create('gst_submit_status')}
                            <div class="form-group">
                                <label class="control-label col-md-12"><input required="" type="checkbox" >I have reviewed list and want to SUBMIT my GSTR-1 return, I will not be able to revert after submitting the return </label>
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="fp" value="{$month}">
                                <input type="hidden" name="upload_id" value="{$link}">
                                <input type="hidden" name="gstn" value="{$gstin}">
                                <input type="hidden" name="month" value="{$month}{$gstin}">
                                <input type="hidden" name="form_type" value="GSTR1">
                                <input type="hidden" name="upload_id" value="{$link}">
                                <input type="submit" {if $error==1} disabled="" {/if} name="submit_gst" value="Submit GST" class="btn blue pull-right"/>
                            </div>
                        </form>
                    {/if}
                    <!-- END PAYMENT TRANSACTION TABLE -->
                </div>
            </div>
        {/if}
        {if !empty($summary)}
            <div class="row">
                <div class="col-md-12">

                    <!-- BEGIN PAYMENT TRANSACTION TABLE -->

                    <div class="portlet ">
                        <div class="portlet-body">
                            {foreach from=$summary item=v}
                                {if $v.sec_nm=='B2B'}
                                    <h4><b>B2B Sec Summary</b></h4>
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
                                            <td>{$v.ttl_rec}</td>
                                            <td>{$v.ttl_val}</td>
                                            <td>{$v.ttl_tax}</td>
                                            <td>{$v.ttl_igst}</td>
                                            <td>{$v.ttl_cgst}</td>
                                            <td>{$v.ttl_sgst}</td>
                                            <td>{$v.ttl_cess}</td>
                                        </tr>
                                    </table>
                                {/if}
                                {if $v.sec_nm=='B2CS'}
                                    <h4><b>B2CS Sec Summary</b></h4>
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
                                            <td>{$v.ttl_rec}</td>
                                            <td>{$v.ttl_val}</td>
                                            <td>{$v.ttl_tax}</td>
                                            <td>{$v.ttl_igst}</td>
                                            <td>{$v.ttl_cgst}</td>
                                            <td>{$v.ttl_sgst}</td>
                                            <td>{$v.ttl_cess}</td>
                                        </tr>
                                    </table>
                                {/if}
                                {if $v.sec_nm=='HSN'}
                                    <h4><b>HSN Sec Summary</b></h4>
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
                                            <td>{$v.ttl_rec}</td>
                                            <td>{$v.ttl_val}</td>
                                            <td>{$v.ttl_tax}</td>
                                            <td>{$v.ttl_igst}</td>
                                            <td>{$v.ttl_cgst}</td>
                                            <td>{$v.ttl_sgst}</td>
                                            <td>{$v.ttl_cess}</td>
                                        </tr>
                                    </table>
                                {/if}
                                {if $v.sec_nm=='NIL'}
                                    <h4><b>NIL Sec Summary</b></h4>
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>Total Nil Sup Amount</th>
                                            <th>Total Non Gst Ngsup Amount</th>
                                            <th>Total Exempted Ngsup Amount</th>
                                        </tr>
                                        <tr>
                                            <td>{$v.ttl_nilsup_amt|string_format:"%.2f"}</td>
                                            <td>{$v.ttl_expt_amt|string_format:"%.2f"}</td>
                                            <td>{$v.ttl_ngsup_amt|string_format:"%.2f"}</td>
                                        </tr>
                                    </table>
                                {/if}
                            {/foreach}

                        </div>
                    </div>

                    <!-- END PAYMENT TRANSACTION TABLE -->
                </div>
            </div>
        {/if}









    {/if}
    <!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->



