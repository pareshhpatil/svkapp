                                

<div class="row">            
    <div class="col-md-12">
        <h3 class="page-title" style="margin-left: 0px !important;">Select a package</h3>
        <!-- BEGIN PAYMENT TRANSACTION TABLE -->

        <div class="portlet-body">
            <div class="table-scrollable">
                <table class="table table-striped  table-hover" id="sample_1">
                    <thead>
                        <tr>

                            <th class="td-c">
                                Packages
                            </th>
                            <th class="td-c">
                                Speed
                            </th>
                            <th class="td-c">
                                Validity
                            </th>
                            <th class="td-c">
                                Data limit
                            </th>
                            {if $has_tax==1}
                                <th class="td-c">
                                    Price
                                </th>
                                <th class="td-c">
                                    GST
                                </th>
                                <th class="td-c">
                                    Total
                                </th>
                            {else}
                                <th class="td-c">
                                    Price
                                </th>
                            {/if}
                            <th class="td-c">
                                View
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                    <form action="" method="">
                        {$int=0}
                        {$id=0}
                        {foreach from=$requestlist item=v key=k}
                            {$cat='abcd'}
                            {foreach from=$requestlist.$k item=vp}
                                <tr>
                                    {if {$cat!=$k}}
                                        {if $int==1}
                                            <td colspan="6"></td>
                                        </tr><tr>
                                        {/if}
                                        {$cat=$k}
                                        <td class="td-c " style="vertical-align: middle;background-color: #0da3e2;color: #ffffff;" rowspan="{$requestlist.$k|@count}">
                                            <h4><b>{$k}</b></h4>
                                        </td>
                                    {/if}
                                    <td class="td-c">
                                        {$vp.speed}
                                    </td>
                                    <td class="td-c" style="text-align: -webkit-center;">
                                        {if $vp.duration|@count >1}
                                            <select name="selecttemplate" style="width: 100px;" onchange="getplanDetails('{$merchant_id}{$vp.plan_id}', this.value,{$id}, '{$url}');" required class="form-control input-sm" data-placeholder="Select...">
                                                {foreach from=$vp.duration item=vd}
                                                    <option value="{$vd.duration}">{$vd.duration} Month</option>
                                                {/foreach}
                                            </select>
                                        {else}
                                            {$vp.duration.0.duration} Month
                                        {/if}
                                    </td>
                                    <td class="td-c">
                                        {$vp.data}
                                    </td>
                                    {if $has_tax==1}
                                        <td class="td-c">
                                        <span id="base_{$id}">
                                        {$vp.base_amount}</span>
                                        </td>
                                        <td class="td-c">
                                        <span id="tax_{$id}">
                                        {$vp.tax_amount}</span>
                                        </td>
                                        <td class="td-c">
                                        <span id="price_{$id}">
                                            {$vp.price}</span>
                                    </td>
                                    {else}
                                        <td class="td-c">
                                        <span id="price_{$id}">
                                            {$vp.price}</span>
                                    </td>
                                    {/if}
                                    <td class="td-c"> 
                                        <input type="hidden" id="planid_{$id}" value="{$vp.plan_link}">
                                        <a  href="/m/{$url}/confirmpackage/{$vp.plan_link}" id="link_{$id}"  class="btn btn-xs blue"><i class="fa fa-check"></i> Select plan </a>
                                    </td>
                                </tr>
                                {$id=$id+1}
                                {$int=1}
                            {/foreach}
                        {/foreach}
                    </form>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
</div>
<!-- END PAYMENT TRANSACTION TABLE -->
{if $hide_footer!=true}
<hr/>
<p>&copy; {$current_year} OPUS Net Pvt. Handmade in Pune. <a target="_BLANK" href="https://www.swipez.in"><img src="/assets/admin/layout/img/logo.png" class="img-responsive pull-right powerbyimg" alt=""/><span class="powerbytxt">powered by</span></a></p>
</div>
{/if}
</div>	
<!-- END PAGE CONTENT-->
</div>
</div>


<a  href="#guestpay"  data-toggle="modal" id="guest"></a>   



<script>

    function planselect(id)
    {

        var link = document.getElementById('planid_' + id).value;
        var postlink = '/login/failed/' + link + '|ponl';
        $('#guestlogin').attr('action', postlink);
        $('#gpay').attr('href', '/m/{$url}/confirmpackage/' + link);
        $('#guestjoin').attr('href', '/patron/register/index/' + link + '!plan');

        var price = document.getElementById('price_' + id).innerHTML;
        document.getElementById('booking_amount').innerHTML = '<i class="fa fa-inr fa-large"></i>' + price;

    }
</script>