<div id="guest_pop" style="z-index: 1000; position: fixed;">

    <div class="guest-pop" style="margin-top: 100px; border: 12px solid; border-radius: 15px; border-color: #595959;padding:0px">

        <div class="patron_details" style="width: 700px; float: left; ">
            <form name="create_supplier" action="" method="post">
                <div class="right" style="width: 712px; float: left;"><h1>Select supplier</h1><a href="#supplier"> <img src="/images/close.gif" style="margin-left:580px;cursor: pointer;margin-top :00px;" onclick = "document.getElementById('guest_pop').style.display = 'none'" width="29" height="26"  class="close" /></a>
                </div> 

                {if {$supplier[0].supplier_company_name} !=''}
                    <div class="last-row-details" style="width: 700px;float: left;">
                        <div class="box-one-n" style="width: 25%;"><strong>Supplier company name</strong></div>
                        <div class="box-one-n" style="width: 20%;"><strong>Contact person name</strong></div>
                        <div class="box-one-n" style="width: 20%;"><strong>Mobile</strong></div>
                        <div class="box-one-n" style="width: 20%;"><strong>Industry type</strong></div>
                        <div class="box-one-n" style="width: 10%;"><strong>Select</strong></div></div>
                        {assign var="sup" value="{$supplierid}"|@explode:","}
 <div style="width: 700px; float: left; max-height: 290px; overflow-y: auto; overflow-x: hidden;">

                    {section name=sec1 loop=$supplier}
                        {assign var=check value=''}
                            <div class="last-row-details-two"  style="width: 700px;float: left;">
                                <div class="box-two-n" id="spname{$supplier[sec1].supplier_id}" style="width: 25%;">{$supplier[sec1].supplier_company_name}</div>
                                <div class="box-two-n" id="spcontact{$supplier[sec1].supplier_id}" style="width: 20%;">{$supplier[sec1].contact_person_name}</div>
                                <div class="box-two-n" id="spmobile{$supplier[sec1].supplier_id}" style="width: 20%;">{$supplier[sec1].mobile1}</div>
                                <div class="box-two-n" id="sptype{$supplier[sec1].supplier_id}" style="width: 20%;">{$supplier[sec1].config_value}</div>
                                <div class="box-two-n" id="spemail{$supplier[sec1].supplier_id}" style="display: none;">{$supplier[sec1].email_id1}</div>
                                <div class="box-six-n" style="width: 10%;">    <input type="checkbox" {$check}  value="{$supplier[sec1].supplier_id}" id="spid{$supplier[sec1].supplier_id}" onchange="AddsupplierRow(this.value);"></input>  </div>
                            </div>


                        
                    {/section}       
                {else}
                    <br><br><br><div align="center"><h5 >No records found</h1></div>
                {/if}
</div>

            </form>
        </div>
        <div class="right" style="background-color:#FFFFFF;width: 712px; float: left; margin-top: 10px;" ><a href="#supplier" onclick = "document.getElementById('guest_pop').style.display = 'none'" class="search1" style="margin-right:40px;float: right;" />Done</a> </div>

    </div>

</div>
{section name=sec1 loop=$supplierid}      
    <script>
                    document.getElementById("spid" + {$supplierid[sec1]} ).checked = true;
    </script>     
{/section}     