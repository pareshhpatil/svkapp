@if(isset($template_info->template_type) && $template_info->template_type!='scan' && $template_info->template_type!='construction')
<!-- add taxes label -->
<h3 class="form-section mt-2">Add taxes
    <a data-cy="add_taxes_btn" href="javascript:;" onclick="AddInvoiceTax();" class="btn btn-sm green pull-right"> <i class="fa fa-plus"> </i> Add new row </a>
</h3>

<div class="table-scrollable">
    <table class="table table-bordered table-hover" id="taxes_tbl">
        <thead>
            <tr>
                <th class="td-c">
                    Tax label
                </th>
                <th class="td-c">
                    Tax in %
                </th>
                <th class="td-c">
                    Applicable on
                </th>
                <th class="td-c">
                    Absolute cost
                </th>
                <th class="td-c">

                </th>
            </tr>
        </thead>
        <tbody id="new_tax">
            @if($mode=='create')
            @php $int=1; @endphp
            @if(!empty($default_tax_list))
            @foreach($default_tax_list as $v)
            <tr @if($v->tax_type==4) class="other_taxes" @else class="add_default_taxes" @endif>
                <td>
                    <select style="width: 100%" onchange="setTaxCalculatedOn(this.value,$(this).attr('data-cy'));calculatetax();" data-cy="invoice_tax_id{{$int}}" name="tax_id[]" data-placeholder="Select..." class="form-control taxselect">
                        <option value="">Select</option>
                        @if(!empty($tax_list))
                        @foreach($tax_list as $k=>$tv)
                        <option @if($k==$v->tax_id) selected="" @endif value="{{$k}}">{{$tv['tax_name']}}</option>
                        @endforeach
                        @endif
                    </select>

                    @if($v->tax_type==4)
                    @if($v->tax_calculated_on==1)
                    <span class="help-block tax-note" data-cy="note-for-tax-calculation{{$int}}">Tax calculated on <b>Grand total (Amount with GST)</b></span>
                    @else
                    <span class="help-block tax-note" data-cy="note-for-tax-calculation{{$int}}">Tax calculated on <b>Base amount (Amount without GST)</b></span>
                    @endif
                    @else
                    <span class="help-block tax-note" data-cy="note-for-tax-calculation{{$int}}"></span>
                    @endif
                </td>
                <td>
                    <div class="input-icon right">
                        <input type="number" step="0.01" max="100" data-cy="invoice_tax_percent{{$int}}" name="tax_percent[]" readonly="" value="{{$v->percentage}}" onblur="calculatetax()" class="form-control " placeholder="Add tax %">
                    </div>
                </td>
                <td>
                    <input type="text" data-cy="invoice_tax_applicable{{$int}}" value="0" name="tax_applicable[]" onblur="calculatetax(this.value,$(this).attr('data-cy'))" class="form-control ">
                </td>
                <td>
                    <input type="text" name="tax_amt[]" value="" data-cy="invoice_tax_amount{{$int}}" readonly id="totaltax{{$int}}" class="form-control ">
                    <input type="hidden" name="tax_detail_id[]" value="0">
                    <input type="hidden" name="tax_type[]" value="{{$v->tax_type}}" data-cy="invoice_tax_type{{$int}}">
                    <input type="hidden" name="tax_calculated_on[]" value="{{$v->tax_calculated_on}}" data-cy="invoice_tax_calculated_on{{$int}}">
                </td>

                <td class="td-c">
                    <a data-cy="invoice_tax_remove{{$int}}" href="javascript:;" onclick="$(this).closest('tr').remove();
                                        calculatetax();" class="btn btn-sm red"> <i class="fa fa-times"> </i> </a>
                </td>
            </tr>
            @php $int++; @endphp
            @endforeach
            @endif
            @endif

            @if($mode=='update')
            @php $int=1; @endphp
            @if(!empty($invoice_tax))
            @foreach($invoice_tax as $v)
            <tr @if($v->tax_type==4) class="other_taxes" @else class="add_default_taxes" @endif>
                <td>
                    <select style="width: 100%" onchange="setTaxCalculatedOn(this.value,$(this).attr('data-cy'));calculatetax();" data-cy="invoice_tax_id{{$int}}" name="tax_id[]" data-placeholder="Select..." class="form-control taxselect">
                        <option value="">Select</option>
                        @if(!empty($tax_list))
                        @foreach($tax_list as $k=>$tv)
                        <option @if($k==$v->tax_id) selected="" @endif value="{{$k}}">{{$tv['tax_name']}}</option>
                        @endforeach
                        @endif
                    </select>
                    @if($v->tax_type==4)
                    @if($v->tax_calculated_on==1)
                    <span class="help-block tax-note" data-cy="note-for-tax-calculation{{$int}}">Tax calculated on <b>Grand total (Amount with GST)</b></span>
                    @else
                    <span class="help-block tax-note" data-cy="note-for-tax-calculation{{$int}}">Tax calculated on <b>Base amount (Amount without GST)</b></span>
                    @endif
                    @else
                    <span class="help-block tax-note" data-cy="note-for-tax-calculation{{$int}}"></span>
                    @endif
                </td>
                <td>
                    <div class="input-icon right">
                        <input type="number" step="0.01" max="100" data-cy="invoice_tax_percent{{$int}}" name="tax_percent[]" readonly="" value="{{$v->tax_percent}}" {!!$validate->percent!!} onblur="calculatetax()" class="form-control " placeholder="Add tax %">
                    </div>
                </td>
                <td>
                    <input type="text" data-cy="invoice_tax_applicable{{$int}}" name="tax_applicable[]" @if($v->tax_type==5) readonly @endif value="{{$v->applicable}}" onblur="calculatetax(this.value,$(this).attr('data-cy'))" class="form-control ">
                </td>
                <td>
                    <input type="text" name="tax_amt[]" value="{{$v->tax_amount}}" data-cy="invoice_tax_amount{{$int}}" readonly id="totaltax{{$int}}" class="form-control ">
                    <input type="hidden" name="tax_detail_id[]" value="{{$v->id}}">
                    <input type="hidden" name="tax_type[]" value="{{$v->tax_type}}" data-cy="invoice_tax_type{{$int}}">
                    <input type="hidden" name="tax_calculated_on[]" value="{{$v->tax_calculated_on}}" data-cy="invoice_tax_calculated_on{{$int}}">
                </td>

                <td class="td-c">
                    <a data-cy="invoice_tax_remove{{$int}}" href="javascript:;" onclick="setProductGST({{$int}});$(this).closest('tr').remove();
                                    calculatetax();" class="btn btn-sm red"> <i class="fa fa-times"> </i> </a>
                </td>
            </tr>
            @php $int++; @endphp
            @endforeach
            @endif
            @endif
        </tbody>
        <tr class="warning">
            <td>
                <div class="input-icon right">
                    <input type="text" readonly value="{{$template_info->tax_total}}" class="form-control " placeholder="Enter total label">
                </div>
            </td>
            <td></td>
            <td></td>
            <td>
                <input type="hidden" id="merchant_state" value="{{$merchant_state}}">
                <input type="text" id="totaltaxcost" data-cy="invoice_tax_total" name="totaltax" value="{{$info->tax_amount??0}}" class="form-control " readonly>
            </td>
            <td></td>
        </tr>
    </table>
