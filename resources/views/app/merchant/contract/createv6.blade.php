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

    .panel-wrap {
        position: fixed;
        top: 0;
        bottom: 0;
        right: 0;
        left: 50% !important;
        /* width: 80em; */
        transform: translateX(100%);
        transition: .3s ease-out;
        z-index: 100;
    }

    .panel {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        background: #fff;
        color: #394242;
        overflow-y: scroll;
        overflow-x: hidden;
        padding: 1em;
        box-shadow: 0 5px 15px rgb(0 0 0 / 50%);
        margin-bottom: 0;
    }

    .remove {
        padding: 4px 3px;
        cursor: pointer;
        float: left;
        position: relative;
        top: 0px;
        color: #fff;
        right: 25px;
        z-index: 99999;
    }

    .remove:hover {
        color: #FFF;
    }

    .remove i {
        font-size: 19px !important;
    }

    .subscription-info i {
        font-size: 22px !important;
    }

    .cust-head {
        text-align: left !important;
    }

    .subscription-info h3 {
        text-align: center;
        color: #000;
        margin-bottom: 2px !important;
    }

    .subscription-info h2 {
        font-weight: 600;
        margin-bottom: 0 !important;
        margin-top: 5px !important;
        text-align: center;
    }

    .td-head {
        font-size: 19px;
    }

    @media (max-width: 767px) {
        .cust-head {
            text-align: center !important;
        }

        .panel-wrap {
            /* width: 23em; */
            top: 0;
            bottom: 0;
            right: 0;
            left: 0;
            position: fixed;
        }
    }

    @media (min-width: 768px) and (max-width: 991px) {
        .panel-wrap {
            /* width: 47em; */
            position: fixed;
            right: 0;
        }
    }

    @media (min-width: 992px) and (max-width: 1199px) {
        .panel-wrap {
            /* width: 47em; */
            position: fixed;
            right: 0;
        }
    }

    .right-value {
        text-align: right;
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
                        <input type="hidden" name="contract_id" id="contract_id" value="{{ $contract_id }}">
                        <input type="hidden" name="merchant_id" id="merchant_id" value="{{ $merchant_id }}">
                        <input type="hidden" name="project_id" id="project_id" value="{{ $project_id??null }}">
                        <input type="hidden" name="title" id="title" value="{{ $title }}">

                        @switch($step)
                            @case(1)
                                @include('app.merchant.contract.steps.step-1')
                            @break
                            @case(2)
                                @include('app.merchant.contract.steps.step-2')
                            @break
                            @case(3)
                                @include('app.merchant.contract.steps.step-3')
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

@endsection