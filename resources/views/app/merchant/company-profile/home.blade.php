@extends('app.master')

@section('header')

<link href="{{asset('assets/global/plugins/uniform/css/uniform.default.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/admin/layout/css/landing.css?version=1625166659')}}" rel="stylesheet" type="text/css"/>

<style>
    #HW_badge_cont{position:absolute!important}#HW_badge{height:18px!important;width:18px!important;line-height:18px!important;font-size:14px!important;top:6px!important;left:5px!important;background:#f99b36!important;border-radius:30px!important}
</style>
@endsection
@section('content')

<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">
        <span class="page-title mb-2" style="float: left;">Company profile</span>
        {{ Breadcrumbs::render() }}
        @if($details->publishable == 0)
        <a href="javascript:void(0)" class="btn grey pull-right mb-1"> View Page</a>
        @else
        <a href="/m/<?php echo $merchant->display_url?>" class="btn blue pull-right mb-1" target="_BLANK"> View Page</a>
        @endif
    </div>
    <div class="row no-margin">
        @include('layouts.alerts')
        <div class="col-md-12 no-padding">
            <!-- <form enctype="multipart/form-data" action="/merchant/companyprofile/saved/home" method="post" id="submit_form" class="form-horizontal form-row-sepe"> -->
            <form enctype="multipart/form-data" action="{{url('/site/update-home')}}" method="post" id="submit_form" class="form-horizontal form-row-sepe">
                @csrf
                <input type="hidden" value="updateHome" name="type">
                <div class="portlet light bordered">
                    <div class="portlet-body form">
                        <form action="#" id="template_create" >
                            <div class="form-body">
                                <div class="alert alert-danger display-none">
                                    <button class="close" data-dismiss="alert"></button>
                                    You have some form errors. Please check below.
                                </div>
                                <!-- image upload -->
                                @include('app.merchant.company-profile.banner')
                                
                                <div class="portlet-body form">
                                    <div class="form-body">
                                        <div class="tab-content">
                                            <div id="home-panel" class="row tab-pane fade in active">
                                                <div class="col-md-10">
                                                    <div class="form-group">
                                                        <label class="control-label col-md-3">Publish my company page</label>
                                                        <div data-tour="publish-company-page" class="col-md-5">
                                                            <input type="checkbox" id="publisher" onchange="handleToggle()" id="toggleButton" name="publishable" value="1" class="make-switch" data-on-text="&nbsp;Enabled&nbsp;&nbsp;" 
                                                                    data-off-text="&nbsp;Disabled&nbsp;" @if($details->publishable == 1) checked @endif>
                                                            <input type="hidden" name="publish" id="publish" @if($details->publishable == 1) value="1" @else value="0" @endif>
                                                        
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-md-3 control-label">Display URL</label>
                                                            <div class="col-md-5 varied-display-url-field">
                                                                <div class="input-group">
                                                                    <span class="input-group-addon" id="basic-addon2">{{env('SWIPEZ_BASE_URL')}}m/</span>
                                                                    <input required maxlength="20" minlength="3" data-tour="display-url" type="text" name="display_url" required value="{{$merchant->display_url}}" @if($details->publishable == 0) readonly @endif class="form-control to_be_disabled @error('display_url') is-invalid @enderror">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <a @if($details->publishable == 0) style="display:none" @endif href="/m/<?php echo $merchant->display_url?>" target="_blank" class="btn btn-md green to_be_hide company-profile-view-page-button">View Page</a>
                                                            </div>
                                                        {{-- <div class="col-md-1">
                                                            <a href="https://{{$merchant->display_url.env('SUB_DOMAIN_URL')}}" target="_blank" class="btn btn-md green">View Page</a>
                                                        </div> --}}
                                                    </div>
                                                    <div data-tour="company-info">
                                                        <div class="form-group">
                                                            <label class="col-md-3 control-label">Title</label>                                                                                                                     
                                                            <div class="col-md-5">
                                                                <textarea maxlength="100" minlength="5" name="banner_text" @if($details->publishable == 0) readonly @endif class="form-control form-control-inline input-sm to_be_disabled @error('banner_text') is-invalid @enderror" rows="3">{{str_replace('%INDUSTRY_NAME%', $industry_name, str_replace('%NO_OF_CUSTOMER%', $merchant->customer_count, str_replace('%NO_OF_EMPLOYEE%', $merchant->employee_count,str_replace('%COMPANY_NAME%', ucwords($merchant->company_name), $details->banner_text))))}}</textarea>
                                                                <span class="help-block">
                                                                </span>
                                                                @error('banner_text')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-md-3 control-label">Sub Title</label>
                                                            <div class="col-md-5">
                                                                <textarea maxlength="250" minlength="5" name="banner_paragraph" @if($details->publishable == 0) readonly @endif class="form-control form-control-inline input-sm to_be_disabled @error('banner_paragraph') is-invalid @enderror" rows="3">{{$details->banner_paragraph}}</textarea>
                                                                <span class="help-block">
                                                                </span>
                                                                @error('banner_paragraph')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        {{-- <div class="form-group">
                                                            <label class="col-md-3 control-label">Overview</label>
                                                            <div class="col-md-9 to_be_hide" @if($details->publishable == 0) style="display:none" @endif>
                                                                <textarea class="form-control description" id="description" name="overview" rows="8" >{{str_replace('%INDUSTRY_NAME%', $industry_name, str_replace('%NO_OF_CUSTOMER%', $merchant->customer_count, str_replace('%NO_OF_EMPLOYEE%', $merchant->employee_count,str_replace('%COMPANY_NAME%', ucwords($merchant->company_name), $details->overview))))}}</textarea>
                                                                <span class="help-block">
                                                                </span>
                                                            </div>
                                                            <div class="col-md-9 to_be_show" @if($details->publishable == 1) style="display:none" @endif>
                                                                <textarea class="form-control" readonly id="textdescription" rows="8" >{{strip_tags($details->overview)}}</textarea>
                                                                <span class="help-block">
                                                                </span>
                                                            </div>
                                                        </div> --}}
                                                        <div class="form-group">
                                                            <label class="col-md-3 control-label">Why work with us</label>
                                                            <div class="col-md-9 to_be_hide" @if($details->publishable == 0) style="display:none" @endif>
                                                                <textarea class="form-control tncrich" id="work_description" name="why_work_with_us_text" rows="8" >{{str_replace('%INDUSTRY_NAME%', $industry_name, str_replace('%NO_OF_CUSTOMER%', $merchant->customer_count, str_replace('%NO_OF_EMPLOYEE%', $merchant->employee_count,str_replace('%COMPANY_NAME%', ucwords($merchant->company_name), $details->overview))))}}</textarea>
                                                                <span class="help-block">
                                                                </span>
                                                            </div>
                                                            <div class="col-md-9 to_be_show" @if($details->publishable == 1) style="display:none" @endif>
                                                                <textarea class="form-control" readonly id="work-textdescription" rows="8" >{{strip_tags($details->overview)}}</textarea>
                                                                <span class="help-block">
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div id="policy-panel" class="row tab-pane fade">
                                                <div class="col-md-1"></div>
                                                <div class="col-md-10">
                                                    <div class="form-group">
                                                        <label>Terms and Conditions</label>
                                                        <div class="to_be_hide" @if($details->publishable==0) style="display:none" @endif ><textarea name="terms_condition" class="form-control tncrich" rows="8">{{$details->terms_condition}}</textarea></div>
                                                        <div class="to_be_show" @if($details->publishable==1) style="display:none" @endif ><textarea class="form-control" readonly rows="8">{{strip_tags($details->terms_condition)}}</textarea></div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Cancellation and Refund</label>
                                                        <div class="to_be_hide" @if($details->publishable==0) style="display:none" @endif ><textarea name="cancellation_policy" class="form-control tncrich" rows="8">{{$details->cancellation_policy}}</textarea></div>
                                                        <div class="to_be_show"  @if($details->publishable==1) style="display:none" @endif ><textarea class="form-control" readonly rows="8">{{strip_tags($details->cancellation_policy)}}</textarea></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row tab-pane fade" id="aboutus-panel">
                                                <div class="col-md-1"></div>
                                                <div class="col-md-10">
                                                    <div class="form-group">
                                                        <label>About us</label>
                                                        <div class="to_be_hide" @if($details->publishable==0) style="display:none" @endif ><textarea name="about_us" class="form-control tncrich" rows="10">{{$details->about_us}}</textarea></div>
                                                        <div class="to_be_show" @if($details->publishable==1) style="display:none" @endif ><textarea class="form-control" readonly rows="10">{{strip_tags($details->about_us)}}</textarea></div>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="row tab-pane fade" id="contactus-panel">
                                                <div class="col-md-10">
                                                    <div class="form-group">
                                                        <label class="col-md-3 control-label">Office location</label>
                                                        <div class="col-md-5">
                                                            <textarea maxlength="255" minlength="5" rows="3" name="location" @if($details->publishable==0) readonly @endif class="form-control form-control-inline input-sm to_be_disabled @error('location') is-invalid @enderror">{{$details->office_location}}</textarea>
                                                            <span class="help-block">
                                                            </span>
                                                            @error('location')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-md-3 control-label">Contact no</label>
                                                        <div class="col-md-5">
                                                            <input maxlength="13" minlength="10" type="number" name="contact_no" @if($details->publishable==0) readonly @endif value="{{$details->contact_no}}" class="form-control form-control-inline input-sm to_be_disabled @error('contact_us') is-invalid @enderror">
                                                            <span class="help-block">
                                                            </span>
                                                            @error('contact_us')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-md-3 control-label">Email ID</label>
                                                        <div class="col-md-5">
                                                            <input maxlength="250" minlength="5" type="email" name="email_id" @if($details->publishable==0) readonly @endif value="{{$details->email_id}}" class="form-control form-control-inline input-sm to_be_disabled @error('email_id') is-invalid @enderror">
                                                            <span class="help-block">
                                                            </span>
                                                            @error('eamil_id')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-actions">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="pull-right">
                                                    <input type="hidden" name="ex_display_url"  value="testuser15">
                                                    <button data-tour="save-company-page" type="submit" class="btn blue"><i class="fa fa-check"></i> Save</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>	
                        </form>
                    </div>
                </div>
            </form>
        </div>
    </div>	
