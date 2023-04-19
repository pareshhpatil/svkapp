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
                <h3 class="modal-title">Current billed amount
                    <a class="close " data-toggle="modal" @click="closeSidePanelcost();">
                        <button type="button" class="close" aria-hidden="true"></button></a>

                </h3>
                <hr>
                <div class="portlet light bordered ">

                    <div class="portlet-body form">

                        <div class="row">
                            <div class="col-md-3">
                                Select cost code
                                <select id="v_bill_codecost" placeholder="Native Select" data-search="false" data-silent-initial-value-set="true">
                                    @if(!empty($csi_codes))
                                    @foreach($csi_codes as $v)
                                    <option value="{{$v['value']}}">{{$v['label']}}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-3">
                                Select cost type
                                <select id="v_cost_typecost" placeholder="Native Select" data-search="false" data-silent-initial-value-set="true">
                                    @if(!empty($merchant_cost_types))
                                    @foreach($merchant_cost_types as $v)
                                    <option value="{{$v['value']}}">{{$v['label']}}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-2">
                                <br>
                                <input type="hidden" id="bill_codecost">
                                <input type="hidden" id="cost_typecost">
                                <a href="#" onclick="filterCost('aa')" id="filterbutton" class="btn btn-sm blue">Search</a>
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
                                            <input id="allCheckboxcost" type="checkbox" onclick="allcostCheck()">
                                        </th>
                                        <th class="td-c" style="min-width: 150px;">
                                            Cost code
                                        </th>
                                        <th class="td-c">
                                            Cost type
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
                                    
                                </tbody>
                            </table>
                        </div>
                        <div class="text-danger" id="cost_checkbox_error"></div>
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
                                        <a href="#" onclick="closeSidePanelcost();" class="btn default">Cancel</a>
                                        <a href="#" onclick="setCostAmount();" class="btn blue">Add cost</a>
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