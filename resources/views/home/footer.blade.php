@livewireScripts

@isset($disablejquery)

@else
<script src="/assets/global/plugins/jquery.min.js" type="text/javascript" async="true"></script>
@endisset
@if(env('APP_ENV')=='PROD')
<a data-toggle="modal" href="#chatnowModal" class="btn btn-secondary chat-btn" id="chatnow_btn" onclick="showPanel()" style="display: none;">
    <div class="chat-btn-inner">
        <svg viewBox="0 0 24 24">
            <path d="M17.775,3.75 L6.225,3.75 C4.85809524,3.75 3.75,4.8580798 3.75,6.2249655 L3.75,14.4748505 C3.75,15.8417362 4.85809524,16.949816 6.225,16.949816 L15.78675,16.949816 L18.83925,20.0105234 C18.994943,20.1649501 19.2057125,20.2510378 19.425,20.24977 C19.5332206,20.2525586 19.6405895,20.2299549 19.7385,20.1837709 C20.046576,20.0572147 20.2483375,19.7578309 20.25,19.4247815 L20.25,6.2249655 C20.25,4.8580798 19.1419048,3.75 17.775,3.75 Z" color="paperWhite" class="svg-clr"></path>
        </svg>
    </div>
</a>
@endif

{{-- <footer class="navbar navbar-expand-lg navbar-dark bg-secondary pt-4 pb-1" id="footer">
    <div class="container d-lg-block">
        <div class="row row-eq-height">
            <div class="col-6 col-md-3 pb-4">
                <h5 class="mb-2 text-white text-uppercase"><b>Products & Features</b></h5>
                <a class="d-block text-light" href="{{ route('home.collectit') }}">Collect it - Billing app</a>
                <a class="d-block text-light" href="{{ route('home.billing') }}">Billing software</a>
                <a class="d-block text-light" href="{{ route('home.billing.feature.onlineinvoicing') }}">Online invoicing</a>
                <a class="d-block text-light" href="{{ route('home.payouts') }}">Payouts</a>
                <a class="d-block text-light" href="{{ route('home.paymentlink') }}">Payment links</a>
                <a class="d-block text-light" href="{{ route('home.expenses') }}">Expense management software</a>
                <a class="d-block text-light" href="{{ route('home.inventorymanagement') }}">Inventory management
                    software</a>
                <a class="d-block text-light" href="{{ route('home.paymentcollections') }}">Online payment
                    collections</a>
                <a class="d-block text-light" href="{{ route('home.einvoicing') }}">E-Invoicing</a>
                <a class="d-block text-light" href="{{ route('home.woocommerce.invoice') }}">Woocommerce invoicing</a>
                <a class="d-block text-light" href="{{ route('home.woocommerce.paymentgateway') }}">Woocommerce payment gateway</a>
                <a class="d-block text-light" href="{{ route('home.gstfiling') }}">GST filing software</a>
                <a class="d-block text-light" href="{{ route('home.gstrecon') }}">GST reconciliation</a>
                <a class="d-block text-light" href="{{ route('home.event') }}">Event registrations</a>
                <a class="d-block text-light" href="{{ route('home.booking') }}">Venue booking software</a>
                <a class="d-block text-light" href="{{ route('home.websitebuilder') }}">Website builder</a>
                <a class="d-block text-light" href="{{ route('home.urlshortener') }}">URL shortener</a>
            </div>
            <!-- Show below industry div in only large screens -->
            <div class="col-6 col-md-3 pb-4 d-none d-lg-block">
                <h5 class="mb-2 text-white text-uppercase"><b>Industry</b></h5>
                <a class="d-block text-light" href="{{ route('home.industry.cable') }}">Billing software for cable</a>
                <a class="d-block text-light" href="{{ route('home.industry.isp') }}">Billing software for ISP</a>
                <a class="d-block text-light" href="{{ route('home.industry.travelntour') }}">Billing software for
                    travel</a>
                <a class="d-block text-light" href="{{ route('home.industry.milkdairy') }}">Billing software for
                    dairy</a>
                <a class="d-block text-light" href="{{ route('home.industry.franchise') }}">Franchise billing
                    software</a>
                <a class="d-block text-light" href="{{ route('home.industry.education') }}">Billing software for
                    school</a>
                <a class="d-block text-light" href="{{ route('home.industry.entertainmentevent') }}">Event management
                    software</a>
                <a class="d-block text-light" href="{{ route('home.industry.hospitalityevent') }}">Hospitality event
                    management</a>
                <a class="d-block text-light" href="{{ route('home.industry.bookingfitness') }}">Fitness venue
                    bookings</a>
                <a class="d-block text-light" href="{{ route('home.industry.societybooking') }}">Housing society
                    billing</a>
                <a class="d-block text-light" href="{{ route('home.industry.freelancers') }}">Freelancer billing</a>
                <a class="d-block text-light" href="{{ route('home.industry.enterprises') }}">Solutions for
                    enterprises</a>
            </div>
            <div class="col-6 col-md-3">
                <h5 class="mb-2 text-white text-uppercase"><b>Company</b></h5>
                <a class="d-block text-light" href="{{ route('home.footer.terms') }}">Terms</a>
                <a class="d-block text-light" href="{{ route('home.footer.privacy') }}">Privacy</a>
                <a class="d-block text-light" href="{{ route('home.footer.partner') }}">Partner program</a>
                <a class="d-block text-light" href="{{ route('home.footer.inthenews') }}">In the news</a>
                <a class="d-block text-light" href="{{ route('home.footer.aboutus') }}">About Us</a>
                <a class="d-block text-light" href="{{ route('home.footer.contactus') }}">Contact Us</a>
                <br>
                <h5 class="mb-2 text-white text-uppercase"><b>COMPARE</b></h5>
                <a class="d-block text-light" href="{{ env('SWIPEZ_BASE_URL') }}quickbooks-alternative"> vs QuickBooks</a>
            </div>
            <div class="col-6 col-md-3">
                <h5 class="mb-2 text-white text-uppercase"><b>Resources</b></h5>
                <a class="d-block text-light" href="{{ env('SWIPEZ_BASE_URL') }}merchant/register">Sign up</a>
                <a class="d-block text-light" href="https://headwayapp.co/swipez-updates" target="_blank">What's
                    new?</a>
                <a class="d-block text-light" href="{{ route('home.pricing') }}">Pricing</a>
                <a class="d-block text-light" href="https://helpdesk.swipez.in/help">Knowledge Base</a>
                <a class="d-block text-light" href="{{ route('home.freebiztools') }}">Free business tools</a>
                <a class="d-block text-light" href="{{ route('home.invoicetemplates') }}">Invoice templates</a>
                <a class="d-block text-light" href="{{ route('home.invoiceformats') }}">Download invoice format</a>
                <a class="d-block text-light" href="{{ route('home.partnerbenefits') }}">Partner benefits</a>
                <a class="d-block text-light" href="{{ route('home.integrations') }}">Integrations</a>
                <a class="d-block text-light" href="{{ env('SWIPEZ_BASE_URL') }}blog">Blog</a>
                <a class="d-block text-light" href="https://docs.swipez.in/">Documentation</a>
            </div>


            <div class="col-6 col-md-3 pb-4 d-lg-none">
                <h5 class="mb-2 text-white text-uppercase"><b>Industry</b></h5>
                <a class="d-block text-light" href="{{ route('home.industry.cable') }}">Cable billing</a>
                <a class="d-block text-light" href="{{ route('home.industry.isp') }}">ISP billing </a>
                <a class="d-block text-light" href="{{ route('home.industry.travelntour') }}">Travel billing</a>
                <a class="d-block text-light" href="{{ route('home.industry.milkdairy') }}">Dairy billing</a>
                <a class="d-block text-light" href="{{ route('home.industry.franchise') }}">Franchise billing</a>
                <a class="d-block text-light" href="{{ route('home.industry.education') }}">School billing</a>
                <a class="d-block text-light" href="{{ route('home.industry.entertainmentevent') }}">Event
                    management</a>
                <a class="d-block text-light" href="{{ route('home.industry.hospitalityevent') }}">Hospitality
                    events</a>
                <a class="d-block text-light" href="{{ route('home.industry.bookingfitness') }}">Fitness bookings</a>
                <a class="d-block text-light" href="{{ route('home.industry.societybooking') }}">Society billing</a>
                <a class="d-block text-light" href="{{ route('home.industry.freelancers') }}">Freelancer billing</a>
                <a class="d-block text-light" href="{{ route('home.industry.enterprises') }}">Solutions for
                    enterprises</a>
            </div>
        </div>
    </div>
</footer> --}}

