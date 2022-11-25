
<section class="banner-img" style="background:url('{$json.content.home.banner.value}');" id="content_home_banner_value"> <div class="btnEditBox">
        <button onclick="display_image('content_home_banner_value', '{$json.content.home.banner.info}', '{$json.content.home.banner.max}');" com_logo_id="23" id="edit_banner_img" class="btnEditImg"><i class="fa fa-edit"></i> Edit Image</button></div>
    <div class="btnEditBox btmAlign"> 

        <button onclick="display_status('section_home_status', '0');" id="section_home_status_disable" {if $json.section.home.status==0} style="display: none;"{/if} class="btnDesableSec"><i class="fa fa-close"></i> Disable Section</button> 
        <button onclick="display_status('section_home_status', '1');" id="section_home_status_enable" {if $json.section.home.status==1} style="display: none;"{/if} class="btnEnableSec"><i class="fa fa-check"></i> Enable Section</button>
    </div>
    <h1><span id="content_home_title_value">{$json.content.home.title.value}</span><div class="btnEditBox">
            <button onclick="display_status('content_home_title_status', '0');" id="content_home_title_status_disable" {if $json.content.home.title.status==0} style="display: none;"{/if} class="btnDesableSec"><i class="fa fa-close"></i> Disable</button> 
            <button onclick="display_status('content_home_title_status', '1');" id="content_home_title_status_enable" {if $json.content.home.title.status==1} style="display: none;"{/if} class="btnEnableSec"><i class="fa fa-check"></i> Enable</button>
            <button header_1_id="1" onclick="display_text('content_home_title_value');" class="btnEditText"><i class="fa fa-text-width"></i> Edit Text</button>
        </div></h1>
    <h5><span id="content_home_caption_value">{$json.content.home.caption.value}</span><div class="btnEditBox">
            <button onclick="display_status('content_home_caption_status', '0');" id="content_home_caption_status_disable" {if $json.content.home.caption.status==0} style="display: none;"{/if} class="btnDesableSec"><i class="fa fa-close"></i> Disable</button> 
            <button onclick="display_status('content_home_caption_status', '1');" id="content_home_caption_status_enable" {if $json.content.home.caption.status==1} style="display: none;"{/if} class="btnEnableSec"><i class="fa fa-check"></i> Enable</button>
            <button header_2_id="2" onclick="display_text('content_home_caption_value');" id="header_edit_comp_2" class="btnEditText"><i class="fa fa-text-width"></i> Edit Text</button></div></h5>
</section>
<section class="info"> <div class="btnEditBox btmAlign"><button pay_bil_id="3" id="edit_pay_bill" onclick="display_text('section_paybill_title');" class="btnEditText"><i class="fa fa-text-width"></i> Edit Text</button> 
        <button onclick="display_status('section_paybill_status', '0');" id="section_paybill_status_disable" {if $json.section.paybill.status==0} style="display: none;"{/if} class="btnDesableSec"><i class="fa fa-close"></i> Disable Section</button> 
        <button onclick="display_status('section_paybill_status', '1');" id="section_paybill_status_enable" {if $json.section.paybill.status==1} style="display: none;"{/if} class="btnEnableSec"><i class="fa fa-check"></i> Enable Section</button>
    </div>
    <center >
        <h4><span id="section_paybill_title">{$json.section.paybill.title}</span></h4>
        <a href="#" class="button">PAY NOW</a>
    </center>
</section>
<section class="about-us" id="s2"> 
    <div class="btnEditBox btmAlign">
        <!--<button class="btnEditText"><i class="fa fa-text-width"></i> Edit Text</button>-->
        <button onclick="display_status('section_aboutus_status', '0');" id="section_aboutus_status_disable" {if $json.section.aboutus.status==0} style="display: none;"{/if} class="btnDesableSec"><i class="fa fa-close"></i> Disable Section</button> 
        <button onclick="display_status('section_aboutus_status', '1');" id="section_aboutus_status_enable" {if $json.section.aboutus.status==1} style="display: none;"{/if} class="btnEnableSec"><i class="fa fa-check"></i> Enable Section</button>
    </div>
    <center>
        <h2 class="sec-ttl"><div class="btnEditBox btmAlign"><button onclick="display_text('section_aboutus_title');" edit_abt_id="4" id="edit_abt_us" class="btnEditText"><i class="fa fa-text-width"></i> Edit Text</button></div>
            <div class="inside"><span id="section_aboutus_title">{$json.section.aboutus.title}</span></div>
        </h2>
        <div id="desciption_set_abt" class="data"><div class="btnEditBox"><button onclick="display_textarea('content_aboutus_text_value');" abt_des_id="44" id="edit_abt_description" class="btnEditText"><i class="fa fa-text-width"></i> Edit Text</button> </div>
            <span id="content_aboutus_text_value"> {$json.content.aboutus.text.value}</span>
        </div>
    </center>
