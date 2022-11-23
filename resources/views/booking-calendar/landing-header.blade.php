<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pay your Bill | MY COMPANY Pvt. Ltd</title>
    <meta name="description" content="MY COMPANY Pvt. Ltd customers can search for pending bill by entering their mail id or mobile number.">

    <link href='https://fonts.googleapis.com/css?family=Rubik' rel='stylesheet'>
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link href="https://www.swipez.in/css/toastr.css" rel="stylesheet">
    <style>
        body {
            padding: 15px 0;
            font-family: Roboto;
            background-color: #f8f8f8;
        }

        .num-block {
            float: left;
            width: 100%;
            padding: 15px 30px;
        }

        /* skin 1 */
        .skin-1 .num-in {
            float: left;
            width: 94px;
        }

        .skin-1 .num-in span {
            display: block;
            float: left;
            width: 30px;
            height: 32px;
            line-height: 32px;
            text-align: center;
            position: relative;
            cursor: pointer;
        }

        .skin-1 .num-in span.dis:before {
            background-color: #ccc !important;
        }

        .skin-1 .num-in input {
            float: left;
            width: 32px;
            height: 32px;
            border: 1px solid #6E6F7A;
            border-radius: 5px;
            color: #000;
            text-align: center;
            padding: 0;
        }

        .skin-1 .num-in span.minus:before {
            content: '';
            position: absolute;
            width: 15px;
            height: 2px;
            background-color: #000;
            top: 50%;
            left: 0;
        }

        .skin-1 .num-in span.plus:before,
        .skin-1 .num-in span.plus:after {
            content: '';
            position: absolute;
            right: 0px;
            width: 15px;
            height: 2px;
            background-color: #000;
            top: 50%;
        }

        .skin-1 .num-in span.plus:after {
            -webkit-transform: rotate(90deg);
            -ms-transform: rotate(90deg);
            -o-transform: rotate(90deg);
            transform: rotate(90deg);
        }

        .portlet {
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 10%), 0 1px 2px 0 rgb(0 0 0 / 6%);
            border-radius: 0.5rem;
        }

        .portlet {
            margin-top: 0;
            margin-bottom: 25px;
            padding: 0;
            -webkit-border-radius: 4px;
            -moz-border-radius: 4px;
            -ms-border-radius: 4px;
            -o-border-radius: 4px;
            border-radius: 4px;
        }

        .portlet>.portlet-body {
            padding: 1rem;
            background-color: #ffffff;
            border-radius: 0.5rem;
        }

        .portlet>.portlet-body {
            clear: both;
            -webkit-border-radius: 0 0 4px 4px;
            -moz-border-radius: 0 0 4px 4px;
            -ms-border-radius: 0 0 4px 4px;
            -o-border-radius: 0 0 4px 4px;
            border-radius: 0 0 4px 4px;
        }

        .lable-heading {
            font-style: normal;
            font-weight: 400;
            font-size: 18px;
            line-height: 24px;
            color: #000000;
        }

        .lable-sub-heading {
            font-style: normal;
            font-weight: 400;
            font-size: 14px;
            line-height: 24px;
            color: #A0ACAC;
        }

        .lable-sub-heading2 {
            font-style: normal;
            font-weight: 400;
            font-size: 14px;
            line-height: 24px;
            color: #000;
        }

        .card-lable-heading {
            font-style: normal;
            font-weight: 400;
            font-size: 20px;
            line-height: 24px;
            color: #5C6B6B;
        }

        .card-lable-text {
            font-style: normal;
            font-weight: 400;
            font-size: 16px;
            line-height: 19px;
            color: #859494;
        }

        .back-button {
            color: #6F8181;
            font-size: 0.9rem;
        }

        /* / skin 1 */
    </style>
</head>

<body>