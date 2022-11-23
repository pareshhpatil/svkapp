{strip}
    <table  style="margin:0 auto; font-family:Verdana, Arial;border: 1px solid #e2e2e2;width: 600px" align="center" width="600" border="0" cellspacing="0" cellpadding="10" >
        
        <tr>
            <td style="font-size:12px; line-height:20px;  border-bottom:1px #e2e2e2;">
                <div style="float:left; width:280px; margin-right:5px;">
                    <table width="580" border="0" cellspacing="0" cellpadding="5" style="color:#5b4d4b;font-size: 12px;line-height: 14px;">
                        <tr>
                            <td> <span style="font-size: 18px;color: #275770;width: 100%;font-weight: bold;">Thank You</span> </td>
                        </tr>
                        <tr>
                            <td> <span style="font-size: 13px;width: 100%;">Your form has been submited successful.</span> </td>
                        </tr>
                    </table>
                </div>

            </td>
        </tr>

        <tr >
            <td>
                <div>
                    {if !empty($response.form_details)}
                        <table width="100%" border="0" cellspacing="0" cellpadding="5" style="color:#5b4d4b;font-size: 13px;line-height: 15px;line-height: 25px;">
                            <thead>
                                <tr style="background-color: #275770;height: 40px;color: #FFFFFF;font-size: 18px;">
                                    <th colspan="2" style="font-weight: normal;" >Form Details</th>
                                </tr>
                            </thead>
                        </table> 
                        {foreach from=$response.form_details item=v}
                            {if $v.label!='' && $v.value!=''}
                                <table width="100%" border="0" cellspacing="0" cellpadding="5" style="color:#5b4d4b;font-size: 13px;line-height: 15px;line-height: 25px;">
                                    <tr>
                                        <td style="border: 1px solid #e2e2e2;border-top: 0px; width: 40%;">{$v.label}</td>
                                        <td style="border: 1px solid #e2e2e2;border-top: 0px;border-left: 0px;">
                                            {$v.value}
                                        </td>
                                    </tr>
                                </table>
                            {/if}
                        {/foreach}
                    {/if}
                </div>
            </td>
        </tr>

        <tr>
            <td style="color:#5b4d4b; line-height:30px; border-top:1px solid #e2e2e2; text-align:right; font-size:13px;">

                <span style="float: left;">&copy; {$current_year} OPUS Net Pvt. Handmade in Pune.</span>
                <span style="color: #275770;font-size: 15px;font-weight: 400;margin-right: 10px;">powered by</span><img src="https://www.swipez.in/images/logo.png" style="float:right; clear:right;" width="auto" height="37" class="img1" />

            </td>
        </tr>

    </table>
{/strip}