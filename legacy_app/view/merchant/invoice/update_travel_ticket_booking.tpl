

<!-- add particulars label -->
<h3 class="form-section">Add bookings
    <a href="javascript:;" onclick="AddTravelTicketBooking('b');" class="btn btn-sm green pull-right"> <i class="fa fa-plus"> </i> Add new row </a></h3>

<div class="table-scrollable">
    <table class="table table-bordered table-hover" id="bparticular_table">
        <thead>
            <tr>
                <th class="td-c">
                    Booking Date
                </th>
                <th class="td-c">
                    Journey Date
                </th>
                <th class="td-c">
                    Name
                </th>
                <th class="td-c"> 
                    Type
                </th>
                <th class="td-c"> 
                    From
                </th>
                <th class="td-c"> 
                    To
                </th>
                <th class="td-c"> 
                    Amt
                </th>
                <th class="td-c"> 
                    Ser.Ch.
                </th>
                <th class="td-c"> 
                    Total
                </th>
                <th class="td-c"> 
                    
                </th>

            </tr>
        </thead>
        <tbody id="bnew_particular">
            {$total=0}
            {$amount=0}
            {$charge=0}
            {$int=1}
            {foreach from=$particular item=v}
                {if $v.type==1}
                    <tr>
                        <td>
                            <div class="input-icon right">
                                <input type="hidden" name="texistid[]" value="{$v.id}">
                                <input type="hidden" name="btype[]" value="b" >
                                <input type="text" required="" style="padding-right: 0px; padding-left: 10px;width: 120px" value="{$v.booking_date|date_format:"%d-%m-%Y"}"  name="booking_date[]" class="form-control date-picker input-sm"  autocomplete="off" data-date-format="dd M yyyy" >
                            </div>
                        </td>
                        <td>
                            <div class="input-icon right">
                                <input type="text" required="" style="padding-right: 0px; padding-left: 10px;width: 120px" value="{$v.journey_date|date_format:"%d-%m-%Y"}"  name="journey_date[]" class="form-control date-picker input-sm"  autocomplete="off" data-date-format="dd M yyyy" >
                            </div>
                        </td>
                        <td>
                            <div class="input-icon right">
                                <input type="text" name="b_name[]" maxlength="500" value="{$v.name}" class="form-control input-sm" >
                            </div>
                        </td>
                        <td>
                            <div class="input-icon right">
                                <input type="text" name="b_type[]" maxlength="45" value="{$v.vehicle_type}" class="form-control input-sm" >
                            </div>
                        </td>
                        <td>
                            <div class="input-icon right">
                                <input type="text" name="b_from[]" maxlength="45" value="{$v.from_station}" class="form-control input-sm" >
                            </div>
                        </td>
                        <td>
                            <div class="input-icon right">
                                <input type="text" name="b_to[]" maxlength="45" value="{$v.to_station}" class="form-control input-sm" >
                            </div>
                        </td>
                        <td>
                            <div class="input-icon right">
                                <input type="text" required="" name="b_amt[]" id="bamt{$int}" {$validate.money}  value="{$v.amount}" onblur="calculatetravelbooking({$int}, 'b')" class="form-control input-sm" >
                            </div>
                        </td>
                        <td>
                            <div class="input-icon right">
                                <input type="text" name="b_charge[]" id="bcharge{$int}"  {$validate.money} value="{$v.charge}" onblur="calculatetravelbooking({$int}, 'b')" class="form-control input-sm" >
                            </div>
                        </td>
                        <td>
                            <div class="input-icon right">
                                <input type="text" name="b_total[]" id="btotal{$int}" value="{$v.total}" readonly  class="form-control input-sm" >
                            </div>
                        </td>
                        <td><a href="javascript:;" onclick="$(this).closest('tr').remove(), calculatetravelbooking({$int}, 'b');" class="btn btn-sm red"> <i class="fa fa-times"> </i> </a>
                        </td>
                    </tr>
                    {$int=$int+1}
                    {$amount=$amount+$v.amount}
                    {$charge=$charge+$v.charge}
                    {$total=$total+$v.total}
                {/if}
            {/foreach}
        </tbody>
        <tbody>
            <tr >
                <td colspan="6" >
                    <b class="pull-right">Total Rs.</b>
                </td>
                <td class="td-c">
                    <input type="hidden" id="totalcostamt" value="{$info.basic_amount}" name="totalcost">
                    <input type="text" id="btotalcost" readonly value="{$amount}"  class="form-control input-sm" ></td>
                <td class="td-c"> <input type="text" id="btotalcharge" readonly  value="{$charge}" class="form-control input-sm" ></td>
                <td class="td-c"> <input type="text" id="btotalcostamt" readonly  value="{$total}" class="form-control input-sm" ></td>
            </tr>
        </tbody>

    </table>
