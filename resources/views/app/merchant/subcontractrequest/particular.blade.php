@if(isset($template_info->template_type) && $template_info->template_type!='scan')
<!-- add particulars label -->
<h3 class="form-section">Add particulars
</h3>
<a data-cy="add_particulars_btn" href="javascript:;" onclick="AddInvoiceParticularRow();" class="btn btn-sm green pull-right"> <i class="fa fa-plus"> </i> Add new row </a>
    @if($product_taxation_type=='3')
    <div class="row">
        <div class="col-md-2">
            <select id="product_taxation_type" required onchange="setProductTaxation(this.value);calculateamt();calculatetax(undefined,undefined,'1')" class="form-control" name="taxation_type">
                <option @if($invoice_product_taxation == '1') selected @endif value="1">
                    <div>
                        <b>Product cost exclusive of tax</b>
                    </div>
                </option>
                <option @if($invoice_product_taxation == '2') selected @endif value="2">
                    <div>
                        <b> Product cost inclusive of tax</b>
                    </div>
                </option>
            </select>
        </div>
    </div>
    @endif
<div class="table-scrollable">
    <table class="table table-bordered table-hover" id="particular_table">
        @if($mode=='update')
        @php $particular_column= json_decode($template_info->particular_column,1); @endphp
        @if(!empty($particular_column))
        <thead>
            <tr>
                @foreach($particular_column as $k=>$v)
                @if($k!='sr_no')
                <th class="td-c">
                    {{$v}}
                </th>
                @endif
                @endforeach
                <th class="td-c">
                    Actions
                </th>
            </tr>
        </thead>


        <tbody id="new_particular">
            @php $discount_perc=0; @endphp
            @php $rate=0; @endphp
            @if(!empty($invoice_particular))
            @php $int=1; @endphp
            @foreach($invoice_particular as $dp)
            <tr>
                @foreach($particular_column as $k=>$v)
                @php $readonly=''; @endphp
                @if($k!='sr_no')
                @php $value=$dp->{$k}; @endphp
                <td>
                    @if($k == 'rate' || $k == 'qty' || $k == 'discount_perc' || $k == 'discount' || $k == 'total_amount')
                    @if($k == 'amount' || $k == 'rate')
                    @php $rate=1; @endphp
                    @endif
                    @if($rate==1 && $k == 'total_amount')
                    @php $readonly='readonly'; @endphp
                    @endif
                    @if($k == 'discount_perc')
                    @php $discount_perc=1; @endphp
                    @endif
                    @if($discount_perc==1 && $k == 'discount')
                    @php $readonly='readonly'; @endphp
                    @endif
                    <input type="number" step="0.01" {{$readonly}} onblur="calculateamt();calculatetax(undefined,undefined,'1');" name="{{$k}}[]" data-cy="particular_{{$k}}{{$int}}" class="form-control " value="{{$value}}">
                    @elseif($k=='gst')
                    <select name="gst[]" data-cy="particular_gst{{$int}}" onchange="setTaxApplicableAmt({{$int}},this.value);calculateamt();calculatetax(undefined,undefined,'1');" style="min-width:80px;" class="form-control ">
                        <option value="">Select</option>
                        <option value="0">0%</option>
                        <option @if($dp->{$k}==5) selected="" @endif value="5">5%</option>
                        <option @if($dp->{$k}==12) selected="" @endif value="12">12%</option>
                        <option @if($dp->{$k}==18) selected="" @endif value="18">18%</option>
                        <option @if($dp->{$k}==28) selected="" @endif value="28">28%</option>
                    </select>
                        @else
                        @if($k=='item')
                        @php $prod_ex=0; @endphp
                        <select required style="width: 100%; " onchange="product_rate(this.value, this);calculateamt();calculatetax(undefined,undefined,'1');" data-cy="particular_item{{$int}}" name="item[]" data-placeholder="Type or Select" class="form-control  productselect">
                            <option value="">Select Product</option>
                            @if(!empty($product_list))
                            @foreach($product_list as $pk=>$vk)
                            @if($dp->{$k}==$pk)
                            @php $prod_ex=1; @endphp
                            <option selected="" value="{{$pk}}">{{$pk}}</option>
                            @else
                            <option value="{{$pk}}">{{$pk}}</option>
                            @endif
                            @endforeach
                            @endif
                            @if($prod_ex==0)
                            <option selected="" value="{{$value}}">{{$value}}</option>
                            @endif
                        </select>
                        @elseif($k=='product_expiry_date')
                        <input pattern="^(0[1-9]|[1-2][0-9]|3[0-1]) [A-Za-z]{3} [0-9]{4}$" 
                        class="form-control form-control-inline date-picker" 
                        type="text" name="{{$k}}[]"
                        value='<x-localize :date="$value" type="date" />'
                        autocomplete="off"  data-cy="particular_{{$k}}{{$int}}" data-date-format="{{ Session::get('default_date_format')}}" placeholder="Expiry Date" />
                        @else
                        <input type="text" name="{{$k}}[]" data-cy="particular_{{$k}}{{$int}}" class="form-control " value="{{$value}}">
                        @endif
                        @endif
                        @if($k == 'qty')
                        <span id="qtyavailable{{$int}}" class="help-block "></span>
                        @endif
                </td>
                @endif
                
                @endforeach
                <td class="td-c">
                <input type="hidden" id="pint{{$int}}" name="pint[]" value="{{$int}}">
                    <input type="hidden" name="particular_id[]" value="{{$dp->id}}"> <input type="hidden" name="product_gst[]" value="{{$dp->gst}}" data-cy="product_gst{{$int}}"/> <a data-cy="particular-remove{{$int}}" href="javascript:;" onclick="setTaxApplicableAmt({{$int}});$(this).closest('tr').remove();
                            calculateamt();calculatetax(undefined,undefined,'1');" class="btn btn-sm red"> <i class="fa fa-times"> </i> </a></td>
            </tr>
            @php $int++; @endphp
            @endforeach
            @endif
        </tbody>
        <tfoot>
            <tr class="warning">
                @if(!empty($particular_column))
                @foreach($particular_column as $k=>$v)
                @if($k!='sr_no')
                <th class="td-c" >
                @if($k=='item')
                <input type="text" value="{{$template_info->particular_total}}" class="form-control " readonly="">
                @elseif($k=='total_amount')
                <input type="text" id="particulartotal" data-cy="particular-total" name="totalcost" value="{{$info->basic_amount}}" class="form-control " readonly="">
                @endif
                </th>
                @endif
                @endforeach
                @endif
                <th></th>
            </tr>
        </tfoot>
        @endif

        @endif
    </table>
</div>
@endif