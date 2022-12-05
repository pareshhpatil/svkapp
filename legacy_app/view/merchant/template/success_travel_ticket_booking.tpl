{if $ajax==0}
    <div class="page-content">
    {/if}
    <!-- BEGIN PAGE HEADER-->
    <!-- END PAGE HEADER-->


    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            {if $ajax==0}
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
            {/if}

            <div class="">

                <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->

                <!-- /.modal -->
                <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
                <!-- BEGIN PAGE CONTENT-->
                <div class="invoice">
                    <div class="row invoice-logo">
                        <div class="col-xs-6 invoice-logo-space">
                            <div class="col-md-6">
                                {if {$image_path}!=''}
                                    <img src="/uploads/images/logos/{$image_path}" class="img-responsive templatelogo" alt=""/>
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
                    <hr/>

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
                                    {if {$v.position}=='R' && {$v.column_name!='Billing cycle name'}} <li><strong>{$v.column_name}:</strong>
                                            {if $v.datatype=='date'}
                                                {$current_date}
                                            {else if $v.datatype=='money'}
                                                12,245.00
                                            {else if $v.datatype=='number'}
                                                123456
                                            {else}
                                                Custom column {$ccol}
                                                {$ccol=$ccol+1}
                                            {/if}
                                        </li>  
                                    {/if}
                                {/foreach}

                            </ul>
                        </div>
                    </div>
                    <h4>Booking details</h4>
                    <div class="row">
                        <div class="col-xs-12">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th class="td-c">
                                            No.
                                        </th>
                                        <th class="td-c">
                                            Booking Date
                                        </th>
                                        <th class="td-c">
                                            Journey Date
                                        </th>
                                        <th class="td-c">
                                            Name
                                        </th>
                                        <th class="td-c"> 
                                            Type
                                        </th>
                                        <th class="td-c"> 
                                            From
                                        </th>
                                        <th class="td-c"> 
                                            To
                                        </th>
                                        <th class="td-c"> 
                                            Amt
                                        </th>
                                        <th class="td-c"> 
                                            Ser.Ch.
                                        </th>
                                        <th class="td-c"> 
                                            Total
                                        </th>

                                    </tr>
                                </thead>
                                <tbody>

                                    <tr>
                                        <td class="td-c">
                                            1
                                        </td>
                                        <td class="td-c">
                                            {$current_date}
                                        </td>
                                        <td class="td-c">
                                            {$current_date}
                                        </td>
                                        <td class="td-c">
                                            Rohit Sharma
                                        </td>
                                        <td class="td-c"> 
                                            Bus
                                        </td>
                                        <td class="td-c"> 
                                            Mumbai
                                        </td>
                                        <td class="td-c"> 
                                            Goa
                                        </td>
                                        <td class="td-c"> 
                                            1,600.00
                                        </td>
                                        <td class="td-c"> 
                                            200.00
                                        </td>
                                        <td class="td-c"> 
                                            1,800.00
                                        </td>

                                    </tr>
                                    <tr >
                                        <td colspan="7" >
                                            <b class="pull-right">Total Rs.</b>
                                        </td>
                                        <td class="td-c"> 1,600.00</td>
                                        <td class="td-c"> 200.00</td>
                                        <td class="td-c"> 1,800.00</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <h4>Cancellation details</h4>
                    <div class="row">
                        <div class="col-xs-12">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th class="td-c">
                                            No.
                                        </th>
                                        <th class="td-c">
                                            Booking Date
                                        </th>
                                        <th class="td-c">
                                            Journey Date
                                        </th>
                                        <th class="td-c">
                                            Name
                                        </th>
                                        <th class="td-c"> 
                                            Type
                                        </th>
                                        <th class="td-c"> 
                                            From
                                        </th>
                                        <th class="td-c"> 
                                            To
                                        </th>
                                        <th class="td-c"> 
                                            Amt
                                        </th>
                                        <th class="td-c"> 
                                            Ser.Ch.
                                        </th>
                                        <th class="td-c"> 
                                            Total
                                        </th>

                                    </tr>
                                </thead>
                                <tbody>

                                    <tr>
                                        <td class="td-c">
                                            1
                                        </td>
                                        <td class="td-c">
                                            {$current_date}
                                        </td>
                                        <td class="td-c">
                                            {$current_date}
                                        </td>
                                        <td class="td-c">
                                            Neha Sharma
                                        </td>
                                        <td class="td-c"> 
                                            Bus
                                        </td>
                                        <td class="td-c"> 
                                            Mumbai
                                        </td>
                                        <td class="td-c"> 
                                            Goa
                                        </td>
                                        <td class="td-c"> 
                                            1,000.00
                                        </td>
                                        <td class="td-c"> 
                                            200.00
                                        </td>
                                        <td class="td-c"> 
                                            800.00
                                        </td>

                                    </tr>
                                    <tr >
                                        <td colspan="7" >
                                            <b class="pull-right">Total Rs.</b>
                                        </td>
                                        <td class="td-c"> 1,000.00</td>
                                        <td class="td-c">200.00</td>
                                        <td class="td-c"> 800.00</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