</div>
@endif
<div style="display: none;" id="plugin_div">
    <h3 class="form-section mt-2">Plugins</h3>
</div>

@if(isset($plugin['has_deductible']))
<h3 class="form-section base-font mb-2">Deductibles
    <a href="javascript:;" onclick="AddInvoiceDebit();" class="btn btn-sm green pull-right mb-1"> <i class="fa fa-plus"> </i> Add new row </a>
</h3>
<div class="table-scrollable">
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th class="td-c  default-font">
                    Deductible
                </th>
                <th class="td-c  default-font">
                    Deduct in %
                </th>
                <th class="td-c  default-font">
                    Applicable on
                </th>
                <th class="td-c  default-font">
                    Absolute cost
                </th>

                <th class="td-c">

                </th>
            </tr>
        </thead>
        <tbody id="new_debit">
            @php $int =1; @endphp
            @if(!empty($plugin['deductible']))
            @foreach($plugin['deductible'] as $v)
            <tr>
                <td>
                    <div class="input-icon right">
                        <input type="text" data-cy="plugin_deduct_tax{{$int}}" name="deduct_tax[]" required {!!$validate->narrative!!} value="{{$v['tax_name']}}" class="form-control " placeholder="Add label">
                    </div>
                </td>
                <td>
                    <div class="input-icon right">
                        <input type="number" step="0.01" max="100" data-cy="plugin_deduct_percent{{$int}}" name="deduct_percent[]" placeholder="Add %" id="debitin{{$int}}" onblur="calculatedebit({{$int}})" value="{{$v['percent']}}" class="form-control ">
                    </div>
                </td>
                <td>
                    <input type="number" step="0.01" data-cy="plugin_deduct_applicable{{$int}}" name="deduct_applicable[]" value="{{$v['applicable']??''}}" placeholder="Add applicable on" value="" id="applicabledebitamount{{$int}}" onblur="calculatedebit({{$int}})" class="form-control ">
                </td>
                <td>
                    <input type="text" type="number" step="0.01" data-cy="plugin_deduct_total{{$int}}" max="100" name="deduct_total[]" readonly="" value="{{$v['total']??''}}" id="totaldebit{{$int}}" class="form-control ">
                </td>

                <td>
                    <a href="javascript:;" data-cy="plugin_deduct_remove{{$int}}" onclick="$(this).closest('tr').remove();" class="btn btn-sm red"> <i class="fa fa-times"> </i> </a>
                </td>
            </tr>
            @php $int++; @endphp
            @endforeach
            @endif
        </tbody>
    </table>
