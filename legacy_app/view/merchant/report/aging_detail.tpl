
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
                            <label class="help-block">Search by</label>
                            <select class="form-control " onchange="document.getElementById('rptby').innerHTML=$(this).find('option:selected').text();" data-placeholder="Aging interval" name="aging_by">
                                <option value="last_update_date">Billing date</option>
                                <option value="due_date" {if {$aging_by_selected == 'due_date'}} selected {/if}>Due date</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="help-block" id="rptby">{if $aging_by_selected == 'due_date'}Due date{else}Billing date{/if}</label>
                            <input class="form-control form-control-inline  rpt-date" id="daterange"  autocomplete="off" data-date-format="{$session_date_format}"  name="date_range" type="text" value="{$from_date} - {$to_date}" placeholder="From date"/>
                        </div>
                        

                        <div class="form-group">
                            <label class="help-block">{$customer_default_column.customer_name|default:'Contact person name'}</label>
                            <select class="form-control  select2me" data-placeholder="{$customer_default_column.customer_name|default:'Contact person name'}" name="customer_name">
                                <option value=""></option>
                                {foreach from=$customer_list item=v}
                                    {if {{$customer_selected}=={$v.customer_id}}}
                                        <option selected value="{$v.customer_id}" selected>
                                        {$v.name} 
                                        {if $v.company_name}
                                            ({$v.company_name})
                                        {/if}
                                        </option>
                                    {else}
                                        <option value="{$v.customer_id}">{$v.name} 
                                        {if $v.company_name}
                                            ({$v.company_name})
                                        {/if}
                                        </option>
                                    {/if}

                                {/foreach}
                            </select>

                            

                        </div>

                        <div class="form-group">
                            <label class="help-block">&nbsp;</label>
                            <button type="submit" class="btn  blue">Search</button>

                        </div>
                    </form>

                </div>
            </div>
            <br>
            <!-- END PORTLET-->
        </div>                    

        <!-- BEGIN PAGE CONTENT-->
        <div class="col-md-12">
            <!-- BEGIN PAYMENT TRANSACTION TABLE -->

            <div class="portlet ">
                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover" id="table-ellipsis-small">
                        <thead>
                            <tr>
                                <!--<th>
                                    System invoice #
                                </th>-->
                                <th>
                                    {if {$aging_by_selected == 'due_date'}} Due Date {else} Bill Date {/if}
                                </th>

                                {if $reportlist.0.display_invoice_no==1}
                                    <th class="td-c">
                                        Invoice number
                                    </th>
                                {/if}
                                <th>
                                {$customer_default_column.customer_code|default:'Customer code'}
                                </th>
                                <th>
                                {$customer_default_column.customer_name|default:'Contact person name'}
                                </th>
                                <th>
                                    {$company_column_name}
                                </th>
                                <th>
                                    Email
                                </th>
                                <th>
                                    Mobile
                                </th>
                                <th>
                                    Status
                                </th>

                                <th>
                                    Age
                                </th>
                                <th>
                                    Amount
                                </th>


                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th colspan="{$reportlist.0.display_invoice_no+9}" style=""></th>
                            </tr>
                        </tfoot>
                        <tbody>
                            {foreach from=$reportlist item=v}
                                <tr>
                                    <!--
                                    <td>
                                        {$v.payment_request_id} 
                                    </td> -->
                                    <td>
                                        {{$v.date}|date_format:"%d/%b/%y"}
                                    </td>
                                    {if $reportlist.0.display_invoice_no==1}
                                        <td>
                                            {$v.invoice_number}
                                        </td>
                                    {/if}
                                    <td>
                                        {$v.customer_code} 
                                    </td>
                                    <td>
                                        <a target="_BLANK" href="/merchant/paymentrequest/view/{$v.link}">
                                            {$v.customer_name}
                                        </a>
                                    </td>
                                    <td>
                                        {$v.company_name} 
                                    </td>
                                    <td>
                                        {$v.email} 
                                    </td>
                                    <td>
                                        {$v.mobile} 
                                    </td>
                                    <td>
                                        {$v.status} 
                                    </td>

                                    <td>
                                        {$v.age} 
                                    </td>
                                    <td>
                                    {$v.currency_icon}   {$v.amount|number_format:2:".":","} 
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

