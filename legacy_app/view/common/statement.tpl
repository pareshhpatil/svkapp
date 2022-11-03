<div class="invoice-1"> 
{* statement of account starts *}          
      <div class="table-2">
       <div class="rowre-a"  >
           
         Total Amount:    {$money_text}
       </div> 
       </div>
       <div class="table-2" >
       <div class="rowre-a" >
              Statement of account        
       </div> 
  
      <div class="rowre-b">
              Payment request from april 2014 to march 2015
      </div>
      
       <div class="rowre-b">
         <div class="box1-two" >
                Details of receivables
         </div>
         <div class="box2-two" >
                Amount (&#8377)
         </div>
         <div class="box3-two" >
                Details of receipts
         </div>
       </div>    
       
        <div class="rowre-b">
         <div class="boxno-two" >
                
         </div>
        
         <div class="box4-two" >
                Payment date
         </div>
         <div class="box4-two" >
                Payment type
         </div>
         <div class="box5-two" >
                Amount (&#8377)
         </div>
       </div> 
       
           
            {assign var=val value=0}
            {section name=sec1 loop=$societyinfo}
           
       <div class="rowre-b">
         <div class="box1-two" >
              {if {$societyinfo[$val].narrative != ''}}
               {$societyinfo[$val].narrative}
              {else}
               {{$societyinfo[$val].request_date}|date_format:"%e %b %Y"}   
              {/if}
         </div>
         <div class="box2-two" >
               {$societyinfo[$val].request_amt} 
         </div>
         <div class="box4-two" >
              {{$societyinfo[$val].payment_date}|date_format:"%e %b %Y"}
         </div>
         <div class="box4-two" >
               {$societyinfo[$val].payment_mode} 
         </div>
         <div class="box5-two" >
              {$societyinfo[$val].transaction_amt} 
         </div>
       </div>  
                {assign var=totalreq_amt value=$totalreq_amt+{$societyinfo[$val].request_amt}}
                {assign var=totaltrans_amt value=$totaltrans_amt+{$societyinfo[$val].transaction_amt}}
                 {assign var=val value=$val+1}
           {/section}
       
       

       <div class="rowre-b">
         <div class="box1-two" >
               Total receivables 
         </div>
         <div class="box2-two" >
               {$totalreq_amt}
         </div>
         <div class="box6-two" >
              Total transaction amount
         </div>
         <div class="box7-two" >
               {$totaltrans_amt}
         </div>
       </div>  

       <div class="rowre-b">
         <div class="box8-two" >
                Net amount to pay
         </div>
           
         <div class="box7-two" style="font-weight: bold;color: #990000;">
                {$totalreq_amt - $totaltrans_amt}
         </div>
       </div> 
      
      {*<div class="rowre-b" >
           
         Interest @18% will be charged on the payments delayed beyond 30 days
       </div> 
       *}
       {* statement of accounts ends*}
       </div>  