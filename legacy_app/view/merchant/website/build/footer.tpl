<footer>
            <ul class="f-nav">
                <li><div class="btnEditBox">
                        
                        <button onclick="display_status('content_footer_link_terms_status', '0');" id="content_footer_link_terms_status_disable" {if $json.content.footer.link.terms.status==0} style="display: none;"{/if} class="btnDesableSec"><i class="fa fa-close"></i> Disable</button> 
                <button onclick="display_status('content_footer_link_terms_status', '1');" id="content_footer_link_terms_status_enable" {if $json.content.footer.link.terms.status==1} style="display: none;"{/if} class="btnEnableSec"><i class="fa fa-check"></i> Enable</button>
                    </div>
                    <span id="content_footer_link_terms_link" style="display: none;">{$json.content.footer.link.terms.link}</span>
                    <a >Terms & Condition</a>
                </li>
                <li><div class="btnEditBox">
                        <button onclick="display_status('content_footer_link_cancellation_status', '0');" id="content_footer_link_cancellation_status_disable" {if $json.content.footer.link.cancellation.status==0} style="display: none;"{/if} class="btnDesableSec"><i class="fa fa-close"></i> Disable</button> 
                <button onclick="display_status('content_footer_link_cancellation_status', '1');" id="content_footer_link_cancellation_status_enable" {if $json.content.footer.link.cancellation.status==1} style="display: none;"{/if} class="btnEnableSec"><i class="fa fa-check"></i> Enable</button>
                    </div>
                    <span id="content_footer_link_cancellation_link" style="display: none;">{$json.content.footer.link.cancellation.link}</span>
                    <a >Cancellation Policy</a>
                </li>
                <li><div class="btnEditBox">
                          <button onclick="display_status('content_footer_link_disclaimer_status', '0');" id="content_footer_link_disclaimer_status_disable" {if $json.content.footer.link.disclaimer.status==0} style="display: none;"{/if} class="btnDesableSec"><i class="fa fa-close"></i> Disable</button> 
                <button onclick="display_status('content_footer_link_disclaimer_status', '1');" id="content_footer_link_disclaimer_status_enable" {if $json.content.footer.link.disclaimer.status==1} style="display: none;"{/if} class="btnEnableSec"><i class="fa fa-check"></i> Enable</button>
                    </div>
                    <span id="content_footer_link_disclaimer_link" style="display: none;">{$json.content.footer.link.disclaimer.link}</span>
                    <a >Disclaimer</a>
                </li>
            </ul>
            <div class="social">
                <a ><img src="/assets/site-builder/build/images/fb.png" />
                    <span id="content_footer_social_facebook_link" style="display: none;">{$json.content.footer.social.facebook.link}</span>
                    <div class="btnEditBox">
                        <button onclick="display_status('content_footer_social_facebook_status', '0');" id="content_footer_social_facebook_status_disable" {if $json.content.footer.social.facebook.status==0} style="display: none;"{/if} class="btnDesableSec"><i class="fa fa-close"></i> Disable</button> 
                <button onclick="display_status('content_footer_social_facebook_status', '1');" id="content_footer_social_facebook_status_enable" {if $json.content.footer.social.facebook.status==1} style="display: none;"{/if} class="btnEnableSec"><i class="fa fa-check"></i> Enable</button>
                
                        <button onclick="display_text('content_footer_social_facebook_link');" class="btnMinus"><i class="fa fa-code"></i> Set URL </button></div></a>
                <a ><img src="/assets/site-builder/build/images/tw.png" />
                    <span id="content_footer_social_twitter_link" style="display: none;">{$json.content.footer.social.twitter.link}</span>
                    <div class="btnEditBox">
                        <button onclick="display_status('content_footer_social_twitter_status', '0');" id="content_footer_social_twitter_status_disable" {if $json.content.footer.social.twitter.status==0} style="display: none;"{/if} class="btnDesableSec"><i class="fa fa-close"></i> Disable</button> 
                <button onclick="display_status('content_footer_social_twitter_status', '1');" id="content_footer_social_twitter_status_enable" {if $json.content.footer.social.twitter.status==1} style="display: none;"{/if} class="btnEnableSec"><i class="fa fa-check"></i> Enable</button>
                        <button onclick="display_text('content_footer_social_twitter_link');" class="btnMinus"><i class="fa fa-code"></i> Set URL </button></div></a>
                <a ><img src="/assets/site-builder/build/images/in.png" />
                    <span id="content_footer_social_linkedin_link" style="display: none;">{$json.content.footer.social.linkedin.link}</span>
                    <div class="btnEditBox">
                        <button onclick="display_status('content_footer_social_linkedin_status', '0');" id="content_footer_social_linkedin_status_disable" {if $json.content.footer.social.linkedin.status==0} style="display: none;"{/if} class="btnDesableSec"><i class="fa fa-close"></i> Disable</button> 
                <button onclick="display_status('content_footer_social_linkedin_status', '1');" id="content_footer_social_linkedin_status_enable" {if $json.content.footer.social.linkedin.status==1} style="display: none;"{/if} class="btnEnableSec"><i class="fa fa-check"></i> Enable</button>
                        <button onclick="display_text('content_footer_social_linkedin_link');" class="btnMinus"><i class="fa fa-code"></i> Set URL </button></div></a>
                <a ><img src="/assets/site-builder/build/images/yt.png" />
                    <span id="content_footer_social_youtube_link" style="display: none;">{$json.content.footer.social.youtube.link}</span>
                    <div class="btnEditBox">
                        <button onclick="display_status('content_footer_social_youtube_status', '0');" id="content_footer_social_youtube_status_disable" {if $json.content.footer.social.youtube.status==0} style="display: none;"{/if} class="btnDesableSec"><i class="fa fa-close"></i> Disable</button> 
                <button onclick="display_status('content_footer_social_youtube_status', '1');" id="content_footer_social_youtube_status_enable" {if $json.content.footer.social.youtube.status==1} style="display: none;"{/if} class="btnEnableSec"><i class="fa fa-check"></i> Enable</button>
                        <button onclick="display_text('content_footer_social_youtube_link');" class="btnMinus"><i class="fa fa-code"></i> Set URL </button></div></a>
                <a ><img src="/assets/site-builder/build/images/st.png" />
                    <span id="content_footer_social_instagram_link" style="display: none;">{$json.content.footer.social.instagram.link}</span>
                    <div class="btnEditBox">
                        <button onclick="display_status('content_footer_social_instagram_status', '0');" id="content_footer_social_instagram_status_disable" {if $json.content.footer.social.instagram.status==0} style="display: none;"{/if} class="btnDesableSec"><i class="fa fa-close"></i> Disable</button> 
                <button onclick="display_status('content_footer_social_instagram_status', '1');" id="content_footer_social_instagram_status_enable" {if $json.content.footer.social.instagram.status==1} style="display: none;"{/if} class="btnEnableSec"><i class="fa fa-check"></i> Enable</button>
                        <button onclick="display_text('content_footer_social_instagram_link');" class="btnMinus"><i class="fa fa-code"></i> Set URL </button></div></a> </div>

            <div class="copy"><span id="content_footer_text_value">{$json.content.footer.text.value}</span><div class="btnEditBox">
                    <button onclick="display_text('content_footer_text_value');" footer_edit_id_res="43" id="edit_footer_reserv" class="btnEditText"><i class="fa fa-text-width"></i> Edit Text</button> </div></div>
        </footer>
    </div>
</div>
</body>
</html>