</section>
<section class="our-services" id="s3"><div class="btnEditBox btmAlign"> 
        <button onclick="display_status('section_services_status', '0');" id="section_services_status_disable" {if $json.section.services.status==0} style="display: none;"{/if} class="btnDesableSec"><i class="fa fa-close"></i> Disable Section</button> 
        <button onclick="display_status('section_services_status', '1');" id="section_services_status_enable" {if $json.section.services.status==1} style="display: none;"{/if} class="btnEnableSec"><i class="fa fa-check"></i> Enable Section</button>
    </div>
    <center>
        <h2 class="sec-ttl"><div class="btnEditBox btmAlign"><button onclick="display_text('section_services_title');" our_service_id="5" id="edit_our_service" class="btnEditText"><i class="fa fa-text-width"></i> Edit Text</button></div>
            <div><span id="section_services_title">{$json.section.services.title}</span></div>
        </h2>
        <ul class="our-service-list">
            <li>
                <div class="icon"><div class="btnEditBox"><button onclick="display_image('content_services_service1_icon', '{$json.content.services.service1.info}', '{$json.content.services.service1.max}');" uor_ser_img_id_1="24" id="edit_our_service_img_1" class="btnEditImg"><i class="fa fa-edit"></i> Edit Image</button></div><img style="max-height: 42px;max-width: 42px;" id="content_services_service1_icon" src="{$json.content.services.service1.icon}" /></div>
                <div id="our_des_1" class="data"><div class="btnEditBox"><button onclick="display_textarea('content_services_service1_text');" our_disc_id_1="45"  id="our_service_description_1" class="btnEditText"><i class="fa fa-text-width"></i> Edit Text</button></div>                                 
                    <span id="content_services_service1_text"> <p>{$json.content.services.service1.text}</p></span>
                </div>
                <div class="btnEditBox btmAlign">
                    <button onclick="display_status('content_services_service1_status', '0');" id="content_services_service1_status_disable" 
                    {if $json.content.services.service1.status==0} style="display: none;"{/if} class="btnDesableSec"><i class="fa fa-close"></i> Disable</button> 
                <button onclick="display_status('content_services_service1_status', '1');" id="content_services_service1_status_enable" 
                {if $json.content.services.service1.status==1} style="display: none;"{/if} class="btnEnableSec"><i class="fa fa-check"></i> Enable</button>
        </div>
    </li>
    <li>
        <div class="icon"><div class="btnEditBox"><button onclick="display_image('content_services_service2_icon', '{$json.content.services.service2.info}', '{$json.content.services.service2.max}');" uor_ser_img_id_2="25" id="edit_our_service_img_2" class="btnEditImg"><i class="fa fa-edit"></i> Edit Image</button></div><img style="max-height: 42px;max-width: 42px;" id="content_services_service2_icon" src="{$json.content.services.service2.icon}" /></div>
        <div id="our_des_2" class="data"><div class="btnEditBox"><button onclick="display_textarea('content_services_service2_text');" our_disc_id_2="46"  id="our_service_description_2" class="btnEditText"><i class="fa fa-text-width"></i> Edit Text</button></div>

            <span id="content_services_service2_text"> <p >{$json.content.services.service2.text}</p></span>
        </div>
        <div class="btnEditBox btmAlign">
            <button onclick="display_status('content_services_service2_status', '0');" id="content_services_service2_status_disable" {if $json.content.services.service2.status==0} style="display: none;"{/if} class="btnDesableSec"><i class="fa fa-close"></i> Disable</button> 
            <button onclick="display_status('content_services_service2_status', '1');" id="content_services_service2_status_enable" {if $json.content.services.service2.status==1} style="display: none;"{/if} class="btnEnableSec"><i class="fa fa-check"></i> Enable</button>
        </div>
    </li>
    <li>
        <div class="icon"><div class="btnEditBox"><button onclick="display_image('content_services_service3_icon', '{$json.content.services.service3.info}', '{$json.content.services.service3.max}');" uor_ser_img_id_3="26" id="edit_our_service_img_3" class="btnEditImg"><i class="fa fa-edit"></i> Edit Image</button></div><img style="max-height: 42px;max-width: 42px;" id="content_services_service3_icon" src="{$json.content.services.service3.icon}" /></div>
        <div id="our_des_3" class="data"><div class="btnEditBox"><button onclick="display_textarea('content_services_service3_text');" our_disc_id_3="47"  id="our_service_description_3" class="btnEditText"><i class="fa fa-text-width"></i> Edit Text</button></div>

            <span id="content_services_service3_text"><p>{$json.content.services.service3.text}</p></span>
        </div>
        <div class="btnEditBox btmAlign">
            <button onclick="display_status('content_services_service3_status', '0');" id="content_services_service3_status_disable" {if $json.content.services.service3.status==0} style="display: none;"{/if} class="btnDesableSec"><i class="fa fa-close"></i> Disable</button> 
            <button onclick="display_status('content_services_service3_status', '1');" id="content_services_service3_status_enable" {if $json.content.services.service3.status==1} style="display: none;"{/if} class="btnEnableSec"><i class="fa fa-check"></i> Enable</button>
        </div>
    </li>
