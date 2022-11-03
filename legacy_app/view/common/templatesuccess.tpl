 {if ({$tax[{1}].column_name.0}!='')} 

                <div class="table-1" style=" margin-top:0px;">
                    <div class="row-a">

                        <div class="box-two_">Tax Name</div> 
                        <div class="box-two">Percentage (%)</div>
                        <div class="box-two">Applicable (&#8377)  </div> 
                        <div class="box-two">Amount (&#8377) </div> 
                        <div class="box-two">Narrative</div>  
                    </div> 



                    {assign var=val value=1}
                    {assign var=total value=0}
                    {section name=sec1 loop=$tax}
                        <div class="row-b">

                            <div class="box-two_">{$tax[{$val}].column_name.0}</div> 
                            <div class="box-twopp">{if {$tax[{$val}].default_column_value.1} !=''}{$tax[{$val}].default_column_value.1} {else}xxxxx{/if}</div>
                            <div class="box-two">xxxxx </div> 
                            <div class="box-two">xxxxx</div> 
                            <div class="box-two nnborder">xxxxx</div>  
                        </div> 
                        {assign var=val value=$val+1}

                    {/section}
                    <div class="row-b">
                        <div class="box-twopp_ nnborder" style="font-weight: bold;">{$taxTotal}:</div> 
                        <div class="box-twopp nnborder"> &nbsp; </div> 
                        <div class="box-twopp nnborder">&nbsp; </div> 
                        <div class="box-twopp nnborder" style="font-weight: bold;">&#8377 xxxxx</div> 
                        <div class="box-twopp_ nnborder"></div>  
                    </div> 



                </div>
            {/if} 
            


            
            <div class="table-1" style="padding:0px; margin-top:14px;">
                <div class="row-a">

                    <div class="box-two_">Final Summary</div> 

                </div> 





                <div class="row-b">

                    <div class="box-two_2">Bill value with taxes<br />xxxxx</div> 
                    <div class="box-two_3">Grand Total<br />xxxxx<br /> <span>(includes convenience fee)</span></div> 
                    <div class=" btnss">  <span class="respond" style="background: #ccc;">Pay Now</span>   </div>


                </div> 


            </div>
            
            
            {if {$supplierlist[0].supplier_company_name} !=''}
    <div class="box-3">
        <p class="blue-big">&nbsp;Supplier details</p>

    </div>
    <div class="table-1" style=" margin-top:0px;">
        <div class="row-a">

            <div class="box-twopp_" style="width:29%">Company name</div> 
            <div class="box-twopp" style="width:28%">Contact name</div>
            <div class="box-twopp" style="width:14%">Mobile </div> 
            <div class="box-twopp" style="width:28%">Email </div> 
        </div> 

        {section name=sec1 loop=$supplierlist}
            <div class="row-b">
                <div class="box-twopp_" style="width:29%">{if {$supplierlist[sec1].supplier_company_name} != ''}{$supplierlist[sec1].supplier_company_name}{else}&nbsp;{/if}</div> 
                <div class="box-twopp" style="width:28%">{if {$supplierlist[sec1].contact_person_name} != ''}{$supplierlist[sec1].contact_person_name}{else}&nbsp;{/if}</div>
                <div class="box-twopp " style="width:14%">{if {$supplierlist[sec1].mobile1} != ''}{$supplierlist[sec1].mobile1}{else}&nbsp;{/if}</div>
                <div class="box-twopp nnborder" style="width:28%">{if {$supplierlist[sec1].email_id1} != ''}{$supplierlist[sec1].email_id1}{else}&nbsp;{/if} </div> 


                {*                        <div class="{if {$narrative}>20} masterTooltip {/if}box-twopp_ nnborder" {if {$narrative}>20}title="{$tax[{$val}].value.4}"{/if}>{if {$tax[{$val}].value.4} != ''} {if {$narrative}>20} {{$tax[{$val}].value.4}|substr:0:20}.... {else}{$tax[{$val}].value.4} {/if} {else}&nbsp;{/if}</div>  *}

            </div> 

        {/section}

    </div>
        {/if}
            <div class=" bottom-1"><p class="gray-small2"> &copy; {$current_year} OPUS Net Pvt. Handmade in Pune.</p> <img src="/images/powerdby.gif" width="216" height="41" /></div>

        </div>
