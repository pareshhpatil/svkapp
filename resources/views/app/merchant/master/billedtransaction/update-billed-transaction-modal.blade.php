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
            <h3 class="modal-title">Bill transaction

            <a class="close " data-toggle="modal"  onclick="return closeSideUpdatePanelBillCode();">
                    <button type="button" class="close" aria-hidden="true"></button></a>
            </h3>
            <hr>
            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <form class="form-horizontal form-row-sepe" id="billcodeform" name="billcodeform" action="/merchant/billedtransaction/update" method="post">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="project_id" value="{{$project_id}}"  id="project_id_update" >
                        <input type="hidden"  id="bill_id" value="0" name="id" >
                        <div class="form-body">
                            <!-- Start profile details -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Bill Code <span class="required">*
                                            </span></label>
                                        <div class="col-md-5">
                                        <select  name="cost_code" id="bill_code" data-search="true"     data-placeholder="Select code">
                                            @if(!empty($code_list))
                                            @foreach($code_list as $v)
                                            <option value="{{$v->code}}">{{$v->code}} | {{$v->title}}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                        <div class="text-danger" id="cost_code_error"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Cost type <span class="required">*
                                            </span></label>
                                        <div class="col-md-5">
                                        <select  name="cost_type" id="cost_type" data-search="true"     data-placeholder="Select cost type">
                                            @if(!empty($cost_type))
                                            @foreach($cost_type as $v)
                                            <option value="{{$v['value']}}">{{$v['label']}}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                        <div class="text-danger" id="cost_type_error"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Date <span class="required">*
                                            </span></label>
                                        <div class="col-md-5">
                                        <input class="form-control form-control-inline date-picker" type="text" id="date" required name="date" autocomplete="off" data-date-format="dd M yyyy" placeholder="Date" value="" />
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Rate <span class="required">*
                                            </span></label>
                                        <div class="col-md-5">
                                            <input type="number" step="0.01" required="true" onblur="calculate();" name="rate" id="rate" class="form-control" placeholder="Enter rate">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Unit <span class="required">*
                                            </span></label>
                                        <div class="col-md-5">
                                            <input type="number" step="0.01" required="true" onblur="calculate();" maxlength="100" name="unit" id="unit" class="form-control" placeholder="Enter unit">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Amount <span class="required">*
                                            </span></label>
                                        <div class="col-md-5">
                                            <input type="text" readonly  required="true"  name="amount" id="amount" class="form-control" placeholder="Enter unit">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Bill Description <span class="required">
                                            </span></label>
                                        <div class="col-md-5">
                                            <input type="text"  maxlength="100" name="description" id="description" class="form-control" placeholder="Enter description">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="pull-right">
                                        <a href="#" onclick="return closeSideUpdatePanelBillCode();" class="btn default">Cancel</a>
                                        <input type="submit" value="Submit" onclick="return updatebillcode();" class="btn blue" />
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function showbilltransaction(val='',cost_type='') {
document.getElementById("updatepanelWrapIdBillCode").style.boxShadow = "0 0 0 9999px rgba(0,0,0,0.5)";
document.getElementById("updatepanelWrapIdBillCode").style.transform = "translateX(0%)";
$('.page-sidebar-wrapper').css('pointer-events', 'none');
$('.page-content-wrapper').css('pointer-events', 'none');
$('#updatepanelWrapIdBillCode').css('pointer-events', 'auto');

VirtualSelect.init({ ele: '#bill_code' });
VirtualSelect.init({ ele: '#cost_type' });
if(val!='')
{
    document.querySelector('#bill_code').setValue(val);
}else
{
    document.querySelector('#bill_code').reset();
}
if(cost_type!='')
{
    document.querySelector('#cost_type').setValue(cost_type);
}else
{
    document.querySelector('#cost_type').reset();
}

}


function showupdatebilltransaction(id,cost_code,date,rate,unit,amount,cost_type,description) {

document.getElementById("bill_id").value = id;
document.getElementById("date").value = date;
document.getElementById("rate").value = rate;
document.getElementById("unit").value = unit;
document.getElementById("amount").value = amount;
document.getElementById("description").value = description;
showbilltransaction(cost_code,cost_type);

}

function calculate()
{
    document.getElementById("amount").value=document.getElementById("rate").value*document.getElementById("unit").value;
}

</script>