<div class="display-none" id="tax_dropdown">
    <select style="width: 100%;" onchange="setTaxPercent(this.value);" name="tax_id[]" data-placeholder="Select..." class="form-control  input-sm" ><option value="">Select</option>
        {foreach from=$tax_list key=k item=tt}
            <option value="{$k}">{$tt.tax_name}</option>
        {/foreach}
    </select>
</div>
<!-- Supplier list start -->
<div class="modal fade bs-modal-lg" id="respond" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                Select supplier
                <button type="button" id="closeguest" class="close" data-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">

                {if {$supplier[0].supplier_company_name} !=''}
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th class="td-c">
                                        Supplier company name
                                    </th>
                                    <th class="td-c">
                                        Contact person name
                                    </th>
                                    <th class="td-c">
                                        Mobile
                                    </th>
                                    
                                    <th class="td-c">
                                        Select
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                {assign var="sup" value="{$supplierid}"|@explode:","}
                                {section name=sec1 loop=$supplier}
                                    <tr>
                                        <td class="td-c">
                                            <div id="spname{$supplier[sec1].supplier_id}">{$supplier[sec1].supplier_company_name}</div>
                                        </td>
                                        <td class="td-c">
                                            <div id="spcontact{$supplier[sec1].supplier_id}">{$supplier[sec1].contact_person_name}</div>
                                        </td>
                                        <td class="td-c">
                                            <div id="spmobile{$supplier[sec1].supplier_id}">{$supplier[sec1].mobile1}</div>
                                        </td>
                                        
                                        <td class="td-c">
                                            <div id="spemail{$supplier[sec1].supplier_id}" style="display: none;">{$supplier[sec1].email_id1}</div>
                                            <input type="checkbox" {if {$supplier[sec1].supplier_id}|in_array:$plugin.supplier } checked="" {/if} class="form-control"  value="{$supplier[sec1].supplier_id}" id="spid{$supplier[sec1].supplier_id}" onchange="AddsupplierRow(this.value);"/>
                                        </td>
                                    </tr>
                                {/section}     
                            </tbody>
                        </table>
                    <div class="row no-margin">
                        <button type="button" class="btn blue pull-right" data-dismiss="modal" aria-hidden="true">Done</button>
                    </div>

                {else}
                    <br><div align="center"><h5 >No records found</h5></div>
                {/if}
            </div>

        </div>

    </div>
    <!-- /.modal-content -->
</div>
<div class="modal  fade" id="new_covering" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button"  class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Add new covering note</h4>
            </div>
            <form action="/merchant/coveringnote/save" method="post" id="covering_frm" class="form-horizontal form-row-sepe">
                {CSRF::create('coveringnote_save')}
                <div class="form-body">
                    <!-- Start profile details -->
                    <div class="row">
                        <div class="col-md-12">
                            <br>
                            <div id="covering_error" class="alert alert-danger" style="display: none;">

                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Template name <span class="required">*
                                    </span></label>
                                <div class="col-md-4">
                                    <input type="text" required name="template_name" {$validate.name} class="form-control" value="{$post.template_name}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">Mail body <span class="required">*
                                    </span></label>
                                <div class="col-md-7">
                                    <textarea required name="body" id="summernote" class="form-control description">
                                                <div style="text-align: center;"><span style="font-family:open sans,helvetica neue,helvetica,arial,sans-serif">{if $logo!=''}<img data-file-id="890997" src="{$logo}"  style="max-height: 200px; margin: 0px;"></span></div><div><span style="font-family:open sans,helvetica neue,helvetica,arial,sans-serif">{/if}Dear %CUSTOMER_NAME%,<br><br>Please find attached our invoice for the services provided to your company.<br><br>It has been a pleasure serving you. We look forward to working with you again.<br><br>If you have any questions about your invoice, please contact us by replying to this email.<br><br>Thanking You<br><br>With best regards,<br>%COMPANY_NAME%</span></div>
                                    </textarea>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Mail Subject <span class="required">*
                                    </span></label>
                                <div class="col-md-6">
                                    <input type="text" required maxlength="100" name="subject" class="form-control" value="Payment request from %COMPANY_NAME%">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Invoice label <span class="required">*
                                    </span></label>
                                <div class="col-md-4">
                                    <input type="text" required maxlength="20"  name="invoice_label" class="form-control" value="View Invoice">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Attach PDF <span class="required">*
                                    </span></label>
                                <div class="col-md-4">
                                    <input type="checkbox" checked="" name="pdf_enable" value="1" class="make-switch" data-on-text="&nbsp;Enabled&nbsp;&nbsp;" 
                                           data-off-text="&nbsp;Disabled&nbsp;">
                                </div>
                            </div>
                        </div>


                    </div>
                </div>					
                <!-- End profile details -->



            </form>
            <div class="modal-footer">
                <button type="button" id="cclosebutton" class="btn default" data-dismiss="modal">Close</button>
                <input type="submit" onclick="return saveCovering('');" value="Save" class="btn blue">
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
{section name=sec1 loop=$supplierid}      
    <script>
        try{
        document.getElementById("spid{$supplierid['sec1']}").checked = true;
        } catch (o){}
    </script>     
{/section} 

<script>
    function showsupplier() {
        if ($('#issupplier').is(':checked')) {
            $("#supplierdiv").slideDown(500).fadeIn();
        } else
        {
            $("#supplierdiv").slideUp(500).fadeOut();
        }
    }
</script>