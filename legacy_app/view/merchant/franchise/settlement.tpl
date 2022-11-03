<style>
    .custom-button{
        position:absolute !important;
        right:45px !important;
    }
    @media screen and (max-width:1366px) {
        .custom-button{
            right:28px !important;
        }
    }
</style>
<div class="page-content">
    <div class="page-bar">
        {include file="../../common/breadcumbs.tpl" title={$title} links=$links}
        {if isset($ledger_balance)}
            <span class="page-title pull-right">Nodal Ledger Balance: {$ledger_balance}</span>
        {/if}
    </div>
   
    <form action="/merchant/franchise/savesettlement" onsubmit="return confirm('Are you sure you want to transfer this amount?');" method="post">
        {CSRF::create('franchise_settlement')}
        <div class="row">
            <div class="col-md-12">
                {if isset($haserrors)}
                    <div class="alert alert-danger">
                        <button type="button" class="close" data-dismiss="alert"></button>
                        <strong>Error!</strong>
                        <div class="media">
                            {foreach from=$haserrors key=k item=v}
                                <p class="media-heading">Settlement ID {$k} - {$v}.</p>
                            {/foreach}
                        </div>

                    </div>
                {/if}
                {if $success!=''}
                    <div class="alert alert-block alert-success fade in">
                        <button type="button" class="close" data-dismiss="alert"></button>
                        <p>{$success}</p>
                    </div>
                {/if}
                
                <div class="portlet " >
                    

                    <div class="portlet-body" style="overflow:scroll">
                        <a onclick="AddNewSettlement();" class="btn btn-sm blue pull-right mb-2 custom-button">Add new row</a>
                        <table class="table table-striped  table-hover" style="margin-top:30px">
                            <thead>
                                <tr>
                                    <th class="td-c">
                                        Settlement ID
                                    </th>
                                    <th class="td-c">
                                        Settlement amount
                                    </th>
                                    <th class="td-c">
                                        Capture
                                    </th>
                                    <th class="td-c">
                                        TDR
                                    </th>
                                    <th class="td-c">
                                        GST
                                    </th>
                                    <th class="td-c">
                                        {if !empty($franchise_list)} Franchise {/if}
                                        {if !empty($vendor_list)} Vendor {/if}
                                        name
                                    </th>
                                    <th class="td-c">
                                        Benificiary name
                                    </th>
                                    <th class="td-c">
                                        Amount to settle
                                    </th>
                                    <th class="td-c">
                                        Transfer Charges
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="new_settlement">
                            <form action="" method="">
                                {$int=1}
                                {foreach from=$list item=v}
                                    <tr>
                                        <td class="td-c">
                                            {$v.id}
                                            <input type="hidden" value="{$v.id}" name="settlement_id[]" >
                                            <input type="hidden" value="{$int}" name="id[]" >
                                        </td>
                                        <td class="td-c">
                                            {$v.settlement_amount}
                                        </td>
                                        <td class="td-c">
                                            {$v.total_capture}
                                        </td>
                                        <td class="td-c">
                                            {$v.total_tdr}
                                        </td>
                                        <td class="td-c">
                                            {$v.total_service_tax}
                                        </td>
                                        <td class="td-c">
                                            {if !empty($franchise_list)}
                                                {$v.franchise_name}
                                            {/if}
                                            {if !empty($vendor_list)}
                                                {$v.vendor_name}
                                            {/if}
                                        </td>
                                        <td class="td-c">
                                            <select onchange="getTransferCharge();" readonly class="form-control input-sm" id="benificiary{$int}" required data-placeholder="Benificiary name" name="beneficiary_ids[]">
                                                {if $v.franchise_id==0 && $v.vendor_id==0}
                                                    <option value="0">Merchant account</option>
                                                {else}
                                                    {foreach from=$franchise_list item=f}
                                                        {if $v.franchise_id==$f.franchise_id}
                                                            <option selected value="f{$f.franchise_id}">{$f.franchise_name}</option>
                                                        {/if}
                                                    {/foreach}
                                                    {foreach from=$vendor_list item=f}
                                                        {if $v.vendor_id==$f.vendor_id}
                                                            <option selected value="v{$f.vendor_id}">{$f.vendor_name}</option>
                                                        {/if}
                                                    {/foreach}
                                                {/if}
                                            </select>
                                        </td>
                                        <td class="td-c">
                                            <input type="number" onblur="getTransferCharge();" value="{$v.settlement_amount}" id="amt{$int}" class="form-control input-sm" step="0.01" name="request_amount[]" >
                                        </td>
                                        <td class="td-c">
                                            <input type="number" value="0.00" id="transfercharges{$int}" readonly  class="form-control input-sm" step="0.01" name="transfer_charges[]" >
                                        </td>
                                    </tr>
                                    {$int=$int+1}
                                {/foreach}
                            </form>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="pull-right">
                    {if $ledger_balance>10}
                        <p>&nbsp;</p><input onclick="return validateLedgerAmount();" type="submit" value="Submit" class="btn blue">
                        <input type="hidden" id="pgtransfercharge" value="{$cashfree_transfer_charge|string_format:"%.2f"}">
                        <input type="hidden" id="ledgerbalance" value="{$max_amount|string_format:"%.2f"}">
                        <input type="hidden" name="post_key" value="{$post_key}">
                    {/if}
                </div>
            </div>
        </div>
    </form>
    <div style="display: none;" id="beneficiary">
        <select onchange="getTransferCharge();" class="form-control input-sm" id="benificiary__ID__" data-placeholder="Benificiary name" name="beneficiary_ids[]">
            <option value="">Benificiary name</option>
            <option value="0">Merchant account</option>
            {foreach from=$franchise_list item=f}
                <option value="f{$f.franchise_id}">{$f.franchise_name}</option>
            {/foreach}
            {foreach from=$vendor_list item=f}
                <option value="v{$f.vendor_id}">{$f.vendor_name}</option>
            {/foreach}
        </select>
    </div>
</div>
</div>


