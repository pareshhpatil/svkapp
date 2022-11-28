{if isset($signature.font_file)}
    <link href="{$signature.font_file}" rel="stylesheet">
{/if}
{if !empty($tax) && $template_type!='isp'}
    <div class="row">
        <div class="col-xs-12">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>
                            Tax name
                        </th>
                        <th class="hidden-480">
                            Percentage
                        </th>
                        <th class="hidden-480">
                            Applicable 
                        </th>
                        <th class="hidden-480">
                            Amount
                        </th>
                        <th>
                            Narrative
                        </th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$tax item=v}
                        <tr> <td class="">
                                {$tax_list.{$v}.tax_name}
                            </td>
                            <td class="hidden-480 td-c">
                                {$tax_list.{$v}.percentage}
                            </td>
                            <td class="hidden-480 td-c">
                                {$sub_total|number_format:2:".":","}
                            </td>
                            <td class="hidden-480 td-c">
                                {$tax_default.{$v}|number_format:2:".":","}
                            </td>
                            <td class="hidden-480">
                                
                            </td>
                        </tr>
                    {/foreach}

                    <tr> <td>
                            <b>Tax total</b>
                        </td>
                        <td class="hidden-480">

                        </td>
                        <td class="hidden-480">

                        </td>
                        <td class="hidden-480 td-c">
                            {{$total_amount-$sub_total}|number_format:2:".":","}
                        </td>
                        <td class="hidden-480">

                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>
{/if}

<div class="row no-margin"><br>
    <div class="col-xs-6">
        {if $template_type=='travel_ticket_booking'}
            <b class="pull-left">Amount (in words) :&nbsp; </b> {$money_words} <br>
            {if $info.tnc!=''}
                <p style="margin-bottom: 2px;"> {$info.tnc}</p>
            {/if}
        {/if}
        <div class="well">
            <strong>Narrative</strong><br/>
            It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.
        </div>
    </div>
    <div class="col-md-6 invoice-block">
        <ul class="list-unstyled amounts pull-right">
            <li >
                <strong>Bill value with taxes:</strong> <i class="fa  fa-inr"></i> {$total_amount|number_format:2:".":","}
            </li>
            <li class="pull-right">
                <strong>Grand Total:</strong> <i class="fa  fa-inr"></i> {$total_amount|number_format:2:".":","}
            </li>
        </ul>
        <br/>
        
    </div>
</div>
{if $info.tnc!='' && $template_type!='isp'}
<div class="row no-margin">
<div class="col-xs-12 no-padding">
        <b>Terms & Conditions</b><br>
        {$info.tnc}
    </div>
 </div>
  {/if}
{if isset($signature)}
    <div class="row" style="padding-right: 20px;padding-left: 20px;">
        <div class="col-md-12">
            <div class="pull-{$signature.align}">
                {if $signature.type=='font'}
                    <label style="font-family: '{$signature.font_name}',cursive;font-size: {$signature.font_size}px;">{$signature.name}</label>
                {else}
                    <img src="{$signature.signature_file}" style="max-height: 100px;">
                {/if}
            </div>
        </div>
    </div>
{/if}


</div>
<!-- END PAGE CONTENT-->
</div>
</div>
<div class="col-md-1"></div>
</div>
<!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->