</ul>
<ul class="our-service-list">
    <li>
        <div class="icon"><div class="btnEditBox"><button onclick="display_image('content_services_service4_icon', '{$json.content.services.service4.info}', '{$json.content.services.service4.max}');" uor_ser_img_id_4="27" id="edit_our_service_img_4" class="btnEditImg"><i class="fa fa-edit"></i> Edit Image</button></div><img style="max-height: 42px;max-width: 42px;" id="content_services_service4_icon" src="{$json.content.services.service4.icon}" /></div>
        <div id="our_des_4" class="data"><div class="btnEditBox"><button onclick="display_textarea('content_services_service4_text');" our_disc_id_4="48"  id="our_service_description_4" class="btnEditText"><i class="fa fa-text-width"></i> Edit Text</button></div>

            <span id="content_services_service4_text"><p>{$json.content.services.service4.text}</p></span>
        </div>
        <div class="btnEditBox btmAlign">
            <button onclick="display_status('content_services_service4_status', '0');" id="content_services_service4_status_disable" {if $json.content.services.service4.status==0} style="display: none;"{/if} class="btnDesableSec"><i class="fa fa-close"></i> Disable</button> 
            <button onclick="display_status('content_services_service4_status', '1');" id="content_services_service4_status_enable" {if $json.content.services.service4.status==1} style="display: none;"{/if} class="btnEnableSec"><i class="fa fa-check"></i> Enable</button>
        </div>
    </li>
    <li>
        <div class="icon"><div class="btnEditBox"><button onclick="display_image('content_services_service5_icon', '{$json.content.services.service5.info}', '{$json.content.services.service5.max}');" uor_ser_img_id_5="28" id="edit_our_service_img_5" class="btnEditImg"><i class="fa fa-edit"></i> Edit Image</button></div><img style="max-height: 42px;max-width: 42px;" id="content_services_service5_icon" src="{$json.content.services.service5.icon}" /></div>
        <div id="our_des_5" class="data"><div class="btnEditBox"><button onclick="display_textarea('content_services_service5_text');" our_disc_id_5="49"  id="our_service_description_5" class="btnEditText"><i class="fa fa-text-width"></i> Edit Text</button></div>

            <span id="content_services_service5_text"><p>{$json.content.services.service5.text}</p></span>
        </div>
        <div class="btnEditBox btmAlign">
            <button onclick="display_status('content_services_service5_status', '0');" id="content_services_service5_status_disable" {if $json.content.services.service5.status==0} style="display: none;"{/if} class="btnDesableSec"><i class="fa fa-close"></i> Disable</button> 
            <button onclick="display_status('content_services_service5_status', '1');" id="content_services_service5_status_enable" {if $json.content.services.service5.status==1} style="display: none;"{/if} class="btnEnableSec"><i class="fa fa-check"></i> Enable</button>
        </div>
    </li>
    <li>
        <div class="icon"><div class="btnEditBox"><button  onclick="display_image('content_services_service6_icon', '{$json.content.services.service6.info}', '{$json.content.services.service6.max}');" uor_ser_img_id_6="29" id="edit_our_service_img_6" class="btnEditImg"><i class="fa fa-edit"></i> Edit Image</button></div><img style="max-height: 42px;max-width: 42px;" id="content_services_service6_icon" src="{$json.content.services.service6.icon}" /></div>
        <div id="our_des_6" class="data"><div class="btnEditBox"><button onclick="display_textarea('content_services_service6_text');" our_disc_id_6="50"  id="our_service_description_6" class="btnEditText"><i class="fa fa-text-width"></i> Edit Text</button></div>

            <span id="content_services_service6_text"><p >{$json.content.services.service6.text}</p></span>
        </div>
        <div class="btnEditBox btmAlign">
            <button onclick="display_status('content_services_service6_status', '0');" id="content_services_service6_status_disable" {if $json.content.services.service6.status==0} style="display: none;"{/if} class="btnDesableSec"><i class="fa fa-close"></i> Disable</button> 
            <button onclick="display_status('content_services_service6_status', '1');" id="content_services_service6_status_enable" {if $json.content.services.service6.status==1} style="display: none;"{/if} class="btnEnableSec"><i class="fa fa-check"></i> Enable</button>
        </div>
    </li>
</ul>
</center>
</section>


