<style>
    /***
New Timeline
***/
    .timeline {
        margin: 0;
        padding: 0;
        position: relative;
        margin-bottom: 30px;
    }

    .pb0 {
        padding-bottom: 0 !important;
    }

    .timeline:before {
        content: '';
        position: absolute;
        display: block;
        width: 4px;
        background: #f5f6fa;
        top: 0px;
        bottom: 0px;
        margin-left: 38px;
    }

    .ptitle {
        font-size: 1.1rem !important;
    }

    .timeline-item {
        margin: 0;
        padding: 0;
    }

    .timeline-badge {
        float: left;
        position: relative;
        padding-right: 30px;
        height: 80px;
        width: 80px;
    }

    .timeline-badge-userpic {
        width: 80px;
        border: 4px #f5f6fa solid;
        -webkit-border-radius: 50% !important;
        -moz-border-radius: 50% !important;
        border-radius: 50% !important;
    }

    .timeline-badge-userpic img {
        -webkit-border-radius: 50% !important;
        -moz-border-radius: 50% !important;
        border-radius: 50% !important;
        vertical-align: middle !important;
    }

    .timeline-icon {
        width: 80px;
        height: 80px;
        background-color: #f5f6fa;
        -webkit-border-radius: 50% !important;
        -moz-border-radius: 50% !important;
        border-radius: 50% !important;
        padding-top: 30px;
        padding-left: 22px;
    }

    .timeline-icon i {
        font-size: 34px;
    }

    .timeline-body {
        position: relative;
        padding: 20px;
        margin-top: 20px;
        margin-left: 110px;
        background-color: #f5f6fa;
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        -ms-border-radius: 4px;
        -o-border-radius: 4px;
        border-radius: 4px;
    }

    .timeline-body:before,
    .timeline-body:after {
        content: " ";
        display: table;
    }

    .timeline-body:after {
        clear: both;
    }

    .timeline-body-arrow {
        position: absolute;
        top: 30px;
        left: -14px;
        width: 0;
        height: 0;
        border-style: solid;
        border-width: 14px 14px 14px 0;
        border-color: transparent #f5f6fa transparent transparent;
    }

    .timeline-body-head {
        margin-bottom: 10px;
    }

    .timeline-body-head-caption {
        float: left;
    }

    .timeline-body-title {
        font-size: 16px;
        font-weight: 600;
    }

    .timeline-body-alerttitle {
        font-size: 16px;
        font-weight: 600;
    }

    .timeline-body-time {
        font-size: 14px;
        margin-left: 10px;
    }

    .timeline-body-head-actions {
        float: right;
    }

    .timeline-body-head-actions .btn-group {
        margin-top: -2px;
    }

    .timeline-body-content {
        font-size: 14px;
        margin-top: 35px;
    }

    .timeline-body-img {
        width: 100px;
        height: 100px;
        margin: 5px 20px 0 0px;
    }

    .page-container-bg-solid .timeline:before {
        background: #fff;
    }

    .page-container-bg-solid .timeline-badge-userpic {
        border-color: #fff;
    }

    .page-container-bg-solid .timeline-icon {
        background-color: #fff;
    }

    .page-container-bg-solid .timeline-body {
        background-color: #fff;
    }

    .page-container-bg-solid .timeline-body-arrow {
        border-color: transparent #fff transparent transparent;
    }

    

    @media (max-width: 768px) {
        .timeline-body-head-caption {
            width: 100%;
        }

        .timeline-body-head-actions {
            float: left;
            width: 100%;
            margin-top: 20px;
            margin-bottom: 20px;
        }
    }

    @media (max-width: 480px) {
        .timeline:before {
            margin-left: 28px;
        }

        .timeline-badge {
            padding-right: 40px;
            width: 60px;
            height: 60px;
        }

        .timeline-badge-userpic {
            width: 60px;
        }

        .timeline-icon {
            width: 60px;
            height: 60px;
            padding-top: 23px;
            padding-left: 18px;
        }

        .timeline-icon i {
            font-size: 25px;
        }

        .timeline-body {
            margin-left: 80px;
        }

        .timeline-body-arrow {
            top: 17px;
        }


    }
