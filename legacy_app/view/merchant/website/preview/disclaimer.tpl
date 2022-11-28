<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>{$company_name}</title>
        <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1, maximum-scale=1" />
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet">
        <script type="text/javascript" src="{$cloud_front}/live/js/jquery-1.9.1.min.js"></script> 
        <script type="text/javascript" src="{$cloud_front}/live/js/site.inc.js"></script>
        <script type="text/javascript" src="{$cloud_front}/live/js/jquery.scrollTo.min.js"></script>
        <link href="{$cloud_front}/live/css/animate.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="{$cloud_front}/live/css/style.css"/>
        <link rel="stylesheet" href="{$cloud_front}/live/css/device.css"/>
        <!--[if lt IE 9]>
            <script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
    </head>

    <body>
        <header style="padding: 10px;">
            <div class="header-inside"> <a hre="#" class="logo"><img id="logo_image" style="max-height: 50px;max-width: 200px;" src="{$json.logo.image}" /></a><a href="javascript:void(0)" class="menu-icon"></a>
                <nav>
                    <ul style="padding-top: 10px;" class="main-nav">
                       {if $json.section.home.status==1} <li><a  href="{if $mode=='preview'}/merchant/website/preview/home{else}/{/if}" class="active">HOME</a></li>{/if}
                        {if $json.section.aboutus.status==1}<li><a href="{if $mode=='preview'}/merchant/website/preview/home{else}/{/if}">ABOUT US</a></li>{/if}
                        {if $json.section.services.status==1}<li><a  href="{if $mode=='preview'}/merchant/website/preview/home{else}/{/if}">SERVICES</a></li>{/if}
                        <li><a  href="{if $mode=='preview'}#{else}{$server_name}/m/{$display_url}/directpay{/if}">DIRECT PAY</a></li>
                        {if $is_plan==true}
                        <li><a  href="{if $mode=='preview'}#{else}{$server_name}/m/{$display_url}/packages{/if}">PLANS</a></li>
                        {/if}
                        {if $json.section.project.status==1}<li><a id="s5nav" href="{if $mode=='preview'}/merchant/website/preview/home{else}/{/if}">PROJECTS</a></li>{/if}
                        {if $json.section.contactus.status==1}<li><a  href="{if $mode=='preview'}/merchant/website/preview/home{else}/{/if}">CONTACT</a></li>{/if}
                    </ul>
                </nav>
            </div>
        </header>
        <section class="editor-data" style="min-height: 620px;">
            <center>
                <h2 class="sec-ttl">
                    <div>{$json.content.disclaimer.title.value}</div>
                </h2>
                {$json.content.disclaimer.text.value}
            </center>
        </section>
        <footer>
                    <ul class="f-nav">
                    {if $json.content.footer.link.terms.status==1}<li><a href="/terms">Terms & Condition</a></li>{/if}
                {if $json.content.footer.link.cancellation.status==1}<li><a href="/cancellation">Cancellation Policy</a></li>{/if}
            {if $json.content.footer.link.disclaimer.status==1}<li><a href="/disclaimer">Disclaimer</a></li>{/if}
            </ul>
<div class="social"> 
{if $json.content.footer.social.facebook.status==1}<a target="_BLANK" href="{$json.content.footer.social.facebook.link}"><img src="{$cloud_front}/live/images/fb.png" /></a>{/if} 
{if $json.content.footer.social.twitter.status==1}<a target="_BLANK" href="{$json.content.footer.social.twitter.link}"><img src="{$cloud_front}/live/images/tw.png" /></a>{/if}
{if $json.content.footer.social.linkedin.status==1} <a target="_BLANK" href="{$json.content.footer.social.linkedin.link}"><img src="{$cloud_front}/live/images/in.png" /></a> {/if}
{if $json.content.footer.social.youtube.status==1}<a target="_BLANK" href="{$json.content.footer.social.youtube.link}"><img src="{$cloud_front}/live/images/yt.png" /></a> {/if}
{if $json.content.footer.social.instagram.status==1}<a target="_BLANK" href="{$json.content.footer.social.instagram.link}"><img src="{$cloud_front}/live/images/st.png" /></a> {/if}
</div>
<p class="copy">{$json.content.footer.text.value}</p>
<p style="color:#ffffff;font-size:11px;"><a target="_BLANK" style="color:#ffffff;font-size:11px;" href="https://www.swipez.in/billing-software">Billing software</a> powered by <a target="_BLANK" style="color:#ffffff;font-size:11px;" href="https://www.swipez.in">Swipez</a></p>
</footer>
    </body>
</html>
