<link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet" type="text/css">
<script src="https://apis.google.com/js/api:client.js"></script>
<script>
    var googleUser = {};
    var startApp = function () {
        gapi.load('auth2', function () {
            // Retrieve the singleton for the GoogleAuth library and set up the client.
            auth2 = gapi.auth2.init({
                client_id: '{{$GOOGLE_AUTH_CLIENT_ID}}',
                cookiepolicy: 'single_host_origin',
                // Request scopes in addition to 'profile' and 'email'
                //scope: 'additional_scope'
            });
            attachSignin(document.getElementById('googleBtnweb'));
            attachSignin(document.getElementById('googleBtnmob'));
        });
    };

    function attachSignin(element) {
        auth2.attachClickHandler(element, {},
                function (googleUser) {
                    var id_token = googleUser.getAuthResponse().id_token;
                    validateGoogleToken(id_token);
                }, function (error) {
        });
    }
</script>
<style type="text/css">
    .customGPlusSignIn {
        display: inline-block;
        background: #f8f5f2;
        color: #0a8080 !important;
        width: 100%;
        border-radius: 5px;
        border: 1px solid #eaeaea;
        white-space: nowrap;
        text-align: center;
        padding: 10px;
    }
    .customGPlusSignIn:hover {
        cursor: pointer;
    }
    span.label {
        font-family: serif;
        font-weight: normal;
    }
    span.icon {
        display: inline-block;
        vertical-align: middle;
        width: 42px;
    }
    span.buttonText {
        display: inline-block;
        vertical-align: middle;
        padding-right: 35px;
        font-size: 16px;
        font-weight: bold;
        /* Use the Roboto font that is loaded in the <head> */
        font-family: 'Roboto', sans-serif;
    }
    .img-square-20px{
        width: 20px !important;
        height: 20px !important;
        min-width: 20px !important;
    }
    .btn-primaryc{
        border-color: #f99b36!important;
        font-size: 18px!important;
    }
    .line-text{
        border-bottom: 1px solid #dcdcdc;
        width: 45%;
        margin-top: -10px;
    }
    .float-right{
        float: right;
    }
</style>
@if(isset($captcha))
<script src="https://www.google.com/recaptcha/api.js?render={{env('V3_CAPTCHA_CLIENT_ID')}}"></script>

<script>
    grecaptcha.ready(function () {
        captcha_id = '{{env('V3_CAPTCHA_CLIENT_ID')}}';
        captchaSet();
    });

    setInterval(function() {
            captchaSet();
        }, 2 * 60 * 1000);

</script>
@endif