</div>
<hr>
@php $has_plugin=1; @endphp
@endif

<!--Online payments-->
@if(isset($plugin['has_online_payments']))
<div id="online_payments_div">
    <h3 class="form-section base-font mb-2">Enable/Disable Payments
        <div class="pull-right">
            <input type="hidden" id="is_online_payments_" @if(isset($plugin['has_online_payments']) && $plugin['has_online_payments']=='1' ) value="1" @else value="0" @endif name="has_online_payments" />
        </div>
    </h3>
    <div id="enable_payments_div" class="mb-2" @if(isset($plugin['has_online_payments']) && $plugin['has_online_payments']=='1' ) style="display: block;" @else style="display:none;" @endif>
        <div class="form-group form-horizontal">
            <label class="control-label col-md-3 w-auto">Enable online payments</label>
            <div class="col-md-3">
                <input type="hidden" id="is_enable_payments_" name="enable_payments" @if(isset($plugin['enable_payments']) && $plugin['enable_payments']=='1' ) value="1" @else value="0" @endif />
                <input type="checkbox" id="isenablepayments" @if(isset($plugin['enable_payments']) && $plugin['enable_payments']=='1' ) checked="" value="1" @else value="0" @endif onchange="checkValue('is_enable_payments_', this.checked);" data-size="small" class="make-switch" data-on-text="&nbsp;YES&nbsp;&nbsp;" data-off-text="&nbsp;NO&nbsp;">
            </div>
        </div>
    </div>
</div>
<hr>
@php $has_plugin=1; @endphp
@endif

@if(isset($plugin['has_cc']))
<h3 class="form-section base-font mb-2">Add CC Emails
    <a onclick="AddCC();" class="btn btn-sm green pull-right"> <i class="fa fa-plus"> </i> Add new row </a>
