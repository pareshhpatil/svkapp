
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">Help Desk</h3>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row no-margin">
                <div class="col-md-1"></div>
                <div class="col-md-10" style="text-align: -webkit-center;text-align: -moz-center;">
                {if isset($errors)}
                    <div class="alert alert-danger alert-dismissable" style="max-width: 900px;text-align: left;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                        <strong>Error!</strong>
                        <div class="media">
                            {foreach from=$errors key=k item=v}
                                <p class="media-heading">{$k} - {$v.1}</p>
                            {/foreach}
                        </div>

                    </div>
                {/if}


                <div class="portlet light bordered" style="max-width: 900px;text-align: left;">
                    
                    <div class="portlet-body form">
                        <!--<h3 class="form-section">Profile details</h3>-->
                        <form action="/helpdesk/send" method="post" id="submit_form" class="form-horizontal form-row-sepe">
                            <div class="form-body">
                                <!-- Start profile details -->
                                <div class="row">
                                    <div class="col-md-12">

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label class="control-label col-md-4">To <span class="required">
                                                                    * </span></label>
                                                            <div class="col-md-8">
                                                                <input type="text" value="support@swipez.in" disabled class="form-control" >
                                                                <span class="help-block"> </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label class="control-label col-md-4">Name <span class="required">
                                                                    * </span></label>
                                                            <div class="col-md-8">
                                                                <input type="text" required name="name" class="form-control" >
                                                                <span class="help-block"> </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label class="control-label col-md-4">Email <span class="required">
                                                                    * </span></label>
                                                            <div class="col-md-8">
                                                                <input type="email" name="email"  class="form-control" >
                                                                <span class="help-block"> </span>
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label class="control-label col-md-4">Subject <span class="required">
                                                                    * </span></label>
                                                            <div class="col-md-8">
                                                                <input type="text" name="subject" required="" class="form-control" >
                                                                <span class="help-block"> </span>
                                                            </div>

                                                        </div>
                                                    </div>
                                                    


                                                </div>
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label class="control-label col-md-4">Message <span class="required">
                                                                    * </span> </label>
                                                            <div class="col-md-8">
                                                                <textarea type="text" required name="message"  class="form-control" ></textarea>
                                                                <span class="help-block"> </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            
                                                            <div class="col-md-4"></div>
                                                            <div class="col-md-8">
                                                                <img src="/captcha_code_file.php?rand=<?php echo rand(); ?>" style=" width: 160px;"  />
                                                                <span class="help-block"> </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label class="control-label col-md-4">Captcha <span class="required">
                                                                    * </span> </label>
                                                            <div class="col-md-8">
                                                                <input type="text" required name="captcha"  class="form-control" >
                                                                <span class="help-block"> </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    
                                                </div>
                                            </div>
                                            <div class="form-actions">
                                               
                                                <div class="col-md-12">
                                                   
                                                    <button type="submit" class="btn blue pull-right">Save</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>					
                        <!-- End profile details -->



                        </form>
                    </div>
                </div>
            </div>	
            <!-- END PAGE CONTENT-->
        </div>
    </div>
    <!-- END CONTENT -->
</div>