</div>


<h3 class="form-section">Add cancellation
    <a href="javascript:;" onclick="AddTravelTicketBooking('c');" class="btn btn-sm green pull-right"> <i class="fa fa-plus"> </i> Add new row </a></h3>

<div class="table-scrollable">
    <table class="table table-bordered table-hover" id="cparticular_table">
        <thead>
            <tr>
                <th class="td-c">
                    Booking Date
                </th>
                <th class="td-c">
                    Journey Date
                </th>
                <th class="td-c">
                    Name
                </th>
                <th class="td-c"> 
                    Type
                </th>
                <th class="td-c"> 
                    From
                </th>
                <th class="td-c"> 
                    To
                </th>
                <th class="td-c"> 
                    Amt
                </th>
                <th class="td-c"> 
                    Ser.Ch.
                </th>
                <th class="td-c"> 
                    Total
                </th>
                <th class="td-c"> 
                    
                </th>

            </tr>
        </thead>
        <tbody id="cnew_particular">
            {$total=0}
            {$amount=0}
            {$charge=0}
            {foreach from=$particular item=v}
                {if $v.type==2}
                    <tr>
                        <td>
                            <div class="input-icon right">
                                <input type="hidden" name="texistid[]" value="{$v.id}">
                                <input type="hidden" name="btype[]" value="c" >
                                <input type="text" required="" style="padding-right: 0px; padding-left: 10px;width: 120px" value="{$v.booking_date|date_format:"%d-%m-%Y"}"  name="booking_date[]" class="form-control date-picker input-sm"  autocomplete="off" data-date-format="dd M yyyy" >
                            </div>
                        </td>
                        <td>
                            <div class="input-icon right">
                                <input type="text" required="" style="padding-right: 0px; padding-left: 10px;width: 120px" value="{$v.journey_date|date_format:"%d-%m-%Y"}"  name="journey_date[]" class="form-control date-picker input-sm"  autocomplete="off" data-date-format="dd M yyyy" >
                            </div>
                        </td>
                        <td>
                            <div class="input-icon right">
                                <input type="text" name="b_name[]" maxlength="500" value="{$v.name}" class="form-control input-sm" >
                            </div>
                        </td>
                        <td>
                            <div class="input-icon right">
                                <input type="text" name="b_type[]" maxlength="45" value="{$v.vehicle_type}" class="form-control input-sm" >
                            </div>
                        </td>
                        <td>
                            <div class="input-icon right">
                                <input type="text" name="b_from[]" maxlength="45" value="{$v.from_station}" class="form-control input-sm" >
                            </div>
                        </td>
                        <td>
                            <div class="input-icon right">
                                <input type="text" name="b_to[]" maxlength="45" value="{$v.to_station}" class="form-control input-sm" >
                            </div>
                        </td>
                        <td>
                            <div class="input-icon right">
                                <input type="text" required="" name="b_amt[]" id="camt{$int}" {$validate.money}  value="{$v.amount}" onblur="calculatetravelbooking({$int}, 'c')" class="form-control input-sm" >
                            </div>
                        </td>
                        <td>
                            <div class="input-icon right">
                                <input type="text" name="b_charge[]" id="ccharge{$int}" {$validate.money}  value="{$v.charge}" onblur="calculatetravelbooking({$int}, 'c')" class="form-control input-sm" >
                            </div>
                        </td>
                        <td>
                            <div class="input-icon right">
                                <input type="text" name="b_total[]" id="ctotal{$int}" value="{$v.total}" readonly  class="form-control input-sm" >
                            </div>
                        </td>
                        <td><a href="javascript:;" onclick="$(this).closest('tr').remove(), calculatetravelbooking({$int}, 'c');" class="btn btn-sm red"> <i class="fa fa-times"> </i> </a>
                        </td>
                    </tr>
                    {$int=$int+1}
                    {$amount=$amount+$v.amount}
                    {$charge=$charge+$v.charge}
                    {$total=$total+$v.total}
                {/if}
            {/foreach}
        </tbody>
        <tbody>
            <tr >
                <td colspan="6" >
                    <b class="pull-right">Total Rs.</b>
                </td>
                <td class="td-c"> <input type="text" id="ctotalcost" value="{$amount}" readonly  class="form-control input-sm" ></td>
                <td class="td-c"> <input type="text" id="ctotalcharge" value="{$charge}" readonly  class="form-control input-sm" ></td>
                <td class="td-c"> <input type="text" id="ctotalcostamt" value="{$total}" readonly  class="form-control input-sm" ></td>
            </tr>
        </tbody>

    </table>
</div>
<!-- add particulars label ends -->

