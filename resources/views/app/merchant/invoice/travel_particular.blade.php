@if (isset($properties['vehicle_section']))
    <!-- add particulars label -->
    <h3 class="form-section">{{ $properties['vehicle_section']['title'] ?? 'Vehicle Booking' }}
    </h3>
    <a href="javascript:;" onclick="AddInvoiceParticularRow();" class="btn btn-sm green pull-right"> <i class="fa fa-plus">
        </i> Add new row </a>
    @if ($product_taxation_type == '3')
        <div class="row">
            <div class="col-md-2">
                <select id="product_taxation_type" required
                    onchange="setProductTaxation(this.value);calculateamt();calculatetax(undefined,undefined,'1')"
                    class="form-control" name="taxation_type">
                    <option @if ($invoice_product_taxation == '1') selected @endif value="1">
                        <div>
                            <b>Product cost exclusive of tax</b>
                        </div>
                    </option>
                    <option @if ($invoice_product_taxation == '2') selected @endif value="2">
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
            @if ($mode == 'update')
                @php $particular_column= json_decode($template_info->particular_column,1); @endphp
                @if (!empty($particular_column))
                    <thead>
                        <tr>
                            @foreach ($particular_column as $k => $v)
                                @if ($k != 'sr_no')
                                    <th class="td-c">
                                        {{ $v }}
                                    </th>
                                @endif
                            @endforeach
                            <th class="td-c">
                                Actions
                            </th>
                        </tr>
                    </thead>


                    <tbody id="new_particular">
                        @php $total_amount=0; @endphp
                        @php $discount_perc=0; @endphp
                        @if (!empty($invoice_particular))
                            @php $int=1; @endphp
                            @foreach ($invoice_particular as $dp)
                                <tr>
                                    @if (!empty($particular_column))
                                        @foreach ($particular_column as $k => $v)
                                            @php $readonly=''; @endphp
                                            @if ($k != 'sr_no')
                                                @php $value=$dp->{$k}; @endphp
                                                <td>
                                                    @if ($k == 'rate' ||
                                                        $k == 'qty' ||
                                                        $k == 'tax_amount' ||
                                                        $k == 'discount_perc' ||
                                                        $k == 'discount' ||
                                                        $k == 'total_amount')
                                                        @if ($k == 'discount_perc')
                                                            @php $discount_perc=1; @endphp
                                                        @endif
                                                        @if ($discount_perc == 1 && $k == 'discount')
                                                            @php $readonly='readonly'; @endphp
                                                        @endif
                                                        <input type="number" step="0.01" onblur="calculateamt();"
                                                            {{ $readonly }}
                                                            data-cy="particular-{{ $k }}{{ $int }}"
                                                            name="{{ $k }}[]" class="form-control "
                                                            value="{{ $value }}">
                                                    @elseif($k == 'gst')
                                                        <select name="gst[]"
                                                            data-cy="particular-gst{{ $int }}"
                                                            onchange="calculateamt();" style="min-width:80px;"
                                                            class="form-control ">
                                                            <option value="">Select</option>
                                                            <option value="0">0%</option>
                                                            <option @if ($value == 5) selected @endif
                                                                value="5">5%</option>
                                                            <option @if ($value == 12) selected @endif
                                                                value="12">12%</option>
                                                            <option @if ($value == 18) selected @endif
                                                                value="18">18%</option>
                                                            <option @if ($value == 28) selected @endif
                                                                value="28">28%</option </select>
                                                        @else
                                                            @if ($k == 'item')
                                                                @php $prod_ex=0; @endphp
                                                                <select required style="width: 100%; "
                                                                    data-cy="particular-item{{ $int }}"
                                                                    onchange="product_rate(this.value, this);"
                                                                    name="item[]" data-placeholder="Type or Select"
                                                                    class="form-control  productselect">
                                                                    <option value="">Select Product</option>
                                                                    @if (!empty($product_list) && isset($product_list))
                                                                        @foreach ($product_list as $pk => $vk)
                                                                            @if ($value == $pk)
                                                                                @php $prod_ex=1; @endphp
                                                                                <option selected
                                                                                    value="{{ $pk }}">
                                                                                    {{ $pk }}</option>
                                                                            @else
                                                                                <option value="{{ $pk }}">
                                                                                    {{ $pk }}</option>
                                                                            @endif
                                                                        @endforeach
                                                                    @endif
                                                                    @if ($prod_ex == 0)
                                                                        <option selected=""
                                                                            value="{{ $value }}">
                                                                            {{ $value }}</option>
                                                                    @endif
                                                                </select>
                                                            @else
                                                                <input type="text" name="{{ $k }}[]"
                                                                    data-cy="particular-{{ $k }}{{ $int }}"
                                                                    class="form-control " value="{{ $value }}">
                                                            @endif
                                                    @endif
                                                </td>
                                            @endif
                                        @endforeach
                                    @endif
                                    <td class="td-c"><input type="hidden" name="particular_id[]"
                                            value="{{ $dp->id }}"> <a href="javascript:;"
                                            data-cy="particular-remove{{ $int }}"
                                            onclick="$(this).closest('tr').remove();
                            calculateamt();"
                                            class="btn btn-sm red"> <i class="fa fa-times"> </i> </a></td>
                                </tr>
                                @php $total_amount=$total_amount+$dp->total_amount; @endphp
                                @php $int++; @endphp
                            @endforeach
                        @endif
                    </tbody>
                    <tfoot>
                        <tr class="warning">
                            @if (!empty($particular_column))
                                @foreach ($particular_column as $k => $v)
                                    @if ($k != 'sr_no')
                                        <th class="td-c">
                                            @if ($k == 'item')
                                                <input type="text" value="{{ $template_info->particular_total }}"
                                                    class="form-control " readonly="">
                                            @elseif($k == 'total_amount')
                                                <input type="text" id="particulartotal" name="totalcost"
                                                    data-cy="particular-total" value="{{ $total_amount }}"
                                                    class="form-control " readonly="">
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
    <!-- add particulars label ends -->