</h3>
<div class="table-scrollable" style="max-width: 500px;">
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th class="td-c  default-font">
                    Email
                </th>

                <th class="td-c">

                </th>
            </tr>
        </thead>
        <tbody id="new_cc">
            @if(!empty($plugin['cc_email']))
            @php $int =1; @endphp
            @foreach($plugin['cc_email'] as $v)
            <tr>
                <td>
                    <div class="input-icon right">
                        <input type="email" name="cc[]" data-cy="plugin_cc_email{{$int}}" value="{{$v}}" class="form-control " placeholder="Add email">
                    </div>
                </td>
                <td>
                    <a href="javascript:;" data-cy="plugin_cc_remove{{$int}}" onclick="$(this).closest('tr').remove();" class="btn btn-sm red"> <i class="fa fa-times"> </i> </a>
                </td>
            </tr>
            @php $int++; @endphp
            @endforeach
            @endif
        </tbody>
    </table>
</div>
<hr>
@php $has_plugin=1; @endphp
@endif


@if(isset($plugin['has_supplier']))
<!-- add supplier start -->
<div id="supplierdiv">
    <h3 class="form-section base-font mb-2">Add supplier
        <a data-toggle="modal" href="#supplier" class="btn btn-sm green pull-right mb-1"> <i class="fa fa-plus"> </i> Add new row </a>
    </h3>
    <div class="table-scrollable">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th class="td-c  default-font">
                        Supplier company name
                    </th>
                    <th class="td-c  default-font">
                        Contact person name
                    </th>
                    <th class="td-c  default-font">
                        Mobile
                    </th>
                    <th class="td-c  default-font">
                        Email
                    </th>

                    <th class="td-c">
                    </th>
                </tr>
            </thead>
            <tbody id="new_supplier">

            </tbody>
        </table>
    </div>
    <hr>
</div>
@endif



<!-- grand total label -->
@if(isset($plugin['has_autocollect']) && $request_type==4)
@if($mode=='create')
<h3 class="form-section base-font mb-2">Auto collect
    <div class="pull-right">
    </div>
</h3>
<div id="enable_payments_div" class="mb-2" style="display: block;">
    <div class="form-group form-horizontal">
        <label class="control-label col-md-3 w-auto">Enable auto collect payment</label>
        <div class="col-md-3">
            <input type="hidden" id="is_enable_autocollect_" name="enable_autocollect" @if(isset($plugin['has_autocollect']) && $plugin['has_autocollect']=='1' ) value="1" @else value="0" @endif />
            <input type="checkbox" id="isenableautocollect" @if(isset($plugin['has_autocollect']) && $plugin['has_autocollect']=='1' ) checked="" value="1" @else value="0" @endif onchange="checkValue('is_enable_autocollect_', this.checked);" data-size="small" class="make-switch" data-on-text="&nbsp;YES&nbsp;&nbsp;" data-off-text="&nbsp;NO&nbsp;">
        </div>
    </div>
</div>
<hr>
@php $has_plugin=1; @endphp
@else
@if(isset($plugin['autocollect_plan_id']))
<input type="hidden" name="auto_collect_plan_id" value="{{$plugin['autocollect_plan_id']}}" />
@endif
@endif
@endif

@if(isset($plugin['has_franchise']))
<h3 class="form-section base-font mb-2">Franchise
</h3>
<div class="row mb-2">
    <div class="form-group form-horizontal">
        <label class="control-label col-md-3 w-auto">Select franchise</label>
        <div class="col-md-3">
            <select name="franchise_id" data-cy="plugin_franchise_id" required class="form-control" data-placeholder="Select...">
                <option value="">Select franchise</option>
                @if(!empty($franchise_list))
                @foreach($franchise_list as $v)
                <option value="{{$v->franchise_id}}">{{$v->franchise_name}}</option>
                @endforeach
                @endif
            </select>
        </div>
    </div>
</div>
<div class="row mb-2">
    <div class="form-group form-horizontal">
        <label class="control-label col-md-3 w-auto">Display franchise name on invoice </label>
        <div class="control-label col-md-3 w-auto">
            <label> <input type="checkbox" @if($plugin['franchise_name_invoice']==1) checked @endif value="1" name="franchise_name_invoice"> Yes </label>
        </div>
    </div>
</div>
<hr>
@php $has_plugin=1; @endphp
@endif

