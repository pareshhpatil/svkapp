@extends('app.master')

@section('content')
<style>
    .lable-heading {
        font-family: 'Rubik';
        font-style: normal;
        font-weight: 400;
        font-size: 14px;
        line-height: 24px;
        color: #767676;
        margin-bottom: 0px;
        margin-top: 5px;
    }

    .col-id-no {
        position: sticky !important;
        left: 0;
        z-index: 2;
        border-right: 2px solid #D9DEDE !important;
        background-color: #fff;
    }
</style>
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">
        <span class="page-title" style="float: left;">{{$title}}</span>
        {{ Breadcrumbs::render('home.contractupdate') }}
    </div>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">
            @include('layouts.alerts')

            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <!--<h3 class="form-section">Profile details</h3>-->
                    <form action="/merchant/contract/updatesave" id="frm_expense" onsubmit="loader();" method="post" enctype="multipart/form-data" class="form-horizontal form-row-sepe">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-body">
                            <!-- Start profile details -->
                            <div class="row">
                                <div class="col-md-6">
                                    <h3 class="form-section">
                                        Contract information
                                    </h3>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Select project <span class="required">*
                                            </span></label>
                                        <div class="col-md-8">
                                            <select class="form-control select2me" onchange="projectSelected(this.value)" data-placeholder="Select project" required name="project_id" id="project_id">
                                                <option value="">Select project</option>
                                                @foreach($project_list as $v)
                                                <option @if($detail->project_id == $v->id) selected @endif value="{{$v->id}}">{{$v->project_name}} | {{$v->project_id}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Contract number <span class="required">*
                                            </span></label>
                                        <div class="col-md-8">
                                            <input type="text" maxlength="45" required data-cy="contract_no" name="contract_no" class="form-control" data-cy="invoice_no" value="{{$detail->contract_code }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Billing frequency<span class="required">
                                            </span></label>
                                        <div class="col-md-8">
                                            <select class="form-control " name="billing_frequency">
                                                <option @if($detail->billing_frequency == 1) selected @endif value="1">Weekly</option>
                                                <option @if($detail->billing_frequency == 2) selected @endif value="2">Monthly</option>
                                                <option @if($detail->billing_frequency == 3) selected @endif value="3">Quarterly</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Contract date<span class="required">*
                                            </span></label>
                                        <div class="col-md-8">
                                            <input class="form-control form-control-inline date-picker" type="text" required data-cy="contract_date" name="contract_date" autocomplete="off" data-date-format="{{ Session::get('default_date_format')}}" placeholder="Contract date" value='@if(isset($detail->contract_date))<x-localize :date="$detail->contract_date" type="date" />@endif' />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">First billing date<span class="required">*
                                            </span></label>
                                        <div class="col-md-8">
                                            <input class="form-control form-control-inline date-picker" data-cy="bill_date" type="text" required name="bill_date" autocomplete="off" data-date-format="{{ Session::get('default_date_format')}}" placeholder="Bill date" value='@if(isset($detail->bill_date))<x-localize :date="$detail->bill_date" type="date" />@endif' />
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <h3 class="form-section">
                                        Contract information
                                    </h3>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Project name</label>
                                        <div class="col-md-8">
                                            <div class="input-icon right">
                                                <input type="text" id="project_name" name="project_name" readonly class="form-control cust_det">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Company name</label>
                                        <div class="col-md-8">
                                            <div class="input-icon right">
                                                <input type="text" id="customer_code" name="customer_code" readonly class="form-control cust_det">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Customer name</label>
                                        <div class="col-md-8">
                                            <div class="input-icon right">
                                                <input type="text" id="customer_name" name="customer_name" readonly class="form-control cust_det">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Email</label>
                                        <div class="col-md-8">
                                            <div class="input-icon right">
                                                <input type="text" id="customer_email" name="customer_email" readonly class="form-control cust_det">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Mobile number</label>
                                        <div class="col-md-8">
                                            <div class="input-icon right">
                                                <input type="text" id="customer_number" name="customer_number" readonly class="form-control cust_det">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                        <h3 class="form-section">Add particulars</h3>
                            </div>
                            <div class="col-md-6">
                            <a data-cy="add_particulars_btn" href="javascript:;" onclick="AddInvoiceParticularRowContract();" class="btn green pull-right mb-1"> Add new row </a>
                            </div></div>
                        <div class="table-scrollable">
                            <table class="table table-bordered table-hover" id="particular_table">

                                <thead>
                                    <tr>
                                        <th class="td-c col-id-no">
                                            Bill Code
                                        </th>
                                        <th class="td-c" style="min-width: 150px;">
                                            Bill Type
                                        </th>
                                        <th class="td-c">
                                            Original Contract Amount
                                        </th>
                                        <th class="td-c">
                                            Retainage %
                                        </th>
                                        <th class="td-c">
                                            Retainage Amount
                                        </th>
                                        <th class="td-c">
                                            Project Id
                                        </th>
                                        <th class="td-c">
                                            Cost Code
                                        </th>
                                        <th class="td-c">
                                            Cost Type
                                        </th>
                                        <th class="td-c">
                                            Group Code 1
                                        </th>
                                        <th class="td-c">
                                            Group Code 2
                                        </th>
                                        <th class="td-c">
                                            Group Code 3
                                        </th>
                                        <th class="td-c">
                                            Group Code 4
                                        </th>
                                        <th class="td-c">
                                            Group Code 5
                                        </th>
                                        <th class="td-c">

                                        </th>
                                    </tr>
                                </thead>


                                <tbody id="new_particular">
                                    @foreach($detail->json_particulars as $key=>$row)
                                    @php
                                    $is_calculated = false;
                                    @endphp
                                    <tr>
                                        @foreach($default_particulars as $v=>$r)
                                        @if($v == 'retainage_amount')
                                        <td>
                                            <input numbercom="yes" onkeyup="updateTextView($(this));" type="text" data-cy="particular_{{$v}}{{$key+1}}" class="form-control pc-input" id="{{$v}}{{$key+1}}" readonly="" name="{{$v}}[]" />
                                        </td>
                                        @elseif ($v == 'bill_type')
                                        <td>
                                            @if($row[$v]=='Calculated' ) @php $is_calculated = true; @endphp @endif
                                            <select required style="width: 100%; min-width: 200px;" data-cy="particular_bill_type{{$key+1}}" id="bill_type{{$key+1}}" name="bill_type[]" data-placeholder="Type or Select Bill Type" class="form-control ">
                                                <option @if($row[$v]==0) selected @endif value="0">Select Type</option>
                                                <option @if($row[$v]=='% Complete' ) selected @endif value="% Complete">% Complete</option>
                                                <option @if($row[$v]=='Unit' ) selected @endif value="Unit">Unit</option>
                                                <option @if($row[$v]=='Calculated' ) selected @endif value="Calculated">Calculated</option>
                                            </select>
                                        </td>
                                        @elseif ($v == 'original_contract_amount')
                                        <td class="td-r">
                                            <input numbercom="yes" onkeyup="updateTextView($(this));" type="text" onblur="calculateRetainage();" 
                                            data-cy="particular_{{$v}}{{$key+1}}" class="form-control pc-input" value="{{number_format($row[$v],2)}}" 
                                            id="{{$v}}{{$key+1}}" name="{{$v}}[]"  @if($is_calculated == true) readonly @endif/>
                                            @if($is_calculated == true)
                                            <a id="add-calc{{$key+1}}" style="display:none; padding-top:5px;" href="javascript:;" onclick="OpenAddCaculatedRow('{{$key+1}}')">Add calculation</a>
                                            <a id="remove-calc{{$key+1}}" style="display:inline-block;padding-top:5px;" href="javascript:;" onclick="RemoveCaculatedRow('{{$key+1}}')">Remove</a>
                                            <span id="pipe-calc{{$key+1}}" style="display:inline-block;margin-left: 0px;color:#859494" > | </span>
                                            <a id="edit-calc{{$key+1}}" style="display:inline-block;padding-top:5px;" href="javascript:;" onclick="editCaculatedRow('{{$key+1}}')">Edit</a>
                                            @endif
                                        </td>
                                        @elseif ($v == 'retainage_percent')
                                        <td>
                                            <input numbercom="yes" onkeyup="updateTextView($(this));" type="text" onblur="calculateRetainage();" data-cy="particular_{{$v}}{{$key+1}}" class="form-control pc-input" value="{{number_format($row[$v],2)}}" id="{{$v}}{{$key+1}}" name="{{$v}}[]" />
                                        </td>
                                        @elseif ($v == 'bill_code')
                                        <td class="col-id-no">
                                            <div>
                                                <select required style="width: 100%; min-width: 200px;" onchange="billCode(this.value, '{{$key+1}}');" data-cy="particular_item{{$key+1}}" id="bill_code{{$key+1}}" name="bill_code[]" data-placeholder="Type or Select" class="form-control productselect">
                                                    <option value="">Select Code</option>
                                                    @if(!empty($csi_code))
                                                    @foreach($csi_code as $pk=>$vk)
                                                    @if($row[$v]==$vk->code)
                                                    <option selected="" value="{{$vk->code}}">{{$vk->title}} | {{$vk->code}}</option>
                                                    @else
                                                    <option value="{{$vk->code}}">{{$vk->title}} | {{$vk->code}}</option>
                                                    @endif
                                                    @endforeach
                                                    @endif
                                                </select>
                                                <div class="text-center">
                                                    <p id="description{{$key+1}}" class="lable-heading">
                                                        {{$row['description']}}
                                                    </p>
                                                </div>
                                                <script>
                                                    billCode('{{$vk->code}}', '{{$key+1}}')
                                                </script>
                                            </div>
                                        </td>
                                        @else
                                        <td>
                                            <input type="text" data-cy="particular_{{$v}}{{$key+1}}" class="form-control pc-input" value="{{$row[$v]}}" id="{{$v}}{{$key+1}}" name="{{$v}}[]" />
                                        </td>
                                        @endif
                                        @endforeach
                                        <input type="hidden" value="{{$row['calculated_perc']}}" id="calculated_perc{{$key+1}}" name="calculated_perc[]">
                                        <input type="hidden" value="{{$row['calculated_row']}}" id="calculated_row{{$key+1}}" name="calculated_row[]">
                                        <input type="hidden" id="description-hidden{{$key+1}}" name="description[]" value="{{$row['description']}}">
                                        <input type="hidden" id="pint{{$key+1}}" name="pint[]" value="{{$key+1}}">
                                        <input type="hidden" name="product_gst[]" value="" data-cy="product_gst{{$key+1}}">
                                        <input type="hidden" name="particular_id[]" value="0">
                                        <td class="td-c">
                                            <a data-cy="particular-remove{{$key+1}}" href="javascript:;" onclick="$(this).closest('tr').remove();calculateRetainage();" class="btn btn-sm red"> <i class="fa fa-times"> </i> </a>
                                        </td>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="warning">
                                        <th class="td-c col-id-no"></th>
                                        <th>Grand total</th>
                                        <th class="td-c">
                                            <input type="text" id="particulartotal1" data-cy="particular-total1" name="totalcost" value="{{$detail->contract_amount}}" class="form-control " readonly="">
                                        </th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>

                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <br>
                        <!-- End profile details -->
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="pull-right">
                                        <input type="hidden" value="" id="contract_amount" name="contract_amount">
                                        <input type="hidden" value="{{$link}}" name="link">
                                        <a href="/merchant/contract/list" class="btn default">Cancel</a>
                                        <input type="submit" value="Update" class="btn blue" data-cy="contract_save" />
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

@include('app.merchant.contract.add-calculation-modal')
@include('app.merchant.contract.add-bill-code-modal')
@endsection
<script>
    mode = '{{$mode}}';
    // @if(isset($csi_code))
    // csi_codes = {!!$csi_code_json!!};
    // @endif
</script>

@section('footer')
<script>
    project_id = '{{$detail->project_id}}'
    projectSelected(project_id)
    calculateRetainage()
    $('.productselect').select2({
        tags: true,
        insertTag: function(data, tag) {
            var $found = false;
            $.each(data, function(index, value) {
                if ($.trim(tag.text).toUpperCase() == $.trim(value.text).toUpperCase()) {
                    $found = true;
                }
            });
            if (!$found) data.unshift(tag);
        }
    }).on('select2:open', function(e) {
        pind = $(this).index();
        var index = $(".productselect").index(this);
        index+=1;
        if (document.getElementById('prolist' + pind)) {} else {
            $('.select2-results').append('<div class="wrapper" id="prolist' + pind + '" > <a class="clicker" onclick="billIndex(' + index + ',' + index + ',0);">Add new bill code</a> </div>');
        }
    });
</script>
@endsection