@endif

@if (isset($properties['travel_section']))
    <!-- add particulars label -->
    @php$tb_col = json_encode($properties['travel_section']['column']);
        $has_dttm='0';
        $dateortime = 0;
        if (isset($setting['has_datetime'])) {
            $has_dttm = $setting['has_datetime'];
        }
        
        if ($has_dttm == '1') {
            $dateortime = 2;
        }
        
    @endphp



    <script>
        var tb_col = '{!! $tb_col ?? '' !!}';
        var tb_has_datetime = '{!! $has_dttm ?? '0' !!}';
    </script>
    <h3 class="form-section">{{ $properties['travel_section']['title'] ?? 'Travel details' }}
        <a href="javascript:;" onclick="AddSecRow(tb_col,'tb',tb_has_datetime);" class="btn btn-sm green pull-right"> <i
                class="fa fa-plus"> </i> Add new row </a>
    </h3>

    <div class="table-scrollable">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    @php $int=0; @endphp
                    @if (!empty($properties['travel_section']['column']))
                        @foreach ($properties['travel_section']['column'] as $k => $v)
                            @if ($k != 'sr_no')
                                <th class="td-c">
                                    {{ $v }}
                                </th>
                                @php $int++; @endphp
                            @endif
                        @endforeach
                    @endif
                    <th class="td-c">

                    </th>

                </tr>
            </thead>
            <tbody id="new_sec_tb">
                @if ($mode == 'update')
                    @php $sec_total=0; @endphp
                    @if (!empty($ticket_detail))
                        @php $total_amount=0; @endphp
                        @php $rate=0; @endphp
                        @php $discount_perc=0; @endphp
                        @php $numrow=1; @endphp
                        @foreach ($ticket_detail as $v)
                            @if ($v->type == 1)
                                <tr>
                                    @if (!empty($properties['travel_section']['column']))
                                        @foreach ($properties['travel_section']['column'] as $k => $tb)
                                            @php $readonly=''; @endphp
                                            @if ($k != 'sr_no')
                                                @php
                                                    if (isset($v->{$k})) {
                                                        $value = $v->{$k};
                                                    } else {
                                                        $value='';
                                                    }
                                                @endphp
                                                <td class="td-c">
                                                    @if ($k == 'charge' ||
                                                        $k == 'amount' ||
                                                        $k == 'rate' ||
                                                        $k == 'qty' ||
                                                        $k == 'tax_amount' ||
                                                        $k == 'discount_perc' ||
                                                        $k == 'discount' ||
                                                        $k == 'total_amount')
                                                        @if ($k == 'amount' || $k == 'rate')
                                                            @php $rate=1; @endphp
                                                        @endif
                                                        @if ($k == 'discount_perc')
                                                            @php $discount_perc=1; @endphp
                                                        @endif
                                                        @if ($rate == 1 && $k == 'total_amount')
                                                            @php $readonly='readonly'; @endphp
                                                        @endif
                                                        @if ($discount_perc == 1 && $k == 'discount')
                                                            @php $readonly='readonly'; @endphp
                                                        @endif
                                                        @if ($k == 'total_amount')
                                                            <input onblur="calculateSecamt('tb');" type="text"
                                                                data-cy="travel-tb-{{ $k }}{{ $numrow }}"
                                                                value="{{ $v->total }}" {{ $readonly }}
                                                                name="sec_{{ $k }}[]"
                                                                data-cy="particular-tb-{{ $k }}{{ $int }}"
                                                                class="form-control  pc-input">
                                                            @php $total_amount=$total_amount+$v->total; @endphp
                                                        @else
                                                            <input onblur="calculateSecamt('tb');" type="text"
                                                                data-cy="travel-tb-{{ $k }}{{ $numrow }}"
                                                                value="{{ $value }}" {{ $readonly }}
                                                                name="sec_{{ $k }}[]"
                                                                data-cy="particular-tb-{{ $k }}{{ $int }}"
                                                                class="form-control  pc-input">
                                                        @endif
                                                    @elseif($k == 'gst')
                                                        <select name="sec_gst[]" onchange="calculateamt('sec_');"
                                                            data-cy="travel-tb-{{ $k }}{{ $numrow }}"
                                                            style="min-width:80px;" class="form-control ">
                                                            <option value="">Select</option>
                                                            <option value="0">0%</option>
                                                            <option @if ($v->{$k} == 5) selected @endif
                                                                value="5">5%</option>
                                                            <option @if ($v->{$k} == 12) selected @endif
                                                                value="12">12%</option>
                                                            <option @if ($v->{$k} == 18) selected @endif
                                                                value="18">18%</option>
                                                            <option @if ($v->{$k} == 28) selected @endif
                                                                value="28">28%</option </select>
                                                        @elseif($k == 'booking_date' || $k == 'journey_date')
                                                            <input type="text" name="sec_{{ $k }}[]"
                                                                data-cy="travel-tb-{{ $k }}{{ $numrow }}"
                                                                value='<x-localize :date="$value" type="datetime" />'
                                                                @if ($has_dttm == '1')  @endif
                                                                class="form-control @if ($has_dttm == '1') date form_datetime1 date-set @else date-picker @endif"
                                                                style="min-width:@if ($has_dttm == '1') 160px @else 110px @endif !important;"
                                                                autocomplete="off" data-date-format="{{ Session::get('default_date_format')}}">
                                                        @elseif($k == 'from')
                                                            <input type="text" value="{{ $v->from_station }}"
                                                                data-cy="travel-tb-{{ $k }}{{ $numrow }}"
                                                                name="sec_{{ $k }}[]"
                                                                class="form-control  pc-input">
                                                        @elseif($k == 'to')
                                                            <input type="text" value="{{ $v->to_station }}"
                                                                data-cy="travel-tb-{{ $k }}{{ $numrow }}"
                                                                name="sec_{{ $k }}[]"
                                                                class="form-control  pc-input">
                                                        @elseif($k == 'type')
                                                            <input type="text" value="{{ $v->vehicle_type }}"
                                                                data-cy="travel-tb-{{ $k }}{{ $numrow }}"
                                                                name="sec_{{ $k }}[]"
                                                                class="form-control  pc-input">
                                                        @else
                                                            <input type="text" value="{{ $value }}"
                                                                data-cy="travel-tb-{{ $k }}{{ $numrow }}"
                                                                name="sec_{{ $k }}[]"
                                                                class="form-control  pc-input" maxlength="500">
                                                    @endif
                                                </td>
                                            @endif
                                        @endforeach
                                    @endif
                                    <td>
                                        @if (!empty($travel_col))
                                            @foreach ($travel_col as $tc)
                                                @if (isset($properties['travel_section']['column'][$tc]))
                                                @else
                                                    <input type="hidden"
                                                        data-cy="travel-tb-{{ $tc }}{{ $numrow }}"
                                                        name="sec_{{ $tc }}[]" value="">
                                                @endif
                                            @endforeach
                                        @endif
                                        <input type="hidden" id="sectb{{ $numrow }}" name="sec_exist_id[]"
                                            value="{{ $v->id }}">
                                        <input type="hidden" name="sec_type_value[]" value="tb">
                                        <a href="javascript:;"
                                            onclick="$(this).closest('tr').remove();calculateSecamt('tb');calculateamt('sec_');"
                                            class="btn btn-sm red"> <i class="fa fa-times"> </i> </a>
                                    </td>
                                    @php $numrow++; @endphp
                                </tr>
                            @endif
                        @endforeach
                        @php $sec_total=$sec_total+$total_amount; @endphp
                    @endif
                @endif
            </tbody>
            <tbody>
                <tr>
                    <td colspan="{{ $int - 1 }}">
                        <b class="pull-right">Total Rs.</b>
                    </td>
                    <td class="td-c">
                        <input type="text" id="tb_sectotalamt" value="{{ $total_amount ?? 0 }}"
                            name="sec_calculate[]" readonly class="form-control">
                    </td>
                </tr>
            </tbody>

        </table>

    </div>
    @php$tc_col = json_encode($properties['travel_cancel_section']['column']);
    @endphp
    <script>
        var tc_col = '{!! $tc_col ?? '' !!}';
    </script>

    <h3 class="form-section">{{ $properties['travel_cancel_section']['title'] ?? 'Travel cancellation details' }}
        <a href="javascript:;" onclick="AddSecRow(tc_col,'tc',tb_has_datetime);" class="btn btn-sm green pull-right">
            <i class="fa fa-plus"> </i> Add new row </a>
    </h3>

    <div class="table-scrollable">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    @php $int=0; @endphp
                    @if (!empty($properties['travel_cancel_section']['column']))
                        @foreach ($properties['travel_cancel_section']['column'] as $k => $v)
                            @if ($k != 'sr_no')
                                <th class="td-c">
                                    {{ $v }}
                                </th>
                                @php $int++; @endphp
                            @endif
                        @endforeach
                    @endif
                    <th class="td-c">

                    </th>

                </tr>
            </thead>
            <tbody id="new_sec_tc">
                @php $total_amount=0; @endphp
                @php $rate=0; @endphp
                @php $discount_perc=0; @endphp
                @if (!empty($ticket_detail))
                    @php $numrow=1; @endphp
                    @foreach ($ticket_detail as $v)
                        @if ($v->type == 2)
                            <tr>
                                @if (!empty($properties['travel_cancel_section']['column']))
                                    @foreach ($properties['travel_cancel_section']['column'] as $k => $tb)
                                        @php $readonly=''; @endphp
                                        @if ($k != 'sr_no')
                                            @php
                                                if (isset($v->{$k})) {
                                                    $value = $v->{$k};
                                                } else {
                                                    $value='';
                                                }
                                            @endphp
                                            <td class="td-c">
                                                @if ($k == 'charge' ||
                                                    $k == 'amount' ||
                                                    $k == 'rate' ||
                                                    $k == 'qty' ||
                                                    $k == 'tax_amount' ||
                                                    $k == 'discount_perc' ||
                                                    $k == 'discount' ||
                                                    $k == 'total_amount')
                                                    @if ($k == 'amount' || $k == 'rate')
                                                        @php $rate=1; @endphp
                                                    @endif
                                                    @if ($rate == 1 && $k == 'total_amount')
                                                        @php $readonly='readonly'; @endphp
                                                    @endif
                                                    @if ($k == 'discount_perc')
                                                        @php $discount_perc=1; @endphp
                                                    @endif
                                                    @if ($discount_perc == 1 && $k == 'discount')
                                                        @php $readonly='readonly'; @endphp
                                                    @endif
                                                    @if ($k == 'total_amount')
                                                        <input onblur="calculateSecamt('tc');" type="text"
                                                            data-cy="travel-tc-{{ $k }}{{ $numrow }}"
                                                            value="{{ $v->total }}" {{ $readonly }}
                                                            name="sec_{{ $k }}[]"
                                                            class="form-control  pc-input">
                                                        @php $total_amount=$total_amount+$v->total; @endphp
                                                    @else
                                                        <input onblur="calculateSecamt('tc');" type="text"
                                                            data-cy="travel-tc-{{ $k }}{{ $numrow }}"
                                                            value="{{ $value }}" {{ $readonly }}
                                                            name="sec_{{ $k }}[]"
                                                            class="form-control  pc-input">
                                                    @endif
                                                @elseif($k == 'gst')
                                                    <select name="sec_gst[]" onchange="calculateamt('sec_');"
                                                        data-cy="travel-tc-{{ $k }}{{ $numrow }}"
                                                        style="min-width:80px;" class="form-control ">
                                                        <option value="">Select</option>
                                                        <option value="0">0%</option>
                                                        <option @if ($v->{$k} == 5) selected @endif
                                                            value="5">5%</option>
                                                        <option @if ($v->{$k} == 12) selected @endif
                                                            value="12">12%</option>
                                                        <option @if ($v->{$k} == 18) selected @endif
                                                            value="18">18%</option>
                                                        <option @if ($v->{$k} == 28) selected @endif
                                                            value="28">28%</option </select>
                                                    @elseif($k == 'booking_date' || $k == 'journey_date')
                                                        <input type="text" name="sec_{{ $k }}[]"
                                                            data-cy="travel-tc-{{ $k }}{{ $numrow }}"
                                                            value='<x-localize :date="$value" type="datetime" />'
                                                            @if ($has_dttm == '1')  @endif
                                                            class="form-control @if ($has_dttm == '1') date form_datetime1 date-set @else date-picker @endif"
                                                            style="min-width:@if ($has_dttm == '1') 160px @else 110px @endif !important;"
                                                            autocomplete="off" data-date-format="{{ Session::get('default_date_format')}}">
                                                    @elseif($k == 'from')
                                                        <input type="text" value="{{ $v->from_station }}"
                                                            data-cy="travel-tc-{{ $k }}{{ $numrow }}"
                                                            name="sec_{{ $k }}[]"
                                                            class="form-control  pc-input">
                                                    @elseif($k == 'to')
                                                        <input type="text" value="{{ $v->to_station }}"
                                                            data-cy="travel-tc-{{ $k }}{{ $numrow }}"
                                                            name="sec_{{ $k }}[]"
                                                            class="form-control  pc-input">
                                                    @elseif($k == 'type')
                                                        <input type="text" value="{{ $v->vehicle_type }}"
                                                            data-cy="travel-tc-{{ $k }}{{ $numrow }}"
                                                            name="sec_{{ $k }}[]"
                                                            class="form-control  pc-input">
                                                    @else
                                                        <input type="text" value="{{ $value }}"
                                                            data-cy="travel-tc-{{ $k }}{{ $numrow }}"
                                                            name="sec_{{ $k }}[]"
                                                            class="form-control  pc-input" maxlength="500">
                                                @endif
                                            </td>
                                        @endif
                                    @endforeach
                                @endif
                                <td>
                                    @if (!empty($travel_col))
                                        @foreach ($travel_col as $tc)
                                            @if (isset($properties['travel_cancel_section']['column'][$tc]))
                                            @else
                                                <input type="hidden"
                                                    data-cy="travel-tc-{{ $tc }}{{ $numrow }}"
                                                    name="sec_{{ $tc }}[]" value="">
                                            @endif
                                        @endforeach
                                    @endif
                                    <input type="hidden" id="sectc{{ $numrow }}" name="sec_exist_id[]"
                                        value="{{ $v->id }}">
                                    <input type="hidden" name="sec_type_value[]" value="tc">
                                    <a href="javascript:;"
                                        onclick="$(this).closest('tr').remove();calculateSecamt('tc');calculateamt('sec_');"
                                        class="btn btn-sm red"> <i class="fa fa-times"> </i> </a>
                                </td>
                                @php $numrow++; @endphp
                            </tr>
                        @endif
                    @endforeach
                @endif
            </tbody>
            <tbody>
                <tr>
                    <td colspan="{{ $int - 1 }}">
                        <b class="pull-right">Total Rs.</b>
                    </td>
                    <td class="td-c"> <input type="text" id="tc_sectotalamt" value="{{ $total_amount ?? 0 }}"
                            name="sec_calculate[]" readonly class="form-control input-sm"></td>
                </tr>
            </tbody>

        </table>
    </div>

    <!-- add particulars label ends -->
@endif



@if (isset($properties['hotel_section']))
    <!-- add particulars label -->
    @php $hb_col=json_encode($properties['hotel_section']['column']); @endphp
    <script>
        var hb_col = '{!! $hb_col ?? '' !!}';
    </script>
    <h3 class="form-section">{{ $properties['hotel_section']['title'] ?? 'Travel details' }}

        <a href="javascript:;" onclick="AddSecRow(hb_col,'hb',tb_has_datetime);" class="btn btn-sm green pull-right">
            <i class="fa fa-plus"> </i> Add new row </a>
    </h3>

    <div class="table-scrollable">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    @php $int=0; @endphp
                    @if (!empty($properties['hotel_section']['column']))
                        @foreach ($properties['hotel_section']['column'] as $k => $v)
                            @if ($k != 'sr_no')
                                <th class="td-c">
                                    {{ $v }}
                                </th>
                                @php $int++; @endphp
                            @endif
                        @endforeach
                    @endif
                    <th class="td-c">

                    </th>

                </tr>
            </thead>
            <tbody id="new_sec_hb">
                @php $total_amount=0; @endphp
                @php $rate=0; @endphp
                @php $discount_perc=0; @endphp
                @if (!empty($ticket_detail))
                    @php $numrow=1; @endphp
                    @foreach ($ticket_detail as $v)
                        @php $v->qty=$v->units; @endphp
                        @if ($v->type == 3)
                            <tr>
                                @if (!empty($properties['hotel_section']['column']))
                                    @foreach ($properties['hotel_section']['column'] as $k => $tb)
                                        @php $readonly=''; @endphp
                                        @if ($k != 'sr_no')
                                            @php
                                                if (isset($v->{$k})) {
                                                    $value = $v->{$k};
                                                } else {
                                                    $value='';
                                                }
                                            @endphp
                                            <td class="td-c">
                                                @if ($k == 'charge' ||
                                                    $k == 'amount' ||
                                                    $k == 'rate' ||
                                                    $k == 'qty' ||
                                                    $k == 'tax_amount' ||
                                                    $k == 'discount_perc' ||
                                                    $k == 'discount' ||
                                                    $k == 'total_amount')
                                                    @if ($k == 'amount' || $k == 'rate')
                                                        @php $rate=1; @endphp
                                                    @endif
                                                    @if ($rate == 1 && $k == 'total_amount')
                                                        @php $readonly='readonly'; @endphp
                                                    @endif
                                                    @if ($k == 'discount_perc')
                                                        @php $discount_perc=1; @endphp
                                                    @endif
                                                    @if ($discount_perc == 1 && $k == 'discount')
                                                        @php $readonly='readonly'; @endphp
                                                    @endif
                                                    @if ($k == 'total_amount')
                                                        <input onblur="calculateSecamt('hb');" type="text"
                                                            data-cy="travel-hb-{{ $k }}{{ $numrow }}"
                                                            value="{{ $v->total }}" {{ $readonly }}
                                                            name="sec_{{ $k }}[]"
                                                            class="form-control  pc-input">
                                                        @php $total_amount=$total_amount+$v->total; @endphp
                                                    @else
                                                        <input onblur="calculateSecamt('hb');" type="text"
                                                            data-cy="travel-hb-{{ $k }}{{ $numrow }}"
                                                            value="{{ $value }}" {{ $readonly }}
                                                            name="sec_{{ $k }}[]"
                                                            class="form-control  pc-input">
                                                    @endif
                                                @elseif($k == 'item')
                                                    @php $prod_ex=0; @endphp
                                                    @php $value=$v->name; @endphp
                                                    <select required style="width: 100%; "
                                                        data-cy="travel-hb-{{ $k }}{{ $numrow }}"
                                                        onchange="product_rate(this.value, this,'sec_');"
                                                        name="sec_item[]" data-placeholder="Type or Select"
                                                        class="form-control  productselect">
                                                        <option value="">Select Product</option>
                                                        @if (!empty($product_list) && isset($product_list))
                                                            @foreach ($product_list as $pk => $vk)
                                                                @if ($value == $pk)
                                                                    @php $prod_ex=1; @endphp
                                                                    <option selected value="{{ $pk }}">
                                                                        {{ $pk }}</option>
                                                                @else
                                                                    <option value="{{ $pk }}">
                                                                        {{ $pk }}</option>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                        @if ($prod_ex == 0)
                                                            <option selected="" value="{{ $value }}">
                                                                {{ $value }}</option>
                                                        @endif

                                                    </select>
                                                @elseif($k == 'gst')
                                                    <select name="sec_gst[]" onchange="calculateamt('sec_');"
                                                        data-cy="travel-hb-{{ $k }}{{ $numrow }}"
                                                        style="min-width:80px;" class="form-control ">
                                                        <option value="">Select</option>
                                                        <option value="0">0%</option>
                                                        <option @if ($v->{$k} == 5) selected @endif
                                                            value="5">5%</option>
                                                        <option @if ($v->{$k} == 12) selected @endif
                                                            value="12">12%</option>
                                                        <option @if ($v->{$k} == 18) selected @endif
                                                            value="18">18%</option>
                                                        <option @if ($v->{$k} == 28) selected @endif
                                                            value="28">28%</option </select>
                                                    @elseif($k == 'from_date')
                                                        <input type="text" name="sec_{{ $k }}[]"
                                                            data-cy="travel-hb-{{ $k }}{{ $numrow }}"
                                                            value='<x-localize :date="$v->booking_date" type="datetime" />'
                                                            @if ($has_dttm == '1')  @endif
                                                            class="form-control @if ($has_dttm == '1') date form_datetime1 date-set @else date-picker @endif"
                                                            style="min-width:@if ($has_dttm == '1') 160px @else 110px @endif !important;"
                                                            autocomplete="off" data-date-format="{{ Session::get('default_date_format')}}">
                                                    @elseif($k == 'to_date')
                                                        <input type="text" name="sec_{{ $k }}[]"
                                                            data-cy="travel-hb-{{ $k }}{{ $numrow }}"
                                                            value='<x-localize :date="$v->journey_date" type="datetime" />'
                                                            @if ($has_dttm == '1')  @endif
                                                            class="form-control @if ($has_dttm == '1') date form_datetime1 date-set @else date-picker @endif"
                                                            style="min-width:@if ($has_dttm == '1') 160px @else 110px @endif !important;"
                                                            autocomplete="off" data-date-format="{{ Session::get('default_date_format')}}">
                                                    @elseif($k == 'from')
                                                        <input type="text" value="{{ $v->from_station }}"
                                                            data-cy="travel-hb-{{ $k }}{{ $numrow }}"
                                                            name="sec_{{ $k }}[]"
                                                            class="form-control  pc-input">
                                                    @elseif($k == 'to')
                                                        <input type="text" value="{{ $v->to_station }}"
                                                            data-cy="travel-hb-{{ $k }}{{ $numrow }}"
                                                            name="sec_{{ $k }}[]"
                                                            class="form-control  pc-input">
                                                    @elseif($k == 'type')
                                                        <input type="text" value="{{ $v->vehicle_type }}"
                                                            data-cy="travel-hb-{{ $k }}{{ $numrow }}"
                                                            name="sec_{{ $k }}[]"
                                                            class="form-control  pc-input">
                                                    @else
                                                        <input type="text" value="{{ $value }}"
                                                            data-cy="travel-hb-{{ $k }}{{ $numrow }}"
                                                            name="sec_{{ $k }}[]"
                                                            class="form-control  pc-input" maxlength="500">
                                                @endif
                                            </td>
                                        @endif
                                    @endforeach
                                @endif
                                <td>
                                    @if (!empty($travel_col))
                                        @foreach ($travel_col as $tc)
                                            @if (isset($properties['hotel_section']['column'][$tc]))
                                            @else
                                                <input type="hidden"
                                                    data-cy="travel-hb-{{ $tc }}{{ $numrow }}"
                                                    name="sec_{{ $tc }}[]" value="">
                                            @endif
                                        @endforeach
                                    @endif
                                    <input type="hidden" id="sechb{{ $numrow }}" name="sec_exist_id[]"
                                        value="{{ $v->id }}">
                                    <input type="hidden" name="sec_type_value[]" value="hb">
                                    <a href="javascript:;"
                                        onclick="$(this).closest('tr').remove();calculateSecamt('hb');calculateamt('sec_');"
                                        class="btn btn-sm red"> <i class="fa fa-times"> </i> </a>
                                </td>
                                @php $numrow++; @endphp
                            </tr>
                        @endif
                    @endforeach
                @endif
            </tbody>
            <tbody>
                <tr>
                    <td colspan="{{ $int - 1 }}">
                        <b class="pull-right">Total Rs.</b>
                    </td>
                    <td class="td-c">
                        <input type="text" id="hb_sectotalamt" value="{{ $total_amount ?? 0 }}"
                            name="sec_calculate[]" readonly class="form-control">
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

