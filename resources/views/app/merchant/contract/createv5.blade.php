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

        {{--<div class="page-content">
            <!-- BEGIN PAGE HEADER-->
            <div class="page-bar">
                <span class="page-title" style="float: left;">{{$title}}</span>
                {{ Breadcrumbs::render('home.contractcreate') }}
                <span class=" pull-right badge badge-pill status steps" style="padding: 6px 16px 6px 16px !important;">Step <span x-text="step"></span> of 3</span>
            </div>
            <!-- END PAGE HEADER-->
            <!-- BEGIN PAGE CONTENT-->
            <div class="row">--}}
                @livewire('contract.create', ['contract_id' => $contract_id, 'title' => $title])
                <!-- END PAGE CONTENT-->
            {{--</div>
        </div>--}}
        <!-- END CONTENT -->

    </div>
    <script>
        mode = '{{$mode}}';
    </script>
    </div>
    @include('app.merchant.contract.add-bill-code-modal')
@endsection