<style>
    .chelptext {
        color: #767676;
        font-size: 13px;
    }

    .subscription-info h3 {
        font-size: 15px !important;
    }
    .onhover-border:hover {
        border: 1px solid #ddd !important;
    }

    .table thead tr th {
        font-size: 12px;
        padding: 3px;
        font-weight: 400;
        color: #333;
    }

    .table > tbody > tr > td {
        font-size: 12px !important;
        padding: 3px;
        border: 1px solid #D9DEDE;
        border-right: 0px;
        border-left: 0px;
    }

    .error-corner {
        border: 1px solid grey;
        background-image: linear-gradient(225deg, red, red 6px, transparent 6px, transparent);
    }

    ul {
        list-style-type: none !important;
    }

    li {
        list-style-type: none !important;
    }

    .vs-option, .vscomp-value {
        font-size: 12px !important;
    }

    .dropdown-menu li > a {
        font-size: 12px !important;
        line-height: 18px;
    }

    .bill_code_td{
        text-align: left;
    }

</style>
<div class="portlet light bordered">

    <div class="portlet-body form">
        <div class="subscription-info">
            <div class="row">
                <div class="col-md-3">
                    <h3 id="pr_project_name">{{ $project->project_name }}</h3>
                    <p class="text-center chelptext">PROJECT NAME

                    </p>
                </div>
                <div class="col-md-3">
                    <h3 id="pr_company_name">{{ (!is_null($project) && is_null($project->company_name))? $project->customer_id : $project->customer_company_code??null }}</h3>
                    <p class="text-center chelptext">COMPANY NAME</p>
                </div>
                <div class="col-md-3">
                    <h3 id="pr_contract_date"><x-localize :date="$contract->contract_date" type="'date'"/></h3>
                    <p class="text-center chelptext">CONTRACT DATE</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <h3 id="pr_contract_number">{{ $contract->contract_code }}</h3>
                    <p class="text-center chelptext">CONTRACT NUMBER</p>
                </div>
                <div class="col-md-3">
                    <h3 id="pr_billing_frequency">{{ \App\ContractParticular::$billing_frequency[$contract->billing_frequency] }}</h3>
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

            @php $particular_column = \App\ContractParticular::$particular_column @endphp
            <div class="table-scrollable">
                <table class="table table-striped  table-hover dataTable no-footer" id="table-subscription" role="grid" aria-describedby="table-subscription_info">
                    @if(!empty($particular_column))
                        <thead>
                        <tr>
                            @foreach($particular_column as $k=>$v)
                                @if($k!='description')
                                    <th class="td-c " @if($k=='description' || $k=='bill_code' ) style="min-width: 100px;" @endif>
                                        <span class="popovers" data-placement="top" data-container="body" data-trigger="hover" data-content="{{$v['title']}}" data-original-title=""> {{Helpers::stringShort($v['title'])}}</span>
                                    </th>
                                @endif
                            @endforeach
                        </tr>
                        </thead>
                    @endif
                    <tbody id="preview_data">
                    @php $totalRetainageAmount = 0;
                    $costTypeArr = array_combine(array_column($cost_types,'value'), array_column($cost_types,'label'))
                    @endphp

                    @foreach($particulars as $particular)
                        @php $totalRetainageAmount += $particular['retainage_amount'] @endphp
                        <tr>
                            <td class="td-c">{{ $particular['bill_code'] }}</td>
                            <td class="td-c">{{ $particular['bill_type'] }}</td>
                            <td class="td-c">{{ $costTypeArr[$particular['cost_type']]??null }}</td>
                            <td class="td-c">{{ $particular['original_contract_amount'] }}</td>
                            <td class="td-c">{{ $particular['retainage_percent'] }}</td>
                            <td class="td-c">{{ $particular['retainage_amount'] }}</td>
                            <td class="td-c">{{ $particular['project_code']??$particular['project'] }}</td>
                            <td class="td-c">{{ $particular['cost_code'] }} </td>
                            <td class="td-c">{{ $particular['group'] }}</td>
                            <td class="td-c"> {{ $particular['bill_code_detail'] }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="warning">
                            <th class="td-c"></th>
                            <th class="td-c">Grand total</th>
                            <th></th>
                            <th class="td-c">
                                <span id="particulartotaldiv">{{ number_format($contract->contract_amount) }}</span>
                                <input type="hidden" id="particulartotal" data-cy="particular-total1" name="contract_amount" value="{{ $contract->contract_amount }}">
                            </th>
                           <th></th><th><span id="particulartotaldiv">{{ number_format($totalRetainageAmount) }}</span></th><th></th><th></th><th></th><th></th>

                        </tr>
                    </tfoot>
                </table>
            </div>

        </div>
    </div>
</div>
<div class="portlet light bordered">
    <div class="portlet-body form">
        <div class="row">
            <div class="col-md-12">
                <div class="pull-right">
                    <a href="{{ route('contract.list.new') }}" class="btn green">Cancel</a>
                    <a class="btn green" href="{{ route('contract.create.new', ['step' => 2, 'contract_id' => $contract_id]) }}">Back</a>
                    <button type="submit" class="btn blue">Save Contract</button>
                </div>
            </div>
        </div>
    </div>
</div>