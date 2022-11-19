@extends('layouts.admin')
@section('content')
@php $columns=json_decode($det->quotation,1); @endphp
@php $tnc=json_decode($det->tnc,1); @endphp
<div class="panel ">
    <div class="panel-body" style="overflow: auto;">

        <div class="row">
            <div class="col-md-12">

                <!-- BEGIN PAYMENT TRANSACTION TABLE -->


                <div class="portlet-body">
                    <div class="">
                        <table class="table" style="font-size: 12px !important;color: black !important;margin-bottom: 0;">
                            <tbody>
                                <tr>
                                    <td class="td-c" style="border-top: 0px;"><img style="max-width: 180px;" src="/dist/img/1607424021.jpeg" alt="logo" class="logo-default"></td>
                                </tr>
                                <tr>
                                    <td class="td-c text-red" style="border-top: 0px;font-size: 30px;font-family: cambria;color: #ff0000;">{{$company_name}}</td>
                                </tr>
                                <tr>
                                    <td class="td-c" style="font-size: 15px;font-family: cambria;  border-top: 2px solid #4F81BD;">
                                        FLAT NO. 201, GODAVARI APARTMENT, KAILASH NAGAR, BADLAPUR WEST 421503<br>
                                        Contact no 8879391658 Email: contact@siddhivinayaktravelshouse.in
                                    </td>
                                </tr>

                                <tr>
                                    <td style="font-size: 15px;font-family: cambria; ">
                                        To,<br>
                                        <b>{{$det->company_name}}</b>

                                        <br>
                                        <br>
                                        <table class="table table-bordered" style="font-size: 14px; color: black !important;">
                                            <tbody>
                                                <tr style="">
                                                    @foreach ($columns['columns'] as $item)
                                                    <th class="td-c tds" style="background-color: #DBE5F1;color:#17365D;border: 1px solid #4F81BD; padding: 10px !important;">{{$item}}</th>
                                                    @endforeach
                                                </tr>
                                                @foreach ($columns['rows'] as $row)
                                                <tr>
                                                    @foreach ($columns['columns'] as $key=>$item)
                                                    <td class="td-c" style="border: 1px solid #4F81BD;">{{$row[$key]}}</td>
                                                    @endforeach
                                                </tr>
                                                @endforeach

                                            </tbody>
                                        </table>

                                        <br>
                                        <h4>Terms & Condition: -</h4>
                                        <ul>
                                            @foreach ($tnc as $item)
                                            <li>{{$item}}</li>
                                            @endforeach
                                        </ul>
                                        <br>
                                        <p>
                                            For any type of query on given quotation, please be free to contact us. Hope
                                            you will satisfy with our quotation provided and looking forward positive
                                            reply from you.
                                            <br><br>
                                            Thanking You.
                                        </p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>



                <!-- END PAYMENT TRANSACTION TABLE -->

            </div>
        </div>
    </div>
    <!-- /.panel-body -->
</div>
@endsection