<section class="the-team"><div class="btnEditBox btmAlign">
        <button onclick="display_status('section_team_status', '0');" id="section_team_status_disable" {if $json.section.team.status==0} style="display: none;"{/if} class="btnDesableSec"><i class="fa fa-close"></i> Disable Section</button> 
        <button onclick="display_status('section_team_status', '1');" id="section_team_status_enable" {if $json.section.team.status==1} style="display: none;"{/if} class="btnEnableSec"><i class="fa fa-check"></i> Enable Section</button>
    </div>
    <center>
        <h2 class="sec-ttl"><div class="btnEditBox btmAlign"><button onclick="display_text('section_team_title');" the_team_id="6" id="the_team_edit" class="btnEditText"><i class="fa fa-text-width"></i> Edit Text</button></div>
            <div><span id="section_team_title">{$json.section.team.title}</span></div>
        </h2>
        <ul class="team-list">
            <li>
                <div class="img-box"><div class="btnEditBox"><button onclick="display_image('content_team_member1_photo', '{$json.content.team.member1.info}', '{$json.content.team.member1.max}');" the_team_img_id_1="30" id="edit_the_team_img_1" class="btnEditImg"><i class="fa fa-edit"></i> Edit Image</button></div><img style="max-height: 320px;max-width: 320px;" id="content_team_member1_photo" src="{$json.content.team.member1.photo}" /></div>
                <div class="data"><div class="btnEditBox"><button onclick="display_textarea('content_team_member1_text');" team_name_id="7" id="team_name_edit_1" class="btnEditText"><i class="fa fa-text-width"></i> Edit Text</button></div>
                    <div class="btnEditBox btmAlign">
                        <button onclick="display_status('content_team_member1_status', '0');" id="content_team_member1_status_disable" {if $json.content.team.member1.status==0} style="display: none;"{/if} class="btnDesableSec"><i class="fa fa-close"></i> Disable</button> 
                        <button onclick="display_status('content_team_member1_status', '1');" id="content_team_member1_status_enable" {if $json.content.team.member1.status==1} style="display: none;"{/if} class="btnEnableSec"><i class="fa fa-check"></i> Enable</button>
                    </div>
                    <span id="content_team_member1_text">
                        {$json.content.team.member1.text}
                    </span>
                </div>
            </li>
            <li>
                <div class="img-box"><div class="btnEditBox"><button onclick="display_image('content_team_member2_photo', '{$json.content.team.member2.info}', '{$json.content.team.member2.max}');" the_team_img_id_2="31" id="edit_the_team_img_2" class="btnEditImg"><i class="fa fa-edit"></i> Edit Image</button></div><img style="max-height: 320px;max-width: 320px;" id="content_team_member2_photo" src="{$json.content.team.member2.photo}" /></div>
                <div class="data"><div class="btnEditBox"><button onclick="display_textarea('content_team_member2_text');" team_name_id_2="8" id="team_name_edit_2" class="btnEditText"><i class="fa fa-text-width"></i> Edit Text</button></div>
                    <div class="btnEditBox btmAlign">
                        <button onclick="display_status('content_team_member2_status', '0');" id="content_team_member2_status_disable" {if $json.content.team.member2.status==0} style="display: none;"{/if} class="btnDesableSec"><i class="fa fa-close"></i> Disable</button> 
                        <button onclick="display_status('content_team_member2_status', '1');" id="content_team_member2_status_enable" {if $json.content.team.member2.status==1} style="display: none;"{/if} class="btnEnableSec"><i class="fa fa-check"></i> Enable</button>
                    </div>
                    <span id="content_team_member2_text">
                        {$json.content.team.member2.text}
                    </span>
                </div>
            </li>
            <li>
                <div class="img-box"><div class="btnEditBox"><button onclick="display_image('content_team_member3_photo', '{$json.content.team.member3.info}', '{$json.content.team.member3.max}');" the_team_img_id_3="32" id="edit_the_team_img_3" class="btnEditImg"><i class="fa fa-edit"></i> Edit Image</button></div><img style="max-height: 320px;max-width: 320px;" id="content_team_member3_photo" src="{$json.content.team.member3.photo}" /></div>
                <div class="data"><div class="btnEditBox"><button onclick="display_textarea('content_team_member3_text');" team_name_id_3="9" id="team_name_edit_3" class="btnEditText"><i class="fa fa-text-width"></i> Edit Text</button></div>
                    <div class="btnEditBox btmAlign">
                        <button onclick="display_status('content_team_member3_status', '0');" id="content_team_member3_status_disable" {if $json.content.team.member3.status==0} style="display: none;"{/if} class="btnDesableSec"><i class="fa fa-close"></i> Disable</button> 
                        <button onclick="display_status('content_team_member3_status', '1');" id="content_team_member3_status_enable" {if $json.content.team.member3.status==1} style="display: none;"{/if} class="btnEnableSec"><i class="fa fa-check"></i> Enable</button>
                    </div>
                    <span id="content_team_member3_text">
                        {$json.content.team.member3.text}
                    </span>
                </div>
            </li>
        </ul>
    </center>
