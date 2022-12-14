
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">
        {include file="../../common/breadcumbs.tpl" title={$title} links=$links}
    </div>
    <!-- END PAGE HEADER-->
    <div class="row">
        <!-- END SEARCH CONTENT-->
        <div class="col-md-12">

            <!-- BEGIN PORTLET-->
            <div class="portlet">

                <div class="portlet-body ">
                    <form class="form-inline" method="post" role="form">
                        <div class="form-group">
                            <label class="help-block">Bill date</label>
                            <input class="form-control form-control-inline  rpt-date" id="daterange"  autocomplete="off" data-date-format="{$session_date_format}"  name="date_range" type="text" value="{$from_date} - {$to_date}" placeholder="From date"/>
                        </div>

                        {if !empty($billing_profile_list)}
                            {if count($billing_profile_list)>1}
                                <div class="form-group">
                                    <label class="help-block">Billing profile</label>
                                    <select class="form-control rpt-date" data-placeholder="Billing profile" name="billing_profile_id">
                                        <option value="0">Select..</option>
                                        {foreach from=$billing_profile_list item=v}
                                            {if {$billing_profile_id==$v.id}}
                                                <option selected value="{$v.id}" selected>{$v.profile_name} {$v.gst_number}</option>
                                            {else}
                                                <option value="{$v.id}">{$v.profile_name} {$v.gst_number}</option>
                                            {/if}
                                        {/foreach}
                                    </select>
                                </div>
                            {/if}
                        {/if}



                        <div class="form-group">
                            <label class="help-block">&nbsp;</label>
                            <button type="submit" class="btn  blue">Search</button>

                        </div>
                    </form>

                </div>
            </div>
            <!-- END PORTLET-->
        </div> 

        <div class="col-md-12">
            <!-- BEGIN PAYMENT TRANSACTION TABLE -->

            <div class="portlet ">
                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover" id="table-small" >
                        <thead>
                            <tr>
                                <th>
                                    Tax Name
                                </th>
                                <th>
                                    Tax Percentage (%)
                                </th>
                                <th>
                                    Taxable Amount
                                </th>
                                <th>
                                    Tax Amount
                                </th>



                            </tr>
                        </thead>
                        <tbody>
                            {foreach from=$reportlist item=v}
                                <tr>
                                    <td>
                                        {$v.tax_name} 
                                    </td>
                                    <td>
                                        {$v.tax_percent} 
                                    </td>
                                    <td>
                                        {$v.total_applicable|number_format:2:".":","} 
                                    </td>
                                    <td>
                                        {$v.total_amount|number_format:2:".":","} 
                                    </td>
                                </tr>
                            {/foreach}
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- END PAYMENT TRANSACTION TABLE -->
        </div>

    </div>

    <!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->

