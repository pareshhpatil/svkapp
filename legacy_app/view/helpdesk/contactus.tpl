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
        <script src='https://www.google.com/recaptcha/api.js'></script>
        <!--[if lt IE 9]>
            <script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
    </head>

    <body>
        <section class="contact-us" style="padding: 0px 0px;" id="s6">
            <center>
                <form action="{$server_name}/helpdesk/sendenquiry" method="post">
                    <ul class="contact-form">
                        {if $haserrors!=''}
                            <li >
                                <span style="color:red;">{$haserrors}</span>
                            </li>
                        {/if}
                        {if $success!=''}
                            <li >
                                <span style="color:limegreen;">{$success}</span>
                            </li>
                        {/if}
                        <li>
                            <input type="text" required maxlength="70" name="name" class="text" placeholder="Name"/>
                        </li>
                        <li>
                            <input type="email" required name="email" class="text" placeholder="Email"/>
                        </li>
                        <li>
                            <input type="text" required aria-required=”true” title="Enter your valid mobile number" maxlength="12" {$validate.mobile} name="mobile" class="text" placeholder="Mobile"/>
                        </li>
                        <li>
                            <input type="text" required maxlength="500" name="message" class="text" placeholder="Message"/>
                        </li>
                        <li>
                            <form id="comment_form" action="form.php" method="post">
                                <div class="g-recaptcha" data-sitekey="{$capcha_key}"></div>
                            </form>
                        </li>
                        <li>
                            <input type="hidden" name="link" value="{$link}"/>
                            <input type="submit" class="button" value="Send"/>
                        </li>
                    </ul>
                </form>

            </center>
        </section>

    </body>
</html>
