var activeFeatureTab = 1;
var activeIndustryTab = 1;
var featuretabs = $('#featureTab > li');
var industrytabs = $('#industryTab > li');
var featuretabLength = featuretabs.length;
var industrytabLength = industrytabs.length;

var delay = 5000;
var featureVar = null;
var industryVar = null;
try {
    //console.log("tabLength : " + tabLength);
    var tabChange = function () {
        var currTab = activeFeatureTab <= featuretabLength ? activeFeatureTab++ : activeFeatureTab = 1;
        $('#featureTab li:nth-child(' + currTab + ') a').tab('show');
    }

    var tabChange2 = function () {
        var currTab = activeIndustryTab <= industrytabLength ? activeIndustryTab++ : activeIndustryTab = 1;
        $('#industryTab li:nth-child(' + currTab + ') a').tab('show');
    }

    var tabFeatureCycle = setInterval(tabChange, delay);
    var tabIndustryCycle = setInterval(tabChange2, delay);

    // Tab click event handler
    $(function () {
        $('#featureTab a').on('click', function (event) {
            event.preventDefault();
            if (featureVar !== undefined) {
                //console.log("myVar is defined")
                clearTimeout(featureVar);
            }
            clearInterval(tabFeatureCycle);

            $(this).tab('show');
            activeFeatureTab = $(this).parent('li').index() + 1;
            tabFeatureCycle = null;

            featureVar = setTimeout(function () {
                //console.log("inside function : " + delay)
                tabFeatureCycle = setInterval(tabChange, delay)
            }, delay);
        });

        $('#industryTab a').on('click', function (event) {
            event.preventDefault();
            if (industryVar !== undefined) {
                //console.log("myVar is defined")
                clearTimeout(industryVar);
            }
            clearInterval(tabIndustryCycle);

            $(this).tab('show');
            activeIndustryTab = $(this).parent('li').index() + 1;
            tabIndustryCycle = null;

            industryVar = setTimeout(function () {
                //console.log("inside function : " + delay)
                tabIndustryCycle = setInterval(tabChange2, delay)
            }, delay);
        });
    })

    function prevFeatureClick() {
        clearInterval(tabFeatureCycle);
        var currTab = activeFeatureTab == 1 ? featuretabLength : --activeFeatureTab;
        activeFeatureTab = currTab;
        $('#featureTab li:nth-child(' + currTab + ') a').tab('show');
    }

    function prevIndustryClick() {
        clearInterval(tabIndustryCycle);
        var currTab = activeIndustryTab == 1 ? industrytabLength : --activeIndustryTab;
        activeIndustryTab = currTab;
        $('#industryTab li:nth-child(' + currTab + ') a').tab('show');
    }

    function nextFeatureClick() {
        clearInterval(tabFeatureCycle);
        var currTab = activeFeatureTab < featuretabLength ? ++activeFeatureTab : activeFeatureTab = 1;
        activeFeatureTab = currTab;
        $('#featureTab li:nth-child(' + currTab + ') a').tab('show');
    }

    function nextIndustryClick() {
        clearInterval(tabIndustryCycle);
        var currTab = activeIndustryTab < industrytabLength ? ++activeIndustryTab : activeIndustryTab = 1;
        activeIndustryTab = currTab;
        $('#industryTab li:nth-child(' + currTab + ') a').tab('show');
    }

    function downloadFile(clickedId) {
        var id = clickedId + 'Excel';
        document.getElementById(id).click();
    }


// data flow js

var _0x576e1b=_0x3052;(function(_0x976218,_0x419e3c){var _0x19c1e8=_0x3052,_0x3bbf66=_0x976218();while(!![]){try{var _0x454c50=parseInt(_0x19c1e8(0x232))/0x1+parseInt(_0x19c1e8(0x25d))/0x2*(parseInt(_0x19c1e8(0x23c))/0x3)+parseInt(_0x19c1e8(0x1f4))/0x4*(parseInt(_0x19c1e8(0x23f))/0x5)+parseInt(_0x19c1e8(0x1de))/0x6+parseInt(_0x19c1e8(0x25a))/0x7+-parseInt(_0x19c1e8(0x24d))/0x8+-parseInt(_0x19c1e8(0x210))/0x9;if(_0x454c50===_0x419e3c)break;else _0x3bbf66['push'](_0x3bbf66['shift']());}catch(_0xc04b46){_0x3bbf66['push'](_0x3bbf66['shift']());}}}(_0x9046,0xa50ed));function _0x9046(){var _0x3b52ac=['\x22\x20class=\x22color_2\x22\x20>','10488915TFJTtL','#_m2','changeText','fadeIn','fadeTo','images/data-flow/quickbooks-logo.png?id=v1','#mainbt','Manage\x20franchise','#main_div3','div_seller','images/data-flow/shopify-logo.png?id=v1','api.svg?id=v1','\x22\x20class=\x22color_3\x22\x20>','Bulk\x20invoicing','#bt_next','fade','dashed-blue31','yes','div_right_3','#14ACBC','Payment\x20Gateway','#main_div31','images/data-flow/yesbank-logo.jpg?id=v1','#main_div33','GST\x20invoicing','images/data-flow/paytm-logo.png?id=v1','#div_right_4_sub1','float:\x20right;','#main_div1','#div_right_2_sub','#main_div4','#fra_connector','images/data-flow/busy-logo.jpg?id=v1','#div_right_3_sub4','1050881pOiQPe','<img\x20alt=\x22Simple\x20bulk\x20invoice\x20software\x22\x20src=\x22images/data-flow/','images/data-flow/cashfree-logo.svg?id=v1','dashed-blue41','<img\x20\x20alt=\x22Simple\x20bulk\x20invoice\x20software\x22\x20src=\x22','#svgContainer','franchise.svg?id=v1','images/data-flow/woocommerce-logo.png?id=v1','#div_right_4_sub','#div_right_4_sub3','3YThhvW','#main_img','toggle','1155OzfRkM','bt_next','images/data-flow/stripe-logo.png?id=v1','dashed-blue3','<img\x20alt=\x22Simple\x20bulk\x20invoice\x20software\x22\x20class=\x22img1\x22\x20src=\x22','float:\x20left;','auto','dashed-blue11','bt_play','Expenses\x20management','#div3','changeText1','right_img4','div_right_2','3759872wBDMMj','#div_right_3_sub2','display:position','dashed-blue21','#_m8','changeText3','#div_right_3_sub1','length','remove','div_vendor','#66c1e6','images/data-flow/razorpay-logo.svg?id=v1','Billing\x20software','2153354HABvkq','\x22\x20>','display:none','1117448FMrUxj','images/data-flow/flipkart-logo.png?id=v1','customers.svg?id=v1','innerHTML','#div_right_2_sub1','images/data-flow/gst_portal.jpg?id=v1','div_right_1','#main_div32','fadeOut','770328Kjisvk','\x22\x20class=\x22img\x22\x20>','display:none\x20!important','#div_right_3_sub3','right_img2','#cust_connecter','#svgContainer\x20path','Inventory\x20management','width:\x20160px;margin-bottom:\x2020px;\x20margin-top:15px;','Customer\x20data','div_cust','<img\x20alt=\x22Simple\x20bulk\x20invoice\x20software\x22\x20class=\x22img\x22\x20src=\x22','#3781AD','#vendor_connecter','\x22\x20class=\x22color_1\x22\x20>','getElementById','text-align-last:\x20end;margin-top:\x205px;','dashed-blue2','#div_right_1_sub1','text','#main_div2','APIs','4604vegJUe','bt_pause','div_fra','Event\x20/\x20Booking\x20calender','classList','bt_prev','GST\x20filling','style','HTMLSVGconnect','right_img1','addPath','div_right_4','bulk-upload.svg?id=v1','#2B5778','changeTextbt','#bt_prev','dashed-blue4','#_m9','#_m3','main_div4','opacity:\x200.1;float:\x20right;','#F7F8F8','Vendor\x20data','opacity:\x200.1;float:\x20left;','dashed-blue1','Payment\x20pages','disabled'];_0x9046=function(){return _0x3b52ac;};return _0x9046();}function draw_line(_0x7b7b2c,_0x493415,_0x29b48a,_0x4207b9,_0x1de909,_0xb720d3,_0x2c88b5,_0x4fa7d8,_0xdc23f8,_0x4db4ed,_0xdb3ddf,_0x1e2cce,_0x4769fe,_0x52981b,_0x614b53){var _0x248f36=_0x3052;$(_0x248f36(0x237))[_0x248f36(0x1fc)]({'strokeWidth':0x8,'orientation':_0x248f36(0x245),'paths':[{'start':_0x248f36(0x1e3),'end':_0x248f36(0x1f2),'class':_0x248f36(0x20c),'stroke':_0x7b7b2c},{'start':_0x248f36(0x1eb),'end':_0x248f36(0x218),'class':_0x248f36(0x1ef),'stroke':_0x493415},{'start':_0x248f36(0x22f),'end':_0x248f36(0x22c),'class':_0x248f36(0x242),'stroke':_0x29b48a},{'start':_0x248f36(0x249),'end':_0x248f36(0x205),'class':_0x248f36(0x204),'offset':0x0,'stroke':_0x4207b9},{'start':'#div_right_1_sub','end':_0x248f36(0x211),'class':_0x248f36(0x246),'offset':0x14,'stroke':_0x1de909},{'start':_0x248f36(0x1f0),'end':_0x248f36(0x206),'class':_0x248f36(0x220),'offset':0x1e,'stroke':_0xb720d3},{'start':_0x248f36(0x1d9),'end':_0x248f36(0x23d),'class':'dashed-blue31','stroke':_0x2c88b5},{'start':_0x248f36(0x22d),'end':_0x248f36(0x23d),'class':_0x248f36(0x250),'stroke':_0x4fa7d8},{'start':'#div_right_3_sub1','end':_0x248f36(0x218),'class':_0x248f36(0x220),'offset':0x23,'stroke':_0xdc23f8},{'start':_0x248f36(0x24e),'end':'#main_div31','class':_0x248f36(0x250),'offset':0x1b,'stroke':_0x4db4ed},{'start':_0x248f36(0x1e1),'end':'#main_div32','class':_0x248f36(0x246),'offset':0x12,'stroke':_0xdb3ddf},{'start':_0x248f36(0x231),'end':_0x248f36(0x227),'class':_0x248f36(0x235),'offset':0xa,'stroke':_0x1e2cce},{'start':_0x248f36(0x23b),'end':_0x248f36(0x251),'class':_0x248f36(0x246),'offset':0x18,'stroke':_0x52981b},{'start':_0x248f36(0x23a),'end':'#main_div4','class':_0x248f36(0x250),'offset':0x20,'stroke':_0x4769fe},{'start':_0x248f36(0x22a),'end':'#_m9','class':_0x248f36(0x235),'offset':0xf,'stroke':_0x614b53}]});}draw_line('#2B5778','#3781AD',_0x576e1b(0x223),_0x576e1b(0x257),_0x576e1b(0x201),_0x576e1b(0x223),_0x576e1b(0x223),_0x576e1b(0x1ea),_0x576e1b(0x201),_0x576e1b(0x1ea),_0x576e1b(0x223),_0x576e1b(0x257),_0x576e1b(0x201),_0x576e1b(0x1ea),'#66c1e6');var hide_color=_0x576e1b(0x209),titles=titles1,text=['APIs','Bulk\x20upload',_0x576e1b(0x1e7)],text_img=[_0x576e1b(0x21b),_0x576e1b(0x200),_0x576e1b(0x1d7)],text1=['Bulk\x20upload',_0x576e1b(0x1f3),_0x576e1b(0x20a)],text_img1=[_0x576e1b(0x200),_0x576e1b(0x21b),'vendor.svg?id=v1'],text2=['Bulk\x20upload','APIs',_0x576e1b(0x217)],text_img2=['bulk-upload.svg?id=v1',_0x576e1b(0x21b),_0x576e1b(0x238)],imgs=['images/data-flow/amazon-logo.png?id=v1',_0x576e1b(0x21a),_0x576e1b(0x25e),_0x576e1b(0x239)],right_img=[_0x576e1b(0x234),_0x576e1b(0x241),_0x576e1b(0x229),_0x576e1b(0x258)],right_img1=[_0x576e1b(0x226),'images/data-flow/icicibank-logo.png?id=v1'],right_img2=[_0x576e1b(0x230),_0x576e1b(0x215),'images/data-flow/tally-logo.svg?id=v1'],right_img3=['images/data-flow/gst_apis.jpg?id=v1',_0x576e1b(0x1da)],counter=0x0,counter1=0x0,counter2=0x0,elem=document['getElementById'](_0x576e1b(0x212)),elem1=document[_0x576e1b(0x1ed)](_0x576e1b(0x24a)),elem2=document[_0x576e1b(0x1ed)]('changeText2'),elem3=document[_0x576e1b(0x1ed)](_0x576e1b(0x252)),elembutton=document['getElementById'](_0x576e1b(0x202));counter2=intcounter,change2(),document[_0x576e1b(0x1ed)](_0x576e1b(0x1f9))[_0x576e1b(0x20e)]=!![];if(istimer)inst2=setInterval(change2,0x2ee0);else document[_0x576e1b(0x1ed)]('buttonsdiv')['style']=_0x576e1b(0x1e0),document['getElementById'](_0x576e1b(0x207))[_0x576e1b(0x1fb)]=_0x576e1b(0x1ee),document[_0x576e1b(0x1ed)]('main_img')[_0x576e1b(0x1fb)]=_0x576e1b(0x1e6);function change(){$(elem)['fadeTo'](0x9c4,0x0,function(){var _0x57eff2=_0x3052;this['innerHTML']=_0x57eff2(0x233)+text_img[counter]+_0x57eff2(0x1ec)+text[counter],counter=++counter%text[_0x57eff2(0x254)],$(this)[_0x57eff2(0x214)](0x9c4,0x1,change);});}change();var counter_1=0x0;function change_1(){var _0x1ce0eb=_0x576e1b;$(elem1)[_0x1ce0eb(0x214)](0xc80,0x0,function(){var _0x5c94bf=_0x1ce0eb;this['innerHTML']=_0x5c94bf(0x233)+text_img1[counter_1]+_0x5c94bf(0x20f)+text1[counter_1],counter_1=++counter_1%text1['length'],$(this)['fadeTo'](0xc80,0x1,change_1);});}change_1();var counter_2=0x0;function change_2(){var _0x53d08c=_0x576e1b;$(elem2)[_0x53d08c(0x214)](0xe10,0x0,function(){var _0x2f9de8=_0x53d08c;this[_0x2f9de8(0x1d8)]=_0x2f9de8(0x233)+text_img2[counter_2]+_0x2f9de8(0x21c)+text2[counter_2],counter_2=++counter_2%text2['length'],$(this)['fadeTo'](0xdac,0x1,change_2);});}change_2();var counter_3=0x0;function change_3(){var _0x236228=_0x576e1b;$(elem3)[_0x236228(0x214)](0x1068,0x0,function(){var _0x46c2c6=_0x236228;this[_0x46c2c6(0x1d8)]=_0x46c2c6(0x236)+imgs[counter_3]+_0x46c2c6(0x1df),counter_3=++counter_3%imgs[_0x46c2c6(0x254)],$(this)['fadeTo'](0xfa0,0x1,change_3);});}function _0x3052(_0x4472a4,_0x3f9234){var _0x9046c0=_0x9046();return _0x3052=function(_0x30525b,_0x27651b){_0x30525b=_0x30525b-0x1d7;var _0x2d022=_0x9046c0[_0x30525b];return _0x2d022;},_0x3052(_0x4472a4,_0x3f9234);}change_3();var counter4=0x0,ele_1=document[_0x576e1b(0x1ed)](_0x576e1b(0x1fd)),ele_2=document[_0x576e1b(0x1ed)](_0x576e1b(0x1e2)),ele_3=document[_0x576e1b(0x1ed)]('right_img3'),ele_4=document[_0x576e1b(0x1ed)](_0x576e1b(0x24b)),count_1=0x0;function change_4(){var _0x1463af=_0x576e1b;$(ele_1)[_0x1463af(0x214)](0xbb8,0x0,function(){var _0x3edd4f=_0x1463af;this['innerHTML']=_0x3edd4f(0x1e9)+right_img[count_1]+_0x3edd4f(0x25b),count_1=++count_1%right_img['length'],$(this)[_0x3edd4f(0x214)](0xbb8,0x1,change_4);});}change_4();var count_2=0x0;function change_5(){var _0x27c51f=_0x576e1b;$(ele_2)[_0x27c51f(0x214)](0xce4,0x0,function(){var _0x19575a=_0x27c51f;this[_0x19575a(0x1d8)]=_0x19575a(0x1e9)+right_img1[count_2]+_0x19575a(0x25b),count_2=++count_2%right_img1[_0x19575a(0x254)],$(this)[_0x19575a(0x214)](0xce4,0x1,change_5);});}change_5();var count_3=0x0;function change_6(){$(ele_3)['fadeTo'](0xed8,0x0,function(){var _0x292506=_0x3052;this[_0x292506(0x1d8)]=_0x292506(0x1e9)+right_img2[count_3]+'\x22\x20>',count_3=++count_3%right_img2[_0x292506(0x254)],$(this)['fadeTo'](0xed8,0x1,change_6);});}change_6();var count_4=0x0;function change_7(){$(ele_4)['fadeTo'](0x10cc,0x0,function(){var _0x3668fb=_0x3052;this[_0x3668fb(0x1d8)]=_0x3668fb(0x243)+right_img3[count_4]+_0x3668fb(0x25b),count_4=++count_4%right_img3['length'],$(this)['fadeTo'](0x10cc,0x1,change_7);});}change_7();var isf=_0x576e1b(0x221),bt_time=0x708,isprev=_0x576e1b(0x221);function pause(){var _0x2ba2e7=_0x576e1b;document['getElementById']('bt_pause')['style']=_0x2ba2e7(0x25c),document[_0x2ba2e7(0x1ed)]('bt_play')[_0x2ba2e7(0x1fb)]=_0x2ba2e7(0x24f),clearInterval(inst2);}function play(){var _0xb95806=_0x576e1b;document[_0xb95806(0x1ed)](_0xb95806(0x1f5))[_0xb95806(0x1fb)]='display:position',document['getElementById'](_0xb95806(0x247))['style']=_0xb95806(0x25c),change2(),inst2=setInterval(change2,0x2ee0);}function prev(){var _0x32cb59=_0x576e1b;isprev=='yes'&&(clearInterval(inst2),counter2>0x2?(document[_0x32cb59(0x1ed)](_0x32cb59(0x1f9))[_0x32cb59(0x20e)]=![],isprev='no',counter2=counter2-0x2,counter2==0x0&&(isf='no',document['getElementById'](_0x32cb59(0x1f9))[_0x32cb59(0x20e)]=!![]),change2(),inst2=setInterval(change2,0x2ee0)):(isprev='no',document[_0x32cb59(0x1ed)](_0x32cb59(0x1f9))[_0x32cb59(0x20e)]=!![],counter2=0x0,isf='no',change2(),inst2=setInterval(change2,0x2ee0)),$(_0x32cb59(0x203))[_0x32cb59(0x1dd)](bt_time,function(){var _0x90f02f=_0x32cb59;$(this)[_0x90f02f(0x213)](bt_time),isprev='yes';}));}var isnext=_0x576e1b(0x221);function next(){var _0x52337a=_0x576e1b;isnext=='yes'&&(counter2<titles[_0x52337a(0x254)]?(document['getElementById'](_0x52337a(0x1f9))[_0x52337a(0x20e)]=![],isnext='no',counter2=counter2,change2(),clearInterval(inst2),inst2=setInterval(change2,0x2ee0)):(isnext='no',counter2=0x0,document['getElementById'](_0x52337a(0x1f9))[_0x52337a(0x20e)]=!![],isf='no',change2(),clearInterval(inst2),inst2=setInterval(change2,0x2ee0)),document[_0x52337a(0x1ed)](_0x52337a(0x240))[_0x52337a(0x20e)]=!![],$(_0x52337a(0x21e))[_0x52337a(0x1dd)](bt_time,function(){var _0x46f415=_0x52337a;$(this)[_0x46f415(0x213)](bt_time),isnext=_0x46f415(0x221),document[_0x46f415(0x1ed)]('bt_next')[_0x46f415(0x20e)]=![];}));}function change2(){var _0x152046=_0x576e1b;if(titles[counter2]==_0x152046(0x259))isf=='no'&&($(_0x152046(0x216))['fadeOut'](bt_time,function(){var _0x356cd6=_0x152046;$(this)[_0x356cd6(0x1f1)](_0x356cd6(0x259))['fadeIn'](bt_time);}),addlines(_0x152046(0x201),_0x152046(0x1ea),_0x152046(0x223),_0x152046(0x257),_0x152046(0x201),_0x152046(0x223),_0x152046(0x223),'#3781AD',_0x152046(0x201),_0x152046(0x1ea),_0x152046(0x223),'#66c1e6',_0x152046(0x201),'#3781AD','#66c1e6'),document['getElementById'](_0x152046(0x1e8))[_0x152046(0x1fb)]=_0x152046(0x22b),document['getElementById'](_0x152046(0x256))[_0x152046(0x1fb)]=_0x152046(0x244),document[_0x152046(0x1ed)](_0x152046(0x1f6))[_0x152046(0x1fb)]=_0x152046(0x244),document[_0x152046(0x1ed)](_0x152046(0x219))[_0x152046(0x1fb)]='float:\x20right;',document[_0x152046(0x1ed)](_0x152046(0x1db))[_0x152046(0x1fb)]='float:\x20left;',document[_0x152046(0x1ed)](_0x152046(0x24c))[_0x152046(0x1fb)]='float:\x20right;',document['getElementById'](_0x152046(0x222))['style']=_0x152046(0x22b),document[_0x152046(0x1ed)](_0x152046(0x1ff))[_0x152046(0x1fb)]=_0x152046(0x244));else{if(titles[counter2]==_0x152046(0x228))addlines(_0x152046(0x201),'#3781AD',_0x152046(0x223),_0x152046(0x257),_0x152046(0x201),_0x152046(0x223),hide_color,hide_color,'#2B5778',_0x152046(0x1ea),_0x152046(0x223),_0x152046(0x257),'#2B5778',_0x152046(0x1ea),'#66c1e6'),$(_0x152046(0x216))[_0x152046(0x1dd)](bt_time,function(){var _0x2cb407=_0x152046;$(this)[_0x2cb407(0x1f1)](_0x2cb407(0x228))['fadeIn'](bt_time),document[_0x2cb407(0x1ed)]('div_cust')[_0x2cb407(0x1fb)]=_0x2cb407(0x22b),document[_0x2cb407(0x1ed)]('div_vendor')[_0x2cb407(0x1fb)]=_0x2cb407(0x244),document[_0x2cb407(0x1ed)](_0x2cb407(0x1f6))[_0x2cb407(0x1fb)]=_0x2cb407(0x244),document[_0x2cb407(0x1ed)]('div_seller')[_0x2cb407(0x1fb)]=_0x2cb407(0x22b),document['getElementById'](_0x2cb407(0x1db))[_0x2cb407(0x1fb)]=_0x2cb407(0x244),document[_0x2cb407(0x1ed)](_0x2cb407(0x24c))['style']=_0x2cb407(0x208),document[_0x2cb407(0x1ed)](_0x2cb407(0x24c))['classList'][_0x2cb407(0x23e)](_0x2cb407(0x21f)),document[_0x2cb407(0x1ed)](_0x2cb407(0x222))[_0x2cb407(0x1fb)]=_0x2cb407(0x22b),document[_0x2cb407(0x1ed)]('div_right_4')['style']=_0x2cb407(0x244);});else{if(titles[counter2]==_0x152046(0x21d))addlines(_0x152046(0x201),hide_color,_0x152046(0x223),_0x152046(0x257),'#2B5778',_0x152046(0x223),hide_color,hide_color,'#2B5778',hide_color,_0x152046(0x223),'#66c1e6',_0x152046(0x201),hide_color,_0x152046(0x257)),$(_0x152046(0x216))['fadeOut'](bt_time,function(){var _0x1e6dbc=_0x152046;$(this)[_0x1e6dbc(0x1f1)](_0x1e6dbc(0x21d))[_0x1e6dbc(0x213)](bt_time),document[_0x1e6dbc(0x1ed)]('div_cust')[_0x1e6dbc(0x1fb)]=_0x1e6dbc(0x22b),document[_0x1e6dbc(0x1ed)]('div_vendor')[_0x1e6dbc(0x1fb)]=_0x1e6dbc(0x20b),document['getElementById'](_0x1e6dbc(0x256))[_0x1e6dbc(0x1f8)]['toggle'](_0x1e6dbc(0x21f)),document[_0x1e6dbc(0x1ed)](_0x1e6dbc(0x1f6))[_0x1e6dbc(0x1fb)]=_0x1e6dbc(0x244),document[_0x1e6dbc(0x1ed)](_0x1e6dbc(0x219))[_0x1e6dbc(0x1fb)]=_0x1e6dbc(0x22b),document[_0x1e6dbc(0x1ed)](_0x1e6dbc(0x1db))[_0x1e6dbc(0x1fb)]=_0x1e6dbc(0x244),document[_0x1e6dbc(0x1ed)](_0x1e6dbc(0x24c))[_0x1e6dbc(0x1fb)]='opacity:\x200.1;float:\x20right;',document[_0x1e6dbc(0x1ed)](_0x1e6dbc(0x24c))[_0x1e6dbc(0x1f8)]['toggle']('fade'),document[_0x1e6dbc(0x1ed)](_0x1e6dbc(0x222))[_0x1e6dbc(0x1fb)]=_0x1e6dbc(0x22b),document[_0x1e6dbc(0x1ed)](_0x1e6dbc(0x1ff))[_0x1e6dbc(0x1fb)]=_0x1e6dbc(0x244);});else{if(titles[counter2]=='Subcriptions')addlines(_0x152046(0x201),hide_color,_0x152046(0x223),hide_color,_0x152046(0x201),'#14ACBC',hide_color,hide_color,_0x152046(0x201),hide_color,'#14ACBC',hide_color,_0x152046(0x201),hide_color,hide_color),$(_0x152046(0x216))[_0x152046(0x1dd)](bt_time,function(){var _0x316f7b=_0x152046;$(this)[_0x316f7b(0x1f1)]('Subcriptions')[_0x316f7b(0x213)](bt_time),document[_0x316f7b(0x1ed)](_0x316f7b(0x1e8))[_0x316f7b(0x1fb)]='float:\x20right;',document[_0x316f7b(0x1ed)]('div_vendor')['style']='opacity:\x200.1;float:\x20left;',document[_0x316f7b(0x1ed)](_0x316f7b(0x256))[_0x316f7b(0x1f8)][_0x316f7b(0x23e)](_0x316f7b(0x21f)),document['getElementById'](_0x316f7b(0x1f6))[_0x316f7b(0x1fb)]=_0x316f7b(0x244),document[_0x316f7b(0x1ed)](_0x316f7b(0x219))[_0x316f7b(0x1fb)]=_0x316f7b(0x208),document[_0x316f7b(0x1ed)](_0x316f7b(0x219))['classList'][_0x316f7b(0x23e)]('fade'),document[_0x316f7b(0x1ed)](_0x316f7b(0x1db))[_0x316f7b(0x1fb)]='float:\x20left;',document['getElementById']('div_right_2')[_0x316f7b(0x1fb)]=_0x316f7b(0x208),document[_0x316f7b(0x1ed)](_0x316f7b(0x24c))[_0x316f7b(0x1f8)][_0x316f7b(0x23e)](_0x316f7b(0x21f)),document[_0x316f7b(0x1ed)]('div_right_3')[_0x316f7b(0x1fb)]=_0x316f7b(0x22b),document[_0x316f7b(0x1ed)]('div_right_4')[_0x316f7b(0x1fb)]=_0x316f7b(0x244);});else{if(titles[counter2]==_0x152046(0x224))addlines('#2B5778',hide_color,_0x152046(0x223),hide_color,'#2B5778',_0x152046(0x223),hide_color,hide_color,_0x152046(0x201),hide_color,'#14ACBC',hide_color,hide_color,hide_color,hide_color),$(_0x152046(0x216))[_0x152046(0x1dd)](bt_time,function(){var _0x4e5cd7=_0x152046;$(this)[_0x4e5cd7(0x1f1)]('Payment\x20Gateway')[_0x4e5cd7(0x213)](bt_time),document['getElementById']('div_cust')[_0x4e5cd7(0x1fb)]=_0x4e5cd7(0x22b),document[_0x4e5cd7(0x1ed)](_0x4e5cd7(0x256))['style']=_0x4e5cd7(0x20b),document[_0x4e5cd7(0x1ed)](_0x4e5cd7(0x256))[_0x4e5cd7(0x1f8)][_0x4e5cd7(0x23e)](_0x4e5cd7(0x21f)),document['getElementById']('div_fra')[_0x4e5cd7(0x1fb)]=_0x4e5cd7(0x244),document[_0x4e5cd7(0x1ed)](_0x4e5cd7(0x219))[_0x4e5cd7(0x1fb)]=_0x4e5cd7(0x208),document[_0x4e5cd7(0x1ed)]('div_seller')[_0x4e5cd7(0x1f8)][_0x4e5cd7(0x23e)]('fade'),document[_0x4e5cd7(0x1ed)]('div_right_1')[_0x4e5cd7(0x1fb)]=_0x4e5cd7(0x244),document['getElementById'](_0x4e5cd7(0x24c))[_0x4e5cd7(0x1fb)]=_0x4e5cd7(0x208),document['getElementById'](_0x4e5cd7(0x24c))[_0x4e5cd7(0x1f8)][_0x4e5cd7(0x23e)](_0x4e5cd7(0x21f)),document['getElementById'](_0x4e5cd7(0x222))[_0x4e5cd7(0x1fb)]=_0x4e5cd7(0x22b),document['getElementById']('div_right_4')[_0x4e5cd7(0x1fb)]=_0x4e5cd7(0x20b),document[_0x4e5cd7(0x1ed)](_0x4e5cd7(0x1ff))[_0x4e5cd7(0x1f8)][_0x4e5cd7(0x23e)]('fade');});else{if(titles[counter2]==_0x152046(0x248))addlines(hide_color,_0x152046(0x1ea),hide_color,hide_color,hide_color,hide_color,hide_color,_0x152046(0x1ea),hide_color,_0x152046(0x1ea),hide_color,hide_color,hide_color,_0x152046(0x1ea),hide_color),$('#mainbt')[_0x152046(0x1dd)](bt_time,function(){var _0x3743d8=_0x152046;$(this)[_0x3743d8(0x1f1)]('Expenses\x20management')[_0x3743d8(0x213)](bt_time),document[_0x3743d8(0x1ed)]('div_cust')['style']=_0x3743d8(0x208),document[_0x3743d8(0x1ed)](_0x3743d8(0x1e8))['classList'][_0x3743d8(0x23e)](_0x3743d8(0x21f)),document[_0x3743d8(0x1ed)](_0x3743d8(0x256))[_0x3743d8(0x1fb)]=_0x3743d8(0x244),document[_0x3743d8(0x1ed)](_0x3743d8(0x1f6))['style']=_0x3743d8(0x20b),document[_0x3743d8(0x1ed)]('div_fra')[_0x3743d8(0x1f8)][_0x3743d8(0x23e)](_0x3743d8(0x21f)),document[_0x3743d8(0x1ed)](_0x3743d8(0x219))['style']='opacity:\x200.1;float:\x20right;',document[_0x3743d8(0x1ed)](_0x3743d8(0x219))[_0x3743d8(0x1f8)]['toggle'](_0x3743d8(0x21f)),document[_0x3743d8(0x1ed)](_0x3743d8(0x1db))[_0x3743d8(0x1fb)]=_0x3743d8(0x20b),document[_0x3743d8(0x1ed)](_0x3743d8(0x1db))[_0x3743d8(0x1f8)][_0x3743d8(0x23e)](_0x3743d8(0x21f)),document[_0x3743d8(0x1ed)](_0x3743d8(0x24c))['style']=_0x3743d8(0x22b),document[_0x3743d8(0x1ed)](_0x3743d8(0x222))[_0x3743d8(0x1fb)]='float:\x20right;',document[_0x3743d8(0x1ed)](_0x3743d8(0x1ff))[_0x3743d8(0x1fb)]=_0x3743d8(0x244);});else{if(titles[counter2]==_0x152046(0x1e5))addlines(_0x152046(0x201),'#3781AD',_0x152046(0x223),hide_color,_0x152046(0x201),_0x152046(0x223),'#14ACBC','#3781AD',_0x152046(0x201),_0x152046(0x1ea),_0x152046(0x223),hide_color,hide_color,hide_color,hide_color),$('#mainbt')['fadeOut'](bt_time,function(){var _0xb8080e=_0x152046;$(this)[_0xb8080e(0x1f1)](_0xb8080e(0x1e5))[_0xb8080e(0x213)](bt_time),document[_0xb8080e(0x1ed)](_0xb8080e(0x1e8))[_0xb8080e(0x1fb)]=_0xb8080e(0x22b),document['getElementById'](_0xb8080e(0x256))[_0xb8080e(0x1fb)]=_0xb8080e(0x244),document[_0xb8080e(0x1ed)](_0xb8080e(0x1f6))[_0xb8080e(0x1fb)]='float:\x20left;',document['getElementById'](_0xb8080e(0x219))[_0xb8080e(0x1fb)]=_0xb8080e(0x208),document[_0xb8080e(0x1ed)](_0xb8080e(0x219))[_0xb8080e(0x1f8)]['toggle'](_0xb8080e(0x21f)),document[_0xb8080e(0x1ed)](_0xb8080e(0x1db))[_0xb8080e(0x1fb)]=_0xb8080e(0x244),document[_0xb8080e(0x1ed)]('div_right_2')[_0xb8080e(0x1fb)]='float:\x20right;',document[_0xb8080e(0x1ed)](_0xb8080e(0x222))['style']='float:\x20right;',document[_0xb8080e(0x1ed)](_0xb8080e(0x1ff))[_0xb8080e(0x1fb)]=_0xb8080e(0x20b),document[_0xb8080e(0x1ed)](_0xb8080e(0x1ff))['classList'][_0xb8080e(0x23e)](_0xb8080e(0x21f));});else{if(titles[counter2]==_0x152046(0x1fa))addlines(_0x152046(0x201),_0x152046(0x1ea),_0x152046(0x223),_0x152046(0x257),hide_color,hide_color,hide_color,hide_color,_0x152046(0x201),_0x152046(0x1ea),_0x152046(0x223),_0x152046(0x257),_0x152046(0x257),'#3781AD',_0x152046(0x201)),$('#mainbt')[_0x152046(0x1dd)](bt_time,function(){var _0x1d43c3=_0x152046;$(this)[_0x1d43c3(0x1f1)](_0x1d43c3(0x1fa))[_0x1d43c3(0x213)](bt_time),document[_0x1d43c3(0x1ed)](_0x1d43c3(0x1e8))[_0x1d43c3(0x1fb)]='float:\x20right;',document[_0x1d43c3(0x1ed)]('div_vendor')[_0x1d43c3(0x1fb)]=_0x1d43c3(0x244),document[_0x1d43c3(0x1ed)](_0x1d43c3(0x1f6))[_0x1d43c3(0x1fb)]=_0x1d43c3(0x244),document[_0x1d43c3(0x1ed)](_0x1d43c3(0x219))[_0x1d43c3(0x1fb)]='float:\x20right;',document[_0x1d43c3(0x1ed)](_0x1d43c3(0x1db))[_0x1d43c3(0x1fb)]='opacity:\x200.1;float:\x20left;',document['getElementById']('div_right_1')['classList']['toggle'](_0x1d43c3(0x21f)),document[_0x1d43c3(0x1ed)](_0x1d43c3(0x24c))[_0x1d43c3(0x1fb)]=_0x1d43c3(0x208),document[_0x1d43c3(0x1ed)]('div_right_2')[_0x1d43c3(0x1f8)][_0x1d43c3(0x23e)]('fade'),document[_0x1d43c3(0x1ed)](_0x1d43c3(0x222))[_0x1d43c3(0x1fb)]=_0x1d43c3(0x22b),document[_0x1d43c3(0x1ed)]('div_right_4')[_0x1d43c3(0x1fb)]=_0x1d43c3(0x244);});else{if(titles[counter2]==_0x152046(0x1f7))addlines(_0x152046(0x201),hide_color,_0x152046(0x223),hide_color,'#2B5778',_0x152046(0x223),hide_color,hide_color,_0x152046(0x201),hide_color,'#14ACBC',hide_color,hide_color,hide_color,hide_color),$(_0x152046(0x216))[_0x152046(0x1dd)](bt_time,function(){var _0x7efcd4=_0x152046;$(this)[_0x7efcd4(0x1f1)](_0x7efcd4(0x1f7))[_0x7efcd4(0x213)](bt_time);}),document[_0x152046(0x1ed)]('div_cust')[_0x152046(0x1fb)]=_0x152046(0x22b),document[_0x152046(0x1ed)](_0x152046(0x256))[_0x152046(0x1fb)]='opacity:\x200.1;float:\x20left;',document[_0x152046(0x1ed)](_0x152046(0x256))[_0x152046(0x1f8)][_0x152046(0x23e)]('fade'),document[_0x152046(0x1ed)](_0x152046(0x1f6))['style']=_0x152046(0x244),document['getElementById'](_0x152046(0x219))[_0x152046(0x1fb)]=_0x152046(0x208),document['getElementById'](_0x152046(0x219))['classList'][_0x152046(0x23e)]('fade'),document[_0x152046(0x1ed)](_0x152046(0x1db))['style']=_0x152046(0x244),document['getElementById']('div_right_2')[_0x152046(0x1fb)]='opacity:\x200.1;float:\x20right;',document['getElementById'](_0x152046(0x24c))[_0x152046(0x1f8)]['toggle'](_0x152046(0x21f)),document[_0x152046(0x1ed)]('div_right_3')[_0x152046(0x1fb)]=_0x152046(0x22b),document[_0x152046(0x1ed)](_0x152046(0x1ff))[_0x152046(0x1fb)]='opacity:\x200.1;float:\x20left;',document[_0x152046(0x1ed)](_0x152046(0x1ff))[_0x152046(0x1f8)][_0x152046(0x23e)](_0x152046(0x21f));else titles[counter2]=='Payment\x20pages'&&(addlines('#2B5778',hide_color,_0x152046(0x223),hide_color,'#2B5778',_0x152046(0x223),hide_color,hide_color,'#2B5778',hide_color,_0x152046(0x223),hide_color,hide_color,hide_color,hide_color),$(_0x152046(0x216))[_0x152046(0x1dd)](bt_time,function(){var _0x42ec17=_0x152046;$(this)[_0x42ec17(0x1f1)](_0x42ec17(0x20d))[_0x42ec17(0x213)](bt_time);}),document[_0x152046(0x1ed)](_0x152046(0x1e8))['style']='float:\x20right;',document[_0x152046(0x1ed)](_0x152046(0x256))[_0x152046(0x1fb)]='opacity:\x200.1;float:\x20left;',document['getElementById'](_0x152046(0x256))[_0x152046(0x1f8)][_0x152046(0x23e)](_0x152046(0x21f)),document[_0x152046(0x1ed)](_0x152046(0x1f6))[_0x152046(0x1fb)]='float:\x20left;',document['getElementById'](_0x152046(0x219))['style']=_0x152046(0x208),document[_0x152046(0x1ed)](_0x152046(0x219))[_0x152046(0x1f8)][_0x152046(0x23e)](_0x152046(0x21f)),document[_0x152046(0x1ed)](_0x152046(0x1db))[_0x152046(0x1fb)]=_0x152046(0x244),document[_0x152046(0x1ed)](_0x152046(0x24c))[_0x152046(0x1fb)]='opacity:\x200.1;float:\x20right;',document['getElementById'](_0x152046(0x24c))['classList'][_0x152046(0x23e)]('fade'),document[_0x152046(0x1ed)](_0x152046(0x222))[_0x152046(0x1fb)]=_0x152046(0x22b),document[_0x152046(0x1ed)](_0x152046(0x1ff))['style']=_0x152046(0x20b),document[_0x152046(0x1ed)]('div_right_4')[_0x152046(0x1f8)][_0x152046(0x23e)]('fade'));}}}}}}}}if(counter2==0x0)document[_0x152046(0x1ed)](_0x152046(0x1f9))[_0x152046(0x20e)]=!![];else document[_0x152046(0x1ed)](_0x152046(0x1f9))['disabled']=![];counter2++,counter2>=titles[_0x152046(0x254)]&&(isf='no',counter2=0x0);}function addlines(_0x321748,_0x39574f,_0x417048,_0x465930,_0xbdfa1f,_0x12cfff,_0xde5b94,_0x78032a,_0x378e5c,_0x3e720d,_0x230bab,_0x4a98ef,_0x39cc17,_0xc35324,_0x4233fb){var _0x407388=_0x576e1b;$(_0x407388(0x237))[_0x407388(0x1dd)](bt_time,function(){$(this)['fadeIn'](bt_time);}),setTimeout(function(){var _0x92e0f=_0x407388;$(_0x92e0f(0x1e4))[_0x92e0f(0x255)]();var _0x4cf16c={'start':_0x92e0f(0x1e3),'end':_0x92e0f(0x1f2),'class':_0x92e0f(0x20c),'stroke':_0x321748};$('#svgContainer')[_0x92e0f(0x1fc)](_0x92e0f(0x1fe),_0x4cf16c);var _0x15de28={'start':_0x92e0f(0x1eb),'end':_0x92e0f(0x218),'class':_0x92e0f(0x1ef),'stroke':_0x39574f};$(_0x92e0f(0x237))[_0x92e0f(0x1fc)](_0x92e0f(0x1fe),_0x15de28);var _0x297387={'start':_0x92e0f(0x22f),'end':'#main_div1','class':_0x92e0f(0x242),'stroke':_0x417048};$('#svgContainer')['HTMLSVGconnect'](_0x92e0f(0x1fe),_0x297387);var _0x217aad={'start':_0x92e0f(0x249),'end':_0x92e0f(0x205),'class':_0x92e0f(0x204),'offset':0x0,'stroke':_0x465930};$(_0x92e0f(0x237))[_0x92e0f(0x1fc)](_0x92e0f(0x1fe),_0x217aad);var _0x1b2abe={'start':'#div_right_1_sub','end':_0x92e0f(0x211),'class':_0x92e0f(0x246),'offset':0x14,'stroke':_0xbdfa1f};$(_0x92e0f(0x237))['HTMLSVGconnect'](_0x92e0f(0x1fe),_0x1b2abe);var _0x4c417c={'start':_0x92e0f(0x1f0),'end':_0x92e0f(0x206),'class':_0x92e0f(0x220),'offset':0x1e,'stroke':_0x12cfff};$(_0x92e0f(0x237))[_0x92e0f(0x1fc)](_0x92e0f(0x1fe),_0x4c417c);var _0x26b59d={'start':'#div_right_2_sub1','end':_0x92e0f(0x23d),'class':_0x92e0f(0x220),'stroke':_0xde5b94};$(_0x92e0f(0x237))[_0x92e0f(0x1fc)]('addPath',_0x26b59d);var _0x5062ac={'start':_0x92e0f(0x22d),'end':_0x92e0f(0x23d),'class':_0x92e0f(0x250),'stroke':_0x78032a};$(_0x92e0f(0x237))[_0x92e0f(0x1fc)]('addPath',_0x5062ac);var _0x15c529={'start':_0x92e0f(0x253),'end':_0x92e0f(0x218),'class':_0x92e0f(0x220),'offset':0x23,'stroke':_0x378e5c};$(_0x92e0f(0x237))['HTMLSVGconnect'](_0x92e0f(0x1fe),_0x15c529);var _0x5cea3f={'start':_0x92e0f(0x24e),'end':_0x92e0f(0x225),'class':'dashed-blue21','offset':0x1b,'stroke':_0x3e720d};$(_0x92e0f(0x237))[_0x92e0f(0x1fc)](_0x92e0f(0x1fe),_0x5cea3f);var _0x36d3d0={'start':_0x92e0f(0x1e1),'end':_0x92e0f(0x1dc),'class':_0x92e0f(0x246),'offset':0x12,'stroke':_0x230bab};$(_0x92e0f(0x237))[_0x92e0f(0x1fc)](_0x92e0f(0x1fe),_0x36d3d0);var _0xcd0e7e={'start':_0x92e0f(0x231),'end':_0x92e0f(0x227),'class':'dashed-blue41','offset':0xa,'stroke':_0x4a98ef};$(_0x92e0f(0x237))[_0x92e0f(0x1fc)](_0x92e0f(0x1fe),_0xcd0e7e);var _0x4c7c10={'start':_0x92e0f(0x23b),'end':_0x92e0f(0x251),'class':_0x92e0f(0x246),'offset':0x18,'stroke':_0xc35324};$(_0x92e0f(0x237))[_0x92e0f(0x1fc)](_0x92e0f(0x1fe),_0x4c7c10);var _0x119ecd={'start':'#div_right_4_sub','end':_0x92e0f(0x22e),'class':_0x92e0f(0x250),'offset':0x20,'stroke':_0x39cc17};$('#svgContainer')[_0x92e0f(0x1fc)](_0x92e0f(0x1fe),_0x119ecd);var _0x862db3={'start':_0x92e0f(0x22a),'end':_0x92e0f(0x205),'class':'dashed-blue41','offset':0xf,'stroke':_0x4233fb};$(_0x92e0f(0x237))[_0x92e0f(0x1fc)](_0x92e0f(0x1fe),_0x862db3);},0x7d0);}


} catch (o) { }
