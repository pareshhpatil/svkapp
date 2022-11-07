@extends('app.master')
<link href="/assets/global/plugins/virtual-select/virtual-select.min.css" rel="stylesheet" type="text/css" />
<script src="/assets/global/plugins/virtual-select/virtual-select.min.js" type="text/javascript"></script>

<style>
    .lable-heading {
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
@section('content')
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">
        <span class="page-title" style="float: left;">{{$title}}</span>
        {{ Breadcrumbs::render('home.contractcreate') }}
    </div>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">
            @include('layouts.alerts')

            <div id="perror" style="display: none;" class="alert alert-block alert-danger fade in">
                <p>Error! Select a project before trying to add a new row
                </p>
            </div>
            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <!--<h3 class="form-section">Profile details</h3>-->
                    <form action="/merchant/contract/save" id="frm_expense" onsubmit="loader();" method="post" enctype="multipart/form-data" class="form-horizontal form-row-sepe">
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
                                            <select placeholder="Native Select"
  data-search="false" data-silent-initial-value-set="true" onchange="projectSelected(this.value);document.getElementById('perror').style.display = 'none';" data-placeholder="Select project" required name="project_id" id="project_id">
                                                @foreach($project_list as $v)
                                                <option value="{{$v->id}}">{{$v->project_name}} | {{$v->project_id}}</option>
                                                @endforeach
                                                <option value="1">aa</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Contract number <span class="required">
                                            </span></label>
                                        <div class="col-md-8">
                                            <input type="text" maxlength="45" data-cy="contract_no" name="contract_no" class="form-control" data-cy="invoice_no" value="{{ old('invoice_no') }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Billing frequency<span class="required">
                                            </span></label>
                                        <div class="col-md-8">
                                            <select class="form-control " name="billing_frequency">
                                                <option value="1">Weekly</option>
                                                <option selected value="2">Monthly</option>
                                                <option value="3">Quaterly</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Contract date<span class="required">*
                                            </span></label>
                                        <div class="col-md-8">
                                            <input class="form-control form-control-inline date-picker" type="text" required data-cy="contract_date" name="contract_date" autocomplete="off" data-date-format="dd M yyyy" placeholder="Contract date" value="{{ old('due_date') }}" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">First billing date<span class="required">*
                                            </span></label>
                                        <div class="col-md-8">
                                            <input class="form-control form-control-inline date-picker" data-cy="bill_date" type="text" required name="bill_date" autocomplete="off" data-date-format="dd M yyyy" placeholder="Bill date" value="{{ old('bill_date') }}" />
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <h3 class="form-section">
                                        Project information
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

                        @include('app.merchant.contract.particular')

                        <br>
                        <!-- End profile details -->
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="pull-right">
                                        <input type="hidden" value="0" id="contract_amount" name="contract_amount">
                                        <a href="/merchant/contract/list" class="btn default">Cancel</a>
                                        <input type="submit" value="{{$button}}" class="btn blue" data-cy="expense_dave" />
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
{{-- <script>
    AddInvoiceParticularRowContract();
</script> --}}
@endsection