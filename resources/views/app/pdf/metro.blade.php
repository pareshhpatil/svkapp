<!DOCTYPE html>
<html lang="en" xmlns:v="urn:schemas-microsoft-com:vml">
<head>
  <meta charset="utf-8">
  <meta name="x-apple-disable-message-reformatting">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="format-detection" content="telephone=no, date=no, address=no, email=no">
  <meta name="color-scheme" content="light dark">
  <meta name="supported-color-schemes" content="light dark">
  @php
 if(isset($info['signature']['font_name']))
 {
     $name=$info['signature']['font_name'];
     $fontname='fonts\\'.str_replace(' ', '-', $name).'.ttf';
 }

 @endphp

   <style>
@isset($info['signature']['font_name'])
    @font-face { 
font-family: '{{$info['signature']['font_name']}}';
src: url({{ storage_path($fontname) }}) format("truetype"); 
}
@endisset
@media (max-width: 600px) {
    .sm-w-full {
        width: 100% !important;
    }
}
</style></head>
<body style="word-break: break-word; -webkit-font-smoothing: antialiased; margin: 0; width: 100%; padding: 0">
  <div role="article" aria-roledescription="email" aria-label="" lang="en">    
        <table style="width: 100%" cellpadding="0" cellspacing="0" role="presentation">
                <tr>
                  <td align="center" style="padding-top: 30px; padding-bottom: 30px; background-color: #F7F8F8">         
                       <table class="sm-w-full" style="width: 700px" cellpadding="0" cellspacing="0" role="presentation">
                <tr>
                  <td style="border: 1px solid gray; background-color: #fff; padding: 0">                    <div style="padding: 24px">               <table style="width: 100%" cellpadding="0" cellspacing="0" role="presentation">
                <tr>
            <td width="25%" style="padding: 2px">
                <section style="display: flex; flex-wrap: wrap">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a9/Amazon_logo.svg/1200px-Amazon_logo.svg.png" style="height: 32px; object-fit: scale-down" alt="">
                </section>
            </td>           
             <td style="padding: 2px; vertical-align: top" colspan="2">
                <div style="font-size: 14px; font-weight: 700; text-transform: uppercase">Supplier</div>
                <div style="margin-top: 4px; font-size: 14px; font-weight: 600">Vintage Fashion Store</div>
                <div style="font-size: 14px; font-weight: 600">185 Lado Sarai 185 Lado Sarai 185 Lado Sarai</div>
                <div style="font-size: 14px; font-weight: 600">New Delhi</div>
                <div style="font-size: 14px; font-weight: 600">110030</div>
            </td>
             
                                </tr>    </table>          <div style="border-top: 2px solid #1ba0af; margin-top: 7px; margin-bottom: 7px; width: 100%"></div>          <table style="width: 100%" cellpadding="0" cellspacing="0" role="presentation">
            <tr>
              
                <td style="padding: 2px; vertical-align: top"> 
                    <div style="padding-bottom:4px;font-size: 14px; font-weight: 700; text-transform: uppercase">Client</div>
             
                    <div style="font-size: 12px">Acme Pvt. Ltd</div>
                    <div style="font-size: 12px">5 Ropewalk Lane, Kala Choda </div>
                    <div style="font-size: 12px">Mumbai</div>
                    <div style="font-size: 12px">400001</div>                    <div style="font-size: 12px">GSTIN:123456789076</div>                </td>
                <td style="padding: 2px; vertical-align: top">
                    <div style="font-size: 14px; font-weight: 700; text-transform: uppercase">Invoice</div>
                    <div style="font-size: 14px; font-weight: 700; text-transform: uppercase">Issue Date</div>
                   
                    <div style="font-size: 14px; font-weight: 700; text-transform: uppercase">Due Date</div>  
                      <div style="font-size: 14px; font-weight: 700; text-transform: uppercase">Payment Method</div>
                    <div style="font-size: 14px; font-weight: 700; text-transform: uppercase">Order Number</div> 
                              
                  </td>         
                         <td style="padding: 2px; vertical-align: top">
                            <div style="font-size: 14px">1042</div>
                    <div style="font-size: 14px">May 10, 2022 </div>
                    
                    <div style="font-size: 14px">June 19, 2022</div> 
                        <div style=" font-size: 14px">Bank Transfer</div>
                        <div style= font-size: 14px">#1042</div>                </td>
            </tr>        </table>
        <div style="border-top: 2px solid #1ba0af; margin-top: 7px; margin-bottom: 7px; width: 100%"></div>
                  <div style="margin-top: 24px; width: 100%; overflow-x: auto">
                    <table style="margin-left: auto; margin-right: auto; width: 100%; overflow: hidden; white-space: nowrap" cellpadding="0" cellspacing="0" role="presentation">                        <thead>
                            <tr>
                                <th style="border: 1px solid #1ba0af; background-color: #1ba0af;padding-top: 6px; padding-bottom: 6px;padding-left: 3px;padding-right: 3px; text-align: left; font-size: 14px; font-weight: 600; color: #fff"> Item </th>
                                <th style="border: 1px solid #1ba0af; background-color: #1ba0af;padding-top: 6px; padding-bottom: 6px;padding-left: 3px;padding-right: 3px; text-align: left; font-size: 14px; font-weight: 600; color: #fff"> Description </th>
                                <th style="border: 1px solid #1ba0af; background-color: #1ba0af;padding-top: 6px; padding-bottom: 6px;padding-left: 3px;padding-right: 3px; text-align: center; font-size: 14px; font-weight: 600; color: #fff"> Quantity </th>
                                <th style="border: 1px solid #1ba0af; background-color: #1ba0af;padding-top: 6px; padding-bottom: 6px;padding-left: 3px;padding-right: 3px; text-align: center; font-size: 14px; font-weight: 600; color: #fff"> Unit Price </th>
                                <th style="border: 1px solid #1ba0af; background-color: #1ba0af;padding-top: 6px; padding-bottom: 6px;padding-left: 3px;padding-right: 3px; text-align: center; font-size: 14px; font-weight: 600; color: #fff"> GST </th>
                                <th style="border: 1px solid #1ba0af; background-color: #1ba0af;padding-top: 6px; padding-bottom: 6px;padding-left: 3px;padding-right: 3px; text-align: right; font-size: 14px; font-weight: 600; color: #fff"> Total </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="border: 1px solid #1ba0af; padding: 3px">                                             Converse All Star                                 </td>
                                <td style="border: 1px solid #1ba0af; padding: 3px">
                                     Size 9                                 </td>
                                <td style="border: 1px solid #1ba0af;padding-top: 6px; padding-bottom: 6px;padding-left: 3px;padding-right: 3px; text-align: center">
                                   1                                 </td>
                                <td style="border: 1px solid #1ba0af;padding-top: 6px; padding-bottom: 6px;padding-left: 3px;padding-right: 3px;padding-right: 3px; text-align: center">  49.00 </td>
                                <td style="border: 1px solid #1ba0af;padding-top: 6px; padding-bottom: 6px;padding-left: 3px;padding-right: 3px; text-align: center">  12.5% </td>
                                <td style="border: 1px solid #1ba0af;padding-top: 6px; padding-bottom: 6px;padding-left: 3px;padding-right: 3px; text-align: right">  Rs.49 </td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid #1ba0af; padding: 3px">                                             Ray-Ban Wayfarer                                 </td>
                                <td style="border: 1px solid #1ba0af; padding: 3px">
                                     White                                 </td>
                                <td style="border: 1px solid #1ba0af;padding-top: 6px; padding-bottom: 6px;padding-left: 3px;padding-right: 3px; text-align: center">
                                   1                                 </td>
                                <td style="border: 1px solid #1ba0af;padding-top: 6px; padding-bottom: 6px;padding-left: 3px;padding-right: 3px; text-align: center">  149.00  </td>
                                <td style="border: 1px solid #1ba0af;padding-top: 6px; padding-bottom: 6px;padding-left: 3px;padding-right: 3px; text-align: center">  12.5%  </td>
                                <td style="border: 1px solid #1ba0af;padding-top: 6px; padding-bottom: 6px;padding-left: 3px;padding-right: 3px; text-align: right">  Rs.149 </td>
                            </tr>
                            <tr>
                                <td colspan="3"></td>
                            <td colspan="2" style="padding: 5px; border: 1px solid #1ba0af">
                                <div style=" color: #374151">Total excel. GST</div>
                            </td>
                            <td style="text-align: end; padding: 5px; border: 1px solid #1ba0af">
                                    <div style="color: #374151">Rs.198</div>
                            </td>
                            </tr>
                            <tr>
                                <td colspan="3"></td>
                            <td colspan="2" style="padding: 5px; border: 1px solid #1ba0af">
                                <div style="font-size: 14px; color: #374151">GST (VAT)12.5%</div>
                            </td>
                            <td style="text-align: end; padding: 5px; border: 1px solid #1ba0af">
                                    <div style="font-size: 14px; color: #374151">Rs.24.75</div>
                            </td>
                            </tr>                            <tr>
                                <td colspan="3"></td>
                                <td colspan="2" style="padding: 5px; border: 1px solid #1ba0af">
                                    <div style="font-size: 14px; color: #1f2937">Total incl. GST</div>
                                </td>
                                <td style="text-align: end; padding: 5px; border: 1px solid #1ba0af">
                                        <div style="font-size: 14px; color: #1f2937">Rs.222.75</div>
                                </td>
                            </tr>
            <tr>
                <td colspan="3"></td>
                                <td colspan="2" style="padding: 5px; border: 1px solid #1ba0af; background-color: #1ba0af">
                                    <div style="font-size: 14px; font-weight: 600; text-transform: uppercase; color: #fff">Amount Due</div>
                                </td>
                                <td style="text-align: end; padding: 5px; border: 1px solid #1ba0af; background-color: #1ba0af">
                                        <div style="font-size: 14px; color: #fff">Rs.222.75</div>
                                </td>
                            </tr>                        </tbody>
                    </table>            </div>                </div>        <div style="margin-bottom: 16px">
            <table style="width: 100%" cellpadding="0" cellspacing="0" role="presentation">
                <tr>
                    <td colspan="6">
                        <div style="font-size: 16px; color: #6e605d; padding-left: 8px; padding-right: 8px">
                            <br>
                            <b> Thank you for your puchase.</b>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="6">
                        <div style="font-size: 13px; color: #6e605d; padding-left: 8px; padding-right: 8px">
                            <br>
                            <b> Please note: No extra charges are applicable for paying online. </b>                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="6" style="padding-top: 10px; font-size: 15px; color: #fb735d; line-height: 20px; text-align: right; padding-left: 8px; padding-right: 8px">                        <a href="#" style="font-family: Arial,Helvetica,sans-serif; font-family: Open Sans,sans-serif; font-size: 13px; background-color: #18aebf; color: #ffffff; padding: 7px 10px 7px 10px; text-decoration: none; float: right" target="_blank">
                                 Pay now
                        </a>
                        <a href="#" style="font-family: Arial,Helvetica,sans-serif; font-family: Open Sans,sans-serif; font-size: 13px; background-color: #f99636; color: #ffffff; padding: 7px 10px 7px 10px; text-decoration: none; float: right; margin-right: 10px" target="_blank">
                                    Save as PDF
                        </a>
                    </td>
                </tr>
               <tr>
                <td><br></td>
               </tr>
                <tr>
                    <td colspan="6" style="padding: 5px; color: #5b4d4b; color: #ffffff; line-height: 30px; text-align: right; font-size: 12px; background-color: #18aebf">
                        <span style="float: left">If you would like to collect online payments for your business, <a href="https://www.swipez.in/merchant/register" style="color: #4444ef; text-decoration: underline" target="_blank">register now</a> on Swipez.</span>
                    </td>
                </tr>
                <tr>
                    <td colspan="6" style="font-size: 15px; color: #fb735d; line-height: 30px">
                        <table width="100%" border="0" cellspacing="0" cellpadding="5" role="presentation">
                            <tbody><tr>
                                <td valign="top">
                                    <div style="font-size: 13px; color: #6e605d; padding-left: 15px; padding-right: 15px">
                                        If you are having trouble viewing this invoice in your email, you can use this link to view
                                        the same invoice
                                        <a style="color: #4444ef; text-decoration: underline" href="https://www.swipez.in/patron/paymentrequest/view/NE7FwKNOkpSAb4xSHABi_xzIhGv69dU3kV0" target="_blank" data-saferedirecturl="https://www.google.com/url?q=https://www.swipez.in/patron/paymentrequest/view/NE7FwKNOkpSAb4xSHABi_xzIhGv69dU3kV0&amp;source=gmail&amp;ust=1656045138803000&amp;usg=AOvVaw3ehTZDuYWC4CQLrjHWvTCx">https://www.swipez.in/patron/<wbr>paymentrequest/view/<wbr>NE7FwKNOkpSAb4xSHABi_<wbr>xzIhGv69dU3kV0</a>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td valign="top">
                                    <div style="font-size: 13px; color: #6e605d; padding-left: 15px; padding-right: 15px">
                                        If you do not recognize the merchant - MY COMPANY Pvt. Ltd OR have a query regarding this
                                        request, please <a style="color: #4444ef; text-decoration: underline" href="mailto:support@swipez.in?Subject=Query+regarding+the+Payment+Request" target="_blank"> contact us.</a>
                                    </div>
                                </td>
                            </tr>
                        </tbody></table>
                    </td>
                </tr>
                <tr>
                    <td colspan="6" style="font-size: 13px; line-height: 30px">
                        <table width="100%" border="0" cellspacing="0" cellpadding="5" role="presentation">
                            <tbody><tr>
                                <td valign="top">
                                    <div style="font-size: 13px; padding-left: 15px; padding-right: 15px">
                                    If you would prefer not receiving our emails, please <a style="color: #4444ef; text-decoration: underline" href="https://www.swipez.in/unsubscribe/select/NE7FwKNOkpSAb4xSHABi_xzIhGv69dU3kV0" target="_blank" data-saferedirecturl="https://www.google.com/url?q=https://www.swipez.in/unsubscribe/select/NE7FwKNOkpSAb4xSHABi_xzIhGv69dU3kV0&amp;source=gmail&amp;ust=1656045138803000&amp;usg=AOvVaw1Q7ifpd7k51xjj3pzNusiw">click here</a> to unsubscribe.
                            </div>
                            </td>
                            </tr>                        </tbody></table>
                    </td>
                </tr>
               </table>
           </div>
           </td>
           </tr>           </table>             </td>
    </tr>
    </table>            </div>
</body>
</html>
