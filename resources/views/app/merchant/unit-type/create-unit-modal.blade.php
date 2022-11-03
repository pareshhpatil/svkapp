<!-- Add unit type model -->
<div class="modal fade" id="createUnitType" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" id="frm_unit_master" class="form-horizontal form-row-sepe">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Create Unit Type</h4>
                </div>
                <div class="modal-body">
                    <div class="portlet-body form">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div id="unit-error-msg" class="alert alert-danger" style="display:none">
                                        <button class="close" data-dismiss="alert"></button>
                                        <ul></ul>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Name <span class="required">*
                                            </span></label>
                                        <div class="col-md-8">
                                            <input type="text" required="true" minlength="2" maxlength="50" id="unit_type_name" name="name" class="form-control" placeholder="Unit name" data-cy="new_unit_name">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="response_type" value="json">
                    <button type="button" id="unitModalClose" class="btn default" data-dismiss="modal" data-cy="unit_modal_close">Close</button>
                    <input type="hidden" id="unit_master_type" value="unit_type">
                    <input type="submit" onclick="return saveUnitType();" value="Save" class="btn blue" data-cy="save_unit_type"/>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>