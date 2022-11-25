<div class="row">
    <div class="col-md-12">
        <div class="col-md-5">
            <table class="table table-bordered">
                <tbody id="tb_gross_sale">
                    <tr>
                        <td colspan="3" class="td-c">
                            <b>Daily Gross Sales</b>
                            <a onclick="addGrossSaleRow('nonbrand');" class="btn green btn-xs pull-right"><i class="fa fa-plus"></i></a>
                        </td>
                    </tr>
                    <tr>
                        <td class="td-c">
                            <b>Date</b>
                        </td>
                        <td class="td-c">
                            <b>Chetty's</b>
                        </td>
                        <td class="td-c">
                            <b>Non Chetty's</b>
                        </td>
                    </tr>
                    @if($mode=='update')
                    @php $gross_sale=0; @endphp
                    @php $sale_tax=0; @endphp
                    @php $net_sale=0; @endphp
                    @php $non_brand_gross_sale=0; @endphp
                    @php $non_brand_sale_tax=0; @endphp
                    @php $non_brand_net_sale=0; @endphp
                    @if(!empty($sale_details))
                    @foreach($sale_details as $v)
                    @if($v->status==1)
                    <tr>
                        <td class="td-c">
                            <input type="text" required="" name="sale_date[]" value='<x-localize :date="$v->date" type="date" />' class="form-control date-picker" placeholder="Date" autocomplete="off" {!!$validate->date!!} data-date-format="{{ Session::get('default_date_format')}}">
                        </td>
                        <td class="td-c">
                            <input type="hidden" name="gs_id[]" value="{{$v->id}}">
                            <input type="number" step="0.01" placeholder="Gross sale" onblur="calculateFranchiseSale();" required="" value="{{$v->gross_sale}}" name="gross_sale[]" class="form-control">
                            @php $gross_sale=$v->gross_sale+$gross_sale; @endphp
                            @php $sale_tax=$v->tax+$sale_tax; @endphp
                            @php $net_sale=$v->billable_sale+$net_sale; @endphp

                        </td>
                        <td class="td-c">
                            <input type="number" step="0.01" placeholder="Gross sale" onblur="calculateFranchiseSale();" required="" value="{{$v->non_brand_gross_sale}}" name="non_brand_gross_sale[]" class="form-control">
                            @php $non_brand_gross_sale=$v->non_brand_gross_sale+$non_brand_gross_sale; @endphp
                            @php $non_brand_sale_tax=$v->non_brand_tax+$non_brand_sale_tax; @endphp
                            @php $non_brand_net_sale=$v->non_brand_billable_sale+$non_brand_net_sale; @endphp
                        </td>
                        <td>
                            <a href="javascript:;" onclick="$(this).closest('tr').remove();calculateFranchiseSale();" class="btn btn-xs red"> <i class="fa fa-times"> </i></a>
                        </td>
                    </tr>
                    @endif
                    @endforeach
                    @endif
                    @else
                    <tr>
                        <td class="td-c">
                            <input type="text" required="" name="sale_date[]" class="form-control date-picker" placeholder="Date" autocomplete="off" {!!$validate->date!!} data-date-format="{{ Session::get('default_date_format')}}>
                        </td>
                        <td class="td-c">
                            <input type="number" step="0.01" placeholder="Gross sale" onblur="calculateFranchiseSale();" required="" name="gross_sale[]" class="form-control">
                        </td>
                        <td class="td-c">
                            <input type="number" step="0.01" placeholder="Gross sale" onblur="calculateFranchiseSale();" required="" name="non_brand_gross_sale[]" class="form-control">
                            <input type="hidden" name="gs_id[]" value="0">
                        </td>
                        <td>
                            <a href="javascript:;" onclick="$(this).closest('tr').remove();calculateFranchiseSale();" class="btn btn-xs red"> <i class="fa fa-times"> </i></a>
                        </td>
                    </tr>
                    @endif
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
                        <td colspan="4" class="td-c">
                            <b>Franchise Fees</b>
                        </td>
                    </tr>
                    <tr>
                        <td class="td-c">
                        </td>
                        <td colspan="2" class="td-c">
                            <b>Chetty's</b>
                        </td>
                        <td colspan="2" class="td-c">
                            <b>Non Chetty's</b>
                        </td>
                    </tr>
                    <tr>
                        <td class="td-c">
                            Gross Billable Sales
                        </td>
                        <td colspan="2" class="td-r">
                            <input type="text" id="gbs" name="gross_bilable_sale" class="form-control td-r" value="{{$gross_sale??0}}" readonly>
                        </td>
                        <td colspan="2" class="td-r">
                            <input type="text" id="nbgbs" name="non_brand_gross_bilable_sale" class="form-control td-r" value="{{$non_brand_gross_sale??0}}" readonly>
                        </td>
                    </tr>
                    <tr>
                        <td class="td-c">
                            Add: CGST and SGST 5.00 %
                        </td>
                        <td colspan="2" class="td-r">
                            <input type="number" step="0.01" id="sale_tax" name="sale_tax" class="form-control td-r" onblur="franchiseSummary();" value="{{$sale_tax??0}}">
                        </td>
                        <td colspan="2" class="td-r">
                            <input type="number" step="0.01" id="nbsale_tax" name="non_brand_sale_tax" class="form-control td-r" onblur="franchiseSummary();" value="{{$non_brand_sale_tax??0}}">
                        </td>
                    </tr>
                    <tr>
                        <td class="td-c">
                            Net Billable Sales
                        </td>
                        <td colspan="2" class="td-r">
                            <input type="text" id="nbs" name="net_bilable_sale" class="form-control td-r" value="{{$net_sale??0}}" readonly>
                        </td>
                        <td colspan="2" class="td-r">
                            <input type="text" id="nbnbs" name="non_brand_net_bilable_sale" class="form-control td-r" value="{{$non_brand_net_sale??0}}" readonly>
                        </td>
                    </tr>
                    <tr>
                        <td class="td-c">
                            Gross Franchisee Fee on Net Billable
                        </td>
                        <td class="td-r">
                            <input type="text" id="gcp" name="gross_comm_percent" onblur="franchiseSummary();" class="form-control td-r" value="{{$sale_summary->commision_fee_percent??0}}">
                        </td>
                        <td class="td-r">
                            <input type="text" id="gca" name="gross_comm_amt" class="form-control td-r" value="{{$sale_summary->gross_fee??0}}" readonly>
                        </td>
                        <td class="td-r">
                            <input type="text" id="nbgcp" name="non_brand_gross_comm_percent" onblur="franchiseSummary();" class="form-control td-r" value="{{$sale_summary->non_brand_commision_fee_percent??0}}">
                        </td>
                        <td class="td-r">
                            <input type="text" id="nbgca" name="non_brand_gross_comm_amt" class="form-control td-r" value="{{$sale_summary->non_brand_gross_fee??0}}" readonly>
                        </td>
                    </tr>
                    <tr>
                        <td class="td-c">
                            Less: Waiver
                        </td>
                        <td class="td-r">
                            <input type="text" id="wcp" name="waiver_comm_percent" onblur="franchiseSummary();" class="form-control td-r" value="{{$sale_summary->commision_waiver_percent??0}}">
                        </td>
                        <td class="td-r">
                            <input type="text" id="wca" name="waiver_comm_amt" class="form-control td-r" value="{{$sale_summary->waiver_fee??0}}" readonly>
                        <td class="td-r">
                            <input type="text" id="nbwcp" name="non_brand_waiver_comm_percent" onblur="franchiseSummary();" class="form-control td-r" value="{{$sale_summary->non_brand_commision_waiver_percent??0}}">
                        </td>
                        <td class="td-r">
                            <input type="text" id="nbwca" name="non_brand_waiver_comm_amt" class="form-control td-r" value="{{$sale_summary->non_brand_waiver_fee??0}}" readonly>
                        </td>
                    </tr>
                    <tr>
                        <td class="td-c">
                            Net Franchise Fee receivable
                        </td>
                        <td class="td-r">
                            <input type="text" id="ncp" name="net_comm_percent" onblur="franchiseSummary();" class="form-control td-r" value="{{$sale_summary->commision_net_percent??0}}">
                        </td>
                        <td class="td-r">
                            <input type="text" id="nca" name="net_comm_amt" class="form-control td-r" value="{{$sale_summary->net_fee??0}}" readonly>
                        </td>
                        <td class="td-r">
                            <input type="text" id="nbncp" name="non_brand_net_comm_percent" onblur="franchiseSummary();" class="form-control td-r" value="{{$sale_summary->non_brand_commision_net_percent??0}}">
                        </td>
                        <td class="td-r">
                            <input type="text" id="nbnca" name="non_brand_net_comm_amt" class="form-control td-r" value="{{$sale_summary->non_brand_net_fee??0}}" readonly>
                        </td>
                    </tr>
                    <tr>
                        <td class="td-c">
                            Penalty on outstanding amt
                        </td>
                        <td colspan="4" class="td-r">
                            <input type="text" id="penalty" name="penalty" onblur="franchiseSummary();" class="form-control td-r" value="{{$sale_summary->penalty??0}}">
                        </td>
                    </tr>
                    <tr>
                        <td class="td-c">
                            Franchisee fees Payable
                        </td>
                        <td colspan="4" class="td-r">
                            <input type="text" id="particulartotal" name="totalcost" class="form-control td-r" value="{{$info->basic_amount??0}}" readonly>
                        </td>
                    </tr>
                    <tr>
                        <td class="td-c">
                            Total Amount (FEE)
                        </td>
                        <td colspan="4" class="td-r">
                            <input type="text" id="invoice_total" class="form-control td-r" @if($mode=='update' ) value="{{$info->basic_amount+$info->tax_amount}}" @endif readonly>
                        </td>
                    </tr>
                    <tr>
                        <td class="td-c">
                            Adjustment
                        </td>
                        <td colspan="4" class="td-r">
                            <input type="text" name="advance" id="adjustment" onblur="franchiseSummary();" class="form-control td-r" value="{{$info->advance??0}}">
                        </td>
                    </tr>
                    <tr>
                        <td class="td-c">
                            Previous outstanding
                        </td>
                        <td colspan="4" class="td-r">
                            <input type="text" id="previous_due" name="previous_dues" onblur="franchiseSummary();" class="form-control td-r" value="{{$info->previous_due??0}}">
                        </td>
                    </tr>
                    <tr>
                        <td class="td-c">
                            Total royalty to be Paid with Previous outstanding
                        </td>
                        <td colspan="4" class="td-r">
                            <input type="text" id="grand_total" class="form-control td-r" value="{{$info->absolute_cost??0}}" readonly>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>
</div>