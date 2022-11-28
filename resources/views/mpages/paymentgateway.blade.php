<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <!-- Fonts -->
   <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;600&display=swap" rel="stylesheet">
   <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
   <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
   <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
   <link href="{{asset('css/toastr.css')}}" rel="stylesheet">
   <style>
      /* The Modal (background) */
      .modal {
         display: none;
         /* Hidden by default */
         position: fixed;
         /* Stay in place */
         z-index: 1;
         /* Sit on top */
         left: 0;
         top: 0;
         width: 100%;
         /* Full width */
         height: 100%;
         /* Full height */
         overflow: auto;
         /* Enable scroll if needed */
         background-color: rgb(0, 0, 0);
         /* Fallback color */
         background-color: rgba(0, 0, 0, 0.4);
         /* Black w/ opacity */
      }

      /* Modal Content/Box */
      .modal-content {
         background-color: #fefefe;
         margin: 5% auto;
         /* 15% from the top and centered */
         padding: 20px;
         border: 1px solid #888;
      }

      /* The Close Button */
      .close {
         color: #aaa;
         float: right;
         font-size: 28px;
         font-weight: bold;
      }

      .close:hover,
      .close:focus {
         color: black;
         text-decoration: none;
         cursor: pointer;
      }

      .timer-span {
         margin-top: 8px;
      }

      .text-success {
         color: #4ea849 !important;
      }

      .hmega {
         font-size: 32px;
         font-weight: 400;
      }

      .h5.light {
         font-weight: 400;
         color: #999;
      }

      .h5 {
         font-size: 12px;
         font-weight: 300;
         color: #434343;
      }

      .bold {
         font-weight: 400 !important;
      }

      .vector-image {
         border-radius: 8px;
         float: right;
         padding: 20px;
      }

      .pay-image {
         border-radius: 8px;
         float: left;
         padding: 10px;
      }

      .pay-button {
         background: #FFFFFF;
         border: 0.5px solid #5C6B6B;
         border-radius: 8px;
         height: 70px;
         margin-top: 10px;
         margin-bottom: 10px;
      }

      .pay-text-style {
         font-style: normal;
         font-weight: 400;
         font-size: 20px;
         line-height: 36px;
         color: #5C6B6B;
         padding: 10px;
         margin-left: 57px;
         position: absolute;
      }

      .heading-company-name {
         float: left;
         font-style: normal;
         font-weight: 500;
         font-size: 20px;
         line-height: 36px;
         color: #5C6B6B;
      }

      .heading-pay-amount {
         float: right;
         font-style: normal;
         font-weight: 500;
         font-size: 20px;
         line-height: 36px;
         color: #394242;
      }

      .heading-company-text {
         float: left;
         font-style: normal;
         font-weight: 400;
         font-size: 15px;
         line-height: 14px;
         color: #A0ACAC;
      }

      .heading-pay-text {
         float: right;
         font-style: normal;
         font-weight: 400;
         font-size: 15px;
         line-height: 14px;
         color: #A0ACAC;
      }
   </style>
</head>

