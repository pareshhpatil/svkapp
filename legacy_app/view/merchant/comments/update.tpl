
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">Update comment</h3>
    <br>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row no-margin">
        <div class="col-md-12">
            <form class="form-horizontal" action="/merchant/comments/updatesave" method="post"  id="submit_form" >
                <div class="form-body">
                    <div class="alert alert-danger display-hide">
                        <button class="close" data-close="alert"></button>
                        You have some form errors. Please check below.
                    </div>
                    <div class="form-group">
                        <label for="inputPassword12" class="col-md-2 control-label"></label>
                        <div class="col-md-8">
                            <textarea class="form-control form-control-inline" name="comment">{$detail.comment}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-9"></div>
                        <div class="col-md-2">
                            <input type="hidden" name="id" value="{$id}">
                            <input type="hidden" name="parent_id" value="{$detail.parent_id}">
                            <button type="submit" class="btn blue">Save</button>
                        </div>
                    </div>
                </div>


            </form>		

        </div>

    </div>	
    <!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->
</div>