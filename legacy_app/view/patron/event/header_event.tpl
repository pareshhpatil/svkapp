<!DOCTYPE html>
<html class="no-js" lang="en">

    <head>
        <title>Event registration & ticketing | {$info.event_name}</title>
        <meta name="description" content="Event registration & ticketing portal for {$info.event_name}{if $info.short_description!=''} | {$info.short_description}{/if}">
        <meta name="keywords" content="Event registration for {$info.event_name}, Buy Online ticket for {$info.event_name}, Online ticket for {$info.event_name}">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway:400,800">
        <link rel='stylesheet' href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="/assets/event/css/bootstrap.css">
        <link rel="stylesheet" href="/assets/event/css/style.css">
        <script src="/assets/event/js/eventpage.js?version=5"></script>
        {if isset($global_tag)}
            {$global_tag}
        {/if}
        
        <meta property="fb:app_id" content="1838175649816458" />
        <meta property="og:url" content="{$link}" />
        <meta property="og:title" content="{if $info.title!=''}{$info.title}{else}{$info.event_name}{/if}" />
        <meta property="og:type" content="event" />
        <meta property="og:description" content="{$info.short_description}" />
        {if $info.banner_path!=''}
            <meta property="og:image" content="{$host_link}/uploads/images/logos/{$info.banner_path}" />
        {/if}

        {if $ENV == 'PROD'}
            {literal}
                <!-- Google Code for Remarketing Tag -->
                <!--------------------------------------------------
                Remarketing tags may not be associated with personally identifiable information or placed on pages related to sensitive categories. See more information and instructions on how to setup the tag on: http://google.com/ads/remarketingsetup
                --------------------------------------------------->
                <script type="text/javascript">
                    /* <![CDATA[ */
                    var google_conversion_id = 817807754;
                    var google_custom_params = window.google_tag_params;
                    var google_remarketing_only = true;
                    /* ]]> */
                </script>
                <script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
                </script>
                <noscript>
            <div style="display:inline;">
                <img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/817807754/?guid=ON&amp;script=0"/>
            </div>
            </noscript>
        {/literal}
    {/if}

</head>

<body>
    {if $info.banner_path!=''}
        <div class="jumbotron" style="    padding: 0.5rem 0.5rem;">
            <div class="container text-center" style="padding: 0; max-width: 2000px;">
                <img src="/uploads/images/logos/{$info.banner_path}" class="img-fluid img-resp"
                     alt="Responsive image">
            </div>
        </div>
    {else}
        <br>
    {/if}
    <div class="container-fluid">