@endif



@if (isset($properties['facility_section']))
    <!-- add particulars label -->
    @php $fs_col=json_encode($properties['facility_section']['column']); @endphp
    <script>
        var fs_col = '{!! $fs_col ?? '' !!}';
    </script>
    <h3 class="form-section">{{ $properties['facility_section']['title'] ?? 'Travel details' }}
        <a href="javascript:;" onclick="AddSecRow(fs_col,'fs',tb_has_datetime);" class="btn btn-sm green pull-right">
            <i class="fa fa-plus"> </i> Add new row </a>
    </h3>

    <div class="table-scrollable">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    @php $int=0; @endphp
                    @if (!empty($properties['facility_section']['column']))
                        @foreach ($properties['facility_section']['column'] as $k => $v)
                            @if ($k != 'sr_no')
                                <th class="td-c">
                                    {{ $v }}
                                </th>
                                @php $int++; @endphp
                            @endif
                        @endforeach
                    @endif
                    <th class="td-c">

                    </th>

                </tr>
            </thead>
            <tbody id="new_sec_fs">
                @php $total_amount=0; @endphp
                @php $rate=0; @endphp
                @php $discount_perc=0; @endphp
                @if (!empty($ticket_detail))
                    @php $numrow=1; @endphp
                    @foreach ($ticket_detail as $v)
                        @php $v->qty=$v->units; @endphp
                        @if ($v->type == 4)
                            <tr>
                                @if (!empty($properties['facility_section']['column']))
                                    @foreach ($properties['facility_section']['column'] as $k => $tb)
                                        @php $readonly=''; @endphp
                                        @if ($k != 'sr_no')
                                            @php
                                                if (isset($v->{$k})) {
                                                    $value = $v->{$k};
                                                } else {
                                                    $value='';
                                                }
                                            @endphp
                                            <td class="td-c">
                                                @if ($k == 'charge' ||
                                                    $k == 'amount' ||
                                                    $k == 'rate' ||
                                                    $k == 'qty' ||
                                                    $k == 'tax_amount' ||
                                                    $k == 'discount_perc' ||
                                                    $k == 'discount' ||
                                                    $k == 'total_amount')
                                                    @if ($k == 'amount' || $k == 'rate')
                                                        @php $rate=1; @endphp
                                                    @endif
                                                    @if ($rate == 1 && $k == 'total_amount')
                                                        @php $readonly='readonly'; @endphp
                                                    @endif
                                                    @if ($k == 'discount_perc')
                                                        @php $discount_perc=1; @endphp
                                                    @endif
                                                    @if ($discount_perc == 1 && $k == 'discount')
                                                        @php $readonly='readonly'; @endphp
                                                    @endif
                                                    @if ($k == 'total_amount')
                                                        <input onblur="calculateSecamt('fs');" type="text"
                                                            data-cy="travel-fs-{{ $k }}{{ $numrow }}"
                                                            value="{{ $v->total }}" {{ $readonly }}
                                                            name="sec_{{ $k }}[]"
                                                            class="form-control  pc-input">
                                                        @php $total_amount=$total_amount+$v->total; @endphp
                                                    @else
                                                        <input onblur="calculateSecamt('fs');" type="text"
                                                            data-cy="travel-fs-{{ $k }}{{ $numrow }}"
                                                            value="{{ $value }}" {{ $readonly }}
                                                            name="sec_{{ $k }}[]"
                                                            class="form-control  pc-input">
                                                    @endif
                                                @elseif($k == 'item')
                                                    @php $prod_ex=0; @endphp
                                                    @php $value=$v->name; @endphp
                                                    <select required style="width: 100%; "
                                                        data-cy="travel-fs-{{ $k }}{{ $numrow }}"
                                                        onchange="product_rate(this.value, this,'sec_');"
                                                        name="sec_item[]" data-placeholder="Type or Select"
                                                        class="form-control  productselect">
                                                        <option value="">Select Product</option>
                                                        @foreach ($product_list as $pk => $vk)
                                                            @if ($value == $vk)
                                                                @php $prod_ex=1; @endphp
                                                                <option selected value="{{ $pk }}">
                                                                    {{ $pk }}</option>
                                                            @else
                                                                <option value="{{ $pk }}">
                                                                    {{ $pk }}</option>
                                                            @endif
                                                        @endforeach
                                                        @if ($prod_ex == 0)
                                                            <option selected="" value="{{ $value }}">
                                                                {{ $value }}</option>
                                                        @endif
                                                    </select>
                                                @elseif($k == 'gst')
                                                    <select name="sec_gst[]"
                                                        data-cy="travel-fs-{{ $k }}{{ $numrow }}"
                                                        onchange="calculateamt('sec_');" style="min-width:80px;"
                                                        class="form-control ">
                                                        <option value="">Select</option>
                                                        <option value="0">0%</option>
                                                        <option @if ($v->{$k} == 5) selected @endif
                                                            value="5">5%</option>
                                                        <option @if ($v->{$k} == 12) selected @endif
                                                            value="12">12%</option>
                                                        <option @if ($v->{$k} == 18) selected @endif
                                                            value="18">18%</option>
                                                        <option @if ($v->{$k} == 28) selected @endif
                                                            value="28">28%</option </select>
                                                    @elseif($k == 'from_date')
                                                        <input type="text" name="sec_{{ $k }}[]"
                                                            data-cy="travel-fs-{{ $k }}{{ $numrow }}"
                                                            value='<x-localize :date="$v->booking_date" type="datetime" />'
                                                            @if ($has_dttm == '1')  @endif
                                                            class="form-control @if ($has_dttm == '1') date form_datetime1 date-set @else date-picker @endif"
                                                            style="min-width:@if ($has_dttm == '1') 160px @else 110px @endif !important;"
                                                            autocomplete="off" data-date-format="{{ Session::get('default_date_format')}}">
                                                    @elseif($k == 'to_date')
                                                        <input type="text" name="sec_{{ $k }}[]"
                                                            data-cy="travel-fs-{{ $k }}{{ $numrow }}"
                                                            value='<x-localize :date="$v->journey_date" type="datetime" />'
                                                            @if ($has_dttm == '1')  @endif
                                                            class="form-control @if ($has_dttm == '1') date form_datetime1 date-set @else date-picker @endif"
                                                            style="min-width:@if ($has_dttm == '1') 160px @else 110px @endif !important;"
                                                            autocomplete="off" data-date-format="{{ Session::get('default_date_format')}}">
                                                    @elseif($k == 'from')
                                                        <input type="text" value="{{ $v->from_station }}"
                                                            data-cy="travel-fs-{{ $k }}{{ $numrow }}"
                                                            name="sec_{{ $k }}[]"
                                                            class="form-control  pc-input">
                                                    @elseif($k == 'to')
                                                        <input type="text" value="{{ $v->to_station }}"
                                                            data-cy="travel-fs-{{ $k }}{{ $numrow }}"
                                                            name="sec_{{ $k }}[]"
                                                            class="form-control  pc-input">
                                                    @else
                                                        <input type="text" value="{{ $value }}"
                                                            data-cy="travel-fs-{{ $k }}{{ $numrow }}"
                                                            name="sec_{{ $k }}[]"
                                                            class="form-control  pc-input" maxlength="500">
                                                @endif
                                            </td>
                                        @endif
                                    @endforeach
                                @endif
                                <td>
                                    @if (!empty($travel_col))
                                        @foreach ($travel_col as $tc)
                                            @if (isset($properties['facility_section']['column'][$tc]))
                                            @else
                                                <input type="hidden"
                                                    data-cy="travel-fs-{{ $tc }}{{ $numrow }}"
                                                    name="sec_{{ $tc }}[]" value="">
                                            @endif
                                        @endforeach
                                    @endif
                                    <input type="hidden" id="secfs{{ $numrow }}" name="sec_exist_id[]"
                                        value="{{ $v->id }}">
                                    <input type="hidden" name="sec_type_value[]" value="fs">
                                    <a href="javascript:;"
                                        onclick="$(this).closest('tr').remove();calculateSecamt('fs');calculateamt('sec_');"
                                        class="btn btn-sm red"> <i class="fa fa-times"> </i> </a>
                                </td>
                                @php $numrow++; @endphp
                            </tr>
                        @endif
                    @endforeach
                @endif
            </tbody>
            <tbody>
                <tr>
                    <td colspan="{{ $int - 1 }}">
                        <b class="pull-right">Total Rs.</b>
                    </td>
                    <td class="td-c">
                        <input type="text" id="fs_sectotalamt" value="{{ $total_amount ?? 0 }}"
                            name="sec_calculate[]" readonly class="form-control">
                    </td>
                </tr>
            </tbody>

        </table>


    </div>
@endif
<input type="hidden" id="sec_total" value="{{ $sec_total ?? 0 }}">
