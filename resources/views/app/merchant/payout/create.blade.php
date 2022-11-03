@extends('app.master')

@section('content')
<div class="page-content">
    <div class="page-bar">
        <span class="page-title" style="float: left;">{{$title}}</span>
        {{ Breadcrumbs::render() }}
    </div>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">
            @if(!empty($validerrors))
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert"></button>
                    <strong>Error!</strong>
                    <div class="media">
                        @foreach ($validerrors as $v)
                            <p class="media-heading">{{$v}}</p>
                        @endforeach
                    </div>
                </div>
            @endif
            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <!--<h3 class="form-section">Profile details</h3>-->
                    <form action="/merchant/beneficiary/save" onsubmit="loader();" method="post" class="form-horizontal form-row-sepe">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-body">
                            <!-- Start profile details -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alert alert-danger display-none">
                                        <button class="close" data-dismiss="alert"></button>
                                        You have some form errors. Please check below.
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Beneficiary type <span class="required">*
                                            </span></label>
                                        <div class="col-md-4">
                                            <select class="form-control" id="beneficiarytype" onchange="setBenifeciaryType(this.value);" data-placeholder="Beneficiary type" required="" name="type" >
                                                <option value="">Select type</option>
                                                <option value="Customer">Customer</option>
                                                <option value="Employee">Employee</option>
                                                <option value="Franchise">Franchise</option>
                                                <option value="Vendor">Vendor</option>
                                                <option value="Other">Other</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group display-none" id="divtype">
                                        <label class="control-label col-md-4" id="lbltype">Select Customer</label>
                                        <div class="col-md-4">
                                            <div class="">
                                                <select id="customer_id" onchange="setBenifeciaryDetail(this.value)" class="form-control select2me" data-placeholder="Select...">
                                                </select>
                                            </div>	
                                        </div>
                                        <div class="col-md-1 no-margin no-padding">
                                            <a data-toggle="modal" title="Add new customer" id="addnewlink" href="#custom" class="btn green"><i class="fa fa-plus"></i></a>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Beneficiary code <span class="required">
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="text" required id="code"  maxlength="45" {{$validate->name}} name="beneficiary_code"  class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Name <span class="required">*
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="text" required id="name"  maxlength="100" {{$validate->name}} name="name"  class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-4">Email id <span class="required">*
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="email" required=""  id="email" maxlength="250" name="email_id" class="form-control" value="">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Mobile no<span class="required">*
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="text" id="mobile" maxlength="12" required {{$validate->mobile}} name="mobile" class="form-control" >
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Bank Account<span class="required">*
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="text" required maxlength="25" name="account_number"   class="form-control" >
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">IFSC code <span class="required">*
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="text" required=""  maxlength="20" name="ifsc" class="form-control" >
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">UPI id <span class="required">
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="text"  maxlength="45" name="upi" class="form-control" >
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Address <span class="required">*
                                            </span></label>
                                        <div class="col-md-4">
                                            <textarea type="text" id="address" required name="address" maxlength="250" class="form-control"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">City <span class="required">
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="text" id="city" maxlength="45" name="city" {{$validate->city}} class="form-control" >
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">State <span class="required">
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="text" id="state" maxlength="45" name="state" {{$validate->city}} class="form-control" >
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Zipcode <span class="required">
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="text" id="zipcode" maxlength="6" name="pincode" {{$validate->zipcode}} class="form-control" >
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>					
                        <!-- End profile details -->
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="pull-right">
                                    
                                    <a href="/merchant/beneficiary/viewlist" class="btn btn-default">Cancel</a>
                                    <input type="submit" value="Save" class="btn blue"/>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>	
        <!-- END PAGE CONTENT-->
    </div>
</div>
<!-- END CONTENT -->
</div>

