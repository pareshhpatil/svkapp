<!doctype html>
<html>

<head>
    @if(env('APP_ENV','LOCAL')=='PROD')
    @isset($new_registered)
    <script>
        window.dataLayer = window.dataLayer || [];
        dataLayer.push({'registered': 'Yes'});
        </script>
        @endisset

    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src = 'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-KJ2KHBQ');
    </script>
    @endif
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="{{ asset('js/app.js') }}{{ Helpers::fileTime('custom','js/app.js') }}" defer></script>
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.js"></script> -->
    <script src="{{asset('assets/global/plugins/jquery.min.js')}}"></script>
    <script src="{{asset('select2/select2.min.js')}}"></script>
    
    @php if(isset($javascript)){ @endphp
    @foreach($javascript as $js)
    <script src="/assets/admin/layout/scripts/{{$js}}.js{{ Helpers::fileTime('js',$js) }}" type="text/javascript">
    </script>
    @endforeach
    @php } @endphp

    <link href="{{ asset('css/app.css') }}{{ Helpers::fileTime('custom','css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('select2/select2.min.css') }}" rel="stylesheet" />
    
    <style>
        .select2-results__option {
            padding: 0px !important;
            padding-left:10px !important;
            font-size: 0.9rem !important;
        }
        .select2-search__field{
            border-radius: 6px;
            --tw-ring-color: rgba(24, 174, 191) !important;
        }
        .select2-selection__arrow{
            margin-top: 9px;
            margin-right: 7px;
        }
        .selectize-input{
            padding-top: 9px;
            padding-bottom: 9px;
            border-radius: 5px;
        }
        .select2-selection{
            height: 40px;
            border-radius: 5px;
            border-color: rgba(209, 213, 219);
        }
        #select2-location2-container{
            padding-top: 6px;
            padding-left: 12px;
            font-size: 14px;
        }
        .select2-selection__rendered{
            font-size:0.9rem;
            padding-top:5px;
            margin-left:3px;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice{
    border: 1px solid #18aebf;
    background-color: transparent;
}

.select2-container--default .select2-selection--multiple .select2-selection__choice__remove{
    color: #18aebf;
    border-right: 1px solid #18aebf;
}
.select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover{
    color: #18aebf;
}
.select2-container--default .select2-selection--multiple .select2-selection__choice__display{
    color: #18aebf;
}
    </style>
    <title>{{$title}}</title>

</head>

<body>
    @if(env('APP_ENV','LOCAL')=='PROD')
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KJ2KHBQ" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    @endif

    @yield('content')
</body>
@if(env('IS_FULLSTORY_ACTIVE')==1)
<script>
window['_fs_debug'] = false;
window['_fs_host'] = 'fullstory.com';
window['_fs_script'] = 'edge.fullstory.com/s/fs.js';
window['_fs_org'] = 'B8KTC';
window['_fs_namespace'] = 'FS';
(function(m,n,e,t,l,o,g,y){
    if (e in m) {if(m.console && m.console.log) { m.console.log('FullStory namespace conflict. Please set window["_fs_namespace"].');} return;}
    g=m[e]=function(a,b,s){g.q?g.q.push([a,b,s]):g._api(a,b,s);};g.q=[];
    o=n.createElement(t);o.async=1;o.crossOrigin='anonymous';o.src='https://'+_fs_script;
    y=n.getElementsByTagName(t)[0];y.parentNode.insertBefore(o,y);
    g.identify=function(i,v,s){g(l,{uid:i},s);if(v)g(l,v,s)};g.setUserVars=function(v,s){g(l,v,s)};g.event=function(i,v,s){g('event',{n:i,p:v},s)};
    g.anonymize=function(){g.identify(!!0)};
    g.shutdown=function(){g("rec",!1)};g.restart=function(){g("rec",!0)};
    g.log = function(a,b){g("log",[a,b])};
    g.consent=function(a){g("consent",!arguments.length||a)};
    g.identifyAccount=function(i,v){o='account';v=v||{};v.acctId=i;g(o,v)};
    g.clearUserCookie=function(){};
    g.setVars=function(n, p){g('setVars',[n,p]);};
    g._w={};y='XMLHttpRequest';g._w[y]=m[y];y='fetch';g._w[y]=m[y];
    if(m[y])m[y]=function(){return g._w[y].apply(this,arguments)};
    g._v="1.3.0";
})(window,document,window['_fs_namespace'],'script','user');
</script>
@endif

<!-- Smart look script --> 
@if(env('IS_SMART_LOOK_ACTIVE')==1 && $hide_smartlook_for_patron==true)
    <script type='text/javascript'>
        window.smartlook||(function(d) {
        var o=smartlook=function(){ o.api.push(arguments)},h=d.getElementsByTagName('head')[0];
        var c=d.createElement('script');o.api=new Array();c.async=true;c.type='text/javascript';
        c.charset='utf-8';c.src='https://rec.smartlook.com/recorder.js';h.appendChild(c);
        })(document);
        smartlook('init', '936cedeb2f54186e4d1b006dc4bcc0395e854ecf');
  </script>
@endif
<!-- end smart look script -->

</html>