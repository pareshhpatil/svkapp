<tfoot id="footerRow" class="headFootZIndex">
<tr class="warning">
    <th class="col-id-no" colspan="2" id="bill_code_foot">Grand total</th>
    <th class="td-c">
        <span id="particulartotaldiv">{{ number_format($contract->contract_amount) }}</span>
        <input type="hidden" id="particulartotal" data-cy="particular-total1" name="contract_amount" value="{{ $contract->contract_amount }}">
    </th>
    <th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th>

</tr>
</tfoot>