@if(isset($plugin['has_vendor']))
<h3 class="form-section base-font mb-2">Vendor
</h3>
<div class="row mb-2">
    <div class="form-group form-horizontal">
        <label class="control-label col-md-3 w-auto">Select vendor</label>
        <div class="col-md-3">
            <select name="vendor_id" id="vendor_id" onchange="vendorCommission(this.value);" data-cy="plugin_vendor_id" required class="form-control select2me" data-placeholder="Select...">
                <option value="">Select vendor</option>
                @if(!empty($vendor_list))
                @foreach($vendor_list as $v)
                @isset($info->vendor_id)
                <option @if($info->vendor_id==$v->vendor_id) selected @endif value="{{$v->vendor_id}}">{{$v->vendor_name}}</option>
                @else
                <option value="{{$v->vendor_id}}">{{$v->vendor_name}}</option>
                @endisset
                @endforeach
                @endif
            </select>
        </div>
        <div class="col-md-2">
            <select name="commision_type" id="commision_type" data-cy="plugin_commision_type" required class="form-control">
                <option value="">Commision type</option>
                @isset($vendor_commission->type)
                <option @if($vendor_commission->type==1) selected @endif value="1">Percentage</option>
                <option @if($vendor_commission->type==2) selected @endif value="2">Fixed</option>
                @else
                <option value="1">Percentage</option>
                <option value="2">Fixed</option>
                @endisset

            </select>
        </div>
        <div class="col-md-2">
            <input type="number" @isset($vendor_commission->commission_value) value="{{$vendor_commission->commission_value}}" @endisset id="commision_value" step="0.01" onchange="calculateVendorCommission();" name="commision_value" placeholder="Value" data-cy="plugin_commision_value" required class="form-control" />
        </div>
        <div class="col-md-3">
            <input step="0.01" id="commision_amount" @isset($vendor_commission->amount) value="{{$vendor_commission->amount}}" @endisset onblur="calculateVendorCommission();" name="commision_amount" readonly placeholder="Commision amount" data-cy="plugin_commision_amount" required class="form-control" />
        </div>
    </div>
</div>
<hr>
@php $has_plugin=1; @endphp
@endif

@if(isset($plugin['has_covering_note']))
<h3 class="form-section base-font mb-2">Covering note

    <a class="btn mb-1 green pull-right" onclick="AddCoveringNote();" href="javascript:;">
        Add new note</a>
</h3>

<div class="row mb-2">
    <div class="form-group form-section form-horizontal">
        <label class="control-label col-md-3 w-auto">Select covering note</label>
        <div class="col-md-3">
            <select name="covering_id" onchange="showEditNote();" data-cy="plugin_covering_id"   id="covering_select" class="form-control" data-placeholder="Select...">
                <option value="0">Select covering note</option>
                @if(!empty($covering_list))
                @foreach($covering_list as $v)
                <option @if($plugin['default_covering_note']==$v->covering_id)  selected @endif value="{{$v->covering_id}}">{{$v->template_name}}</option>
               
                @endforeach
                @endif
               
               
               
            </select>
            <a class="hidden" id="conf_cov" data-toggle="modal" href="#con_coveri"></a>
        </div>
        <div class=" col-md-2" id="edit_note_div" style="display: @if($plugin['default_covering_note']!=0) block @else none @endif ;" >
        <a class="btn mb-1 green "  onclick="EditCoveringNote();" href="javascript:;">
           Edit note</a>
        </div>
        </div>
</div>
<hr>
@php $has_plugin=1; @endphp
@endif

@if(isset($plugin['has_custom_notification']))
<h3 class="form-section base-font mb-2">Customize notification</h3>

<div class="row mb-2">
    <div class="col-md-12">
        <div class="form-group form-horizontal">
            <label class="control-label col-md-3 w-auto" style="min-width: 128px;">Email Subject</label>
            <div class="col-md-6">
                <input class="form-control" value="@if(isset($plugin['custom_email_subject'])){{$plugin['custom_email_subject']??''}} @else Payment request from %COMPANY_NAME% @endif" type="text" maxlength="200" data-cy="plugin_custom_subject" name="custom_subject" placeholder="Email subject">
            </div>
        </div>
    </div>