<div class="modal fade bs-modal-lg in" id="MDCustomer" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form action="" method="post" id="customerForm"  class="form-horizontal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" id="closebutton1" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">New Customer</h4>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div id="warning" style="display:none;" class="portlet">
                            <div class="portlet-title">
                                <div class="caption">
                                    Duplicate customer entry?
                                </div>

                            </div>
                            <div class="portlet-body">
                                <div  class="alert alert-block alert-warning fade in">

                                    <h4 class="alert-heading">Warning!</h4>
                                    <p id="ex_message">
                                        This Email ID, Mobile Number already exists in your customer database. You could either replace this record or create a new entry with same values. 
                                        Alternatively you can edit the records entered from the Customer create screen below.
                                    </p>
                                    <br>
                                    <br>
                                    <p class="pull-right" style="margin-top: -25px;">
                                        <button type="submit" id="ex_delete" onclick="return deleteCustomer();" class="btn red btn-sm" >
                                            Disable Existing & Add New  </button>
                                        <button type="submit" id="ex_add" onclick="return saveCustomer();" class="btn green btn-sm" >
                                            Save As New </button>
                                        <a  onclick="return confirmreplace();" class="btn blue btn-sm" >
                                            Replace existing customer data</a>
                                        <a   href="#basic" id="confirmm" data-toggle="modal">
                                        </a>
                                        <a onclick="document.getElementById('closebutton1').click();" class="btn default btn-sm"> Cancel </a>
                                    </p>
                                </div>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="td-c">
                                                Customer code
                                            </th>
                                            <th class="td-c">
                                                Name
                                            </th>
                                            <th class="td-c">
                                                Email
                                            </th>
                                            <th class="td-c">
                                                Mobile
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
                                            <label class="control-label col-md-4">Customer code<span class="required">* </span></label>
                                            <div class="col-md-7">
                                                <input type="text" id="customer_code" name="customer_code" class="form-control" @if($merchant_setting->customer_auto_generate==0) required @else readonly  value="Auto generate"  @endif>
                                                       <input type="hidden" id="customer_code" name="auto_generate" value="{{$merchant_setting->customer_auto_generate}}" >
                                            </div>
                                            <div class="col-md-3">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-4">Customer name<span class="required">* </span></label>
                                            <div class="col-md-7">
                                                <input type="text" name="customer_name" required value="" class="form-control" >
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-4">Email<span class="required"> </span></label>
                                            <div class="col-md-7">
                                                <input type="email" name="email" id="defaultemail" value="" class="form-control" >
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Mobile<span class="required"> </span></label>
                                            <div class="col-md-7">
                                                <input type="text" name="mobile" class="form-control" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Address<span class="required"> </span></label>
                                            <div class="col-md-7">
                                                <textarea  value="" name="address" class="form-control" ></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">City<span class="required"> </span></label>
                                            <div class="col-md-7">
                                                <input type="text" name="city"  value="" class="form-control" >
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">State<span class="required"> </span></label>
                                            <div class="col-md-7">
                                                <input type="text" name="state"  value="" class="form-control" >
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Zipcode<span class="required"> </span></label>
                                            <div class="col-md-7">
                                                <input type="text" name="zipcode"  value="" class="form-control" >
                                            </div>
                                        </div>
                                    </div>


                                    <div class="loading" id="loader" style="display: none;">Loading&#8230;</div>
                                </div>
                                <!-- End profile details -->
                            </div>

                            <h4 class="modal-title">Custom Fields </h4> 
                            <div  class="row">
                                <div class="col-md-6">
                                    @foreach ($column as $v)
                                    @if($v->position=='L')
                                    <div  class="form-group">
                                        <input name="column_id[]" type="hidden" value="{{$v->column_id}}">
                                        <label class="control-label col-md-4">{{$v->column_name}}<span class="required"></span></label>
                                        <div class="col-md-7">
                                            @if($v->column_datatype=='textarea')
                                            <textarea maxlength="500" class="form-control" name="column_value[]"></textarea>
                                            @elseif($v->column_datatype=='date')
                                            <input class="form-control form-control-inline date-picker"   autocomplete="off" data-date-format="{{ Session::get('default_date_format')}}"  name="column_value[]" type="text" />  
                                            @elseif($v->column_datatype=='number')
                                            <input type="number" maxlength="100" class="form-control" value="" name="column_value[]">
                                            @else
                                            <input maxlength="100" type="text" class="form-control" name="column_value[]">
                                            @endif
                                        </div>
                                    </div>
                                    @endif
                                    @endforeach
                                </div>
                                <div class="col-md-6">
                                    @foreach ($column as $v)
                                    @if($v->position=='R')
                                    <div  class="form-group">
                                        <input name="column_id[]" type="hidden" value="{{$v->column_id}}">
                                        <label class="control-label col-md-4">{{$v->column_name}}<span class="required"></span></label>
                                        <div class="col-md-7">
                                            @if($v->column_datatype=='textarea')
                                            <textarea maxlength="500" class="form-control" name="column_value[]"></textarea>
                                            @elseif($v->column_datatype=='date')
                                            <input class="form-control form-control-inline date-picker"   autocomplete="off" data-date-format="{{ Session::get('default_date_format')}}"  name="column_value[]" type="text" />  
                                            @elseif($v->column_datatype=='number')
                                            <input type="number" maxlength="100" class="form-control" value="" name="column_value[]">
                                            @else
                                            <input maxlength="100" type="text" class="form-control" name="column_value[]">
                                            @endif
                                        </div>
                                    </div>
                                    @endif
                                    @endforeach
                                </div>
                            </div>



                        </div>


                    </div>
                </div>

                <div class="modal-footer">
                    <input type="hidden" id="template_id" name="template_id" value="{$info.template_id}}"/> 
                    <input type="hidden" id="customer_id_" name="customer_id" value=""/> 
                    <button type="button" class="btn default" id="closebutton" data-dismiss="modal">Close</button>
                    <button type="submit" onclick="return saveBeneCustomer();" class="btn blue">Save</button>
                </div>
            </div>

            <!-- /.modal-content -->
        </div>
    </form>	
    <!-- /.modal-dialog -->
