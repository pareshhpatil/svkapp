<div class="page-content">
    <div class="page-bar">
        {include file="../../common/breadcumbs.tpl" title={$title} links=$links}
        {if isset($ledger_balance)}
            <span class="pull-right">Nodal Ledger Balance: {$ledger_balance}</span>
        {/if}
    </div>
    <div class="row">
        <div class="col-md-12">
            <form action="" method="post">
                <button type="submit" name="export" class="pull-right btn btn-sm green mb-2" title="Download report in excel format">Excel export</button>
            </form>
            <div class="portlet ">
                <div class="portlet-body" style="overflow:scroll">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th class="td-c">
                                    ID
                                </th>
                                <th class="td-c">
                                    Date time
                                </th>
                                <th class="td-c">
                                    Type
                                </th>
                                <th class="td-c">
                                    Particular
                                </th>
                                <th class="td-c">
                                    Remarks
                                </th>
                                <th class="td-c">
                                    Amount
                                </th>
                                <th class="td-c">
                                    Closing balance
                                </th>


                            </tr>
                        </thead>
                        <tbody>
                        <form action="" method="">
                            {foreach from=$statements item=v}
                                <tr>
                                    <td class="td-c">
                                        {$v.ledgerId}
                                    </td>
                                    <td class="td-c">
                                        {$v.addedOn}
                                    </td>
                                    <td class="td-c">
                                        {$v.eventType}
                                    </td>
                                    <td class="td-c">
                                        {$v.particulars}
                                    </td>
                                    <td class="td-c">
                                        {$v.remarks}
                                    </td>
                                    <td class="td-c">
                                        {$v.amount}
                                    </td>
                                    <td class="td-c">
                                        {$v.closingBalance}
                                    </td>


                                </tr>
                            {/foreach}
                        </form>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
</div>


