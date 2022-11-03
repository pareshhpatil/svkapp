<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @if(route('mpages.home', $merchant->display_url) == url()->current())
        <title>Billing Portal for {{ucwords($merchant->company_name)}} | Powered by Swipez</title>
        <meta name="description" content="{{ucfirst($merchant->company_name)}} | {{ucfirst($industry_name)}} | {!! $merchant_detail->banner_paragraph == null ? $default_data->banner_paragraph : $merchant_detail->banner_paragraph !!}">
        @endif
        @if(route('merchant.aboutus', $merchant->display_url) == url()->current())
        <title>About Us | {{ucwords($merchant->company_name)}}</title>
        <meta name="description" content="{!!str_replace('%INDUSTRY_NAME%', $industry_name, str_replace('%NO_OF_CUSTOMER%', $merchant->customer_count, str_replace('%NO_OF_EMPLOYEE%', $merchant->employee_count,str_replace('%COMPANY_NAME%', ucwords($merchant->company_name), $description))))!!}">
        @endif
        @if(route('merchant.contactus', $merchant->display_url) == url()->current())
        <title>Contact Us | {{ucwords($merchant->company_name)}}</title>
        <meta name="description" content="You can contact {{ucfirst($merchant->company_name)}} using  the get in touch form.">
        @endif
        @if(route('mpages.policies', $merchant->display_url) == url()->current())
        <title>Terms & Conditions | {{ucwords($merchant->company_name)}}</title>
        <meta name="description" content="Please read the terms and conditions carefully as it sets out the terms of doing business.">
        @endif
        @if(route('mpages.paymentlink', $merchant->display_url) == url()->current())
        <title>Direct Pay | {{ucwords($merchant->company_name)}}</title>
        <meta name="description" content="Please use this portal to make direct payment of any custom amount to {{ucfirst($merchant->company_name)}}.">
        @endif
        @if(route('mpages.paymybill', $merchant->display_url) == url()->current())
        <title>Pay your Bill | {{ucwords($merchant->company_name)}}</title>
        <meta name="description" content="{{ucfirst($merchant->company_name)}} customers can search for pending bill by entering their mail id or mobile number.">
        @endif

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;600&display=swap" rel="stylesheet">
        <!-- <link href="{{ asset('assets/admin/layout/css/movingintotailwind.css') }}" rel="stylesheet"> -->
        <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
        <link href="{{asset('css/toastr.css')}}" rel="stylesheet">
        <link href="{{asset('select2/select2.min.css')}}" rel="stylesheet" />
        <!-- Styles -->
        <style>
        .gray-bg-1 {
            background-color: #EAEAEA;
        }
        .gray-bg-2 {
            background-color: #DDDDDD;
        }
        .blue-bg-1 {
            background-color: #1C729F;
        }
        .text-color-1 {
            color: #1C729F;
        }
        .text-color-2 {
            color: #555555;
        }
        .ring-blue-default {
          --tw-ring-opacity: 1;
          --tw-ring-color: #18AEBF;
        }
        .bg-aqua {
            background-color: #18AEBF;
        }
        .chat-btn {
            position: fixed;
            bottom: 15px;
            color: #fff;
            border-radius: 50px;
            text-align: center;
            left: 30px;
            z-index: 1;
        }
        .modal {
            transition: opacity 0.25s ease;
        }
        body.modal-active {
            overflow-x: hidden;
            overflow-y: visible !important;
        }
        #toast-container > .toast-info {
            background-image: none !important;
        }
        </style>
    </head>
    <body>
        @include('mpages.partial.header')
        @yield('content')
        @include('mpages.partial.footer')
        
        <script>
            document.getElementById('hamburger').addEventListener('click', function(){
            let element = document.getElementsByClassName('navbar-list');
            if(element.length >0){
                document.getElementById('navbar-list').classList.remove('hidden');
                document.getElementById('navbar-list').classList.remove('navbar-list');
            }  
            else{
                document.getElementById('navbar-list').classList.add('hidden');
                document.getElementById('navbar-list').classList.add('navbar-list');
            }
          });
        </script>

        <script src="{{asset('js/jquery-1.7.1.min.js')}}"></script>
        <script src="{{asset('js/toastr.js')}}"></script>
        <script src='https://www.google.com/recaptcha/api.js?render={{env('V3_CAPTCHA_CLIENT_ID')}}'></script>
        <script src="{{asset('select2/select2.min.js')}}"></script>
      <script>
        grecaptcha.ready(function () {
        captcha_id = '{{env('V3_CAPTCHA_CLIENT_ID')}}';
        captchaSet();
    });
          function captchaSet() {
              grecaptcha.execute(captcha_id, { action: 'homepage' }).then(function (token) {
                  try {
                      document.getElementById('captchamob').value = token;
                      document.getElementById('captchamob1').value = token;
                  } catch (o) {
                  }
              });
          }
      </script>
        @yield('script')
    </body>
</html>