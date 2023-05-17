<style>
    .panel-wrap {
        position: fixed;
        top: 0;
        bottom: 0;
        right: 0;
        left: 40% !important;
        /* width: 80em; */
        transform: translateX(100%);
        transition: .3s ease-out;
        z-index: 1000;
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

    .remove {
        padding: 4px 3px;
        cursor: pointer;
        float: left;
        position: relative;
        top: 0px;
        color: #fff;
        right: 25px;
        z-index: 99999;
    }

    .remove:hover {
        color: #FFF;
    }

    .remove i {
        font-size: 19px !important;
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

    .vscomp-ele {
        max-width: 100%;
    }
</style>
<div class="panel-wrap" id="updatepanelWrapIdBillCode">



    <div class="panel">
        <div class='cnt223'>
            <h3 class="modal-title">Sub contracts

                <a class="close " data-toggle="modal" onclick="return closeSideUpdatePanelBillCode();">
                    <button type="button" class="close" aria-hidden="true"></button></a>
            </h3>
            <hr>


            <div class="portlet light bordered " id="subcontract_detail" style="display: none;">
                <div class="portlet-body form">
                    <h3 class="form-section"><span id="showcode"></span></h3>
                    <div class="table-scrollable">
                        <table class="table table-bordered table-hover" id="particular_table1">
                            <thead>
                                <tr>
                                    <th class="td-c">
                                        <input id="allCheckbox" type="checkbox" onclick="selectAll(this)">
                                    </th>
                                    <th class="td-c">
                                        Bill code
                                    </th>
                                    <th class="td-c">
                                        Description
                                    </th>
                                    <th class="td-c">
                                        Cost type
                                    </th>
                                    <th class="td-c">
                                        Amount
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="sub_det"></tbody>
                        </table>
                    </div>
                    <div class="text-danger" id="calc_checkbox_error"></div>
                    <br>
                    <div class="row" style="text-align: end;">
                        <div class="col-md-3 col-md-offset-6">
                            <label class="control-label">Amount </label>
                        </div>
                        <div class="col-lg-3">
                            <input class="form-control right-value" id="c_amount" disabled="" type="text" value="0">
                            <input id="selected_field_ids" type="hidden" value="">
                            <input id="selected_subcontract_id" type="hidden" value="">
                        </div>
                    </div>
                    <br>
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="pull-right">
                                    <input type="hidden" value="0" id="contract_amount" name="contract_amount">
                                    <button onclick="closeDetail();" class="btn default">Cancel</button>
                                    <a href="#" onclick="setContractAmount();" class="btn blue">Add calculation</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>




            <div class="portlet light bordered" id="subcontract_list">
                <div class="portlet-body form">
                    <table class="table table-striped table-hover" id="table-no-export">
                        <thead>
                            <tr>
                                <th class="td-c">
                                    Subcontract code
                                </th>
                                <th class="td-c">
                                    Date
                                </th>
                                <th class="td-c">
                                    Amount
                                </th>
                                <th class="td-c">
                                    ?
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($subcontract))
                            @foreach($subcontract as $v)
                            <tr>
                                <td class="td-c" id="contractcode{{$v->sub_contract_id}}">{{$v->sub_contract_code}}</td>
                                <td class="td-c">{{$v->start_date}}</td>
                                <td class="td-c">{{$v->sub_contract_amount}}</td>
                                <td class="td-c">
                                    <span style="display: none;" id="subparticulars{{$v->sub_contract_id}}">{!!$v->particulars!!}</span>
                                    <button onclick="showDetail('{{$v->sub_contract_id}}')" class="btn btn-blue btn-sm">Select</button>
                                </td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var rowid = '';
    var subcontract_id = '';
    var subcontract_particulars = [];

    function showbilltransaction(id) {
        document.getElementById("updatepanelWrapIdBillCode").style.boxShadow = "0 0 0 9999px rgba(0,0,0,0.5)";
        document.getElementById("updatepanelWrapIdBillCode").style.transform = "translateX(0%)";
        $('.page-sidebar-wrapper').css('pointer-events', 'none');
        $('.page-content-wrapper').css('pointer-events', 'none');
        $('#updatepanelWrapIdBillCode').css('pointer-events', 'auto');
        rowid = id;
        subcontract_id = _('subcontract_id' + rowid).value;
        if (subcontract_id > 0) {
            showDetail(subcontract_id);
            subcontract_particular = _('subcontract_particular' + rowid).value;
            subcontract_particulars = subcontract_particular.split(",");
            $.each(subcontract_particulars, function(index, value) {
                document.getElementById('calc' + value).checked = true;
            });
        }


    }

    function setContractAmount() {
        _('subcontract_id' + rowid).value = subcontract_id;
        _('subcontract_particular' + rowid).value = _('selected_field_ids').value;
        _('budget' + rowid).value = _('c_amount').value;
        calculateChangeOrder();
        closeSideUpdatePanelBillCode();
    }


    function showDetail(id) {
        document.getElementById('subcontract_list').style.display = 'none';
        document.getElementById('subcontract_detail').style.display = 'block';
        document.getElementById('showcode').innerHTML = document.getElementById('contractcode' + id).innerHTML;
        subcontract_id = id;
        array = JSON.parse(document.getElementById('subparticulars' + id).innerHTML);
        html = '';
        console.log(array);
        $.each(array, function(index, value) {
            used = 0;
            try {
                used = value.co_created;
            } catch (o) {
                used = 0;
            }
            if (used != 1) {
                html = html + '<tr><td class="td-c"><input type="hidden" name="calc-pint[]" value="' + value.pint + '" id="calc-pint' + value.pint + '"><input type="checkbox" name="calc-checkbox[]" value="' + value.pint + '" id="calc' + value.pint + '" onclick="calculate()"></td><td class="td-c">' + value.bill_code + '</td><td class="td-c">' + value.description + '</td><td class="td-c">' + value.bill_type + '</td><td class="td-c"><input type="hidden" id="ocm' + value.pint + '" value="' + value.original_contract_amount + '">  ' + value.original_contract_amount + '</td></tr>';
            }
        });
        document.getElementById('sub_det').innerHTML = html;
    }

    function closeDetail() {
        subcontract_particulars = [];
        document.getElementById('subcontract_list').style.display = 'block';
        document.getElementById('subcontract_detail').style.display = 'none';
    }

    function calculate() {

        amount = 0;
        const ids = [];

        $('input[name="calc-checkbox[]"]').each(function(indx, arr) {

            checked = document.getElementById('calc' + arr.value).checked;
            if (checked) {
                amount = amount + Number(document.getElementById('ocm' + arr.value).value);
                ids.push(arr.value);
            }
        });
        document.getElementById('selected_field_ids').value = ids.join(',');
        document.getElementById('c_amount').value = amount;
        document.getElementById('selected_subcontract_id').value = subcontract_id;
    }



    function selectAll(value) {
        $('input[name="calc-checkbox[]"]').each(function(indx, arr) {
            if (value.checked) {
                document.getElementById('calc' + arr.value).checked = true;
            } else {
                document.getElementById('calc' + arr.value).checked = false;
            }
        });
        calculate();
    }
</script>