</div>
@endsection

@section('footer')

<!-- help hero tour -->
@if ($showTour==0) 
<div data-controls-modal="helphero" data-backdrop="static" data-keyboard="false" class="modal fade" id="edit-company-profile" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="width: 430px;">
        <div class="modal-content">
            <div class="portlet box" style="margin-bottom: 5px;">
                <div class="portlet-title" style="background-color: #18aebf;">
                    <div class="caption whitecolor">
                        Edit your company pages
                    </div>
                </div>
            </div>
            <div class="modal-body ">
                Take this 2 minute tour to learn how to edit your company pages
            </div>
            <div class="modal-footer">
                <a type="button" class="btn green" data-dismiss="modal" aria-label="Close">Don't show again</a>
                <a href="" onclick="HelpHero.startTour('eeDEo0MOGu',{ skipIfAlreadySeen: false });" data-dismiss="modal" class="btn blue">Take the tour</a>
            </div>
        </div>                  
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<a data-toggle="modal" id="edit-company-profile-helphero" href="#edit-company-profile"></a>
<script>
    document.getElementById('edit-company-profile-helphero').click();
</script>
<script src="//app.helphero.co/embed/cjcHsHLBZdr"></script>
<script>
    HelpHero.identify("{{$merchant->merchant_id}}", {
        role: "Merchant",
        created_at : "{{$merchant->created_date}}"
    });
    HelpHero.on("tour_completed", function(event, info) {
        if(event.kind == 'tour_completed' && event.tourId== 'eeDEo0MOGu' && event.details == 'COMPLETE') {
            $.ajax({
                type: 'POST',
                url: '/site/set-complete-company-page',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "flag": 1
                },
                success: function (data) {
                    obj = JSON.parse(data);
                    if (obj.status == 1) {
                    } else {
                    }
                }
            });
        }
    });
