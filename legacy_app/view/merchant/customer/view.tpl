<div class="page-content">
    <div class="page-bar">
        {include file="../../common/breadcumbs.tpl" title={$lang_title.view_customer} links=$links}
    </div>
    <!-- BEGIN PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        {if isset($haserrors)}
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert"></button>
                <strong>Error!</strong>
                <div class="media">
                    {foreach from=$haserrors item=v}
                        <p class="media-heading">{$v.0} - {$v.1}.</p>
                    {/foreach}
                </div>

            </div>
        {/if}
        {if isset($success)}
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert"></button>
                <h4 class="alert-heading">Customer Created / Modified</h4>
                <p>
                    {$success}
                </p>
                <p>
                    <a class="btn blue input-sm" href="/merchant/customer/update/{$link}">
                        Update customer </a>


                    <a class="btn green input-sm" href="/merchant/dashboard">
                        {if $GettingStarted==true} Back to Getting Started{else}Dashboard{/if} </a>

                </p>
            </div>
        {/if}
        <div class="col-md-12">


            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <h4 class="form-section">{$lang_title.system_fields}</h4>
                    <div class="form-body">
                        <!-- Start profile details -->
                        <div class="alert alert-danger display-none">
                            <button class="close" data-dismiss="alert"></button>
                            You have some form errors. Please check below.
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="form-group">
                                        <label class="control-label col-md-4">{$customer_default_column.customer_code|default:$lang_title.customer_code}</label>
                                        <label class="control-label col-md-6">{$detail.customer_code}</label>
                                        <div class="help-inline"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label class="control-label col-md-4">{$customer_default_column.customer_name|default:$lang_title.customer_name}</label>
                                        <label class="control-label col-md-6">{$detail.first_name}
                                            {$detail.last_name}</label>
                                        <div class="help-inline"></div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group">
                                        <label class="control-label col-md-4">{$customer_default_column.email|default:$lang_title.email}</label>
                                        <label class="control-label col-md-6">{$detail.email}</label>
                                        <div class="help-inline"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label class="control-label col-md-4">{$customer_default_column.mobile|default:$lang_title.mobile}</label>
                                        <label class="control-label col-md-6">{$detail.mobile}</label>
                                        <div class="help-inline"></div>
                                    </div>
                                </div>





                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="form-group">
                                        <label class="control-label col-md-4">{$customer_default_column.address|default:$lang_title.address}</label>
                                        <label class="control-label col-md-6">{$detail.address}
                                            {$detail.address2}</label>
                                        <div class="help-inline"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label class="control-label col-md-4">{$customer_default_column.country|default:$lang_title.country}</label>
                                        <label class="control-label col-md-6">{$detail.country}</label>
                                        <div class="help-inline"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label class="control-label col-md-4">{$customer_default_column.state|default:$lang_title.state}</label>
                                        <label class="control-label col-md-6">{$detail.state}</label>
                                        <div class="help-inline"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label class="control-label col-md-4">{$customer_default_column.city|default:$lang_title.city}</label>
                                        <label class="control-label col-md-6">{$detail.city}</label>
                                        <div class="help-inline"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label class="control-label col-md-4">{$customer_default_column.zipcode|default:$lang_title.zipcode}</label>
                                        <label
                                            class="control-label col-md-6">{if $detail.zipcode!=0}{$detail.zipcode}{/if}</label>
                                        <div class="help-inline"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End profile details -->
                    </div>
                    <h4 class="form-section">{$lang_title.custom_fields} </h4>

                    <div class="row">
                        <div class="col-md-6">
                            {foreach from=$column item=v}
                                {if $v.position=='L'}
                                    {if $v.column_datatype!='company_name'}
                                        <div class="row">
                                            <div class="form-group">
                                                <label class="control-label col-md-4">{$v.column_name}</label>
                                                <label class="control-label col-md-6">{$v.value}</label>
                                                <div class="help-inline"></div>
                                            </div>
                                        </div>
                                    {/if}
                                {/if}
                            {/foreach}
                            {if $detail.company_name}
                                <div class="row">
                                    <div class="form-group">
                                        <label class="control-label col-md-4">{$company_column_name}</label>
                                        <label class="control-label col-md-6">{$detail.company_name}</label>
                                        <div class="help-inline"></div>
                                    </div>
                                </div>
                            {/if}
                            <div class="row">
                                <div class="form-group">
                                    <label class="control-label col-md-4">Balance</label>
                                    <label class="control-label col-md-6">{$detail.balance}</label>
                                    <div class="help-inline"></div>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-6">
                            {foreach from=$column item=v}
                                {if $v.position=='R'}
                                    {if $v.column_datatype!='company_name'}
                                        <div class="row">
                                            <div class="form-group">
                                                <label class="control-label col-md-4">{$v.column_name}</label>
                                                <label class="control-label col-md-6">{$v.value}</label>
                                                <div class="help-inline"></div>
                                            </div>
                                        </div>
                                    {/if}
                                {/if}
                            {/foreach}
                        </div>
                    </div>
                </div>
            </div>





            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <h4 class="form-section">Customer Ledger</h4>

                    <table class="table table-striped  table-hover" id="table-no-export">
                        <thead>
                            <tr>

                                <th class="td-c">
                                    Date
                                </th>
                                <th class="td-c">
                                    Description
                                </th>
                                <th class="td-c">
                                    Type
                                </th>
                                <th class="td-c">
                                    Amount
                                </th>
                                <th class="td-c">
                                    Reference No
                                </th>

                            </tr>
                        </thead>
                        <tbody>
                            {foreach from=$ledger item=v}
                                <tr>

                                    <td class="td-c">
                                        {{$v.created_date}|date_format:"%d %b %Y "}
                                    </td>

                                    <td class="td-c">
                                        {$v.description}
                                    </td>
                                    <td class="td-c">
                                        {if $v.ledger_type=='CREDIT'}
                                            <span class="badge badge-pill status paid_online">CREDIT</span>
                                        {else}
                                            <span class="badge badge-pill status unpaid">DEBIT</span>
                                        {/if}
                                    </td>
                                    <td class="td-c">
                                        {$v.amount|string_format:"%.2f"}
                                    </td>
                                    <td class="td-c">
                                        {if $v.type==1}
                                            <a href="/merchant/transaction/invoice/{$v.ref}" class="iframe"
                                                target="_BLANK">{if $v.invoice_number!=''}{$v.invoice_number}{else}{$v.reference_no}{/if}</a>
                                        {else if $v.type==2}
                                            <a href="/merchant/transaction/receipt/{$v.ref}" class="iframe"
                                                target="_BLANK">{$v.reference_no}</a>
                                        {else if $v.type==3}
                                            <a href="/merchant/creditnote/view/{$v.ref}" class="iframe"
                                                target="_BLANK">{$v.reference_no}</a>

                                        {/if}

                                    </td>

                                </tr>

                            {/foreach}
                        </tbody>
                    </table>


                </div>
            </div>
        </div>
        <!-- END PAGE CONTENT-->
    </div>

</div>
<!-- END CONTENT -->
</div>