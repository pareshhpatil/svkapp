@extends('app.master')

<style>
    .customize-output-panel-wrap {
        position: fixed;
        top: 0;
        bottom: 0;
        right: 0;
        left: 50% !important;
        transform: translateX(100%);
        transition: .3s ease-out;
        z-index: 100;
    }

    .customize-output-panel {
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
    .custom_chk {
        justify-content: center;
        box-sizing: border-box;
        width: 24px;
        height: 24px;
        font-size: 10px;
        font-weight: 500;
        border-radius: 50%;
        background-color: rgb(241,243,244);
        color: rgb(128,134,139);
        margin-right: 8px;
        cursor: pointer;
        align-items: center;
        -webkit-box-pack: center;
        -webkit-box-align: center;
        display: -webkit-inline-box;
    }
    .active-day {
        color: white !important;
        background-color: rgb(26,115,232);
    }
    @media screen and (min-width: 0px) and (max-width: 700px) {
        .mobile {
            display: block !important;
        }

        .desk {
            display: none !important;

        }
    }

    @media screen and (min-width: 701px) {
        .mobile {
            display: none !important;
        }

        .desk {
            display: block !important;

        }

    }

    @media (max-width: 767px) {
        .customize-output-panel-wrap {
            top: 0;
            bottom: 0;
            right: 0;
            left: 0;
            position: fixed;
        }
    }

    @media (min-width: 768px) and (max-width: 991px) {
        .customize-output-panel-wrap {
            position: fixed;
            right: 0;
        }
    }

    @media (min-width: 992px) and (max-width: 1199px) {
        .customize-output-panel-wrap {
            position: fixed;
            right: 0;
        }
    }

    .control-label {
        color: #394242 !important;
    }

    .popovers {
        color: #859494;
        vertical-align: text-bottom;
    }
</style>
@section('content')
<script src="/assets/admin/layout/scripts/coveringnote.js" type="text/javascript"></script>

<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">
        <span class="page-title" style="float: left;">{{$title}}</span>
        {{ Breadcrumbs::render('settings.invoiceformat') }}
    </div>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div>
        <div class="col-md-12">
            <div class="portlet light bordered">
                <ul class="nav nav-tabs">
                    <li role="presentation" class="active"><a href="#tab1" data-toggle="tab" class="step" aria-expanded="true">Properties</a></li>
                    <li role="presentation"><a href="#tab2" data-toggle="tab" class="step" aria-expanded="true">Notifications</a></li>
                    <li role="presentation"><a href="#tab3" data-toggle="tab" class="step" aria-expanded="true">Invoice format</a></li>
                </ul>
                <div class="portlet-body">
                    @include('app.merchant.contract.steps.plugin-form')
                </div>

            </div>
            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="pull-right">
                                <a class="btn green" href="/merchant/profile/settings">Cancel</a>
                                <button class="btn blue" type="submit" onclick="submitForm()">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('app.merchant.contract.steps.plugin-modals')
        </div>
    </div>
</div>
@endsection
<script>
    function submitForm() {
        document.getElementById('plugin-form').submit();
    }
</script>