</section>
<section class="testimonial"><div class="btnEditBox btmAlign">
        <button onclick="display_status('section_testimonial_status', '0');" id="section_testimonial_status_disable" {if $json.section.testimonial.status==0} style="display: none;"{/if} class="btnDesableSec"><i class="fa fa-close"></i> Disable Section</button> 
        <button onclick="display_status('section_testimonial_status', '1');" id="section_testimonial_status_enable" {if $json.section.testimonial.status==1} style="display: none;"{/if} class="btnEnableSec"><i class="fa fa-check"></i> Enable Section</button>
    </div>
    <center>
        <h2 class="sec-ttl"><div class="btnEditBox btmAlign"><button onclick="display_text('section_testimonial_title');" class="btnEditText"><i class="fa fa-text-width"></i> Edit Text</button></div>
            <div><span id="section_testimonial_title">{$json.section.testimonial.title} </span></div>
        </h2>
        <ul class="testimonial-list">
            <li>
                <div class="img-box"><div customer_testimonial_img_1_id="33" id="edit_customer_testimonial_img_1" class="btnEditBox">
                        <button onclick="display_image('content_testimonial_message1_photo', '{$json.content.testimonial.message1.info}', '{$json.content.testimonial.message1.max}');" class="btnEditImg"><i class="fa fa-edit"></i> Edit Image</button></div>
                    <img style="max-height: 150px;max-width: 150px;" id="content_testimonial_message1_photo" src="{$json.content.testimonial.message1.photo}" /></div>
                <div class="data"><div class="btnEditBox"><button onclick="display_textarea('content_testimonial_message1_text');" custimi_id_1="51" id="custimaision_edit_des_1" class="btnEditText"><i class="fa fa-text-width"></i> Edit Text</button></div>
                    <div class="btnEditBox btmAlign"> 
                        <button onclick="display_status('content_testimonial_message1_status', '0');" id="content_testimonial_message1_status_disable" {if $json.content.testimonial.message1.status==0} style="display: none;"{/if} class="btnDesableSec"><i class="fa fa-close"></i> Disable</button> 
                        <button onclick="display_status('content_testimonial_message1_status', '1');" id="content_testimonial_message1_status_enable" {if $json.content.testimonial.message1.status==1} style="display: none;"{/if} class="btnEnableSec"><i class="fa fa-check"></i> Enable</button>
                    </div>
                    <span id="content_testimonial_message1_text">
                        {$json.content.testimonial.message1.text} 
                    </span>
                </div>
            </li>
            <li>
                <div class="img-box"><div class="btnEditBox"><button onclick="display_image('content_testimonial_message2_photo', '{$json.content.testimonial.message2.info}', '{$json.content.testimonial.message2.max}');"customer_testimonial_img_2_id="34" id="edit_customer_testimonial_img_2" class="btnEditImg"><i class="fa fa-edit"></i> Edit Image</button></div><img style="max-height: 150px;max-width: 150px;" id="content_testimonial_message2_photo" src="{$json.content.testimonial.message2.photo}" /></div>
                <div id="ref_cons_2" class="data"><div class="btnEditBox"><button onclick="display_textarea('content_testimonial_message2_text');" custimi_id_2="52" id="custimaision_edit_des_2" class="btnEditText"><i class="fa fa-text-width"></i> Edit Text</button></div>
                    <div class="btnEditBox btmAlign">
                        <button onclick="display_status('content_testimonial_message2_status', '0');" id="content_testimonial_message2_status_disable" {if $json.content.testimonial.message2.status==0} style="display: none;"{/if} class="btnDesableSec"><i class="fa fa-close"></i> Disable</button> 
                        <button onclick="display_status('content_testimonial_message2_status', '1');" id="content_testimonial_message2_status_enable" {if $json.content.testimonial.message2.status==1} style="display: none;"{/if} class="btnEnableSec"><i class="fa fa-check"></i> Enable</button>
                    </div>
                    <span id="content_testimonial_message2_text">{$json.content.testimonial.message2.text}</span>
                </div>
            </li>
            <li>
                <div class="img-box"><div class="btnEditBox"><button onclick="display_image('content_testimonial_message3_photo', '{$json.content.testimonial.message3.info}', '{$json.content.testimonial.message3.max}');" customer_testimonial_img_3_id="35" id="edit_customer_testimonial_img_3" class="btnEditImg"><i class="fa fa-edit"></i> Edit Image</button></div><img style="max-height: 150px;max-width: 150px;" id="content_testimonial_message3_photo" src="{$json.content.testimonial.message3.photo}" /></div>
                <div id="ref_cons_3" class="data"><div class="btnEditBox"><button onclick="display_textarea('content_testimonial_message3_text');" custimi_id_3="53" id="custimaision_edit_des_3" class="btnEditText"><i class="fa fa-text-width"></i> Edit Text</button></div>
                    <div class="btnEditBox btmAlign"> 
                        <button onclick="display_status('content_testimonial_message3_status', '0');" id="content_testimonial_message3_status_disable" {if $json.content.testimonial.message3.status==0} style="display: none;"{/if} class="btnDesableSec"><i class="fa fa-close"></i> Disable</button> 
                        <button onclick="display_status('content_testimonial_message3_status', '1');" id="content_testimonial_message3_status_enable" {if $json.content.testimonial.message3.status==1} style="display: none;"{/if} class="btnEnableSec"><i class="fa fa-check"></i> Enable</button>
                    </div>
                    <span id="content_testimonial_message3_text">{$json.content.testimonial.message3.text}</span>
                </div>
            </li>
        </ul>
    </center>
