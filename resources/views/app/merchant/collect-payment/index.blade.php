@extends('app.master')



<style>
    #ul {
        background: white;
    }

     .a1 {
        color: black;
    }


    .toggle-button:hover,
    .toggle-button:active,
    .toggle-button:focus,
    .toggle-button:visited {
        text-decoration: none;

    }

    #title_8 {
        font-family: Roboto;
        font-style: normal;
        font-weight: 400;
        font-size: 18px;
        line-height: 21px;

        color: #394242;
    }

    .apps-description1 {
        font-family: Roboto;
        font-style: normal;
        font-weight: 400;
        font-size: 14px;
        line-height: 17px;

        color: #5C6B6B;
    }
  
      
    .card2 {
       
        
          border: 0.1px solid #fff;
         
      }
    .card2:hover {
        transition: all .1s ease-in-out;
          box-shadow: 0px 4px 8px rgba(43, 28, 28, 0.2);
          border: 0.1px solid #cccccc;
         
      }
</style>
@section('content')
<div class="page-content">
    <div class="page-bar">
        <span class="page-title" style="float: left;">{{$title}}</span>
        {{ Breadcrumbs::render() }}
    </div>
    <div class="loading" id="loader" style="display: none;">Loading&#8230;</div>



    @foreach ($page_data as $item)

    <h4 style="color: #abaaaa; padding-left: 10px; margin-bottom: 0px; margin-top: 20px;">{{ $item['name']}}</h4>
    <div class="row justify-content-md-center flex-container ">
        @foreach ($item['item_list'] as $item2)
        <a   href="{{$item2['link']}}" data-tour="collect-payments-{{str_replace(' ','-',strtolower($item2['title']))}}">
        <div class="col-xs-12 col-sm-6 col-md-4 mb-1 flex-item">
       
            <div class="card2 box-plugin apps-shadow  mr-1"
                style="background-color: #ffffff; padding-left: 3px; padding-right: 5px;">
                <div class="apps-box">
                    <div class="row no-margin">
                        <div class="col-xs-12">
                            <h4 class="mb-1" id="title_8">{{$item2['title']}}
                            </h4>

                        </div>

                    </div>
                    <div class="row no-margin">

                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12 apps-description1">{{$item2['desc']}} </div>

                            </div>

                        </div>

                    </div>





                </div>
                <br><br>
            </div>

            <a href="{{$item2['link']}}" data-tour="collect-payments-{{str_replace(' ','-',strtolower($item2['title']))}}" class=" pull-right mb-1 ml-1" style="margin-top: -30px;
        margin-right: 20px;"><svg style="width: 25px;
            height: 25px;" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg></a>
           
        </div></a>
        


        @endforeach
    </div>
    @endforeach




    <x-menu-button :menuList="$menu_list" />




</div>

@isset($groove)
    <!-- BEGIN GROOVE WIDGET CODE -->
<script>

!function(e,t){if(!e.groove){var i=function(e,t){return Array.prototype.slice.call(e,t)},a={widget:null,loadedWidgets:{},classes:{Shim:null,Embeddable:function(){this._beforeLoadCallQueue=[],this.shim=null,this.finalized=!1;var e=function(e){var t=i(arguments,1);if(this.finalized){if(!this[e])throw new TypeError(e+"() is not a valid widget method");this[e].apply(this,t)}else this._beforeLoadCallQueue.push([e,t])};this.initializeShim=function(){a.classes.Shim&&(this.shim=new a.classes.Shim(this))},this.exec=e,this.init=function(){e.apply(this,["init"].concat(i(arguments,0))),this.initializeShim()},this.onShimScriptLoad=this.initializeShim.bind(this),this.onload=void 0}},scriptLoader:{callbacks:{},states:{},load:function(e,i){if("pending"!==this.states[e]){this.states[e]="pending";var a=t.createElement("script");a.id=e,a.type="text/javascript",a.async=!0,a.src=i;var s=this;a.addEventListener("load",(function(){s.states[e]="completed",(s.callbacks[e]||[]).forEach((function(e){e()}))}),!1);var n=t.getElementsByTagName("script")[0];n.parentNode.insertBefore(a,n)}},addListener:function(e,t){"completed"!==this.states[e]?(this.callbacks[e]||(this.callbacks[e]=[]),this.callbacks[e].push(t)):t()}},createEmbeddable:function(){var t=new a.classes.Embeddable;return e.Proxy?new Proxy(t,{get:function(e,t){return e instanceof a.classes.Embeddable?Object.prototype.hasOwnProperty.call(e,t)||"onload"===t?e[t]:function(){e.exec.apply(e,[t].concat(i(arguments,0)))}:e[t]}}):t},createWidget:function(){var e=a.createEmbeddable();return a.scriptLoader.load("groove-script","https://d9c27c88-adc0-4923-a7e8-2ef0b33493f5.widget.cluster.groovehq.com/api/loader"),a.scriptLoader.addListener("groove-iframe-shim-loader",e.onShimScriptLoad),e}};e.groove=a}}(window,document);

window.groove.widget = window.groove.createWidget();

/* Contact Email */
window.groove.widget.identifyContact('contact_email', '{{$talk_email_id}}');
window.groove.widget.identifyCompany('company_name', '{{$company_name}}');

window.groove.widget.setContact({
  contact_first_name: '{{$first_name}}',
  contact_last_name: '{{$last_name}}',
  contact_phone_number:'{{$mobile}}'  
});

window.groove.widget.init('d9c27c88-adc0-4923-a7e8-2ef0b33493f5', {});

</script>

<!-- END GROOVE WIDGET CODE -->
@endisset

@endsection