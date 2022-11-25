<table  style="margin:0 auto; font-family:Verdana, Arial;width: 600px; border: 1px solid black;" align="center" width="600" border="0" cellspacing="0" cellpadding="10" >
    
    <tr style="border-spacing: 0px;">
        <td style="text-align: center;background-color: #E5E5E5 ;font-size:18px;" >
            <b>High value payments feed</b>
        </td>
    </tr>
    <tr >
        <td>
            <div>
                <table width="100%" border="1" cellspacing="0" cellpadding="5" style="border-collapse: collapse;color:#5b4d4b;font-size: 13px;line-height: 15px;">
                    <thead>
                        <tr style="background-color: #E5E5E5 ;">
                            <th style="width:20px;">Merchant Id</th>
                            <th style="width:200px;">Company name</th>
                            <th style="width:100px;">Amount</th>
                            <th style="width:60px;">Transaction date</th>
                            <th style="width:60px;">Customer email</th>
                            <th style="width:100px;">Customer mobile</th>
                            <th style="width:150px;">Mode of payment</th>
                            <th style="width:150px;">Registered on</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transaction_details as $key=>$row)
                            <tr> 
                                <td>
                                    {{$row->merchant_id}}
                                </td>
                                <td class="col-md-5">
                                    {{$row->company_name}}
                                </td>
                                <td>
                                    {{$row->amount}}
                                </td>
                                <td>
                                    {{$row->created_date}}
                                </td>
                                <td>
                                    {{$row->email}}
                                </td>
                                <td>
                                    {{$row->mobile}}
                                </td>
                                <td>
                                    {{$row->payment_mode}}
                                </td>
                                <td>
                                   {{$row->registered_on}}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </td>
    </tr>
</table>