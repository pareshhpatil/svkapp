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
        <style>
        input.text, textarea.text{
            font-size:15px;
            border-radius:0px;
        }
        .text-center {
        text-align: center;
    }

    .g-recaptcha {
        display: inline-block;
    }
        </style>
        <!--[if lt IE 9]>
            <script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
    </head>

    <body>
        <section class="contact-us" style="padding: 0px 0px;background-color:#ffffff;" id="s6">
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
                                <span style="color:green;">{$success}</span>
                            </li>
                        {/if}
                        <li>
                            <input type="text" value="{$post.name}" required maxlength="70" name="name" class="form-control text" placeholder="Name"/>
                        </li>
                        <li>
                            <input type="email" value="{$post.email}" required name="email" class="text" placeholder="Email"/>
                        </li>
                        <li>
                            <input type="text" value="{$post.mobile}" required aria-required=”true” title="Enter your valid mobile number" maxlength="12" {$validate.mobile} name="mobile" class="text" placeholder="Mobile"/>
                        </li>
                        <li>
                            <textarea style="height: 80px;" type="text" value="{$post.message}" required name="message" class="text" placeholder="Message"></textarea>
                        </li>
                        <li >
                            <form id="comment_form" action="form.php" method="post">
                                <div class="g-recaptcha" data-sitekey="{$capcha_key}"></div>
                            </form>
                        </li>
                        <li class="text-center">
                            <input type="hidden" name="link" value="swipez"/>
                            <input type="hidden" name="subject" value="{$subject}"/>
                            <input type="submit" style="background-color:#275770;border-radius:5px;" class="button" value="Send"/>
                        </li>
                    </ul>
                </form>

            </center>
        </section>

    </body>
</html>
