<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>{$company_name}</title>
        <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1, maximum-scale=1" />
        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
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
                    {if $json.section.home.status==1} <li><a id="s1nav" href="#" class="active">HOME</a></li>{/if}
                {if $json.section.aboutus.status==1}<li><a id="s2nav" href="#">ABOUT US</a></li>{/if}
            {if $json.section.services.status==1}<li><a id="s3nav" href="#">SERVICES</a></li>{/if}
            <li><a  href="{if $mode=='preview'}#{else}{$server_name}/m/{$display_url}/directpay{/if}">DIRECT PAY</a></li>
                {if $is_plan==true}
                <li><a  href="{if $mode=='preview'}#{else}{$server_name}/m/{$display_url}/packages{/if}">PLANS</a></li>
                {/if}
            {if $json.section.project.status==1}<li><a id="s5nav" href="#">PROJECTS</a></li>{/if}
    {if $json.section.contactus.status==1}<li><a id="s6nav" href="#">CONTACT</a></li>{/if}
</ul>
</nav>
</div>
</header>
{if $json.section.home.status==1}
    <section style="background:linear-gradient(0deg,rgba(0,0,0,0.5),rgba(0,0,0,0.5)),url('{$json.content.home.banner.value}');" class="banner-img" id="s1">
        <h1>{if $json.content.home.title.status==1}{$json.content.home.title.value} {/if}</h1>
    {if $json.content.home.caption.status==1}<h5>{$json.content.home.caption.value}</h5>{/if}
