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
.m-2 {
    margin: 8px !important;
}
.min-h-screen {
    min-height: 100vw !important;
}
.items-center {
    align-items: center !important;
}
.justify-center {
    justify-content: center !important;
}
.drop-shadow {
    --tw-drop-shadow: drop-shadow(0 1px 2px rgb(0 0 0 / 0.1)) drop-shadow(0 1px 1px rgb(0 0 0 / 0.06)) !important;
    filter: var(--tw-blur) var(--tw-brightness) var(--tw-contrast) var(--tw-grayscale) var(--tw-hue-rotate) var(--tw-invert) var(--tw-saturate) var(--tw-sepia) var(--tw-drop-shadow) !important;
}
@media (max-width: 600px) {
    .sm-w-full {
        width: 100% !important;
    }
}
</style></head>
<body style="word-break: break-word; -webkit-font-smoothing: antialiased; margin: 0; width: 100%; padding: 0">
  <div role="article" aria-roledescription="email" aria-label="" lang="en"> <table style="width: 100%" cellpadding="0" cellspacing="0" role="presentation">
                <tr>
                  <td align="center" style="padding-top: 30px; padding-bottom: 30px; background-color: #F7F8F8">
                     <table class="sm-w-full" style="width: 700px" cellpadding="0" cellspacing="0" role="presentation">
                <tr>
                  <td style="border: 1px solid gray; background-color: #fff; padding: 0">  
                          <div style="padding: 40px">        <table style="width: 100%" cellpadding="0" cellspacing="0" role="presentation">
            <tr>
            <td style="margin-top: 8px">
                <section style="display: flex; flex-wrap: wrap">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a9/Amazon_logo.svg/1200px-Amazon_logo.svg.png" style="height: 32px; object-fit: scale-down" alt="">
                </section>
            </td>
               <td style="padding: 5px; vertical-align: top; border-top: 2px solid #2d9560">                        <div style="color: #2d9560; font-size: 30px; font-weight: 500">INVOICE</div>                        <div style="font-size: 14px; color: #000">1042</div>
                        <table cellpadding="0" cellspacing="0" role="presentation">
                            <tr>
                                <td style="border-bottom: 1px solid gray ">
                            <div style="margin-top: 8px;padding-top: 3px;padding-bottom: 3px;"><span style="margin-top: 4px; font-size: 14px; font-weight: 600; color: #000">ISSUE DATE: </span><span style="font-size: 14px; color: #000">May 10,2022</span></div>
                                </td>
                            </tr>
                            <tr>
                                <td style="border-bottom: 1px solid gray ">
                            <div style="padding-top: 3px;padding-bottom: 3px;"><span style="margin-top: 4px; font-size: 14px; font-weight: 600; color: #000">DELIVERY DATE:</span> <span style="font-size: 14px; color: #000">May 10,2022</span></div>
                            </td>
                        </tr>
                        <tr>
                                    <td style="border-bottom: 1px solid gray ">    
                                                            <div style="padding-top: 3px;padding-bottom: 3px;"><span style="margin-top: 4px; font-size: 14px; font-weight: 600; color: #000">DUE DATE:</span> <span style="font-size: 14px; color: #000">June 19,2022</span></div>
                    </td>
                </tr>
                    </table>
                            </td>
                            <td style="padding: 5px; vertical-align: top; border-top: 2px solid #2d9560">                    <div style="margin-top: 8px; margin-bottom: 4px; font-size: 14px; font-weight: 700; color: #000">Supplier</div>
                  <b>Vintage Fashion Store</b> <br>
                    185 Lado Sarai<br>
                   New Delhi<br>
                    110030 <br>
                </td>            </tr>        </table><table style="margin-top: 16px; width: 100%" cellpadding="0" cellspacing="0" role="presentation">
  <tr>
    <td style="border-top: 1px solid gray; border-bottom: 1px solid gray; padding: 4px">  
              <div style="padding-top:5px;padding-bottom:4px; font-size: 14px; font-weight: 700; color: #000">Client</div>
        Acme Pvt. Ltd<br>
       5 Ropewalk Lane, Kala Choda<br>
       Mumbai<br>
       400001<br>       GSTIN:123456789076
    </td>
    <td style="padding: 5px">
    </td>
       <td style="vertical-align: top;border-top: 1px dotted gray; border-bottom: 1px dotted gray; padding: 4px">   
                    <div style="text-align: left;padding-top:2px;"><span style="font-size: 14px; font-weight: 600; color: #000">Payment Method </span> <span style="margin-left: 8px; font-size: 14px; color: #000">Bank Transfer</span></div>
                <div style="text-align: left"><span style="font-size: 14px; font-weight: 600; color: #000">Order Number</span> <span style="margin-left: 8px; font-size: 14px; color: #000">#1042</span></div>                </td>            </tr>