@if(env('APP_ENV')=='PROD')
<div class="modal left fade" id="chatnowModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog chat-dialog">
        <div class="modal-content chat-content">
            <div id="chatbox" class="modal-body chat-body"></div>
        </div>
    </div>
</div>
@endif

<div class="d-flex justify-content-center bg-primary">
    <div class="p-2 text-white">Swipez
        <small>Handmade in Pune, India</small>
    </div>
    <div class="p-2 text-white">
        <a class="text-light" href="https://www.facebook.com/Swipez-347240818812009">
            <svg aria-hidden="true" focusable="false" data-prefix="fab" data-icon="facebook-square" class="h-6 text-white" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                <path fill="currentColor" d="M400 32H48A48 48 0 0 0 0 80v352a48 48 0 0 0 48 48h137.25V327.69h-63V256h63v-54.64c0-62.15 37-96.48 93.67-96.48 27.14 0 55.52 4.84 55.52 4.84v61h-31.27c-30.81 0-40.42 19.12-40.42 38.73V256h68.78l-11 71.69h-57.78V480H400a48 48 0 0 0 48-48V80a48 48 0 0 0-48-48z">
                </path>
            </svg>
        </a>
    </div>
    <div class="p-2">
        <a class="text-light" href="https://www.instagram.com/swipez.in">
            <svg aria-hidden="true" focusable="false" data-prefix="fab" data-icon="instagram-square" class="h-6 text-white" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                <path fill="currentColor" d="M224,202.66A53.34,53.34,0,1,0,277.36,256,53.38,53.38,0,0,0,224,202.66Zm124.71-41a54,54,0,0,0-30.41-30.41c-21-8.29-71-6.43-94.3-6.43s-73.25-1.93-94.31,6.43a54,54,0,0,0-30.41,30.41c-8.28,21-6.43,71.05-6.43,94.33S91,329.26,99.32,350.33a54,54,0,0,0,30.41,30.41c21,8.29,71,6.43,94.31,6.43s73.24,1.93,94.3-6.43a54,54,0,0,0,30.41-30.41c8.35-21,6.43-71.05,6.43-94.33S357.1,182.74,348.75,161.67ZM224,338a82,82,0,1,1,82-82A81.9,81.9,0,0,1,224,338Zm85.38-148.3a19.14,19.14,0,1,1,19.13-19.14A19.1,19.1,0,0,1,309.42,189.74ZM400,32H48A48,48,0,0,0,0,80V432a48,48,0,0,0,48,48H400a48,48,0,0,0,48-48V80A48,48,0,0,0,400,32ZM382.88,322c-1.29,25.63-7.14,48.34-25.85,67s-41.4,24.63-67,25.85c-26.41,1.49-105.59,1.49-132,0-25.63-1.29-48.26-7.15-67-25.85s-24.63-41.42-25.85-67c-1.49-26.42-1.49-105.61,0-132,1.29-25.63,7.07-48.34,25.85-67s41.47-24.56,67-25.78c26.41-1.49,105.59-1.49,132,0,25.63,1.29,48.33,7.15,67,25.85s24.63,41.42,25.85,67.05C384.37,216.44,384.37,295.56,382.88,322Z">
                </path>
            </svg>
        </a>
    </div>
    <div class="p-2 text-white">
        <a class="text-light" href="https://www.linkedin.com/company/swipez/">
            <svg aria-hidden="true" focusable="false" data-prefix="fab" data-icon="linkedin" class="h-6 text-white" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                <path fill="currentColor" d="M416 32H31.9C14.3 32 0 46.5 0 64.3v383.4C0 465.5 14.3 480 31.9 480H416c17.6 0 32-14.5 32-32.3V64.3c0-17.8-14.4-32.3-32-32.3zM135.4 416H69V202.2h66.5V416zm-33.2-243c-21.3 0-38.5-17.3-38.5-38.5S80.9 96 102.2 96c21.2 0 38.5 17.3 38.5 38.5 0 21.3-17.2 38.5-38.5 38.5zm282.1 243h-66.4V312c0-24.8-.5-56.7-34.5-56.7-34.6 0-39.9 27-39.9 54.9V416h-66.4V202.2h63.7v29.2h.9c8.9-16.8 30.6-34.5 62.9-34.5 67.2 0 79.7 44.3 79.7 101.9V416z">
                </path>
            </svg>
        </a>
    </div>
    <div class="p-2 text-white">
        <a class="text-light" href="https://www.youtube.com/channel/UC62hpFWFt-S8aReQGDsdoSg/featured">
            <svg aria-hidden="true" focusable="false" data-prefix="fab" data-icon="youtube-square" class="h-6 text-white" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                <path fill="currentColor" d="M186.8 202.1l95.2 54.1-95.2 54.1V202.1zM448 80v352c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V80c0-26.5 21.5-48 48-48h352c26.5 0 48 21.5 48 48zm-42 176.3s0-59.6-7.6-88.2c-4.2-15.8-16.5-28.2-32.2-32.4C337.9 128 224 128 224 128s-113.9 0-142.2 7.7c-15.7 4.2-28 16.6-32.2 32.4-7.6 28.5-7.6 88.2-7.6 88.2s0 59.6 7.6 88.2c4.2 15.8 16.5 27.7 32.2 31.9C110.1 384 224 384 224 384s113.9 0 142.2-7.7c15.7-4.2 28-16.1 32.2-31.9 7.6-28.5 7.6-88.1 7.6-88.1z">
                </path>
            </svg>
        </a>
    </div>
    <div class="p-2 text-white"><small>© {{ date('Y') }} OPUS Net Pvt Ltd</small></div>