</div>
<div class="row mb-2">
    <div class="col-md-12">
        <div class="form-group form-horizontal">
            <label class="control-label col-md-3 w-auto" style="min-width: 128px;">SMS</label>
            <div class="col-md-6">
                <textarea class="form-control" type="text" maxlength="200" data-cy="plugin_custom_sms" name="custom_sms" placeholder="Payment request SMS">{{$plugin['custom_sms']??'You have received a payment request from %COMPANY_NAME% for amount %TOTAL_AMOUNT%. To make an online payment, access your bill via %SHORT_URL%'}}</textarea>
            </div>
        </div>
    </div>
</div>
<hr>
@php $has_plugin=1; @endphp
@endif
@if(isset($plugin['has_custom_reminder']))
<h3 class="form-section base-font">Custom reminder
    <a onclick="AddInvoiceReminder();" class="btn btn-sm green pull-right "> <i class="fa fa-plus"> </i> Add new row </a>
    <input type="hidden" value="" id="cust_reminderval">
</h3>
<div class="row">
    <div class="col-md-12">
        <div class="table-scrollable">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="td-c  default-font" style="width: 150px;">
                            Reminder before day
                        </th>
                        <th class="td-c  default-font">
                            Reminder subject
                        </th>
                        <th class="td-c  default-font">
                            Reminder SMS
                        </th>

                        <th class="td-c" style="width: 50px;">

                        </th>
                    </tr>
                </thead>
                <tbody id="new_reminder">
                    @if(!empty($plugin['reminders']))
                    @php $int =1; @endphp
                    @foreach($plugin['reminders'] as $k=>$v)
                    <tr>
                        <td>
                            <div class="input-icon right">
                                <input type="number" data-cy="plugin_reminder_day{{$int}}" step="1" min="0" max="50" name="reminders[]" id="rm{{$k}}" value="{{$k}}" class="form-control">
                            </div>
                        </td>
                        <td>
                            <div class="input-icon right">
                                <input type="text" name="reminder_subject[]" data-cy="plugin_reminder_subject{{$int}}" value="{{$v['email_subject']}}" class="form-control">
                            </div>
                        </td>
                        <td>
                            <div class="input-icon right">
                                <input type="text" name="reminder_sms[]" data-cy="plugin_reminder_sms{{$int}}" value="{{$v['sms']}}" class="form-control">
                            </div>
                        </td>
                        <td>
                            <a href="javascript:;" data-cy="plugin_reminder_remove{{$int}}" onclick="$(this).closest('tr').remove();" class="btn btn-sm red"> <i class="fa fa-times"> </i></a>
                        </td>
                    </tr>
                    @php $int++; @endphp
                    @endforeach

                    @endif
                </tbody>
            </table>

        </div>
        @if(isset($plugin['has_custom_reminder']))
        <input type="hidden" value="1" name="has_custom_reminder">
        @endif

    </div>
</div>
<hr>
@php $has_plugin=1; @endphp
@endif
@if(isset($plugin['has_partial']))
<div id="partial_div">
    <h3 class="form-section base-font mb-2">Partial Payment
        <div class="pull-right">
            <input type="checkbox" data-cy="plugin_partial" onchange="disablePlugin(this.checked, 'plg13');checkValue('is_partial_', this.checked);" @if(isset($plugin['has_partial']) && $plugin['has_partial']=='1' ) checked="" value="1" @else value="0" @endif class="make-switch" data-size="small">
            <input type="hidden" id="is_partial_" name="has_partial" @if(isset($plugin['has_partial']) && $plugin['has_partial']=='1' ) value="1" @else value="0" @endif />
        </div>
    </h3>
    <div id="min_partial_payment_div" class="mb-2" @if(isset($plugin['has_partial']) && $plugin['has_partial']=='1' ) style="display: block;" @else style="display:none;" @endif>
        <div class="form-group form-horizontal">
            <label class="control-label col-md-3 w-auto">Minimum partial amount</label>
            <div class="col-md-3">
                <input type="number" data-cy="plugin_partial_min_amt" name="partial_min_amount" id="pma" @if(isset($plugin['has_partial']) && $plugin['has_partial']=='1' ) min="50" @endif step="0.01" value="{{$plugin['partial_min_amount']}}" class="form-control">
            </div>
        </div>
    </div>