</section>

<section class="our-projects" id="s5"><div class="btnEditBox btmAlign"> 
        <button onclick="display_status('section_project_status', '0');" id="section_project_status_disable" {if $json.section.project.status==0} style="display: none;"{/if} class="btnDesableSec"><i class="fa fa-close"></i> Disable Section</button> 
        <button onclick="display_status('section_project_status', '1');" id="section_project_status_enable" {if $json.section.project.status.status==1} style="display: none;"{/if} class="btnEnableSec"><i class="fa fa-check"></i> Enable Section</button>
    </div>
    <center>
        <h2 class="sec-ttl"><div class="btnEditBox btmAlign"><button onclick="display_text('section_project_title');" class="btnEditText"><i class="fa fa-text-width"></i> Edit Text</button></div>
            <div id="section_project_title">{$json.section.project.title}</div>
        </h2>
        <ul class="our-projects-list">
            <li><div class="btnEditBox"><button onclick="display_image('content_project_project1_image', '{$json.content.project.project1.info}', '{$json.content.project.project1.max}');" our_proj_img_id_2="37" id="edit_our_proj_img_2" class="btnEditImg"><i class="fa fa-edit"></i> Edit Image</button>
                    <button onclick="display_status('content_project_project1_status', '0');" id="content_project_project1_status_disable" {if $json.content.project.project1.status==0} style="display: none;"{/if} class="btnDesableSec"><i class="fa fa-close"></i> Disable</button> 
                    <button onclick="display_status('content_project_project1_status', '1');" id="content_project_project1_status_enable" {if $json.content.project.project1.status==1} style="display: none;"{/if} class="btnEnableSec"><i class="fa fa-check"></i> Enable</button>
                </div><a ><img style="max-height: 320px;max-width: 340px;min-height: 320px;min-width: 340px;" id="content_project_project1_image" src="{$json.content.project.project1.image}" /><span><div class="btnEditBox"><button onclick="display_text('content_project_project1_text');" our_project_id_2="13" id="edit_our_proj_name2" class="btnEditText"><i class="fa fa-text-width"></i> Edit Text</button> </div><div id="content_project_project1_text">{$json.content.project.project1.text}</div></span></a>
            </li>


            <li><div class="btnEditBox"><button onclick="display_image('content_project_project2_image', '{$json.content.project.project2.info}', '{$json.content.project.project2.max}');"our_proj_img_id_2="37" id="edit_our_proj_img_2" class="btnEditImg"><i class="fa fa-edit"></i> Edit Image</button>
                    <button onclick="display_status('content_project_project2_status', '0');" id="content_project_project2_status_disable" {if $json.content.project.project2.status==0} style="display: none;"{/if} class="btnDesableSec"><i class="fa fa-close"></i> Disable</button> 
                    <button onclick="display_status('content_project_project2_status', '1');" id="content_project_project2_status_enable" {if $json.content.project.project2.status==1} style="display: none;"{/if} class="btnEnableSec"><i class="fa fa-check"></i> Enable</button>
                </div><a ><img  style="max-height: 320px;max-width: 340px;min-height: 320px;min-width: 340px;" id="content_project_project2_image" src="{$json.content.project.project2.image}" /><span><div class="btnEditBox"><button onclick="display_text('content_project_project2_text');" our_project_id_2="13" id="edit_our_proj_name2" class="btnEditText"><i class="fa fa-text-width"></i> Edit Text</button> </div><div id="content_project_project2_text">{$json.content.project.project2.text}</div></span></a>
            </li>

            <li><div class="btnEditBox"><button onclick="display_image('content_project_project3_image', '{$json.content.project.project3.info}', '{$json.content.project.project3.max}');" our_proj_img_id_3="38" id="edit_our_proj_img_3" class="btnEditImg"><i class="fa fa-edit"></i> Edit Image</button>
                    <button onclick="display_status('content_project_project3_status', '0');" id="content_project_project3_status_disable" {if $json.content.project.project3.status==0} style="display: none;"{/if} class="btnDesableSec"><i class="fa fa-close"></i> Disable</button> 
                    <button onclick="display_status('content_project_project3_status', '1');" id="content_project_project3_status_enable" {if $json.content.project.project3.status==1} style="display: none;"{/if} class="btnEnableSec"><i class="fa fa-check"></i> Enable</button>
                </div><a ><img  style="max-height: 320px;max-width: 340px;min-height: 320px;min-width: 340px;" id="content_project_project3_image" src="{$json.content.project.project3.image}" /><span><div class="btnEditBox"><button onclick="display_text('content_project_project3_text');" our_project_id_3="14" id="edit_our_proj_name3" class="btnEditText"><i class="fa fa-text-width"></i> Edit Text</button> </div><div id="content_project_project3_text">{$json.content.project.project3.text}</div></span></a>
            </li>

            <li><div class="btnEditBox"><button onclick="display_image('content_project_project4_image', '{$json.content.project.project4.info}', '{$json.content.project.project4.max}');" our_proj_img_id_4="39" id="edit_our_proj_img_4" class="btnEditImg"><i class="fa fa-edit"></i> Edit Image</button>
                    <button onclick="display_status('content_project_project4_status', '0');" id="content_project_project4_status_disable" {if $json.content.project.project4.status==0} style="display: none;"{/if} class="btnDesableSec"><i class="fa fa-close"></i> Disable</button> 
                    <button onclick="display_status('content_project_project4_status', '1');" id="content_project_project4_status_enable" {if $json.content.project.project4.status==1} style="display: none;"{/if} class="btnEnableSec"><i class="fa fa-check"></i> Enable</button>
                </div><a ><img  style="max-height: 320px;max-width: 340px;min-height: 320px;min-width: 340px;" id="content_project_project4_image" src="{$json.content.project.project4.image}" /><span><div class="btnEditBox"><button onclick="display_text('content_project_project4_text');" our_project_id_4="15" id="edit_our_proj_name4" class="btnEditText"><i class="fa fa-text-width"></i> Edit Text</button> </div><div id="content_project_project4_text">{$json.content.project.project4.text}</div></span></a>
            </li>

            <li class="large"><div class="btnEditBox"><button onclick="display_image('content_project_project5_image', '{$json.content.project.project5.info}', '{$json.content.project.project5.max}');" our_proj_img_id_5="40" id="edit_our_proj_img_5" class="btnEditImg"><i class="fa fa-edit"></i> Edit Image</button>
                    <button onclick="display_status('content_project_project5_status', '0');" id="content_project_project5_status_disable" {if $json.content.project.project5.status==0} style="display: none;"{/if} class="btnDesableSec"><i class="fa fa-close"></i> Disable</button> 
                    <button onclick="display_status('content_project_project5_status', '1');" id="content_project_project5_status_enable" {if $json.content.project.project5.status==1} style="display: none;"{/if} class="btnEnableSec"><i class="fa fa-check"></i> Enable</button>
                </div><a ><img  style="max-height: 320px;max-width: 690px;min-height: 320px;min-width: 690px;" id="content_project_project5_image" src="{$json.content.project.project5.image}" /><span><div class="btnEditBox"><button onclick="display_text('content_project_project5_text');" our_project_id_5="16" id="edit_our_proj_name5" class="btnEditText"><i class="fa fa-text-width"></i> Edit Text</button> </div><div id="content_project_project5_text">{$json.content.project.project5.text}</div></span></a>
            </li>

            <li class="large"><div class="btnEditBox"><button onclick="display_image('content_project_project6_image', '{$json.content.project.project6.info}');" our_proj_img_id_6="41" id="edit_our_proj_img_6" class="btnEditImg"><i class="fa fa-edit"></i> Edit Image</button>
                    <button onclick="display_status('content_project_project6_status', '0');" id="content_project_project6_status_disable" {if $json.content.project.project6.status==0} style="display: none;"{/if} class="btnDesableSec"><i class="fa fa-close"></i> Disable</button> 
                    <button onclick="display_status('content_project_project6_status', '1');" id="content_project_project6_status_enable" {if $json.content.project.project6.status==1} style="display: none;"{/if} class="btnEnableSec"><i class="fa fa-check"></i> Enable</button>
                </div><a ><img  style="max-height: 320px;max-width: 690px;min-height: 320px;min-width: 690px;" id="content_project_project6_image" src="{$json.content.project.project6.image}" /><span><div class="btnEditBox"><button onclick="display_text('content_project_project6_text');" our_project_id_6="17" id="edit_our_proj_name6" class="btnEditText"><i class="fa fa-text-width"></i> Edit Text</button> </div><div id="content_project_project6_text">{$json.content.project.project6.text}</div></span></a>
            </li>

            <li><div class="btnEditBox"><button onclick="display_image('content_project_project7_image', '{$json.content.project.project7.info}', '{$json.content.project.project7.max}');" our_proj_img_id_7="42" id="edit_our_proj_img_7" class="btnEditImg"><i class="fa fa-edit"></i> Edit Image</button>
                    <button onclick="display_status('content_project_project7_status', '0');" id="content_project_project7_status_disable" {if $json.content.project.project7.status==0} style="display: none;"{/if} class="btnDesableSec"><i class="fa fa-close"></i> Disable</button> 
                    <button onclick="display_status('content_project_project7_status', '1');" id="content_project_project7_status_enable" {if $json.content.project.project7.status==1} style="display: none;"{/if} class="btnEnableSec"><i class="fa fa-check"></i> Enable</button>
                </div><a ><img  style="max-height: 320px;max-width: 340px;min-height: 320px;min-width: 340px;" id="content_project_project7_image" src="{$json.content.project.project7.image}" /><span><div class="btnEditBox"><button onclick="display_text('content_project_project7_text');" our_project_id_7="18" id="edit_our_proj_name7" class="btnEditText"><i class="fa fa-text-width"></i> Edit Text</button> </div><div id="content_project_project7_text">{$json.content.project.project7.text}</div></span></a></li>
        </ul>
    </center>
