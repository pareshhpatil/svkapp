<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Swipez website builder</title>
        <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1, maximum-scale=1" />
        <link rel="shortcut icon" href="/images/briq.ico" type="image/x-icon">
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" />
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet">
        <script type="text/javascript" src="/assets/site-builder/build/js/jquery-1.9.1.min.js"></script>
        <script type="text/javascript" src="/assets/site-builder/build/js/site.inc.js"></script>
        <script type="text/javascript" src="/assets/site-builder/build/js/jquery.scrollTo.min.js"></script>
        <link href="/assets/site-builder/build/css/animate.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="/assets/site-builder/build/css/style.css"/>
        <link rel="stylesheet" href="/assets/site-builder/build/css/edit-theme.css"/>
        <link rel="stylesheet" href="/assets/site-builder/build/css/device.css"/>
        <script type="text/javascript" src="/assets/site-builder/build/js/my_jquery.js"></script>
        <script type="text/javascript" src="/assets/site-builder/build/js/tinymce/tinymce.min.js"></script>
        <!--[if lt IE 9]>
            <script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
    </head>
    <body> 
        <div id="publishpopup" class="editable-popup edit_company_logo">
            <div class="editable-popup-inside" style="text-align: center;">
                <img style="float: inherit;" src="/assets/admin/layout/img/gears.gif">
                <br>
                <br>
                <h4 style="font-size: 20px;">Please wait while we build your website</h4><br>
                <h4 style="font-size: 16px;">
                    Do not refresh or close your browser<br/>
                </h4>
            </div>
        </div>
        <div id="imagepopup" class="editable-popup edit_company_logo">
            <div class="editable-popup-inside">
                <a id="close_company_logo" style="cursor: pointer;" onclick="closepopup('image');" class="btn-close"><i class="fa fa-remove"></i></a>
                <ul class="form-editable">
                    <form id="image_form" enctype="multipart/form-data" name="uploader" method="POST">
                        <li><label id="image_info" style="color: red;">Image Upload</label></li>
                        <li><label></label>
                            <input type="file" class="btnFile" id="fileupload" accept="image/*"  name="uploaded_file"></li> 
                        <li><input id="imageinputpath" name="path" type="hidden" class="text" />
                            <input id="maxupload" type="hidden" class="text" />
                        </li>
                        <li><a href="#" onclick="return uploadfile();" class="button">Save</a></li>
                        <h7 id="status"></h7>
                        <p id="loaded_n_total"></p>
                    </form>                  
                </ul>
            </div>
        </div>

        <div id="statuspopup" class="editable-popup">
            <div class="editable-popup-inside">
                <a id="close_company_header" style="cursor: pointer;" class="btn-close" onclick="closepopup('status');"><i class="fa fa-remove"></i></a>
                <form id="status_form" method="POST">
                    <ul class="form-editable">
                        <br>
                        <li><label id="status_text">Are you sure change t</label>
                            <input id="statusinput" name="value" type="hidden" class="text" />
                            <input id="statusinputpath" name="path" type="hidden" class="text" />
                        </li>                  
                        <li><input type="submit" style="background: #21c7d5;" onclick="return saveform('status');" value="Yes" class="button">
                            <input type="submit" onclick="return closepopup('status');" value="No" class="button"></li>
                        <li></li>
                    </ul>
                </form>
            </div>
        </div>

        <div id="textpopup" class="editable-popup">
            <div class="editable-popup-inside">
                <a id="close_company_header" style="cursor: pointer;" class="btn-close" onclick="closepopup('text');"><i class="fa fa-remove"></i></a>
                <form id="text_form" method="POST">
                    <ul class="form-editable">
                        <li><label>Enter text</label>
                            <input id="textinput" name="value" type="text" class="text" />
                            <input id="textinputpath" name="path" type="hidden" class="text" />
                            <input name="site_builder" type="hidden" value="website" class="text" />
                        </li>                  
                        <li><input type="submit" onclick="return saveform('text');" value="Save" class="button"></li>
                    </ul>
                </form>
            </div>
        </div>

        <div id="textareapopup"  class="editable-popup abot_description_pop">
            <div class="editable-popup-inside" style="width: 800px;">
                <a id="close_company_header" class="btn-close" onclick="closepopup('textarea');"><i class="fa fa-remove"></i></a>
                <form id="textarea_form" method="POST">
                    <ul class="form-editable">
                        <li id="abccc"><label>Description here</label></li>                    
                        <li><textarea id="description_textarea"></textarea></li>
                        <input id="textareainputpath" name="path" type="hidden" class="text" />
                        <input name="site_builder" type="hidden" value="website" class="text" />
                        <input name="value" id="textareainput" type="hidden" class="text" />
                        <li><input type="submit" onclick="return saveform('textarea');" value="Save" class="button"></li>
                    </ul>
                </form>
            </div>
        </div>

        <div class="main-sidebar" id="sidebar">
            <a onclick="window.history.back();" href="" class="btn-back">
                <i class="fa fa-arrow-left"></i> Back
            </a>

            <ul class="sidebar-nav">
                <li><a href="/merchant/website/build" {if $type=='home'} class="active" {/if}>Home</a></li>
                <li><a href="/merchant/website/build/terms" {if $type=='terms'} class="active" {/if}>Terms</a></li>
                <li><a href="/merchant/website/build/cancellation" {if $type=='cancellation'} class="active" {/if}>Cancellation</a></li>
                <li><a href="/merchant/website/build/disclaimer" {if $type=='disclaimer'} class="active" {/if}>Disclaimer</a></li>
            </ul>
        </div>
        <div class="main-topbar-wrapp">
            <div class="main-topbar">
                <h1><a href="/merchant/dashboard">Dashboard</a></h1> <div class="btns"><a href="/merchant/website/preview/{$type}" target="_BLANK" class="button preview">Preview</a>
                    {if $status==2}
                        <a href="/merchant/website/publish/{$type}" onclick=" $('#sidebar').css('display', 'none');
                        $('#publishpopup').css('display', 'block');" class="button publish">Publish</a>
                    {/if}
                </div>
            </div> 
        </div> 
        <div class="main-wrapper">
            <div class="main-wrapper-inside">
                <header style="padding: 10px;">
                    <div class="header-inside"> <a  class="logo"><div class="btnEditBox"><button onclick="display_image('logo_image', '{$json.logo.info}', '{$json.logo.max}');" com_logo_id="22" id="edit_show_com_logo" class="btnEditImg"><i class="fa fa-edit"></i> Edit Image</button></div><img style="max-height: 50px;" id="logo_image" src="{$json.logo.image}" /></a><a href="javascript:void(0)" class="menu-icon"></a>
                        <nav >
                            <ul style="padding-top: 10px;" class="main-nav"><div class="btnEditBox"><!--<button id="header_text_name" class="btnEditImg"><i class="fa fa-text-width"></i> Edit Nav</button>--></div>
                                <li><a id="s1nav" href="#" class="active">HOME</a></li>
                                <li><a id="s2nav" href="#">ABOUT US</a></li>
                                <li><a id="s3nav" href="#">SERVICES</a></li>
                                <li><a id="s6nav" href="#">CONTACT</a></li>
                            </ul>
                        </nav>
                    </div>
                </header>