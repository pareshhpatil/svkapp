<div class="invoice-1">
    <div class="row-1">  {if {$image_path} != ''} <img src="/uploads/images/logos/{$image_path}"  width="193" height="100" />{else}<img src="/images/nologo.gif" width="193" height="100"  /> {/if}
        <p class="heading1">{if {$company_name}!=''}{if $merchant_page!=''}<a href="{$merchant_page}" target="_Blank">{$company_name}</a>{else}{$company_name}{/if}{else}&nbsp;{/if}</p>
        <p class="heading2">INVOICE</p>
    </div> 

  <div class="box-1" style="height:100px;">
        {if {$patron_name} != ''}   <p class="gray-big">Patron name: <span>{$patron_name}</span> </p>{/if}
        {if {$patron_email} != ''}   <p class="gray-big">Email Id: <span>{$patron_email}</span> </p>{/if}
        {if {$patron_mobile} != ''} <p class="gray-big">Mobile: <span>{$patron_mobile}</span> </p> {/if}
        {if {$bill_date} != ''}   <p class="gray-big">Bill date: <span>{$bill_date}</span> </p>{/if}
        {if {$due_date} != ''} <p class="gray-big">Due date: <span>{$due_date}</span> </p>{/if}



        {assign var=val value=4}
        {section name=sec1 loop=$header}
            {if {{$header[{$val}].position}=='L' && {$header[{$val}].value} != ''}}
                <p class="gray-big">{$header[{$val}].column_name}:  <span>{$header[{$val}].value}</span> </p>
            {/if}
            {assign var=val value=$val+1}
        {/section}
        {if {$narrative} != ''} <p class="gray-big"><span>{$narrative}</span> </p>       {/if}  
    </div>


    <div class="box-2" style="height:100px;">
        {assign var=val value=4}
        {section name=sec1 loop=$header}
            {if {{$header[{$val}].position}=='R' && {$header[{$val}].value} != ''}}
                <p class="gray-big">{$header[{$val}].column_name}:  <span>{$header[{$val}].value}</span> </p>
            {/if}
            {assign var=val value=$val+1}
        {/section}

    </div> 



    <div class="box-3">
        <p class="blue-big">&nbsp;Student Fees Detail</p>

    </div>
    {if ({$particular[{1}].value.0}!='')} 
        <div class="table-1" style="padding-top:6px;">
            <div class="row-a">

                <div class="box-one-" style="width: 41%;">Fee Type</div> 
                <div class="box-twopp">Duration</div>
                <div class="box-twopp">Amount (&#8377) </div> 
                <div class="box-twopp nnborder">Narrative</div> 

            </div> 

            {assign var=val value=1}
            {assign var=total value=0}
            {section name=sec1 loop=$particular}
               {if {$particular[{$val}].value.2} > 0}
                    {assign var=narrative value={{$particular[{$val}].value.3}|count_characters:true}}

                    <div class="row-b">
                        <div class="box-one-" style="width: 41%;">{if {$particular[{$val}].value.0} != ''}{$particular[{$val}].value.0}{else}&nbsp;{/if}</div> 

                        <div class="box-twopp">{if {$particular[{$val}].value.1} != ''}{$particular[{$val}].value.1}{else}&nbsp;{/if}</div> 
                        <div class="box-twopp">{if {$particular[{$val}].value.2} != ''}{$particular[{$val}].value.2}{else}&nbsp;{/if}</div>  
                        <div class="{if {$narrative}>20} masterTooltip {/if}box-twopp nnborder" {if {$narrative}>20}title="{$particular[{$val}].value.3}"{/if}>{if {$particular[{$val}].value.3} != ''} {if {$narrative}>20} {{$particular[{$val}].value.3}|substr:0:20}.... {else}{$particular[{$val}].value.3} {/if} {else}&nbsp;{/if}</div>  
                    </div>
                {/if}
                {assign var=total value=$total+{$particular[{$val}].value.2}}
                {assign var=val value=$val+1}

            {/section}
            <div class="row-b">
                <div class="box-one-sub" style="font-weight: bold;">{$particularTotal}:</div> 


                <div class="box-total nnborder"  style="font-weight: bold; width: 64%;">&#8377 {$total}</div>  
            </div>

        </div>
    {/if}
           {if ({$tax[{1}].value.0}!='')} 
        <div class="table-1" style=" margin-top:0px;">
            <div class="row-a">

                <div class="box-twopp_">Tax Name</div> 
                <div class="box-twopp">Percentage (%)</div>
                <div class="box-twopp">Applicable (&#8377)  </div> 
                <div class="box-twopp">Amount (&#8377) </div> 
                <div class="box-twopp">Narrative</div>  
            </div> 



            {assign var=val value=1}
            {assign var=total value=0}
            {section name=sec1 loop=$tax}
                {if {$tax[{$val}].value.3} > 0}
                    <div class="row-b">
                        {assign var=narrative value={{$tax[{$val}].value.4}|count_characters:true}}
                        <div class="box-twopp_">{if {$tax[{$val}].value.0} != ''}{$tax[{$val}].value.0}{else}&nbsp;{/if}</div> 
                        <div class="box-twopp">{if {$tax[{$val}].value.1} != ''}{$tax[{$val}].value.1}{else}&nbsp;{/if}</div>
                        <div class="box-twopp">{if {$tax[{$val}].value.2} != ''}{$tax[{$val}].value.2}{else}&nbsp;{/if} </div> 
                        <div class="box-twopp">{if {$tax[{$val}].value.3} != ''}{$tax[{$val}].value.3}{else}&nbsp;{/if}</div> 
                        <div class="{if {$narrative}>20} masterTooltip {/if}box-twopp_ nnborder" {if {$narrative}>20}title="{$tax[{$val}].value.4}"{/if}>{if {$tax[{$val}].value.4} != ''} {if {$narrative}>20} {{$tax[{$val}].value.4}|substr:0:20}.... {else}{$tax[{$val}].value.4} {/if} {else}&nbsp;{/if}</div>  

                    </div> 
                    {assign var=totaltax value=$totaltax+{$tax[{$val}].value.3}}

                    {assign var=val value=$val+1}
                {/if}
            {/section}



            <div class="row-b">

                <div class="box-twopp_ nnborder" style="font-weight: bold;">{$taxTotal}:</div> 
                <div class="box-twopp nnborder"> &nbsp; </div> 
                <div class="box-twopp nnborder">&nbsp; </div> 
                <div class="box-twopp nnborder" style="font-weight: bold;">&#8377 {$totaltax}</div> 
                <div class="box-twopp_ nnborder"></div>  
            </div> 

        </div>
    {/if} 
    
    
       
    
   
</div>

