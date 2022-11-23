{if $ajax==0}
    <div class="page-content">
    {/if}
    <!-- BEGIN PAGE HEADER-->
    <!-- END PAGE HEADER-->


    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-10">

            {if $ajax==0}
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-block alert-success fade in  nolr-margin">
                            <h4 class="alert-heading">Template Created / Modified</h4>
                            <p>
                                Template has been saved. You can use this template to send payment requests to your patrons.
                            </p>
                            <p>
                                <a class="btn blue input-sm" href="/merchant/template/update/{$link}">
                                    Update template </a>
                                <a class="btn green input-sm" href="/merchant/invoice/create">
                                    Create invoice </a>
                            </p>
                        </div>
                    </div>
                </div>
            {/if}


            <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->

            <!-- /.modal -->
            <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
            <!-- BEGIN PAGE CONTENT-->
            <div class="invoice" >

                <div class="">
                    <div class="row invoice-logo">
                        <div class="col-xs-6 invoice-logo-space">
                            <div class="col-md-6">
                                {if {$image_path}!=''}
                                    <img src="/uploads/images/logos/{$image_path}" class="img-responsive templatelogo"
                                        alt="" />
                                {/if}
                            </div>
                        </div>
                        <div class="col-xs-2"></div>
                        <div class="col-xs-4">
                            <p style="text-align: left;">
                                <a href="{$merchant_page}" class="invoice-heading" target="_BLANK">{$company_name}</a>
                                <br>
                                <span class="muted">
                                    {$merchant_address}<br>
                                    E-mail: {$business_email}<br>
                                    Contact: {$business_contact} <br>
                                </span>
                            </p>
                        </div>
                    </div>
                    <hr />

                    <div class="row">

                        <div class="col-xs-5 invoice-payment">
                            <ul class="list-unstyled">
                                {foreach from=$customer_column_list item=v}
                                    <li><strong>{$v.column_name}:</strong>
                                        {if $v.customer_column_id==1}
                                            Cust-124
                                        {else if $v.customer_column_id==2}
                                            Rohit Sharma
                                        {else if $v.customer_column_id==3}
                                            rohitsharmabills@gmail.com
                                        {else if $v.customer_column_id==4}
                                            9876543210
                                        {else if $v.customer_column_id==5}
                                            134 135, Vashi Plaza C Wing, Sector 17, Vashi, Mumbai
                                        {else if $v.customer_column_id==6}
                                            Mumbai
                                        {else if $v.customer_column_id==7}
                                            Maharashtra
                                        {else if $v.customer_column_id==7}
                                            400703
                                        {/if}
                                    </li>
                                {/foreach}

                            </ul>
                        </div>
                        <div class="col-xs-1">
                        </div>
                        <div class="col-xs-5 invoice-payment">
                            <ul class="list-unstyled">
                                {$ccol=1}
                                {foreach from=$header item=v}
                                    {if {$v.position}=='R' && {$v.column_name!='Billing cycle name'}} <li>
                                            <strong>{$v.column_name}:</strong>
                                            {if $v.datatype=='date'}
                                                {$current_date}
                                            {else if $v.datatype=='money'}
                                                {if $template_type=='scan'}
                                                    1,000.00
                                                {else}
                                                    12,245.00
                                                {/if}
                                            {else if $v.datatype=='number'}
                                                123456
                                            {else}
                                                {if $v.function_id==9}
                                                    INV-001
                                                {else}
                                                    Custom column {$ccol}
                                                    {$ccol=$ccol+1}
                                                {/if}
                                            {/if}
                                        </li>
                                    {/if}
                                {/foreach}

                            </ul>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-xs-12">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        {foreach from=$particular_col key=k item=v}
                                            <th {if $k!='item'}class="td-c" {/if}>
                                                {$v}
                                            </th>
                                        {/foreach}
                                    </tr>
                                </thead>
                                <tbody>
                                <tbody>
                                <tbody>
                                    {$int=1}
                                    {foreach from=$default_particular item=dp}
                                        <tr>
                                            {foreach from=$particular_col key=k item=v}
                                                {if $k=='sr_no'}
                                                    <td class="tdc">
                                                        {$int}
                                                    </td>
                                                {elseif $k=='item'}
                                                    <td>
                                                        {$dp}
                                                    </td>
                                                {else}
                                                    <td class="td-c">
                                                        {if $k=='qty'}
                                                            1
                                                        {elseif $k=='rate'}
                                                            1,000.00
                                                        {elseif $k=='total_amount'}
                                                            1,000.00
                                                        {/if}
                                                    </td>
                                                {/if}
                                            {/foreach}
                                        </tr>
                                        {$int=$int+1}
                                    {/foreach}
                                    <tr>
                                        {foreach from=$particular_col key=k item=v}
                                            <td {if $k!='item'}class="td-c" {/if}>
                                                {if $k=='item'}<b> Particular total</b>{/if}
                                                {if $k=='total_amount'}{$sub_total|number_format:2:".":","}{/if}
                                            </td>
                                        {/foreach}
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>