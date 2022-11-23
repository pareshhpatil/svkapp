<div class="modal fade" id="coupon" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/merchant/coupon/save" id="couponForm" method="post"  class="form-horizontal form-row-sepe">
                {CSRF::create('coupon_save')}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Add new coupon</h4>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-danger" style="display: none;" id="errorshow">
                                <button class="close" onclick="document.getElementById('errorshow').style.display = 'none';"></button>
                                <p id="error_display">Please correct the below errors to complete registration.</p>
                            </div>
                            <div class="portlet">
                                <div class="portlet-body form">
                                    <!--<h3 class="form-section">Profile details</h3>-->

                                    <div class="form-body">
                                        <!-- Start profile details -->
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="alert alert-danger display-none">
                                                    <button class="close" data-dismiss="alert"></button>

                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Coupon code <span class="required">*
                                                        </span></label>
                                                    <div class="col-md-4">
                                                        <input type="text" required name="coupon_code" id="coupon_code" class="form-control" value="{$post.coupon_code}">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Coupon description <span class="required">*
                                                        </span></label>
                                                    <div class="col-md-6">
                                                        <textarea  required name="descreption" class="form-control" >{$post.descreption}</textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Start date <span class="required">*
                                                        </span></label>
                                                    <div class="col-md-4">
                                                        <input class="form-control form-control-inline date-picker" type="text" required  value="{$post.start_date}" name="start_date"  autocomplete="off" data-date-format="dd M yyyy"  placeholder="Start date"/>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">End date <span class="required">*
                                                        </span></label>
                                                    <div class="col-md-4">
                                                        <input class="form-control form-control-inline date-picker" type="text" required  value="{$post.end_date}" name="end_date"  autocomplete="off" data-date-format="dd M yyyy"  placeholder="End date"/>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Number of coupons<span class="required">*
                                                        </span></label>
                                                    <div class="col-md-4">
                                                        <input type="number" min="0" value="0" required name="limit" class="form-control" value="{$post.limit}">
                                                    </div>Keep 0 for unlimited
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Coupon offer type</label>
                                                    <div class="col-md-4">
                                                        <select name="is_fixed" class="form-control" onchange="isFixed(this.value);" data-placeholder="Select...">
                                                            <option value="1">Fixed amount</option>
                                                            <option value="2">Percentage</option>

                                                        </select>
                                                        <span class="help-block">
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="form-group" id="per_div" style="display: none;">
                                                    <label class="control-label col-md-4">Percentage <span class="required">*
                                                        </span></label>
                                                    <div class="col-md-4">
                                                        <input type="number" min="0" required id="per" name="percent" class="form-control" value="{$post.percent}">
                                                    </div>%
                                                </div>
                                                <div class="form-group" id="fixed_div" >
                                                    <label class="control-label col-md-4">Fixed amount <span class="required">*
                                                        </span></label>
                                                    <div class="col-md-4">
                                                        <input type="number" min="0" required id="fix" name="fixed_amount" class="form-control" value="{$post.fixed_amount}">
                                                    </div>
                                                </div>

                                            </div>


                                        </div>
                                    </div>					
                                    <!-- End profile details -->




                                </div>
                            </div>
                        </div>


                    </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" onclick="saveCoupon();"  id="btnSubmit"  class="btn blue" value="Save"/>
                    <button type="button" class="btn default" id="closebutton" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="mandatory" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Select display column</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="col-md-4 control-label">Is Mandatory? <span class="required"> </span></label>
                            <div class="col-md-6">
                                <select onchange="chooseMandatory(this.value)" id="selectmand" class="form-control">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                                </select>
                                <input type="hidden" id="hid_type">
                                <input type="hidden" id="hid_name">
                                <input type="hidden" id="hid_position">
                                <span class="help-block">
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn blue" id="closebutton" data-dismiss="modal">Done</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
                            <!-- /.modal-dialog -->
</div>



<div class="modal fade" id="customer" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Select display column</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-10">
                        <div class="table-scrollable">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th class="td-c">
                                            Column name
                                        </th>
                                        <th class="td-c">
                                            Select
                                        </th>
                                        <th class="td-c">
                                            Mandatory
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id='structure_col'>
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn blue" id="closebutton" data-dismiss="modal">Done</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
                            <!-- /.modal-dialog -->
</div>

<script>
 structure_col_json='{$structure_column_json}';
</script>