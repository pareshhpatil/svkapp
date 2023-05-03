</div>
</div>
<!-- END CONTENT -->
</div>
<!-- /.modal -->
<div class="modal fade" id="coupon" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/merchant/coupon/save" id="couponForm" method="post" class="form-horizontal form-row-sepe">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Add new coupon</h4>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-12">
                                <div class="form">
                                    <!--<h3 class="form-section">Profile details</h3>-->

                                    <div class="form-body">
                                        <!-- Start profile details -->
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="alert alert-danger display-none">
                                                    <button class="close" data-dismiss="alert"></button>

                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Coupon code <span class="required">*
                                                        </span></label>
                                                    <div class="col-md-4">
                                                        <input type="text" required data-cy="coupon_code" name="coupon_code" id="coupon_code" class="form-control" value="">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Coupon description <span class="required">*
                                                        </span></label>
                                                    <div class="col-md-6">
                                                        <textarea required name="descreption" data-cy="coupon_description" class="form-control"></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Start date <span class="required">*
                                                        </span></label>
                                                    <div class="col-md-4">
                                                        <input class="form-control form-control-inline date-picker" type="text" required value="" data-cy="coupon_start-date" name="start_date" autocomplete="off" {!!$validate->date!!} data-date-format="{{ Session::get('default_date_format')}}" placeholder="Start date" />
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">End date <span class="required">*
                                                        </span></label>
                                                    <div class="col-md-4">
                                                        <input class="form-control form-control-inline date-picker" type="text" required value="" data-cy="coupon_end-date" name="end_date" autocomplete="off" {!!$validate->date!!} data-date-format="{{ Session::get('default_date_format')}}" placeholder="End date" />
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Number of coupons<span class="required">*
                                                        </span></label>
                                                    <div class="col-md-4">
                                                        <input type="number" min="0" value="0" required name="limit" data-cy="coupon_limit" class="form-control" value="">
                                                    </div>Keep 0 for unlimited
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Coupon offer type</label>
                                                    <div class="col-md-4">
                                                        <select name="is_fixed" data-cy="coupon_type" class="form-control" onchange="isFixed(this.value);" data-placeholder="Select...">
                                                            <option value="1">Fixed amount</option>
                                                            <option value="2">Percentage</option>

                                                        </select>
                                                        <span class="help-block">
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="form-group" id="per_div" style="display: none;">
                                                    <label class="control-label col-md-4">Percentage <span class="required">*
                                                        </span></label>
                                                    <div class="col-md-4">
                                                        <input type="number" min="0" required id="per" data-cy="coupon_percent" name="percent" class="form-control" value="">
                                                    </div>%
                                                </div>
                                                <div class="form-group" id="fixed_div">
                                                    <label class="control-label col-md-4">Fixed amount <span class="required">*
                                                        </span></label>
                                                    <div class="col-md-4">
                                                        <input type="number" min="0" required id="fix" data-cy="coupon_amount" name="fixed_amount" class="form-control" value="">
                                                    </div>
                                                </div>

                                            </div>


                                        </div>
                                    </div>
                                    <!-- End profile details -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn default" id="closebutton" data-cy="coupon_btn-close" data-dismiss="modal">Close</button>
                    <input type="submit" onclick="saveCoupon();" data-cy="coupon_btn-save" id="btnSubmit" class="btn blue" value="Save" />
                    
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade bs-modal-lg in" id="custom" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form action="" method="post" id="customerForm" class="form-horizontal">
        {!!Helpers::csrfToken('invoice_customer')!!}
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" id="closebutton1" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">New Customer</h4>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div id="warning" style="display:none;" class="portlet ">
                            <div class="portlet-title">
                                <div class="caption">
                                    Duplicate customer entry?
                                </div>

                            </div>
                            <div class="portlet-body">
                                <div class="alert alert-block alert-warning fade in">

                                    <h4 class="alert-heading">Warning!</h4>
                                    <p id="ex_message">
                                        This Email ID, Mobile Number already exists in your customer database. You could
                                        either replace this record or create a new entry with same values.
                                        Alternatively you can edit the records entered from the Customer create screen
                                        below.
                                    </p>
                                    <br>
                                    <br>
                                    <p class="pull-right" style="margin-top: -25px;">
                                        <button type="submit" id="ex_delete" onclick="return deleteCustomer();" class="btn red btn-sm">
                                            Disable Existing & Add New </button>
                                        <button type="submit" id="ex_add" onclick="return saveCustomer();" class="btn green btn-sm">
                                            Save As New </button>
                                        <a onclick="return confirmreplace();" class="btn blue btn-sm">
                                            Replace existing customer data</a>
                                        <a href="#basic" id="confirmm" data-toggle="modal">
                                        </a>
                                        <a onclick="document.getElementById('closebutton1').click();" class="btn default btn-sm"> Cancel </a>
                                    </p>
                                </div>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="td-c">
                                                {{$customer_default_column['customer_code']??'Customer code'}}
                                            </th>
                                            <th class="td-c">
                                            {{$customer_default_column['customer_name']??'Name'}}
                                            </th>
                                            <th class="td-c">
                                            {{$customer_default_column['email']??'Email'}}
                                            </th>
                                            <th class="td-c">
                                            {{$customer_default_column['mobile']??'Mobile'}}
                                            </th>
                                            <th class="td-c">
                                                Replace?
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="allcusta">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="portlet-body form">

                            <div class="form-body">
                                <!-- Start profile details -->
                                <div class="alert alert-danger display-none">
                                    <button class="close" data-dismiss="alert"></button>
                                    You have some form errors. Please check below.
                                </div>
                                <div class="alert alert-danger" style="display: none;" id="errorshow">
                                    <button class="close" onclick="document.getElementById('errorshow').style.display = 'none';"></button>
                                    <p id="error_display">Please correct the below errors to complete registration.</p>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-4">{{$customer_default_column['customer_code']??'Customer code'}}<span class="required">*
                                                </span></label>
                                            <div class="col-md-7">
                                                <input type="text" id="customer_code" data-cy="customer_code" name="customer_code" @if($customer_auto_generate==0) required @else readonly value="Auto generate" @endif class="form-control">
                                                <input type="hidden" id="customer_code" name="auto_generate" value="{{$customer_auto_generate}}">
                                            </div>
                                            <div class="col-md-3">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-4"> {{$customer_default_column['customer_name']??'Customer name'}}<span class="required">*
                                                </span></label>
                                            <div class="col-md-7">
                                                <input type="text" data-cy="customer_name" name="customer_name" required value="" class="form-control" onpaste="return false;"
                                                onkeydown="return /[a-zA-Z\s]/i.test(event.key)" {$validate.name}>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-4"> {{$customer_default_column['email']??'Email'}}<span class="required">
                                                </span></label>
                                            <div class="col-md-7">
                                                <input type="email" data-cy="customer_email" name="email" id="defaultemail" value="" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">{{$customer_default_column['mobile']??'Mobile'}}<span class="required">
                                                </span></label>
                                            <div class="col-md-7">
                                                <div class="input-group">
                                                    <span class="input-group-addon" id="country_code_txt">+91</span>
                                                    <input type="text" data-cy="customer_mobile" {!!$validate->mobile!!} title="Enter your valid mobile number" id="defaultmobile" name="mobile" value="" class="form-control" maxlength="10">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-4">{{$customer_default_column['address']??'Address'}}<span class="required">
                                                </span></label>
                                            <div class="col-md-7">
                                                <textarea value="" name="address" data-cy="customer_address" class="form-control"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">{{$customer_default_column['country']??'Country'}}</label>
                                            <div class="col-md-7">
                                                <select name="country" data-cy="customer_country" style="width: 100%" onchange="showStateDiv(this.value);" required class="form-control select2me" data-placeholder="Select...">
                                                    <option value="">Select Country</option>
                                                    @if(!empty($country_code))
                                                    @foreach($country_code as $v)
                                                    <option @if($v->config_value=='India') selected @endif
                                                        value="{{$v->config_value}}">{{$v->config_value}}</option>
                                                    @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">{{$customer_default_column['state']??'State'}}</label>
                                            <div class="col-md-7" id="state_drpdown">
                                                <select name="state" data-cy="customer_state" style="width: 100%" required class="form-control select2mepop" data-placeholder="Select...">
                                                    <option value="">Select State</option>
                                                    @if(!empty($state_code))
                                                    @foreach($state_code as $v)
                                                    <option @if($merchant_state==$v->config_value) selected @endif
                                                        value="{{$v->config_value}}">{{$v->config_value}}</option>
                                                    @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="col-md-7" id="state_txt" hidden>
                                                <input type="text" name="state1" value="" class="form-control" data-cy="customer_state">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">{{$customer_default_column['city']??'City'}}<span class="required">
                                                </span></label>
                                            <div class="col-md-7">
                                                <input type="text" name="city" data-cy="customer_city" value="" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">{{$customer_default_column['zipcode']??'Zipcode'}}<span class="required">
                                                </span></label>
                                            <div class="col-md-7">
                                                <input type="text" name="zipcode" data-cy="customer_zipcode" value="" class="form-control">
                                            </div>
                                        </div>
                                    </div>


                                    <div class="loading" id="loader" style="display: none;">Loading&#8230;</div>
                                </div>
                                <!-- End profile details -->


                                <h4 class="modal-title">Custom Fields </h4>

                                <div class="row">
                                    <div class="col-md-6">
                                        @if(!empty($customer_custom_column))
                                        @foreach($customer_custom_column as $v)
                                        @if($v->position=='L')
                                        <div class="form-group">
                                            <input name="column_id[]" type="hidden" value="{{$v->column_id}}">
                                            <label class="control-label col-md-4">{{$v->column_name}}<span class="required"></span></label>
                                            <div class="col-md-7">
                                                @if($v->column_datatype=='textarea')
                                                <textarea maxlength="500" class="form-control" data-cy="customer_custom-{{$v->column_name}}" name="column_value[]"></textarea>
                                                @elseif($v->column_datatype=='date')
                                                <input class="form-control form-control-inline date-picker" data-cy="customer_custom-{{$v->column_name}}" autocomplete="off" {!!$validate->date!!} data-date-format="{{ Session::get('default_date_format')}}" name="column_value[]" type="text" />
                                                @elseif($v->column_datatype=='number')
                                                <input type="number" maxlength="100" class="form-control" data-cy="customer_custom-{{$v->column_name}}" value="" name="column_value[]">
                                                @else
                                                <input maxlength="100" type="text" class="form-control" data-cy="customer_custom-{{$v->column_name}}" name="column_value[]">
                                                @endif
                                            </div>
                                        </div>
                                        @endif
                                        @endforeach
                                        @endif
                                    </div>


                                    <div class="col-md-6">
                                        @if(!empty($customer_custom_column))
                                        @foreach($customer_custom_column as $v)
                                        @if($v->position=='R')
                                        <div class="form-group">
                                            <input name="column_id[]" type="hidden" value="{{$v->column_id}}">
                                            <label class="control-label col-md-4">{{$v->column_name}}<span class="required"></span></label>
                                            <div class="col-md-7">
                                                @if($v->column_datatype=='textarea')
                                                <textarea maxlength="500" class="form-control" data-cy="customer_custom-{{$v->column_name}}" name="column_value[]"></textarea>
                                                @elseif($v->column_datatype=='date')
                                                <input class="form-control form-control-inline date-picker" autocomplete="off" {!!$validate->date!!} data-date-format="{{ Session::get('default_date_format')}}" name="column_value[]" type="text" /> @elseif($v->column_datatype=='number')
                                                <input type="number" maxlength="100" class="form-control" value="" data-cy="customer_custom-{{$v->column_name}}" name="column_value[]">
                                                @else
                                                <input maxlength="100" type="text" class="form-control" data-cy="customer_custom-{{$v->column_name}}" name="column_value[]">
                                                @endif
                                            </div>
                                        </div>
                                        @endif
                                        @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>


                        </div>


                    </div>
                </div>

                <div class="modal-footer">

                    <input type="hidden" id="template_id" name="template_id" value="@if(isset($template_info->template_id)){{$template_info->template_id}}@endif" />
                    <input type="hidden" id="customer_id_" name="customer_id" value="" />
                    <button type="button" data-cy="customer_btn-close" class="btn default" id="closebutton" data-dismiss="modal">Close</button>
                    <button type="submit" data-cy="customer_btn-save" onclick="return display_warning();" class="btn blue">Save</button>
                </div>
            </div>
    </form>
    <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</div>