</div>
<script src="/static/js/lazysizes.min.js" async=""></script>



@php if(isset($loader)){ @endphp
<script>
    window.onload = function() {
        $('.loader').fadeOut();
    }
</script>
@php } @endphp


<script src="/static/js/custom.js{{ Helpers::fileTime('new','static/js/custom.js') }}" async="true"></script>
<script src="/static/js/popper.min.js" async="true"></script>
<script src="/static/js/bootstrap.min.js" async="true"></script>
<script src="/static/js/smooth-scroll.polyfills.min.js" async="true"></script>
@yield('customfooter')

@if(env('APP_ENV')=='PROD')
<script>
    function showPanel() {
        $('#chatbox').height(screen.height - 80);
        src = document.getElementById("chatbox").innerHTML;
        if (src == '') {
            document.getElementById("chatbox").innerHTML = '<iframe id="iframeModalWindow" height="100%" width="100%" src="/merchant/chatnow" allowtransparency="true" name="iframe_modal" frameborder="0"></iframe>';
        }
        document.getElementById("chatnow_btn").style.display = 'none';
        $('#chatnowModal').on('hidden.bs.modal', function() {
            document.getElementById("chatnow_btn").style.display = 'inline-block';
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById("chatnow_btn").style.display = 'inline-block';
    }, false);
</script>
<!-- BEGIN GROOVE WIDGET CODE -->
{{-- <script>
    !function(e,t){if(!e.groove){var i=function(e,t){return Array.prototype.slice.call(e,t)},a={widget:null,loadedWidgets:{},classes:{Shim:null,Embeddable:function(){this._beforeLoadCallQueue=[],this.shim=null,this.finalized=!1;var e=function(e){var t=i(arguments,1);if(this.finalized){if(!this[e])throw new TypeError(e+"() is not a valid widget method");this[e].apply(this,t)}else this._beforeLoadCallQueue.push([e,t])};this.initializeShim=function(){a.classes.Shim&&(this.shim=new a.classes.Shim(this))},this.exec=e,this.init=function(){e.apply(this,["init"].concat(i(arguments,0))),this.initializeShim()},this.onShimScriptLoad=this.initializeShim.bind(this),this.onload=void 0}},scriptLoader:{callbacks:{},states:{},load:function(e,i){if("pending"!==this.states[e]){this.states[e]="pending";var
a=t.createElement("script");a.id=e,a.type="text/javascript",a.async=!0,a.src=i;var
s=this;a.addEventListener("load",(function(){s.states[e]="completed",(s.callbacks[e]||[]).forEach((function(e){e()}))}),!1);var
n=t.getElementsByTagName("script")[0];n.parentNode.insertBefore(a,n)}},addListener:function(e,t){"completed"!==this.states[e]?(this.callbacks[e]||(this.callbacks[e]=[]),this.callbacks[e].push(t)):t()}},createEmbeddable:function(){var
t=new a.classes.Embeddable;return e.Proxy?new Proxy(t,{get:function(e,t){return e instanceof
a.classes.Embeddable?Object.prototype.hasOwnProperty.call(e,t)||"onload"===t?e[t]:function(){e.exec.apply(e,[t].concat(i(arguments,0)))}:e[t]}}):t},createWidget:function(){var
e=a.createEmbeddable();return
a.scriptLoader.load("groove-script","https://d9c27c88-adc0-4923-a7e8-2ef0b33493f5.widget.cluster.groovehq.com/api/loader"),a.scriptLoader.addListener("groove-iframe-shim-loader",e.onShimScriptLoad),e}};e.groove=a}}(window,document);

window.groove.widget = window.groove.createWidget();

window.groove.widget.init('d9c27c88-adc0-4923-a7e8-2ef0b33493f5', {});

</script> --}}
<!-- END GROOVE WIDGET CODE -->
@endif

@if(isset($captcha))
<script src="https://www.google.com/recaptcha/api.js?render={{$CAPTCHA_CLIENT_ID}}"></script>
<script>
    var captcha_id = '{{$CAPTCHA_CLIENT_ID}}';
    var req_count = 0;

    function gcaptchaReSet() {
        if (req_count < 3) {
            gcaptchaSet();
            req_count = req_count + 1;
        } else {
            req_count = 0;
        }
    }

    function gcaptchaSet() {
        grecaptcha.execute(captcha_id, {
            action: 'homepage'
        }).then(function(token) {
            try {
                document.getElementById('captcha1').value = token;
            } catch (o) {}
        });
    }
    grecaptcha.ready(function() {
        gcaptchaSet();
    });
    setInterval(function() {
        gcaptchaSet();
    }, 2 * 60 * 1000);
</script>
@endif

</body>

</html>