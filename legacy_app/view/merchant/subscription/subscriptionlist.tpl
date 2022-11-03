
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">{$title}</h3>
    <!-- END PAGE HEADER-->

    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        {if isset($success)}
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert"></button>
                    <strong>Success!</strong>{$success}
                </div> 
            {/if}
        <div class="col-md-12">
            <!-- BEGIN PAYMENT TRANSACTION TABLE -->
                <div class="portlet ">
                    <div class="portlet-body">
                        <table class="table table-striped  table-hover" id="table-ellipsis-small">
                            <thead>
                                <tr>
                                    <th class="td-c">
                                        Sent on
                                    </th>
                                    <th class="td-c">
                                    {$customer_default_column.customer_code|default:'Customer code'}
                                    </th>
                                    <th class="td-c">
                                    {$customer_default_column.customer_name|default:'Customer name'}
                                    </th>
                                    <th class="td-c">
                                        Cycle name
                                    </th>
                                    <th class="td-c">
                                        Due date
                                    </th>
                                    {if $list.0.display_invoice_no==1}
                                        <th class="td-c">
                                            Invoice number
                                        </th>
                                    {/if}


                                    <th class="td-c">
                                        Amount
                                    </th>
                                    <th class="td-c">
                                        Status
                                    </th>
                                    <th class="td-c">
                                        View
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                            <form action="" method="">
                                {$int=0}
                                {foreach from=$list item=v}
                                    {$int=$int+1}
                                    <tr>
                                        <td class="td-c">
                                            {{$v.send_date}|date_format:"%d %b %y"}
                                        </td>
                                        <td class="td-c">
                                            {$v.customer_code}
                                        </td>
                                        <td class="td-c">
                                            <a target="_BLANK" href="/merchant/paymentrequest/view/{$v.paylink}">{$v.name}</a>
                                        </td>
                                        <td class="td-c">
                                            {$v.billing_cycle_name}
                                        </td>
                                        <td class="td-c">
                                            {{$v.due_date}|date_format:"%d %b %y"}
                                        </td>
                                        {if $list.0.display_invoice_no==1}
                                            <td class="td-c">
                                                {$v.invoice_number}
                                            </td>
                                        {/if}
                                        <td class="td-c">
                                            {$v.absolute_cost}
                                        </td>
                                        <td class="td-c">
                                            {if {$v.status}=='Submitted'} <span class="label label-sm label-success">Submitted
                                                </span> {else} <span class="label label-sm label-danger">Failed
                                                </span> {/if}
                                            </td>
                                            <td>
                                                <div class="btn-group dropup" style="position: absolute;">
                                                    <button class="btn btn-xs btn-link dropdown-toggle" type="button" data-toggle="dropdown">
                                                        &nbsp;&nbsp;<i class="fa fa-ellipsis-v"></i>&nbsp;&nbsp;
                                                    </button>
                                                    <ul class="dropdown-menu pull-right" role="menu">
                                                        <li>
                                                            <a target="_BLANK" href="/merchant/paymentrequest/view/{$v.paylink}">
                                                                <i class="fa fa-table"></i> View </a>
                                                        </li>
                                                        {if $v.payment_request_status==0 || $v.payment_request_status==4 || $v.payment_request_status==5}
                                                            {if $v.invoice_type==1}
                                                                <li><a target="_BLANK" href="/merchant/paymentrequest/view/{$v.paylink}#respond" title="Settle request" ><i class="fa fa-inr"></i> Settle</a>
                                                                </li>
                                                            {else}
                                                                <li><a target="_BLANK" href="/merchant/paymentrequest/view/{$v.paylink}#convert" title="Convert to Invoice" ><i class="fa fa-inr"></i> Convert to Invoice</a>
                                                                </li>
                                                                <li><a target="_BLANK" href="/merchant/paymentrequest/view/{$v.paylink}#settleestimate" title="Settle" ><i class="fa fa-inr"></i> Settle</a>
                                                                </li>
                                                            {/if}
                                                            <li><a href="/merchant/invoice/update/{$v.paylink}" title="Update request" ><i class="fa fa-edit"></i> Edit</a>
                                                            </li>
                                                        {/if}
                                                        <li>
                                                            <a href="/merchant/comments/view/{$v.paylink}" title="Comments" class="iframe" ><i class="fa fa-comment"></i>Comments </a>
                                                        </li>
                                                        {if $v.payment_request_status==0 || $v.payment_request_status==4 || $v.payment_request_status==5}
                                                            <li>
                                                                <a title="Delete Invoice" href="#basic" onclick="document.getElementById('deleteanchor').href = '/merchant/paymentrequest/delete/{$v.paylink}'" 
                                                                   data-toggle="modal" ><i class="fa fa-remove"></i>Delete</a>  
                                                            </li>
                                                        {/if}

                                                        <li>
                                                            <div style="font-size: 0px;"><abcd{$int}>{$server_name}/patron/paymentrequest/view/{$v.paylink}</abcd{$int}></div>
                                                            <a class="btn bs_growl_show" data-clipboard-action="copy"  data-clipboard-target="abcd{$int}"><i class="fa fa-clipboard"></i> Copy Invoice Link</a>
                                                        </li>

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

                            <!-- END PAYMENT TRANSACTION TABLE -->
                        </div>
                    </div>
                    <!-- END PAGE CONTENT-->
                </div>
            </div>
            <!-- END CONTENT -->
        </div>
        <!-- /.modal -->

        <div class="modal fade" id="basic" tabindex="-1" role="basic" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                        <h4 class="modal-title">Delete Invoice</h4>
                    </div>
                    <div class="modal-body">
                        Are you sure you would not like to use this Invoice in the future?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn default" data-dismiss="modal">Close</button>
                        <a href="" id="deleteanchor" class="btn delete">Confirm</a>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>