</div>

<div class="modal fade bs-modal-lg in" id="MDVendor" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form action="" method="post" id="vendorForm"  class="form-horizontal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" id="closebutton1" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">New Vendor</h4>
                </div>

                <div class="modal-body">
                    <div class="row">
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
                                            <label class="control-label col-md-4">Vendor code <span class="required">
                                                </span></label>
                                            <div class="col-md-7">
                                                <input type="text" @if($merchant_setting->vendor_code_auto_generate==1) readonly value="Auto generate" @endif maxlength="45" name="vendor_code"  class="form-control" >
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Vendor name <span class="required">*
                                                </span></label>
                                            <div class="col-md-7">
                                                <input type="text" required  maxlength="100" name="vendor_name" {{$validate->name}} class="form-control" >
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-4">Email id <span class="required">*
                                                </span></label>
                                            <div class="col-md-7">
                                                <input type="email" required=""  id="f_email" maxlength="250" name="email" class="form-control" >
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Mobile no<span class="required">*
                                                </span></label>
                                            <div class="col-md-7">
                                                <input type="text"  maxlength="12" required {{$validate->mobile}} name="mobile" class="form-control" >
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Address <span class="required">*
                                                </span></label>
                                            <div class="col-md-7">
                                                <textarea type="text" required name="address" maxlength="250" class="form-control"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">City <span class="required">*
                                                </span></label>
                                            <div class="col-md-7">
                                                <input type="text"  required maxlength="45" name="city" {{$validate->city}} class="form-control" >
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">State <span class="required">*
                                                </span></label>
                                            <div class="col-md-7">
                                                <input type="text" required  maxlength="45" name="state" {{$validate->city}} class="form-control" >
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Zipcode <span class="required">*
                                                </span></label>
                                            <div class="col-md-7">
                                                <input type="text" required  maxlength="6" name="zipcode" {{$validate->zipcode}} class="form-control" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Bank account holder name <span class="required">*
                                                </span></label>
                                            <div class="col-md-7">
                                                <input type="text" required maxlength="100" name="account_holder_name" {{$validate->name_text}} class="form-control" >
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Account number <span class="required">*
                                                </span></label>
                                            <div class="col-md-7">
                                                <input type="text" required maxlength="20" name="account_number" pattern="[0-9]"  class="form-control" >
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Bank name <span class="required">*
                                                </span></label>
                                            <div class="col-md-7">
                                                <input type="text" required maxlength="45" name="bank_name" {{$validate->name_text}} class="form-control" >
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Bank account type <span class="required">*
                                                </span></label>
                                            <div class="col-md-7">
                                                <select class="form-control" required name="account_type">
                                                    <option  value="">Select account type</option>
                                                    <option value="Current">Current</option>
                                                    <option value="Saving">Saving</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">IFSC code <span class="required">*
                                                </span></label>
                                            <div class="col-md-7">
                                                <input type="text" required=""  maxlength="20" name="ifsc_code" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="loading" id="loader" style="display: none;">Loading&#8230;</div>
                                </div>
                                <!-- End profile details -->
                            </div>
                        </div>


                    </div>
                </div>

                <div class="modal-footer">
                    <input type="hidden" name="pan" value="">
                    <input type="hidden" name="adhar_card" value="">
                    <input type="hidden" name="gst" value="">
                    <button type="button" class="btn default" id="closebuttonv" data-dismiss="modal">Close</button>
                    <button type="submit" onclick="return saveBeneVendor();" class="btn blue">Save</button>
                </div>
            </div>

            <!-- /.modal-content -->
        </div>
    </form>	
    <!-- /.modal-dialog -->
