<div class="portlet-body form">
    <div class="form-body">
        <div class="row">
            <div class="col-md-10">
                <div class="form-group">
                    <label class="control-label col-md-3">Create and publish my company page</label>
                    <div class="col-md-5">
                        <input type="checkbox" id="issupplier" onchange="handleToggleButton()" id="toggleButton" name="publishable" value="1" class="make-switch" data-on-text="&nbsp;Enabled&nbsp;&nbsp;" 
                                {if $details.publishable==1}
                                    checked
                                {/if} data-off-text="&nbsp;Disabled&nbsp;">

                      
                    </div>
                </div>
                                

                <!-- <div class="form-group">
                    <label class="col-md-3 control-label">Create and publish my company page</label>
                    <div class="col-md-5">
                        <input type="checkbox" id="toggleButton" name="publishable" onchange="handleToggleButton()" class="form-control form-control-inline input-sm" {if ($details.publishable==1)} checked {/if}>
                        <span class="help-block">
                        </span>
                    </div>
                </div> -->
                <div class="form-group">
                    <label class="col-md-3 control-label">Company name</label>
                    <div class="col-md-5">
                        <input type="text" name="company_name" value="{$details.company_name}" readonly class="form-control form-control-inline input-sm">
                        <span class="help-block">
                        </span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Display name</label>
                    <div class="col-md-5">
                        <input type="text" name="display_name" value="{$details.display_name}" readonly class="form-control form-control-inline input-sm">
                        <span class="help-block">
                        </span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Website</label>
                    <div class="col-md-5">
                        <input type="text" name="website" value="{$details.merchant_website}" {if ($details.publishable==0)} readonly {/if} class="form-control form-control-inline input-sm">
                        <span class="help-block">
                        </span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Display URL</label>
                    <div class="col-md-5">
                        <input type="text" name="display_url" required value="{$details.display_url}" {if ($details.publishable==0)} readonly {/if} class="form-control form-control-inline input-sm">
                        <span class="help-block">
                        </span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Overview</label>
                    <div class="col-md-9" {if ($details.publishable==0)} style="display:none" {/if}>
                        <textarea class="form-control description" id="description" name="overview" rows="8" >{$details.overview}</textarea>
                        <span class="help-block">
                        </span>
                    </div>
                    <div class="col-md-9" {if ($details.publishable==1)} style="display:none" {/if}>
                        <textarea class="form-control" readonly id="textdescription" rows="8" >{strip_tags($details.overview)}</textarea>
                        <span class="help-block">
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6"></div>
            <div class="col-md-6">
                <input type="hidden" name="ex_display_url"  value="{$details.display_url}">
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
<form action="/merchant/update_toggle" method="POST" id="toggleButtonForm">
    {CSRF::create('togglebutton')}
    <input type="hidden" name="publishable" value="on" id="toggleButtonData">
    <input type="hidden" name="testinput" value="testinput">
</form>
<script>
    $(document).ready(function() {
        if($('.custom-slider').hasClass('checked')) {
            $('.custom-slider').css('background-color', '#0DA3E2');
        }
    });
   
    function handleToggleButton() {
        if($('#uniform-toggleButton > span').hasClass('checked')){
            $('#toggleButtonData').val('on');
            form = document.getElementById('toggleButtonForm');
        	let eventForm = new FormData(form)
        	$.ajax({
		        type: "POST",
		        url: form.getAttribute('action'),
		        data: eventForm,
		        processData: false,  // Important!
		        contentType: false,
		        cache: false,
		        success: function(response) {
		        	if (response.status == 'success') {
		            	location.reload();
		            }
		        },
		        error: function() {

		        },
		        dataType: 'json'
		    });

        }
        else{
            $('#toggleButtonData').val('off');
            form = document.getElementById('toggleButtonForm');
        	let eventForm = new FormData(form)
        	$.ajax({
		        type: "POST",
		        url: form.getAttribute('action'),
		        data: eventForm,
		        processData: false,  // Important!
		        contentType: false,
		        cache: false,
		        success: function(response) {
		        	if (response.status == 'success') {
		            	location.reload();
		            }
		        },
		        error: function() {

		        },
		        dataType: 'json'
		    });
        }
    }
</script>
</div>	
<!-- END PAGE CONTENT-->
</div>
</div>