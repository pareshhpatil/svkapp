
<div class="portlet-body form">

    <div class="form-body">
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-10">
                <div class="form-group">
                    <label>Terms and Conditions</label>
                    <div {if ($details.publishable==0)} style="display:none" {/if}><textarea name="terms_condition" class="form-control description" rows="8">{$details.terms_condition}</textarea></div>
                    <div {if ($details.publishable==1)} style="display:none" {/if}><textarea class="form-control" readonly rows="8">{$details.terms_condition}</textarea></div>
                </div>

                <div class="form-group">
                    <label>Cancellation and Refund</label>
                    <div {if ($details.publishable==0)} style="display:none" {/if}><textarea name="cancellation_policy" class="form-control description" rows="8">{$details.cancellation_policy}</textarea></div>
                    <div {if ($details.publishable==1)} style="display:none" {/if}><textarea class="form-control" readonly rows="8">{$details.cancellation_policy}</textarea></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6"></div>
            <div class="col-md-6">
                <button type="submit" class="btn blue"><i class="fa fa-check"></i> Save</button>
            </div>
        </div>
    </div>
</form></div>
</div>	


</div>
</div>
</form>
</div>

</div>	
<!-- END PAGE CONTENT-->
</div>
</div>