<div class="modal fade bs-modal-lg" id="supplier" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                Select supplier
                <button type="button" id="closeguest" class="close" data-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">

                @if(!empty($supplier))
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="td-c">
                                Supplier company name
                            </th>
                            <th class="td-c">
                                Contact person name
                            </th>
                            <th class="td-c">
                                Mobile
                            </th>
                            <th class="td-c">
                                Select
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($supplier as $v)
                        <tr>
                            <td class="td-c">
                                <div id="spname{{$v->supplier_id}}">{{$v->supplier_company_name}}</div>
                            </td>
                            <td class="td-c">
                                <div id="spcontact{{$v->supplier_id}}">{{$v->contact_person_name}}</div>
                            </td>
                            <td class="td-c">
                                <div id="spmobile{{$v->supplier_id}}">{{$v->mobile1}}</div>
                            </td>

                            <td class="td-c">
                                <div id="spemail{{$v->supplier_id}}" style="display: none;">{{$v->email_id1}}</div>

                                <input type="checkbox" @if(!empty($plugin['supplier'])) @if(in_array($v->supplier_id,$plugin['supplier'])) checked @endif
                                @endif
                                value="{{$v->supplier_id}}" id="spid{{$v->supplier_id}}" onchange="AddsupplierRow(this.value);" />

                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="row no-margin">
                    <button type="button" class="btn blue pull-right" data-dismiss="modal" aria-hidden="true">Done</button>
                </div>
                @endif
            </div>

        </div>

    </div>
    <!-- /.modal-content -->