</div>
<hr>
@php $has_plugin=1; @endphp
@endif
@if(isset($plugin['has_e_invoice']))
<div id="e_invoice_div">
    <h3 class="form-section base-font mb-2">e-Invoice
        <div class="pull-right">
            <input type="checkbox" data-cy="plugin_e_invoice" onchange="disablePlugin(this.checked, 'plg21');checkValue('is_e_invoice_', this.checked);" @if(isset($plugin['has_e_invoice']) && $plugin['has_e_invoice']=='1' ) checked="" value="1" @else value="0" @endif class="make-switch" data-size="small">
            <input type="hidden" id="is_e_invoice_" name="has_e_invoice" @if(isset($plugin['has_e_invoice']) && $plugin['has_e_invoice']=='1' ) value="1" @else value="0" @endif />
        </div>
    </h3>
    <div id="notify_e_invoice_div" class="mb-2" @if(isset($plugin['has_e_invoice']) && $plugin['has_e_invoice']=='1' ) style="display: block;" @else style="display:none;" @endif>
        <div class="form-group form-horizontal">
            <label class="control-label col-md-3 w-auto">Send e-Invoice to customer</label>
            <div class="col-md-3">
                <input type="checkbox" data-cy="plugin_notify_e_invoice" checked="" onchange="checkValue('is_notify_e_invoice_', this.checked);" value="1" class="make-switch" data-size="small">
                <input type="hidden" id="is_notify_e_invoice_" name="notify_e_invoice" @if(isset($plugin['has_e_invoice']) && $plugin['has_e_invoice']=='1' ) value="1" @else value="0" @endif />
            </div>
        </div>
    </div>
</div>
<hr>
@php $has_plugin=1; @endphp
@endif
@if(isset($plugin['has_coupon']))
<h3 class="form-section base-font mb-2">Coupon
    <a class="btn btn-sm green pull-right" data-toggle="modal" href="#coupon">
        <i class="fa fa-plus"></i> Add new coupon</a>
</h3>
<div class="mb-2">
    <div class="form-group form-horizontal">
        <label class="control-label col-md-3 w-auto">Select coupon</label>
        <div class="col-md-3">
            <select style="min-width: 150px;" data-cy="plugin_coupon_id" name="coupon_id" id="coupon_select" class="form-control" data-placeholder="Select...">
                <option value="0">Select coupon</option>
                @if(!empty($coupon_list))
                @foreach($coupon_list as $v)
                <option value="{{$v->coupon_id}}">{{$v->coupon_code}}</option>
                @endforeach
                @endif
            </select>
        </div>
    </div>
</div>
<hr>
@php $has_plugin=1; @endphp
@endif
</div>
</div>
</div>
<div class="display-none" id="tax_div">
    <select style="width: 100%;" onchange="setTaxCalculatedOn(this.value,$(this).attr('data-cy'));" name="tax_id[]" data-placeholder="Select..." class="form-control  taxselectdefault">
        <option value="">Select</option>
        @if(!empty($tax_list))
        @foreach($tax_list as $k=>$v)
        <option value="{{$k}}">{{$v['tax_name']}}</option>
        @endforeach
        @endif
    </select>
</div>

