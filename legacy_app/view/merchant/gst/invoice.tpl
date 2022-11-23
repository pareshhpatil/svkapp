<div class="page-content" style="min-height:901px">
    <div class="row no-margin">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            {if isset($success)}
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert"></button>
                    <strong>Success!</strong>{$success}
                </div>
            {/if}
            <h3 class="page-title">View {if $det.dty=='RI'}invoice {elseif $det.dty=='C'}Credit note {/if}&nbsp;</h3>
            <!-- BEGIN PORTLET-->
            <div class="invoice" style="max-width: 900px;border: 1px solid lightgrey !important;text-align: left;">
                <div class="row no-margin">
                    <div class="col-md-6" style="padding-left: 0px;padding-right: 0px;">
                        <div class="" style="">
                            <table class="table " style="margin: 0px 0 0px 0 !important;">

                                <tbody>
                                    <tr>
                                        <td class="bx-0" style="min-width: 120px;">
                                            <b>Invoice Type</b>
                                        </td>
                                        <td class="bx-0" style="min-width: 120px;">
                                            {$det.invTyp}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="bx-0" style="min-width: 120px;">
                                            <b>Customer name</b>
                                        </td>
                                        <td class="bx-0" style="min-width: 120px;">
                                            {$det.cname}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="bx-0" style="min-width: 120px;">
                                            <b>Customer GST</b>
                                        </td>
                                        <td class="bx-0" style="min-width: 120px;">
                                            {$det.ctin}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="bx-0" style="min-width: 120px;">
                                            <b>Place of supply</b>
                                        </td>
                                        <td class="bx-0" style="min-width: 120px;">
                                            {$state.{$det.pos}}
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>

                    </div>

                    <div class="col-md-6 invoice-payment" style="padding-left: 0px;padding-right: 0px;">
                        <div class="">
                            <table class="table" style="margin: 0px 0 0px 0 !important;">
                                <tbody>

                                    {if $det.dty!='RI'}
                                        <tr>
                                            <td class="bx-0" style="min-width: 120px;">
                                                <b>Note number</b>
                                            </td>
                                            <td class="bx-0" style="min-width: 120px;">
                                                {$det.ntNum}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="bx-0" style="min-width: 120px;">
                                                <b>Note date</b>
                                            </td>
                                            <td class="bx-0" style="min-width: 120px;">
                                                {$det.ntDt|date_format:"%d %b %Y"}
                                            </td>
                                        </tr>
                                    {/if}
                                    <tr>
                                        <td class="bx-0" style="min-width: 120px;">
                                            <b>Invoice number</b>
                                        </td>
                                        <td class="bx-0" style="min-width: 120px;">
                                            {$det.inum}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="bx-0" style="min-width: 120px;">
                                            <b>Bill date</b>
                                        </td>
                                        <td class="bx-0" style="min-width: 120px;">
                                            {$det.pdt|date_format:"%d %b %Y"}
                                        </td>
                                    </tr>


                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-scrollable">
                            <table class="table table-bordered table-condensed">
                                <thead>
                                    <tr>
                                        <th class="col-md-5" style="border-bottom: 1px solid #ddd;">
                                            Particular
                                        </th>
                                        <th class="col-md-2 td-c" style="border-bottom: 1px solid #ddd;">
                                            SAC/HSN
                                        </th>
                                        <th class="col-md-3 td-c" style="border-bottom: 1px solid #ddd;">
                                            Qty
                                        </th>
                                        <th class="col-md-3 td-c" style="border-bottom: 1px solid #ddd;">
                                            Rate
                                        </th>
                                        <th class="col-md-3 td-c" style="border-bottom: 1px solid #ddd;">
                                            GST(%)
                                        </th>
                                        <th class="col-md-3 td-c" style="border-bottom: 1px solid #ddd;">
                                            Amount
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {$sub_total=0}
                                    {$total=0}
                                    {$cgst=0}
                                    {$igst=0}
                                    {foreach from=$particular item=v}
                                        <tr>

                                            <td class="col-md-5" style="border-top: 0;border-bottom: 0;">
                                                {$v.desc}
                                            </td>
                                            <td class="col-md-3 td-c" style="border-top: 0;border-bottom: 0;">
                                                {$v.hsnSc}
                                            </td>
                                            <td class="col-md-3 td-c" style="border-top: 0;border-bottom: 0;">
                                                {$v.qty}
                                            </td>
                                            <td class="col-md-3 td-c" style="border-top: 0;border-bottom: 0;">
                                                {$v.txval}
                                            </td>
                                            <td class="col-md-3 td-c" style="border-top: 0;border-bottom: 0;">
                                                {if $v.irt>0}
                                                    {$gst=$v.irt}
                                                {else}
                                                    {$gst=$v.crt*2}
                                                {/if}
                                                {$gst}
                                            </td>
                                            <td class="col-md-3 td-c" style="border-top: 0;border-bottom: 0;">
                                                {$v.txval*$v.qty}
                                                {$sub_total=$sub_total+$v.txval*$v.qty}
                                                {$total=$total+$v.sval}
                                                {$cgst=$cgst+$v.camt}
                                                {$igst=$igst+$v.iamt}
                                            </td>
                                        </tr>
                                    {/foreach}


                                    <tr>
                                        <td colspan="3" style="border-bottom: none;"></td>
                                        <td colspan="2"><label>Sub Total</label></td>
                                        <td colspan="2">{$sub_total|string_format:"%.2f"}</td>
                                    </tr>

                                    <tr id="cgst" {if $cgst==0} style="display: none;" {/if}>
                                        <td colspan="3" style="border: none;"></td>
                                        <td colspan="2"><label>CGST</label></td>
                                        <td colspan="2">{$cgst|string_format:"%.2f"}</td>
                                    </tr>
                                    <tr id="sgst" {if $cgst==0} style="display: none;" {/if}>
                                        <td colspan="3" style="border: none;"></td>
                                        <td colspan="2"><label>SGST</label></td>
                                        <td colspan="2">{$cgst|string_format:"%.2f"}</td>
                                    </tr>
                                    <tr id="igst" {if $igst==0} style="display: none;" {/if}>
                                        <td colspan="3" style="border: none;"></td>
                                        <td colspan="2"><label>IGST</label></td>
                                        <td colspan="2">{$igst|string_format:"%.2f"}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" style="border: none;"></td>
                                        <td colspan="2"><label>Total</label></td>
                                        <td colspan="2">{$total|string_format:"%.2f"}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>

                    </div>
                </div>
                <div class="row no-margin">
                    <div class="col-md-12">
                        <a class="btn green hidden-print margin-bottom-5 pull-right" style="margin-right: 20px;"
                                    href="/merchant/gst/updateinvoice/{$link}{if $ref!=''}/{$ref}{/if}">
                            Update
                        </a>
                    </div>
                </div>
            </div>

            <!-- END PORTLET-->
        </div>
    </div>





</div>