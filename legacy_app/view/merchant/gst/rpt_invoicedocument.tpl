
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">
        {include file="../../common/breadcumbs.tpl" title={$title} links=$links}
    </div>
    <!-- END PAGE HEADER-->

    <!-- BEGIN SEARCH CONTENT-->
    <!-- BEGIN SEARCH CONTENT-->

    <!-- BEGIN PAGE CONTENT-->

    <div class="row">


        {if isset($success)}
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert"></button>
                <strong></strong>{$success}
            </div> 
        {/if}
        {if isset($error)}
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert"></button>
                <strong></strong>{$success}
            </div> 
        {/if}
        <div class="col-md-12">
            <!-- BEGIN PORTLET-->

            <div class="portlet">
                <div class="portlet-body">

                    <form class="form-inline" method="post" action="" role="form">
                        <div class="form-group">
                            <select required="" class="form-control" name="gstin">
                                <option value="">Select GSTIN</option>
                                {foreach from=$data key=k item=v}
                                    <option {if $post.gstin==$v.gstin} selected{/if} value="{$v.gstin}">{$v.company_name} - {$v.gstin}</option>
                                {/foreach}
                            </select>
                        </div>
                        <div class="form-group">
                            <select required="" id="fpmonth" name="month" class="form-control" >
                                <option>File Period Month</option>
                                {foreach from=$months key=k item=v}

                                    <option {if $post.month==$k} selected {/if} value="{$k}">{$v}</option>
                                {/foreach}
                            </select>
                        </div>
                        <div class="form-group">
                            <select required="" id="fpyear" name="year" class="form-control" >
                                <option>File Period Year</option>
                                {foreach from=$years item=v}
                                    <option {if $v==$post.year} selected {/if} value="{$v}">{$v}</option>
                                {/foreach}
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="submit" name="submit" class="btn blue" value="Select" />
                        </div>

                    </form>

                </div>
            </div>
            <!-- END PORTLET-->
        </div>
    </div>
    {if isset($post.gstin)}
        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN PAYMENT TRANSACTION TABLE -->
                <div class="portlet ">
                    <div class="portlet-body">

                        <div class="table-scrollable">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th class="td-c">
                                            Type
                                        </th>
                                        <th class="td-c">
                                            Serial No
                                        </th>
                                        <th class="td-c">
                                            From serial number
                                        </th>
                                        <th class="td-c">
                                            To serial number
                                        </th>
                                        <th class="td-c">
                                            Total number
                                        </th>
                                        <th class="td-c">
                                            Cancelled
                                        </th>
                                        <th class="td-c">
                                            Net issued
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="new_tax">
                                </tbody>
                                <tbody>
                                    {foreach from=$invoice_seq key=kk item=v}
                                        <tr>
                                            <td class="td-c">
                                                Invoices for outward supply
                                            </td>
                                            <td class="td-c">
                                                {$kk+1}
                                            </td>
                                            <td class="td-c">
                                                {$v.from_serial}
                                            </td>
                                            <td class="td-c">
                                                {$v.to_serial}
                                            </td>
                                            <td class="td-c">
                                                {$v.total}
                                            </td>
                                            <td class="td-c">
                                                {$v.canceled}
                                            </td>
                                            <td class="td-c">
                                                {$v.total - $v.canceled}
                                            </td>
                                        </tr>
                                    {/foreach}
                                    {foreach from=$credit_seq key=kk item=v}
                                        <tr>
                                            <td class="td-c">
                                                Credit Note
                                            </td>
                                            <td class="td-c">
                                                {$kk+1}
                                            </td>
                                            <td class="td-c">
                                                {$v.from_serial}
                                            </td>
                                            <td class="td-c">
                                                {$v.to_serial}
                                            </td>
                                            <td class="td-c">
                                                {$v.total}
                                            </td>
                                            <td class="td-c">
                                                {$v.canceled}
                                            </td>
                                            <td class="td-c">
                                                {$v.total - $v.canceled}
                                            </td>
                                        </tr>
                                    {/foreach}
                                    {foreach from=$debit_seq key=kk item=v}
                                        <tr>
                                            <td class="td-c">
                                                Debit Note
                                            </td>
                                            <td class="td-c">
                                                {$kk+1}
                                            </td>
                                            <td class="td-c">
                                                {$v.from_serial}
                                            </td>
                                            <td class="td-c">
                                                {$v.to_serial}
                                            </td>
                                            <td class="td-c">
                                                {$v.total}
                                            </td>
                                            <td class="td-c">
                                                {$v.canceled}
                                            </td>
                                            <td class="td-c">
                                                {$v.total - $v.canceled}
                                            </td>
                                        </tr>
                                    {/foreach}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- END PAYMENT TRANSACTION TABLE -->
            </div>
        </div>
    {/if}

    <!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->

