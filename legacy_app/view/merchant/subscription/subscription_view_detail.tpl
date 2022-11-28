<div class="portlet light bordered">
    <div class="portlet-body form">
        <div class="subscription-info">
            <div class="row">
                <div class="col-md-8">
                    <h2 class="cust-head">
                        {if $subscription_summary.details.company_name!=''}
                            {$subscription_summary.details.company_name}
                        {else}
                            {$subscription_summary.details.customer_name}
                        {/if}
                        ({$subscription_summary.details.customer_code})
                    </h2>
                </div>
                <div class="col-md-2">
                    <h2>{$subscription_summary.currency_icon} {$subscription_summary.summary.collectedAmt}</h2>
                    <p class="text-center">COLLECTED</p>
                </div>
                <div class="col-md-2">
                    <h2>{$subscription_summary.currency_icon} {$subscription_summary.summary.dueAmt}</h2>
                    <p class="text-center">DUE</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2">
                    <h3>{if $subscription_summary.details.mode==1}
                            Daily
                        {elseif $subscription_summary.details.mode==2}
                            Weekly
                        {elseif $subscription_summary.details.mode==3}
                            Monthly
                        {else}
                            Yearly
                        {/if}
                    </h3>
                    <p class="text-center">MODE</p>
                </div>
                <div class="col-md-2">
                    <h3>{{$subscription_summary.details.start_date}|date_format:"%d %b %Y"}</h3>
                    <p class="text-center">START DATE</p>
                </div>
                <div class="col-md-2">
                    <h3>{if $subscription_summary.details.end_mode!=1}
                            {{$subscription_summary.details.end_date}|date_format:"%d %b %Y"}
                        {else}
                            <img alt="Infinity" class="img-fluid" height="20px" width="20px"
                                src="{$server_name}/assets/admin/layout/img/infinity-svgrepo-com.svg" />
                        {/if}
                    </h3>
                    <p class="text-center">END DATE</p>
                </div>
                <div class="col-md-2">
                    <h3>{if $subscription_summary.details.end_mode!=1}
                            {$subscription_summary.summary.occurrences}
                        {else}
                            <img alt="Infinity" class="img-fluid" height="20px" width="20px"
                                src="{$server_name}/assets/admin/layout/img/infinity-svgrepo-com.svg" />
                        {/if}
                    </h3>
                    <p class="text-center">OCCURRENCES</p>
                </div>
                <div class="col-md-2">
                    <h3>{$subscription_summary.summary.sent}</h3>
                    <p class="text-center">SENT</p>
                </div>
                <div class="col-md-2">
                    <h3>{if $subscription_summary.details.end_mode!=1}
                            {$subscription_summary.summary.pending}
                        {else}
                            <img alt="Infinity" class="img-fluid" height="20px" width="20px"
                                src="{$server_name}/assets/admin/layout/img/infinity-svgrepo-com.svg" />
                        {/if}
                    </h3>
                    <p class="text-center">PENDING</p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="portlet light bordered">
    <h3 class="page-title">Invoices created</h3>
    <div class="portlet-body">
        <table class="table table-striped  table-hover" id="table-subscription">
            <thead>
                <tr>
                    {if $list.0.display_invoice_no==1}
                        <th class="td-c">
                            Invoice number
                        </th>
                    {/if}
                    <th class="td-c">
                        Sent on
                    </th>
                    <th class="td-c">
                        Due date
                    </th>

                    <th class="td-c">
                        Amount
                    </th>
                    <th class="td-c">
                        Status
                    </th>
                    <th class="td-c">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody>
                <form action="" method="">
                    {$int=0}
                    {foreach from=$list item=v}
                        {$int=$int+1}
                        <tr>
                            {if $list.0.display_invoice_no==1}
                                <td class="td-c">
                                    {$v.invoice_number}
                                </td>
                            {/if}
                            <td class="td-c">
                                {{$v.send_date}|date_format:"%d %b %y"}
                            </td>
                            <td class="td-c">
                                {{$v.due_date}|date_format:"%d %b %y"}
                            </td>

                            <td class="td-c">
                                {$subscription_summary.currency_icon} {$v.absolute_cost}
                            </td>
                            <td class="td-c">
                                {if ($v.payment_request_status=='1')}
                                    <span class="badge badge-pill status paid_online">{strtoupper($v.status)}</span>
                                {else if ($v.payment_request_status=='2')}
                                    <span class="badge badge-pill status paid_offline">{strtoupper($v.status)}</span>
                                {else if ($v.payment_request_status=='6')}
                                    <span class="badge badge-pill status converted">{strtoupper($v.status)}</span>
                                {else if ($v.payment_request_status=='7')}
                                    <span class="badge badge-pill status partial_paid">PART PAID</span>
                                {else if ($v.payment_request_status=='11')}
                                    <span class="badge badge-pill status draft">DRAFT</span>
                                {else}
                                    <!-- 0 = unpaid, 4=failed ,5= initiated -->
                                    {if ($v.due_date < date("Y-m-d"))}
                                        <span class="badge badge-pill status overdue">OVERDUE</span>
                                    {else}
                                        <span class="badge badge-pill status unpaid">UNPAID</span>
                                    {/if}
                                {/if}
                            </td>
                            <td class="td-c">
                                <div class="btn-group dropup" style="position: absolute;">
                                    <button class="btn btn-xs btn-link dropdown-toggle" type="button"
                                        data-toggle="dropdown">
                                        &nbsp;&nbsp;<i class="fa fa-ellipsis-v"></i>&nbsp;&nbsp;
                                    </button>
                                    <ul class="dropdown-menu" role="menu" style="pointer-events:all;">
                                        <li>
                                            <a target="_BLANK" href="/merchant/paymentrequest/view/{$v.paylink}">
                                                <i class="fa fa-table"></i> View {if $v.invoice_type==1} invoice 
                                                {else}
                                                estimate {/if}
                                            </a>
                                        </li>
                                        {if ($v.payment_request_status=='6')}
                                            <li>
                                                <a target="_BLANK" href="/merchant/paymentrequest/view/{$v.converted_id_link}">
                                                    <i class="fa fa-table"></i> View invoice
                                                </a>
                                            </li>
                                        {/if}
                                        {if $v.payment_request_status==0 || $v.payment_request_status==4 || $v.payment_request_status==5 || $v.payment_request_status==8 || $v.payment_request_status==11}
                                            {if $v.payment_request_status!=11}
                                                {if $v.invoice_type==1}
                                                    <li><a target="_BLANK" href="/merchant/paymentrequest/view/{$v.paylink}#respond"
                                                            title="Settle request">{$subscription_summary.currency_icon} Settle</a>
                                                    </li>
                                                {else}
                                                    <li><a target="_BLANK" href="/merchant/paymentrequest/view/{$v.paylink}#convert"
                                                            title="Convert to Invoice">{$subscription_summary.currency_icon} Convert to
                                                            Invoice</a>
                                                    </li>
                                                    <li><a target="_BLANK"
                                                            href="/merchant/paymentrequest/view/{$v.paylink}#settleestimate"
                                                            title="Settle">{$subscription_summary.currency_icon} Settle</a>
                                                    </li>
                                                {/if}
                                            {/if}
                                            <li><a target="_BLANK" href="/merchant/invoice/update/{$v.paylink}" title="Update request"><i
                                                        class="fa fa-edit"></i> Edit</a>
                                            </li>
                                        {/if}
                                        <!--<li>
                                            <a href="/merchant/comments/view/{$v.paylink}" title="Comments" class="iframe"><i class="fa fa-comment"></i>Comments </a>
                                        </li>-->
                                        {if $v.payment_request_status==0 || $v.payment_request_status==4 || $v.payment_request_status==5 || $v.payment_request_status==8 || $v.payment_request_status==11}
                                            <li>
                                                <a title="Delete Invoice" href="#basic"
                                                    onclick="document.getElementById('deleteanchor').href = '/merchant/paymentrequest/delete/{$v.paylink}'"
                                                    data-toggle="modal"><i class="fa fa-remove"></i>Delete</a>
                                            </li>
                                        {/if}
                                        {if $v.payment_request_status!=11}
                                            <li>
                                                <div style="font-size: 0px;">
                                                    <abcd{$int}>{$server_name}/patron/paymentrequest/view/{$v.paylink}
                                                    </abcd{$int}>
                                                </div>
                                                <a class="btn bs_growl_show" data-clipboard-action="copy"
                                                    data-clipboard-target="abcd{$int}"><i class="fa fa-clipboard"></i> Copy
                                                    {if $v.invoice_type==1} invoice {else} estimate {/if} Link</a>
                                            </li>
                                        {/if}
                                        {if ($v.payment_request_status=='6')}
                                            <li>
                                                <div style="font-size: 0px;">
                                                    <abcd{$int+1}>
                                                        {$server_name}/patron/paymentrequest/view/{$v.converted_id_link}
                                                    </abcd{$int+1}>
                                                </div>
                                                <a class="btn bs_growl_show" data-clipboard-action="copy"
                                                    data-clipboard-target="abcd{$int+1}"><i class="fa fa-clipboard"></i> Copy
                                                    invoice Link</a>
                                            </li>
                                        {/if}
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    {/foreach}
                </form>
            </tbody>
        </table>
    </div>
</div>