</table>                <div style="margin-top: 16px; width: 100%; overflow-x: auto">
                    <table style="margin-left: auto; margin-right: auto; width: 100%; overflow: hidden; white-space: nowrap" cellpadding="0" cellspacing="0" role="presentation">
                        <thead>
                            <tr style="text-align: left; color: #000">
                                <th style="padding-top: 7px;
                                padding-bottom: 7px; border-bottom: 2px solid #2d9560; text-align: left; font-size: 14px; font-weight: 600; color: #1f2937"> Item </th>
                                <th style="padding-top: 7px;
                                padding-bottom: 7px; border-bottom: 2px solid #2d9560; text-align: left; font-size: 14px; font-weight: 600; color: #1f2937"> Description </th>
                                <th style="padding-top: 7px;
                                padding-bottom: 7px; border-bottom: 2px solid #2d9560; text-align: right; font-size: 14px; font-weight: 600; color: #1f2937"> Quantity </th>
                                <th style="padding-top: 7px;
                                padding-bottom: 7px; border-bottom: 2px solid #2d9560; text-align: right; font-size: 14px; font-weight: 600; color: #1f2937"> Unit Price </th>
                                <th style="padding-top: 7px;
                                padding-bottom: 7px; border-bottom: 2px solid #2d9560; text-align: center; font-size: 14px; font-weight: 600; color: #1f2937"> GST </th>
                                <th style="padding-top: 7px;
                                padding-bottom: 7px; border-bottom: 2px solid #2d9560; text-align: right; font-size: 14px; font-weight: 600; color: #1f2937"> Total </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="padding-top: 7px;padding-bottom: 7px; border-bottom: 1px dotted gray; text-align: left">                                            Converse All Star                                 </td>
                                <td style="padding-top: 7px;padding-bottom: 7px; border-bottom: 1px dotted gray; text-align: left">
                                    Size 9                                 </td>
                                <td style="padding-top: 7px;padding-bottom: 7px; border-bottom: 1px dotted gray; text-align: right">
                                   1                                 </td>
                                <td style="padding-top: 7px;padding-bottom: 7px; border-bottom: 1px dotted gray; text-align: right"> 49.00 </td>
                                <td style="padding-top: 7px;padding-bottom: 7px; border-bottom: 1px dotted gray; text-align: center"> 12.5% </td>
                                <td style="padding-top: 7px;padding-bottom: 7px; border-bottom: 1px dotted gray; text-align: right"> Rs.49 </td>
                            </tr>
                            <tr>
                                <td style="padding-top: 7px;padding-bottom: 7px; border-bottom: 1px dotted gray; background-color: #f3f4f6; text-align: left">                                            Ray-Ban Wayfarer                                 </td>
                                <td style="padding-top: 7px;padding-bottom: 7px; border-bottom: 1px dotted gray; background-color: #f3f4f6; text-align: left">
                                    White                                 </td>
                                <td style="padding-top: 7px;padding-bottom: 7px; border-bottom: 1px dotted gray; background-color: #f3f4f6; text-align: right">
                                   1                                 </td>
                                <td style="padding-top: 7px;padding-bottom: 7px; border-bottom: 1px dotted gray; background-color: #f3f4f6; text-align: right"> 149.00  </td>
                                <td style="padding-top: 7px;padding-bottom: 7px; border-bottom: 1px dotted gray; background-color: #f3f4f6; text-align: center"> 12.5%  </td>
                                <td style="padding-top: 7px;padding-bottom: 7px; border-bottom: 1px dotted gray; background-color: #f3f4f6; text-align: right"> Rs.149 </td>
                            </tr>
                            <tr>
                                <td colspan="3"></td>
                            <td colspan="2" style="padding: 5px; border-bottom: 1px solid #2d9560">
                                <div style="padding-top: 6px; text-transform: uppercase; color: #374151">Total excel. GST</div>
                            </td>
                            <td style="text-align: end; padding: 5px; border-bottom: 1px solid #2d9560">
                                    <div style="font-weight: 600; color: #374151">Rs.198</div>
                            </td>
                            </tr>
                            <tr>
                                <td colspan="3"></td>
                            <td colspan="2" style="padding: 5px; border-bottom: 1px solid #2d9560">
                                <div style="padding-top: 2px; padding-bottom: 2px;font-size: 14px; text-transform: uppercase; color: #374151">GST (VAT)12.5%</div>
                            </td>
                            <td style="text-align: end; padding: 5px; border-bottom: 1px solid #2d9560">
                                    <div style="padding-top: 2px; padding-bottom: 2px;font-size: 14px; font-weight: 600; color: #374151">Rs.24.75</div>
                            </td>
                            </tr>                            <tr>
                                <td colspan="3"></td>
                                <td colspan="2" style="padding: 5px">
                                    <div style="padding-top: 2px; padding-bottom: 2px;font-size: 14px; font-weight: 500; text-transform: uppercase; color: #1f2937">Total incl. GST</div>
                                </td>
                                <td style="text-align: end; padding: 5px">
                                        <div style="padding-top: 2px; padding-bottom: 2px;font-size: 14px; font-weight: 600; color: #1f2937">Rs.222.75</div>
                                </td>
                            </tr>
            <tr>
                <td colspan="3"></td>
                                <td colspan="2" style="padding: 5px; border-bottom: 2px solid #2d9560; border-top: 1px solid #2d9560">
                                    <div style="padding-top: 2px; padding-bottom: 2px;font-size: 14px; font-weight: 500; text-transform: uppercase; color: #1f2937">Amount Due</div>
                                </td>
                                <td style="text-align: end; padding: 5px; background-color: #2d9560; border-bottom: 2px solid #2d9560; border-top: 1px solid #2d9560">
                                        <div style="padding-top: 2px; padding-bottom: 2px;font-size: 14px; font-weight: 600; color: #fff">Rs.222.75</div>
                                </td>
                            </tr>                        </tbody>
                    </table>            </div>        </div>
        <div style="margin-bottom: 16px">
            <table style="width: 100%" cellpadding="0" cellspacing="0" role="presentation">
                <tr>
                    <td colspan="6">
                        <div style="font-size: 13px; color: #6e605d; padding-left: 8px; padding-right: 8px">
                            <br>
                            <b> Please note: No extra charges are applicable for paying online. </b>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="6" style="font-size: 15px; color: #fb735d; line-height: 20px; text-align: right; padding-left: 8px; padding-right: 8px">
                        <a href="#" style="font-family: Arial,Helvetica,sans-serif; font-family: Open Sans,sans-serif; font-size: 13px; background-color: #18aebf; color: #ffffff; padding: 7px 10px 7px 10px; text-decoration: none; float: right" target="_blank">
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