</script>
@endif

<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="{{asset('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js')}}" type="text/javascript"></script>
<!-- IMPORTANT! Load jquery-ui.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
<script src="{{asset('assets/global/plugins/bootstrap/js/bootstrap.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/global/plugins/uniform/jquery.uniform.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js')}}" type="text/javascript"></script>
<!-- END CORE PLUGINS -->


<!-- END PAGE LEVEL PLUGINS -->
<script>
$(document).ready(function() {
    console.log($(document).width());
    if($(document).width() <= 1250 && $(document).width() >= 991) {
        $('.varied-display-url-field').removeClass('col-md-5');
        $('.varied-display-url-field').addClass('col-md-7');
    }
    else {
        $('.varied-display-url-field').addClass('col-md-5');
        $('.varied-display-url-field').removeClass('col-md-7');
    }
    if($(document).width() <= 991) {
        $('.company-profile-view-page-button').addClass('pull-right');
    }
    else{
        $('.company-profile-view-page-button').removeClass('pull-right');
    }
    if($(document).width() <= 663) {
        $('.extra-breaks').css('display', 'block');
    }
    else {
        $('.extra-breaks').css('display', 'none');
    }
    if($('.custom-slider').hasClass('checked')) {
        $('.custom-slider').css('background-color', '#0DA3E2');
    }
});
function handleToggle(){
    if($('.bootstrap-switch').hasClass('bootstrap-switch-on')){
        $('#publish').val("1");
        $('.to_be_disabled').removeAttr('disabled');
        $('.to_be_disabled').removeAttr('readonly');
        $('.to_be_disabled').removeAttr('style');
        $('.to_be_hide').css('display','inline-block');
        $('.to_be_show').css('display', 'none');

    }
    else{
        $('#publish').val("0");
        $('.to_be_disabled').attr('disabled','disabled');
        $('.to_be_hide').css('display','none');
        $('.to_be_show').css('display', 'block');
    }
}
</script>
@endsection
