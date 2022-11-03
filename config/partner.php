<?php

$partners['trade_india']['app_url'] = env('TRADE_INDIA_URL');
$partners['trade_india']['login_url'] = 'https://www.tradeindia.com/my-tradeindia';
$partners['trade_india']['dashboard_url'] = 'https://www.tradeindia.com/my-tradeindia';
$partners['trade_india']['home_url'] = 'https://www.tradeindia.com';
$partners['trade_india']['support_email'] = 'helpdesk@tradeindia.com';
$partners['trade_india']['support_person_name'] = '';
$partners['trade_india']['support_contact'] = '+91 8882583020';
$partners['trade_india']['app_name'] = 'Trade India';
$partners['trade_india']['lhs_menu'] = 'tradeindia_menu';
$partners['trade_india']['logo'] = 'https://tiimg.tistatic.com/new_website1/ti-design-2017/images/tradeindiaLogo.png';
$partners['trade_india']['header_menu'] = 'tradeindia_header';
$partners['trade_india']['header_files'] = array('<link rel="stylesheet" href="/assets/admin/layout/css/trade_india_common.css" />', '<link rel="stylesheet" href="/assets/admin/layout/css/trade_india_home.css" />');
$partners['trade_india']['footer_code'] = '<script type="text/javascript"> $(function(){ $(' . "'#sidebar-nav li .dropdown').on('click',function(event){      $(this).parent().find('.sidebar-sub-nav').toggle(200);   $(this).parent().siblings().find('.sidebar-sub-nav').hide(200);   $(this).parent().find('.sidebar-sub-nav').mouseleave(function(){     var thisUI = $(this);     $('html').click(function(){       thisUI.hide();       $('html').unbind('click');     });   }); }); }); if($(window).width() <= 768){ 	$(document).ready(function(){ 	" . '	$(".collapse-icon-bg").click(function(){ $(".main-collaps-container").toggleClass("side-bar-collapsed"); $(".main-collaps-container").toggleClass("nav-tab-defult"); }); }); }; </script>';

return $partners;
?>