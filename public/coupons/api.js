
if (!window.jQuery)
{
    var script = document.createElement('script');
    script.type = "text/javascript";
    script.src = "https://code.jquery.com/jquery-1.3.2.min.js";
    document.getElementsByTagName('head')[0].appendChild(script);
}

//<![CDATA[
if (document.createStyleSheet)
{
    document.createStyleSheet('https://www.swipez.in/coupons/api.css?v=1');
} else
{
    var styles = "@import url('https://www.swipez.in/coupons/api.css?v=1');";
    var newSS = document.createElement('link');
    newSS.rel = 'stylesheet';
    newSS.href = 'data:text/css,' + escape(styles);
    document.getElementsByTagName("head")[0].appendChild(newSS);
}
//]]>
var swipez_offer_type = document.getElementById('swipez-offer').getAttribute("offer-type"); // offer,store,exclusive-offers,all

loadcoupons(swipez_offer_type);

function loadcoupons(swipez_offer_type)
{
    if (swipez_offer_type == 'store')
    {
        cudu_store();
    }
    if (swipez_offer_type == 'exclusive-offers')
    {
        cudu_exclusive_offers();
    }
    if (swipez_offer_type == 'offer' || swipez_offer_type == 'all')
    {
        cudu_offers();
    }
}


function cudu_store()
{
    try {
        document.getElementById("cudubest").className = "classic-menu-dropdown";
        document.getElementById("cudustore").className = "classic-menu-dropdown active";
        document.getElementById("cuduexclusive").className = "classic-menu-dropdown";
        document.getElementById("mcudubest").className = "classic-menu-dropdown";
        document.getElementById("mcudustore").className = "classic-menu-dropdown active";
        document.getElementById("mcuduexclusive").className = "classic-menu-dropdown";
    } catch (o)
    {

    }

    var swipezTKey = 'default';
    try {
        var swipezTKey = document.getElementById('swipez-offer').getAttribute("swipez-trans-key"); // HvT5Lq5_1tsUbPS0CDigUQ
    } catch (o)
    {
    }

    var swipezMerchantKey = document.getElementById('swipez-offer').getAttribute("swipez-merchant-key");
    var couponMenu = 'true';
    try {
        var couponMenu = document.getElementById('swipez-offer').getAttribute("coupon-menu");
    } catch (o)
    {

    }
    let xhr = new XMLHttpRequest;
    //alert('https://intapi.swipez.in/api/v1/coupon/store/'+swipezMerchantKey+'/'+swipezTKey);
    xhr.open('GET', 'https://intapi.swipez.in/api/v1/coupon/store/' + swipezMerchantKey + '/' + swipezTKey, true)
    xhr.onload = function ()
    {
        if (this.status === 200)
        {
            var stores = JSON.parse(this.responseText);
            var store_id = '';
            var swipezMKey_str = "'" + swipezMerchantKey + "'";
            var swipezTKey_str = "'" + swipezTKey + "'";
            var offer_str_li = '';
            if (couponMenu == 'true')
            {
                var offer_str_li = '<ul class="cudu_nav_ul"><li class="cudu_nav_li"><a onclick="cudu_store();"> Stores</li><li class="cudu_nav_li"><a onclick="cudu_offers();"> Best Offers</li><li class="cudu_nav_li"><a onclick="cudu_exclusive_offers();"> Exclusive Offers</li></ul><h4>Top Stores</h4><ul>';
            }

            var img_name = '';
            var num_offers = '';
            var store_id = '';
            var title = '';
            var outlink_url = '';
            $.each(stores['stores'], function (index, value) {
                //console.log(value.store_name);				
                title = "'" + value.store_name + "'";
                title = title.replace(/\'/g, "");
                title = title.replace(/\"/g, "");

                img_name = "'" + value.logo_rectangle_url + "'";
                store_id = "'" + value.store_id + "'";
                num_offers = value.num_offers;
                store_id = value.store_id;

                offer_str_li += '<li class="cudu_offer_li_table" ><table style="margin: 3px; height: 300px; width : 300px; box-shadow: 1px 1px 1px 1px lightgrey; "><tr><td style="text-align:center;"><img src="' + value.logo_rectangle_url + '" /> </td></tr><tr><td style="text-align:center;"><h3 class="cudu_title">' + title + '</h3></td></tr><tr><td style="text-align:center;"><span> Total Offers : ' + num_offers + '</span></td></tr>';

                title = "'" + title + "'";

                offer_str_li += '<tr><td style="text-align:center;"><span><button class="button" onclick="getStoreOffers(' + store_id + ',' + swipezMKey_str + ',' + swipezTKey_str + ');">Get Offers</button></span></td></tr></table></li>';
            });
            offer_str_li += '</ul>';
            $('#swipez-offer').html(offer_str_li);
            //$('#swipez-offer').append(offer_str_li);			
        }
    }
    xhr.send();
} // end cudu_store

function cudu_exclusive_offers()
{
    try {
        document.getElementById("cudubest").className = "classic-menu-dropdown";
        document.getElementById("cudustore").className = "classic-menu-dropdown";
        document.getElementById("cuduexclusive").className = "classic-menu-dropdown active";
        document.getElementById("mcudubest").className = "classic-menu-dropdown";
        document.getElementById("mcudustore").className = "classic-menu-dropdown";
        document.getElementById("mcuduexclusive").className = "classic-menu-dropdown active";
    } catch (o)
    {

    }
    var swipezTKey = 'default';
    try {
        var swipezTKey = document.getElementById('swipez-offer').getAttribute("swipez-trans-key"); // HvT5Lq5_1tsUbPS0CDigUQ
    } catch (o)
    {
    }
    var swipezMerchantKey = document.getElementById('swipez-offer').getAttribute("swipez-merchant-key");
    var couponMenu = 'true';
    try {
        var couponMenu = document.getElementById('swipez-offer').getAttribute("coupon-menu");
    } catch (o)
    {

    }
    let xhr = new XMLHttpRequest;
    xhr.open('GET', 'https://intapi.swipez.in/api/v1/coupon/exclusiveoffer/' + swipezMerchantKey + '/' + swipezTKey, true)
    xhr.onload = function ()
    {
        if (this.status === 200)
        {
            console.log(JSON.parse(this.responseText));

            var data = JSON.parse(this.responseText);
            var offers = data.offers;
            var offer_public_id = '';
            var swipezMKey_str = "'" + swipezMerchantKey + "'";
            var swipezTKey_str = "'" + swipezTKey + "'";
            var offer_str_li = '';
            if (couponMenu == 'true')
            {
                var offer_str_li = '<ul class="cudu_nav_ul"><li class="cudu_nav_li"><a onclick="cudu_store();"> Stores</li><li class="cudu_nav_li"><a onclick="cudu_offers();"> Best Offers</li><li class="cudu_nav_li"><a onclick="cudu_exclusive_offers();"> Exclusive Offers</li></ul><h4>Top Stores</h4><ul>';
            }
            var img_name = '';
            var description = '';
            var title = '';
            var outlink_url = '';
            for (item in offers)
            {
                img_name = "'" + offers[item].store.logo_rectangle_url + "'";
                description = "'" + offers[item].description + "'";
                description = description.replace(/\'/g, "");
                description = description.replace(/\"/g, "");

                title = "'" + offers[item].title + "'";
                title = title.replace(/\'/g, "");
                title = title.replace(/\"/g, "");

                img_name = "'" + offers[item].store.logo_rectangle_url + "'";
                offer_public_id = "'" + offers[item].public_id + "'";
                outlink_url = "'" + offers[item].outlink_url + "'";

                //offer_str_li += '<li class="cudu_offer_li_table" ><table style="margin: 3px; height: 250px; width : 300px; box-shadow: 1px 1px 1px 1px lightgrey; "><tr><td style="text-align:center;"><div><img src="' + offers[item].store.logo_rectangle_url + '" /> </div></td></tr><tr><td style="text-align:center;"><div><h3 class="cudu_title">' + title + '</h3></div></td></tr><tr><td style="text-align:center;"><div><span>' + description + '</span></div></td></tr>';
                offer_str_li += '<li class="" ><table style="margin: 3px; height: 300px; width : 300px; box-shadow: 1px 1px 1px 1px lightgrey; "><tr><td style="text-align:center;"><img src="' + offers[item].store.logo_rectangle_url + '" /> </td></tr><tr><td style="text-align:center;"><h3 class="cudu_title">' + title + '</h3></td></tr><tr><td style="text-align:center;"><span>' + description + '</span></td></tr>';

                description = "'" + description + "'";
                title = "'" + title + "'";

                //offer_str_li += '<tr><td style="text-align:center;"><div><span><button class="button" onclick="getCouponCode(' + offer_public_id + ',' + swipezMKey_str + ',' + img_name + ',' + title + ',' + description + ',' + outlink_url + ');">Get Deal</button></span></div></td></tr></table></li>';
                offer_str_li += '<tr><td style="text-align:center;"><span><button class="button" onclick="getCouponCode(' + offer_public_id + ',' + swipezMKey_str + ',' + swipezTKey_str + ',' + img_name + ',' + title + ',' + description + ',' + outlink_url + ');">Get Deal</button></span></td></tr></table></li>';
                // getCouponCode = Get the button that opens the modal
            }
            offer_str_li += '</ul>';
            $('#swipez-offer').html(offer_str_li);
            //$('#swipez-offer').append(offer_str_li);			
        }
    }
    xhr.send();
} // end cudu_exclusive_offers

function cudu_offers()
{
    try {
        document.getElementById("cudubest").className = "classic-menu-dropdown active";
        document.getElementById("cudustore").className = "classic-menu-dropdown";
        document.getElementById("cuduexclusive").className = "classic-menu-dropdown";
        document.getElementById("mcudubest").className = "classic-menu-dropdown active";
        document.getElementById("mcudustore").className = "classic-menu-dropdown";
        document.getElementById("mcuduexclusive").className = "classic-menu-dropdown";
    } catch (o)
    {

    }
    var swipezTKey = 'default';
    try {
        var swipezTKey = document.getElementById('swipez-offer').getAttribute("swipez-trans-key"); // HvT5Lq5_1tsUbPS0CDigUQ
    } catch (o)
    {
    }

    var swipezMerchantKey = document.getElementById('swipez-offer').getAttribute("swipez-merchant-key");//xCWe00l1FFG19pPsCgyBSg
    var couponMenu = 'true';
    try {
        var couponMenu = document.getElementById('swipez-offer').getAttribute("coupon-menu");
    } catch (o)
    {

    }
    //alert('https://intapi.swipez.in/api/v1/coupon/offer/'+swipezMerchantKey+'/'+swipezTKey); return false;
    let xhr = new XMLHttpRequest;
    xhr.open('GET', 'https://intapi.swipez.in/api/v1/coupon/offer/' + swipezMerchantKey + '/' + swipezTKey, true)
    xhr.onload = function ()
    {
        if (this.status === 200)
        {	//console.log(JSON.parse(this.responseText));
            var data = JSON.parse(this.responseText);
            var offers = data.offers;
            var offer_public_id = '';
            var swipezMKey_str = "'" + swipezMerchantKey + "'";
            var swipezTKey_str = "'" + swipezTKey + "'";
            var offer_str_li = '';
            if (couponMenu == 'true')
            {
                var offer_str_li = '<ul class="cudu_nav_ul"><li class="cudu_nav_li"><a onclick="cudu_store();"> Stores</li><li class="cudu_nav_li"><a onclick="cudu_offers();"> Best Offers</li><li class="cudu_nav_li"><a onclick="cudu_exclusive_offers();"> Exclusive Offers</li></ul><h4>Top Stores</h4><ul>';
            }
            var img_name = '';
            var description = '';
            var title = '';
            var outlink_url = '';
            for (item in offers)
            {
                img_name = "'" + offers[item].store.logo_rectangle_url + "'";
                description = "'" + offers[item].description + "'";
                description = description.replace(/\'/g, "");
                description = description.replace(/\"/g, "");

                title = "'" + offers[item].title + "'";
                title = title.replace(/\'/g, "");
                title = title.replace(/\"/g, "");

                img_name = "'" + offers[item].store.logo_rectangle_url + "'";
                offer_public_id = "'" + offers[item].public_id + "'";
                outlink_url = "'" + offers[item].outlink_url + "'";

                offer_str_li += '<li class="" ><table style="margin: 3px; height: 300px; width : 300px; box-shadow: 1px 1px 1px 1px lightgrey; "><tr><td style="text-align:center;"><img src="' + offers[item].store.logo_rectangle_url + '" /> </td></tr><tr><td style="text-align:center;"><h3 class="cudu_title">' + title + '</h3></td></tr><tr><td style="text-align:center;"><span>' + description + '</span></td></tr>';

                description = "'" + description + "'";
                title = "'" + title + "'";

                offer_str_li += '<tr><td style="text-align:center;"><span><button class="button" onclick="getCouponCode(' + offer_public_id + ',' + swipezMKey_str + ',' + swipezTKey_str + ',' + img_name + ',' + title + ',' + description + ',' + outlink_url + ');">Get Deal</button></span></td></tr></table></li>';
                // getCouponCode = Get the button that opens the modal
            }
            offer_str_li += '</ul>';
            $('#swipez-offer').html(offer_str_li);
            //$('#swipez-offer').append(offer_str_li);			
        }
    }
    xhr.send();
} // end cudu_offers




function getCouponCode(id, Mkey, Tkey, img_name, title, description, outlink_url)
{
    window.scrollTo(0, 0);
    var o_p_id = id;
    var m_key = Mkey;
    let xhr = new XMLHttpRequest;
    //alert('https://intapi.swipez.in/api/v1/coupon/getcode/'+o_p_id+'/'+m_key); return false;
    xhr.open('GET', 'https://intapi.swipez.in/api/v1/coupon/getcode/' + o_p_id + '/' + m_key + '/' + Tkey, true)
    xhr.onload = function ()
    {
        if (this.status === 200)
        {
            console.log(JSON.parse(this.responseText));
            var data = JSON.parse(this.responseText);

            var code = data.code;
            var couponCode = '';
            if (code == 400)
            {
                couponCode = data.message;
            } else
            {
                couponCode = code;
            }
            var outlink_url_link = '';
            if (outlink_url)
            {
                outlink_url_link = '<a href="' + outlink_url + '" target="_blank"><button class="button">Get Deal</button></a>'
            }
            //alert("before popup string created");
            // When the user clicks the button, open the modal
            var popup_str = '';
            popup_str = '<div id="cudu_myModal_' + o_p_id + '" class="cudu_modal" style="">' +
                    '<div class="cudu_modal-content">' +
                    '<div class="cudu_modal-header">' +
                    '<span id="cudu_popup_close_' + o_p_id + '"><button class="cudu_popup_close"></button></span>' +
                    '<h4 class="cudu_modal-title">Coupon details</h4>' +
                    '</div>' +
                    '<div class="cudu_modal-body">' +
                    '<div>' +
                    '<img style="max-width: 100%;max-height: 200px;" src="' + img_name + '">' +
                    '<br>' +
                    '<h4><b>' + title + '</b></h4>' +
                    '<p>' + description + '</p>' +
                    '<div class="cudu_deal-activated-text " data-type="visible" ><span style="color: maroon;">Deal Activated. </span><span>' + couponCode + '</span>' +
                    '</div>' +
                    '<br>' + outlink_url_link +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>';

            $("#swipez-offer").append(popup_str);

            var modal = document.getElementById('cudu_myModal_' + o_p_id);
            var modalClose = document.getElementById('cudu_popup_close_' + o_p_id);
            modal.style.display = 'block';
            //var span = document.getElementsByClassName("cudu_popup_close")[0];
            //var span = document.getElementsByClassName("cudu_popup_close");
            modalClose.onclick = function () {
                modal.style.display = "none";
            }
            // When the user clicks anywhere outside of the modal, close it
            window.onclick = function (event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
        }
    }
    xhr.send();
}

function getStoreOffers(store_id, Mkey, Tkey)
{
    try {
        document.getElementById("cudubest").className = "classic-menu-dropdown";
        document.getElementById("cudustore").className = "classic-menu-dropdown active";
        document.getElementById("cuduexclusive").className = "classic-menu-dropdown";
    } catch (o)
    {

    }
    var store_id = parseInt(store_id);
    var m_key = Mkey;
    var t_key = Tkey;
    let xhr = new XMLHttpRequest;
    var couponMenu = 'true';
    try {
        var couponMenu = document.getElementById('swipez-offer').getAttribute("coupon-menu");
    } catch (o)
    {

    }
    //alert('https://intapi.swipez.in/api/v1/coupon/getstoreoffer/'+store_id+'/'+m_key);
    xhr.open('GET', 'https://intapi.swipez.in/api/v1/coupon/getstoreoffer/' + store_id + '/' + m_key + '/' + t_key, true)
    xhr.onload = function ()
    {
        if (this.status === 200)
        {
            var offers = JSON.parse(this.responseText);
            var offer_public_id = '';
            var swipezMKey_str = "'" + m_key + "'";
            var swipezTKey_str = "'" + t_key + "'";
            var offer_str_li = '';
            if (couponMenu == 'true')
            {
                var offer_str_li = '<ul class="cudu_nav_ul"><li class="cudu_nav_li"><a onclick="cudu_store();"> Stores</li><li class="cudu_nav_li"><a onclick="cudu_offers();"> Best Offers</li><li class="cudu_nav_li"><a onclick="cudu_exclusive_offers();"> Exclusive Offers</li></ul><h4>Top Stores</h4><ul>';
            }
            var img_name = '';
            var description = '';
            var title = '';
            var outlink_url = '';


            $.each(offers['offers'], function (index, value) {
                img_name = "'" + value.store.logo_rectangle_url + "'";
                description = "'" + value.description + "'";
                description = description.replace(/\'/g, "");
                description = description.replace(/\"/g, "");
                description = description.substring(0, 150);
                ;

                title = "'" + value.title + "'";
                title = title.replace(/\'/g, "");
                title = title.replace(/\"/g, "");


                offer_public_id = "'" + value.public_id + "'";
                outlink_url = "'" + value.outlink_url + "'";

                offer_str_li += '<li class="cudu_offer_li_table" ><table style="margin: 3px; height: 300px; width : 250px; box-shadow: 1px 1px 1px 1px lightgrey; "><tr><td style="text-align:center;"><div><img src="' + value.store.logo_rectangle_url + '" /> </div></td></tr><tr><td style="text-align:center;"><div><h3 class="cudu_title">' + title + '</h3></div></td></tr><tr><td style="text-align:center;"><div><span>' + description + '</span></div></td></tr>';

                description = "'" + description + "'";
                title = "'" + title + "'";

                offer_str_li += '<tr><td style="text-align:center;"><div><span><button class="button" onclick="getCouponCode(' + offer_public_id + ',' + swipezMKey_str + ',' + swipezTKey_str + ',' + img_name + ',' + title + ',' + description + ',' + outlink_url + ');">Get Deal</button></span></div></td></tr></table></li>';

            });
            offer_str_li += '</ul>';
            $('#swipez-offer').html(offer_str_li);
        }
    }
    xhr.send();
}