<body>
   <section class="text-gray-600 body-font pt-10 relative gray-bg-1">
      <div class="grid justify-items-center items-center">
         <div class="max-w-sm rounded overflow-hidden shadow-lg">
            <div class="px-6 py-1">
               <div class="flex justify-between">
                  <div class="font-bold text-xl mb-2 heading-company-name ">{{ucwords($merchant->company_name)}}</div>
                  <div class="font-bold text-xl mb-2 heading-pay-amount">
                     ₹ {{$amount}}
                  </div>
               </div>
            </div>
            <div class="px-6 py-1">
               <div class="flex justify-between">
                  <div class="text-l mb-2 heading-company-text">COMPANY NAME</div>
                  <div style="width: 120px;"></div>
                  <div class="text-l mb-2 heading-pay-text">AMOUNT</div>
               </div>
            </div>
            <div class="px-6 pt-4 pb-2" style="margin-top: 10px;">
               <a href="#">
                  <div class="pay-button" id="myBtn" @if( $upi_fee_id !='' ) onclick="callSetu()" @else onclick="callPg('{{$cashfree_fee_id}}')" @endif>
                     <img src="/assets/admin/layout/img/image_15.svg" alt="image11" class="pay-image">
                     <div class="font-bold pay-text-style">UPI
                     </div>
                     <img src="/assets/admin/layout/img/arrow-right.svg" alt="Vector" class="vector-image">
                  </div>
               </a>
               <a href="#">
                  <div class="pay-button" onclick="callPg('{{$cashfree_fee_id}}')">
                     <img src="/assets/admin/layout/img/image_17.svg" alt="image11" class="pay-image">
                     <div class="pay-text-style font-bold">Cards</div>
                     <img src="/assets/admin/layout/img/arrow-right.svg" class="vector-image" alt="Vector">
                  </div>
               </a>
               <a href="#">
                  <div class="pay-button" onclick="callPg('{{$cashfree_fee_id}}')">
                     <img src="/assets/admin/layout/img/image_16.svg" alt="image11" class="pay-image">
                     <div class="pay-text-style font-bold">Wallet</div>
                     <img src="/assets/admin/layout/img/arrow-right.svg" alt="Vector" class="vector-image">
                  </div>
               </a>
               <a href="#">
                  <div class="pay-button" onclick="callPg('{{$cashfree_fee_id}}')">
                     <img src="/assets/admin/layout/img/image_18.svg" alt="image11" class="pay-image">
                     <div class="pay-text-style font-bold">Net Banking</div>
                     <img src="/assets/admin/layout/img/arrow-right.svg" alt="Vector" class="vector-image">
                  </div>
               </a>
               @if($is_paytm == true)
               <a href="#">
                  <div class="pay-button" onclick="callPg('{{$paytm_fee_id}}')">
                     <img src="/assets/admin/layout/img/image_16.svg" alt="image11" class="pay-image">
                     <div class="pay-text-style font-bold">Paytm</div>
                     <img src="/assets/admin/layout/img/arrow-right.svg" alt="Vector" class="vector-image">
                  </div>
               </a>
               @endif
            </div>
         </div>
      </div>
   </section>

   <!-- The Modal -->
   <div id="myModal" class="modal">
      <div class="modal-content sm:w-4/5 xs:w-4/5 lg:w-2/5">
         <span class="close" onclick="closeModal()">&times;</span>
         <div class="text-center">
            <h2 class=" text-xl">Scan the QR code using your UPI apps</h2>
            <img class="inline w-3/5" src="https://i.ibb.co/SxSNTxd/0-BIy-Cbl-CTVo-Ol5-Zg-removebg-preview-removebg-preview.png">
            <span class="block hmega text-success timer-span" id="upi_timer"></span>
            <img class="inline" src="/assets/admin/layout/img/Spinner.svg" alt="qr code" id="qr_image_src" class="qrcode-img" style="width: 220px;">
            <p class="h5 bold light">Do not press the back button until the payment is complete</p>
         </div>
      </div>
   </div>

   <form action="{{$request_post_url}}" method="POST" id="form-id">
      @csrf
      @foreach($post as $name=>$value)
      @if(!is_array($value))
      <input type="hidden" name="{{$name}}" value="{{$value}}" id="{{$name}}">
      @else
      @foreach($value as $name1=>$value1)
      <input type="hidden" name="{{$name}}[]" value="{{$value1}}" id="{{$name}}">
      @endforeach
      @endif
      @endforeach

      <input type="hidden" name="payment_mode" id="payment_mode" value="{{$cashfree_fee_id}}">
      <input type="hidden" name="platform_bill_id" id="platform_bill_id" value="">
      <input type="hidden" name="order_hash" value="" id="order_hash">
   </form>

   <form action="" method="POST" id="payment-response">
      @csrf
      <input type="hidden" name="orderId" value="" id="orderId">
      <input type="hidden" name="orderAmount" value="{{$amount}}" id="orderAmount">
      <input type="hidden" name="txStatus" value="" id="txStatus">
      <input type="hidden" name="referenceId" value="" id="referenceId">
      <input type="hidden" name="paymentMode" value="UPI">
      <input type="hidden" name="txMsg" value="" id="txMsg">
      <input type="hidden" name="txTime" value="" id="txTime">
      <input type="hidden" name="signature" value="">
   </form>

   <script>
      function callPg(fee_id = null) {
         document.getElementById("payment_mode").value = fee_id;
         document.getElementById('form-id').submit();
      }

      function callSetu() {
         var fee_id = "{{$upi_fee_id}}";
         var cashfree_fee_id = "{{$cashfree_fee_id}}";
         var paytm_fee_id = "{{$paytm_fee_id}}";
         if (fee_id != '') {
            document.getElementById("payment_mode").value = fee_id;

            var base_url = "{{ env('SWIPEZ_BASE_URL') }}";
            var url = "{{$url}}";
            var post_url = "{{$request_post_url}}";
            var data = $("#form-id").serialize();

            $.ajax({
               type: 'POST',
               url: post_url,
               data: data,
               success: function(data) {
                  try {
                     data = JSON.parse(data);
                     var upi_url = data.upiLink;
                     if (/ipad|tablet/i.test(navigator.userAgent)) {
                        window.open(upi_url, '_blank').focus();
                     } else if (/mobile/i.test(navigator.userAgent)) {
                        window.open(upi_url, '_blank').focus();
                     } else {
                        document.getElementById("qr_image_src").src = data.img_src;
                     }
                     document.getElementById("platform_bill_id").value = data.platform_bill_id
                     document.getElementById("referenceId").value = data.platform_bill_id
                     document.getElementById("order_hash").value = data.hash
                     var data2 = $("#form-id").serialize();

                     timerID = setInterval(function() {
                        $.ajax({
                           type: 'POST',
                           url: base_url + 'upipgtrack',
                           data: data2,
                           success: function(dback) {
                              try {
                                 dback = JSON.parse(dback);
                                 if (dback.status != 'PENDING') {
                                    document.getElementById("orderId").value = dback.order_hash
                                    document.getElementById("payment-response").action = dback.req_url
                                    document.getElementById('payment-response').submit();
                                 }
                              } catch (e) {
                                 console.log(e);
                              }
                           }
                        });
                     }, 2 * 1000);
                  } catch (e) {
                     console.log(e);
                     document.getElementById("payment-response").action = base_url + 'xway/seturesponse';
                     document.getElementById('payment-response').submit();
                  }
               }
            });

            var d = new Date();
            var countDownDate = new Date(d.getTime() + 5 * 60000).getTime();
            x = setInterval(function() {
               var now = new Date().getTime();
               var distance = countDownDate - now;
               var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
               var seconds = Math.floor((distance % (1000 * 60)) / 1000);
               document.getElementById("upi_timer").innerHTML = minutes + "m " + seconds + "s ";
               if (distance < 0) {
                  clearInterval(x);
                  var modal = document.getElementById("myModal");
                  modal.style.display = "none";
                  document.getElementById("payment-response").action = base_url + 'xway/seturesponse';
                  document.getElementById('payment-response').submit();
               }
            }, 1000)

            var modal = document.getElementById("myModal");
            var btn = document.getElementById("myBtn");
            var span = document.getElementsByClassName("close")[0];
            modal.style.display = "block";
         } else {
            if (cashfree_fee_id != '') {
               document.getElementById("payment_mode").value = cashfree_fee_id;
               document.getElementById('form-id').submit();
            } else {
               document.getElementById("payment_mode").value = paytm_fee_id;
               document.getElementById('form-id').submit();
            }
         }

      }

      function closeModal() {
         var modal = document.getElementById("myModal");
         modal.style.display = "none";
         clearInterval(x);
         clearInterval(timerID);
      }
   </script>
</body>

</html>