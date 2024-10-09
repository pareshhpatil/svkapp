<div class="">
    <div class="card-body">
        @if(session()->has('success'))
        <div class="alert alert-success d-flex align-items-center" role="alert">
            <span class="alert-icon text-success me-2">
                <i class="ti ti-check ti-xs"></i>
            </span>
            {{ session()->get('success') }}
        </div>
        @endif
        <form action="/staff/payment/paymentsave" onsubmit="lod(true)" method="post" id="frm-book">
            @csrf

            <div class="form-group basic">
                <div class="input-wrapper">
                    <label class="label mb-1" for="email1">Acc name - {{$detail->account_holder_name}}</label>
                </div>
            </div>
            <div class="form-group basic">
                <div class="input-wrapper">
                    <label class="label mb-1" for="email1">Account - {{$detail->account_no}}</label>
                </div>
            </div>

            <div class="form-group basic">
                <div class="input-wrapper">
                    <label class="label mb-1" for="email2">Payment from</label>
                    <select class="form-control select2" v-model="source_id" required name="source_id" style="padding: 0 40px 0 16px;" data-placeholder="Select..">
                        <option value="">Select account</option>
                        </option>
                        @foreach($paymentsource as $v)
                        <option value="{{$v->paymentsource_id}}">{{$v->name}}</option>
                        @endforeach

                    </select>
                    <i class="clear-input">
                        <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                    </i>
                </div>
            </div>
            <div class="form-group basic">
                <div class="input-wrapper">
                    <label class="label mb-1" for="email2">Payment mode</label>
                    <select class="form-control select2" v-model="payment_mode" required name="payment_mode" style="padding: 0 40px 0 16px;" data-placeholder="Select..">
                        <option value="">Select account</option>
                        </option>
                        @foreach($payment_modes as $v)
                        <option value="{{$v}}">{{$v}}</option>
                        @endforeach
                    </select>
                    <i class="clear-input">
                        <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                    </i>
                </div>
            </div>
            @if($count>1)
            <div class="form-group basic">
                <div class="input-wrapper">
                    <label class="label mb-1" for="email2">Total count {{$count}}
                        <input type="checkbox" name="multiple_payment">
                    </label>
                    <table class="table table-bordered">
                        <tr>
                            <th>Amount</th>
                            <th>Date</th>
                        </tr>
                        @php $total=0; @endphp
                        @foreach($pending_list as $v)
                        <tr>
                            <td>{{$v->amount}}
                                <input type="hidden" name="multiple_bill_id[]" value="{{$v->transaction_id}}">
                            </td>
                            <td>{{$v->paid_date}}</td>
                        </tr>
                        @php $total=$total+$v->amount; @endphp
                        @endforeach
                        <tr>
                            <th>{{number_format($total,2)}}</th>
                            <th>Grand Total</th>
                        </tr>
                    </table>


                </div>
            </div>
            @endif

            <div class="form-group basic">
                <div class="input-wrapper">
                    <label class="label" for="password2">Amount</label>
                    <input type="number" readonly value="{{$detail->amount}}" class="form-control" pattern="[0-9]*" name="amount" autocomplete="off" placeholder="Amount">
                    <i class="clear-input">
                        <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                    </i>
                </div>
            </div>



            <div class="mt-2">
                <div class="row">
                    <div class="col-6">
                        <input type="hidden" value="{{$date}}" name="date" id="date">
                        <input type="hidden" name="bill_id" value="{{$detail->transaction_id}}">
                        <input type="hidden" name="employee_id" value="{{$detail->employee_id}}">
                        <button type="submit" class="btn btn-lg btn-primary btn-block">Confirm</button>
                    </div>

                </div>
            </div>
        </form>

    </div>
</div>
