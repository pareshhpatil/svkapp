<div>
    <div class="portlet  col-md-12">
        <div class="portlet-body">
            <h4 class="form-section mt-0">Add taxes
                <a wire:click="addTax" class="btn btn-sm green pull-right"> <i class="fa fa-plus"> </i> Add new row </a>
            </h4>
            <div class="table-scrollable">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="td-c">
                                Tax label
                            </th>
                            <th class="td-c">
                                Tax in %
                            </th>
                            <th class="td-c">
                                Applicable on
                            </th>
                            <th class="td-c">
                                Absolute cost
                            </th>
                            <th class="td-c">
                                Narrative
                            </th>
                            <th class="td-c">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($taxes))
                        @foreach($taxes as $key=>$val)
                        <tr>
                            <td>
                                <div class="input-icon right">
                                    <select style="width: 100%;" name="tax_id[]" wire:model.lazy="taxes.{{$key}}" data-placeholder="Select..." class="form-control  input-sm">
                                        <option value="">Select</option>
                                        @if(!empty($merchantTax))
                                        @foreach($merchantTax as $v)
                                        <option value="{{$v['tax_id']}}">{{$v['tax_name']}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </td>
                            <td>
                                <div class="input-icon right">
                                    <input type="number" readonly @if($val>0) value="{{$merchantTax[$val]['percentage']}}" @endif {$validate.percent} class="form-control input-sm" >
                                </div>
                            </td>
                            <td>
                                <input type="text" readonly="" class="form-control input-sm">
                            </td>
                            <td>
                                <input type="text" class="form-control input-sm" readonly="">
                            </td>
                            <td>
                                <input type="text" readonly="" class="form-control input-sm">
                            </td>
                            <td>
                                <a href="javascript:;" wire:click="remove({{$key}})" class="btn btn-sm red"> <i class="fa fa-times"> </i> </a>
                            </td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                    <tr class="warning">
                        <td>
                            <div class="input-icon right">
                                <input type="text" name="tax_total" readonly value="Tax total" class="form-control input-sm" placeholder="Enter total label">
                            </div>
                        </td>
                        <td></td>
                        <td></td>
                        <td>
                            <input type="text" class="form-control input-sm" readonly>
                        </td>
                        <td></td>
                        <td></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>