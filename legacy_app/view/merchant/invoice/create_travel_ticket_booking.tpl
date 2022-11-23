

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
            <tr>
                <td>
                    <div class="input-icon right">
                        <input type="hidden" name="texistid[]" value="0">
                        <input type="hidden" name="btype[]" value="b" >
                        <input type="text" required="" style="padding-right: 0px; padding-left: 10px;width: 120px" value=""  name="booking_date[]" class="form-control date-picker input-sm"  autocomplete="off" data-date-format="dd M yyyy" >
                    </div>
                </td>
                <td>
                    <div class="input-icon right">
                        <input type="text" required="" style="padding-right: 0px; padding-left: 10px;width: 120px" value=""  name="journey_date[]" class="form-control date-picker input-sm"  autocomplete="off" data-date-format="dd M yyyy" >
                    </div>
                </td>
                <td>
                    <div class="input-icon right">
                        <input type="text" name="b_name[]" maxlength="500" class="form-control input-sm" >
                    </div>
                </td>
                <td>
                    <div class="input-icon right">
                        <input type="text" name="b_type[]" maxlength="45" class="form-control input-sm" >
                    </div>
                </td>
                <td>
                    <div class="input-icon right">
                        <input type="text" name="b_from[]" maxlength="45" class="form-control input-sm" >
                    </div>
                </td>
                <td>
                    <div class="input-icon right">
                        <input type="text" name="b_to[]" maxlength="45" class="form-control input-sm" >
                    </div>
                </td>
                <td>
                    <div class="input-icon right">
                        <input type="text" required="" name="b_amt[]" {$validate.money} id="bamt1" onblur="calculatetravelbooking(1, 'b')" class="form-control input-sm" >
                    </div>
                </td>
                <td>
                    <div class="input-icon right">
                        <input type="text" name="b_charge[]" {$validate.money} id="bcharge1" onblur="calculatetravelbooking(1, 'b')" class="form-control input-sm" >
                    </div>
                </td>
                <td>
                    <div class="input-icon right">
                        <input type="text" name="b_total[]" id="btotal1" readonly  class="form-control input-sm" >
                    </div>
                </td>
                <td><a href="javascript:;" onclick="$(this).closest('tr').remove(),calculatetravelbooking(1, 'b');" class="btn btn-sm red"> <i class="fa fa-times"> </i> </a>
                </td>
            </tr>
        </tbody>
        <tbody>
            <tr >
                <td colspan="6" >
                    <b class="pull-right">Total Rs.</b>
                </td>
                <td class="td-c">
                    <input type="hidden" id="totalcostamt" name="totalcost">
                    <input type="text" id="btotalcost" readonly  class="form-control input-sm" ></td>
                <td class="td-c"> <input type="text" id="btotalcharge" readonly  class="form-control input-sm" ></td>
                <td class="td-c"> <input type="text" id="btotalcostamt" readonly  class="form-control input-sm" ></td>
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
            <tr>
                <td>
                    <div class="input-icon right">
                        <input type="hidden" name="texistid[]" value="0">
                        <input type="hidden" name="btype[]" value="c" >
                        <input type="text" required="" style="padding-right: 0px; padding-left: 10px;width: 120px"  value=""  name="booking_date[]" class="form-control date-picker input-sm"  autocomplete="off" data-date-format="dd M yyyy" >
                    </div>
                </td>
                <td>
                    <div class="input-icon right">
                        <input type="text" required="" style="padding-right: 0px; padding-left: 10px;width: 120px" value=""  name="journey_date[]" class="form-control date-picker input-sm"  autocomplete="off" data-date-format="dd M yyyy" >
                    </div>
                </td>
                <td>
                    <div class="input-icon right">
                        <input type="text" name="b_name[]" class="form-control input-sm" >
                    </div>
                </td>
                <td>
                    <div class="input-icon right">
                        <input type="text" name="b_type[]" class="form-control input-sm" >
                    </div>
                </td>
                <td>
                    <div class="input-icon right">
                        <input type="text" name="b_from[]" class="form-control input-sm" >
                    </div>
                </td>
                <td>
                    <div class="input-icon right">
                        <input type="text" name="b_to[]" class="form-control input-sm" >
                    </div>
                </td>
                <td>
                    <div class="input-icon right">
                        <input type="text" required="" name="b_amt[]" {$validate.money} id="camt1" onblur="calculatetravelbooking(1, 'c')" class="form-control input-sm" >
                    </div>
                </td>
                <td>
                    <div class="input-icon right">
                        <input type="text" name="b_charge[]" {$validate.money} id="ccharge1" onblur="calculatetravelbooking(1, 'c')" class="form-control input-sm" >
                    </div>
                </td>
                <td>
                    <div class="input-icon right">
                        <input type="text" name="b_total[]" id="ctotal1" readonly  class="form-control input-sm" >
                    </div>
                </td>
                <td><a href="javascript:;" onclick="$(this).closest('tr').remove(),calculatetravelbooking(1, 'c');" class="btn btn-sm red"> <i class="fa fa-times"> </i> </a>
                </td>
            </tr>
        </tbody>
        <tbody>
            <tr >
                <td colspan="6" >
                    <b class="pull-right">Total Rs.</b>
                </td>
                <td class="td-c"> <input type="text" id="ctotalcost" readonly  class="form-control input-sm" ></td>
                <td class="td-c"> <input type="text" id="ctotalcharge" readonly  class="form-control input-sm" ></td>
                <td class="td-c"> <input type="text" id="ctotalcostamt" readonly  class="form-control input-sm" ></td>
            </tr>
        </tbody>

    </table>
</div>
<!-- add particulars label ends -->

