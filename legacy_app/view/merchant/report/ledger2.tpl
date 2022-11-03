
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
                
                <!-- BEGIN PORTLET-->

                <div class="portlet-body ">
                    <form class="form-inline" method="post" role="form">
                        <div class="form-group">
                            <label class="help-block">From date</label>
                            <input class="form-control form-control-inline  date-picker rpt-date" autocomplete="off" data-date-format="{$session_date_format}" name="from_date" type="text" value="{$from_date}" placeholder="From date"/>
                        </div>

                        <div class="form-group">
                            <label class="help-block">To date</label>
                            <input class="form-control form-control-inline  date-picker rpt-date"  autocomplete="off" data-date-format="{$session_date_format}"  name="to_date" type="text" value="{$to_date}" placeholder="To date"/>

                        </div>

                        <div class="form-group">
                            <label class="help-block">Invoice Status</label>
                            <select class="form-control " data-placeholder="" name="type">
                                <option {if $type==1}selected {/if} value="1" selected>Paid online</option>
                                <option {if $type==2}selected {/if} value="2">All invoices</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="help-block">{$customer_default_column.customer_name|default:'Customer name'}</label>
                            <select class="form-control  select2me" data-placeholder="{$customer_default_column.customer_name|default:'Customer name'}" name="customer_name">
                                <option value=""></option>
                                {foreach from=$customer_list item=v}
                                    {if {{$customer_selected}=={$v.customer_code}}}
                                        <option selected value="{$v.customer_code}" selected>{$v.first_name} {$v.last_name} ({$v.customer_code})</option>
                                    {else}
                                        <option value="{$v.customer_code}">{$v.first_name} {$v.last_name} ({$v.customer_code})</option>
                                    {/if}

                                {/foreach}
                            </select>
                        </div>     
                        <div class="form-group">
                            <label class="help-block">&nbsp;</label>
                            <button type="submit" class="btn  blue">Search</button>
                            <button type="submit" name="export" class="btn  green">Excel export</button>

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
                    <div class="table-scrollable">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>
                                        Date
                                    </th>
                                    <th>
                                        Customer Detail
                                    </th>
                                    <th>
                                        Cr/Dr
                                    </th>
                                    <th>
                                        Invoice Detail
                                    </th>
                                    <th>
                                        Type
                                    </th>
                                    <th>
                                        Debit
                                    </th>
                                    <th>
                                        Credit
                                    </th>
                                    <th>
                                        TDR
                                    </th>
                                    <th>
                                        S.T.
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                {foreach from=$reportlist item=v}
                                    <tr {if $v.cr_dr=='cr'} class="success" {else if $v.cr_dr=='dr'} class=""{else} {if $v.date=='Opening Balance'}class="danger"{else}class="active"{/if}{/if}>
                                        <td>
                                            {$v.date}
                                        </td>
                                        <td>
                                            {$v.customer_detail}
                                        </td>
                                        <td>
                                            {$v.cr_dr}
                                        </td>
                                        <td>
                                            {$v.invoice_number}
                                        </td>
                                        <td>
                                            {$v.type}
                                        </td>
                                        <td>


                                            {if $v.debit!=''}
                                                {$v.debit|string_format:"%.2f"}
                                            {/if}
                                        </td>
                                        <td>
                                            {if $v.cr_dr=='dr'}
                                                0.00
                                            {/if}
                                            {if $v.credit!=''}
                                                {$v.credit|string_format:"%.2f"}
                                            {/if}
                                        </td>
                                        <td>
                                            {if $v.tdr!=''}
                                                {$v.tdr|string_format:"%.2f"}
                                            {/if}
                                        </td>
                                        <td>
                                            {if $v.st!=''}
                                                {$v.st|string_format:"%.2f"}
                                            {/if}
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

    <!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->

