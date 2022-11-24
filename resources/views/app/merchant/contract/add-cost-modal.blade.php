<div wire:ignore>
    <style>
        .panel-wrap {
            position: fixed;
            top: 0;
            bottom: 0;
            right: 0;
            left: 50% !important;
            /* width: 80em; */
            transform: translateX(100%);
            transition: .3s ease-out;
            z-index: 100;
        }

        .panel {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            background: #fff;
            color: #394242;
            overflow-y: scroll;
            overflow-x: hidden;
            padding: 1em;
            box-shadow: 0 5px 15px rgb(0 0 0 / 50%);
            margin-bottom: 0;
        }


        .subscription-info i {
            font-size: 22px !important;
        }

        .cust-head {
            text-align: left !important;
        }

        .subscription-info h3 {
            text-align: center;
            color: #000;
            margin-bottom: 2px !important;
        }

        .subscription-info h2 {
            font-weight: 600;
            margin-bottom: 0 !important;
            margin-top: 5px !important;
            text-align: center;
        }

        .td-head {
            font-size: 19px;
        }

        @media (max-width: 767px) {
            .cust-head {
                text-align: center !important;
            }

            .panel-wrap {
                /* width: 23em; */
                top: 0;
                bottom: 0;
                right: 0;
                left: 0;
                position: fixed;
            }
        }

        @media (min-width: 768px) and (max-width: 991px) {
            .panel-wrap {
                /* width: 47em; */
                position: fixed;
                right: 0;
            }
        }

        @media (min-width: 992px) and (max-width: 1199px) {
            .panel-wrap {
                /* width: 47em; */
                position: fixed;
                right: 0;
            }
        }

        .right-value {
            text-align: right;
        }
    </style>
    <div class="panel-wrap" id="panelWrapIdcost">
        <div class="panel">
            <div class='cnt223'>
                <h3 class="modal-title">Add current billed amount
                    <a class="close " data-toggle="modal" onclick="return closeSidePanelcost();">
                        <button type="button" class="close" aria-hidden="true"></button></a>

                </h3>
                <hr>
                <div class="portlet light bordered ">

                    <div class="portlet-body form">

                        <div class="row">
                            <div class="col-md-3">
                             Select cost code   
                            <div id="cost_codes" x-model="cost_bill_code" ></div>
                            </div>
                            <div class="col-md-3">
                                Select cost type
                            <div id="cost_types" x-model="cost_cost_type" ></div>
                            </div>
                            <div class="col-md-2">
                                <br>
                            <a href="#" @click="filterCost('aa')" id="filterbutton" style="display: none;" ></a>
                            </div>
                        </div>
                        </div>
                        </div>

                <div class="portlet light bordered ">

                    <div class="portlet-body form">

                        


                        <h3 class="form-section">Billed transactions</h3>
                        <div class="table-scrollable">
                            <table class="table table-bordered table-hover" id="particular_table1">
                                <thead>
                                    <tr>
                                        <th class="td-c">
                                            <input id="allCheckboxcost" type="checkbox" @click="allcostCheck()">
                                        </th>
                                        <th class="td-c" style="min-width: 150px;">
                                            Cost code
                                        </th>
                                        <th class="td-c">
                                            Rate
                                        </th>
                                        <th class="td-c">
                                            Unit
                                        </th>
                                        <th class="td-c">
                                            Amount
                                        </th>
                                        <th class="td-c">
                                            Description
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="new_particular_cost">
                                    <template x-for="(field, index) in billed_transactions" :key="index">
                                        <tr>
                                            <td class="td-c">
                                                <input type="checkbox" x-model="field.checked" name="cost-checkbox[]" x-value="field.id" :id="index" @change="costCalc();">
                                            </td>
                                            <td class="td-c" x-text="field.cost_code"></td>
                                            <td class="td-c" x-text="field.rate"></td>
                                            <td class="td-c" x-text="field.unit"></td>
                                            <td class="td-c" x-text="field.amount"></td>
                                            <td class="td-c" x-text="field.description"></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                        <br>

                        <div class="row" style="text-align: end;">
                            <div class="col-md-3 col-md-offset-6">
                                <label class="control-label">Amount </label>
                            </div>
                            <div class="col-lg-3">
                                <input class="form-control right-value" id="cost_amount" disabled type="text" value="0">
                                <input id="selected_field_int" type="hidden" value="0">
                            </div>
                        </div>
                        <br>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="pull-right">
                                        <input type="hidden" value="0" id="cost_selected_id">
                                        <input type="hidden" value="" id="billed_transaction_ids">
                                        <a href="#" @click="closeSidePanelcost();" class="btn default">Cancel</a>
                                        <a href="#" @click="setCostAmount();" class="btn blue">Add cost</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>