</style>

        <!-- TIMELINE ITEM -->


        

        {foreach from=$revision key=k item=v}
            {$int=$k-1}
            <div class="portlet light">
                <div class="portlet-title">
                    <div class="caption">
                        <span class="caption-subject bold uppercase">
                            {if $revision.{$int}.link==''}
                                <a target="_BLANK" href="/merchant/invoice/revision/{$request_link}">Revision #
                                    {$v.revision_no}</a>
                            {else}
                                <a target="_BLANK" href="/merchant/invoice/revision/{$revision.{$int}.link}">Revision #
                                    {$v.revision_no}</a>
                            {/if}

                        </span>
                    </div>
                    <span class="caption caption-helper pull-right">{$v.last_update_date}</span>

                </div>
                <div class="portlet-body pb0">
                    {foreach from=$v.array key=k item=t}

                        {foreach from=$t key=tk item=p}

                            <p class="caption caption-helper ptitle">
                                {$v.user_name} {$p.title}
                            </p>

                            {if $k=='invoice_construction_particular'}
                                {if $p.type=='update'}
                                    <h5 class="caption caption-helper ptitle"><b>Earlier row</b></h5>
                                    <div class="table-scrollable">
                                        <table class="table table-bordered  table-hover table-sm">
                                            <thead>
                                                <tr>
                                                    <th class="td-c  col-id-no">
                                                        Bill Code
                                                    </th>
                                                    <th class="td-c ">
                                                        Bill Type
                                                    </th>
                                                    <th class="td-c ">
                                                        Original Contract Amount
                                                    </th>
                                                    <th class="td-c ">
                                                        Approved Change Order Amount
                                                    </th>
                                                    <th class="td-c ">
                                                        Current Contract Amount
                                                    </th>
                                                    <th class="td-c ">
                                                        Previously Billed Percent
                                                    </th>
                                                    <th class="td-c ">
                                                        Previously Billed Amount
                                                    </th>
                                                    <th class="td-c ">
                                                        Current Billed Percent
                                                    </th>
                                                    <th class="td-c ">
                                                        Current Billed Amount
                                                    </th>
                                                    <th class="td-c ">
                                                        Stored Materials
                                                    </th>
                                                    <th class="td-c ">
                                                        Total Billed
                                                    </th>
                                                    <th class="td-c ">
                                                        Retainage %
                                                    </th>
                                                    <th class="td-c ">
                                                        Retainage Amount Previously Withheld
                                                    </th>
                                                    <th class="td-c ">
                                                        Retainage amount for this draw
                                                    </th>
                                                    <th class="td-c ">
                                                        Retainage Release Amount
                                                    </th>
                                                    <th class="td-c ">
                                                        Total outstanding retainage
                                                    </th>

                                                    <th class="td-c ">
                                                        Project
                                                    </th>
                                                    <th class="td-c ">
                                                        Cost Code
                                                    </th>
                                                    <th class="td-c ">
                                                        Cost Type
                                                    </th>
                                                    <th class="td-c ">
                                                        Group 
                                                    </th>
                                                    <th class="td-c ">
                                                        Bill code detail
                                                    </th>
                                                   
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <tr>
                                                    <td class="td-c">

                                                        {$p.old_value.bill_code}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.old_value.bill_type}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.old_value.original_contract_amount}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.old_value.approved_change_order_amount}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.old_value.current_contract_amount}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.old_value.previously_billed_percent}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.old_value.previously_billed_amount}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.old_value.current_billed_percent}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.old_value.current_billed_amount}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.old_value.stored_materials}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.old_value.total_billed}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.old_value.retainage_percent}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.old_value.retainage_amount_previously_withheld}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.old_value.retainage_amount_for_this_draw}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.old_value.retainage_release_amount}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.old_value.total_outstanding_retainage}
                                                    </td>

                                                    <td class="td-c ">
                                                        {$p.old_value.project}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.old_value.cost_code}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.old_value.cost_type}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.old_value.group}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.old_value.bill_code_detail}
                                                    </td>
                                                    
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>


                                    <h5 class="caption caption-helper ptitle"><b>Current row</b></h5>
                                    <div class="table-scrollable">
                                        <table class="table table-bordered  table-hover table-sm">
                                            <thead>
                                                <tr>
                                                    <th class="td-c  col-id-no">
                                                        Bill Code
                                                    </th>
                                                    <th class="td-c ">
                                                        Bill Type
                                                    </th>
                                                    <th class="td-c ">
                                                        Original Contract Amount
                                                    </th>
                                                    <th class="td-c ">
                                                        Approved Change Order Amount
                                                    </th>
                                                    <th class="td-c ">
                                                        Current Contract Amount
                                                    </th>
                                                    <th class="td-c ">
                                                        Previously Billed Percent
                                                    </th>
                                                    <th class="td-c ">
                                                        Previously Billed Amount
                                                    </th>
                                                    <th class="td-c ">
                                                        Current Billed Percent
                                                    </th>
                                                    <th class="td-c ">
                                                        Current Billed Amount
                                                    </th>
                                                    <th class="td-c ">
                                                        Stored Materials
                                                    </th>
                                                    <th class="td-c ">
                                                        Total Billed
                                                    </th>
                                                    <th class="td-c ">
                                                        Retainage %
                                                    </th>
                                                    <th class="td-c ">
                                                        Retainage Amount Previously Withheld
                                                    </th>
                                                    <th class="td-c ">
                                                        Retainage amount for this draw
                                                    </th>
                                                    <th class="td-c ">
                                                        Retainage Release Amount
                                                    </th>
                                                    <th class="td-c ">
                                                        Total outstanding retainage
                                                    </th>

                                                    <th class="td-c ">
                                                        Project
                                                    </th>
                                                    <th class="td-c ">
                                                        Cost Code
                                                    </th>
                                                    <th class="td-c ">
                                                        Cost Type
                                                    </th>
                                                    <th class="td-c ">
                                                        Group 
                                                    </th>
                                                    <th class="td-c ">
                                                        Bill code detail
                                                    </th>
                                                    
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <tr>
                                                    <td class="td-c">

                                                        {$p.new_value.bill_code}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.new_value.bill_type}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.new_value.original_contract_amount}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.new_value.approved_change_order_amount}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.new_value.current_contract_amount}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.new_value.previously_billed_percent}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.new_value.previously_billed_amount}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.new_value.current_billed_percent}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.new_value.current_billed_amount}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.new_value.stored_materials}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.new_value.total_billed}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.new_value.retainage_percent}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.new_value.retainage_amount_previously_withheld}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.new_value.retainage_amount_for_this_draw}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.new_value.retainage_release_amount}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.new_value.total_outstanding_retainage}
                                                    </td>

                                                    <td class="td-c ">
                                                        {$p.new_value.project}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.new_value.cost_code}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.new_value.cost_type}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.new_value.group}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.new_value.bill_code_detail}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                {elseif $p.type=='add'}
                                    <h5 class="caption caption-helper ptitle"><b>Current row</b></h5>
                                    <div class="table-scrollable">
                                        <table class="table table-bordered  table-hover table-sm">
                                            <thead>
                                                <tr>
                                                    <th class="td-c  col-id-no">
                                                        Bill Code
                                                    </th>
                                                    <th class="td-c ">
                                                        Bill Type
                                                    </th>
                                                    <th class="td-c ">
                                                        Original Contract Amount
                                                    </th>
                                                    <th class="td-c ">
                                                        Approved Change Order Amount
                                                    </th>
                                                    <th class="td-c ">
                                                        Current Contract Amount
                                                    </th>
                                                    <th class="td-c ">
                                                        Previously Billed Percent
                                                    </th>
                                                    <th class="td-c ">
                                                        Previously Billed Amount
                                                    </th>
                                                    <th class="td-c ">
                                                        Current Billed Percent
                                                    </th>
                                                    <th class="td-c ">
                                                        Current Billed Amount
                                                    </th>
                                                    <th class="td-c ">
                                                        Stored Materials
                                                    </th>
                                                    <th class="td-c ">
                                                        Total Billed
                                                    </th>
                                                    <th class="td-c ">
                                                        Retainage %
                                                    </th>
                                                    <th class="td-c ">
                                                        Retainage Amount Previously Withheld
                                                    </th>
                                                    <th class="td-c ">
                                                        Retainage amount for this draw
                                                    </th>
                                                    <th class="td-c ">
                                                        Retainage Release Amount
                                                    </th>
                                                    <th class="td-c ">
                                                        Total outstanding retainage
                                                    </th>

                                                    <th class="td-c ">
                                                        Project
                                                    </th>
                                                    <th class="td-c ">
                                                        Cost Code
                                                    </th>
                                                    <th class="td-c ">
                                                        Cost Type
                                                    </th>
                                                    <th class="td-c ">
                                                        Group 
                                                    </th>
                                                    <th class="td-c ">
                                                        Bill code detail
                                                    </th>
                                                    
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <tr>
                                                    <td class="td-c">

                                                        {$p.new_value.bill_code}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.new_value.bill_type}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.new_value.original_contract_amount}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.new_value.approved_change_order_amount}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.new_value.current_contract_amount}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.new_value.previously_billed_percent}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.new_value.previously_billed_amount}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.new_value.current_billed_percent}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.new_value.current_billed_amount}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.new_value.stored_materials}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.new_value.total_billed}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.new_value.retainage_percent}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.new_value.retainage_amount_previously_withheld}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.new_value.retainage_amount_for_this_draw}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.new_value.retainage_release_amount}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.new_value.total_outstanding_retainage}
                                                    </td>

                                                    <td class="td-c ">
                                                        {$p.new_value.project}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.new_value.cost_code}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.new_value.cost_type}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.new_value.group}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.new_value.bill_code_detail}
                                                    </td>
                                                   
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                {elseif $p.type=='remove'}
                                    <h5 class="caption caption-helper ptitle"><b>Deleted row</b></h5>
                                    <div class="table-scrollable">
                                        <table class="table table-bordered  table-hover table-sm">
                                            <thead>
                                                <tr>
                                                    <th class="td-c  col-id-no">
                                                        Bill Code
                                                    </th>
                                                    <th class="td-c ">
                                                        Bill Type
                                                    </th>
                                                    <th class="td-c ">
                                                        Original Contract Amount
                                                    </th>
                                                    <th class="td-c ">
                                                        Approved Change Order Amount
                                                    </th>
                                                    <th class="td-c ">
                                                        Current Contract Amount
                                                    </th>
                                                    <th class="td-c ">
                                                        Previously Billed Percent
                                                    </th>
                                                    <th class="td-c ">
                                                        Previously Billed Amount
                                                    </th>
                                                    <th class="td-c ">
                                                        Current Billed Percent
                                                    </th>
                                                    <th class="td-c ">
                                                        Current Billed Amount
                                                    </th>
                                                    <th class="td-c ">
                                                        Stored Materials
                                                    </th>
                                                    <th class="td-c ">
                                                        Total Billed
                                                    </th>
                                                    <th class="td-c ">
                                                        Retainage %
                                                    </th>
                                                    <th class="td-c ">
                                                        Retainage Amount Previously Withheld
                                                    </th>
                                                    <th class="td-c ">
                                                        Retainage amount for this draw
                                                    </th>
                                                    <th class="td-c ">
                                                        Retainage Release Amount
                                                    </th>
                                                    <th class="td-c ">
                                                        Total outstanding retainage
                                                    </th>

                                                    <th class="td-c ">
                                                        Project
                                                    </th>
                                                    <th class="td-c ">
                                                        Cost Code
                                                    </th>
                                                    <th class="td-c ">
                                                        Cost Type
                                                    </th>
                                                    <th class="td-c ">
                                                        Group 
                                                    </th>
                                                    <th class="td-c ">
                                                        Bill code detail
                                                    </th>
                                                    
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <tr>
                                                    <td class="td-c">

                                                        {$p.old_value.bill_code}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.old_value.bill_type}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.old_value.original_contract_amount}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.old_value.approved_change_order_amount}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.old_value.current_contract_amount}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.old_value.previously_billed_percent}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.old_value.previously_billed_amount}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.old_value.current_billed_percent}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.old_value.current_billed_amount}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.old_value.stored_materials}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.old_value.total_billed}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.old_value.retainage_percent}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.old_value.retainage_amount_previously_withheld}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.old_value.retainage_amount_for_this_draw}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.old_value.retainage_release_amount}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.old_value.total_outstanding_retainage}
                                                    </td>

                                                    <td class="td-c ">
                                                        {$p.old_value.project}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.old_value.cost_code}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.old_value.cost_type}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.old_value.group}
                                                    </td>
                                                    <td class="td-c ">
                                                        {$p.old_value.bill_code_detail}
                                                    </td>
                                                    
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                {/if}
                            {/if}
                        {/foreach}
                    {/foreach}
                </div>
            </div>
        {/foreach}

        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject bold uppercase"> <a target="_BLANK"
                            href="/merchant/invoice/revision/{$revision.{$count}.link}">Revision #
                            V0</a></span>
                </div>
                <span class="caption caption-helper pull-right">{$invoice_create_date}</span>

            </div>
            <div class="portlet-body pb0">
                <p class="caption caption-helper ptitle">
                    {$invoice_create_by} created invoice
                </p>
            </div>
        </div>