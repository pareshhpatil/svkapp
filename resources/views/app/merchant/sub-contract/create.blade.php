@extends('app.master')
<style>
    .steps {
        background-color: transparent !important;
        border: 2px #18AEBF solid !important;
        color: #18AEBF !important;
        width: auto !important;
    }
</style>
@section('content')
    <div class="page-content">
        <div class="page-bar">
            <span class="page-title" style="float: left;">{{$title}}</span>
            {{ Breadcrumbs::render() }}
            <span class=" pull-right badge badge-pill status steps" style="padding: 6px 16px 6px 16px !important;">Step {{ $step }} of 2</span>
        </div>
        <!-- BEGIN SEARCH CONTENT-->
        <div class="row">
            @include('layouts.alerts')
            <div class="col-md-12">
                <!-- BEGIN PAYMENT TRANSACTION TABLE -->

                <form action="/merchant/sub-contracts/create" method="post" id="create_sub_contract_form" class="form-horizontal form-row-sepe">
                    {{ csrf_field() }}
                    <input type="hidden" name="step" value="{{ $step }}"/>
                    <input type="hidden" name="sub_contract_id" id="sub_contract_id" value="{{ $sub_contract_id }}">
                    <input type="hidden" name="sub_contract_id" id="project_id" value="{{ $project_id }}">
                    @switch($step)
                        @case(1)
                            @include('app.merchant.sub-contract.steps.step-1')
                            @break
                        @case(2)
                            @include('app.merchant.sub-contract.steps.step-2')
                            @break
                    @endswitch
                </form>

                <!-- END PAYMENT TRANSACTION TABLE -->
            </div>
        </div>
        <!-- END PAGE CONTENT-->
    </div>
    <!-- END CONTENT -->




@endsection