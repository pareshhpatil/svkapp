<div>
    <h3 class="form-section">Add particulars
        <a href="javascript:;" wire:click="addRow()" class="btn btn-sm green pull-right"> <i class="fa fa-plus"> </i> Add new row </a>
    </h3>
    <div class="table-scrollable">
        <table class="table table-bordered table-hover" id="particular_table">
            <thead>
                <tr>
                    <th class="td-c">
                        <label class="control-label">Particular <span class="required">*</span></label>
                    </th>
                    <th class="td-c">
                        <label class="control-label">SAC/HSN Code <span class="required"></span></label>
                    </th>
                    <th class="td-c">
                        <label class="control-label">Unit <span class="required">*</span></label>
                    </th>
                    <th class="td-c">
                        <label class="control-label">Rate <span class="required">*</span></label>
                    </th>
                    <th class="td-c">
                        <label class="control-label">Sale Price <span class="required"></span></label>
                    </th>
                    <th class="td-c">
                        <label class="control-label">Tax <span class="required"></span></label>
                    </th>
                    <th class="td-c">
                        <label class="control-label">Total <span class="required"></span></label>
                    </th>
                    <th class="td-c">
                        <label class="control-label">Action <span class="required"></span></label>
                    </th>
                </tr>
            </thead>
            <tbody id="new_particular">
                @if(!@empty($particular))
                @foreach($particular as $key => $value)
                <tr>
                    <td>
                        <div wire:ignore wire:key="select_div{{$key}}">
                            <select style="width: 100%;" class="form-control productselect" name="particular[]" data-cy="particular_product_id_{{$key}}" required wire:model="particular.{{ $key }}.product_id" id="product{{$key}}" selectNum="{{$key}}" wire:click="setParticularDetails($event.target.value,{{$key}})">
                                <option value="">Select Particular</option>
                                @foreach($products[$key] as $pk => $prod)
                                <option value="{{$pk}}" @if($pk==$value['product_id'])selected @endif>{{$prod}}</option>
                                @endforeach
                            </select>
                        </div>
                        {{-- <a class="clicker"> <label class="control-label"> Add New Product </label></a> --}}
                    </td>
                    <td><input type="text" data-cy="particular_sac_{{$key}}" wire:model.lazy="particular.{{ $key }}.sac" name="sac[]" class="form-control" placeholder="SAC/HSN Code"></td>
                    <td><input required type="number" step="1" min="1" data-cy="particular_unit_{{$key}}" wire:model.lazy="particular.{{ $key }}.unit" name="unit[]" class="form-control" placeholder="Unit"></td>
                    <td><input type="number" step="0.01" min="0.01" required data-cy="particular_rate_{{$key}}" wire:model.lazy="particular.{{ $key }}.rate" name="rate[]" class="form-control" placeholder="Rate"></td>
                    <td><input type="number" step="0.01" min="0.01" required data-cy="particular_sale_price_{{$key}}" wire:model.lazy="particular.{{ $key }}.sale_price" name="sale_price[]" class="form-control" placeholder="Sale Price"></td>
                    <td>
                        <select data-placeholder="Select GST" name="tax[]" data-cy="particular_tax_{{$key}}" wire:model="particular.{{ $key }}.tax" class="form-control">
                            <option value="">Select GST</option>
                            @foreach ($gstTax as $tk=>$tax)
                            <option value="{{$tk}}">{{$tax}}%</option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="text" name="total[]" class="form-control" data-cy="particular_total_{{$key}}" wire:model.lazy="particular.{{ $key }}.total" readonly=""></td>
                    <td>
                        <input type="hidden" name="expense_detail_id[]" data-cy="particular_expense_detail_id_{{$key}}" wire:model="particular.{{$key}}.expense_detail_id">
                        <a href="javascript:;" class="btn btn-sm red" wire:click="remove({{ $key }});" data-cy="particular_remove_{{$key}}"> <i class="fa fa-times"> </i></a>
                    </td>
                </tr>
                @endforeach
                @endif
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" style="border-bottom: none;"></td>
                    <td colspan="2"><label>Sub Total</label></td>
                    <td colspan="3"><input type="text" class="form-control" readonly="" data-cy="particular_sub_total" wire:model.lazy="sub_total" id="sub_total" name="sub_total" value="0.00"></td>
                </tr>
                @if($cgst)
                <tr id="cgst">
                    <td colspan="4" style="border: none;"></td>
                    <td colspan="2"><label>CGST</label></td>
                    <td colspan="3"><input type="text" class="form-control" readonly="" data-cy="particular_cgst_amt" wire:model.lazy="cgst_amt" id="cgst_amt" name="cgst_amt" value="0.00"></td>
                </tr>
                @endif
                @if($sgst)
                <tr id="sgst">
                    <td colspan="4" style="border: none;"></td>
                    <td colspan="2"><label>SGST</label></td>
                    <td colspan="3"><input type="text" class="form-control" readonly="" data-cy="particular_sgst_amt" wire:model.lazy="sgst_amt" id="sgst_amt" name="sgst_amt" value="0.00"></td>
                </tr>
                @endif
                @if($igst)
                <tr id="igst">
                    <td colspan="4" style="border: none;"></td>
                    <td colspan="2"><label>IGST</label></td>
                    <td colspan="3"><input type="text" class="form-control" readonly="" data-cy="particular_igst_amt" wire:model.lazy="igst_amt" id="igst_amt" name="igst_amt" value="0.00"></td>
                </tr>
                @endif
                <tr>
                    <td colspan="4" style="border: none;"></td>
                    <td colspan="2"><label>TDS</label></td>
                    <td colspan="3">
                        <select data-placeholder="Select TDS" name="tds" class="form-control" id="tds" data-cy="particular_tds" wire:model="tds">
                            <option value="0">Select TDS</option>
                            <option value="1.00">1%</option>
                            <option value="5.00">5%</option>
                            <option value="10.00">10%</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="4" style="border: none;"></td>
                    <td colspan="2"><label>Discount</label></td>
                    <td colspan="3"><input type="number" min="0" wire:model.lazy="discount" class="form-control" data-cy="particular_discount" name="discount" id="discount"></td>
                </tr>
                <tr>
                    <td colspan="4" style="border: none;"></td>
                    <td colspan="2"><label>Adjustment</label></td>
                    <td colspan="3"><input type="number" class="form-control" wire:model.lazy="adjustment" data-cy="particular_adjustment" name="adjustment" id="adjustment"></td>
                </tr>
                <tr>
                    <td colspan="1" style="border: none;">
                        <label class="control-label col-md-12">Narrative<span class="required"></span></label>
                    </td>
                    <td colspan="3" style="border: none;">
                        <textarea name="narrative" class="form-control" data-cy="particular_narrative" wire:model.lazy="narrative"></textarea>
                    </td>
                    <td colspan="2"><label>Total</label></td>
                    <td colspan="2"><input type="text" class="form-control" readonly id="total" data-cy="particular_grand_total" wire:model="grand_total" name="total" value="0.00">
                        <input type="hidden" name="particular_id[]" value="Na">
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
    @section('footer')
    <script>
        document.getElementById("panelWrapId").style.transform = "translateX(100%)";
        document.addEventListener("livewire:load", function(event) {
            Livewire.hook('message.processed', (message, component) => {
                $('.productselect').select2({
                    //tags: true,
                    insertTag: function(data, tag) {
                        var $found = false;
                        $.each(data, function(index, value) {
                            if ($.trim(tag.text).toUpperCase() == $.trim(value.text).toUpperCase()) {
                                $found = true;
                            }
                        });
                        if (!$found) data.unshift(tag);
                    }
                }).on('select2:open', function(e) {
                    pind = $(this).index();
                    select_id = $(this).attr('selectNum');
                    var index = $(".productselect").index(this);
                    if (document.getElementById('prolist' + pind)) {} else {
                        $('.select2-results').append('<div class="wrapper" id="prolist' + pind + '" > <a class="clicker" onclick="proindex(' + index + ',' + select_id + ');">Add new product</a> </div>');
                    }
                });
                $('.productselect').on('change', function(e) {
                    let elementName = $(this).attr('wire:model');
                    let key = $(this).attr('selectNum');
                    var data = $(this).select2('val');
                    @this.set(elementName, data);
                    livewire.emit('setParticularDetails', e.target.value, key);
                });
                $('.clicker').click(function() {
                    document.getElementById("panelWrapId").style.boxShadow = "0 0 0 9999px rgba(0,0,0,0.5)";
                    document.getElementById("panelWrapId").style.transform = "translateX(0%)";
                    return false;
                });
            })
        });
        $('#vendor_id').on('change', function(e) {
            livewire.emit('setGstType', e.target.value);
        });
    </script>
    @endsection
</div>