</div>
<div class="modal fade bs-modal-lg in" id="MDFranchise" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form action="" method="post" id="franchiseForm"  class="form-horizontal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" id="closebutton1" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">New Franchise</h4>
                </div>

                <div class="modal-body">
                    <div class="row">
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
                                            <label class="control-label col-md-4">Franchise company name <span class="required">*
                                                </span></label>
                                            <div class="col-md-7">
                                                <input type="text" required  maxlength="100" name="franchise_name" {{$validate->name}} class="form-control" >
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-4">Contact person name <span class="required">*
                                                </span></label>
                                            <div class="col-md-7">
                                                <input type="text" required   maxlength="100" name="contact_person_name" {{$validate->name}} class="form-control" >
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-4">Email id <span class="required">*
                                                </span></label>
                                            <div class="col-md-7">
                                                <input type="email" required=""  id="f_email" maxlength="250" name="email" class="form-control" >
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Mobile no<span class="required">*
                                                </span></label>
                                            <div class="col-md-7">
                                                <input type="text"  maxlength="12" required {{$validate->mobile}} name="mobile" class="form-control" >
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Address <span class="required">*
                                                </span></label>
                                            <div class="col-md-7">
                                                <textarea type="text" required name="address" maxlength="250" class="form-control"></textarea>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Bank account holder name <span class="required">*
                                                </span></label>
                                            <div class="col-md-7">
                                                <input type="text" required maxlength="100" name="account_holder_name" {{$validate->name_text}} class="form-control" >
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Account number <span class="required">*
                                                </span></label>
                                            <div class="col-md-7">
                                                <input type="text" required maxlength="20" name="account_number" pattern="[0-9]"  class="form-control" >
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Bank name <span class="required">*
                                                </span></label>
                                            <div class="col-md-7">
                                                <input type="text" required maxlength="45" name="bank_name" {{$validate->name_text}} class="form-control" >
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Bank account type <span class="required">*
                                                </span></label>
                                            <div class="col-md-7">
                                                <select class="form-control" required name="account_type">
                                                    <option  value="">Select account type</option>
                                                    <option value="Current">Current</option>
                                                    <option value="Saving">Saving</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">IFSC code <span class="required">*
                                                </span></label>
                                            <div class="col-md-7">
                                                <input type="text" required=""  maxlength="20" name="ifsc_code" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="loading" id="loader" style="display: none;">Loading&#8230;</div>
                                </div>
                                <!-- End profile details -->
                            </div>
                        </div>


                    </div>
                </div>

                <div class="modal-footer">
                    <input type="hidden" name="contact_person_name2" value="">
                    <input type="hidden" name="adhar_card" value="">
                    <input type="hidden" name="gst" value="">
                    <button type="button" class="btn default" id="closebuttonf" data-dismiss="modal">Close</button>
                    <button type="submit" onclick="return saveBeneFranchise();" class="btn blue">Save</button>
                </div>
            </div>

            <!-- /.modal-content -->
        </div>
    </form>	
    <!-- /.modal-dialog -->
</div>
@endsection