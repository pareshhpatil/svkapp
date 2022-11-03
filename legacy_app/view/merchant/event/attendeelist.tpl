
<div class="page-content">
    <div class="page-bar">
        {include file="../../common/breadcumbs.tpl" title={$title} links=$links}
    </div>

    <!-- BEGIN SEARCH CONTENT-->

    <!-- BEGIN SEARCH CONTENT-->

    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">
            <div class="portlet">

                <div class="portlet-body" >

                    <form class="form-inline" role="form" action="" method="post">
                        <div class="form-group">
                            <input class="form-control form-control-inline  rpt-date" id="daterange"  autocomplete="off" data-date-format="dd M yyyy"  name="date_range" type="text" value="{$from_date} - {$to_date}" placeholder="From date"/>
                        </div>

                        <div class="form-group">
                            <select class="form-control input-sm select2me" name="package_id" data-placeholder="Package name">
                                <option value=""></option>

                                {foreach from=$package_list key=k item=v}
                                    {if {{$package_id}=={$v.package_id}}}
                                        <option selected value="{$v.package_id}">{$v.package_name}</option>
                                    {else}
                                        <option value="{$v.package_id}">{$v.package_name}</option>
                                    {/if}

                                {/foreach}

                            </select>
                        </div>
                        {if $occurence>1}
                            <div class="form-group">
                                <select class="form-control input-sm select2me" name="occurence_id" data-placeholder="Occurence">
                                    <option value=""></option>
                                    {foreach from=$occurence_list key=k item=v}
                                        {if {{$occurence_id}=={$v.occurence_id}}}
                                            <option selected value="{$v.occurence_id}" selected>{$v.start_date}</option>
                                        {else}
                                            <option value="{$v.occurence_id}" >{$v.start_date}</option>
                                        {/if}
                                    {/foreach}
                                </select>
                            </div>
                        {else}
                            <input type="hidden" name="occurence_id" value="">
                        {/if}
                        <input type="submit" class="btn btn-sm blue" value="Search">
                        <a href="/merchant/event/attendees/{$link}/export" class="btn btn-sm green pull-right">Excel export</a>
                    </form>

                </div>
            </div>
            <!-- BEGIN PAYMENT TRANSACTION TABLE -->

            <div class="portlet ">
                <div class="portlet-body">
                    <table class="table table-striped  table-hover" id="table-small">
                        <thead>
                            <tr>
                                <th class="td-c">
                                    ID
                                </th>
                                <th class="td-c">
                                    Transaction id
                                </th>
                                <th class="td-c">
                                    Transaction date
                                </th>
                                <th class="td-c">
                                    Package name
                                </th>
                                {if $occurence>1}
                                    <th class="td-c">
                                        Occurence
                                    </th>
                                {/if}
                                <th class="td-c">
                                    Amount
                                </th>
                                <th class="td-c">
                                    Paid by
                                </th>
                                <th class="td-c">
                                    Customer code
                                </th>
                                <th class="td-c">
                                    Email
                                </th>
                                <th class="td-c">
                                    Availed?
                                </th>
                                {foreach from=$attendee_capture item=vac}
                                    <th class="td-c">{$vac.column_name}</th>
                                    {/foreach}


                            </tr>
                        </thead>
                        <tbody>
                            {foreach from=$requestlist item=v}
                                <tr>

                                    <td class="td-c">
                                        {$v.id}
                                    </td>
                                    <td class="td-c">
                                        <a href="/merchant/transaction/receipt/{$v.link}" class="iframe">
                                            {$v.transaction_id}
                                        </a>
                                    </td>
                                    <td class="td-c">
                                        {$v.transaction_date}
                                    </td>
                                    <td class="td-c">
                                        {$v.package_name}
                                    </td>
                                    {if $occurence>1}
                                        <td class="td-c">
                                            {{$v.event_date}|date_format:"%Y-%m-%d"}
                                        </td>
                                    {/if}
                                    <td class="td-c">
                                        {$v.amount}
                                    </td>
                                    <td class="td-c">
                                        {$v.paid_by}
                                    </td>

                                    <td class="td-c">
                                        {$v.customer_code}
                                    </td>
                                    <td class="td-c">
                                        {$v.email}
                                    </td>
                                    <td class="td-c">
                                {if $v.is_availed==1}Yes{else}No{/if}
                                    </td>
                                    {foreach from=$attendee_capture item=vac}
                                        {assign var="column_name" value="{'attendee_'}{$vac.column_name}"}

                                        <td class="td-c">
                                            {$v.{$column_name|replace:' ':'_'}}
                                        </td>
                                    {/foreach}
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
<!-- /.modal -->
<!-- /.modal-dialog -->
</div>
<!-- /.modal -->