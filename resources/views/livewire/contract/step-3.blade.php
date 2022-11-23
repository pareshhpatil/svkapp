<style>
    .chelptext {
        color: #767676;
        font-size: 13px;
    }

    .subscription-info h3 {
        font-size: 15px !important;
    }
    
</style>
<div class="portlet light bordered">

    <div class="portlet-body form">
        <div class="subscription-info">
            <div class="row">
                <div class="col-md-3">
                    <h3 id="pr_project_name">
                    </h3>
                    <p class="text-center chelptext">PROJECT NAME

                    </p>
                </div>
                <div class="col-md-3">
                    <h3 id="pr_company_name"></h3>
                    <p class="text-center chelptext">COMPANY NAME</p>
                </div>
                <div class="col-md-3">
                    <h3 id="pr_contract_date">
                    </h3>
                    <p class="text-center chelptext">CONTRACT DATE</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <h3 id="pr_contract_number">
                    </h3>
                    <p class="text-center chelptext">CONTRACT NUMBER</p>
                </div>
                <div class="col-md-3">
                    <h3 id="pr_billing_frequency"></h3>
                    <p class="text-center chelptext">BILLING FREQUENCY</p>
                </div>

            </div>
        </div>
    </div>
</div>
<div class="portlet light bordered">
    <h3 class="page-title">Particulars</h3>
    <div class="portlet-body">
        <div id="table-subscription_wrapper" class="dataTables_wrapper no-footer">


            <div class="table-scrollable">
                <table class="table table-striped  table-hover dataTable no-footer" id="table-subscription" role="grid" aria-describedby="table-subscription_info">
                    @if(!empty($particular_column))
                        <thead>
                        <tr>
                            @foreach($particular_column as $k=>$v)
                                @if($k!='description')
                                    <th class="td-c " @if($k=='description' || $k=='bill_code' ) style="min-width: 100px;" @endif>
                                        <span class="popovers" data-placement="top" data-container="body" data-trigger="hover" data-content="{{$v}}" data-original-title=""> {{Helpers::stringShort($v)}}</span>
                                    </th>
                                @endif
                            @endforeach
                        </tr>
                        </thead>
                    @endif
                    <tbody id="preview_data">

                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>