</div>





<div class="modal  fade" id="new_addtax" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Add new Tax</h4>
            </div>
            <form action="/merchant/tax/taxsave" method="post" id="tax_frm" class="form-horizontal form-row-sepe">
                {!!Helpers::csrfToken('tax_save')!!}
                <div class="form-body">
                    <!-- Start profile details -->
                    <div class="row">
                        <div class="col-md-12">
                            <br>
                            <div id="tax_error" class="alert alert-danger" style="display: none;">

                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-4">Tax name <span class="required">*
                                    </span></label>
                                <div class="col-md-4">
                                    <input type="text" data-cy="tax-name" required name="tax_name" maxlength="100" class="form-control" value="">
                                </div>
                            </div>



                            <div class="form-group">
                                <label class="control-label col-md-4 ">Tax Type <span class="required">*
                                    </span></label>
                                <div class="col-md-4">
                                    <select name="tax_type" data-cy="tax-type" onchange="changeTaxtype(this.value);" required class="form-control" data-placeholder="Select...">
                                        <option value="">Select Type</option>
                                        @if(!empty($tax_type))
                                        @foreach($tax_type as $v)
                                        <option value="{{$v->config_key}}">{{$v->config_value}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group display-none" id="tax_calculated_on">
                                <label class="control-label col-md-4 ">Tax calculated on </label>
                                <div class="col-md-4">
                                    <select name="tax_calculated_on" class="form-control" data-placeholder="Select...">
                                        @if(!empty($tax_calculated_on))
                                        @foreach($tax_calculated_on as $t)
                                        <option value="{{$t->config_key}}">{{$t->config_value}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group" id="tpercent">
                                <label class="control-label col-md-4">Percentage <span class="required">
                                    </span></label>
                                <div class="col-md-4">
                                    <input type="number" step="0.01" data-cy="tax-percent" name="percentage" class="form-control" value="">
                                </div>
                            </div>
                            <div class="form-group display-none" id="tamount">
                                <label class="control-label col-md-4">Amount <span class="required">
                                    </span></label>
                                <div class="col-md-4">
                                    <input type="number" step="0.01" data-cy="tax-amount" name="tax_amount" class="form-control" value="">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-4">Description <span class="required">
                                    </span></label>
                                <div class="col-md-4">
                                    <textarea name="description" data-cy="tax-description" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
                <!-- End profile details -->



            </form>
            <div class="modal-footer">
                <button type="button" id="tclosebutton"  data-cy="tax-btn-close" class="btn default" data-dismiss="modal">Close</button>
                <input type="submit" onclick="return saveTax();" data-cy="tax-btn-save" value="Save" class="btn blue">
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>



 {{-- confirmation dialogue --}}
 <div class="modal  fade" id="confirm_box" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button"   class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Discard changes</h4>
            </div>
            <div class="form-body form-horizontal form-row-sepe" style="padding: 10px">
                <p>Changes will not be saved. Do you want to proceed?</p>
            </div> 
          

          
            <div class="modal-footer">
                <button type="button"  data-cy="covering-btn-close" id="cancel_confirm_box" class="btn default" data-dismiss="modal">Close</button>
                <input type="button" data-cy="editcovering-btn-save" onclick="closeSidePanelcover()"   value="Discard" class="btn blue">
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
@include('app.merchant.cover-note.add-covernote-modal')
@include('app.merchant.cover-note.edit-covernote-modal')

<div class="modal fade" id="basic" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Replace customer</h4>
            </div>
            <div class="modal-body">
                Are you sure you want to replace <span id="totalchecked"></span> customer records with newly entered
                customer data?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn default" data-dismiss="modal">Close</button>
                <a href="" onclick="return updatemultiCustomer();" class="btn blue" data-dismiss="modal">Confirm</a>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
 <!-- /.delete attachment-dialog -->
<div class="modal fade" id="attach-delete" tabindex="-1" role="attach-delete" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Delete attachment</h4>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this attachment?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn default" data-dismiss="modal">Close</button>
                <input type="hidden" id="removepath">
                <a  id="attach-delete-click" onclick="deleteattchment()" data-dismiss="modal" class="btn delete">Confirm</a>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<!-- Side panel code for Customer ledger -->

@livewire('format.customer-ledger')

<!-- Side panel code for adding product/service -->
@include('app.merchant.product.side-panel-product',['redirect' => '/merchant/expense/create'])





<script>
    function isFixed(id) {
        if (id == 1) {
            $("#fixed_div").slideDown(200).fadeIn();
            $("#per_div").slideDown(200).fadeOut();
        } else {
            $("#per_div").slideDown(200).fadeIn();
            $("#fixed_div").slideDown(200).fadeOut();
        }
    }
</script>