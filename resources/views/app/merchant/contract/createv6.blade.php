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

    .steps {
        background-color: transparent !important;
        border: 2px #18AEBF solid !important;
        color: #18AEBF !important;
        width: auto !important;
    }
</style>
@section('content')
    <div>
        <div class="page-content">
            <!-- BEGIN PAGE HEADER-->
            <div class="page-bar">
                <span class="page-title" style="float: left;">{{$title}}</span>
                {{ Breadcrumbs::render('home.contractcreate') }}
                <span class=" pull-right badge badge-pill status steps" style="padding: 6px 16px 6px 16px !important;">Step {{ $step }} of 3</span>
            </div>
            <!-- END PAGE HEADER-->
            <!-- BEGIN PAGE CONTENT-->
            <div class="row">
                <div class="col-md-12">
{{--                    @include('layouts.alerts')--}}

                    <div id="perror" style="display: none;" class="alert alert-block alert-danger fade in">
                        <p>Error! Select a project before trying to add a new row
                        </p>
                    </div>
                    <div id="paticulars_error" style="display: none;" class="alert alert-block alert-danger fade in">
                        <p>Error! Before proceeding, please verify the details.. <br> 'Bill code', 'Bill Type', 'Original Contract Amount' are mandatory fields !
                        </p>
                    </div>
                    <form action="{{ route('contract.store') }}" id="frm_expense" onsubmit="loader();" method="post" enctype="multipart/form-data" class="form-horizontal form-row-sepe">
                        @csrf
                        <input type="hidden" name="step" value="{{ $step }}"/>
                        <input type="hidden" name="version" value="v6"/>
                        <input type="hidden" name="contract_id" id="contract_id" value="{{ \App\Libraries\Encrypt::encode($contract_id) }}">
                        <input type="hidden" name="merchant_id" id="merchant_id" value="{{ $merchant_id }}">
                        @switch($step)
                            @case(1)
                                @include('app.merchant.contract.steps.step-1')
                            @break
                        @endswitch
                    </form>

                </div>
                <!-- END PAGE CONTENT-->
            </div>
        </div>
    </div>
    <script>
        {{--mode = '{{$mode}}';--}}
    </script>
    </div>
{{--    @include('app.merchant.contract.add-bill-code-modal')--}}
@endsection