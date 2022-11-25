@extends('app.master')
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
                                <livewire:contract.information/>
                            </div>

                            <livewire:contract.particulars/>
{{--                            @include('app.merchant.contract.particular')--}}

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