<div class="portlet light bordered">
    <div class="portlet-body form">
        <h3 class="form-section">Final summary</h3>
        <div class="row">
            <div class="col-md-2 w-auto">
                <div class="form-group">
                    <p>Fee value with taxes</p>
                    <input type="text" id="totalamount" data-cy="invoice_total" name="grand_total" value="{{$info->invoice_total??0}}" readonly class="form-control">
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <p>Grand total</p>
                    <input type="text" id="grandtotal" data-cy="grand_total" value="{{$info->grand_total??0}}" readonly class="form-control">
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <p>Narrative</p>
                    <div class="input-icon right">
                        <input type="text" data-cy="narrative" name="invoice_narrative" value="{{$info->narrative??''}}" class="form-control" placeholder="Enter narrative ">
                    </div>
                </div>
            </div>
         
           <div class="col-md-2 w-auto">
                <div class="form-group">
                    <p>Notify {{$customer_default_column['customer']??'Customer'}}</p>
                    <div class="input-icon">
                        <input type="checkbox" data-cy="notify" id="notify_" onchange="notifyPatron('notify_');" value="1" @if(isset($info->notify_patron)) @if($info->notify_patron==1) checked @endif @else checked @endif class="make-switch" data-size="small">
                    </div>
                </div>
            </div>
           
            <div class="col-md-12 pull-right">
                <div class="pull-right">
                    <p>&nbsp;</p>
                    <input type="hidden" id="custom_covering" name="custom_covering" value="" />
                    <input type="hidden" id="template_type" name="template_type" value="@if(isset($template_info->template_type)){{$template_info->template_type}}@endif" />
                    @if($mode=='update')
                    <input type="hidden" name="payment_request_id" value="{{$payment_request_link}}" />
                    <input type="hidden" name="staging" value="{{$staging}}" />
                    @endif
                    <input type="hidden" name="template_id" value="{{$template_link}}" />
                    <input type="hidden" id="totalcostamt" name="totalcost" value="{{$info->basic_amount??0}}">
                    <input type="hidden" id="is_notify_" name="notify_patron" value="{{$info->notify_patron??1}}" />
                    <input type="hidden" name="request_type" value="@if($invoice_type==2) 2 @else 1 @endif" />
                    <input type="hidden" id="preview_invoice_status" name="payment_request_status" value="{{$info->payment_request_status??0}}" />
                    <button type="reset" data-cy="btn-reset" class="btn default">Reset</button>
                    @if($mode=='create' || $mode=='update' || (isset($info->payment_request_status) && $info->payment_request_status==11))
                    <input type="submit" data-cy="btn-submit" class="btn default" id="savepreview" @if($invoice_type==1) value="Preview invoice" @else value="Preview estimate" @endif onclick="saveInvoicePreviewStatus('savepreview');">
                    @endif
                    <input type="submit" data-cy="btn-submit" class="btn blue" id="subbtn" @if(isset($info->notify_patron)) @if($info->notify_patron==1) value="Save & Send" @else value="Save" @endif @else value="Save & Send" @endif onclick="saveInvoicePreviewStatus('subbtn');">
                </div>
            </div>
            @if(isset($plugin['has_signature']))
            @if(isset($signature->font_file))
            <link href="{{$signature->font_file??''}}" rel="stylesheet">
            @endif
            @if(!empty($signature))
            <div class="row" style="padding-right: 20px;padding-left: 20px;">
                <div class="col-md-12">
                    <div class="pull-{{$signature->align??'left'}}">
                        @if($signature->type=='font')
                        <label style="font-family: '{{$signature->font_name??''}}',cursive;font-size: {{$signature->font_size??''}}px;">{{$signature->name??''}}</label>
                        @else
                        <img src="{{$signature->signature_file??''}}" style="max-height: 100px;">
                        @endif
                    </div>
                </div>
            </div>
            @endif
            @endif

            <!--/span-->

            <!-- grand total label ends -->
        </div>

        </form>
    </div>
</div>

<div class="modal fade" id="amount_check" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Create zero value invoice?</h4>
            </div>
            <div class="modal-body">
                Invoice grand total is currently 0. This will create a zero value G702 & G703. Would you like to proceed?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn default" data-dismiss="modal">Cancel</button>
                <a href="javascript;" onclick="validateInvoice(@if(isset($payment_request_id))'{{$payment_request_id}}' @else '' @endif);" id="deleteanchor" class="btn blue">Save</a>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- END PAGE CONTENT-->
</div>

<script>
    @if(isset($has_plugin))
    document.getElementById('plugin_div').style.display = 'block';
    @endif
</script>