</section>
<section class="contact-us" id="s6"><div class="btnEditBox btmAlign"> 

        <button onclick="display_status('section_contactus_status', '0');" id="section_contactus_status_disable" {if $json.section.contactus.status==0} style="display: none;"{/if} class="btnDesableSec"><i class="fa fa-close"></i> Disable Section</button> 
        <button onclick="display_status('section_contactus_status', '1');" id="section_contactus_status_enable" {if $json.section.contactus.status==1} style="display: none;"{/if} class="btnEnableSec"><i class="fa fa-check"></i> Enable Section</button>
    </div>
    <center>
        <h2 class="sec-ttl"><div class="btnEditBox btmAlign"><button onclick="display_text('section_contactus_title');" class="btnEditText"><i class="fa fa-text-width"></i> Edit Text</button></div>
            <div id="section_contactus_title">{$json.section.contactus.title}</div>
        </h2>
        <div class="contact-form-wrapp">
            <ul class="contact-form"><div class="btnEditBox btmAlign"> 
                    <button onclick="display_status('content_contactus_contact_status', '0');" id="content_contactus_contact_status_disable" {if $json.content.contactus.contact.status==0} style="display: none;"{/if} class="btnDesableSec"><i class="fa fa-close"></i> Disable Section</button> 
                    <button onclick="display_status('content_contactus_contact_status', '1');" id="content_contactus_contact_status_enable" {if $json.content.contactus.contact.status==1} style="display: none;"{/if} class="btnEnableSec"><i class="fa fa-check"></i> Enable Section</button>

                </div>
                <li>
                    <input type="text" disabled="" class="text" placeholder="Name"/><div class="btnEditBox btmAlign">  </div>
                </li>
                <li>
                    <input type="text" disabled="" class="text" placeholder="Email"/><div class="btnEditBox btmAlign">  </div>
                </li>
                <li>
                    <input type="text" disabled="" class="text" placeholder="Mobile"/><div class="btnEditBox btmAlign">  </div>
                </li>
                <li>
                    <input type="text" disabled="" class="text" placeholder="Message"/><div class="btnEditBox btmAlign"> </div>
                </li>
                <li class="btn">
                    <input type="button" class="button" value="Send"/><div class="btnEditBox btmAlign"> </div>
                </li>
            </ul>
        </div>
        <div class="data">
            <ul class="data-inside"><div class="btnEditBox btmAlign"> 
                    <button onclick="display_status('content_contactus_enquiry_status', '0');" id="content_contactus_enquiry_status_disable" {if $json.content.contactus.enquiry.status==0} style="display: none;"{/if} class="btnDesableSec"><i class="fa fa-close"></i> Disable Section</button> 
                    <button onclick="display_status('content_contactus_enquiry_status', '1');" id="content_contactus_enquiry_status_enable" {if $json.content.contactus.enquiry.status==1} style="display: none;"{/if} class="btnEnableSec"><i class="fa fa-check"></i> Enable Section</button>
                </div>

                <li><div class="btnEditBox"><button onclick="display_textarea('content_contactus_contact_address_text');" address_id="19" id="edit_address" class="btnEditText"><i class="fa fa-text-width"></i> Edit Text</button> </div>
                    <h6>ADDRESS</h6>
                    <span id="content_contactus_contact_address_text">{$json.content.contactus.contact.address.text}</span></li><li><div class="btnEditBox">
                        <button  onclick="display_text('content_contactus_contact_phone_text');" phone_id="20" id="edit_phone" class="btnEditText"><i class="fa fa-text-width"></i> Edit Text</button> </div>
                    <h6>Phone</h6>
                    <p id="content_contactus_contact_phone_text">{$json.content.contactus.contact.phone.text}</p></li><li><div class="btnEditBox">
                        <button onclick="display_text('content_contactus_contact_email_text');" email_id="21" id="edit_email" class="btnEditText"><i class="fa fa-text-width"></i> Edit Text</button> </div>
                    <h6>Email</h6>
                    <p id="content_contactus_contact_email_text">{$json.content.contactus.contact.email.text}</p>
                </li>
            </ul>
        </div>
    </center>
</section>

