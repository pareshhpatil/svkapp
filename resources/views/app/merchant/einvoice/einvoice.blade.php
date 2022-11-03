<body data-new-gr-c-s-check-loaded="14.1055.0" data-gr-ext-installed="">
    <style>
        html {
            margin: 0px;
            margin-top: 10px;
            margin-bottom: 10px;
        }

        body {
            font-family: Open Sans, sans-serif;
        }

        .form-control-plaintext {
            color: #5b4d4b;
            font-size: 13px;
            line-height: 14px;
            height: auto;
            padding: 0;
        }

        .tx-c {
            text-align: center;
        }

        .tx-r {
            text-align: right;
        }

        .bgset {
            background-color: #5b5b5b;
            color: white;
        }

        .bgset2 {
            background-color: #5b5b5b;
            color: white;
        }
    </style>

    <section class="jumbotron jumbotron-features bg-transparent py-4" id="header">
        <div class="container">
            <div class="row align-items-center">


                <div class="d-none d-lg-block col-12 col-md-12 col-lg-12 col-xl-12">
                    <table style="margin:0 auto; font-family: Open Sans, sans-serif;width: 750px; border: 1px solid grey;" align="center" border="0" cellspacing="0" cellpadding="10">
                        <tbody>
                            <tr>
                                <td style="font-size:15px; line-height:30px;">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="5">
                                        <tbody>
                                            <tr>
                                                <td width="250" align="center" valign="middle">
                                                    <h2>{{$data['SellerDtls']['Gstin']}} <br>{{$data['SellerDtls']['LglNm']}}</h2>
                                                </td>
                                                <td width="170"></td>
                                                <td width="300" align="right" valign="top">
                                                    <img src="https://chart.googleapis.com/chart?chs=240x230&cht=qr&chl={{$info->qr_code}}&choe=UTF-8" />
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr style="border-spacing: 0px;">
                                <td class="" style="border-top: 1px solid grey;border-bottom: 1px solid grey;font-size:15px;">
                                    <b>1. e-Invoice Details </b>
                                </td>
                            </tr>
                            <tr>
                                <td style="font-size:11px;  border-bottom:1px #cbcbcb;">
                                    <table border="0" cellspacing="0" cellpadding="5" style="font-size: 13px;line-height: 15px;width: 100%;">
                                        <tbody>
                                            <tr>
                                                <td colspan="2" style="width: 100%; ">
                                                    <b>IRN: </b>{{$info->irn}}
                                                </td>

                                            </tr>
                                            <tr>
                                                <td style="  width: 50%; ">
                                                    <b>Ack. No: {{$info->ack_no}}</b>
                                                </td>
                                                <td style="  width: 50%; ">
                                                    <b>Ack. Date: </b><x-localize :date="$info->ack_date" type="date" />
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                </td>
                            </tr>
                            <tr style="border-spacing: 0px;">
                                <td class="" style="border-top: 1px solid grey;border-bottom: 1px solid grey;font-size:15px;">
                                    <b>2. Transaction Details </b>
                                </td>
                            </tr>
                            <tr>
                                <td style="font-size:11px;  border-bottom:1px #cbcbcb;">
                                    <table border="0" cellspacing="0" cellpadding="5" style="font-size: 13px;line-height: 15px;width: 100%;">
                                        <tbody>
                                            <tr>
                                                <td style="  width: 50%; ">
                                                    <b>Category: </b>{{$data['TranDtls']['SupTyp']}}
                                                </td>
                                                <td style="  width: 50%; ">
                                                    <b>Document No: </b>{{$data['DocDtls']['No']}}
                                                </td>

                                            </tr>
                                            <tr>
                                                <td style="  width: 50%; ">
                                                    <b>Document Type: </b>{{$data['DocDtls']['Typ']}}
                                                </td>
                                                <td style="  width: 50%; ">
                                                    <b>Document Date: </b>{{$data['DocDtls']['Dt']}}
                                                </td>

                                            </tr>
                                        </tbody>
                                    </table>

                                </td>
                            </tr>

                            <tr style="border-spacing: 0px;">
                                <td class="" style="border-top: 1px solid grey;border-bottom: 1px solid grey;font-size:15px;">
                                    <b>3. Party Details </b>
                                </td>
                            </tr>
                            <tr>
                                <td style="font-size:11px;  border-bottom:1px #cbcbcb;">
                                    <table  valign="top"  border="0" cellspacing="0" cellpadding="5" style="font-size: 13px;line-height: 12px;width: 100%;">
                                        <tbody>
                                            <tr>
                                                <td  valign="top" style="  border-bottom:1px #cbcbcb;width: 50%; vertical-align: top;">
                                                    <b style="font-size: 14px;">Seller</b>
                                                    <p>Name: {{$data['SellerDtls']['LglNm']}}</p>
                                                    <p>GSTIN: {{$data['SellerDtls']['Gstin']}}</p>
                                                    <p>Address: {{$data['SellerDtls']['Addr1']}} {{$data['SellerDtls']['Loc']}} {{$data['SellerDtls']['Pin']}}</p>
                                                </td>
                                                <td  valign="top" style="  border-bottom:1px #cbcbcb;width: 50%; vertical-align: top;">
                                                <b style="font-size: 14px;">Purchaser</b>
                                                    <p>Name: {{$data['BuyerDtls']['LglNm']}}</p>
                                                    <p>GSTIN: {{$data['BuyerDtls']['Gstin']}}</p>
                                                    <p>Address: {{$data['BuyerDtls']['Addr1']}} {{$data['BuyerDtls']['Loc']}} {{$data['BuyerDtls']['Pin']}}</p>
                                                </td>

                                            </tr>

                                        </tbody>
                                    </table>

                                </td>
                            </tr>
                            <tr style="border-spacing: 0px;">
                                <td class="" style="border-top: 1px solid grey;border-bottom: 1px solid grey;font-size:15px;">
                                    <b>4. Goods Details </b>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <table width="100%" border="0" cellspacing="0" cellpadding="5" style="border: 1px solid #cbcbcb;border-bottom: 0;color:#5b4d4b;font-size: 13px;line-height: 15px;text-align: center;">
                                        <thead>
                                            <tr>
                                                <th style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;width:5%">
                                                    #
                                                </th>
                                                <th colspan="2" style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb; min-width: 110px;">
                                                    Description
                                                </th>
                                                <th style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;min-width: 70px;">
                                                    HSN/SAC code
                                                </th>
                                                <th style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;">
                                                    Quantity
                                                </th>
                                                <th style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;min-width: 70px;">
                                                    Unit
                                                </th>
                                                <th style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;">
                                                    Rate
                                                </th>
                                                <th style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;">
                                                    Discount
                                                </th>
                                                <th style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;">
                                                    Taxable Amount
                                                </th>
                                                <th style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;">
                                                    GST
                                                </th>

                                                <th style="border-bottom: 1px solid #cbcbcb; text-align: center;">
                                                    Total Amount
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($data['ItemList'] as $particular)
                                            <tr>
                                                <td style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;text-align: center;">
                                                    {{$particular['SlNo']}}
                                                </td>
                                                <td colspan="2" style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb; widtd: 35%;">
                                                    {{$particular['PrdDesc']}}
                                                </td>
                                                <td style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;">
                                                    {{$particular['HsnCd']}}
                                                </td>
                                                <td style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;">
                                                    {{$particular['Qty']}}
                                                </td>
                                                <td style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;">
                                                    {{$particular['Unit']}}
                                                </td>
                                                <td style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;">
                                                    {{$particular['UnitPrice']}}
                                                </td>
                                                <td style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;">
                                                    {{$particular['Discount']}}
                                                </td>
                                                <td style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;">
                                                    {{$particular['AssAmt']}}
                                                </td>
                                                <td style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;">
                                                    {{$particular['GstRt']}}
                                                </td>
                                                <td style="border-bottom: 1px solid #cbcbcb;text-align: right;">
                                                    {{$particular['TotItemVal']}}
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                            </tr>

                            <tr>
                                <td>

                                    <table width="100%" border="0" cellspacing="0" cellpadding="5" style="border: 1px solid #cbcbcb;border-bottom: 0;color:#5b4d4b;font-size: 13px;line-height: 15px;text-align: center;">
                                        <thead>
                                            <tr>
                                                <th style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;">
                                                    Taxable Amt
                                                </th>
                                                <th colspan="2" style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;min-width: 70px;">
                                                    CGST Amt
                                                </th>
                                                <th style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;min-width: 70px;">
                                                    SGST Amt
                                                </th>
                                                <th style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;">
                                                    IGST Amt
                                                </th>
                                                <th style="border-bottom: 1px solid #cbcbcb;min-width: 70px;">
                                                    Total Invoice Amt
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;text-align: center;">
                                                    {{$data['ValDtls']['AssVal']}}
                                                </td>
                                                <td colspan="2" style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb; ">
                                                    {{$data['ValDtls']['CgstVal']}}
                                                </td>
                                                <td style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;">
                                                    {{$data['ValDtls']['SgstVal']}}
                                                </td>
                                                <td style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;">
                                                    {{$data['ValDtls']['IgstVal']}}
                                                </td>
                                                <td style="border-bottom: 1px solid #cbcbcb;">
                                                    {{$data['ValDtls']['TotInvVal']}}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>



                        </tbody>
                    </table>

                </div>


            </div>
        </div>
    </section>








    <script>
        //print();
    </script>
</body>