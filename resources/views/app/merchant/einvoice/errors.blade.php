<body data-new-gr-c-s-check-loaded="14.1055.0" data-gr-ext-installed="">
    

    <section class="jumbotron jumbotron-features bg-transparent py-4" id="header">
        <div class="container">
            <div class="row align-items-center">


                <div class="d-none d-lg-block col-12 col-md-12 col-lg-12 col-xl-12">
                    <table style="margin:0 auto; font-family: Open Sans, sans-serif;width: 750px; border: 1px solid grey;" align="center" border="0" cellspacing="0" cellpadding="10">
                        <tbody>
                            <tr style="border-spacing: 0px;">
                                <td class="" style="border-top: 1px solid grey;border-bottom: 1px solid grey;font-size:15px;">
                                    <b>Errors Details </b>
                                </td>
                            </tr>
                            <tr>
                                <td style="font-size:11px;  border-bottom:1px #cbcbcb;">
                                    <table border="0" cellspacing="0" cellpadding="5" style="font-size: 13px;line-height: 15px;width: 100%;">
                                        <tbody>
                                            <tr>
                                                <td style="width: 100%; ">
                                                    <ul>
                                                        @foreach($errors as $v)
                                                        <li>{{$v}}</li>
                                                        @endforeach
                                                    </ul>
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