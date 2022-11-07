@extends('app.master')
<link rel="stylesheet" href="/assets/global/plugins/virtual-select/virtual-select.min.css">
<script src="/assets/global/plugins/virtual-select/virtual-select.min.js"></script>
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


                        <div class="form-wizard">
                            <div class="form-body">
                                <ul class="nav nav-pills nav-justified steps">
                                    <li>
                                        <a href="#tab1" data-toggle="tab" class="step">
                                            <span class="number">
                                                1 </span>
                                            <span class="desc">
                                                <i class="fa fa-check"></i> Project </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#tab2" data-toggle="tab" class="step">
                                            <span class="number">
                                                2 </span>
                                            <span class="desc">
                                                <i class="fa fa-check"></i> Particulars </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#tab3" data-toggle="tab" class="step active">
                                            <span class="number">
                                                3 </span>
                                            <span class="desc">
                                                <i class="fa fa-check"></i> Preview </span>
                                        </a>
                                    </li>

                                </ul>
                                <div id="bar" class="progress progress-striped" role="progressbar">
                                    <div class="progress-bar progress-bar-success">
                                    </div>
                                </div>
                                <div class="tab-content">
                                    <div class="alert alert-danger display-none">
                                        <button class="close" data-dismiss="alert"></button>
                                        You have some form errors. Please check below.
                                    </div>
                                    <div class="alert alert-success display-none">
                                        <button class="close" data-dismiss="alert"></button>
                                        Your form validation is successful!
                                    </div>


                                    <div class="tab-pane active" id="tab1">

                                        @livewire('contract.contract-information', ['project_id' => $project_id])
                                    </div>
                                    <div class="tab-pane" id="tab2">
                                        @include('app.merchant.contract.particular')
                                    </div>
                                    <div class="tab-pane" id="tab3">
                                        <h3 class="block">Provide your billing and credit card details</h3>

                                    </div>

                                </div>
                            </div>

                        </div>

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