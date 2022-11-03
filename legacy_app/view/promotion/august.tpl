<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.7";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<div class="page-content" >
    <div class="row no-margin" style="text-align: center;">

        <div class="col-md-3"></div>
        <div class="col-md-6" style="text-align: -webkit-center;text-align: -moz-center; max-width: 860px;">
            <br>
            {if $is_valid=='NO' && !isset($is_invoice)}
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert"></button>
                    <strong>Invalid event!</strong>
                    <div class="media">
                        <p class="media-heading">{$invalid_message}</p>
                    </div>
                </div>
            {/if}

            {if isset($errortitle)}
                <div class="alert alert-danger" style="text-align: left;">
                    <button type="button" class="close" data-dismiss="alert"></button>
                    <div class="media">
                        <strong>{$errortitle}</strong> - <p class="media-heading">{$errormessage}</p>
                    </div>
                </div>
            {/if}

            <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->

            <!-- /.modal -->
            <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
            <!-- BEGIN PAGE CONTENT-->
            <div  style="text-align: left;box-shadow: 1px 10px 10px #888888;text-align: center;">
                <div class="row">
                    <div class="col-md-12 fileinput fileinput-new" style="text-align: center;" data-provides="fileinput">
                        <div class="fileinput fileinput-new" data-provides="fileinput">
                            <div class="fileinput-preview thumbnail banner-container"  data-trigger="fileinput"> 
                                <img class="img-responsive" style="width: 100%;" src="{$details.banner}">
                            </div>
                        </div>
                    </div>
                </div>



                <div class="row no-margin no-padding" style="text-align: left;">

                    <div class="col-md-12 invoice-payment">
                        <div class="row">
                            <br>
                            <div class="col-md-12">{$details.description} </div>
                        </div>
                        <span class="help-block"></span>

                        <div class="row">
                            <div class="col-md-12">
                                <br>
                                <strong>Terms and conditions</strong>
                                <br>
                                {$details.terms}
                                <br>
                                <br>
                                <div class="row">
                                    <div class="col-md-9"> <b>To know more about our upcoming promotions, like us on <a target="_BLANK" href="https://www.facebook.com/swipezindia/">Facebook.</a></b></div>
                                    <div class="col-md-3">
                                       <div class="fb-like" data-href="https://www.facebook.com/swipezindia/" data-layout="button_count" data-action="like" data-size="small" data-show-faces="false" data-share="false"></div>
                                    </div>
                                </div>
                                <br>
                                <br>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
</div>
</div>

<!-- Imac start-->






