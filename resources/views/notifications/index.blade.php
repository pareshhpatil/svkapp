@extends('app.master')

@section('header')
@endsection

@section('content')
<div class="page-content">

    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">
        <span class="page-title" style="float: left;">{{$title}}</span>
        <!-- {{ Breadcrumbs::render('home.gstr2a') }} -->
    </div>
    <!-- END PAGE HEADER-->
    <div class="alert alert-danger" style="display:none; margin-left: 0px !important; margin-right:0px !important" id="conn_error">
        Please validate your connection with the GST portal via OTP
    </div>
    <div class="alert alert-danger" style="display:none; margin-left: 0px !important; margin-right:0px !important" id="month_error">
        Month difference cannot be more than 12 months
    </div>
    <div class="alert alert-danger" style="display:none; margin-left: 0px !important; margin-right:0px !important" id="expense_error">
        No expense records found. Please upload expense data.
    </div>
    <!-- BEGIN SEARCH CONTENT-->
    <style>
        .timeline {
            margin: 0;
            padding: 0;
            position: relative;
            margin-bottom: 30px;
        }

        .pb0 {
            padding-bottom: 0 !important;
        }

        .timeline:before {
            content: '';
            position: absolute;
            display: block;
            width: 4px;
            background: #f5f6fa;
            top: 0px;
            bottom: 0px;
            margin-left: 38px;
        }

        .ptitle {
            font-size: 1.1rem !important;
        }

        .timeline-item {
            margin: 0;
            padding: 0;
        }

        .timeline-badge {
            float: left;
            position: relative;
            padding-right: 30px;
            height: 80px;
            width: 80px;
        }

        .timeline-badge-userpic {
            width: 80px;
            border: 4px #f5f6fa solid;
            -webkit-border-radius: 50% !important;
            -moz-border-radius: 50% !important;
            border-radius: 50% !important;
        }

        .timeline-badge-userpic img {
            -webkit-border-radius: 50% !important;
            -moz-border-radius: 50% !important;
            border-radius: 50% !important;
            vertical-align: middle !important;
        }

        .timeline-icon {
            width: 80px;
            height: 80px;
            background-color: #f5f6fa;
            -webkit-border-radius: 50% !important;
            -moz-border-radius: 50% !important;
            border-radius: 50% !important;
            padding-top: 30px;
            padding-left: 22px;
        }

        .timeline-icon i {
            font-size: 34px;
        }

        .timeline-body {
            position: relative;
            padding: 20px;
            margin-top: 20px;
            margin-left: 110px;
            background-color: #f5f6fa;
            -webkit-border-radius: 4px;
            -moz-border-radius: 4px;
            -ms-border-radius: 4px;
            -o-border-radius: 4px;
            border-radius: 4px;
        }

        .timeline-body:before,
        .timeline-body:after {
            content: " ";
            display: table;
        }

        .timeline-body:after {
            clear: both;
        }

        .timeline-body-arrow {
            position: absolute;
            top: 30px;
            left: -14px;
            width: 0;
            height: 0;
            border-style: solid;
            border-width: 14px 14px 14px 0;
            border-color: transparent #f5f6fa transparent transparent;
        }

        .timeline-body-head {
            margin-bottom: 10px;
        }

        .timeline-body-head-caption {
            float: left;
        }

        .timeline-body-title {
            font-size: 16px;
            font-weight: 600;
        }

        .timeline-body-alerttitle {
            font-size: 16px;
            font-weight: 600;
        }

        .timeline-body-time {
            font-size: 14px;
            margin-left: 10px;
        }

        .timeline-body-head-actions {
            float: right;
        }

        .timeline-body-head-actions .btn-group {
            margin-top: -2px;
        }

        .timeline-body-content {
            font-size: 14px;
            margin-top: 35px;
        }

        .timeline-body-img {
            width: 100px;
            height: 100px;
            margin: 5px 20px 0 0px;
        }

        .page-container-bg-solid .timeline:before {
            background: #fff;
        }

        .page-container-bg-solid .timeline-badge-userpic {
            border-color: #fff;
        }

        .page-container-bg-solid .timeline-icon {
            background-color: #fff;
        }

        .page-container-bg-solid .timeline-body {
            background-color: #fff;
        }

        .page-container-bg-solid .timeline-body-arrow {
            border-color: transparent #fff transparent transparent;
        }



        @media (max-width: 768px) {
            .timeline-body-head-caption {
                width: 100%;
            }

            .timeline-body-head-actions {
                float: left;
                width: 100%;
                margin-top: 20px;
                margin-bottom: 20px;
            }
        }

        @media (max-width: 480px) {
            .timeline:before {
                margin-left: 28px;
            }

            .timeline-badge {
                padding-right: 40px;
                width: 60px;
                height: 60px;
            }

            .timeline-badge-userpic {
                width: 60px;
            }

            .timeline-icon {
                width: 60px;
                height: 60px;
                padding-top: 23px;
                padding-left: 18px;
            }

            .timeline-icon i {
                font-size: 25px;
            }

            .timeline-body {
                margin-left: 80px;
            }

            .timeline-body-arrow {
                top: 17px;
            }


        }
    </style>

        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingOne">
                    <div class="panel-title">
                        <div class="caption">
                            <span class="caption-subject bold uppercase">
                            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                Invoice #12122022</a>
                            </span>
                            <span class="caption caption-helper pull-right">Dec 7 2022 at 7:56 PM</span>
                        </div>
                    </div>
                </div>
                <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                    <div class="panel-body">
                        <div class="portlet-body pb0">
                            <p class="caption caption-helper ptitle">
                                <strong><a href="">Paresh</a> </strong> <span style="width:8%; border-radius:0px !important;" class="badge badge-pill status refunded">CREATED INVOICE</span>
                                <span class="pull-right">Dec 7 2022 at 7:56 PM</span>
                            </p>
                            <hr>
                            <p class="caption caption-helper ptitle">
                                <strong><a href="">Abhijeet</a></strong> <span style="width:8%; border-radius:0px !important;" class="badge badge-pill status deleted">UPDATED INVOICE</span>
                                <span class="pull-right">Dec 7 2022 at 7:56 PM</span>
                            </p>
                            <hr>

                            <p class="caption caption-helper ptitle">
                                <strong><a href="">Nitish</a></strong> <span style="width:8%; border-radius:0px !important;" class="badge badge-pill status deleted">UPDATED INVOICE</span>
                                <span class="pull-right">Dec 7 2022 at 7:56 PM</span>
                            </p>
                            <hr>
                            <p class="caption caption-helper ptitle">
                                Invoice waiting for Approval <a href="#" target="_BLANK">
                                    <span class="label label-sm label-warning ">Approve now
                                        <i class="fa fa-share"></i>
                                    </span>
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingTwo">
                    <div class="panel-title">
                        <div class="caption">
                            <span class="caption-subject bold uppercase">
                            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                           Invoice #12122022</a>
                            </span>
                            <span class="caption caption-helper pull-right">Dec 7 2022 at 7:56 PM</span>
                        </div>
                    </div>
                </div>
                <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                    <div class="panel-body">
                        <div class="portlet-body pb0">
                            <p class="caption caption-helper ptitle">
                                <strong><a href="">Paresh</a> </strong> <span style="width:8% ; border-radius:0px !important;" class="badge badge-pill status refunded">CREATED INVOICE</span>
                                <span class="pull-right">Dec 7 2022 at 7:56 PM</span>
                            </p>
                            <hr>
                            <p class="caption caption-helper ptitle">
                                <strong><a href="">Abhijeet</a></strong> <span style="width:8% ;border-radius:0px  !important;" class="badge badge-pill status deleted">UPDATED INVOICE</span>
                                <span class="pull-right">Dec 7 2022 at 7:56 PM</span>
                            </p>

                            <hr>
                            <p class="caption caption-helper ptitle">
                                <strong><a href="">Nitish</a></strong> <span style="width:8%; border-radius:0px  !important;" class="badge badge-pill status deleted">UPDATED INVOICE</span>
                                <span class="pull-right">Dec 7 2022 at 7:56 PM</span>
                            </p>
                            <hr>
                            <p class="caption caption-helper ptitle">
                                <strong><a href="">Shuhaid</a></strong>
                                <span style="width:8%; border-radius:0px !important;" class="badge badge-pill status paid_offline">CHANGED STATUS</span> <i class="fa fa-exchange"></i>
                                <span style="width:8%" class="badge badge-pill status draft">DRAFT</span> <i class="fa fa-arrow-circle-right"></i> <span style="width:8%" class="badge badge-pill status approved">APPROVED</span>
                                <span class="pull-right">Dec 7 2022 at 7:56 PM</span>
                            </p>
                            <hr>
                            <p class="caption caption-helper ptitle">
                                <span style="width:8%; border-radius:0px !important;" class="badge badge-pill status paid_offline">CHANGED STATUS</span> <i class="fa fa-exchange"></i> <span style="width:8%" class="badge badge-pill status approved">APPROVED</span> <i class="fa fa-arrow-circle-right"></i> <span style="width:8%" class="badge badge-pill status overdue">OVERDUE</span>

                                <span class="pull-right">Dec 7 2022 at 7:56 PM</span>
                            </p>
                            <hr>
                            <p class="caption caption-helper ptitle">
                                <strong><a href="">Darshana</a></strong> paid Online using <strong>ACES Direct Debit</strong>

                                <span class="pull-right">Dec 7 2022 at 7:56 PM</span>
                            </p>
                            <hr>
                            <p class="caption caption-helper ptitle">
                                <span style="width:8%; border-radius:0px !important;" class="badge badge-pill status paid_offline">CHANGED STATUS</span> <i class="fa fa-exchange"></i> <span style="width:8%" class="badge badge-pill status overdue">OVERDUE</span> <i class="fa fa-arrow-circle-right"></i> <span style="width:8%" class="badge badge-pill status processing">PROCESSING</span>

                                <span class="pull-right">Dec 7 2022 at 7:56 PM</span>
                            </p>
                            <hr>


                            <p class="caption caption-helper ptitle">
                                <span style="width:8%; border-radius:0px !important;" class="badge badge-pill status paid_offline">CHANGED STATUS</span> <i class="fa fa-exchange"></i> <span style="width:8%" class="badge badge-pill status processing">PROCESSING</span> <i class="fa fa-arrow-circle-right"></i> <span style="width:8%" class="badge badge-pill status paid_online">PAID ONLINE</span>

                                <span class="pull-right">Dec 7 2022 at 7:56 PM</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('footer')
@endsection