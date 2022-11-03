@extends('app.master')

@section('content')
<div class="page-content">
    <div class="page-bar">  
        <span class="page-title" style="float: left;">{{$title}}</span>
        {{ Breadcrumbs::render() }}
    </div>
    <div class="loading" id="loader" style="display: none;">Loading&#8230;</div>

    <div class="row no-margin">
        <div class="col-md-2 hidden-xs">&nbsp;</div>
        <div class="col-md-8 apps-shadow mb-2" style="background-color: #ffffff;">
            <div class="apps-box">
                <div class="row no-margin">
                    <div class="col-xs-6">
                        <h2 class="mb-1" id="title_3">Stripe
                        </h2>
                        <p class="apps-help pull-left">Cloud computing</p>
                    </div>
                    <div class="col-xs-6">
                        <h3 class="mb-1 text-right">
                            $  5,000/-
                        </h3>
                        <p class="apps-help text-right">Your savings</p>
                    </div>
                </div>
                <div class="row no-margin">
                    <div class="col-md-3 apps-left-box">
                        <img class="img-responsive" src="/images/benefits/amazonwebservices/awsactivate.png">
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-12 apps-description">
                                Amazon Web Services offers inexpensive, reliable and scalable cloud computing services. $5000 credits for 2 years and business support worth $1,500
                            </div>
                            <div class="col-md-12 apps-detailed-description" style="display: none;" id="3-detail">
                                <div class="mb-1">
                                    <p class="apps-detailed-description-heading">Description</p>
                                    Amazon Web Services provides SMBs &amp; startups with low cost, easy to use infrastructure needed to scale and grow any size business. AWS Activate is a program with resources designed to help startups get started on AWS. Join some of the fastest-growing startups in the world and build your business using AWS.
                                </div>
                                <div class="mb-1">
                                    <p class="apps-detailed-description-heading">Offer</p>
                                    
                                </div>
                                <p class="apps-detailed-description-heading">Application process</p>
                                <li>Click on Apply</li>
                                <li>Once you apply, you will receive an email with instructions from Swipez support</li>
                                <li>If you haven't already created an AWS account. Create one using the <a href="https://portal.aws.amazon.com/billing/signup?nc2=h_ct&amp;src=header_signup&amp;redirect_url=https%3A%2F%2Faws.amazon.com%2Fregistration-confirmation#/start" target="_blank">link</a></li>
                                <li>Follow the steps mentioned in the email.</li>
                            </div>
                        </div>
                        <div class="row">
                            <a href="{{ route('stripe.connect') }}" class="btn blue pull-right mb-1 ml-2" data-target="#apply" onclick="setBenefit('3')">Apply</a>
                            <a href="javascript:void(0);" class="btn blue-outline pull-right mb-1" onclick="javascript:toggleFullDescription('3');" id="3-button">Learn more</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row no-margin">
        <div class="col-md-2 hidden-xs">&nbsp;</div>
        <div class="col-md-8 apps-shadow mb-2" style="background-color: #ffffff;">
            <div class="apps-box">
                <div class="row no-margin">
                    <div class="col-xs-6">
                        <h2 class="mb-1" id="title_3">Razorpay
                        </h2>
                        <p class="apps-help pull-left">Cloud computing</p>
                    </div>
                    <div class="col-xs-6">
                        <h3 class="mb-1 text-right">
                            $  5,000/-
                        </h3>
                        <p class="apps-help text-right">Your savings</p>
                    </div>
                </div>
                <div class="row no-margin">
                    <div class="col-md-3 apps-left-box">
                        <img class="img-responsive" src="/images/benefits/amazonwebservices/awsactivate.png">
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-12 apps-description">
                                Amazon Web Services offers inexpensive, reliable and scalable cloud computing services. $5000 credits for 2 years and business support worth $1,500
                            </div>
                            <div class="col-md-12 apps-detailed-description" style="display: none;" id="3-detail">
                                <div class="mb-1">
                                    <p class="apps-detailed-description-heading">Description</p>
                                    Amazon Web Services provides SMBs &amp; startups with low cost, easy to use infrastructure needed to scale and grow any size business. AWS Activate is a program with resources designed to help startups get started on AWS. Join some of the fastest-growing startups in the world and build your business using AWS.
                                </div>
                                <div class="mb-1">
                                    <p class="apps-detailed-description-heading">Offer</p>
                                    
                                </div>
                                <p class="apps-detailed-description-heading">Application process</p>
                                <li>Click on Apply</li>
                                <li>Once you apply, you will receive an email with instructions from Swipez support</li>
                                <li>If you haven't already created an AWS account. Create one using the <a href="https://portal.aws.amazon.com/billing/signup?nc2=h_ct&amp;src=header_signup&amp;redirect_url=https%3A%2F%2Faws.amazon.com%2Fregistration-confirmation#/start" target="_blank">link</a></li>
                                <li>Follow the steps mentioned in the email.</li>
                            </div>
                        </div>
                        <div class="row">
                            <a href="javascript:void(0);" class="btn blue pull-right mb-1 ml-2" data-toggle="modal" data-target="#apply" onclick="setBenefit('3')">Apply</a>
                            <a href="javascript:void(0);" class="btn blue-outline pull-right mb-1" onclick="javascript:toggleFullDescription('3');" id="3-button">Learn more</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row no-margin">
        <div class="col-md-2 hidden-xs">&nbsp;</div>
        <div class="col-md-8 apps-shadow mb-2" style="background-color: #ffffff;">
            <div class="apps-box">
                <div class="row no-margin">
                    <div class="col-xs-6">
                        <h2 class="mb-1" id="title_3">Cashfree
                        </h2>
                        <p class="apps-help pull-left">Cloud computing</p>
                    </div>
                    <div class="col-xs-6">
                        <h3 class="mb-1 text-right">
                            $  5,000/-
                        </h3>
                        <p class="apps-help text-right">Your savings</p>
                    </div>
                </div>
                <div class="row no-margin">
                    <div class="col-md-3 apps-left-box">
                        <img class="img-responsive" src="/images/benefits/amazonwebservices/awsactivate.png">
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-12 apps-description">
                                Amazon Web Services offers inexpensive, reliable and scalable cloud computing services. $5000 credits for 2 years and business support worth $1,500
                            </div>
                            <div class="col-md-12 apps-detailed-description" style="display: none;" id="3-detail">
                                <div class="mb-1">
                                    <p class="apps-detailed-description-heading">Description</p>
                                    Amazon Web Services provides SMBs &amp; startups with low cost, easy to use infrastructure needed to scale and grow any size business. AWS Activate is a program with resources designed to help startups get started on AWS. Join some of the fastest-growing startups in the world and build your business using AWS.
                                </div>
                                <div class="mb-1">
                                    <p class="apps-detailed-description-heading">Offer</p>
                                    
                                </div>
                                <p class="apps-detailed-description-heading">Application process</p>
                                <li>Click on Apply</li>
                                <li>Once you apply, you will receive an email with instructions from Swipez support</li>
                                <li>If you haven't already created an AWS account. Create one using the <a href="https://portal.aws.amazon.com/billing/signup?nc2=h_ct&amp;src=header_signup&amp;redirect_url=https%3A%2F%2Faws.amazon.com%2Fregistration-confirmation#/start" target="_blank">link</a></li>
                                <li>Follow the steps mentioned in the email.</li>
                            </div>
                        </div>
                        <div class="row">
                            <a href="javascript:void(0);" class="btn blue pull-right mb-1 ml-2" data-toggle="modal" data-target="#apply" onclick="setBenefit('3')">Apply</a>
                            <a href="javascript:void(0);" class="btn blue-outline pull-right mb-1" onclick="javascript:toggleFullDescription('3');" id="3-button">Learn more</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row no-margin">
        <div class="col-md-2 hidden-xs">&nbsp;</div>
        <div class="col-md-8 apps-shadow mb-2" style="background-color: #ffffff;">
            <div class="apps-box">
                <div class="row no-margin">
                    <div class="col-xs-6">
                        <h2 class="mb-1" id="title_3">PayTM
                        </h2>
                        <p class="apps-help pull-left">Cloud computing</p>
                    </div>
                    <div class="col-xs-6">
                        <h3 class="mb-1 text-right">
                            $  5,000/-
                        </h3>
                        <p class="apps-help text-right">Your savings</p>
                    </div>
                </div>
                <div class="row no-margin">
                    <div class="col-md-3 apps-left-box">
                        <img class="img-responsive" src="/images/benefits/amazonwebservices/awsactivate.png">
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-12 apps-description">
                                Amazon Web Services offers inexpensive, reliable and scalable cloud computing services. $5000 credits for 2 years and business support worth $1,500
                            </div>
                            <div class="col-md-12 apps-detailed-description" style="display: none;" id="3-detail">
                                <div class="mb-1">
                                    <p class="apps-detailed-description-heading">Description</p>
                                    Amazon Web Services provides SMBs &amp; startups with low cost, easy to use infrastructure needed to scale and grow any size business. AWS Activate is a program with resources designed to help startups get started on AWS. Join some of the fastest-growing startups in the world and build your business using AWS.
                                </div>
                                <div class="mb-1">
                                    <p class="apps-detailed-description-heading">Offer</p>
                                    
                                </div>
                                <p class="apps-detailed-description-heading">Application process</p>
                                <li>Click on Apply</li>
                                <li>Once you apply, you will receive an email with instructions from Swipez support</li>
                                <li>If you haven't already created an AWS account. Create one using the <a href="https://portal.aws.amazon.com/billing/signup?nc2=h_ct&amp;src=header_signup&amp;redirect_url=https%3A%2F%2Faws.amazon.com%2Fregistration-confirmation#/start" target="_blank">link</a></li>
                                <li>Follow the steps mentioned in the email.</li>
                            </div>
                        </div>
                        <div class="row">
                            <a href="javascript:void(0);" class="btn blue pull-right mb-1 ml-2" data-toggle="modal" data-target="#apply" onclick="setBenefit('3')">Apply</a>
                            <a href="javascript:void(0);" class="btn blue-outline pull-right mb-1" onclick="javascript:toggleFullDescription('3');" id="3-button">Learn more</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row no-margin">
        <div class="col-md-2 hidden-xs">&nbsp;</div>
        <div class="col-md-8 apps-shadow mb-2" style="background-color: #ffffff;">
            <div class="apps-box">
                <div class="row no-margin">
                    <div class="col-xs-6">
                        <h2 class="mb-1" id="title_3">PayU
                        </h2>
                        <p class="apps-help pull-left">Cloud computing</p>
                    </div>
                    <div class="col-xs-6">
                        <h3 class="mb-1 text-right">
                            $  5,000/-
                        </h3>
                        <p class="apps-help text-right">Your savings</p>
                    </div>
                </div>
                <div class="row no-margin">
                    <div class="col-md-3 apps-left-box">
                        <img class="img-responsive" src="/images/benefits/amazonwebservices/awsactivate.png">
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-12 apps-description">
                                Amazon Web Services offers inexpensive, reliable and scalable cloud computing services. $5000 credits for 2 years and business support worth $1,500
                            </div>
                            <div class="col-md-12 apps-detailed-description" style="display: none;" id="3-detail">
                                <div class="mb-1">
                                    <p class="apps-detailed-description-heading">Description</p>
                                    Amazon Web Services provides SMBs &amp; startups with low cost, easy to use infrastructure needed to scale and grow any size business. AWS Activate is a program with resources designed to help startups get started on AWS. Join some of the fastest-growing startups in the world and build your business using AWS.
                                </div>
                                <div class="mb-1">
                                    <p class="apps-detailed-description-heading">Offer</p>
                                    
                                </div>
                                <p class="apps-detailed-description-heading">Application process</p>
                                <li>Click on Apply</li>
                                <li>Once you apply, you will receive an email with instructions from Swipez support</li>
                                <li>If you haven't already created an AWS account. Create one using the <a href="https://portal.aws.amazon.com/billing/signup?nc2=h_ct&amp;src=header_signup&amp;redirect_url=https%3A%2F%2Faws.amazon.com%2Fregistration-confirmation#/start" target="_blank">link</a></li>
                                <li>Follow the steps mentioned in the email.</li>
                            </div>
                        </div>
                        <div class="row">
                            <a href="javascript:void(0);" class="btn blue pull-right mb-1 ml-2" data-toggle="modal" data-target="#apply" onclick="setBenefit('3')">Apply</a>
                            <a href="javascript:void(0);" class="btn blue-outline pull-right mb-1" onclick="javascript:toggleFullDescription('3');" id="3-button">Learn more</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection