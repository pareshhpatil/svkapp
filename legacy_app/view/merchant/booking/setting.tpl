
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">
        {include file="../../common/breadcumbs.tpl" title={$title} links=$links}
    </div>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        {if isset($haserrors)}
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                <strong>Error!</strong>
                <div class="media">
                    {foreach from=$haserrors key=k item=v}
                        <p class="media-heading">{$k} - {$v.1}</p>
                    {/foreach}
                </div>

            </div>
        {/if}
        {if isset($success)}
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert"></button>
                <strong>Success!</strong>{$success}
            </div>
        {/if}
        <form action="/merchant/bookings/updatesetting" method="post" id="submit_form"  class="form-horizontal form-row-sepe" enctype="multipart/form-data" >
            {CSRF::create('booking_setting')}
            <div class="col-md-12">
                <div class="alert alert-danger display-none">
                    <button class="close" data-dismiss="alert"></button>
                    You have some form errors. Please check below.
                </div>
                <div class="portlet light bordered">
                    <div class="portlet-body form">
                        <!-- End Bank details -->
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label class="control-label col-md-4">Show header menu</label>
                                    <div class="col-md-8">
                                        <label class="control-label">
                                            <input type="checkbox" id="issupplier" name="hide_menu" value="1" class="make-switch" data-on-text="&nbsp;Enabled&nbsp;&nbsp;" 
                                                   {if $det.booking_hide_menu==0}
                                                       checked
                                                   {/if} data-off-text="&nbsp;Disabled&nbsp;">
                                        </label>
                                        <br>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label class="control-label col-md-4">Booking title

                                    </label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" required value="{if $det.booking_title==''}NOW BOOK SPORTS / ACTIVITES ONLINE!{else}{$det.booking_title}{/if}" name="booking_title" maxlength="100">
                                        <br>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 fileinput fileinput-new banner-main" data-provides="fileinput">
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="fileinput-preview thumbnail banner-container" style="min-width: 600px;max-height: 400px;" data-trigger="fileinput">
                                        <img src="{if $det.booking_background!=""}{$det.booking_background}{else}/assets/admin/layout/img/booking_bg.jpg{/if}"></div>
                                    <div>
                                        <span class="btn default btn-file">
                                            <span class="fileinput-new">
                                                Upload image </span>
                                            <span class="fileinput-exists">
                                                Change </span>
                                            <input type="file" name="banner" accept="image/*">
                                        </span>
                                        <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput">
                                            Remove </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Start Bulk upload details -->
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="pull-right">
                                        <input type="submit" class="btn blue" value="Update"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>						
                </div>						
                <!-- End Bulk upload details -->
            </div>
        </form>	
    </div>
</div>	
<!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->
</div>
<div class="modal fade" id="basic" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Update auto invoice number</h4>
            </div>
            <div class="modal-body">

                <form action="/merchant/profile/updateautonumber" method="post" id="submit_form" class="form-horizontal form-row-sepe">
                    <div class="form-body">
                        <!-- Start profile details -->
                        <div class="row">
                            <div class="form-group">
                                <label class="control-label col-md-5">Prefix<span class="required">
                                    </span></label>
                                <div class="col-md-4">
                                    <input type="text" name="subscript" id="subscript" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-5">Current number<span class="required">
                                    </span></label>
                                <div class="col-md-4">
                                    <input type="number"  name="last_number" id="autonumber" class="form-control">
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn default" data-dismiss="modal">Close</button>
                        <input type="hidden" id="autoinvoice_id" name="auto_invoice_id">
                        <input type="submit" class="btn blue">
                    </div>
                </form>
            </div>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="delete" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Delete invoice number</h4>
            </div>
            <div class="modal-body">
                Are you sure you would not like to use this number in the future?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn default" data-dismiss="modal">Close</button>
                <a href="" id="deleteanchor" class="btn delete">Confirm</a>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<script>

    function setautogenerate()
    {

        if ($('#idauto').is(':checked')) {
            $("#idprefix").prop('readonly', false);
        } else
        {
            $("#idprefix").prop('readonly', true);
        }

    }

    function updateauto_number(subscript, val, id)
    {
        document.getElementById('autoinvoice_id').value = id;
        document.getElementById('subscript').value = subscript;
        document.getElementById('autonumber').value = val;
    }
</script>