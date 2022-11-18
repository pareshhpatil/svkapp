<tfoot>
<tr class="warning">
    <th class="td-c"></th>
    <th class="td-c">Grand total</th>
    <th class="td-c">
        <span id="particulartotaldiv">{{ number_format($contract->contract_amount) }}</span>
        <input type="hidden" id="particulartotal" data-cy="particular-total1" name="contract_amount" value="{{ $contract->contract_amount }}">
    </th>
    <th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th>

</tr>
</tfoot>