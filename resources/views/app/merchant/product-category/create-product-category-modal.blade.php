<div class="modal fade" id="createProductCategory" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" id="category_frm_master" class="form-horizontal form-row-sepe">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Create Category</h4>
                </div>
                <div class="modal-body">
                    <div class="portlet-body form">
                        <div class="form-body">
                           <div class="row">
                                <div class="col-md-12">
                                    <div id="category-error-msg" class="alert alert-danger" style="display:none">
                                        <button class="close" data-dismiss="alert"></button>
                                        <ul></ul>
                                    </div>
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Name <span class="required">*
                                            </span></label>
                                        <div class="col-md-8">
                                            <input type="text" required="true" minlength="2" maxlength="50" name="name" class="form-control" value="" data-cy="add_new_product_category" placeholder="Category name">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="response_type" value="json">
                    <button type="button" id="categoryCloseModal" class="btn default" data-dismiss="modal" data-cy="category_close_modal">Close</button>
                    <input type="hidden" id="category_master_type" value="productCategory">
                    <input type="button" onclick="return saveProductCategory();" value="Save" class="btn blue" data-cy="save_product_category"/>
                </div>
            </form>
        </div>
    </div>
</div>