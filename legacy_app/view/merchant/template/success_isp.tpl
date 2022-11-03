{if $ajax==0}
    <div class="page-content">
    {/if}
   
    <div style="text-align: -webkit-center;text-align:-moz-center;">
        <!-- END PAGE HEADER-->
        <!-- BEGIN PAGE CONTENT-->
        {if $ajax==0}
            <div class="row" style="max-width: 900px;text-align: left;">
                <div class="col-md-12 no-padding">
                    <div class="alert alert-block alert-success fade in nolr-margin">
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
        <div class="invoice" style=" display:none;text-align: left;padding: 12px 0px 15px 0px;">
            <div class="row invoice-logo  no-margin" style="margin-bottom: 0px;">
                <div class="col-md-3" style="max-width: 200px;">
                    {if {$image_path}!=''}
                        <img src="/uploads/images/logos/{$image_path}" style="max-width: 190px;"
                            class="img-responsive templatelogo" alt="" />
                    {/if}
                </div>
                <div class="col-md-8">
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
            <div class="row no-margin bg-grey"
                style="border-top: 1px solid black !important;border-bottom: 1px solid black !important;">
                <div class="col-md-12" style="text-align: center;">
                    <h4><b>INVOICE</b></h4>
                </div>
            </div>
            <div class="row no-margin">
                <div class="col-md-6">
                    <ul class="list-unstyled" style="margin-top: 10px;">
                        {foreach from=$customer_column_list item=v}
                            <div class="row">
                                <div class="col-xs-6">
                                    <li><strong>{$v.column_name} :</strong></li>
                                </div>
                                <div class="col-xs-6">
                                    <li>
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
                                </div>
                            </div>
                        {/foreach}
                    </ul>
                </div>

                <div class="col-md-6 invoice-payment" style="border-left: 1px solid;">
                    <ul class="list-unstyled" style="margin-top: 10px;">
                        {$ccol=1}
                        {foreach from=$header item=v}
                            {if {$v.position}=='R' && {$v.column_name!='Billing cycle name'}} <div class="row">
                                    <div class="col-xs-6">
                                        <li><strong>{$v.column_name}:</strong></li>
                                    </div>
                                    <div class="col-xs-6">
                                        <li>
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
                                    </div>
                                </div>
                            {/if}
                        {/foreach}

                    </ul>
                </div>
            </div>
            {if !empty($bds_column)}
                <h4>Booking Information</h4>
                <div class="row">

                    <div class="col-xs-5 invoice-payment">
                        <ul class="list-unstyled">
                            {foreach from=$bds_column item=v}
                                {if $v.position=='L'}
                                    <li><strong>{$v.column_name}:</strong> xxxxx</li>
                                {/if}
                            {/foreach}
                        </ul>
                    </div>
                    <div class="col-xs-1">
                    </div>
                    <div class="col-xs-5 invoice-payment">
                        <ul class="list-unstyled">
                            {foreach from=$bds_column item=v}
                                {if $v.position=='R'}
                                    <li><strong>{$v.column_name}:</strong> xxxxx</li>
                                {/if}
                            {/foreach}
                        </ul>
                    </div>
                </div>
            {/if}

            {if $info.template_type=='isp'}
                <div class="row no-margin"
                    style="border-top: 1px solid black !important;border-bottom: 1px solid black !important; font-size: 15px;line-height: 2.5;">
                    <div class="col-md-4" style="border-right: 1px solid black;">
                        <div class="col-xs-8"><strong>Past Due :</strong></div>
                        <div class="col-xs-4"> 00.00 </div>
                    </div>
                    <div class="col-md-4" style="border-right: 1px solid black;">
                        <div class="col-xs-8"><strong>Current Charges :</strong></div>
                        <div class="col-xs-4">{$sub_total|number_format:2:".":","}</div>
                    </div>
                    <div class="col-md-4 bg-grey" style="">
                        <div class="col-xs-6"><strong>Total Due :</strong></div>
                        <div class="col-xs-6">{$total_amount|number_format:2:".":","}</div>
                    </div>
                </div>
            {/if}

            <div class="row">
                <div class="col-md-12">
                    <div class="table-scrollable">
                        <table class="table table-bordered table-condensed">
                            <thead>
                                <tr>

                                    {foreach from=$particular_col item=v}
                                        <th style="border-bottom: 1px solid #ddd;">
                                            {$v}
                                        </th>
                                    {/foreach}
                                </tr>
                            </thead>
                            <tbody>
                                {$int=1}
                                {foreach from=$default_particular key=kk item=dp}
                                    <tr>
                                        {foreach from=$particular_col key=k item=v}
                                            {if $k=='sr_no'}
                                                <td class="td-c" style="border-top: 0;border-bottom: 0;">
                                                    {$int}
                                                </td>
                                            {elseif $k=='item'}
                                                <td style="border-top: 0;border-bottom: 0;">
                                                    {$dp}
                                                </td>
                                            {else}
                                                <td class="td-c" style="border-top: 0;border-bottom: 0;">
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


                                {if count($particular_col)>4}
                                    {$subtotal_colspan=2}
                                    {$colspan=count($particular_col)-3}
                                {else}
                                    {$subtotal_colspan=1}
                                    {$colspan=count($particular_col)-2}
                                {/if}
                                <tr>
                                    <td colspan="{$colspan}" class="col-md-8" style="border-bottom: 0;">
                                        {if $info.tnc!=''}
                                            <b>Terms & Conditions</b><br>
                                            {$info.tnc}
                                        {/if}
                                    </td>
                                    <td colspan="{$subtotal_colspan+1}" style="padding:0;">
                                        <table class="table table-bordered"
                                            style="margin:0 !important;border-left: 0px !important;">
                                            <tr>
                                                <td class="col-md-6 bl-0">
                                                    <b>{$lang_title.sub_total}</b>
                                                </td>
                                                <td class="col-md-6 td-c ">
                                                    <b> {$sub_total|number_format:2:".":","}</b>
                                                </td>
                                            </tr>

                                            {foreach from=$tax item=v}
                                                <tr>
                                                    <td class="col-md-6 bl-0">
                                                        {$tax_list.{$v}.tax_name}
                                                    </td>
                                                    <td class="col-md-6 td-c ">
                                                        {$tax_amount=$sub_total*$tax_list.{$v}.percentage/100}
                                                        {$tax_amount|number_format:2:".":","}
                                                        {$taxtotal={$taxtotal}+{$tax_amount}}
                                                    </td>
                                                </tr>
                                            {/foreach}
                                            <tr>
                                                <td class="col-md-6 bl-0">
                                                    <b>{$lang_title.total_rs}</b>
                                                </td>
                                                <td class="col-md-6 td-c">
                                                    <b> {($sub_total+$taxtotal)|number_format:2:".":","}</b>
                                                </td>
                                            </tr>
                                            {if $advance>0}
                                                <tr>
                                                    <td class="col-md-6 bl-0">
                                                        <b>Advance Received</b>
                                                    </td>
                                                    <td class="col-md-6 td-c br-0">
                                                        <b> {($advance)|number_format:2:".":","}</b>
                                                    </td>
                                                </tr>
                                                <tr>

                                                    <td class="col-md-6 bl-0">
                                                        <b>Balance</b>
                                                    </td>
                                                    <td class="col-md-6 td-c br-0">
                                                        <b> {($grand_total)|number_format:2:".":","}</b>
                                                    </td>
                                                </tr>
                                            {/if}
                                            <tr>
                                                {if $merchant_detail.pan!=''}
                                                    <td class="col-md-6 bl-0">
                                                        {$lang_title.pan_no}.
                                                    </td>
                                                    <td colspan="{$subtotal_colspan}" class="col-md-6 br-0">
                                                        {$merchant_detail.pan}
                                                    </td>
                                                {/if}
                                            </tr>

                                            <tr>
                                                {if $tan!=''}
                                                    <td class="col-md-6 bl-0">
                                                        TAN NO.
                                                    </td>
                                                    <td colspan="{$subtotal_colspan}" class="col-md-6 br-0">
                                                        {$tan}
                                                    </td>
                                                {/if}
                                            </tr>


                                            <tr>
                                                {if $cin_no!=''}
                                                    <td class="col-md-6 bl-0">
                                                        CIN NO.
                                                    </td>
                                                    <td colspan="{$subtotal_colspan}" class="col-md-6 br-0">
                                                        {$cin_no}
                                                    </td>
                                                {/if}
                                            </tr>


                                            <tr>
                                                {if $merchant_detail.gst_number!=''}
                                                    <td class="col-md-6 bl-0">
                                                        {$lang_title.gst_no}
                                                    </td>
                                                    <td colspan="{$subtotal_colspan}" class="col-md-6 br-0">
                                                        {$merchant_detail.gst_number}
                                                    </td>
                                                {else}
                                                    {if $registration_number!=''}
                                                        <td class="col-md-6 bl-0">
                                                            S. Tax Regn.
                                                        </td>
                                                        <td colspan="{$subtotal_colspan}" class="col-md-6 br-0">
                                                            {$registration_number}
                                                        </td>
                                                    {/if}
                                                {/if}
                                            </tr>

                                            <tr>
                                                <td colspan="2" class="col-md-12 bl-0 br-0">
                                                    <b>{$lang_title.amount_in_word} : </b> {$money_words}
                                                </td>
                                            </tr>

                                        </table>
                                    </td>

                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>



            <!-- END PAGE CONTENT-->