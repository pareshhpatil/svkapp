
    <div class="page-content">
        <!-- BEGIN PAGE HEADER-->
        <div class="page-bar">
            {include file="../common/breadcumbs.tpl" title={$title} links=$links}
        </div>
        <!-- END PAGE HEADER-->
        <!-- BEGIN PAGE CONTENT-->
        <div class="row">
            <form action="/profile/savepreferences" method="post">
                <div class="col-md-12">
                    <div class="portlet light bordered">
                        <div class="portlet-body form">
                            <p>
                                By default, the system notifies you of any payments made by your customers both by SMS and e-mail. However, if you are receiving too many payments and would like to deactivate notifications, you can turn it off by deselecting notifications below.
                            </p>
                            <div class="row">
                                <div class="col-md-9">
                                </div>
                                <div class="col-md-2">
                                    <label><input type="checkbox" name="sms" value="yes" {$sms}></span> SMS</label>
                                    <label><input type="checkbox" name="email" value="yes" {$email}></span> E-mail</label>
                                </div>
                            </div>
                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="pull-right">
                                            <a href="/merchant/profile/settings" class="btn btn-default">Cancel</a>
                                            <button type="submit" class="btn blue pull-right">Save </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>