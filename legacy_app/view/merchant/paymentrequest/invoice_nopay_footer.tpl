<div class="row">
    <div class="col-xs-4">
        {if $narrative!=''}
            <div class="well">
                {$narrative}
            </div>
        {/if}
    </div>
    <div class="col-xs-8 invoice-block">
        {if $template_type!='isp'}
            <ul class="list-unstyled amounts">
                <li>
                    <strong>Bill value {if !empty($tax)}with taxes{/if}:</strong> <i class="fa  {$info.currency_icon}"></i>{$invoice_total}
                </li>
                {if $advance>0}
                    <li>
                        <strong>Advance received :</strong> <i class="fa  {$info.currency_icon}"></i> <span>{$advance|number_format:2:".":","}</span>
                    </li>
                {/if}
                {if $payment_request_status!=1 && $payment_request_status!=2 && $payment_request_status!=3}
                    <li>
                        <strong>Total Amount Payable :</strong> <i class="fa  {$info.currency_icon}"></i> {$grand_total}
                    </li>
                {/if}
            </ul>
            <br/>
        {/if}
        {if $payment_request_status!=3}
        <div class="row no-margin">
            <div class="col-md-12">
                {if $plugin.has_digital_certificate_file==1}
                    <a target="_BLANK" class="btn btn-link hidden-print margin-bottom-5 pull-right" style="margin-right: 20px;" href="/patron/paymentrequest/download_tcpdf/{$Url}">
                        Save as PDF 
                    </a>
                    <a target="_BLANK" class="btn btn-link hidden-print margin-bottom-5 pull-right" style="margin-right: 20px;" href="/patron/paymentrequest/download_tcpdf/{$Url}/2">
                        Print
                    </a>
                {else}
                    <a target="_BLANK" class="btn btn-link hidden-print margin-bottom-5 pull-right" style="margin-right: 20px;" href="/patron/paymentrequest/download/{$Url}">
                        Save as PDF 
                    </a>
                    <a target="_BLANK" class="btn btn-link hidden-print margin-bottom-5 pull-right" style="margin-right: 20px;" href="/patron/paymentrequest/download/{$Url}/2">
                        Print
                    </a>
                {/if}
            </div>
        </div>
        {/if}
    </div>
</div>
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
