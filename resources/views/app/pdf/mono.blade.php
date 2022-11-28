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
.flex {
    display: flex !important;
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
                    <div style="padding: 24px">
             <div style="margin-top: 16px; margin-bottom: 8px; text-align: right; font-weight: 700; letter-spacing: 0.1em; color: #000">Invoice 10056</div>
            <div style="border-top: 2px solid rgb(228, 227, 227)"></div>
            <table style="margin-top: 16px; width: 100%" cellpadding="0" cellspacing="0" role="presentation">
              <tr>                <td style=" padding: 2px; vertical-align: top">
                     <div style="font-weight: 700; letter-spacing: 0.1em; color: #9ca3af">Supplier:</div>
                    <div style="margin-top: 4px; font-size: 14px; font-weight: 700">Vintage Fashion Store</div>
                    <span style="font-size: 14px">185 Lado Sarai</span><br>
                    <span style="font-size: 14px">New Delhi</span><br>
                    <span style="font-size: 14px">110030</span></td>
                <td style=" padding: 2px; vertical-align: top">
                    <div style="font-weight: 700; letter-spacing: 0.1em; color: #9ca3af">Client:</div>
                    <span style="margin-top: 4px; font-size: 14px; font-weight: 700">Acme Pvt. Ltd</span><br>
                    <span style="font-size: 14px">5 Ropewalk Lane, Kala Choda </span><br>
                    <span style="font-size: 14px">Mumbai</span><br>
                    <span style="font-size: 14px">400001</span><br>                    <span style="margin-top: 8px; font-size: 14px">GSTIN:123456789076</span>                </td>
            </tr>
            </table>
            <div style="border-top: 1px solid rgb(228, 227, 227); margin-top: 8px"></div>            <table style="margin-top: 16px; width: 100%" cellpadding="0" cellspacing="0" role="presentation">
                <tr>
                <td style=" padding: 2px; vertical-align: top">
                    <table style="width: 100%" cellpadding="0" cellspacing="0" role="presentation">
                        <tr>
                            <td style="  vertical-align: top"><span style="font-size: 14px">Payment Method:</span></td>
                            <td style="padding-left: 5px; vertical-align: top"><span style="font-size: 14px">Bank Transfer</span></td>
                        </tr>
                        <tr>
                            <td style=" vertical-align: top"><span style="font-size: 14px">Order Number:</span></td>
                            <td style="padding-left: 5px; vertical-align: top"><span style="font-size: 14px">#101</span></td>
                        </tr>
                    </table>                </td>                   <td style=" padding: 2px; vertical-align: top">                     <table style="width: 100%" cellpadding="0" cellspacing="0" role="presentation">
                        <tr>
                            <td style=" vertical-align: top"><span style="font-size: 14px">Issue Date:</span></td>
                            <td style=" padding-left: 5px; vertical-align: top"><span style="font-size: 14px">May 10 2022</span></td>                        </tr>
                        <tr>
                            <td style=" vertical-align: top"><span style="font-size: 14px">Delivery Date:</span></td>
                            <td style=" padding-left: 5px; vertical-align: top"><span style="font-size: 14px">May 10 2022</span></td>                        </tr>
                        <tr>                            <td style=" vertical-align: top"><span style="font-size: 14px">Due Date:</span></td>
                            <td style=" padding-left: 5px; vertical-align: top"><b><span style="font-size: 14px">June 10 2022</span></b></td>
                        </tr>
                    </table>                         </td>
            </tr>
            </table>                <div style="margin-top: 24px; width: 100%; overflow-x: auto">
                    <table style="margin-left: auto; margin-right: auto; width: 100%; overflow: hidden" cellpadding="0" cellspacing="0" role="presentation">
                        <thead>
                            <tr style="text-align: left; color: #000">
                                <th style="padding-top: 6px; padding-bottom: 6px;padding-left: 3px;padding-right: 3px; border-bottom: 1px solid gray; text-align: left; font-size: 14px; font-weight: 600"> Item </th>
                                <th style="padding-top: 6px; padding-bottom: 6px;padding-left: 3px;padding-right: 3px; border-bottom: 1px solid gray; text-align: left; font-size: 14px; font-weight: 600"> Description </th>
                                <th style="padding-top: 6px; padding-bottom: 6px;padding-left: 3px;padding-right: 3px; border-bottom: 1px solid gray; text-align: right; font-size: 14px; font-weight: 600"> Quantity </th>
                                <th style="padding-top: 6px; padding-bottom: 6px;padding-left: 3px;padding-right: 3px; border-bottom: 1px solid gray; text-align: right; font-size: 14px; font-weight: 600"> Unit Price </th>
                                <th style="padding-top: 6px; padding-bottom: 6px;padding-left: 3px;padding-right: 3px; border-bottom: 1px solid gray; text-align: center; font-size: 14px; font-weight: 600"> GST </th>
                                <th style="padding-top: 6px; padding-bottom: 6px;padding-left: 3px;padding-right: 3px; border-bottom: 1px solid gray; text-align: right; font-size: 14px; font-weight: 600"> Total </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="padding-top: 6px; padding-bottom: 6px;padding-left: 3px;padding-right: 3px; text-align: left">                                             Converse All Star                                 </td>
                                <td style="padding-top: 6px; padding-bottom: 6px;padding-left: 3px;padding-right: 3px; text-align: left">
                                     Size 9                                 </td>
                                <td style="padding-top: 6px; padding-bottom: 6px;padding-left: 3px;padding-right: 3px; text-align: right">
                                    1                                 </td>
                                <td style="padding-top: 6px; padding-bottom: 6px;padding-left: 3px;padding-right: 3px; text-align: right">  49.00 </td>
                                <td style="padding-top: 6px; padding-bottom: 6px;padding-left: 3px;padding-right: 3px; text-align: center">  12.5% </td>
                                <td style="padding-top: 6px; padding-bottom: 6px;padding-left: 3px;padding-right: 3px; text-align: right">  Rs.49 </td>
                            </tr>
                            <tr>
                                <td style="padding-top: 6px; padding-bottom: 6px;padding-left: 3px;padding-right: 3px; background-color: rgb(236, 236, 236); text-align: left">                                             Ray-Ban Wayfarer                                 </td>
                                <td style="padding-top: 6px; padding-bottom: 6px;padding-left: 3px;padding-right: 3px; background-color: rgb(236, 236, 236); text-align: left">
                                     White                                 </td>
                                <td style="padding-top: 6px; padding-bottom: 6px;padding-left: 3px;padding-right: 3px; background-color: rgb(236, 236, 236); text-align: right">
                                    1                                 </td>
                                <td style="padding-top: 6px; padding-bottom: 6px;padding-left: 3px;padding-right: 3px; background-color: rgb(236, 236, 236); text-align: right">  149.00  </td>
                                <td style="padding-top: 6px; padding-bottom: 6px;padding-left: 3px;padding-right: 3px; background-color: rgb(236, 236, 236); text-align: center">  12.5%  </td>
                                <td style="padding-top: 6px; padding-bottom: 6px;padding-left: 3px;padding-right: 3px; background-color: rgb(236, 236, 236); text-align: right">  Rs.149 </td>
                            </tr>
                            <tr>
                                <td colspan="3"></td>
                            <td colspan="2" style="padding: 4px; border-bottom: 1px solid rgb(236, 236, 236)">
                                <div style="padding-top: 6px;padding-bottom: 3px; color: #374151">Total excel. GST</div>
                            </td>
                            <td style="text-align:right; padding: 4px; border-bottom: 1px solid rgb(236, 236, 236)">
                                    <div style="font-weight: 600; color: #374151">Rs.198</div>
                            </td>
                            </tr>
                            <tr>
                                <td colspan="3"></td>
                            <td colspan="2" style="padding: 4px; border-bottom: 1px solid rgb(236, 236, 236)">
                                <div style="padding-bottom: 3px; padding-top: 3px;font-size: 14px; text-transform: uppercase; color: #374151">GST (VAT)12.5%</div>
                            </td>
                            <td style="text-align:right; padding: 4px; border-bottom: 1px solid rgb(236, 236, 236)">
                                    <div  style="padding-bottom: 3px; padding-top: 3px; font-size: 14px; font-weight: 600; color: #374151">Rs.24.75</div>
                            </td>
                            </tr>                            <tr>
                                <td colspan="3"></td>
                                <td colspan="2" style="padding: 4px">
                                    <div style="padding-bottom: 3px; padding-top: 3px;font-size: 14px; font-weight: 600; color: #111827">Total incl. GST</div>
                                </td>
                                <td style="text-align:right; padding: 4px">
                                        <div style="padding-bottom: 3px; padding-top: 3px;font-size: 14px; font-weight: 600; color: #111827">Rs.222.75</div>
                                </td>
                            </tr>
            <tr>
                <td colspan="3"></td>
                                <td colspan="2" style="border-left: 1px solid gray; border-bottom: 1px solid gray; border-top: 1px solid gray; background-color: rgb(236, 236, 236); padding: 4px">
                                    <div style="padding-bottom: 3px; padding-top: 3px;font-size: 14px; font-weight: 600; color: #111827">Amount Due</div>
                                </td>
                                <td style="border-right: 1px solid gray; border-bottom: 1px solid gray; border-top: 1px solid gray; text-align:right; padding: 5px; background-color: rgb(236, 236, 236)">
                                        <div style="padding-bottom: 3px; padding-top: 3px;font-size: 14px; font-weight: 600; color: #111827">Rs.222.75</div>
                                </td>
                            </tr>                        </tbody>
                    </table>            </div></div>
<div style="margin-bottom: 16px">
    <table style="width: 100%" cellpadding="0" cellspacing="0" role="presentation">
        <tr>
            <td colspan="6">
                <div style="font-size: 16px; color: #6e605d; padding-left: 8px; padding-right: 8px">
                    <br>
                    <b>Thank you for your purchase. </b>
                </div>
            </td>
        </tr>
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
                    </tr>                </tbody></table>
            </td>
        </tr>
       </table>
   </div>
   </td>
   </tr>   </table>     </td>
</tr>
</table>            </div>
</body>
</html>
