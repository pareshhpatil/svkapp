
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">Comments</h3>
    <br>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row no-margin">
        <div class="col-md-12">
            {if $success!=''}
                <div class="alert alert-block alert-success fade in">
                    <button type="button" class="close" data-dismiss="alert"></button>
                    <p>{$success}</p>
                </div>
            {/if}
            {foreach from=$list item=v}
                <div class="media">
                    <div class="media-body">
                        <h5 class="media-heading">{$v.name} <span>
                                {$v.last_update_date} - <a href="/merchant/comments/update/{$v.link}">
                                    Edit </a>/
                                <a href="/merchant/comments/delete/{$v.link}">
                                    Delete </a>
                            </span>
                        </h5>
                        <p>
                            {$v.comment}
                        </p>
                        <hr>
                    </div>
                </div>
            {/foreach}


            <form class="form-horizontal" action="/merchant/comments/save" method="post"  id="submit_form" >
                <div class="form-body">
                    <div class="alert alert-danger display-hide">
                        <button class="close" data-close="alert"></button>
                        You have some form errors. Please check below.
                    </div>
                    <div class="form-group">
                        <label for="inputPassword12" class="col-md-2 control-label"></label>
                        <div class="col-md-8">
                            <textarea class="form-control form-control-inline" maxlength="100" required="" {$validate.narrative} maxlength="100" placeholder="Add another comment" name="comment"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-9"></div>
                        <div class="col-md-2">
                            <input type="hidden" name="parent_id" value="{$parent_id}">
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