@extends('app.master')

@section('content')
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">  
        <span class="page-title" style="float: left;">{{$title}}</span>
        {{ Breadcrumbs::render() }}
    </div>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">
            @if(!empty($errors))
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert"></button>
                <strong>Error!</strong>
                <div class="media">
                    @foreach ($errors as $v)
                    <p class="media-heading">{{$v}}</p>
                    @endforeach
                </div>
            </div>
            @endif
            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <!--<h3 class="form-section">Profile details</h3>-->
                    <form action="/merchant/creditnote/save" id="frm_expense" onsubmit="loader();"  method="post" enctype="multipart/form-data" class="form-horizontal form-row-sepe">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-body">
                            <!-- Start profile details -->
                            <div class="row">
                                <div class="col-md-6">
                                    <h3 class="form-section">
                                        Debit note information
                                    </h3>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Debit note number <span class="required">*
                                            </span></label>
                                        <div class="col-md-8">
                                            <div class="input-group">
                                                <input id="expense_auto_generate" name="credit_note_no" type="text" @if($debit_note_auto_generate==1) readonly="" value="Auto generate" @endif   class="form-control">
                                                       <span class="input-group-btn">
                                                    <a data-toggle="modal" href="#customer" class="btn btn-icon-only green"><i class="icon-settings"> </i></a>
                                                </span>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Debit note date<span class="required">*
                                            </span></label>
                                        <div class="col-md-8">
                                            <input class="form-control form-control-inline date-picker" type="text" required   name="credit_note_date"  autocomplete="off" data-date-format="{{ Session::get('default_date_format')}}"  placeholder="Debit note date"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Select Vendor <span class="required">*
                                            </span></label>
                                        <div class="col-md-8">
                                            <select class="form-control select2me" onchange="setVendorState(this.value);" data-placeholder="Select vendor" required="" name="vendor_id" >
                                                <option value="">Select vendor</option>
                                                @foreach($vendors as $v)
                                                <option value="{{$v->vendor_id}}">{{$v->vendor_name}} - {{$v->state}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <h3 class="form-section">
                                        Invoice information
                                    </h3>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Invoice number <span class="required">*
                                            </span></label>
                                        <div class="col-md-8">
                                            <input type="text"  required=""  maxlength="45"  name="invoice_no"  class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Bill date<span class="required">*
                                            </span></label>
                                        <div class="col-md-8">
                                            <input class="form-control form-control-inline date-picker" type="text" required   name="bill_date"  autocomplete="off" data-date-format="{{ Session::get('default_date_format')}}"  placeholder="Bill date"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Due date<span class="required">*
                                            </span></label>
                                        <div class="col-md-8">
                                            <input class="form-control form-control-inline date-picker" type="text" required   name="due_date"  autocomplete="off" data-date-format="{{ Session::get('default_date_format')}}"  placeholder="Due date"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Upload file<span class="required">
                                            </span>
                                        </label>
                                        <div class="col-md-8">
                                            <input type="file" accept="image/*,application/pdf" onchange="return validatefilesize(3000000, 'expense');" id="expense" name="file">
                                            <span class="help-block red">* Max file size 3 MB</span>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>

                        <h3 class="form-section">Add particulars
                            <a href="javascript:;" onclick="AddExpenseParticular();" class="btn btn-sm green pull-right"> <i class="fa fa-plus"> </i> Add new row </a></h3>
                        <div class="table-scrollable">
                            <table class="table table-bordered table-hover" id="particular_table">
                                <thead>
                                    <tr>
                                        <th class="td-c">
                                            <label class="control-label">Particular <span class="required">*</span></label>
                                        </th>
                                        <th class="td-c">
                                            <label class="control-label">SAC/HSN Code <span class="required"></span></label>
                                        </th>
                                        <th class="td-c">
                                            <label class="control-label">Unit <span class="required">*</span></label>

                                        </th>
                                        <th class="td-c"> 
                                            <label class="control-label">Rate <span class="required">*</span></label>
                                        </th>
                                        <th class="td-c"> 
                                            <label class="control-label">Tax <span class="required"></span></label>
                                        </th>
                                        <th class="td-c">
                                            <label class="control-label">Total <span class="required"></span></label>
                                        </th>
                                        <th class="td-c">
                                            <label class="control-label">Action <span class="required"></span></label>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="new_particular">
                                    <tr><td>
                                            <input type="text" required="" name="particular[]" class="form-control " placeholder="Particular"></td><td><input  type="text" name="sac[]" class="form-control " placeholder="SAC/HSN Code"></td><td><input required type="number" step="1" name="unit[]" onblur="calculateexpensecost();" class="form-control " placeholder="Unit"></td><td>
                                            <input type="number" required step="0.01" name="rate[]" onblur="calculateexpensecost();" class="form-control " placeholder="Rate"></td><td>
                                            <select class="form-control " onchange="calculateexpensecost();" name="tax[]">
                                                <option value="0">Select tax</option>
                                                <option value="1">Non Taxable</option>
                                                <option value="2">GST @0%</option>
                                                <option value="3">GST @5%</option>
                                                <option value="4">GST @12%</option>
                                                <option value="5">GST @18%</option>
                                                <option value="6">GST @28%</option>
                                            </select>
                                        </td><td><input type="text" name="total[]" class="form-control " readonly=""><input type="hidden" name="particular_id[]" value="Na"></td><td></td></tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" style="border-bottom: none;"></td>
                                        <td colspan="2"><label>Sub Total</label></td>
                                        <td colspan="2"><input type="text" class="form-control" readonly="" id="sub_total" name="sub_total" value="0.00"></td>
                                    </tr>

                                    <tr id="cgst" style="display: none;">
                                        <td colspan="3" style="border: none;"></td>
                                        <td colspan="2"><label>CGST</label></td>
                                        <td colspan="2"><input type="text" class="form-control" readonly="" id="cgst_amt" name="cgst_amt" value="0.00"></td>
                                    </tr>
                                    <tr id="sgst" style="display: none;">
                                        <td colspan="3" style="border: none;"></td>
                                        <td colspan="2"><label>SGST</label></td>
                                        <td colspan="2"><input type="text" class="form-control" readonly="" id="sgst_amt" name="sgst_amt" value="0.00"></td>
                                    </tr>
                                    <tr id="igst" style="display: none;">
                                        <td colspan="3" style="border: none;"></td>
                                        <td colspan="2"><label>IGST</label></td>
                                        <td colspan="2"><input type="text" class="form-control" readonly="" id="igst_amt" name="igst_amt" value="0.00"></td>
                                    </tr>



                                    <tr>
                                        <td colspan="3"  style="border: none;"></td>
                                        <td colspan="2"><label>Total</label></td>
                                        <td colspan="2"><input type="text" class="form-control" readonly id="total" name="total" value="0.00">
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-6">

                                    <div class="form-group">
                                        <label class="control-label col-md-4">Narrative<span class="required">
                                            </span></label>
                                        <div class="col-md-8">
                                            <textarea name="narrative" class="form-control"></textarea>
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
                                        <a href="/merchant/debitnote/viewlist" class="btn default">Cancel</a>
                                        <input type="hidden" id="gst_type" name="gst_type">
                                        <input type="hidden" value="2" name="type">
                                        <input type="submit" value="Save" class="btn blue"/>
                                    </div>
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

<div class="modal fade" id="customer" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="submit_form" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" id="closeauto" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Debit note number</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-body">
                                <div class="alert alert-danger display-hide">
                                    <button class="close" data-close="alert"></button>
                                    You have some form errors. Please check below.
                                </div>

                                <div class="form-group">
                                    <h5>Your debit note number is on auto-generate mode to save your time. Are you sure about changing this setting?</h5>

                                </div>

                                <div class="form-group" >
                                    <label for="autogen" class="col-md-12 control-label">
                                        <input type="radio" id="autogen" name="auto_generate" value="1" @if($debit_note_auto_generate==1) checked="" @endif> Continue auto-generating numbers
                                    </label>
                                    <div class="col-md-8">
                                        <div class="col-md-7">
                                            <div class="form-group">
                                                <p>Prefix</p>
                                                <input type="text" name="prefix" id="prefix" maxlength="10" value="{{$prefix}}" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <p>Number</p>
                                                <input type="text" name="prefix_val" value="{{$prefix_val}}" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group" >
                                    <label for="auto2" class="col-md-8 control-label">
                                        <input type="radio" class="icheck" id="auto2"  name="auto_generate" @if($debit_note_auto_generate!=1) checked="" @endif value="0"/> I will add them manually each time
                                    </label>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="seq_id" name="seq_id" value="{{$prefix_id}}">
                    <button type="button" onclick="return saveExpenseSequence(6);" class="btn blue">Save</button>
                    <button type="button" class="btn default" id="closebutton" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div><!-- BEGIN FOOTER -->



@endsection

