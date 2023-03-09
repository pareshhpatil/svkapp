<tfoot id="footerRow" class="headFootZIndex">
<tr class="warning">
    <th class="col-id-no" id="bill_code_foot">Grand total</th>
    <th></th>
    <th></th>
    <th class="td-c">
        <span id="particulartotaldiv">{{ number_format($contract->contract_amount) }}</span>
        <input type="hidden" id="particulartotal" data-cy="particular-total1" name="contract_amount" value="{{ $contract->contract_amount }}">
        <input type="hidden" id="bulk_id" name="bulk_id" value="{{ $bulk_id }}">
    </th>
    <th></th><th>
        <span id="retainagetotaldiv" x-text="totalretainage"></span>
{{--        <input type="hidden" id="retainagetotal" data-cy="particular-retainagetotal" name="retainage_totak" value="{{ $contract->contract_amount }}"></th>--}}
    <th></th><th></th><th></th><th></th><th></th>

</tr>
</tfoot>