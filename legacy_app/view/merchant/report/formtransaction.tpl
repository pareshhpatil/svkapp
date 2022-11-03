
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">
        {include file="../../common/breadcumbs.tpl" title={$title} links=$links}
    </div>
    <!-- END PAGE HEADER-->
    <div class="row">
        <form class="form-inline" method="post" role="form">
            <!-- END SEARCH CONTENT-->
            <div class="col-md-12">
                {if isset($haserrors)}
                    <div class="alert alert-danger">
                        <button type="button" class="close" data-dismiss="alert"></button>
                        <strong>Error!</strong>
                        <div class="media">
                            <p class="media-heading">{$haserrors}.</p>
                        </div>

                    </div>
                {/if}
                {if isset($successmessage)}
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert"></button>
                        <strong>Success!</strong>
                        <div class="media">
                            <p class="media-heading">{$successmessage}.</p>
                        </div>

                    </div>
                {/if}
                <!-- BEGIN PORTLET-->
                <div class="portlet">

                    <div class="portlet-body ">

                        <div class="form-group">
                            <label class="help-block">Submitted date</label>
                            <input class="form-control form-control-inline  rpt-date" id="daterange"  autocomplete="off" data-date-format="{$session_date_format}"  name="date_range" type="text" value="{$from_date} - {$to_date}" placeholder="From date"/>
                        </div>
                        <div class="form-group">
                            <label class="help-block">Form name</label>
                            <select class="form-control " data-placeholder="Form name" name="form_id">
                                <option value="">Select..</option>
                                {foreach from=$formlist item=v}
                                    {if {{$post.form_id}=={$v.id}}}
                                        <option selected value="{$v.id}" selected>{$v.name}</option>
                                    {else}
                                        <option value="{$v.id}">{$v.name}</option>
                                    {/if}

                                {/foreach}
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="help-block">Payment status</label>
                            <select class="form-control " data-placeholder="Status" name="status">
                                <option value="">Select..</option>
                                <option {if $post.status=='1'} selected {/if} value="1" >Success</option>
                                <option {if $post.status=='0'} selected {/if} value="0" >Failed</option>

                            </select>
                        </div>
                        <div class="form-group">
                            <label class="help-block">&nbsp;</label>
                            <button type="submit" name="submit" class="btn  blue">Search</button>
                            <button type="submit" name="export" class="btn  green" title="Download report in excel format">Excel export</button>
                            <button type="submit" name="downloaddoc" class="btn   green" title="Download files in zip format">File download</button>
                        </div>

                    </div>
                </div>
                <!-- END PORTLET-->
            </div> 
            <div class="col-md-12">
                <!-- BEGIN PAYMENT TRANSACTION TABLE -->

                <div class="portlet ">
                    <div class="portlet-body">
                        {if !empty($reportlist)}
                            <table class="table table-striped table-bordered table-hover" id="table-small" >
                                <thead>
                                    <tr>
                                        <th class="no-sort">
                                            <label><input onchange="checkCheckbox(this.checked);" id="checkAll" type="checkbox"><b>All</b></label>
                                        </th>
                                        {foreach from=$column key=k item=v}
                                            <th>
                                                {$v}
                                            </th>
                                        {/foreach}
                                    </tr>
                                </thead>
                                <tbody>
                                    {foreach from=$reportlist item=v}
                                        <tr>
                                            <td  class="no-sort"><input name="form_check[]" class="icheck" type="checkbox" value="{$v.id.value}"></td>

                                            {foreach from=$column key=k item=c}
                                                <td>
                                                    {if $v.{$k}.subtype=='file' && $v.{$k}.value!=''}
                                                        <a class="btn btn-xs blue iframe" href="{$v.{$k}.value}" >View Doc</a>
                                                    {else}
                                                        {$v.{$k}.value}
                                                    {/if}
                                                </td>
                                            {/foreach}
                                        </tr>
                                    {/foreach}
                                </tbody>
                            </table>
                        {else}
                            <h4 class="center">No records found. Please change your search criteria.</h4>
                        {/if}
                    </div>
                </div>

                <!-- END PAYMENT TRANSACTION TABLE -->
            </div>
        </form>
    </div>

    <!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->

<script>
    order_col = 1;
    first_colsort = 1;

    function checkCheckbox(checkk)
    {
        $('input:checkbox').not(this).prop('checked', checkk);

    }
</script>