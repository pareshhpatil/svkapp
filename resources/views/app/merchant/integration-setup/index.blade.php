@extends('app.master')

@section('content')
<style>
    .image-logo {
        height: 50px !important;

    }
</style>
<div class="page-content">
    <div class="page-bar">
        <span class="page-title" style="float: left;">{{$title}}</span>
        {{ Breadcrumbs::render() }}
    </div>
    <div class="loading" id="loader" style="display: none;">Loading&#8230;</div>
    @if(Session::has('success'))
    <div class="alert alert-success">
        <button type="button" class="close" data-dismiss="alert"></button>
        <strong>@if(Session::has('success_title')) {{Session::get('success_title')}} @else Success!@endif</strong>
        <div class="media">
            <p class="media-heading">{{Session::get('success')}}</p>
        </div>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger">
        <div class="media">
            <p class="media-heading">{{$errors->first()}}</p>
        </div>
    </div>
    @endif
    @foreach($list as $v)
    <div class="row no-margin">
        <div class="col-md-2 hidden-xs">&nbsp;</div>
        <div class="col-md-8 apps-shadow mb-2" style="background-color: #ffffff;">
            <div class="apps-box">
                <div class="row no-margin">
                    <div class="col-xs-6">
                        <h2 class="mb-1" id="title_{{$v->id}}">{{ucwords($v->title)}}
                        </h2>
                        <p class="apps-help pull-left">{{ucfirst($v->category)}}</p>
                    </div>
                    <div class="col-xs-6">
                        <h3 class="mb-1 text-right">
                            @if($v->offer_value>0)
                            @if($v->value_type=='Amount')
                            <!--@if($v->currency=='inr')₹ @else $ @endif {{Helpers::moneyFormatIndia($v->offer_value)}}/--->
                            @else
                            {{Helpers::moneyFormatIndia($v->offer_value)}} %
                            @endif
                            @endif
                        </h3>
                        @isset($bank_detail->stripe_status)
                        @php $stripe_status=1;
                        $payoneer_account_id=$bank_detail->payoneer_account_id;
                        $stripe_account_id=$bank_detail->stripe_account_id;
                        @endphp
                        @else
                        @php $stripe_status=0;
                        $payoneer_account_id ='';
                        $stripe_account_id ='';
                        @endphp
                        @endisset
                        <p class="apps-help text-right">
                            @isset($activePG[$v->integration_type])
                            Integrated
                            @php $processed=1; @endphp
                            @else
                            @if($v->integration_type==11 && $stripe_account_id!='' && $stripe_status==1)
                            In Review
                            @php $processed=1; @endphp
                            @else
                            @if($v->integration_type==12 && $payoneer_account_id!='' && $stripe_status==1)
                            In Review
                            @php $processed=1; @endphp
                            @else
                            {{$v->offer_value_text}}
                            @php $processed=0; @endphp
                            @endif
                            @endif
                            @endisset
                        </p>
                    </div>
                </div>
                <div class="row no-margin">
                    <div class="col-md-3 apps-left-box">
                        <img class="img-responsive image-logo" height="50" src="/images/integrations/{{$v->logo}}">
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
                            @if($processed==1)
                            <a href="javascript:void(groove.widget.toggle())" class="btn blue pull-right mb-1 ml-2">Chat with us</a>
                            @if(isset($activePG[$v->integration_type]) && $v->integration_type==11)
                            <a href="javascript:void(0);" onclick="callSidePanel(1,{{$activePG[$v->integration_type]['pg_id']}});" onclick="" class="btn green pull-right mb-1 ml-2">Update credentials</a>
                            @endif
                            @else
                            @if($v->integration_type==11)
                            <a href="javascript:void(0);" class="btn blue pull-right mb-1 ml-2 clicker" onclick="callSidePanel(1);">Integrate existing account</a>
                            <a href="javascript:void(0);" class="btn green pull-right mb-1 ml-2 clicker" data-toggle="modal" data-target="#{{$v->title}}Modal">Create new account</a>
                            @else
                            <a href="javascript:void(0);" class="btn blue pull-right mb-1 ml-2 clicker" data-toggle="modal" data-target="#{{$v->title}}Modal">Integrate</a>
                            @endif
                            <!-- Modal -->
                            <div class="modal fade" id="{{$v->title}}Modal" tabindex="-1" role="dialog" aria-labelledby="{{$v->title}}ModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="{{$v->title}}ModalLabel">
                                                Integration {{ucfirst($v->title)}} payment gateway
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>

                                        @if($v->integration_type!=11 && $v->integration_type!=12)
                                        <div class="modal-body">
                                            @if($checkKYCComplete==1)
                                            @if(!empty($getActivePGS) && $v->integration_type==7)
                                            Razorpay payment gateway is already associated with your account. To switch to {{ucfirst($v->title)}} payment gateway, please send us a message via chat or email us at <a href="mailto:support@swipez.in">support@swipez.in</a>.
                                            @elseif(!empty($getActivePGS) && $v->integration_type==10)
                                            Cashfree payment gateway is already associated with your account. To switch to {{ucfirst($v->title)}} payment gateway, please send us a message via chat or email us at <a href="mailto:support@swipez.in">support@swipez.in</a>.
                                            @else
                                            To activate payment gateway into your Swipez account, please send us a message via chat or email us at <a href="mailto:support@swipez.in">support@swipez.in</a>.
                                            @endif
                                            @else
                                            To integrate {{ucfirst($v->title)}} payment gateway into your Swipez account please complete your company information & upload the required KYC documents.
                                            @endif
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            @if($checkKYCComplete!=1)
                                            <a type="button" href="/merchant/profile/complete" class="btn btn-primary">Integrate</a>
                                            @endif
                                        </div>
                                        @endif

                                        @if($v->integration_type==11)
                                        <div class="modal-body">
                                            To integrate Stripe into your Swipez account we will redirect you to the Stripe website to complete your onboarding.
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <a type="button" href="/merchant/profile/complete/international" class="btn btn-primary">Integrate</a>
                                        </div>
                                        @endif
                                        @if($v->integration_type==12)
                                        <div class="modal-body">
                                            To integrate Payoneer into your Swipez account we will redirect you to the Payoneer website to complete your onboarding.
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" id="payoneerclose" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <a href="/merchant/integrations/payoneer" onclick="document.getElementById('payoneerclose').click();document.getElementById('pconnect').click();" class="btn btn-primary">Integrate</a>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @if($v->integration_type==12)
                            <a id="pconnect" data-toggle="modal" data-target="#connecting"></a>
                            <div class="modal fade" id="connecting" tabindex="-1" role="dialog" aria-labelledby="connecting" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-body">
                                            Connecting to {{$v->title}}...
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @endif
                            <a href="javascript:void(0);" class="btn blue-outline pull-right mb-1" onclick="javascript:toggleFullDescription('{{$v->id}}');" id="{{$v->id}}-button">Learn
                                more</a>
                            <span class="pt-2 pull-right mb-1" style="color: #F99B36; padding: 7px 14px;">
                                @if($v->integration_type==11 && $stripe_account_id!='' && $stripe_status==1)
                                Stripe account activation currently in progress.
                                @elseif($v->integration_type==12 && $payoneer_account_id!='' && $stripe_status==1)
                                Payoneer account activation currently in progress.
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
<style>
    .panel-wrap {
        position: fixed;
        top: 0;
        bottom: 0;
        right: 0;
        left: 50%;
        /* width: 80em; */
        transform: translateX(100%);
        transition: .3s ease-out;
        z-index: 11;
    }

    .panel-wrap .panel {
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
        color: #000;
        right: 25px;
        z-index: 99999;
    }

    .remove:hover {
        color: #000;
    }

    .remove i {
        font-size: 25px !important;
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
</style>
<div class="panel-wrap" id="panelWrapId">
    <div id="close_tab" hidden>
        <a href="javascript:;" class="remove" data-original-title="Close" title="" onclick="return closeSidePanel();">
            {{-- <i class="fa fa-times"> </i> --}}
        </a>
    </div>
    <div class="panel">
        <div id="integration_view_ajax">
        </div>
    </div>
</div>
<script>
    function callSidePanel(integration_id,pg_id='') {
        if (integration_id != '') {
            document.getElementById("panelWrapId").style.boxShadow = "0 0 0 9999px rgba(0,0,0,0.5)";
            document.getElementById("panelWrapId").style.transform = "translateX(0%)";
            document.getElementById("close_tab").style.display = "block";
            $.ajax({
                type: "POST",
                url: '/merchant/integration-setup/getSetupDetails',
                data: {
                    'integration_id': integration_id,
                    'pg_id': pg_id,
                    "_token": "{{ csrf_token() }}",
                },
                datatype: 'html',
                success: function(response) {
                    $("#integration_view_ajax").html(response);
                    //SubscriptionTableAdvanced.init();
                },
                error: function() {},
            });
        }
        return false;
    }

    function closeSidePanel() {
        document.getElementById("panelWrapId").style.boxShadow = "none";
        document.getElementById("panelWrapId").style.transform = "translateX(100%)";
        document.getElementById("close_tab").style.display = "none";
        return false;
    }
</script>
<script>
    ! function(e, t) {
        if (!e.groove) {
            var i = function(e, t) {
                    return Array.prototype.slice.call(e, t)
                },
                a = {
                    widget: null,
                    loadedWidgets: {},
                    classes: {
                        Shim: null,
                        Embeddable: function() {
                            this._beforeLoadCallQueue = [], this.shim = null, this.finalized = !1;
                            var e = function(e) {
                                var t = i(arguments, 1);
                                if (this.finalized) {
                                    if (!this[e]) throw new TypeError(e + "() is not a valid widget method");
                                    this[e].apply(this, t)
                                } else this._beforeLoadCallQueue.push([e, t])
                            };
                            this.initializeShim = function() {
                                a.classes.Shim && (this.shim = new a.classes.Shim(this))
                            }, this.exec = e, this.init = function() {
                                e.apply(this, ["init"].concat(i(arguments, 0))), this.initializeShim()
                            }, this.onShimScriptLoad = this.initializeShim.bind(this), this.onload = void 0
                        }
                    },
                    scriptLoader: {
                        callbacks: {},
                        states: {},
                        load: function(e, i) {
                            if ("pending" !== this.states[e]) {
                                this.states[e] = "pending";
                                var a = t.createElement("script");
                                a.id = e, a.type = "text/javascript", a.async = !0, a.src = i;
                                var s = this;
                                a.addEventListener("load", (function() {
                                    s.states[e] = "completed", (s.callbacks[e] || []).forEach((function(e) {
                                        e()
                                    }))
                                }), !1);
                                var n = t.getElementsByTagName("script")[0];
                                n.parentNode.insertBefore(a, n)
                            }
                        },
                        addListener: function(e, t) {
                            "completed" !== this.states[e] ? (this.callbacks[e] || (this.callbacks[e] = []), this.callbacks[e].push(t)) : t()
                        }
                    },
                    createEmbeddable: function() {
                        var t = new a.classes.Embeddable;
                        return e.Proxy ? new Proxy(t, {
                            get: function(e, t) {
                                return e instanceof a.classes.Embeddable ? Object.prototype.hasOwnProperty.call(e, t) || "onload" === t ? e[t] : function() {
                                    e.exec.apply(e, [t].concat(i(arguments, 0)))
                                } : e[t]
                            }
                        }) : t
                    },
                    createWidget: function() {
                        var e = a.createEmbeddable();
                        return a.scriptLoader.load("groove-script", "https://d9c27c88-adc0-4923-a7e8-2ef0b33493f5.widget.cluster.groovehq.com/api/loader"), a.scriptLoader.addListener("groove-iframe-shim-loader", e.onShimScriptLoad), e
                    }
                };
            e.groove = a
        }
    }(window, document);
    window.groove.widget = window.groove.createWidget();
    window.groove.widget.init('d9c27c88-adc0-4923-a7e8-2ef0b33493f5', {});
</script>
@endsection