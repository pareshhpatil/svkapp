@extends('app.master')

@section('content')
<div class="page-content">
    <div class="page-bar">  
        <span class="page-title" style="float: left;">{{$title}}</span>
        {{ Breadcrumbs::render() }}
    </div>
    <div class="loading" id="loader" style="display: none;">Loading&#8230;</div>

    @foreach($list as $v)
    <div class="row no-margin">
        <div class="col-md-2 hidden-xs">&nbsp;</div>
        <div class="col-md-8 apps-shadow mb-2" style="background-color: #ffffff;">
            <div class="apps-box">
                <div class="row no-margin">
                    <div class="col-xs-6">
                        <h2 class="mb-1" id="title_{{$v->id}}">{{$v->title}}
                        </h2>
                        <p class="apps-help pull-left">{{$v->category}}</p>
                    </div>
                    <div class="col-xs-6">
                        <h3 class="mb-1 text-right">
                            @if($v->offer_value>0)
                            @if($v->value_type=='Amount')
                            @if($v->currency=='inr')₹ @else $ @endif {{Helpers::moneyFormatIndia($v->offer_value)}}/-
                            @else
                            {{Helpers::moneyFormatIndia($v->offer_value)}} %
                            @endif
                            @endif
                        </h3>
                        <p class="apps-help text-right">{{$v->offer_value_text}}</p>
                    </div>
                </div>
                <div class="row no-margin">
                    <div class="col-md-3 apps-left-box">
                        <img class="img-responsive" src="/images/benefits/{{strtolower(str_replace(' ', '', $v->title))}}/{{$v->logo}}">
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-12 apps-description">
                                {{$v->short_description}}
                            </div>
                            <div class="col-md-12 apps-detailed-description" style="display: none;" id="{{$v->id}}-detail">
                                <div class="mb-1">
                                    <p class="apps-detailed-description-heading">Description</p>
                                    {!!$v->long_description!!}
                                </div>
                                <div class="mb-1">
                                    <p class="apps-detailed-description-heading">Offer</p>
                                    {!!$v->offer!!}
                                </div>
                                <p class="apps-detailed-description-heading">Application process</p>
                                {!!$v->application_process!!}
                            </div>
                        </div>
                        <div class="row">
                            @if(in_array($v->id, $merchant_tracking))
                            <button disabled class="btn  default pull-right mb-1 ml-2">Already availed</button>
                            @else
                            <a href="javascript:void(0);" class="btn blue pull-right mb-1 ml-2" data-toggle="modal" @if(($valid_plan==false && $v->type!='free') || ($v->type=='growth' && $growth_plan==false)) data-target="#basic" onclick="setLink('{{$v->type}}');" @else data-target="#apply" onclick="setBenefit('{{$v->id}}')" @endif>Apply</a>
                            @endif
                            <a href="javascript:void(0);" class="btn blue-outline pull-right mb-1" onclick="javascript:toggleFullDescription('{{$v->id}}');" id="{{$v->id}}-button">Learn
                                more</a>
                                <span class="pt-2 pull-right mb-1" style="color: #F99B36; padding: 7px 14px;">
                                @if($v->type=='startup')
                                Available for Startup & Growth plan
                                @elseif($v->type=='growth')
                                Available for Growth plan
                                @else
                                Available for ALL merchants
                                @endif
                                </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <div class="modal fade" id="basic" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0">
                <div class="modal-body" style="padding:3rem;">
                    <div class="row align-items-center">
                        <div class="col-md-6 mt-2">
                            <img class="img-responsive" src="/images/benefits/go-paid.svg">
                            <h3 class="text-center mt-1 uppercase">EXCLUSIVE BENEFITS FOR YOU</h3>
                        </div>
                        <div class="col-md-6">
                            <p class="apps-detailed-description-heading text-center uppercase mb-0">you are currently on @if($valid_plan==false)a free @else the startup @endif plan</p>
                            <h1 class="card-title text-center mt-0 mb-2">@if($valid_plan==false)Subscribe to a package @else Upgrade to Growth @endif</h1>
                            <ul class="list-unstyled mx-auto pt-3 pl-3 mb-1">
                                <li>
                                    <p>
                                        <i class="fa fa-check mr-1 text-primary"></i>Unlimited invoices and estimates
                                    </p>
                                </li>
                                <li>
                                    <p>
                                        <i class="fa fa-check mr-1 text-primary"></i>Send invoices on email and SMS
                                    </p>
                                </li>
                                <li>
                                    <p>
                                        <i class="fa fa-check mr-1 text-primary"></i>Collect online payments using invoices
                                    </p>
                                </li>
                                <li>
                                    <p>
                                        <i class="fa fa-check mr-1 text-primary"></i>Auto reminders to customers for unpaid
                                        invoices
                                    </p>
                                </li>
                                <li>
                                    <p>
                                        <i class="fa fa-check mr-1 text-primary"></i>Bulk upload invoices using excel sheets
                                    </p>
                                </li>
                                <li>
                                    <p>
                                        <i class="fa fa-check mr-1 text-primary"></i>Create recurring invoices with ease
                                    </p>
                                </li>
                            </ul>
                            <center><a class="btn btn-primary btn-lg text-white" id="package_link" href="/pricing">@if($valid_plan==false)Choose a package @else Upgrade package @endif</a></center>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <div class="modal fade" id="apply" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog ">
            <form action="/merchant/benefit/apply" onsubmit="showLoader();" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" id="benefit_id" name="benefit_id">
                <div class="modal-content border-0">
                    <div class="modal-body">
                        <div class="row align-items-center">
                            <div class="col-md-12 px-2 ">
                                <span class="modal-title modal-margin">Apply for benefit</span>
                                <p class="mt-2">
                                    Would you like to apply for the benefit offered by <span id="company-name"></span>
                                </p>
                                <div class="pull-right mt-1">
                                    <button type="submit" class="btn blue  text-white pull-right" href="/pricing">Yes, please</button>

                                    <a class="btn blue-outline  text-white pull-right mr-1" onclick="$('#apply').modal('hide');" href="javascript:void(0)">No, thank you</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

    </div>
    <div class="modal fade" id="success" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content border-0">
                <div class="modal-body">
                    <div class="row align-items-center">
                        <div class="col-md-12 px-2 ">
                            <span class="modal-title modal-margin">Benefits email sent</span>
                            <p class="mt-2">
                                @if(Session::has('success'))
                                {!!Session::get('success')!!}
                                @endif
                            </p>
                            <p>
                                At times, the email may land up in your Spam folder, please mark it as Not Spam to receive important emails from your Swipez account in your Inbox.
                            </p>
                            <div class="pull-right">
                                <a class="btn blue pull-right text-white" onclick="$('#success').modal('hide');" href="javascript:void(0)">Got it!</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<a data-toggle="modal" id="success_btn" data-target="#success"></a>

@endsection