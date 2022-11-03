<div class="row">
                <div class="col-md-12">
                    <div class="col-md-5">
                        <table class="table table-bordered">
                            <tbody id="tb_gross_sale">
                                <tr>
                                    <td colspan="3" class="td-c">
                                        <b>Daily Gross Sales</b>
                                        <a onclick="addGrossSaleRow();" class="btn green btn-xs pull-right"><i class="fa fa-plus"></i></a>
                                    </td>
                                </tr>
                                    <tr>
                                        <td class="td-c">
                                        <input type="text" required=""  name="sale_date[]" class="form-control date-picker" placeholder="Date"  autocomplete="off" data-date-format="dd M yyyy" >
                                        </td>
                                        <td class="td-c">
                                        <input type="number" step="0.01" placeholder="Gross sale" onblur="calculateFranchiseSale();" required=""  name="gross_sale[]" class="form-control"   >
                                        <input type="hidden" name="gs_id[]" value="0">
                                        </td>
                                        <td>
                                        <a href="javascript:;" onclick="$(this).closest('tr').remove();calculateFranchiseSale();" class="btn btn-xs red"> <i class="fa fa-times"> </i></a>
                                        </td>
                                    </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-7">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td class="td-c">
                                        <b>Summary</b>
                                    </td>
                                    <td colspan="2" class="td-c">
                                        <b>Franchise Fees</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-c">
                                        Gross Billable Sales
                                    </td>
                                    <td colspan="2" class="td-r">
                                        <input type="text" id="gbs" name="gross_bilable_sale" class="form-control td-r" value="0.00" readonly>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-c">
                                        Less : CGST and SGST 5.00 %
                                    </td>
                                    <td colspan="2" class="td-r">
                                        <input type="number" step="0.01" id="sale_tax" name="sale_tax" class="form-control td-r" onblur="franchiseSummary();" value="0.00" >
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-c">
                                        Net Billable Sales
                                    </td>
                                    <td colspan="2" class="td-r">
                                        <input type="text" id="nbs" name="net_bilable_sale" class="form-control td-r" value="0.00" readonly>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-c">
                                        Gross Franchisee Fee on Net Billable
                                    </td>
                                    <td class="td-r">
                                        <input type="text" id="gcp" name="gross_comm_percent" onblur="franchiseSummary();" class="form-control td-r" value="0.00">
                                    </td>
                                    <td  class="td-r">
                                        <input type="text" id="gca" name="gross_comm_amt" class="form-control td-r" value="0.00" readonly>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-c">
                                        Less: Waiver
                                    </td>
                                    <td class="td-r">
                                        <input type="text" id="wcp" name="waiver_comm_percent" onblur="franchiseSummary();" class="form-control td-r" value="0.00">
                                    </td>
                                    <td  class="td-r">
                                       <input type="text" id="wca" name="waiver_comm_amt" class="form-control td-r" value="0.00" readonly>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-c">
                                       Net Franchise Fee receivable
                                    </td>
                                   <td class="td-r">
                                        <input type="text" id="ncp" name="net_comm_percent" onblur="franchiseSummary();" class="form-control td-r" value="0.00">
                                    </td>
                                    <td  class="td-r">
                                       <input type="text" id="nca" name="net_comm_amt" class="form-control td-r" value="0.00" readonly>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-c">
                                        Penalty on outstanding amt
                                    </td>
                                    <td colspan="2" class="td-r">
                                        <input type="text" id="penalty" name="penalty" onblur="franchiseSummary();" class="form-control td-r" value="0.00">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-c">
                                        Franchisee fees Payable
                                    </td>
                                    <td colspan="2" class="td-r">
                                        <input type="text" id="particulartotal" name="totalcost" class="form-control td-r" value="0.00" readonly>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-c">
                                        Total Amount (FEE)
                                    </td>
                                    <td colspan="2" class="td-r">
                                        <input type="text" id="invoice_total" class="form-control td-r" value="0.00" readonly>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-c">
                                        Previous outstanding
                                    </td>
                                    <td colspan="2" class="td-r">
                                        <input type="text" id="previous_due" name="previous_dues" onblur="franchiseSummary();" class="form-control td-r" value="0.00" >
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-c">
                                        Total FF to be Paid with Previous outstanding
                                    </td>
                                    <td colspan="2" class="td-r">
                                        <input type="text" id="grand_total" class="form-control td-r" value="0.00" readonly>
                                    </td>
                                </tr>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            