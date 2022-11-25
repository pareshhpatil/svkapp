<div class="page-content">
    <h3 class="page-title">Update {if $det.dty=='RI'}invoice {elseif $det.dty=='C'}Credit note {/if}&nbsp;</h3>
    <div class="row no-margin">
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <!--<h3 class="form-section">Profile details</h3>-->
                    <form action="/merchant/gst/invoicesave" id="frm_expense" onsubmit="loader();" method="post"
                        enctype="multipart/form-data" class="form-horizontal form-row-sepe">
                        <input type="hidden" name="_token" value="TSsFJMOiEBBrzG2raXAXjjsuO5YvHIkaOGjmeZbz">
                        <div class="form-body">
                            <!-- Start profile details -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Invoice Type<span class="required">*
                                            </span></label>
                                        <div class="col-md-8">
                                            <select onchange="setCustomerType(this.value);" name="invTyp" required
                                                class="form-control" data-placeholder="Select...">
                                                <option {if $det.invTyp=='B2CS'} selected {/if} value="B2CS">B2CS
                                                </option>
                                                <option {if $det.invTyp=='B2B'} selected {/if} value="B2B">B2B
                                                </option>
                                            </select>
                                        </div>
                                        <br>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">{$customer_default_column.customer_name|default:'Customer name'} <span class="required">
                                            </span></label>
                                        <div class="col-md-8">
                                            <input name="cname" id="cname" {if $det.invTyp=='B2B'} required {/if}
                                                value="{$det.cname}" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Customer GST <span class="required">
                                            </span></label>
                                        <div class="col-md-8">
                                            <input name="ctin" id="ctin" {if $det.invTyp=='B2B'} required {/if}
                                                value="{$det.ctin}" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Place of supply<span class="required">*
                                            </span></label>
                                        <div class="col-md-8">
                                            <select name="pos" onchange="setGstType(this.value);" required
                                                class="form-control select2" data-placeholder="Select...">
                                                {foreach from=$state key=k item=v}
                                                    <option {if $k==$det.pos} selected {/if} value="{$k}">{$k} - {$v}
                                                    </option>
                                                {/foreach}
                                            </select>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-md-6">
                                    {if $det.dty!='RI'}
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Note number <span class="required">*
                                                </span></label>
                                            <div class="col-md-8">
                                                <input required name="inum" readonly value="{$det.ntNum}"
                                                    class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Note date<span class="required">*
                                                </span></label>
                                            <div class="col-md-8">
                                                <input class="form-control form-control-inline date-picker" type="text"
                                                    required="" name="ntDt" value="{$det.ntDt|date_format:"%d %b %Y"}"
                                                    autocomplete="off" data-date-format="dd M yyyy" placeholder="Bill date">
                                            </div>
                                        </div>
                                    {/if}
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Invoice number <span class="required">*
                                            </span></label>
                                        <div class="col-md-8">
                                            <input required name="inum" readonly value="{$det.inum}"
                                                class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-4">Bill date<span class="required">*
                                            </span></label>
                                        <div class="col-md-8">
                                            <input class="form-control form-control-inline date-picker" type="text"
                                                required="" name="pdt" value="{$det.pdt|date_format:"%d %b %Y"}"
                                                autocomplete="off" data-date-format="dd M yyyy" placeholder="Bill date">
                                        </div>
                                    </div>



                                </div>
                            </div>
                        </div>

                        <h3 class="form-section">Particulars
                            <a href="javascript:;" onclick="AddExpenseParticular();"
                                class="btn btn-sm green pull-right"> <i class="fa fa-plus"> </i> Add new row </a>
                        </h3>
                        <div class="table-scrollable">
                            <table class="table table-bordered table-hover" id="particular_table">
                                <thead>
                                    <tr>
                                        <th class="td-c">
                                            <label class="control-label">Particular <span
                                                    class="required">*</span></label>
                                        </th>
                                        <th class="td-c">
                                            <label class="control-label">SAC/HSN Code <span
                                                    class="required"></span></label>
                                        </th>
                                        <th class="td-c">
                                            <label class="control-label">Unit <span class="required">*</span></label>

                                        </th>
                                        <th class="td-c">
                                            <label class="control-label">Rate <span class="required">*</span></label>
                                        </th>
                                        <th class="td-c">
                                            <label class="control-label">Tax <span class="required"></span></label>
                                        </th>
                                        <th class="td-c">
                                            <label class="control-label">Total <span class="required"></span></label>
                                        </th>
                                        <th class="td-c">
                                            <label class="control-label">Action <span class="required"></span></label>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="new_particular">
                                    {$sub_total=0}
                                    {$total=0}
                                    {$cgst=0}
                                    {$igst=0}
                                    {foreach from=$particular item=v}
                                        <tr>
                                            <td>
                                                <input type="text" required="" value="{$v.desc}" name="particular[]"
                                                    class="form-control " placeholder="Particular">
                                            </td>
                                            <td><input type="text" value="{$v.hsnSc}" name="sac[]" class="form-control "
                                                    placeholder="SAC/HSN Code"></td>
                                            <td><input required="" value="{$v.qty}" type="number" step="1" name="unit[]"
                                                    onblur="calculateexpensecost();" class="form-control "
                                                    placeholder="Unit"></td>
                                            <td>
                                                <input type="number" value="{$v.txval}" required="" step="0.01"
                                                    name="rate[]" onblur="calculateexpensecost();" class="form-control "
                                                    placeholder="Rate">
                                            </td>
                                            <td>
                                                {if $v.irt>0}
                                                    {$gst=$v.irt}
                                                {else}
                                                    {$gst=$v.crt*2}
                                                {/if}
                                                <select class="form-control " style="width: 120px;"
                                                    onchange="calculateexpensecost();" name="tax[]">
                                                    <option value="0">GST</option>
                                                    <option value="1">Non Taxable</option>
                                                    <option value="2">GST @0%</option>
                                                    <option {if $gst==5} selected {/if} value="3">GST @5%</option>
                                                    <option {if $gst==12} selected {/if} value="4">GST @12%</option>
                                                    <option {if $gst==18} selected {/if} value="5">GST @18%</option>
                                                    <option {if $gst==28} selected {/if} value="6">GST @28%</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" name="total[]" value="{$v.txval*$v.qty}"
                                                    class="form-control " readonly="">
                                                <input type="hidden" name="total_amt[]" value="{$v.sval}">
                                                <input type="hidden" name="particular_id[]" value="{$v.id}">
                                                {$sub_total=$sub_total+$v.txval}
                                                {$total=$total+$v.sval}
                                                {$cgst=$cgst+$v.camt}
                                                {$igst=$igst+$v.iamt}
                                            </td>
                                            <td>
                                                <a href="javascript:;"
                                                    onclick="$(this).closest('tr').remove();calculateexpensecost();"
                                                    class="btn btn-sm red"> <i class="fa fa-times"> </i></a>
                                            </td>
                                        </tr>
                                    {/foreach}
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" style="border-bottom: none;"></td>
                                        <td colspan="2"><label>Sub Total</label></td>
                                        <td colspan="2"><input type="text" class="form-control" readonly=""
                                                id="sub_total" name="sub_total"
                                                value="{$sub_total|string_format:"%.2f"}"></td>
                                    </tr>

                                    <tr id="cgst" {if $cgst==0} style="display: none;" {/if}>
                                        <td colspan="3" style="border: none;"></td>
                                        <td colspan="2"><label>CGST</label></td>
                                        <td colspan="2"><input type="text" class="form-control" readonly=""
                                                id="cgst_amt" name="cgst_amt" value="{$cgst|string_format:"%.2f"}"></td>
                                    </tr>
                                    <tr id="sgst" {if $cgst==0} style="display: none;" {/if}>
                                        <td colspan="3" style="border: none;"></td>
                                        <td colspan="2"><label>SGST</label></td>
                                        <td colspan="2"><input type="text" class="form-control" readonly=""
                                                id="sgst_amt" name="sgst_amt" value="{$cgst|string_format:"%.2f"}"></td>
                                    </tr>
                                    <tr id="igst" {if $igst==0} style="display: none;" {/if}>
                                        <td colspan="3" style="border: none;"></td>
                                        <td colspan="2"><label>IGST</label></td>
                                        <td colspan="2"><input type="text" class="form-control" readonly=""
                                                id="igst_amt" name="igst_amt" value="{$igst|string_format:"%.2f"}"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" style="border: none;"></td>
                                        <td colspan="2"><label>Total</label></td>
                                        <td colspan="2"><input type="text" class="form-control" readonly="" id="total"
                                                name="total_amount" value="{$total|string_format:"%.2f"}">
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <!-- End profile details -->
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-offset-5 col-md-6">
                                    <input type="hidden" value="{$link}" name="invoice_id">
                                    <input type="hidden" id="gst_type" {if $det.splyTy=='INTRA'} value="intra" 
                                    {else}
                                        value="inter" {/if} name="splyTy">
                                    <input type="hidden" id="merchant_state_code" value="{$det.gstin|substr:0:2}">
                                    <input type="hidden" name="gstin" value="{$det.gstin}">
                                    <input type="hidden" name="ref" value="{$ref}">
                                    <input type="submit" value="Save" class="btn blue">
                                    <a href="/merchant/gst/listinvoices" class="btn default">Cancel</a>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>