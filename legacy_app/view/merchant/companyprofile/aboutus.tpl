
<div class="portlet-body form">

    <div class="form-body">
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-10">
                <div class="form-group">
                    <label>About us</label>
                    <div {if ($details.publishable==0)} style="display:none" {/if}><textarea name="about_us" class="form-control description" rows="10">{$details.about_us}</textarea></div>
                    <div {if ($details.publishable==1)} style="display:none" {/if}><textarea class="form-control" readonly rows="10">{strip_tags($details.about_us)}</textarea></div>
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