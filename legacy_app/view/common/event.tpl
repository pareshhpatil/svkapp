<div class="gap"></div>
<div class="invoice-1">
    <div class="banner" style="background-image: url(/uploads/images/logos/{$banner_path});" >  
        <div class="eventtitle"><h1 style="font-size: 25px; margin-top: 15px;">  {$event_name}</h1></div>
        {if {$image_path} != ''} <div style="height: 119px;width: 193px;margin-top: 205px;margin-left: 4px;-webkit-box-shadow: 2px 1px 1px grey;
             -webkit-border-radius: 5px;position: absolute;">  <img id="logoimg" src="/uploads/images/logos/{$image_path}" style="height: 119px;width: 193px;position: absolute; " /></div>{else}<div style="height: 119px;width: 193px;margin-top: 205px;margin-left: 4px;-webkit-box-shadow: 2px 1px 1px grey;
                                                                                                                                                       -webkit-border-radius: 5px;position: absolute;">  <img id="logoimg" src="/images/nologo.gif" style="height: 119px;width: 193px;position: absolute; " /></div> {/if}
        </div> 



        <div class="invoice-1">

            <div class="table-1" style="padding:0px; margin-top:14px;">
                <div class="row-a">

                    <div class="box-two_">Event details</div> 

                </div> 





                <div class="row-b">
                    <div class="box-two_2"style="width: 200px;float: left;text-align: justify;">Event name :</div>  <div class="box-two_2" style="width: 400px;float: left;text-align: justify;text-indent:0px;font-weight: normal;">&nbsp;{$event_name}</div>   

                    {assign var=val value=4}
                    {section name=sec1 loop=$header}
                        {if {$header[{$val}].column_position}!=4 && {$header[{$val}].column_position}!=5 && {$header[{$val}].column_position}!=2}
                        <div class="box-two_2"style="width: 200px;float: left;text-align: justify;">{$header[{$val}].column_name} :</div>  <div class="box-two_2" style="width: 400px;float: left;text-align: justify;text-indent:0px;font-weight: normal;">&nbsp;{$header[{$val}].value}</div>   
                        {/if}
                        {assign var=val value=$val+1}
                    {/section}
                    
                    {if {$bill_date}!={$due_date}}
                        <div class="box-two_2"style="width: 200px;float: left;text-align: justify;">Event date :</div>  <div class="box-two_2" style="width: 400px;float: left;text-align: justify;text-indent:0px;font-weight: normal;">{$bill_date} to {$due_date}</div>   
                    {else}
                        <div class="box-two_2"style="width: 200px;float: left;text-align: justify;">Event date :</div>  <div class="box-two_2" style="width: 400px;float: left;text-align: justify;text-indent:0px;font-weight: normal;">{$due_date}</div>   

                    {/if}

                </div> 
            </div>








        </div>



           