</section>
{/if}
{if $json.section.paybill.status==1}
    <section class="info">
        <center >
            <h4>{$json.section.paybill.title}</h4>
            <a href="{$server_name}/m/{$display_url}/paymybills" class="button">PAY NOW</a>
        </center>
    </section>
{/if}
{if $json.section.aboutus.status==1}
    <section class="about-us" id="s2">
        <center>
            <h2 class="sec-ttl">
                <div>{$json.section.aboutus.title}</div>
            </h2>
            <p>{$json.content.aboutus.text.value}</p>
        </center>
    </section>
{/if}
{if $json.section.services.status==1}
    <section class="our-services" id="s3">
        <center>
            <h2 class="sec-ttl">
                <div>{$json.section.services.title}</div>
            </h2>
            <ul class="our-service-list">
                {if $json.content.services.service1.status==1}
                    <li>
                        <div class="icon"><img style="max-height: 42px;max-width: 42px;" src="{$json.content.services.service1.icon}" /></div>
                        <div class="data">
                            {$json.content.services.service1.text}
                        </div>
                    </li>
                {/if}
                {if $json.content.services.service2.status==1}
                    <li>
                        <div class="icon"><img style="max-height: 42px;max-width: 42px;" src="{$json.content.services.service2.icon}" /></div>
                        <div class="data">
                            {$json.content.services.service2.text}
                        </div>
                    </li>
                {/if}
                {if $json.content.services.service3.status==1}
                    <li>
                        <div class="icon"><img style="max-height: 42px;max-width: 42px;" src="{$json.content.services.service3.icon}" /></div>
                        <div class="data">
                            {$json.content.services.service3.text}
                        </div>
                    </li>
                {/if}
            </ul>
            <ul class="our-service-list">
                {if $json.content.services.service4.status==1}
                    <li>
                        <div class="icon"><img style="max-height: 42px;max-width: 42px;" src="{$json.content.services.service4.icon}" /></div>
                        <div class="data">
                            {$json.content.services.service4.text}
                        </div>
                    </li>
                {/if}
                {if $json.content.services.service5.status==1}
                    <li>
                        <div class="icon"><img style="max-height: 42px;max-width: 42px;" src="{$json.content.services.service5.icon}" /></div>
                        <div class="data">
                            {$json.content.services.service5.text}
                        </div>
                    </li>
                {/if}
                {if $json.content.services.service6.status==1}
                    <li>
                        <div class="icon"><img style="max-height: 42px;max-width: 42px;" src="{$json.content.services.service6.icon}" /></div>
                        <div class="data">
                            {$json.content.services.service6.text}
                        </div>
                    </li>
                {/if}
            </ul>
        </center>
    </section>
{/if}
{if $json.section.team.status==1}
    <section class="the-team">
        <center>
            <h2 class="sec-ttl">
                <div>{$json.section.team.title}</div>
            </h2>
            <ul class="team-list">
                {if $json.content.team.member1.status==1}
                    <li>
                        <div class="img-box" style="min-height:320px;"><img style="max-height: 320px;max-width: 320px;" src="{$json.content.team.member1.photo}" /></div>
                        <div class="data">
                            {$json.content.team.member1.text}
                        </div>
                    </li>
                {/if}
                {if $json.content.team.member2.status==1}
                    <li>
                        <div class="img-box"  style="min-height:320px;"><img style="max-height: 320px;max-width: 320px;" src="{$json.content.team.member2.photo}" /></div>
                        <div class="data">
                            {$json.content.team.member2.text}
                        </div>
                    </li>
                {/if}
                {if $json.content.team.member3.status==1}
                    <li>
                        <div class="img-box" style="min-height:320px;"><img style="max-height: 320px;max-width: 320px;" src="{$json.content.team.member3.photo}" /></div>
                        <div class="data">
                            {$json.content.team.member3.text}
                        </div>
                    </li>
                {/if}
            </ul>
        </center>
    </section>
{/if}
{if $json.section.testimonial.status==1}
    <section class="testimonial">
        <center>
            <h2 class="sec-ttl">
                <div>{$json.section.testimonial.title}</div>
            </h2>
            <ul class="testimonial-list">
                {if $json.content.testimonial.message1.status==1}
                    <li>
                        <div class="img-box"><img style="max-height: 150px;max-width: 150px;" src="{$json.content.testimonial.message1.photo}" /></div>
                        <div class="data">
                            {$json.content.testimonial.message1.text} 
                        </div>
                    </li>
                {/if}
                {if $json.content.testimonial.message2.status==1}
                    <li>
                        <div class="img-box"><img style="max-height: 150px;max-width: 150px;" src="{$json.content.testimonial.message2.photo}" /></div>
                        <div class="data">
                            {$json.content.testimonial.message2.text} 
                        </div>
                    </li>
                {/if}
                {if $json.content.testimonial.message3.status==1}
                    <li>
                        <div class="img-box"><img style="max-height: 150px;max-width: 150px;" src="{$json.content.testimonial.message3.photo}" /></div>
                        <div class="data">
                            {$json.content.testimonial.message3.text} 
                        </div>
                    </li>
                {/if}
            </ul>
        </center>
    </section>
{/if}
{if $json.section.project.status==1}
    <section class="our-projects" id="s5">
        <center>
            <h2 class="sec-ttl">
                <div>{$json.section.project.title}</div>
            </h2>
            <ul class="our-projects-list">
                {if $json.content.project.project1.status==1}
                    <li><a href="#"><img style="max-height: 320px;max-width: 340px;min-height: 320px;min-width: 340px;"  src="{$json.content.project.project1.image}" /><span>{$json.content.project.project1.text}</span></a></li>
                            {/if}
                            {if $json.content.project.project2.status==1}
                    <li><a href="#"><img style="max-height: 320px;max-width: 340px;min-height: 320px;min-width: 340px;"  src="{$json.content.project.project2.image}" /><span>{$json.content.project.project2.text}</span></a></li>
                            {/if}
                            {if $json.content.project.project3.status==1}
                    <li><a href="#"><img style="max-height: 320px;max-width: 340px;min-height: 320px;min-width: 340px;"  src="{$json.content.project.project3.image}" /><span>{$json.content.project.project3.text}</span></a></li>
                            {/if}
                            {if $json.content.project.project4.status==1}
                    <li><a href="#"><img style="max-height: 320px;max-width: 340px;min-height: 320px;min-width: 340px;"  src="{$json.content.project.project4.image}" /><span>{$json.content.project.project4.text}</span></a></li>
                            {/if}
                            {if $json.content.project.project5.status==1}
                    <li class="large"><a href="#"><img style="max-height: 320px;max-width: 690px;min-height: 320px;min-width: 690px;"  src="{$json.content.project.project5.image}" /><span>{$json.content.project.project5.text}</span></a></li>
                            {/if}
                            {if $json.content.project.project6.status==1}
                    <li class="large"><a href="#"><img style="max-height: 320px;max-width: 690px;min-height: 320px;min-width: 690px;"  src="{$json.content.project.project6.image}" /><span>{$json.content.project.project6.text}</span></a></li>
                            {/if}
                            {if $json.content.project.project7.status==1}
                    <li><a href="#"><img style="max-height: 320px;max-width: 340px;min-height: 320px;min-width: 340px;"  src="{$json.content.project.project7.image}" /><span>{$json.content.project.project7.text}</span></a></li>
                            {/if}
            </ul>
        </center>
    </section>
{/if}
<section class="contact-us" id="s6">
    <center>
        <h2 class="sec-ttl">
            <div>{$json.section.contactus.title}</div>
        </h2>
        {if $json.content.contactus.enquiry.status==1}
            <div class="contact-form-wrapp">
                <h6>SEND US AN ENQUIRY</h6>
                <iframe style="width: 100%;height: 480px;" src="{$server_name}/helpdesk/contactus/{$merchant_link}"></iframe>
            </div>
        {/if}
        {if $json.content.contactus.contact.status==1}
            <div class="data">
                <h6>ADDRESS</h6>
                <p>{$json.content.contactus.contact.address.text}</p>
                <h6>Phone</h6>
                <p>{$json.content.contactus.contact.phone.text}</p>
                <h6>ADDRESS</h6>
                <p><a href="mailto:{$json.content.contactus.contact.email.text}">{$json.content.contactus.contact.email.text}</a></p>
            </div>
        {/if}
    </center>
</section>
<footer>
    <ul class="f-nav">
    {if $json.content.footer.link.terms.status==1}<li><a href="terms">Terms & Condition</a></li>{/if}
{if $json.content.footer.link.cancellation.status==1}<li><a href="cancellation">Cancellation Policy</a></li>{/if}
{if $json.content.footer.link.disclaimer.status==1}<li><a href="disclaimer">Disclaimer</a></li>{/if}
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
