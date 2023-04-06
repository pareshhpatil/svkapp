<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Swipez website builder</title>
        <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1, maximum-scale=1" />
        <link rel="shortcut icon" href="/images/briq.ico" type="image/x-icon">
        <script type="text/javascript" src="/assets/site-builder/build/js/jquery-1.9.1.min.js"></script>
        <script type="text/javascript" src="/assets/site-builder/build/js/site.inc.js"></script>
        <script type="text/javascript" src="/assets/site-builder/build/js/jquery.scrollTo.min.js"></script>
        <link href="/assets/site-builder/build/css/animate.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="/assets/site-builder/build/css/style.css"/>
        <link rel="stylesheet" href="/assets/site-builder/build/css/edit-theme.css"/>
        <link rel="stylesheet" href="/assets/site-builder/build/css/device.css"/>
        <!--[if lt IE 9]>
            <script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
    </head>
    <body> 

        <div class="main-wrapper">
            <div class="main-wrapper-inside">

                <section class="editor-data" >
                    <center>
                        <h2 class="sec-ttl"><div class="btnEditBox"><button onclick="display_text('content_terms_title_value');" class="btnEditText"><i class="fa fa-text-width"></i> Edit Text</button></div>
                            <div id="content_terms_title_value">{$json.content.terms.title.value}</div>
                        </h2>
                        
                    </center>
                </section>


            </div>
        </div>
    </body>
</html>