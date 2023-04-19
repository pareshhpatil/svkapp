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
<div class="panel-wrap"  id="panelWrapIdcalc">
    <div class="panel">
        <div class='cnt223'>
            <h3 class="modal-title">Add calculation
                <a class="close " data-toggle="modal"  onclick="return closeSidePanelcalc();">
                    <button type="button" class="close" aria-hidden="true"></button></a>
                
            </h3>
            <hr>
            <div class="portlet light bordered ">
                <div class="portlet-body form">
                    <h3 class="form-section">Particulars List</h3>
                    <div class="table-scrollable">
                        <table class="table table-bordered table-hover" id="particular_table1">
                            <thead>
                                <tr>
                                    <th class="td-c">
                                        <input id="allCheckbox" type="checkbox" onclick='selectDeselectAll(this)'>
                                    </th>
                                    <th class="td-c" style="min-width: 150px;">
                                        Bill code
                                    </th>
                                    <th class="td-c">
                                        Bill description
                                    </th>
                                    <th class="td-c">
                                        Original contract amount
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="new_particular1">
                            </tbody>
                        </table>
                    </div>
                    <div class="text-danger" id="calc_checkbox_error"></div>
                    <br>
                    <div class="row" style="text-align: end;">
                        <div class="col-md-3 col-md-offset-6">
                            <label class="control-label">Total</label>
                        </div>
                        <div class="col-md-3">
                            <input class="form-control right-value" id="calc_total" type="text" disabled value="0">
                        </div>
                    </div>
                    <br>
                    <div class="row" style="text-align: end;">
                        <div class="col-md-3 col-md-offset-6">
                            <label class="control-label">Percentage </label>
                        </div>
                        <div class="col-lg-3">
                            <input class="form-control right-value" onchange="calculatePercContract(this.value)" id="calc_perc" type="number" value="">
                            <div class="text-danger" id="calc_perc_error"></div>
                        </div>
                        
                    </div>
                    <br>
                    <div class="row" style="text-align: end;">
                        <div class="col-md-3 col-md-offset-6">
                            <label class="control-label">Amount </label>
                        </div>
                        <div class="col-lg-3">
                            <input class="form-control right-value" id="calc_amount" disabled type="text" value="0">
                            <input id="selected_field_int"  type="hidden" value="0">
                        </div>
                    </div>
                    <br>
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="pull-right">
                                    <input type="hidden" value="0" id="contract_amount" name="contract_amount">
                                    <a href="#" onclick="return closeSidePanelcalc();" class="btn default">Cancel</a>
                                    <a href="#" onclick="setAOriginalContractAmount();"  @click="setAOriginalContractAmount();" class="btn blue">Add calculation</a>
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