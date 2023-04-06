<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>{$company_name}</title>
        <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1, maximum-scale=1" />
        <link rel="shortcut icon" href="/images/briq.ico" type="image/x-icon">
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
        select.text{
            float:left; width:100%; height:44px; padding:0px 15px; margin:0px; border:1px solid #e0e0e0; background-color:#fff; font-size:15px;  border-radius:0px;  color:#7f7f7f; font-family:'Raleway', sans-serif; display:inline-block; -webkit-transition:0.2s ease-out; -moz-transition:0.2s ease-out; -o-transition:0.2s ease-out; -transition:0.2s ease-out;

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
    <section class="contact-us" style="padding: 0px 20px;background-color:#ffffff;width:100%;text-align:left;" id="s6">
    {if $haserrors!=''}
        <span style="color:red;text-align:left;">{$haserrors}</span>
    {/if}
    {if $success!=''}
        <span style="color:green;text-align:left;">{$success}</span>
    {/if}
    </section>
    <form action="{$server_name}/helpdesk/partner" method="post">
        <section class="contact-us" style="padding: 0px 0px;background-color:#ffffff;width:50%;float:left;" id="s6">
            <center>
                    <ul class="contact-form">
                        
                        <li>
                            <input type="text" value="{$post.company_name}" maxlength="70" name="company_name" class="form-control text" placeholder="Company name (If applicable)"/>
                        </li>
                        <li>
                            <select name="type" required="" class="form-control text" data-placeholder="Select...">
                            <option value="Individual">Individual</option>
                            <option value="Proprietor">Proprietor</option>
                            <option value="LLP">LLP (Partnership)</option>
                            <option value="Pvt Ltd">Pvt. Ltd.</option>
                            </select>
                        </li>
                        <li>
                            <input type="text" value="{$post.name}" required name="name" class="text" placeholder="Name *"/>
                        </li>
                        <li>
                            <input type="email" value="{$post.email}" required name="email" class="text" placeholder="Email ID *"/>
                        </li>
                        <li>
                            <input type="text" value="{$post.mobile}" required aria-required=”true” title="Enter your valid mobile number" maxlength="12" {$validate.mobile} name="mobile" class="text" placeholder="Mobile *"/>
                        </li>
                        <li>
                            <textarea style="height: 80px;" type="text"  required name="address" class="text" placeholder="Address *">{$post.address}</textarea>
                        </li>
                    </ul>

            </center>
        </section>
        <section class="contact-us" style="padding: 0px 0px;background-color:#ffffff;width:50%;float:right;" id="s6">
            <center>
                
                    <ul class="contact-form">
                        <li>
                            <input type="text" value="{$post.website}" name="website" class="form-control text" placeholder="Website URL (If available?)"/>
                        </li>
                        <li>
                            <textarea style="height: 80px;" type="text"   name="description" class="text" placeholder="Brief Description about your Company / Line of Business (optional)">{$post.description}</textarea>
                        </li>
                        <li>
                            <input type="number" step="1" value="{$post.team_size}" required name="team_size" class="text" placeholder="Size of team *"/>
                        </li>
                        <li>
                            <textarea style="height: 80px;" type="text"   name="other_product" class="text" placeholder="Representing other products? Name them if any">{$post.other_product}</textarea>
                        </li>
                        
                        <li >
                                <div class="g-recaptcha" data-sitekey="{$capcha_key}"></div>
                        </li>
                    </ul>
            </center>
        </section>
        <section class="contact-us" style="padding: 0px 0px;background-color:#ffffff;width:100%;text-align:center;" id="s6">
        <input type="submit" style="background-color:#275770;border-radius:5px;" class="button" value="Send Request"/>
        </section>
        </form>
    </body>
</html>
